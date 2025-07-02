<?php $__env->startSection('title', 'Create Item Unit'); ?>
<?php $__env->startSection('icon', 'file-text'); ?>

<?php $__env->startSection('content'); ?>
    <div class="page-intro">
        <h2>Create Item Unit</h2>
        <p>Add a new item unit to your inventory.</p>
    </div>

    <div class="content">
        <div style="width: 100%; background: #111; padding: 24px; border-radius: 8px;">
            <form action="<?php echo e(route('item-units.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div>
                        <label style="font-size: 14px; color: #ccc; margin-bottom: 8px; display: block;">Merk</label>
                        <input type="text" name="merk" value="<?php echo e(old('merk')); ?>" style="width: 100%; padding: 10px; background: #2c2c2c; border: none; color: white; border-radius: 6px; font-size: 14px;" placeholder="Enter merk">
                        <?php $__errorArgs = ['merk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span style="color: #e57373; font-size: 12px;"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div>
                        <label style="font-size: 14px; color: #ccc; margin-bottom: 8px; display: block;">Condition</label>
                        <select name="condition" style="width: 100%; padding: 10px; background: #2c2c2c; border: none; color: white; border-radius: 6px; font-size: 14px;">
                            <option value="">Select condition</option>
                            <option value="Good" <?php echo e(old('condition') == 'Good' ? 'selected' : ''); ?>>Good</option>
                            <option value="Broken" <?php echo e(old('condition') == 'Broken' ? 'selected' : ''); ?>>Broken</option>
                            <option value="Needs Improvement" <?php echo e(old('condition') == 'Needs Improvement' ? 'selected' : ''); ?>>Needs Improvement</option>
                        </select>
                        <?php $__errorArgs = ['condition'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span style="color: #e57373; font-size: 12px;"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div>
                        <label style="font-size: 14px; color: #ccc; margin-bottom: 8px; display: block;">Diperoleh Dari</label>
                        <input type="text" name="diperoleh_dari" value="<?php echo e(old('diperoleh_dari')); ?>" style="width: 100%; padding: 10px; background: #2c2c2c; border: none; color: white; border-radius: 6px; font-size: 14px;" placeholder="Enter source">
                        <?php $__errorArgs = ['diperoleh_dari'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span style="color: #e57373; font-size: 12px;"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div>
                        <label style="font-size: 14px; color: #ccc; margin-bottom: 8px; display: block;">Diperoleh Tanggal</label>
                        <input type="date" name="diperoleh_tanggal" value="<?php echo e(old('diperoleh_tanggal')); ?>" style="width: 100%; padding: 10px; background: #2c2c2c; border: none; color: white; border-radius: 6px; font-size: 14px;">
                        <?php $__errorArgs = ['diperoleh_tanggal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span style="color: #e57373; font-size: 12px;"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div>
                        <label style="font-size: 14px; color: #ccc; margin-bottom: 8px; display: block;">Quantity</label>
                        <input type="number" name="quantity" value="<?php echo e(old('quantity', 1)); ?>" min="1" style="width: 100%; padding: 10px; background: #2c2c2c; border: none; color: white; border-radius: 6px; font-size: 14px;" placeholder="Enter quantity">
                        <?php $__errorArgs = ['quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span style="color: #e57373; font-size: 12px;"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div>
                        <label style="font-size: 14px; color: #ccc; margin-bottom: 8px; display: block;">Item</label>
                        <select name="item_id" style="width: 100%; padding: 10px; background: #2c2c2c; border: none; color: white; border-radius: 6px; font-size: 14px;">
                            <option value="">Select item</option>
                            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($item->id); ?>" <?php echo e(old('item_id') == $item->id ? 'selected' : ''); ?>><?php echo e($item->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['item_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span style="color: #e57373; font-size: 12px;"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div>
                        <label style="font-size: 14px; color: #ccc; margin-bottom: 8px; display: block;">Warehouse</label>
                        <select name="warehouse_id" style="width: 100%; padding: 10px; background: #2c2c2c; border: none; color: white; border-radius: 6px; font-size: 14px;">
                            <option value="">Select warehouse</option>
                            <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $warehouse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($warehouse->id); ?>" <?php echo e(old('warehouse_id') == $warehouse->id ? 'selected' : ''); ?>><?php echo e($warehouse->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['warehouse_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span style="color: #e57373; font-size: 12px;"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div>
                        <label style="font-size: 14px; color: #ccc; margin-bottom: 8px; display: block;">Current Location</label>
                        <input type="text" name="current_location" value="<?php echo e(old('current_location')); ?>" style="width: 100%; padding: 10px; background: #2c2c2c; border: none; color: white; border-radius: 6px; font-size: 14px;" placeholder="Enter current location">
                        <?php $__errorArgs = ['current_location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span style="color: #e57373; font-size: 12px;"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div>
                        <label style="font-size: 14px; color: #ccc; margin-bottom: 8px; display: block;">Notes</label>
                        <textarea name="notes" style="width: 100%; padding: 10px; background: #2c2c2c; border: none; color: white; border-radius: 6px; font-size: 14px;" placeholder="Enter notes"><?php echo e(old('notes')); ?></textarea>
                        <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span style="color: #e57373; font-size: 12px;"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
                <div style="margin-top: 24px;">
                    <button type="submit" style="background: #722be0; color: white; padding: 10px 20px; border-radius: 6px; border: none; font-size: 14px; cursor: pointer;">
                        Create Item Unit
                    </button>
                    <a href="<?php echo e(route('item-units.viewIndex')); ?>" style="background: #2c2c2c; color: #ccc; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-size: 14px; margin-left: 10px;">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\Sarpras\resources\views/item-units/create.blade.php ENDPATH**/ ?>