<nav class="navbar">
    <div class="header-left d-flex align-items-center">
        <a href="{{ route('login') }}">
            <img src="{{ asset('images/logo.png') }}" alt="logo" style="height: 40px; width: 80px;" />
        </a>
    </div>
    <div class="search_bar">
        <form action="{{ route('search') }}" method="GET">
            <input type="text" id="searchInput" name="query" placeholder="Search...">
        </form>
    </div>
    <div class="navbar_content">
        <i class='bx bx-sun' id="darkLight"></i>
        @auth
            <a href="{{ route('notifications.index') }}">
                <i class='bx bx-bell'></i>
                @if(auth()->user()->unreadNotifications->count())
                    <span class="badge badge-danger">{{ auth()->user()->unreadNotifications->count() }}</span>
                @endif
            </a>
            <div class="dropdown">
                <img src="{{ asset('images/image.png') }}" alt="Profile" class="profile dropdown-toggle" id="profileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" />
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="profileDropdown">
                    <h3 class="mb-0" style="font-size: 14px;">{{ Auth::user()->name }}</h3>
                    <p class="mb-1" style="font-size: 12px; color: gray;">{{ Auth::user()->role->role_name ?? 'N/A' }}</p> <!-- Adjusted to access role_name -->
                    <a class="dropdown-item {{ Route::is('profile.edit') ? 'active' : '' }}" href="{{ route('profile.edit') }}">Profile</a>
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        @endauth
    </div>
</nav>
