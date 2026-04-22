<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SamlController;
use App\Http\Middleware\EnsureUserIsAdmin;

// Home page
Route::get('/', function () {
    // Get upcoming events: maximum of 5 events, or all events in the next month, whichever is fewer
    $upcomingEvents = \App\Models\Event::where('start', '>=', now())
        ->where('start', '<=', now()->addMonth())
        ->orderBy('start')
        ->take(5)
        ->get();

    return view('index', compact('upcomingEvents'));
})->name('index');

// 301 Redirects for old .php URLs
Route::permanentRedirect('/hspc.php', '/hspc');
Route::permanentRedirect('/icpc.php', '/icpc');
Route::permanentRedirect('/about.php', '/about');
Route::permanentRedirect('/donate.php', '/donate');
Route::permanentRedirect('/events.php', '/events');

// About page
Route::get('/about', function () {
    try {
        $year = \App\Models\Officer::orderBy('year', 'desc')->first()->year;
    } catch (\Exception $e) {
        $year = now()->year;
    }
    
    $past_years = \App\Models\Officer::where('year', '<', $year)
        ->whereHas('user', function($query) {
            $query->where('hidden', false);
        })
        ->distinct()
        ->pluck('year')
        ->sort()
        ->reverse()
        ->map(function($py) {
            return [
                'year' => $py . '-' . ($py + 1),
                'link' => '/about/' . $py
            ];
        })
        ->values();
    
    $officers = \App\Models\Officer::where('year', $year)
        ->whereHas('user', function($query) {
            $query->where('hidden', false);
        })
        ->orderBy('sort_order')
        ->orderBy('user_id')
        ->get();
    
    $academic_year = $year . '-' . ($year + 1);
    
    return view('about', compact('officers', 'academic_year', 'past_years'));
})->name('about');

// Past officers page
Route::get('/about/{year}', function ($year) {
    $officers = \App\Models\Officer::where('year', $year)
        ->whereHas('user', function($query) {
            $query->where('hidden', false);
        })
        ->orderBy('sort_order')
        ->orderBy('user_id')
        ->get();
    
    $academic_year = $year . '-' . ($year + 1);
    
    if ($officers->count() == 0) {
        abort(404);
    }
    
    return view('past_officers', compact('officers', 'academic_year'));
})->name('past_officers');

// Events page
Route::get('/events', function () {
    $events = \App\Models\Event::where('start', '>=', now())->orderBy('start')->get();
    return view('events', compact('events'));
})->name('events');

// Individual event page
Route::get('/events/{event}', function (\App\Models\Event $event) {
    return view('event_page', compact('event'));
})->name('event_page');

// Join event
Route::post('/events/{event}/join', function (\App\Models\Event $event) {
    if (!auth()->check()) {
        session()->flash('error', 'You must be logged in to join events.');
        return redirect()->route('event_page', $event);
    }
    
    if ($event->users->contains(auth()->user())) {
        session()->flash('error', 'You are already attending this event.');
    } else {
        $event->users()->attach(auth()->id());
        session()->flash('success', "You've been added to the attendees list for this event.");
    }
    
    return redirect()->route('event_page', $event);
})->name('events.join');

// Leave event
Route::delete('/events/{event}/leave', function (\App\Models\Event $event) {
    if (!auth()->check()) {
        session()->flash('error', 'You must be logged in to leave events.');
        return redirect()->route('event_page', $event);
    }
    
    if ($event->users->contains(auth()->user())) {
        $event->users()->detach(auth()->id());
        session()->flash('success', "You've been removed from the attendees list for this event.");
    } else {
        session()->flash('error', 'You are not attending this event.');
    }
    
    return redirect()->route('event_page', $event);
})->name('events.leave');

// ICPC page
Route::get('/icpc', function () {
    return view('icpc');
})->name('icpc');

// HSPC page
Route::get('/hspc', function () {
    return view('hspc');
})->name('hspc');

// Donate page
Route::get('/donate', function () {
    $venmo = config('app.venmo_link', 'https://venmo.com/acm-uva');
    $zelle = config('app.zelle_link', 'mailto:acm-officers@virginia.edu');
    return view('donate', compact('venmo', 'zelle'));
})->name('donate');

// User profile page
Route::get('/users/{username}', function ($username) {
    $user = \App\Models\User::where('username', $username)->first();
    
    if (!$user || (auth()->check() && !auth()->user()->can('view', $user))) {
        abort(404);
    }
    
    $events_attended = $user->events()->orderBy('start', 'desc')->get();
    $badges = $user->badges;
    
    return view('user_page', compact('user', 'events_attended', 'badges'));
})->name('user_page');

