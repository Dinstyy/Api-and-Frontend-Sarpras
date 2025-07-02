<!DOCTYPE html>
<html>
<head>
    <title>Laporan Peminjaman</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Laporan Peminjaman</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Peminjam</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
                <th>Item</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $borrowRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($request->id); ?></td>
                <td><?php echo e($request->borrow_date_expected); ?></td>
                <td><?php echo e($request->return_date_expected); ?></td>
                <td><?php echo e(ucfirst($request->status)); ?></td>
                <td>
                    <?php $__currentLoopData = $request->borrowDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo e($detail->itemUnit->item->name); ?> (<?php echo e($detail->quantity); ?>)<br>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</body>
</html>
<?php /**PATH C:\laragon\www\Sarpras\resources\views/borrow_requests/pdf.blade.php ENDPATH**/ ?>