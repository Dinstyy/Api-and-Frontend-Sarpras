<?php $__env->startSection('title', 'Detail Borrow Request'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-intro">
    <h2>Detail Permintaan Peminjaman #<?php echo e($borrowRequest->id); ?></h2>
    <p>View details of the borrow request.</p>
</div>

<div class="details-container">
    <div class="bg-white p-6 rounded shadow mb-6">
        <p class="mb-2"><strong>Peminjam:</strong> <?php echo e($borrowRequest->user->name ?? 'Unknown'); ?></p>
        <p class="mb-2"><strong>Tanggal Pinjam:</strong> <?php echo e($borrowRequest->borrow_date_expected->format('d/m/Y')); ?></p>
        <p class="mb-2"><strong>Tanggal Kembali:</strong> <?php echo e($borrowRequest->return_date_expected->format('d/m/Y')); ?></p>
        <p class="mb-2"><strong>Alasan:</strong> <?php echo e($borrowRequest->reason); ?></p>
        <p class="mb-2"><strong>Catatan:</strong> <?php echo e($borrowRequest->notes ?? '-'); ?></p>
        <p class="mb-2"><strong>Status:</strong> <?php echo e(ucfirst($borrowRequest->status)); ?></p>
        <?php if($borrowRequest->status == 'rejected'): ?>
            <p class="mb-2"><strong>Alasan Penolakan:</strong> <?php echo e($borrowRequest->rejection_reason ?? '-'); ?></p>
        <?php endif; ?>
        <p class="mb-2"><strong>Penanggung Jawab:</strong> <?php echo e($borrowRequest->handler ? $borrowRequest->handler->name : '-'); ?></p>
    </div>

    <h3 class="text-xl font-bold mb-4">Daftar Item Dipinjam</h3>
    <div class="overflow-x-auto">
        <table class="table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Kuantitas</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $borrowRequest->borrowDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($detail->itemUnit->item->name); ?></td>
                        <td><?php echo e($detail->quantity); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\Sarpras\resources\views/borrow_requests/show.blade.php ENDPATH**/ ?>