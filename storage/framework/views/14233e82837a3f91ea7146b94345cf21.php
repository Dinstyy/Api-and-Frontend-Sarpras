<?php if(auth()->guard()->check()): ?>
    <?php if(in_array(auth()->user()->role, ['admin', 'kepsek'])): ?>
        <script>window.location = "<?php echo e(route('dashboard')); ?>";</script>
    <?php endif; ?>
<?php endif; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sisfo Sarpras</title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/icon.png')); ?>?v=2">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #060814;
            color: #fff;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            margin-top: 0.5rem;
            background-color: transparent;
            position: absolute;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 10;
        }

        .navbar .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-left: 3rem;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .navbar .logo img {
            width: 24px;
            height: 24px;
        }

        .navbar .signin-btn {
            background-color: #A20EFF;
            color: white;
            padding: 0.6rem 1.2rem;
            border-radius: 999px;
            border: none;
            text-decoration: none;
            font-weight: 700;
            margin-right: 3rem;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .navbar .signin-btn:hover {
            background-color: transparent;
            border: 2px solid white;
            color: white;
        }

        .hero {
            text-align: center;
            margin-top: 12.5rem;
            padding: 0 1rem 3rem;
            position: relative;
        }

        .hero h1 {
            font-size: 4rem;
            font-weight: 700;
            line-height: 1.2;
            color: white;
            position: relative;
            z-index: 1;
        }

        .hero h1 span {
            display: block;
            color: #ffffff;
        }

        .hero p {
            max-width: 800px;
            margin: 1.5rem auto 0 auto;
            color: #A0AEC0;
            font-size: 1rem;
            line-height: 1.5;
            word-wrap: break-word;
        }

        .hero-glow {
            position: absolute;
            top: -100px;
            left: 50%;
            transform: translateX(-50%);
            width: 700px;
            height: 500px;
            background: radial-gradient(circle, rgba(162,14,255,0.4) 0%, rgba(6,8,20,0) 80%);
            z-index: 0;
            filter: blur(90px);
        }

        .features {
            display: flex;
            justify-content: center;
            gap: 2rem;
            padding: 2rem;
            border-radius: 1rem;
            background-color: #101826;
            max-width: 1000px;
            margin-left: auto;
            margin-right: auto;
        }

        .feature {
            display: flex;
            flex-direction: row;
            align-items: flex-start;
            gap: 1rem;
            width: 300px;
        }

        .feature-icon {
            background-color: #1A1A1A;
            padding: 0.7rem;
            border-radius: 0.5rem;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .feature-text {
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
        }

        .feature-title {
            font-weight: 600;
            font-size: 1rem;
        }

        .feature-desc {
            color: #A0AEC0;
            font-size: 0.9rem;
        }

        @media(max-width: 768px) {
            .hero h1 {
                font-size: 2.2rem;
            }

            .features {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <img src="<?php echo e(asset('images/icon.png')); ?>" alt="Logo">
            Sarpras
        </div>
        <a href="<?php echo e(route('login')); ?>" class="signin-btn">Sign In →</a>
    </div>

    <div class="hero">
        <div class="hero-glow"></div>
        <h1>Kelola Sarpras Sekolah<br><span>dengan Mudah</span></h1>
        <p>Aplikasi Sarpras Sekolah yang mudah digunakan dan memudahkan untuk
        mengelola sarpras. Kelola sarpras sekolah anda dengan efisien.</p>
    </div>
    <div class="features">
        <div class="feature">
            <div class="feature-icon">📷</div>
            <div class="feature-text">
                <div class="feature-title">Damaged Items</div>
                <div class="feature-desc">Kelola laporan barang rusak dan update status barang</div>
            </div>
        </div>
        <div class="feature">
            <div class="feature-icon">⚡</div>
            <div class="feature-text">
                <div class="feature-title">Borrow & Return</div>
                <div class="feature-desc">Kelola request peminjaman dan pengembalian barang</div>
            </div>
        </div>
        <div class="feature">
            <div class="feature-icon">📦</div>
            <div class="feature-text">
                <div class="feature-title">Submission</div>
                <div class="feature-desc">Ajukan pengajuan dana untuk pengadaan barang</div>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\Sarpras\resources\views/welcome.blade.php ENDPATH**/ ?>