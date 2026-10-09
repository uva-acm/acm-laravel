<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ACM @ UVA')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="/favicon.ico">
    @stack('styles')
</head>
<body>
    <div class="drawer">
        <input id="mobile-drawer" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content flex flex-col min-h-screen">
            <!-- Navigation -->
            <nav class="navbar bg-base-100 shadow-md sticky top-0 z-40 w-full">
                    <div class="navbar-start">
                        <!-- Mobile menu button -->
                        <label for="mobile-drawer" class="btn btn-ghost md:hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                        </label>
                        <a href="{{ route('index') }}" class="btn btn-ghost normal-case text-xl flex items-center gap-2">
                            <img src="/acm.svg" style="height: 2rem;" alt="ACM @ UVA Logo"/>
                            ACM @ UVA
                        </a>
                    </div>
                    <div class="navbar-center hidden md:flex">
                        <ul class="menu menu-horizontal px-1">
                            <li><a href="{{ route('about') }}">About</a></li>
                            <li><a href="{{ route('events') }}">Events</a></li>
                            <li><a href="{{ route('icpc') }}">ICPC</a></li>
                            <li><a href="{{ route('hspc') }}">HSPC</a></li>
                            <li><a href="{{ route('donate') }}">Donate</a></li>
                        </ul>
                    </div>
                    <div class="navbar-end">
                        @auth
                            <div class="dropdown dropdown-end mr-4">
                                <label tabindex="0" class="btn btn-ghost btn-circle avatar {{ auth()->user()->image ? '' : 'avatar-placeholder' }}">
                                    @if(auth()->user()->image)
                                        <div class="w-8 h-8 rounded-full">
                                            <img src="{{ Storage::url(auth()->user()->image) }}" alt="{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}" class="w-full h-full object-cover rounded-full">
                                        </div>
                                    @else
                                        <div class="bg-secondary text-secondary-content rounded-full w-8 h-8 flex items-center justify-center">
                                            @if(auth()->user()->first_name && auth()->user()->last_name)
                                                <span class="text-xs">{{ substr(auth()->user()->first_name, 0, 1) }}{{ substr(auth()->user()->last_name, 0, 1) }}</span>
                                            @elseif(auth()->user()->first_name)
                                                <span class="text-xs">{{ substr(auth()->user()->first_name, 0, 1) }}</span>
                                            @else
                                                <span class="text-xs">{{ substr(auth()->user()->username, 0, 1) }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </label>
                                <ul tabindex="0" class="menu dropdown-content mt-3 z-[1] p-2 shadow-md bg-base-100 rounded-box w-52">
                                    @if(!auth()->user()->hidden)
                                        <li><a href="{{ route('user_page', auth()->user()->username) }}"><i class="fa fa-user mr-2"></i>Profile</a></li>
                                    @endif
                                    @if(auth()->user()->is_admin)
                                        <li><a href="{{ route('admin.index') }}"><i class="fa fa-cog mr-2"></i>Admin</a></li>
                                    @endif
                                    <li><a href="{{ route('logout_page') }}"><i class="fa fa-sign-out-alt mr-2"></i>Log out</a></li>
                                </ul>
                            </div>
                        @else
                            <a href="{{ route('login_page') }}?next={{ request()->path() }}" class="btn btn-ghost">Log in</a>
                        @endauth
                    </div>
            </nav>

    <!-- Main Content -->
    <main class="flex-1">
        <div class="container mx-auto py-8 px-4 prose">
        @if(session('success'))
            <div class="alert alert-success mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
        </div>
    </main>

            <!-- Footer -->
            <footer class="mt-auto">
                <div class="container mx-auto">
                    <div class="text-center py-3 border-t border-secondary">
                        <small class="text-secondary mb-3 block">Disclaimer: Although this organization has members who are University of Virginia students and may have University employees associated or engaged in its activities and affairs, the organization is not a part of or an agency of the University. It is a separate and independent organization, which is responsible for and manages its own activities and affairs. The University does not direct, supervise or control the organization and is not responsible for the organization's contracts, acts or omissions.</small>
                        <small class="text-secondary">© {{ date('Y') }} Association for Computing Machinery at the University of Virginia. All Rights Reserved.</small>
                    </div>
                </div>
            </footer>
        </div>
        <div class="drawer-side z-30">
            <label for="mobile-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
            <ul class="menu p-4 w-80 min-h-full bg-base-100 text-base-content text-lg font-semibold pt-20">
                <li><a href="{{ route('about') }}" class="py-2">About</a></li>
                <li><a href="{{ route('events') }}" class="py-2">Events</a></li>
                <li><a href="{{ route('icpc') }}" class="py-2">ICPC</a></li>
                <li><a href="{{ route('hspc') }}" class="py-2">HSPC</a></li>
                <li><a href="{{ route('donate') }}" class="py-2">Donate</a></li>
            </ul>
        </div>
    </div>

    @stack('scripts')
</body>
</html> 