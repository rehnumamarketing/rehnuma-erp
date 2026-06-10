<?php
/**
 * Rehnuma ERP - Login Page
 */

session_start();

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'rehnuma_erp');

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    if ($email && $password) {
        $connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if ($connection->connect_error) {
            $error_message = 'خطا در اتصال به بانک اطلاعات';
        } else {
            $query = "SELECT id, username, password, role, branch_id, status FROM users WHERE email = ? AND status = 'active'";
            $stmt = $connection->prepare($query);
            
            if ($stmt) {
                $stmt->bind_param('s', $email);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows === 1) {
                    $user = $result->fetch_assoc();
                    
                    if (password_verify($password, $user['password'])) {
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['email'] = $email;
                        $_SESSION['role'] = $user['role'];
                        $_SESSION['branch_id'] = $user['branch_id'];
                        
                        header('Location: dashboard.php');
                        exit();
                    } else {
                        $error_message = 'رمز عبور نادرست است';
                    }
                } else {
                    $error_message = 'ایمیل یا رمز عبور اشتباه است';
                }
                
                $stmt->close();
            }
            
            $connection->close();
        }
    } else {
        $error_message = 'لطفاً تمام فیلدها را پر کنید';
    }
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود - Rehnuma ERP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 15px 15px 0 0;
            text-align: center;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card login-card">
                    <div class="login-header">
                        <h1 class="mb-2">🏪 Rehnuma ERP</h1>
                        <p class="mb-0">سیستم مدیریت کسب و کار</p>
                    </div>
                    
                    <div class="card-body p-4">
                        <?php if ($error_message): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo htmlspecialchars($error_message); ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">ایمیل:</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">رمز عبور:</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">ورود</button>
                        </form>
                        
                        <hr>
                        
                        <div class="alert alert-info" role="alert">
                            <strong>اطلاعات ورود تجربی:</strong><br>
                            ایمیل: <code>admin@rehnuma.com</code><br>
                            رمز عبور: <code>admin123</code>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>