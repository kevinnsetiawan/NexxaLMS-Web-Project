<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'NexaLearn - Learn & Teach' }}</title>
    <!-- Custom LMS CSS System -->
    <link rel="stylesheet" href="{{ asset('css/lms.css') }}">
    <!-- Phosphor Icons for premium icon styles -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="glass-nav">
        <div class="container flex-between" style="height: 100%;">
            <a href="/" class="nav-brand">
                <i class="ph-bold ph-sparkles" style="color: var(--primary); font-size: 1.6rem;"></i>
                Nexa<span>Learn</span>
            </a>
            
            <ul class="nav-links">
                <li><a href="/" class="nav-link {{ Request::is('/') ? 'active' : '' }}"><i class="ph ph-compass"></i> Explore</a></li>
                @auth
                    @if(auth()->user()->isStudent())
                        <li><a href="{{ route('student.dashboard') }}" class="nav-link {{ Request::routeIs('student.dashboard') ? 'active' : '' }}"><i class="ph ph-layout"></i> Dashboard</a></li>
                    @elseif(auth()->user()->isInstructor())
                        <li><a href="{{ route('instructor.dashboard') }}" class="nav-link {{ Request::routeIs('instructor.dashboard') ? 'active' : '' }}"><i class="ph ph-chalkboard-teacher"></i> Instructor Hub</a></li>
                    @elseif(auth()->user()->isAdmin())
                        <li><a href="{{ route('admin.dashboard') }}" class="nav-link {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}"><i class="ph ph-chart-pie"></i> Admin Console</a></li>
                    @endif
                    
                    <!-- User Initials Avatar -->
                    <li class="flex-align gap-2" style="border-left: 1px solid var(--border-color); padding-left: 1.5rem;">
                        <div class="avatar-circle">
                            @php
                                $names = explode(' ', auth()->user()->name);
                                $initials = count($names) > 1 ? substr($names[0], 0, 1) . substr($names[1], 0, 1) : substr($names[0], 0, 2);
                                echo strtoupper($initials);
                            @endphp
                        </div>
                        <div style="text-align: left; line-height: 1.2;">
                            <div style="font-size: 0.85rem; font-weight: 600; color: var(--text-primary);">{{ auth()->user()->name }}</div>
                            <span class="badge badge-primary" style="font-size: 0.65rem; padding: 0.1rem 0.4rem;">{{ ucfirst(auth()->user()->role) }}</span>
                        </div>
                        <form action="{{ route('logout') }}" method="POST" style="margin-left: 0.5rem;">
                            @csrf
                            <button type="submit" class="btn btn-secondary btn-sm" title="Log Out" style="padding: 0.4rem; border-radius: 8px;">
                                <i class="ph ph-sign-out" style="font-size: 1.1rem; color: var(--danger);"></i>
                            </button>
                        </form>
                    </li>
                @else
                    <li><a href="{{ route('login') }}" class="btn btn-secondary btn-sm"><i class="ph ph-sign-in"></i> Sign In</a></li>
                    <li><a href="{{ route('register') }}" class="btn btn-primary btn-sm"><i class="ph ph-user-plus"></i> Join Free</a></li>
                @endauth
            </ul>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer style="background: rgba(8, 12, 24, 0.95); border-top: 1px solid var(--border-glass); padding: 2rem 0; margin-top: 5rem; text-align: center; font-size: 0.9rem; color: var(--text-muted);">
        <div class="container">
            <p>&copy; {{ date('Y') }} NexaLearn. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
