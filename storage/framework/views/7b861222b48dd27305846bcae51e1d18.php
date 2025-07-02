<?php $__env->startSection('title', 'Create Item'); ?>
<?php $__env->startSection('icon', 'file'); ?>

<?php $__env->startSection('content'); ?>
    <div class="page-intro">
        <h2>Create Item</h2>
        <p>Add a new item to your inventory.</p>
    </div>

    <div class="content">
        <div style="width: 100%; max-width: 600px; background: #111; padding: 24px; border-radius: 8px; border: 1px solid #2c2c2c;">
            <form action="<?php echo e(route('items.store')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div style="margin-bottom: 16px;">
                    <label for="name" style="display: block; font-size: 14px; color: #ccc; margin-bottom: 4px;">Name</label>
                    <input type="text" id="name" name="name" value="<?php echo e(old('name')); ?>" required style="width: 100%; padding: 10px; background: #2c2c2c; border: none; color: white; border-radius: 6px; font-size: 14px;" placeholder="Enter item name">
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div style="color: #e57373; font-size: 12px; margin-top: 4px;"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div style="margin-bottom: 16px;">
                    <label for="type" style="display: block; font-size: 14px; color: #ccc; margin-bottom: 4px;">Type</label>
                    <select id="type" name="type" required style="width: 100%; padding: 10px; background: #2c2c2c; border: none; color: white; border-radius: 6px; font-size: 14px;">
                        <option value="" disabled selected>Select item type</option>
                        <option value="consumable" <?php echo e(old('type') === 'consumable' ? 'selected' : ''); ?>>Consumable</option>
                        <option value="non-consumable" <?php echo e(old('type') === 'non-consumable' ? 'selected' : ''); ?>>Non-Consumable</option>
                    </select>
                    <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div style="color: #e57373; font-size: 12px; margin-top: 4px;"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div style="margin-bottom: 16px;">
                    <label for="category_name" style="display: block; font-size: 14px; color: #ccc; margin-bottom: 4px;">Category</label>
                    <select id="category_name" name="category_name" required style="width: 100%; padding: 10px; background: #2c2c2c; border: none; color: white; border-radius: 6px; font-size: 14px;">
                        <option value="" disabled <?php echo e(old('category_name') ? '' : 'selected'); ?>>Select category</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->name); ?>" <?php echo e(old('category_name') === $category->name ? 'selected' : ''); ?>>
                                <?php echo e($category->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['category_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div style="color: #e57373; font-size: 12px; margin-top: 4px;"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div style="margin-bottom: 16px;">
                    <label for="description" style="display: block; font-size: 14px; color: #ccc; margin-bottom: 4px;">Description</label>
                    <textarea id="description" name="description" style="width: 100%; padding: 10px; background: #2c2c2c; border: none; color: white; border-radius: 6px; font-size: 14px; resize: vertical;" placeholder="Enter item description"><?php echo e(old('description')); ?></textarea>
                    <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div style="color: #e57373; font-size: 12px; margin-top: 4px;"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div style="margin-bottom: 16px;">
                    <label for="image" style="display: block; font-size: 14px; color: #ccc; margin-bottom: 4px;">Image</label>
                    <input type="file" id="image" name="image" accept="image/*" style="width: 100%; padding: 10px; background: #2c2c2c; border: none; color: white; border-radius: 6px; font-size: 14px;">
                    <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div style="color: #e57373; font-size: 12px; margin-top: 4px;"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" style="background: #722be0; color: white; padding: 10px 20px; border-radius: 6px; border: none; cursor: pointer; font-size: 14px; font-weight: 500;">
                        <i data-feather="save"></i> Save Item
                    </button>
                    <a href="<?php echo e(route('items.viewIndex')); ?>" style="background: #2c2c2c; color: #ccc; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 500;">
                        <i data-feather="x"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\Sarpras\resources\views/items/create.blade.php ENDPATH**/ ?>