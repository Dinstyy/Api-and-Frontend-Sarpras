<div class="header">
    <div class="breadcrumb">
        <a href="<?php echo e(route('dashboard')); ?>" class="breadcrumb-link dashboard-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
            <i data-feather="home"></i>
            <span>Dashboard</span>
        </a>
        <?php if(!request()->routeIs('dashboard')): ?>
            <span class="breadcrumb-separator">></span>
            <div class="breadcrumb-current">
                <i data-feather="<?php echo $__env->yieldContent('icon', 'home'); ?>"></i>
                <span><?php echo $__env->yieldContent('title', 'Dashboard'); ?></span>
            </div>
        <?php endif; ?>
    </div>

    <div class="header-right" style="display: flex; align-items: center; gap: 20px;">
        
        <div class="header-user">
            <div class="user-avatar"><?php echo e(strtoupper(substr(Auth::user()->name, 0, 1))); ?></div>
            <span class="user-name"><?php echo e(Auth::user()->name); ?></span>
            <i data-feather="chevron-down" class="dropdown-icon"></i>
        </div>

        <div class="dropdown-menu" id="userDropdown">
            <a href="#" class="dropdown-item"><i data-feather="user"></i> Profil</a>
            <a href="#" class="dropdown-item"><i data-feather="settings"></i> Pengaturan</a>
            <form method="POST" action="<?php echo e(route('logout')); ?>" style="margin: 0;">
                <?php echo csrf_field(); ?>
                <button type="submit" class="dropdown-item logout"><i data-feather="log-out"></i> Keluar</button>
            </form>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\Sarpras\resources\views/components/header.blade.php ENDPATH**/ ?>