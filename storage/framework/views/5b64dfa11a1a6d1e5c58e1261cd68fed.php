<?php $__env->startSection('title', 'Item Details'); ?>
<?php $__env->startSection('icon', 'file'); ?>

<?php $__env->startSection('content'); ?>
    <div class="page-intro">
        <h2>Item Details</h2>
        <p>Tampilan detail inventori barang.</p>
    </div>

    <div class="content">
        <div style="width: 100%; max-width: 600px; background: #fff; padding: 24px; border-radius: 8px; border: 1px solid #e5e7eb; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h3 style="color: #1f2937;">Barang: <?php echo e($item->name); ?></h3>
                <div>
                    <a href="<?php echo e(route('items.edit', $item->id)); ?>" style="background: #6366f1; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; margin-right: 8px;">
                        <i data-feather="edit"></i> Edit
                    </a>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('delete-form-<?php echo e($item->id); ?>').submit();" style="background: #ef4444; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none;">
                        <i data-feather="trash-2"></i> Hapus
                    </a>
                    <form id="delete-form-<?php echo e($item->id); ?>" action="<?php echo e(route('items.destroy', $item->id)); ?>" method="POST" style="display: none;">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                    </form>
                </div>
            </div>

            <div style="margin-bottom: 16px;">
                <span style="display: inline-block; background: #9333ea; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px; text-transform: uppercase;">
                    <?php echo e($item->category ? $item->category->name : 'Uncategorized'); ?>

                </span>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 14px; color: #6b7280; margin-bottom: 4px;">Nama</label>
                <p style="font-size: 16px; color: #1f2937;"><?php echo e($item->name); ?></p>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 14px; color: #6b7280; margin-bottom: 4px;">Tipe</label>
                <p style="font-size: 16px; color: #1f2937;"><?php echo e($item->type === 'consumable' ? 'Consumable' : 'Non-Consumable'); ?></p>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 14px; color: #6b7280; margin-bottom: 4px;">Deskripsi</label>
                <p style="font-size: 14px; color: #4b5563;"><?php echo e($item->description ?? 'Tidak ada deskripsi tersedia'); ?></p>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 14px; color: #6b7280; margin-bottom: 4px;">Gambar</label>
                <?php if($item->image): ?>
                    <img src="<?php echo e($item->image); ?>" alt="<?php echo e($item->id); ?>" style="max-width: 100%; border-radius: 6px; border: 1px solid #e5e7eb;">
                <?php else: ?>
                    <p style="font-size: 14px; color: #6b7280;">Tidak ada gambar tersedia</p>
                <?php endif; ?>
            </div>

            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 24px;">
                <div style="background: #bfdbfe; color: #1e40af; padding: 8px 12px; border-radius: 12px; display: flex; align-items: center; gap: 6px;">
                    <span style="font-size: 14px;">📦</span>
                    <span>Total Unit: <?php echo e($item->itemUnits->count()); ?></span>
                </div>
                <div style="background: #f3e8ff; color: #6b21a8; padding: 8px 12px; border-radius: 12px; display: flex; align-items: center; gap: 6px;">
                    <span style="font-size: 14px;">🏬</span>
                    <span>Gudang: <?php echo e($item->itemUnits->count() > 0 ? $item->itemUnits->first()->warehouse->name ?? 'N/A' : 'N/A'); ?></span>
                </div>
            </div>

            <a href="<?php echo e(route('item-units.create', ['item_id' => $item->id])); ?>" style="background: #3b82f6; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 500; display: inline-block;">
                <i data-feather="plus"></i> Tambah Unit
            </a>
        </div>

        <!-- Unit Barang Table -->
        <div style="margin-top: 24px;">
            <div style="background: #fff; padding: 16px; border-radius: 8px; border: 1px solid #e5e7eb; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <h3 style="color: #1f2937; margin-bottom: 16px;">Unit Barang (<?php echo e($item->itemUnits->count()); ?> unit)</h3>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                            <th style="padding: 12px; text-align: left; color: #6b7280;">SKU</th>
                            <th style="padding: 12px; text-align: left; color: #6b7280;">Kondisi</th>
                            <th style="padding: 12px; text-align: left; color: #6b7280;">Status</th>
                            <th style="padding: 12px; text-align: left; color: #6b7280;">Lokasi</th>
                            <th style="padding: 12px; text-align: left; color: #6b7280;">Qty</th>
                            <th style="padding: 12px; text-align: left; color: #6b7280;">Kode QR</th>
                            <th style="padding: 12px; text-align: left; color: #6b7280;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $item->itemUnits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr style="border-bottom: 1px solid #e5e7eb;">
                                <td style="padding: 12px; color: #1f2937;"><?php echo e($unit->unit_code); ?></td>
                                <td style="padding: 12px; color: #dc2626;"><?php echo e($unit->condition); ?></td>
                                <td style="padding: 12px; color: #10b981;">
                                    <span style="background: #d1fae5; padding: 4px 8px; border-radius: 12px;"><?php echo e(ucfirst($unit->status)); ?></span>
                                </td>
                                <td style="padding: 12px; color: #1f2937;"><?php echo e($unit->warehouse->name ?? 'N/A'); ?></td>
                                <td style="padding: 12px; color: #1f2937;"><?php echo e($unit->quantity); ?></td>
<td style="padding: 12px; text-align: center;">
    <?php if($unit->qr_image_url): ?>
        <div class="bg-white p-1 rounded border inline-block">
            <img src="<?php echo e($unit->qr_image_url); ?>" alt="QR Code" style="width: 50px; height: 50px;">
        </div>
    <?php else: ?>
        <div class="bg-white p-1 rounded border inline-block">
            <span style="color: #6b7280; font-size: 12px;">
                <?php if($unit->qr_image && !$unit->qr_image_exists): ?>
                    QR file missing
                <?php else: ?>
                    No QR
                <?php endif; ?>
            </span>
        </div>
    <?php endif; ?>
</td>
                                <td style="padding: 12px;">
                                    <a href="<?php echo e(route('item-units.showView', $unit->unit_code)); ?>" style="background: #f59e0b; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; margin-right: 8px;">
                                        <i data-feather="eye"></i> Lihat
                                    </a>
                                    <a href="<?php echo e(route('item-units.edit', $unit->unit_code)); ?>" style="background: #6366f1; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; margin-right: 8px;">
                                        <i data-feather="edit"></i> Edit
                                    </a>
                                    <form action="<?php echo e(route('item-units.destroy', $unit->unit_code)); ?>" method="POST" style="display: inline;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" style="background: #ef4444; color: white; padding: 6px 12px; border: none; border-radius: 6px; cursor: pointer;">
                                            <i data-feather="trash-2"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" style="padding: 12px; text-align: center; color: #6b7280;">Tidak ada unit barang tersedia.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\Sarpras\resources\views/items/show.blade.php ENDPATH**/ ?>