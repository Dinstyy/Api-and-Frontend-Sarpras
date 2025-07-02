<?php $__env->startSection('content'); ?>
<div class="content">
    <h2 style="margin-bottom: 16px; font-size: 18px;">Detail Pengguna</h2>
    <div style="background: #1a1a1a; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
        <p><strong>Nama:</strong> <?php echo e($user->name); ?></p>
        <p><strong>Username:</strong> <?php echo e($user->username ?? '-'); ?></p>
        <p><strong>Email:</strong> <?php echo e($user->email ?? '-'); ?></p>
        <p><strong>Kelas:</strong> <?php echo e($user->kelas ?? '-'); ?></p>
        <p><strong>Role:</strong> <?php echo e(ucfirst($user->role)); ?></p>
        <p><strong>Created At:</strong> <?php echo e($user->created_at->format('d/m/Y H:i')); ?></p>
        <p><strong>Updated At:</strong> <?php echo e($user->updated_at->format('d/m/Y H:i')); ?></p>
        <a href="<?php echo e(route('users.viewIndex')); ?>" style="background: #333; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none;">
            Kembali ke Daftar
        </a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\Sarpras\resources\views/users/show.blade.php ENDPATH**/ ?>