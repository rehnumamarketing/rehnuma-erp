# Rehnuma ERP System

## 🎯 Complete ERP Solution for Resin Art Business

A comprehensive, web-based Enterprise Resource Planning (ERP) system built with **PHP + MySQL**, optimized for **XAMPP** deployment.

### ✨ Features

- **3 Core Modules:**
  - 🏪 Store (Materials Sales)
  - 🎨 Orders (Custom Resin Art)
  - 👨‍🎓 Students (Training/Courses)

- **Multi-Currency Support** (AFN base currency)
- **Multi-Branch Operations**
- **RTL Interface** (Persian/Dari + English)
- **Role-Based Access Control** (Admin, Branch User, Accountant)
- **Inventory Management** with Goods Receiving/Transfer/Shipment
- **Debtors & Creditors** with Installment Reminders
- **Multiple Measurement Units** (kg, L, pieces)
- **Multiple Payment Options** (cash, credit, partial)
- **Draft Invoices & Returns Capability**
- **Comprehensive Financial Reports**
- **PDF Export Functionality**

---

## 📋 Quick Start

### Requirements
- XAMPP (PHP 7.4+, MySQL 5.7+)
- Modern Web Browser
- 50MB Free Disk Space

### Installation Steps

1. **Download XAMPP** from [apachefriends.org](https://www.apachefriends.org/download.html)

2. **Extract Project Files** to:
   - **Windows:** `C:\xampp\htdocs\rehnuma-erp\`
   - **Linux:** `/opt/lampp/htdocs/rehnuma-erp/`
   - **macOS:** `/Applications/XAMPP/xamppfiles/htdocs/rehnuma-erp/`

3. **Start XAMPP Services:**
   - Windows: Open XAMPP Control Panel → Start Apache & MySQL
   - Linux: `sudo /opt/lampp/lampp start`
   - macOS: Open XAMPP app

4. **Run Setup:**
   - Go to: `http://localhost/rehnuma-erp/setup.php`
   - Wait for setup completion
   - **Delete setup.php file** afterward

5. **Login:**
   - URL: `http://localhost/rehnuma-erp`
   - Email: `admin@rehnuma.com`
   - Password: `admin123`

---

## 📁 Project Structure

```
rehnuma-erp/
├── config/                    # Configuration files
│   └── database.php          # Database connection
├── public/                    # Public assets
│   └── css/style.css         # Global styles
├── app/                       # Application logic
│   ├── controllers/          # Business logic
│   ├── models/               # Database models
│   ├── views/                # HTML templates (RTL)
│   └── helpers/              # Utility functions
├── modules/                   # Core modules
│   ├── store/                # Store module
│   ├── orders/               # Orders module
│   ├── students/             # Students module
│   └── inventory/            # Inventory management
├── reports/                   # Report generation
├── database/                  # Database schema
├── setup.php                  # Installation wizard
├── index.php                  # Home page
├── login.php                  # Login page
├── dashboard.php              # Main dashboard
├── logout.php                 # Logout handler
└── INSTALLATION.md            # Detailed setup guide
```

---

## 🔐 Default Credentials

| Field | Value |
|-------|-------|
| Email | admin@rehnuma.com |
| Password | admin123 |
| Role | Admin (Full Access) |

⚠️ **Change these immediately after first login!**

---

## 💾 Database Setup

### Automatic Setup
- Database name: `rehnuma_erp`
- Host: `localhost`
- User: `root`
- Password: (empty)
- Charset: `utf8mb4`

The setup wizard creates 25+ tables automatically:
- Users & Roles
- Branches & Multi-currency
- Products & Inventory
- Goods Receiving/Transfer/Shipment
- Invoices & Payments
- Orders & Order Items
- Students & Courses
- Financial Transactions
- Debtors & Creditors
- And more...

---

## 👥 User Roles & Permissions

### Admin
✅ Full system access
✅ All branches & locations
✅ Financial controls
✅ User management
✅ System settings

### Branch User
✅ Branch-specific operations
✅ Inventory management
✅ Sales & orders
✅ Customer management
❌ Financial reports
❌ System settings

### Accountant
✅ Financial reports only
✅ View invoices
✅ View debtors/creditors
❌ Create transactions
❌ Inventory access

---

## 📊 Core Modules

### 1️⃣ Store (Materials Sales)
- Product catalog with pricing
- Multi-currency support
- Automatic stock deduction
- Discount management
- Debtor/creditor tracking
- Sales reports

### 2️⃣ Orders (Custom Resin Art)
- Order creation & tracking
- Material cost calculation
- Order profit analysis
- Customer order history
- Delivery tracking

### 3️⃣ Students (Training/Courses)
- Student registration
- Course management
- Fee tracking
- Expense recording
- Progress monitoring
- Profit per student

### 4️⃣ Inventory Management
- Goods receiving (incoming shipments)
- Stock transfers (between branches)
- Outgoing shipments
- Stock tracking with alerts
- Expiry date management
- Product movement history

---

## 💰 Financial Features

✅ Multi-currency transactions (AFN, USD, etc.)
✅ Exchange rate management
✅ Income & expense tracking
✅ Installment reminders
✅ Partial payment support
✅ Financial reports (daily/weekly/monthly)
✅ Profit calculations
✅ PDF export

---

## 🌐 Language & Interface

- **Primary Language:** Persian/Dari (فارسی/دری)
- **Secondary Language:** English
- **Layout:** Full RTL (Right-to-Left) support
- **Responsive Design:** Works on desktop, tablet, mobile

---

## 📄 Reporting Features

Generate reports with custom date ranges:

- 📊 Daily/Weekly/Monthly/Annual Sales Reports
- 📦 Inventory & Stock Balance Reports
- 👥 Debtor & Creditor Reports
- 💰 Financial Summary Reports
- 📋 Supplier-wise Purchase Reports
- 🚚 Shipment Reports
- 👨‍🎓 Student Performance Reports
- 📈 Profit Analysis
- 🔄 Stock Movement History
- ✅ Export to PDF

---

## 🛠️ Tech Stack

| Component | Technology |
|-----------|-------------|
| **Backend** | PHP 7.4+ |
| **Database** | MySQL 5.7+ / MariaDB |
| **Frontend** | HTML5, CSS3, Bootstrap 5 |
| **JavaScript** | Vanilla JS, jQuery |
| **PDF Generation** | TCPDF |
| **Server** | Apache (via XAMPP) |

---

## 🚀 Performance Optimization

✅ Database indexing on frequently queried columns
✅ Query optimization
✅ Session management
✅ Caching strategies
✅ Responsive asset loading
✅ Input validation & sanitization

---

## 🔒 Security Features

✅ Password hashing (bcrypt)
✅ SQL injection prevention
✅ XSS protection
✅ CSRF token validation
✅ Role-based access control
✅ Activity logging
✅ Secure session management

---

## 📞 Support & Documentation

- **Installation Guide:** See `INSTALLATION.md`
- **FAQ:** Check `/docs/FAQ.md`
- **API Reference:** See `/docs/API.md`

---

## 📝 License

This project is developed for Rehnuma business operations.

---

## 🎉 Ready to Start?

1. Download XAMPP
2. Extract project files
3. Run setup wizard
4. Start using your ERP!

**Questions?** Check `INSTALLATION.md` for detailed troubleshooting.

---

**Built with ❤️ for Rehnuma Resin Art Business**