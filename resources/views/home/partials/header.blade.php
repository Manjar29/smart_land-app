<header class="site-header">
    <div class="container header-wrap">
        <div class="logo">
            <div class="logo-mark">SD</div>
            <div>
                Smart Digital Land Record<br>
                <small style="color: var(--ink-soft); font-weight: 600;">Khajna & Mutation Management</small>
            </div>
        </div>

        <nav class="nav-links">
            <a href="#services">Services</a>
            <a href="#notices">Notices</a>
            <a href="#map">Map</a>
            <a href="#contact">Contact</a>
            <a href="{{ route('khajna.apply') }}">Khajna</a>
            <a href="{{ route('mutation.apply') }}">Mutation</a>
            <a href="{{ route('khajna.track') }}">Track Khajna</a>
            <a href="{{ route('mutation.track') }}">Track Mutation</a>
            <a href="{{ route('district-admin.login') }}">Admin</a>
            @if (Route::has('login'))
                @auth
                    <a class="btn btn-light" href="{{ url('/dashboard') }}">Dashboard</a>
                @else
                    <a class="btn btn-light" href="{{ route('login') }}">Login</a>
                    @if (Route::has('register'))
                        <a class="btn btn-brand" href="{{ route('register') }}">Register</a>
                    @endif
                @endauth
            @endif
        </nav>
    </div>
</header>
