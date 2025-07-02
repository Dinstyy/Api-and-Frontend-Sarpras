<?php $__env->startSection('title', 'Return Requests'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-intro">
    <h2>Daftar Permintaan Pengembalian</h2>
    <p>Manage all return requests here.</p>
</div>

<div class="table-container">
    <div class="filter-form">
        <form class="flex flex-wrap gap-4">
            <input type="text" name="search" placeholder="Cari nama/kode..." value="<?php echo e(request('search')); ?>" class="search-input">
            <select name="status" class="border rounded px-3 py-2">
                <option value="">Semua Status</option>
                <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                <option value="approved" <?php echo e(request('status') == 'approved' ? 'selected' : ''); ?>>Disetujui</option>
                <option value="rejected" <?php echo e(request('status') == 'rejected' ? 'selected' : ''); ?>>Ditolak</option>
            </select>
            <input type="date" name="start_date" value="<?php echo e(request('start_date')); ?>" class="border rounded px-3 py-2">
            <input type="date" name="end_date" value="<?php echo e(request('end_date')); ?>" class="border rounded px-3 py-2">
            <button type="submit" class="filter-form button">Filter</button>
        </form>
    </div>

    <div class="action-buttons mb-4 flex gap-4">
        <a href="<?php echo e(route('return-requests.exportExcel')); ?>" class="bg-green-500 text-white px-4 py-2 rounded">Export Excel</a>
        <a href="<?php echo e(route('return-requests.exportPdf')); ?>" class="bg-red-500 text-white px-4 py-2 rounded">Export PDF</a>
    </div>

    <div class="overflow-x-auto">
        <table class="table">
            <thead>
                <tr>
                    <th>Peminjam</th>
                    <th>Tanggal Pengembalian</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($request->user->name ?? 'Unknown'); ?></td>
                        <td><?php echo e($request->created_at->format('d/m/Y')); ?></td>
                        <td><?php echo e(ucfirst($request->status)); ?></td>
                        <td class="action-buttons">
                            <a href="<?php echo e(route('return-requests.show', $request->id)); ?>" class="text-blue-500 hover:underline">Detail</a>
                            <?php if($request->status == 'pending'): ?>
                                <form action="<?php echo e(route('return-requests.approve', $request->id)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <button type="submit" class="text-green-500 hover:underline">Setujui</button>
                                </form>
                                <form action="<?php echo e(route('return-requests.reject', $request->id)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <input type="text" name="rejection_reason" placeholder="Alasan penolakan" class="border rounded px-2 py-1" required>
                                    <button type="submit" class="text-red-500 hover:underline">Tolak</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4"><?php echo e($requests->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\Sarpras\resources\views/return_requests/index.blade.php ENDPATH**/ ?>