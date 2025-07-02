<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sarpras - <?php echo $__env->yieldContent('title', 'Dashboard'); ?></title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/icon.png')); ?>?v=2">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://unpkg.com/feather-icons"></script>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #1a1a1a;
            color: #ccc;
            height: 100vh;
            display: flex;
            overflow: hidden;
        }

        .dashboard-grid {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .category-cards {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .category-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #ccc;
            text-align: center;
        }

        .category-circle {
            width: 100px;
            height: 100px;
            background-color: #222;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: 600;
            color: #8B5CF6;
            border: 2px solid #8B5CF6;
            margin-bottom: 8px;
        }

        .category-table {
            flex: 1;
            min-width: 300px;
        }

        .category-table .table {
            background-color: #222;
            color: #ccc;
        }

        .category-table .table th {
            background-color: #2c2c2c;
            color: #8B5CF6;
        }

        .category-table .table td {
            color: #ccc;
        }

        .reports-section {
            flex: 1;
            min-width: 200px;
        }

        .report-card {
            background-color: #222;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 10px;
            border: 1px solid #2c2c2c;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .report-card span {
            color: #ccc;
        }

        .report-card a:hover {
            text-decoration: underline;
        }

        .sidebar {
            width: 250px;
            background-color: #1a1a1a;
            padding: 20px;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #2c2c2c;
            color: #ccc;
            height: 100vh;
            flex-shrink: 0;
            position: relative;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            gap: 8px;
            padding-top: 21px;
            padding-bottom: 16px;
            border-bottom: 1px solid #2c2c2c;
            border-right: 1px solid #2c2c2c;
            width: 120%;
            height: 82.5px;
            background-color: #1a1a1a;
            margin-left: -10%;
            margin-top: -20px;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .sidebar-header img {
            width: 25px;
            height: 25px;
            margin-left: 1.7rem;
            margin-bottom: 0.5rem;
        }

        .sidebar-header span {
            margin-bottom: 0.5rem;
        }

        .sidebar a {
            color: #ccc;
            text-decoration: none;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 7px;
            padding: 8px;
            border-radius: 6px;
            transition: background 0.2s;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #222;
            color: white;
        }

        .sidebar-section-label {
            font-size: 12px;
            color: #888;
            margin: 20px 0 4px 8px;
            margin-top: 12px;
            margin-bottom: 10px;
        }

        .sidebar-scrollable {
            flex: 1;
            overflow-y: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .sidebar-scrollable::-webkit-scrollbar {
            display: none;
        }

        .sidebar-section {
            padding: 0 8px;
        }

        .sidebar-padding-top {
            margin-top: 25px;
            position: sticky;
            top: 82.5px;
            background-color: #1a1a1a;
            z-index: 5;
        }

        .sidebar-padding-bottom {
            padding-top: 15px;
            position: sticky;
            bottom: 0;
            background-color: #1a1a1a;
            z-index: 5;
        }

        .sidebar-padding-bottom::before {
            content: '';
            position: absolute;
            top: -7px;
            left: -20px;
            right: -20px;
            border-top: 1px solid #2c2c2c;
        }

        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 24px;
            background-color: #1a1a1a;
            border-bottom: 1px solid #2c2c2c;
            margin-bottom: 0.4rem;
            font-size: 14px;
            flex-shrink: 0;
        }

        .content::-webkit-scrollbar {
            width: 8px;
        }

        .content::-webkit-scrollbar-track {
            background: #2c2c2c;
        }

        .content::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        .content::-webkit-scrollbar-thumb:hover {
            background: #ccc;
        }

        .user-avatar {
            background: linear-gradient(135deg, #8c3aff, #722be0);
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            margin-left: 10px;
            gap: 8px;
            color: white;
        }

        .breadcrumb-link {
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: #888;
            transition: color 0.3s;
        }

        .breadcrumb-link:hover {
            color: #ccc;
        }

        .breadcrumb-link:active,
        .breadcrumb-link:focus {
            color: #ccc;
            text-decoration: underline;
        }

        .dashboard-link.active {
            color: white;
        }

        .dashboard-link.active:hover,
        .dashboard-link.active:active,
        .dashboard-link.active:focus {
            color: white;
            text-decoration: underline;
        }

        .breadcrumb-separator {
            color: #888;
            margin: 0 4px;
        }

        .breadcrumb-current {
            display: flex;
            align-items: center;
            gap: 8px;
            color: white;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 24px;
            color: white;
        }

        .header-user {
            display: flex;
            align-items: center;
            gap: 12px;
            background-color: rgba(255, 255, 255, 0.05);
            padding: 6px 12px;
            border-radius: 24px;
            transition: background 0.3s;
            cursor: pointer;
        }

        .header-user:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .user-name {
            color: white;
            font-weight: 500;
            font-size: 14px;
        }

        .dropdown-icon {
            width: 18px;
            height: 18px;
            color: white;
        }

        .dropdown-menu {
            position: absolute;
            top: 70px;
            right: 40px;
            background-color: #111;
            border: 1px solid #2c2c2c;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            display: none;
            flex-direction: column;
            min-width: 180px;
            z-index: 1000;
        }

        .dropdown-item {
            padding: 10px 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            color: #ccc;
            text-decoration: none;
            transition: background 0.3s;
            background-color: transparent;
            border: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }

        .dropdown-item:hover {
            background-color: #1a1a1a;
            color: white;
        }

        .dropdown-item.logout {
            border-top: 1px solid #2c2c2c;
            margin-top: 5px;
        }

        .content {
            padding: 24px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            overflow-y: auto;
        }

        .feather {
            width: 17px;
            height: 17px;
        }

        .card {
            background-color: #1a1a1a;
            padding: 16px 20px;
            border-radius: 10px;
            width: 290px;
            min-height: 70px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border: 1.5px solid #2c2c2c;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            font-weight: 400;
            color: #ddd;
        }

        .card-icon i {
            width: 16px;
            height: 16px;
        }

        .card-body {
            margin-top: 10px;
        }

        .card-value {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .card-footer {
            font-size: 11px;
            color: #aaa;
        }

        .page-intro {
            padding: 0 24px;
            margin-bottom: 7px;
        }

        .page-intro h2 {
            font-size: 25px;
            font-weight: 600;
            color: white;
            margin-bottom: 4px;
            margin-left: 5px;
            margin-top: 10px;
        }

        .page-intro p {
            font-size: 13px;
            color: #999;
            margin-left: 5px;
        }

        .search-form {
            margin-bottom: 16px;
        }

        .search-input {
            padding: 10px 14px;
            background: #2c2c2c;
            border: none;
            color: white;
            border-radius: 6px;
            font-size: 14px;
            width: 300px;
            outline: none;
        }

        .search-input::placeholder {
            color: #888;
        }

        table th:not(:last-child),
        table td:not(:last-child) {
            border-right: 1px solid #2c2c2c;
        }

        table th,
        table td {
            border-bottom: 1px solid #2c2c2c;
        }

        .table-container, .form-container, .details-container {
            background-color: #222;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #2c2c2c;
        }

        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border: 1px solid #2c2c2c;
            border-radius: 8px;
            overflow: hidden;
        }

        .table th, .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #2c2c2c;
        }

        .table th {
            background-color: #2c2c2c;
            color: #8B5CF6;
            font-weight: 600;
        }

        .table td {
            color: #ccc;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .table tbody tr:hover {
            background-color: #2a2a2a;
        }

        .filter-form {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .filter-form input, .filter-form select {
            background-color: #2c2c2c;
            border: 1px solid #444;
            color: white;
            padding: 8px;
            border-radius: 4px;
        }

        .filter-form button {
            background-color: #3b82f6;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }

        .filter-form button:hover {
            background-color: #2563eb;
        }

        .action-buttons a, .action-buttons button {
            margin-right: 8px;
            text-decoration: none;
        }

        .select2-container .select2-selection--single {
            background-color: #2c2c2c;
            border: 1px solid #444;
            color: white;
            height: 38px;
            border-radius: 4px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: white;
            line-height: 36px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }

        .select2-dropdown {
            background-color: #2c2c2c;
            border: 1px solid #444;
        }

        .select2-results__option {
            color: white;
        }

        .select2-results__option--highlighted {
            background-color: #444 !important;
            color: white !important;
        }

        .item-row {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }

        .item-row select, .item-row input {
            flex: 1;
        }

        .bg-white {
            background-color: #222 !important;
        }

        .shadow {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .rounded {
            border-radius: 6px;
        }

        .text-blue-500 {
            color: #3b82f6 !important;
        }

        .text-blue-500:hover {
            text-decoration: underline;
        }

        .text-green-500 {
            color: #10b981 !important;
        }

        .text-green-500:hover {
            text-decoration: underline;
        }

        .text-red-500 {
            color: #ef4444 !important;
        }

        .text-red-500:hover {
            text-decoration: underline;
        }

        .bg-green-500 {
            background-color: #10b981 !important;
        }

        .bg-red-500 {
            background-color: #ef4444 !important;
        }

        .text-white {
            color: white !important;
        }

        .px-4 {
            padding-left: 16px;
            padding-right: 16px;
        }

        .py-2 {
            padding-top: 8px;
            padding-bottom: 8px;
        }

        .border {
            border: 1px solid #444;
        }

        .px-2 {
            padding-left: 8px;
            padding-right: 8px;
        }

        .py-1 {
            padding-top: 4px;
            padding-bottom: 4px;
        }

        .h-20 {
            height: 80px;
        }

        .mb-6 {
            margin-bottom: 24px;
        }

        .mb-4 {
            margin-bottom: 16px;
        }

        .mb-2 {
            margin-bottom: 8px;
        }

        .text-xl {
            font-size: 20px;
        }

        .font-bold {
            font-weight: 700;
        }
    </style>
</head>
<body>
    <?php echo $__env->make('components.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="main">
        <?php echo $__env->make('components.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <?php if(session('success')): ?>
            <div style="background: #4CAF50; color: white; padding: 10px; margin: 10px 24px; border-radius: 4px;">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div style="background: #e57373; color: white; padding: 10px; margin: 10px 24px; border-radius: 4px;">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>
        <?php if($errors->has('import')): ?>
            <div style="background: #e57373; color: white; padding: 10px; margin: 10px 24px; border-radius: 4px;">
                <?php echo e($errors->first('import')); ?>

            </div>
        <?php endif; ?>

        <div class="content">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </div>

    <script>
        feather.replace();
        const headerUser = document.querySelector('.header-user');
        const dropdownMenu = document.getElementById('userDropdown');
        if (headerUser && dropdownMenu) {
            headerUser.addEventListener('click', function (e) {
                e.stopPropagation();
                dropdownMenu.style.display = dropdownMenu.style.display === 'flex' ? 'none' : 'flex';
            });
            window.addEventListener('click', function () {
                dropdownMenu.style.display = 'none';
            });
        }
    </script>
    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\laragon\www\Sarpras\resources\views/layouts/app.blade.php ENDPATH**/ ?>