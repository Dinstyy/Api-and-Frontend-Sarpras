<div class="header">
    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}" class="breadcrumb-link dashboard-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i data-feather="home"></i>
            <span>Dashboard</span>
        </a>
        @if(!request()->routeIs('dashboard'))
            <span class="breadcrumb-separator">></span>
            <div class="breadcrumb-current">
                <i data-feather="@yield('icon', 'home')"></i>
                <span>@yield('title', 'Dashboard')</span>
            </div>
        @endif
    </div>

    <div class="header-right" style="display: flex; align-items: center; gap: 20px;">
        {{-- User dropdown --}}
        <div class="header-user">
            <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            <span class="user-name">{{ Auth::user()->name }}</span>
            <i data-feather="chevron-down" class="dropdown-icon"></i>
        </div>

        <div class="dropdown-menu" id="userDropdown">
            <a href="#" class="dropdown-item"><i data-feather="user"></i> Profil</a>
            <a href="#" class="dropdown-item"><i data-feather="settings"></i> Pengaturan</a>
            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                @csrf
                <button type="submit" class="dropdown-item logout"><i data-feather="log-out"></i> Keluar</button>
            </form>
        </div>
    </div>
</div>
