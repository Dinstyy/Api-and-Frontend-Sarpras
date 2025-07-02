<?php $__env->startSection('content'); ?>
<div class="content">
    <h2 style="margin-bottom: 16px; font-size: 18px;">Tambah Pengguna</h2>
    <form action="<?php echo e(route('users.store')); ?>" method="POST" style="background: #1a1a1a; padding: 20px; border-radius: 8px;">
        <?php echo csrf_field(); ?>

        <div style="margin-bottom: 15px;">
            <label for="role" style="display: block; margin-bottom: 5px;">Role</label>
            <select name="role" id="role" style="width: 100%; padding: 8px; background: #2c2c2c; border: none; color: white; border-radius: 4px;" required onchange="toggleFields()">
                <?php $__currentLoopData = ['admin', 'siswa', 'guru', 'kepsek', 'caraka']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($role); ?>"><?php echo e(ucfirst($role)); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = ['role'];
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

        <div style="margin-bottom: 15px;">
            <label for="name" style="display: block; margin-bottom: 5px;">Nama</label>
            <input type="text" name="name" id="name" value="<?php echo e(old('name')); ?>"
                   style="width: 100%; padding: 8px; background: #2c2c2c; border: none; color: white; border-radius: 4px;" required>
            <?php $__errorArgs = ['name'];
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

        <div style="margin-bottom: 15px; display: none;" id="emailField">
            <label for="email" style="display: block; margin-bottom: 5px;">Email</label>
            <input type="email" name="email" id="email" value="<?php echo e(old('email')); ?>"
                   style="width: 100%; padding: 8px; background: #2c2c2c; border: none; color: white; border-radius: 4px;">
            <?php $__errorArgs = ['email'];
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

        <div style="margin-bottom: 15px; display: none;" id="passwordField">
            <label for="password" style="display: block; margin-bottom: 5px;">Password</label>
            <input type="password" name="password" id="password" placeholder="Masukkan password"
                   style="width: 100%; padding: 8px; background: #2c2c2c; border: none; color: white; border-radius: 4px;">
            <?php $__errorArgs = ['password'];
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

        <div style="margin-bottom: 15px; display: none;" id="kelasField">
            <label for="kelas" style="display: block; margin-bottom: 5px;">Kelas</label>
            <input type="text" name="kelas" id="kelas" value="<?php echo e(old('kelas')); ?>"
                   style="width: 100%; padding: 8px; background: #2c2c2c; border: none; color: white; border-radius: 4px;">
            <?php $__errorArgs = ['kelas'];
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

        <div style="display: flex; gap: 10px;">
            <button type="submit" style="background: #722be0; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer;">
                Simpan
            </button>
            <a href="<?php echo e(route('users.viewIndex')); ?>" style="background: #333; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none;">
                Batal
            </a>
        </div>
    </form>
</div>

<script>
function toggleFields() {
    const role = document.getElementById('role').value;
    const emailField = document.getElementById('emailField');
    const passwordField = document.getElementById('passwordField');
    const kelasField = document.getElementById('kelasField');

    emailField.style.display = 'none';
    passwordField.style.display = 'none';
    kelasField.style.display = 'none';
    document.getElementById('email').removeAttribute('required');
    document.getElementById('password').removeAttribute('required');
    document.getElementById('kelas').removeAttribute('required');

    if (['admin', 'kepsek'].includes(role)) {
        emailField.style.display = 'block';
        document.getElementById('email').setAttribute('required', 'required');
        if (['kepsek'].includes(role)) {
            passwordField.style.display = 'block';
            document.getElementById('password').setAttribute('required', 'required');
        }
    } else if (role === 'siswa') {
        kelasField.style.display = 'block';
        document.getElementById('kelas').setAttribute('required', 'required');
    }
}
toggleFields();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\Sarpras\resources\views/users/create.blade.php ENDPATH**/ ?>