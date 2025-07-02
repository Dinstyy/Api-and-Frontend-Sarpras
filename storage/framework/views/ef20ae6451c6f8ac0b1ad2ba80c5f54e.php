<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/icon.png')); ?>?v=2">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        .left-side {
            flex: 1;
            position: relative;
            overflow: hidden;
        }

        .left-side .bg-image {
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 1;
        }

        .left-side::after {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background-color: rgba(138, 43, 226, 0.4);
            z-index: 2;
        }

        .right-side {
            flex: 1;
            background-color: #0f0f0f;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }

        .login-box {
            width: 100%;
            max-width: 360px;
        }

        .logo {
            display: flex;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .logo img {
            width: 38px;
            height: 38px;
            cursor: pointer;
            transition: 0.2s ease;
        }

        h2 {
            text-align: center;
            font-size: 1.3rem;
            margin-bottom: 0.5rem;
        }

        p {
            text-align: center;
            color: #aaa;
            margin-bottom: 2rem;
            font-size: 0.8rem;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
            border: 1px solid #444;
            border-radius: 8px;
            background-color: #0f0f0f;
            color: white;
        }

        label {
            font-size: 0.8rem;
            margin-bottom: 0.5rem;
            display: block;
        }

        .checkbox {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
            margin-top: 0.4rem;
            position: relative;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

        .checkbox label {
            margin-left: 0.2rem;
        }

        .checkbox input[type="checkbox"] {
            appearance: none;
            width: 16px;
            height: 16px;
            border: 2px solid white;
            background-color: black;
            cursor: pointer;
            margin-bottom: 0.3rem;
            border-radius: 4px;
            position: relative;
            z-index: 2;
        }

        .checkbox .checkmark {
            position: absolute;
            pointer-events: none;
            left: 2px;
            top: 3px;
            color: black;
            font-size: 12px;
            z-index: 3;
            display: none;
        }

        .checkbox input[type="checkbox"]:checked + .checkmark {
            display: block;
        }

        .checkbox input[type="checkbox"]:checked {
        background-color: white;
        }

        button {
            width: 100%;
            padding: 0.75rem;
            background-color: #8c3aff;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            transition: 0.2s ease;
        }

        button:hover {
            background-color: #722be0;
        }

        .spinner {
            display: none;
            margin-right: 8px;
        }
        button.loading .spinner {
            display: inline-block;
        }
        button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .error-message {
            background-color: black;
            color: red;
            border: 1px solid red;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .error-message i {
            margin-right: 0.5rem;
        }

        input:-webkit-autofill {
            box-shadow: 0 0 0px 1000px rgba(138, 43, 226, 0.1) inset !important;
            -webkit-text-fill-color: white !important;
            border: 1px solid white !important;
            transition: background-color 5000s ease-in-out 0s;
        }
    </style>
</head>
<body>
    <div class="left-side">
        <img src="<?php echo e(asset('images/tb.jpg')); ?>" alt="Background" class="bg-image">
    </div>
    <div class="right-side">
        <div class="login-box">
            <div class="logo">
                <a href="<?php echo e(url('/')); ?>">
                    <img src="<?php echo e(asset('images/icon.png')); ?>" alt="Logo">
                </a>
            </div>
            <h2>Log in to your account</h2>
            <p>Enter your email and password below to log in</p>
            <?php if($errors->any()): ?>
                <div class="error-message">
                    <i class="fa fa-times-circle"></i>
                    <?php echo e($errors->first()); ?>

                </div>
            <?php endif; ?>
            <form action="<?php echo e(route('login.post')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="role" value="admin">

                <label for="email">Email address</label>
                <input type="email" name="email" id="email" placeholder="email@example.com" value="<?php echo e(old('email')); ?>" required>

                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter ur pw**" required>

                <div class="checkbox">
                    <input type="checkbox" name="remember" id="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>>
                    <span class="checkmark"><i class="fa-solid fa-check"></i></span>
                    <label for="remember">Remember me</label>
                </div>

                <button type="submit" id="login-btn">
                    <span class="spinner" id="spinner"><i class="fa fa-spinner fa-spin"></i></span>
                    Log in
                </button>
            </form>
        </div>
    </div>
    <script>
    document.getElementById('login-btn').addEventListener('click', function (e) {
        const btn = this;
        btn.classList.add('loading');
        btn.disabled = true;

        setTimeout(() => {
            btn.closest('form').submit();
        }, 2000);

        e.preventDefault();
    });
    </script>
</body>
</html>
<?php /**PATH C:\laragon\www\Sarpras\resources\views/auth/login.blade.php ENDPATH**/ ?>