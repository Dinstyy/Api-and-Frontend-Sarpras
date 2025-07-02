<div class="sidebar">
    <div class="sidebar-header">
        <img src="{{ asset('images/icon.png') }}" alt="Logo" width="25" height="25" />
        <span style="font-size: 16px; font-weight: 600; color: white;">Sarpras</span>
    </div>
    <div class="sidebar-padding-top">
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><i data-feather="home"></i> Dashboard</a>
        <a href="{{ route('users.viewIndex') }}" class="{{ request()->routeIs('users.viewIndex') ? 'active' : '' }}"><i data-feather="users"></i> Users</a>
    </div>
    <div class="sidebar-scrollable">
        <div class="sidebar-section-label">Master Data</div>
        <div class="sidebar-section">
            <a href="{{ route('categories.viewIndex') }}" class="{{ request()->routeIs('categories.viewIndex') ? 'active' : '' }}"><i data-feather="bookmark"></i> Kategori</a>
            <a href="{{ route('warehouses.viewIndex') }}" class="{{ request()->routeIs('warehouses.viewIndex') ? 'active' : '' }}"><i data-feather="box"></i> Gudang</a>
        </div>
        <div class="sidebar-section-label">Manage Inventory</div>
        <div class="sidebar-section">
            <a href="{{ route('items.viewIndex') }}" class="{{ request()->routeIs('items.viewIndex') ? 'active' : '' }}"><i data-feather="file"></i> Item</a>
            <a href="{{ route('item-units.viewIndex') }}" class="{{ request()->routeIs('item-units.viewIndex') ? 'active' : '' }}"><i data-feather="file-text"></i> Item Units</a>
            <a href="{{ route('stock_movements.index') }}" class="{{ request()->routeIs('stock_movements.index') ? 'active' : '' }}">
                <i data-feather="folder-plus"></i> Stock Manage
            </a>
        </div>
        <div class="sidebar-section-label">Main Menu</div>
        <div class="sidebar-section" style="margin-bottom: 60px;">
            <a href="{{ route('borrow-requests.index') }}" class="{{ request()->routeIs('borrow-requests.index') ? 'active' : '' }}"><i data-feather="upload-cloud"></i> Borrow</a>
            <a href="{{ route('return-requests.index') }}" class="{{ request()->routeIs('return-requests.index') ? 'active' : '' }}"><i data-feather="download-cloud"></i> Return</a>
            <a href="#"><i data-feather="flag"></i> Report Damage</a>
        </div>
    </div>
    <div class="sidebar-spacer"></div>
    <div class="sidebar-padding-bottom">
        <a href="{{ route('activity-logs.index') }}" class="{{ request()->routeIs('activity-logs.index') ? 'active' : '' }}"><i data-feather="external-link"></i> Log Activity</a>
        <a href="#"><i data-feather="bell"></i> Notification</a>
    </div>
</div>
