<?php $__env->startSection('title', 'Warehouses'); ?>
<?php $__env->startSection('icon', 'box'); ?>

<?php $__env->startSection('content'); ?>
    <h2 style="margin-bottom: 16px; font-size: 18px;">Daftar Gudang</h2>
    <div style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 16px;">
        <div>
            <a href="<?php echo e(route('warehouses.create')); ?>" style="background: #722be0; color: white; padding: 10px 18px; border-radius: 6px; text-decoration: none; font-weight: 500; font-size: 14px;">
                + Tambah Gudang
            </a>
        </div>
        <div>
            <!-- Add export buttons if needed -->
        </div>
    </div>
    <table style="width: 100%; border-collapse: collapse; color: white;" id="warehousesTable">
        <thead style="background-color: #722be0;">
            <tr>
                <th style="font-size: 15px; text-align: left; padding: 12px;">No</th>
                <th style="font-size: 15px; text-align: left; padding: 12px;">Nama</th>
                <th style="font-size: 15px; text-align: left; padding: 12px;">Lokasi</th>
                <th style="font-size: 15px; text-align: left; padding: 12px;">Kapasitas</th>
                <th style="font-size: 15px; text-align: left; padding: 12px;">Aksi</th>
            </tr>
        </thead>
        <tbody id="warehouse-table-body">
            <?php $__empty_1 = true; $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $warehouse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="padding: 12px;"><?php echo e($warehouses->firstItem() + $index); ?></td>
                    <td style="padding: 12px;"><?php echo e($warehouse->name); ?></td>
                    <td style="padding: 12px;"><?php echo e($warehouse->location); ?></td>
                    <td style="padding: 12px;"><?php echo e($warehouse->capacity); ?></td>
                    <td style="padding: 12px; display: flex; gap: 8px;">
                        <a href="<?php echo e(route('warehouses.show', $warehouse->id)); ?>"
                           style="display: inline-flex; align-items: center; gap: 6px; background-color: #FFF7E6; color: #D18616; font-weight: 600; font-size: 14px; padding: 6px 12px; border-radius: 8px; text-decoration: none;">
                            <i data-feather="eye" style="font-size: 16px;"></i>
                            Lihat
                        </a>
                        <a href="<?php echo e(route('warehouses.edit', $warehouse->id)); ?>"
                           style="display: inline-flex; align-items: center; gap: 6px; background-color: #F0F4FF; color: #4C6EF5; font-weight: 600; font-size: 14px; padding: 6px 12px; border-radius: 8px; text-decoration: none;">
                            <i data-feather="edit" style="font-size: 16px;"></i>
                            Edit
                        </a>
                        <form action="<?php echo e(route('warehouses.destroy', $warehouse->id)); ?>" method="POST" style="display: inline;">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" onclick="return confirm('Yakin ingin menghapus gudang ini?')"
                                    style="display: inline-flex; align-items: center; gap: 6px; background-color: #FFF0F0; color: #E03131; font-weight: 600; font-size: 14px; padding: 6px 12px; border-radius: 8px; border: none; cursor: pointer;">
                                <i data-feather="trash" style="font-size: 16px;"></i>
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" style="padding: 12px; text-align: center;">Tidak ada gudang ditemukan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div style="margin-top: 16px;" id="pagination-links">
        <?php echo e($warehouses->links()); ?>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\Sarpras\resources\views/warehouses/index.blade.php ENDPATH**/ ?>