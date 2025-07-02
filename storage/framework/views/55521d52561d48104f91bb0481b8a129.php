<?php $__env->startSection('title', 'Buat Permintaan Peminjaman'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="page-intro">
        <h2 class="text-2xl font-bold mb-4">Buat Permintaan Peminjaman</h2>
    </div>

    <?php if(session('error')): ?>
        <div class="bg-red-500 text-white p-4 rounded mb-4">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>
    <?php if(session('success')): ?>
        <div class="bg-green-500 text-white p-4 rounded mb-4">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="form-container">
        <form action="<?php echo e(route('borrow-requests.store')); ?>" method="POST" id="borrow-form">
            <?php echo csrf_field(); ?>
            <div class="mb-4">
                <label class="block text-sm font-medium text-white mb-1">Peminjam</label>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-white mb-1">Nama Peminjam</label>
                <input type="text" name="borrower_name" id="borrower_name" value="<?php echo e(old('borrower_name')); ?>" class="w-full border rounded px-3 py-2 bg-[#2c2c2c] text-white" placeholder="Masukkan nama peminjam jika eksternal">
                <?php $__errorArgs = ['borrower_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-red-500 text-sm"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-white mb-1">Tanggal Pinjam</label>
                <input type="date" name="borrow_date_expected" class="w-full border rounded px-3 py-2 bg-[#2c2c2c] text-white" value="<?php echo e(old('borrow_date_expected')); ?>">
                <?php $__errorArgs = ['borrow_date_expected'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-red-500 text-sm"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-white mb-1">Tanggal Kembali</label>
                <input type="date" name="return_date_expected" class="w-full border rounded px-3 py-2 bg-[#2c2c2c] text-white" value="<?php echo e(old('return_date_expected')); ?>">
                <?php $__errorArgs = ['return_date_expected'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-red-500 text-sm"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-white mb-1">Alasan</label>
                <textarea name="reason" class="w-full border rounded px-3 py-2 bg-[#2c2c2c] text-white"><?php echo e(old('reason')); ?></textarea>
                <?php $__errorArgs = ['reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-red-500 text-sm"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-white mb-1">Catatan (Opsional)</label>
                <textarea name="notes" class="w-full border rounded px-3 py-2 bg-[#2c2c2c] text-white"><?php echo e(old('notes')); ?></textarea>
                <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-red-500 text-sm"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-white mb-1">Item yang Dipinjam</label>
                <div id="item-rows">
                    <div class="item-row">
                        <select name="items[0][item_unit_id]" class="w-full border rounded px-3 py-2 bg-[#2c2c2c] text-white">
                            <option value="">Pilih Item</option>
                            <?php $__currentLoopData = $itemUnits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemUnit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($itemUnit->id); ?>"><?php echo e($itemUnit->item->name); ?> (<?php echo e($itemUnit->item->type === 'consumable' ? 'Stok: ' . $itemUnit->quantity : 'Status: ' . $itemUnit->status); ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <input type="number" name="items[0][quantity]" min="1" value="1" class="w-full border rounded px-3 py-2 bg-[#2c2c2c] text-white" placeholder="Kuantitas">
                        <button type="button" class="remove-item bg-red-500 text-white px-2 py-1 rounded">-</button>
                    </div>
                </div>
                <button type="button" id="add-item" class="bg-green-500 text-white px-4 py-2 rounded mt-2">+ Tambah Item</button>
                <?php $__errorArgs = ['items'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-red-500 text-sm"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Simpan</button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('#user_id').select2({
            placeholder: "Pilih Peminjam (Ketik untuk mencari)",
            allowClear: true,
            width: '100%'
        });

        // Handle user_id change
        $('#user_id').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const userName = selectedOption.data('name');
            const borrowerNameInput = $('#borrower_name');

            if ($(this).val()) {
                borrowerNameInput.val(userName || '');
                borrowerNameInput.prop('disabled', true);
            } else {
                borrowerNameInput.val('');
                borrowerNameInput.prop('disabled', false);
            }
        });

        // Trigger change on page load
        $('#user_id').trigger('change');

        // Form validation
        $('#borrow-form').on('submit', function(e) {
            const userId = $('#user_id').val();
            const borrowerName = $('#borrower_name').val().trim();

            if (!userId && !borrowerName) {
                e.preventDefault();
                alert('Nama Peminjam wajib diisi.');
                $('#borrower_name').focus();
            }
        });

        // Add item row
        let itemIndex = 1;
        $('#add-item').click(function() {
            const itemRow = `
                <div class="item-row">
                    <select name="items[${itemIndex}][item_unit_id]" class="w-full border rounded px-3 py-2 bg-[#2c2c2c] text-white">
                        <option value="">Pilih Item</option>
                        <?php $__currentLoopData = $itemUnits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemUnit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($itemUnit->id); ?>"><?php echo e($itemUnit->item->name); ?> (<?php echo e($itemUnit->item->type === 'consumable' ? 'Stok: ' . $itemUnit->quantity : 'Status: ' . $itemUnit->status); ?>)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <input type="number" name="items[${itemIndex}][quantity]" min="1" value="1" class="w-full border rounded px-3 py-2 bg-[#2c2c2c] text-white" placeholder="Kuantitas">
                    <button type="button" class="remove-item bg-red-500 text-white px-2 py-1 rounded">-</button>
                </div>
            `;
            $('#item-rows').append(itemRow);
            itemIndex++;
        });

        // Remove item row
        $(document).on('click', '.remove-item', function() {
            if ($('.item-row').length > 1) {
                $(this).closest('.item-row').remove();
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\Sarpras\resources\views/borrow_requests/create.blade.php ENDPATH**/ ?>