<div class="sidebar">
    <div class="sidebar-header">
        <img src="<?php echo e(asset('images/icon.png')); ?>" alt="Logo" width="25" height="25" />
        <span style="font-size: 16px; font-weight: 600; color: white;">Sarpras</span>
    </div>
    <div class="sidebar-padding-top">
        <a href="<?php echo e(route('dashboard')); ?>" class="<?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>"><i data-feather="home"></i> Dashboard</a>
        <a href="<?php echo e(route('users.viewIndex')); ?>" class="<?php echo e(request()->routeIs('users.viewIndex') ? 'active' : ''); ?>"><i data-feather="users"></i> Users</a>
    </div>
    <div class="sidebar-scrollable">
        <div class="sidebar-section-label">Master Data</div>
        <div class="sidebar-section">
            <a href="<?php echo e(route('categories.viewIndex')); ?>" class="<?php echo e(request()->routeIs('categories.viewIndex') ? 'active' : ''); ?>"><i data-feather="bookmark"></i> Kategori</a>
            <a href="<?php echo e(route('warehouses.viewIndex')); ?>" class="<?php echo e(request()->routeIs('warehouses.viewIndex') ? 'active' : ''); ?>"><i data-feather="box"></i> Gudang</a>
        </div>
        <div class="sidebar-section-label">Manage Inventory</div>
        <div class="sidebar-section">
            <a href="<?php echo e(route('items.viewIndex')); ?>" class="<?php echo e(request()->routeIs('items.viewIndex') ? 'active' : ''); ?>"><i data-feather="file"></i> Item</a>
            <a href="<?php echo e(route('item-units.viewIndex')); ?>" class="<?php echo e(request()->routeIs('item-units.viewIndex') ? 'active' : ''); ?>"><i data-feather="file-text"></i> Item Units</a>
            <a href="<?php echo e(route('stock_movements.index')); ?>" class="<?php echo e(request()->routeIs('stock_movements.index') ? 'active' : ''); ?>">
                <i data-feather="folder-plus"></i> Stock Manage
            </a>
        </div>
        <div class="sidebar-section-label">Main Menu</div>
        <div class="sidebar-section" style="margin-bottom: 60px;">
            <a href="<?php echo e(route('borrow-requests.index')); ?>" class="<?php echo e(request()->routeIs('borrow-requests.index') ? 'active' : ''); ?>"><i data-feather="upload-cloud"></i> Borrow</a>
            <a href="<?php echo e(route('return-requests.index')); ?>" class="<?php echo e(request()->routeIs('return-requests.index') ? 'active' : ''); ?>"><i data-feather="download-cloud"></i> Return</a>
            <a href="#"><i data-feather="flag"></i> Report Damage</a>
        </div>
    </div>
    <div class="sidebar-spacer"></div>
    <div class="sidebar-padding-bottom">
        <a href="<?php echo e(route('activity-logs.index')); ?>" class="<?php echo e(request()->routeIs('activity-logs.index') ? 'active' : ''); ?>"><i data-feather="external-link"></i> Log Activity</a>
        <a href="#"><i data-feather="bell"></i> Notification</a>
    </div>
</div>
<?php /**PATH C:\laragon\www\Sarpras\resources\views/components/sidebar.blade.php ENDPATH**/ ?>