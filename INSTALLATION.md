# Rehnuma ERP - Installation Guide

## Prerequisites

1. **XAMPP** - Download from: https://www.apachefriends.org/download.html
2. **Browser** - Chrome, Firefox, Edge, or Safari
3. **Basic knowledge** of PHP and MySQL

## Step-by-Step Installation

### Step 1: Download and Install XAMPP

**Windows:**
- Download XAMPP installer for Windows
- Run the installer
- Choose installation directory (default: C:\xampp)
- Complete installation

**Linux:**
```bash
cd ~/Downloads
chmod +x xampp-linux-x64-*.run
sudo ./xampp-linux-x64-*.run
```

**macOS:**
- Download XAMPP for macOS
- Open the DMG file
- Drag XAMPP to Applications folder

### Step 2: Extract Project Files

**Windows:**
```
C:\xampp\htdocs\rehnuma-erp\
```

**Linux/macOS:**
```
/opt/lampp/htdocs/rehnuma-erp/
```

### Step 3: Start XAMPP Services

**Windows:**
- Open XAMPP Control Panel
- Click "Start" for Apache
- Click "Start" for MySQL

**Linux:**
```bash
sudo /opt/lampp/lampp start
```

**macOS:**
```bash
sudo /Applications/XAMPP/xamppfiles/xampp start
```

### Step 4: Run Setup Wizard

1. Open browser and go to: `http://localhost/rehnuma-erp/setup.php`
2. Wait for database setup to complete
3. You'll see confirmation message with default login credentials
4. **Delete setup.php file** after setup completes

### Step 5: Login

1. Navigate to: `http://localhost/rehnuma-erp`
2. Use default credentials:
   - **Email:** admin@rehnuma.com
   - **Password:** admin123
3. Click "ورود به سیستم" (Login)

## Default Setup Information

### Database
- **Name:** rehnuma_erp
- **Host:** localhost
- **User:** root
- **Password:** (empty)
- **Charset:** utf8mb4

### Admin User
- **Email:** admin@rehnuma.com
- **Password:** admin123
- **Role:** Admin (Full Access)

### Base Currency
- **AFN** (Afghan Afghani) - Primary currency

### Main Branch
- **Name:** Main Branch
- **Location:** Kabul
- **Status:** Active

### Default Measurement Units
- Kilogram (kg) - Weight
- Gram (g) - Weight
- Liter (L) - Volume
- Milliliter (ml) - Volume
- Piece (pc) - Count
- Box (box) - Count

## Troubleshooting

### "Connection refused" Error
- Ensure Apache and MySQL are running in XAMPP
- Check that XAMPP is listening on port 80 (Apache) and 3306 (MySQL)

### "Database not found" Error
- Run setup.php again
- Ensure MySQL is running and accessible

### "Permission denied" Error (Linux/macOS)
- Run with sudo: `sudo /opt/lampp/lampp start`

### "404 Not Found" Error
- Verify files are in correct directory
- Check XAMPP DocumentRoot setting

## File Permissions (Linux/macOS)

```bash
chmod -R 755 /opt/lampp/htdocs/rehnuma-erp
chmod -R 777 /opt/lampp/htdocs/rehnuma-erp/logs
chmod -R 777 /opt/lampp/htdocs/rehnuma-erp/cache
```

## Next Steps

1. **Change Admin Password** - Go to Admin Settings
2. **Create Additional Users** - Add branch managers and accountants
3. **Add Products** - Start adding items to your inventory
4. **Configure Exchange Rates** - Set up currency rates
5. **Add Suppliers** - Enter supplier information
6. **Create Branches** - Add additional branch locations

## Support

For issues, check:
- XAMPP logs in xampp/logs/
- Browser console (F12) for JavaScript errors
- MySQL error log for database issues

## Security Notes

⚠️ **Important:**
- Change default admin password immediately
- Don't expose your ERP on the internet without HTTPS
- Use strong passwords for all users
- Regularly backup your database
- Keep XAMPP updated

## Backup Instructions

**MySQL Database Backup:**

1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Select database: rehnuma_erp
3. Click "Export"
4. Click "Go" to download SQL file

**Files Backup:**
- Copy entire rehnuma-erp folder to safe location

## Performance Tips

1. Increase PHP memory limit in php.ini
2. Enable query caching in MySQL
3. Use indexes on frequently searched columns
4. Regular database optimization
5. Clean old logs periodically
