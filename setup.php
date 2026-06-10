<?php
/**
 * Rehnuma ERP - Installation Wizard
 * This file should be run once to set up the database and initial admin user
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'rehnuma_erp');

$connection = new mysqli(DB_HOST, DB_USER, DB_PASS);

if ($connection->connect_error) {
    die('Database Connection Error: ' . $connection->connect_error);
}

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
if ($connection->query($sql) === FALSE) {
    die('Error creating database: ' . $connection->error);
}

$connection->select_db(DB_NAME);

// Create tables
$tables = [
    // Users table
    "CREATE TABLE IF NOT EXISTS users (
        id INT PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(100) UNIQUE NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin', 'branch_user', 'accountant') NOT NULL DEFAULT 'branch_user',
        branch_id INT,
        status ENUM('active', 'inactive') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX(role),
        INDEX(branch_id)
    )",
    
    // Branches table
    "CREATE TABLE IF NOT EXISTS branches (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        location VARCHAR(255),
        manager_id INT,
        status ENUM('active', 'inactive') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )",
    
    // Currencies table
    "CREATE TABLE IF NOT EXISTS currencies (
        id INT PRIMARY KEY AUTO_INCREMENT,
        code VARCHAR(10) UNIQUE NOT NULL,
        name VARCHAR(50),
        symbol VARCHAR(10),
        is_base BOOLEAN DEFAULT FALSE
    )",
    
    // Exchange rates table
    "CREATE TABLE IF NOT EXISTS exchange_rates (
        id INT PRIMARY KEY AUTO_INCREMENT,
        from_currency VARCHAR(10) NOT NULL,
        to_currency VARCHAR(10) NOT NULL,
        rate DECIMAL(15, 6) NOT NULL,
        rate_date DATE NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_rate (from_currency, to_currency, rate_date)
    )",
    
    // Measurement units table
    "CREATE TABLE IF NOT EXISTS measurement_units (
        id INT PRIMARY KEY AUTO_INCREMENT,
        unit_name VARCHAR(50) NOT NULL,
        unit_type ENUM('weight', 'volume', 'count') NOT NULL,
        abbreviation VARCHAR(10),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    
    // Products/Items table
    "CREATE TABLE IF NOT EXISTS products (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(255) NOT NULL,
        description TEXT,
        category VARCHAR(100),
        measurement_unit_id INT NOT NULL,
        base_price DECIMAL(15, 2) NOT NULL,
        currency_id INT NOT NULL DEFAULT 1,
        reorder_level INT DEFAULT 10,
        status ENUM('active', 'inactive') DEFAULT 'active',
        created_by INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY(measurement_unit_id) REFERENCES measurement_units(id),
        FOREIGN KEY(currency_id) REFERENCES currencies(id),
        FOREIGN KEY(created_by) REFERENCES users(id),
        INDEX(category),
        INDEX(status)
    )",
    
    // Inventory/Stock table
    "CREATE TABLE IF NOT EXISTS inventory (
        id INT PRIMARY KEY AUTO_INCREMENT,
        product_id INT NOT NULL,
        branch_id INT NOT NULL,
        quantity_in_hand INT DEFAULT 0,
        quantity_reserved INT DEFAULT 0,
        expiry_date DATE,
        last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY(product_id) REFERENCES products(id),
        FOREIGN KEY(branch_id) REFERENCES branches(id),
        UNIQUE KEY unique_product_branch (product_id, branch_id),
        INDEX(quantity_in_hand)
    )",
    
    // Suppliers/Vendors table
    "CREATE TABLE IF NOT EXISTS suppliers (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        contact_person VARCHAR(100),
        email VARCHAR(100),
        phone VARCHAR(20),
        address TEXT,
        payment_terms VARCHAR(100),
        status ENUM('active', 'inactive') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )",
    
    // Customers table
    "CREATE TABLE IF NOT EXISTS customers (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100),
        phone VARCHAR(20),
        address TEXT,
        customer_type ENUM('permanent', 'regular') DEFAULT 'regular',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX(customer_type)
    )",
    
    // Goods Receiving (Incoming Shipment) table
    "CREATE TABLE IF NOT EXISTS goods_receiving (
        id INT PRIMARY KEY AUTO_INCREMENT,
        supplier_id INT NOT NULL,
        invoice_number VARCHAR(100),
        delivery_date DATE NOT NULL,
        received_by INT NOT NULL,
        branch_id INT NOT NULL,
        status ENUM('pending', 'received', 'inspected') DEFAULT 'pending',
        notes TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY(supplier_id) REFERENCES suppliers(id),
        FOREIGN KEY(received_by) REFERENCES users(id),
        FOREIGN KEY(branch_id) REFERENCES branches(id),
        INDEX(status),
        INDEX(delivery_date)
    )",
    
    // Goods Receiving Items table
    "CREATE TABLE IF NOT EXISTS goods_receiving_items (
        id INT PRIMARY KEY AUTO_INCREMENT,
        receiving_id INT NOT NULL,
        product_id INT NOT NULL,
        quantity_received INT NOT NULL,
        purchase_price DECIMAL(15, 2) NOT NULL,
        currency_id INT NOT NULL,
        expiry_date DATE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY(receiving_id) REFERENCES goods_receiving(id),
        FOREIGN KEY(product_id) REFERENCES products(id),
        FOREIGN KEY(currency_id) REFERENCES currencies(id)
    )",
    
    // Stock Transfer (Between Branches) table
    "CREATE TABLE IF NOT EXISTS stock_transfers (
        id INT PRIMARY KEY AUTO_INCREMENT,
        from_branch_id INT NOT NULL,
        to_branch_id INT NOT NULL,
        transfer_date DATE NOT NULL,
        transferred_by INT NOT NULL,
        status ENUM('pending', 'in_transit', 'received') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY(from_branch_id) REFERENCES branches(id),
        FOREIGN KEY(to_branch_id) REFERENCES branches(id),
        FOREIGN KEY(transferred_by) REFERENCES users(id),
        INDEX(status)
    )",
    
    // Stock Transfer Items table
    "CREATE TABLE IF NOT EXISTS stock_transfer_items (
        id INT PRIMARY KEY AUTO_INCREMENT,
        transfer_id INT NOT NULL,
        product_id INT NOT NULL,
        quantity INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY(transfer_id) REFERENCES stock_transfers(id),
        FOREIGN KEY(product_id) REFERENCES products(id)
    )",
    
    // Invoices table
    "CREATE TABLE IF NOT EXISTS invoices (
        id INT PRIMARY KEY AUTO_INCREMENT,
        invoice_number VARCHAR(100) UNIQUE NOT NULL,
        branch_id INT NOT NULL,
        customer_id INT NOT NULL,
        invoice_type ENUM('store', 'order', 'student') NOT NULL,
        status ENUM('draft', 'issued', 'cancelled') DEFAULT 'draft',
        invoice_date DATE NOT NULL,
        total_amount DECIMAL(15, 2) NOT NULL,
        discount DECIMAL(15, 2) DEFAULT 0,
        tax DECIMAL(15, 2) DEFAULT 0,
        final_amount DECIMAL(15, 2) NOT NULL,
        currency_id INT NOT NULL,
        exchange_rate DECIMAL(15, 6) NOT NULL DEFAULT 1,
        language ENUM('dari', 'english') DEFAULT 'dari',
        notes TEXT,
        created_by INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY(branch_id) REFERENCES branches(id),
        FOREIGN KEY(customer_id) REFERENCES customers(id),
        FOREIGN KEY(currency_id) REFERENCES currencies(id),
        FOREIGN KEY(created_by) REFERENCES users(id),
        INDEX(status),
        INDEX(invoice_date),
        INDEX(invoice_type)
    )",
    
    // Invoice Items table
    "CREATE TABLE IF NOT EXISTS invoice_items (
        id INT PRIMARY KEY AUTO_INCREMENT,
        invoice_id INT NOT NULL,
        product_id INT NOT NULL,
        quantity INT NOT NULL,
        unit_price DECIMAL(15, 2) NOT NULL,
        item_discount DECIMAL(15, 2) DEFAULT 0,
        total DECIMAL(15, 2) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY(invoice_id) REFERENCES invoices(id),
        FOREIGN KEY(product_id) REFERENCES products(id)
    )",
    
    // Payments table
    "CREATE TABLE IF NOT EXISTS payments (
        id INT PRIMARY KEY AUTO_INCREMENT,
        invoice_id INT NOT NULL,
        payment_date DATE NOT NULL,
        payment_type ENUM('cash', 'credit', 'partial') NOT NULL,
        amount DECIMAL(15, 2) NOT NULL,
        currency_id INT NOT NULL,
        payment_method VARCHAR(50),
        reference_number VARCHAR(100),
        notes TEXT,
        created_by INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY(invoice_id) REFERENCES invoices(id),
        FOREIGN KEY(currency_id) REFERENCES currencies(id),
        FOREIGN KEY(created_by) REFERENCES users(id),
        INDEX(payment_date),
        INDEX(payment_type)
    )",
    
    // Returns/Stock Adjustment table
    "CREATE TABLE IF NOT EXISTS returns (
        id INT PRIMARY KEY AUTO_INCREMENT,
        invoice_id INT NOT NULL,
        return_date DATE NOT NULL,
        reason TEXT,
        status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
        created_by INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY(invoice_id) REFERENCES invoices(id),
        FOREIGN KEY(created_by) REFERENCES users(id),
        INDEX(status)
    )",
    
    // Return Items table
    "CREATE TABLE IF NOT EXISTS return_items (
        id INT PRIMARY KEY AUTO_INCREMENT,
        return_id INT NOT NULL,
        product_id INT NOT NULL,
        quantity INT NOT NULL,
        unit_price DECIMAL(15, 2) NOT NULL,
        total_value DECIMAL(15, 2),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY(return_id) REFERENCES returns(id),
        FOREIGN KEY(product_id) REFERENCES products(id)
    )",
    
    // Shipments table
    "CREATE TABLE IF NOT EXISTS shipments (
        id INT PRIMARY KEY AUTO_INCREMENT,
        invoice_id INT NOT NULL,
        delivery_channel VARCHAR(100),
        courier_tracking_number VARCHAR(100),
        status ENUM('pending', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
        shipped_date DATETIME,
        delivered_date DATETIME,
        notes TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY(invoice_id) REFERENCES invoices(id),
        INDEX(status),
        INDEX(shipped_date)
    )",
    
    // Debtors/Creditors table
    "CREATE TABLE IF NOT EXISTS debtors_creditors (
        id INT PRIMARY KEY AUTO_INCREMENT,
        customer_id INT NOT NULL,
        invoice_id INT NOT NULL,
        outstanding_amount DECIMAL(15, 2) NOT NULL,
        type ENUM('debtor', 'creditor') NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY(customer_id) REFERENCES customers(id),
        FOREIGN KEY(invoice_id) REFERENCES invoices(id),
        INDEX(type)
    )",
    
    // Installments table
    "CREATE TABLE IF NOT EXISTS installments (
        id INT PRIMARY KEY AUTO_INCREMENT,
        debtor_creditor_id INT NOT NULL,
        installment_number INT NOT NULL,
        amount DECIMAL(15, 2) NOT NULL,
        due_date DATE NOT NULL,
        paid_date DATE,
        status ENUM('unpaid', 'paid', 'overdue') DEFAULT 'unpaid',
        reminder_count INT DEFAULT 0,
        last_reminder DATE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY(debtor_creditor_id) REFERENCES debtors_creditors(id),
        INDEX(status),
        INDEX(due_date)
    )",
    
    // Orders table
    "CREATE TABLE IF NOT EXISTS orders (
        id INT PRIMARY KEY AUTO_INCREMENT,
        order_number VARCHAR(100) UNIQUE NOT NULL,
        customer_id INT NOT NULL,
        branch_id INT NOT NULL,
        order_date DATE NOT NULL,
        delivery_date DATE,
        status ENUM('pending', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending',
        total_cost DECIMAL(15, 2) NOT NULL,
        profit DECIMAL(15, 2) DEFAULT 0,
        notes TEXT,
        created_by INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY(customer_id) REFERENCES customers(id),
        FOREIGN KEY(branch_id) REFERENCES branches(id),
        FOREIGN KEY(created_by) REFERENCES users(id),
        INDEX(status),
        INDEX(order_date)
    )",
    
    // Order Items table
    "CREATE TABLE IF NOT EXISTS order_items (
        id INT PRIMARY KEY AUTO_INCREMENT,
        order_id INT NOT NULL,
        product_id INT NOT NULL,
        quantity INT NOT NULL,
        unit_cost DECIMAL(15, 2) NOT NULL,
        total_cost DECIMAL(15, 2) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY(order_id) REFERENCES orders(id),
        FOREIGN KEY(product_id) REFERENCES products(id)
    )",
    
    // Students table
    "CREATE TABLE IF NOT EXISTS students (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100),
        phone VARCHAR(20),
        address TEXT,
        date_of_birth DATE,
        status ENUM('active', 'inactive', 'graduated') DEFAULT 'active',
        enrollment_date DATE NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX(status),
        INDEX(enrollment_date)
    )",
    
    // Courses table
    "CREATE TABLE IF NOT EXISTS courses (
        id INT PRIMARY KEY AUTO_INCREMENT,
        course_name VARCHAR(100) NOT NULL,
        description TEXT,
        course_fee DECIMAL(15, 2) NOT NULL,
        currency_id INT NOT NULL,
        duration_weeks INT,
        branch_id INT,
        status ENUM('active', 'inactive') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY(currency_id) REFERENCES currencies(id),
        FOREIGN KEY(branch_id) REFERENCES branches(id),
        INDEX(status)
    )",
    
    // Student Enrollment table
    "CREATE TABLE IF NOT EXISTS student_enrollments (
        id INT PRIMARY KEY AUTO_INCREMENT,
        student_id INT NOT NULL,
        course_id INT NOT NULL,
        enrollment_date DATE NOT NULL,
        completion_date DATE,
        progress_percentage INT DEFAULT 0,
        status ENUM('enrolled', 'in_progress', 'completed', 'dropped') DEFAULT 'enrolled',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY(student_id) REFERENCES students(id),
        FOREIGN KEY(course_id) REFERENCES courses(id),
        INDEX(status)
    )",
    
    // Student Fees table
    "CREATE TABLE IF NOT EXISTS student_fees (
        id INT PRIMARY KEY AUTO_INCREMENT,
        enrollment_id INT NOT NULL,
        fee_date DATE NOT NULL,
        amount_due DECIMAL(15, 2) NOT NULL,
        amount_paid DECIMAL(15, 2) DEFAULT 0,
        outstanding_amount DECIMAL(15, 2),
        currency_id INT NOT NULL,
        payment_status ENUM('unpaid', 'partial', 'paid') DEFAULT 'unpaid',
        notes TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY(enrollment_id) REFERENCES student_enrollments(id),
        FOREIGN KEY(currency_id) REFERENCES currencies(id),
        INDEX(payment_status)
    )",
    
    // Training Materials/Expenses table
    "CREATE TABLE IF NOT EXISTS training_expenses (
        id INT PRIMARY KEY AUTO_INCREMENT,
        enrollment_id INT NOT NULL,
        product_id INT NOT NULL,
        quantity_used INT NOT NULL,
        unit_cost DECIMAL(15, 2) NOT NULL,
        total_cost DECIMAL(15, 2) NOT NULL,
        expense_date DATE NOT NULL,
        currency_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY(enrollment_id) REFERENCES student_enrollments(id),
        FOREIGN KEY(product_id) REFERENCES products(id),
        FOREIGN KEY(currency_id) REFERENCES currencies(id)
    )",
    
    // Financial Transactions table
    "CREATE TABLE IF NOT EXISTS financial_transactions (
        id INT PRIMARY KEY AUTO_INCREMENT,
        transaction_type ENUM('income', 'expense', 'withdrawal') NOT NULL,
        amount DECIMAL(15, 2) NOT NULL,
        currency_id INT NOT NULL,
        category VARCHAR(100),
        description TEXT,
        transaction_date DATE NOT NULL,
        branch_id INT,
        created_by INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY(currency_id) REFERENCES currencies(id),
        FOREIGN KEY(branch_id) REFERENCES branches(id),
        FOREIGN KEY(created_by) REFERENCES users(id),
        INDEX(transaction_type),
        INDEX(transaction_date)
    )",
    
    // Activity Log table
    "CREATE TABLE IF NOT EXISTS activity_logs (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT NOT NULL,
        action VARCHAR(255) NOT NULL,
        entity_type VARCHAR(100),
        entity_id INT,
        old_value TEXT,
        new_value TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY(user_id) REFERENCES users(id),
        INDEX(created_at),
        INDEX(user_id)
    )"
];

foreach ($tables as $sql) {
    if ($connection->query($sql) === FALSE) {
        echo "Error creating table: " . $connection->error . "<br>";
    }
}

echo "<h2>✅ Database Setup Completed!</h2>";
echo "<p>All tables have been created successfully.</p>";

// Insert base data
$connection->query("INSERT IGNORE INTO currencies (code, name, symbol, is_base) VALUES ('AFN', 'Afghan Afghani', '؋', TRUE)");
$connection->query("INSERT IGNORE INTO currencies (code, name, symbol, is_base) VALUES ('USD', 'US Dollar', '$', FALSE)");
$connection->query("INSERT IGNORE INTO currencies (code, name, symbol, is_base) VALUES ('PKR', 'Pakistani Rupee', 'Rs', FALSE)");

$connection->query("INSERT IGNORE INTO measurement_units (unit_name, unit_type, abbreviation) VALUES ('Kilogram', 'weight', 'kg')");
$connection->query("INSERT IGNORE INTO measurement_units (unit_name, unit_type, abbreviation) VALUES ('Gram', 'weight', 'g')");
$connection->query("INSERT IGNORE INTO measurement_units (unit_name, unit_type, abbreviation) VALUES ('Liter', 'volume', 'L')");
$connection->query("INSERT IGNORE INTO measurement_units (unit_name, unit_type, abbreviation) VALUES ('Milliliter', 'volume', 'ml')");
$connection->query("INSERT IGNORE INTO measurement_units (unit_name, unit_type, abbreviation) VALUES ('Piece', 'count', 'pc')");
$connection->query("INSERT IGNORE INTO measurement_units (unit_name, unit_type, abbreviation) VALUES ('Box', 'count', 'box')");

// Create admin user (hashed password)
$admin_password = password_hash('admin123', PASSWORD_BCRYPT);
$connection->query("INSERT IGNORE INTO users (username, email, password, role, status) VALUES ('admin', 'admin@rehnuma.com', '$admin_password', 'admin', 'active')");

// Create main branch
$connection->query("INSERT IGNORE INTO branches (name, location, status) VALUES ('Main Branch', 'Kabul', 'active')");

echo "<p>Base data (currencies, units, admin user) added successfully.</p>";
echo "<p><strong>⚠️ IMPORTANT: Please delete this file (setup.php) after setup is complete.</strong></p>";
echo "<p><a href='index.php' class='btn btn-primary'>Go to Login →</a></p>";

$connection->close();
?>