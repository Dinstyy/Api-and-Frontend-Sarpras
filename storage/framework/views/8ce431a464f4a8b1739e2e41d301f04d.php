<?php $__env->startSection('title', 'Log Aktivitas'); ?>

<?php $__env->startSection('heading', 'Log Aktivitas'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-white">Log Aktivitas</h1>
        <div class="flex gap-3">
            <a href="<?php echo e(route('activity-logs.exportExcel')); ?>"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow transition">
               Ekspor Excel
            </a>
            <a href="<?php echo e(route('activity-logs.exportPdf')); ?>"
               class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded shadow transition">
               Ekspor PDF
            </a>
        </div>
    </div>

    <?php if($logs->isEmpty()): ?>
        <div class="bg-[#1e1e1e] text-center rounded-xl shadow border border-[#333] p-12">
            <div class="flex justify-center mb-4">
                <svg class="w-20 h-20 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <h3 class="text-xl text-white font-semibold">Belum Ada Log Aktivitas</h3>
            <p class="text-gray-400">Tidak ada data log aktivitas yang tercatat saat ini.</p>
        </div>
    <?php else: ?>
        <div class="bg-black border border-[#333] rounded-lg shadow overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-[#1a1a1a] text-violet-300 border-b border-[#333]">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold">No</th>
                        <th class="px-6 py-3 text-left font-semibold">Aksi</th>
                        <th class="px-6 py-3 text-left font-semibold">Deskripsi</th>
                        <th class="px-6 py-3 text-left font-semibold">Waktu</th>
                    </tr>
                </thead>
                <tbody class="text-white divide-y divide-[#333]">
                    <?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-[#1e1e1e] transition">
                            <td class="px-6 py-4"><?php echo e($loop->iteration + ($logs->currentPage() - 1) * $logs->perPage()); ?></td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    <?php if(str_contains(strtolower($log->action), 'create')): ?> bg-green-900 text-green-300
                                    <?php elseif(str_contains(strtolower($log->action), 'update')): ?> bg-blue-900 text-blue-300
                                    <?php elseif(str_contains(strtolower($log->action), 'delete')): ?> bg-red-900 text-red-300
                                    <?php else: ?> bg-gray-700 text-gray-300 <?php endif; ?>">
                                    <?php echo e($log->action); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4"><?php echo e($log->description); ?></td>
                            <td class="px-6 py-4"><?php echo e($log->created_at->format('d M Y H:i')); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>

            <?php if($logs->hasPages()): ?>
                <div class="px-6 py-3 border-t border-[#333] bg-[#111] text-white">
                    <?php echo e($logs->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\Sarpras\resources\views/activity-logs/index.blade.php ENDPATH**/ ?>