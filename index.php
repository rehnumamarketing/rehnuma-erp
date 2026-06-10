<?php
/**
 * Rehnuma ERP - Main Entry Point
 */

session_start();

// Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'rehnuma_erp');
define('SITE_URL', 'http://localhost/rehnuma-erp/');
define('PROJECT_NAME', 'Rehnuma ERP');

// Check if user is logged in
$is_logged_in = isset($_SESSION['user_id']);

if (!$is_logged_in) {
    header('Location: ' . SITE_URL . 'login.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rehnuma ERP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>public/css/style.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <h1 class="card-title mb-4">🏪 Rehnuma ERP</h1>
                        <h3 class="card-subtitle mb-4">سیستم مدیریت منابع کسب و کار</h3>
                        
                        <p class="card-text mb-4">خوش آمدید!</p>
                        <div class="btn-group-vertical w-100" role="group">
                            <a href="dashboard.php" class="btn btn-primary mb-2">📊 داشبورد</a>
                            <a href="modules/store/index.php" class="btn btn-success mb-2">🏪 فروشگاه</a>
                            <a href="modules/orders/index.php" class="btn btn-info mb-2">🎨 سفارشات</a>
                            <a href="modules/students/index.php" class="btn btn-warning mb-2">👨‍🎓 دانشجویان</a>
                            <a href="modules/inventory/index.php" class="btn btn-secondary mb-2">📦 موجودی</a>
                            <a href="logout.php" class="btn btn-danger">خروج</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>