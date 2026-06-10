<?php
/**
 * Rehnuma ERP - Dashboard
 */

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'rehnuma_erp');

$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($connection->connect_error) {
    die('خطا در اتصال به بانک اطلاعات');
}

// Get dashboard data based on user role
$role = $_SESSION['role'];

// Get today's sales
$today_sales = $connection->query("SELECT SUM(final_amount) as total FROM invoices WHERE DATE(invoice_date) = CURDATE() AND status = 'issued'");
$today_sales_data = $today_sales->fetch_assoc();
$today_sales_total = $today_sales_data['total'] ?? 0;

// Get this month sales
$month_sales = $connection->query("SELECT SUM(final_amount) as total FROM invoices WHERE MONTH(invoice_date) = MONTH(CURDATE()) AND YEAR(invoice_date) = YEAR(CURDATE()) AND status = 'issued'");
$month_sales_data = $month_sales->fetch_assoc();
$month_sales_total = $month_sales_data['total'] ?? 0;

// Get total debtors
$debtors = $connection->query("SELECT SUM(outstanding_amount) as total FROM debtors_creditors WHERE type = 'debtor'");
$debtors_data = $debtors->fetch_assoc();
$debtors_total = $debtors_data['total'] ?? 0;

// Get low stock items
$low_stock = $connection->query("SELECT p.id, p.name, i.quantity_in_hand, p.reorder_level FROM inventory i JOIN products p ON i.product_id = p.id WHERE i.quantity_in_hand <= p.reorder_level LIMIT 5");

?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>داشبورد - Rehnuma ERP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .stat-card {
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-card.sales {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .stat-card.debtors {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }
        .stat-card.orders {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">🏪 Rehnuma ERP</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">داشبورد</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="modulesDropdown" role="button" data-bs-toggle="dropdown">
                            ماژول‌ها
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="modulesDropdown">
                            <li><a class="dropdown-item" href="modules/store/">🏪 فروشگاه</a></li>
                            <li><a class="dropdown-item" href="modules/orders/">🎨 سفارشات</a></li>
                            <li><a class="dropdown-item" href="modules/students/">👨‍🎓 دانشجویان</a></li>
                            <li><a class="dropdown-item" href="modules/inventory/">📦 موجودی</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">خروج</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <div class="container-fluid p-4">
        <h1 class="mb-4">📊 داشبورد</h1>
        
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card stat-card sales p-4">
                    <h5>فروش امروز</h5>
                    <h2><?php echo number_format($today_sales_total, 0); ?> ؋</h2>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card orders p-4">
                    <h5>فروش این ماه</h5>
                    <h2><?php echo number_format($month_sales_total, 0); ?> ؋</h2>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card debtors p-4">
                    <h5>بدهکاری</h5>
                    <h2><?php echo number_format($debtors_total, 0); ?> ؋</h2>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card p-4" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white;">
                    <h5>محصولات کم موجود</h5>
                    <h2><?php echo $low_stock->num_rows; ?></h2>
                </div>
            </div>
        </div>
        
        <!-- Quick Links -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">⚡ دسترسی سریع</h5>
                    </div>
                    <div class="card-body">
                        <div class="btn-group-horizontal d-flex gap-2 flex-wrap">
                            <a href="modules/store/new_invoice.php" class="btn btn-primary">➕ فاکتور جدید</a>
                            <a href="modules/orders/new_order.php" class="btn btn-info">🎨 سفارش جدید</a>
                            <a href="modules/inventory/goods_receiving.php" class="btn btn-success">📥 دریافت کالا</a>
                            <a href="modules/inventory/stock_transfer.php" class="btn btn-warning">🔄 انتقال کالا</a>
                            <a href="reports/" class="btn btn-secondary">📊 گزارش‌ها</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>