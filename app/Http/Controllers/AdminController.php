<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
use App\Models\Officer;
use App\Models\Badge;
use App\Helpers\FileHelper;


class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'events' => Event::count(),
            'officers' => Officer::count(),
            'badges' => Badge::count(),
        ];
        
        return view('admin.index', compact('stats'));
    }

    // Events management
    public function events()
    {
        $events = Event::orderBy('start')->get();
        return view('admin.events', compact('events'));
    }

    public function storeEvent(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'start' => 'required|date',
            'end' => 'nullable|date|after:start',
            'location' => 'required|string|max:30',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:' . FileHelper::getMaxUploadSizeKB()
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        Event::create($validated);
        return redirect()->route('admin.events')->with('success', 'Event created successfully!');
    }

    public function updateEvent(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'start' => 'required|date',
            'end' => 'nullable|date|after:start',
            'location' => 'required|string|max:30',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:' . FileHelper::getMaxUploadSizeKB()
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $event->update($validated);
        return redirect()->route('admin.events')->with('success', 'Event updated successfully!');
    }

    public function deleteEvent(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.events')->with('success', 'Event deleted successfully!');
    }

    // Users management
    public function users()
    {
        $users = User::with('badges')->get();
        $badges = Badge::orderBy('name')->get();
        return view('admin.users', compact('users', 'badges'));
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'is_admin' => 'boolean',
            'hidden' => 'boolean',
            'bio' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:' . FileHelper::getMaxUploadSizeKB()
        ]);

        $validated['password'] = bcrypt($validated['password']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('users', 'public');
        }

        User::create($validated);
        return redirect()->route('admin.users')->with('success', 'User created successfully!');
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'is_admin' => 'boolean',
            'hidden' => 'boolean',
            'bio' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:' . FileHelper::getMaxUploadSizeKB()
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('users', 'public');
        }

        $user->update($validated);
        return redirect()->route('admin.users')->with('success', 'User updated successfully!');
    }

    public function deleteUser(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted successfully!');
    }

    public function addBadgesToUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'badge_ids' => 'required|array',
            'badge_ids.*' => 'exists:badges,id'
        ]);

        // Get badges that the user doesn't already have
        $existingBadgeIds = $user->badges->pluck('id')->toArray();
        $newBadgeIds = array_diff($validated['badge_ids'], $existingBadgeIds);

        if (empty($newBadgeIds)) {
            return redirect()->route('admin.users')->with('error', 'User already has all selected badges!');
        }

        // Attach new badges
        $user->badges()->attach($newBadgeIds);

        $badgeCount = count($newBadgeIds);
        $message = $badgeCount === 1 ? 'Badge added successfully!' : "{$badgeCount} badges added successfully!";

        return redirect()->route('admin.users')->with('success', $message);
    }

    public function getUserBadges(User $user)
    {
        return response()->json([
            'badges' => $user->badges->map(function($badge) {
                return [
                    'id' => $badge->id,
                    'name' => $badge->name,
                    'color' => $badge->color,
                    'description' => $badge->description,
                ];
            })
        ]);
    }

    public function removeBadgesFromUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'badge_ids' => 'required|array',
            'badge_ids.*' => 'exists:badges,id'
        ]);

        // Detach selected badges
        $user->badges()->detach($validated['badge_ids']);

        $badgeCount = count($validated['badge_ids']);
        $message = $badgeCount === 1 ? 'Badge removed successfully!' : "{$badgeCount} badges removed successfully!";

        return redirect()->route('admin.users')->with('success', $message);
    }

    public function resetUserPassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => bcrypt($validated['password'])
        ]);

        return redirect()->route('admin.users')->with('success', 'Password reset successfully!');
    }

    // Officers management
    public function officers()
    {
        $officers = Officer::with('user')->orderBy('year', 'desc')->orderBy('sort_order')->get();
        $users = User::where('hidden', false)->get();
        return view('admin.officers', compact('officers', 'users'));
    }

    public function storeOfficer(Request $request)
    {
        $validated = $request->validate([
            'position' => 'required|string|max:30',
            'sort_order' => 'required|integer',
            'year' => 'required|integer',
            'user_id' => 'required|exists:users,id'
        ]);

        Officer::create($validated);
        return redirect()->route('admin.officers')->with('success', 'Officer created successfully!');
    }

    public function editOfficer(Officer $officer)
    {
        return response()->json([
            'position' => $officer->position,
            'year' => $officer->year,
            'sort_order' => $officer->sort_order,
            'user_id' => $officer->user_id,
        ]);
    }

    public function editUser(User $user)
    {
        return response()->json([
            'username' => $user->username,
            'email' => $user->email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'bio' => $user->bio,
            'is_admin' => $user->is_admin,
            'hidden' => $user->hidden,
        ]);
    }

    public function editEvent(Event $event)
    {
        return response()->json([
            'name' => $event->name,
            'location' => $event->location,
            'description' => $event->description,
            'start' => $event->start->format('Y-m-d\TH:i'),
            'end' => $event->end ? $event->end->format('Y-m-d\TH:i') : '',
        ]);
    }

    public function editBadge(Badge $badge)
    {
        return response()->json([
            'name' => $badge->name,
            'description' => $badge->description,
            'color' => $badge->color,
        ]);
    }

    public function updateOfficer(Request $request, Officer $officer)
    {
        $validated = $request->validate([
            'position' => 'required|string|max:30',
            'sort_order' => 'integer',
            'year' => 'required|integer',
            'user_id' => 'required|exists:users,id'
        ]);

        $officer->update($validated);
        return redirect()->route('admin.officers')->with('success', 'Officer updated successfully!');
    }

    public function deleteOfficer(Officer $officer)
    {
        $officer->delete();
        return redirect()->route('admin.officers')->with('success', 'Officer deleted successfully!');
    }

    // Badges management
    public function badges()
    {
        $badges = Badge::all();
        return view('admin.badges', compact('badges'));
    }

    public function storeBadge(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:30|unique:badges',
            'description' => 'nullable|string',
            'color' => 'required|string|in:blue,indigo,purple,pink,red,orange,yellow,green,teal,cyan,black,white,gray,gray-dark'
        ]);

        Badge::create($validated);
        return redirect()->route('admin.badges')->with('success', 'Badge created successfully!');
    }

    public function updateBadge(Request $request, Badge $badge)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:30|unique:badges,name,' . $badge->id,
            'description' => 'nullable|string',
            'color' => 'required|string|in:blue,indigo,purple,pink,red,orange,yellow,green,teal,cyan,black,white,gray,gray-dark'
        ]);

        $badge->update($validated);
        return redirect()->route('admin.badges')->with('success', 'Badge updated successfully!');
    }

    public function deleteBadge(Badge $badge)
    {
        $badge->delete();
        return redirect()->route('admin.badges')->with('success', 'Badge deleted successfully!');
    }

    // API endpoints for users
    public function apiGetUsers(Request $request)
    {
        $users = User::with('badges')->get();

        return response()->json([
            'success' => true,
            'data' => $users->map(function($user) {
                return [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'is_admin' => $user->is_admin,
                    'hidden' => $user->hidden,
                    'bio' => $user->bio,
                    'image' => $user->image,
                    'badges' => $user->badges->map(function($badge) {
                        return [
                            'id' => $badge->id,
                            'name' => $badge->name,
                            'color' => $badge->color,
                            'description' => $badge->description,
                        ];
                    }),
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ];
            })
        ]);
    }

    public function apiCreateUser(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'is_admin' => 'boolean',
            'hidden' => 'boolean',
            'bio' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:' . FileHelper::getMaxUploadSizeKB()
        ]);

        $validated['password'] = bcrypt($validated['password']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('users', 'public');
        }

        $user = User::create($validated);
        $user->load('badges');

        return response()->json([
            'success' => true,
            'message' => 'User created successfully!',
            'data' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'is_admin' => $user->is_admin,
                'hidden' => $user->hidden,
                'bio' => $user->bio,
                'image' => $user->image,
                'badges' => $user->badges,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]
        ], 201);
    }

    // API endpoints for officers
    public function apiGetOfficers(Request $request)
    {
        $officers = Officer::with('user')->orderBy('year', 'desc')->orderBy('sort_order')->get();

        return response()->json([
            'success' => true,
            'data' => $officers->map(function($officer) {
                return [
                    'id' => $officer->id,
                    'position' => $officer->position,
                    'year' => $officer->year,
                    'sort_order' => $officer->sort_order,
                    'user_id' => $officer->user_id,
                    'user' => [
                        'id' => $officer->user->id,
                        'username' => $officer->user->username,
                        'email' => $officer->user->email,
                        'first_name' => $officer->user->first_name,
                        'last_name' => $officer->user->last_name,
                        'bio' => $officer->user->bio,
                        'image' => $officer->user->image,
                        'hidden' => $officer->user->hidden,
                    ],
                    'created_at' => $officer->created_at,
                    'updated_at' => $officer->updated_at,
                ];
            })
        ]);
    }

    public function apiCreateOfficer(Request $request)
    {
        $validated = $request->validate([
            'position' => 'required|string|max:30',
            'sort_order' => 'required|integer',
            'year' => 'required|integer',
            'user_id' => 'required|exists:users,id'
        ]);

        $officer = Officer::create($validated);
        $officer->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Officer created successfully!',
            'data' => [
                'id' => $officer->id,
                'position' => $officer->position,
                'year' => $officer->year,
                'sort_order' => $officer->sort_order,
                'user_id' => $officer->user_id,
                'user' => [
                    'id' => $officer->user->id,
                    'username' => $officer->user->username,
                    'email' => $officer->user->email,
                    'first_name' => $officer->user->first_name,
                    'last_name' => $officer->user->last_name,
                    'bio' => $officer->user->bio,
                    'image' => $officer->user->image,
                    'hidden' => $officer->user->hidden,
                ],
                'created_at' => $officer->created_at,
                'updated_at' => $officer->updated_at,
            ]
        ], 201);
    }

    public function apiDeleteAllOfficers()
    {
        \Log::info('apiDeleteAllOfficers called');

        $count = Officer::count();
        \Log::info("Officer count before delete: {$count}");

        // Use delete instead of truncate to ensure it works with foreign keys
        Officer::query()->delete();

        $afterCount = Officer::count();
        \Log::info("Officer count after delete: {$afterCount}");

        return response()->json([
            'success' => true,
            'message' => "All officers deleted successfully! ({$count} records removed)",
            'deleted_count' => $count
        ]);
    }

    // API Tokens management
    public function tokens()
    {
        $users = User::orderBy('first_name')->orderBy('last_name')->get();
        $tokens = \Laravel\Sanctum\PersonalAccessToken::with('tokenable')
            ->whereHasMorph('tokenable', [User::class])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.tokens', compact('tokens', 'users'));
    }

    public function createToken(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'abilities' => 'nullable|array',
        ]);

        $user = User::findOrFail($validated['user_id']);
        $abilities = $validated['abilities'] ?? ['*'];

        $token = $user->createToken($validated['name'], $abilities);

        // Store the plain text token in the session to display it once
        session()->flash('new_token', $token->plainTextToken);
        session()->flash('success', 'API token created successfully! Make sure to copy it now, as it will not be shown again.');

        return redirect()->route('admin.tokens');
    }

    public function deleteToken($tokenId)
    {
        $token = \Laravel\Sanctum\PersonalAccessToken::findOrFail($tokenId);
        $token->delete();

        return redirect()->route('admin.tokens')->with('success', 'Token deleted successfully!');
    }


} 