// User update route with policy authorization
Route::post('/users/{username}', function ($username) {
    $user = \App\Models\User::where('username', $username)->first();
    
    if (!$user) {
        abort(400);
    }

    if (!auth()->check()) {
        abort(401);
    }

    if (!auth()->user()->can('update', $user)) {
        abort(403);
    }
    
    return app(\App\Http\Controllers\UserController::class)->update(request(), $user);
})->name('user.update');

// Authentication routes
Route::get('/login', function () {
    $next = request('next', '/');
    return view('login_page', compact('next'));
})->name('login_page');

Route::post('/login', function () {
    $credentials = request()->only(['username', 'password']);
    $next = request('next', '/');
    
    if (auth()->attempt($credentials)) {
        session()->flash('success', 'You are now logged in!');
        return redirect($next);
    } else {
        session()->flash('error', 'Invalid username or password!');
        return redirect()->route('login_page', ['next' => $next]);
    }
})->name('login');

Route::get('/logout', function () {
    auth()->logout();
    session()->flash('success', 'You have been logged out!');
    return redirect('/');
})->name('logout_page');

// SAML Authentication routes
Route::get('/saml/login', [SamlController::class, 'login'])->name('saml.login');
Route::post('/saml/acs', [SamlController::class, 'acs'])->name('saml.acs');
Route::get('/saml/logout', [SamlController::class, 'logout'])->name('saml.logout');
Route::get('/saml/sls', [SamlController::class, 'sls'])->name('saml.sls');
Route::post('/saml/sls', [SamlController::class, 'sls']);
Route::get('/saml/metadata', [SamlController::class, 'metadata'])->name('saml.metadata');

// Admin routes
Route::middleware(['auth', EnsureUserIsAdmin::class])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    
    // Events management
    Route::get('/admin/events', [AdminController::class, 'events'])->name('admin.events');
    Route::post('/admin/events', [AdminController::class, 'storeEvent'])->name('admin.events.store');
    Route::get('/admin/events/{event}/edit', [AdminController::class, 'editEvent'])->name('admin.events.edit');
    Route::put('/admin/events/{event}', [AdminController::class, 'updateEvent'])->name('admin.events.update');
    Route::delete('/admin/events/{event}', [AdminController::class, 'deleteEvent'])->name('admin.events.destroy');
    
    // Users management
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.users.destroy');
    Route::post('/admin/users/{user}/badges', [AdminController::class, 'addBadgesToUser'])->name('admin.users.badges.add');
    Route::get('/admin/users/{user}/badges', [AdminController::class, 'getUserBadges'])->name('admin.users.badges.get');
    Route::delete('/admin/users/{user}/badges', [AdminController::class, 'removeBadgesFromUser'])->name('admin.users.badges.remove');
    Route::post('/admin/users/{user}/reset-password', [AdminController::class, 'resetUserPassword'])->name('admin.users.reset-password');
    
    // Officers management
    Route::get('/admin/officers', [AdminController::class, 'officers'])->name('admin.officers');
    Route::post('/admin/officers', [AdminController::class, 'storeOfficer'])->name('admin.officers.store');
    Route::get('/admin/officers/{officer}/edit', [AdminController::class, 'editOfficer'])->name('admin.officers.edit');
    Route::put('/admin/officers/{officer}', [AdminController::class, 'updateOfficer'])->name('admin.officers.update');
    Route::delete('/admin/officers/{officer}', [AdminController::class, 'deleteOfficer'])->name('admin.officers.destroy');
    
    // Badges management
    Route::get('/admin/badges', [AdminController::class, 'badges'])->name('admin.badges');
    Route::post('/admin/badges', [AdminController::class, 'storeBadge'])->name('admin.badges.store');
    Route::get('/admin/badges/{badge}/edit', [AdminController::class, 'editBadge'])->name('admin.badges.edit');
    Route::put('/admin/badges/{badge}', [AdminController::class, 'updateBadge'])->name('admin.badges.update');
    Route::delete('/admin/badges/{badge}', [AdminController::class, 'deleteBadge'])->name('admin.badges.destroy');

    // API Tokens management
    Route::get('/admin/tokens', [AdminController::class, 'tokens'])->name('admin.tokens');
    Route::post('/admin/tokens', [AdminController::class, 'createToken'])->name('admin.tokens.create');
    Route::delete('/admin/tokens/{token}', [AdminController::class, 'deleteToken'])->name('admin.tokens.destroy');

});

// POTY routes
Route::get('/poty/{year}', function ($year) {
    return view('poty', $year);
})->name('poty');