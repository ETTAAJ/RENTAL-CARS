# Car Rental Website - Complete Documentation

## 📋 Table of Contents

1. [Project Overview](#project-overview)
2. [Server Requirements](#server-requirements)
3. [Installation Guide](#installation-guide)
4. [Configuration Guide](#configuration-guide)
5. [Customization](#customization)
6. [Admin Access](#admin-access)
7. [Support & License](#support--license)

---

## 🚗 Project Overview

### Description

This is a complete, professional PHP-based car rental website system with a fully functional admin dashboard. The system allows customers to browse available cars, view detailed specifications, and book vehicles directly through WhatsApp integration. The admin panel provides comprehensive car management, settings configuration, and business customization options.

### Key Features

#### Customer-Facing Features
- **Responsive Car Listing**: Browse all available cars in a modern, responsive grid layout
- **Advanced Filtering**: Filter cars by name, fuel type, gear box, and sort by price
- **Car Details Page**: Comprehensive car information with technical specifications
- **WhatsApp Booking System**: Direct booking integration via WhatsApp (no database storage required)
- **Multi-Currency Support**: Real-time currency conversion (MAD, EUR, USD) with user preference saving
- **Dark/Light Mode**: Theme toggle with persistent user preference
- **Animated Hero Section**: Engaging animated background on homepage
- **Contact Form**: Integrated contact form with WhatsApp integration
- **About Us Page**: Professional company information page
- **Mobile-First Design**: Fully responsive across all devices

#### Admin Panel Features
- **Car Management**: Complete CRUD operations (Create, Read, Update, Delete)
- **Image Upload**: Automatic image management with file naming
- **Technical Specifications**: Manage car specifications (Gear Box, Fuel, Doors, Air Conditioner, Seats, Distance)
- **Discount Management**: Set and manage discount percentages for cars
- **Settings Management**: 
  - Currency exchange rates configuration
  - WhatsApp number setup
  - Logo upload and management
  - Social media links (Facebook, Twitter, Instagram, LinkedIn, YouTube)
- **Advanced Filtering**: Filter cars by availability, name, fuel type, gear box, and price
- **Secure Authentication**: Password-protected admin panel

---

## 💻 Server Requirements

### Minimum Requirements

- **PHP Version**: 7.4 or higher (PHP 8.0+ recommended)
- **MySQL Version**: 5.7 or higher (MySQL 8.0+ or MariaDB 10.3+ recommended)
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **PHP Extensions**:
  - `mysqli` - MySQL database connectivity
  - `gd` or `imagick` - Image processing for uploads
  - `json` - JSON data handling
  - `mbstring` - String manipulation
  - `fileinfo` - File type detection

### Recommended Setup

- **XAMPP** (Windows/Mac/Linux) - Includes Apache, MySQL, and PHP
- **WAMP** (Windows) - Windows Apache MySQL PHP
- **MAMP** (Mac) - Mac Apache MySQL PHP
- **LAMP** (Linux) - Linux Apache MySQL PHP

### Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

---

## 📦 Installation Guide

### Step 1: Upload Files

1. Download or extract the project files
2. Upload all files to your web server directory:
   - **XAMPP**: `C:\xampp\htdocs\rental-cars\`
   - **WAMP**: `C:\wamp64\www\rental-cars\`
   - **MAMP**: `/Applications/MAMP/htdocs/rental-cars/`
   - **Live Server**: Upload via FTP to your `public_html` or `www` directory

3. Ensure the following directories exist and are writable:
   - `assets/images/` - For car images and logo
   - `admin/` - Admin panel files

### Step 2: Create Database

#### Option A: Using phpMyAdmin (Recommended for Beginners)

1. Open phpMyAdmin in your browser (usually `http://localhost/phpmyadmin`)
2. Click "New" to create a new database
3. Enter database name: `car_rental` (or your preferred name)
4. Select collation: `utf8mb4_unicode_ci`
5. Click "Create"

#### Option B: Using Command Line

```sql
CREATE DATABASE car_rental CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Step 3: Import Database

#### Option A: Using phpMyAdmin

1. Select your database from the left sidebar
2. Click the "Import" tab
3. Click "Choose File" and select `car_rental.sql`
4. Click "Go" to import

#### Option B: Using Command Line

```bash
mysql -u root -p car_rental < car_rental.sql
```

### Step 4: Configure Database Connection

1. Open `/config/app.php` in a text editor
2. Locate the `database` section (around line 150)
3. Update the following values:

```php
'database' => [
    'host' => 'localhost',        // Database host (usually 'localhost')
    'user' => 'root',              // Database username
    'pass' => '',                  // Database password (leave empty if no password)
    'name' => 'car_rental',        // Database name you created
],
```

**For Live Servers:**
- `host`: Usually `localhost` or provided by your hosting
- `user`: Your database username (provided by hosting)
- `pass`: Your database password (provided by hosting)
- `name`: Your database name (provided by hosting)

### Step 5: Set File Permissions

**For Linux/Unix Servers:**

```bash
chmod 755 assets/images/
chmod 644 config/app.php
```

**For Windows:**
- Right-click `assets/images/` folder
- Properties → Security → Ensure "Write" permission is enabled

### Step 6: Access Your Website

1. **Local Development:**
   - Open browser: `http://localhost/rental-cars/`
   - Or: `http://localhost/rental-cars/index.php`

2. **Live Server:**
   - Open: `https://yourdomain.com/`
   - Or: `https://yourdomain.com/index.php`

### Step 7: Access Admin Panel

1. Navigate to: `http://localhost/rental-cars/admin/`
2. **Default Credentials:**
   - Username: `admin`
   - Password: `admin123`

⚠️ **IMPORTANT**: Change the default password immediately after first login!

---

## ⚙️ Configuration Guide

### Change Company Name

1. Open `/config/app.php`
2. Find the `site_name` setting (around line 21)
3. Update the value:

```php
'site_name' => 'Your Company Name',
```

Change to:
```php
'site_name' => 'ABC Car Rentals',
```

### Change Logo

#### Method 1: Upload via Admin Panel (Recommended)

1. Log in to admin panel
2. Go to **Settings** page
3. Scroll to "Logo Management"
4. Click "Choose File" and select your logo
5. Click "Update Logo"
6. Supported formats: PNG, JPG, SVG
7. Recommended size: 200x200px or larger

#### Method 2: Manual Upload

1. Upload your logo file to `assets/images/` directory
2. Rename it to `logo.png` (or keep original name)
3. Open `/config/app.php`
4. Update `logo_path`:

```php
'logo_path' => 'assets/images/your-logo.png',
```

### Change WhatsApp Number

1. Open `/config/app.php`
2. Find `whatsapp_number` (around line 52)
3. Update with your number (country code + number, no spaces or +):

```php
'whatsapp_number' => '1234567890',  // Example: 212612345678 for Morocco
```

**Or via Admin Panel:**
1. Log in to admin panel
2. Go to **Settings**
3. Update "WhatsApp Number" field
4. Click "Save Settings"

### Change Email Address

1. Open `/config/app.php`
2. Find `contact_email` (around line 61)
3. Update:

```php
'contact_email' => 'contact@yourcompany.com',
```

### Update Currency Rates

#### Method 1: Via Admin Panel (Recommended)

1. Log in to admin panel
2. Go to **Settings**
3. Find "Currency Exchange Rates" section
4. Update:
   - **MAD to EUR**: Current exchange rate (e.g., 0.092)
   - **MAD to USD**: Current exchange rate (e.g., 0.10)
5. Click "Save Settings"

#### Method 2: Manual Configuration

1. Open `/config/app.php`
2. Find `currency_rates` section (around line 110)
3. Update values:

```php
'currency_rates' => [
    'MAD' => 1.0,      // Base currency
    'EUR' => 0.092,    // Update with current rate
    'USD' => 0.10,     // Update with current rate
],
```

**To find current exchange rates:**
- Visit: https://www.xe.com/currencyconverter/
- Search: "MAD to EUR" or "MAD to USD"

### Apply Discounts to Cars

1. Log in to admin panel
2. Go to **Cars** page
3. Click **Edit** on any car
4. Find "Discount" field
5. Enter discount percentage (0-100):
   - Example: `10` for 10% off
   - Example: `25` for 25% off
6. Click "Update Car"
7. Discount badge will appear on car card automatically

---

## 🎨 Customization

### Change Colors (Theme Colors)

The website uses CSS variables for easy color customization. Colors are defined in `header.php`.

1. Open `header.php`
2. Find the `:root` section (around line 20)
3. Update color variables:

```css
:root {
    --primary-purple: #6C5CE7;    /* Main purple color */
    --primary-orange: #FF6B35;    /* Main orange color */
    --dark-text: #2D3436;         /* Dark text color */
    --light-bg: #F8F9FA;          /* Light background */
}
```

**Example - Change to Blue Theme:**
```css
:root {
    --primary-purple: #3498db;    /* Blue */
    --primary-orange: #e74c3c;    /* Red accent */
}
```

### Edit Homepage Content

1. Open `index.php`
2. Find the hero section (around line 440)
3. Update text:

```php
<h1 class="hero-title">Experience the road like never before</h1>
<p class="hero-subtitle">Your custom subtitle text here...</p>
```

### Edit About Us Page

1. Open `about-us.php`
2. Find content sections
3. Update text, statistics, and features as needed

### Add or Remove Cars

#### Add a New Car

1. Log in to admin panel
2. Click **"Add New Car"** button
3. Fill in the form:
   - **Car Name**: e.g., "Toyota Camry"
   - **Image**: Upload car image (max 5MB)
   - **Price**: Daily rental price (e.g., 500)
   - **Discount**: Percentage (0-100, optional)
   - **Description**: Car description
   - **Availability**: Available or Unavailable
   - **Specifications**:
     - Gear Box: Automat or Manual
     - Fuel: Petrol, Diesel, or Hybrid
     - Doors: Number of doors
     - Air Conditioner: Yes or No
     - Seats: Number of seats
     - Distance: e.g., "500 km"
4. Click **"Add Car"**

#### Edit a Car

1. Go to admin panel → **Cars**
2. Click **"Edit"** on any car
3. Update information
4. Click **"Update Car"**

#### Delete a Car

1. Go to admin panel → **Cars**
2. Click **"Delete"** on any car
3. Confirm deletion
4. Car and its image will be permanently deleted

### Customize Footer

1. Open `footer.php`
2. Find footer content sections
3. Update:
   - Company description
   - Contact information
   - Social media links (configured in admin settings)

### Add Social Media Links

1. Log in to admin panel
2. Go to **Settings**
3. Scroll to "Social Media Links"
4. Enter your social media URLs:
   - Facebook: `https://facebook.com/yourpage`
   - Twitter: `https://twitter.com/yourhandle`
   - Instagram: `https://instagram.com/yourhandle`
   - LinkedIn: `https://linkedin.com/company/yourcompany`
   - YouTube: `https://youtube.com/yourchannel`
5. Leave empty to hide social icons
6. Click **"Save Settings"**

---

## 🔐 Admin Access

### Default Credentials

- **Username**: `admin`
- **Password**: `admin123`

### Security Recommendations

⚠️ **CRITICAL**: Change the default password immediately!

1. Log in to admin panel
2. The default credentials are displayed on the login page (for initial setup only)
3. **Change Password**:
   - Currently, password change must be done directly in the database
   - Future updates may include a password change feature in admin panel

**To change password in database:**

1. Open phpMyAdmin
2. Select your database
3. Go to `users` table
4. Find the admin user
5. Edit the `password` field
6. Use PHP's `password_hash()` function to generate a secure hash:

```php
<?php
echo password_hash('your-new-password', PASSWORD_DEFAULT);
?>
```

7. Copy the generated hash and paste it in the password field
8. Save changes

### Admin Panel Features

- **Car Management**: Full CRUD operations
- **Settings**: Configure currency, WhatsApp, logo, social media
- **Filtering**: Advanced car filtering and sorting
- **Image Management**: Automatic image upload and deletion

### Admin Panel URL

- Local: `http://localhost/rental-cars/admin/`
- Live: `https://yourdomain.com/admin/`

---

## 📞 Support & License

### License Terms

This is a **single-domain license** product. The license includes:

✅ **Allowed:**
- Use on one (1) live website/domain
- Modify and customize for your business
- Use for commercial purposes

❌ **Not Allowed:**
- Redistribution or resale
- Use on multiple domains (requires additional licenses)
- Sharing with third parties
- Creating derivative products for sale

### Support

For support, customization requests, or questions:

1. **Documentation**: Refer to this README and `CUSTOMIZATION_GUIDE.md`
2. **Configuration**: Check `/config/app.php` for all customizable settings
3. **Technical Issues**: Review server requirements and PHP error logs

### System Requirements Reminder

- PHP 7.4+ with required extensions
- MySQL 5.7+ or MariaDB 10.3+
- Apache 2.4+ or Nginx 1.18+
- Write permissions on `assets/images/` directory

### Troubleshooting

#### Common Issues

**Issue**: "Database connection failed"
- **Solution**: Check database credentials in `/config/app.php`
- Verify database exists and user has proper permissions

**Issue**: "Images not uploading"
- **Solution**: Check `assets/images/` folder permissions (must be writable)
- Verify PHP `gd` or `imagick` extension is enabled

**Issue**: "Admin login not working"
- **Solution**: Verify default credentials: `admin` / `admin123`
- Check database `users` table exists and has admin user

**Issue**: "WhatsApp link not working"
- **Solution**: Verify WhatsApp number format in `/config/app.php`
- Format: Country code + number (no spaces, no + sign)
- Example: `212612345678` for Morocco

**Issue**: "Currency conversion not working"
- **Solution**: Check exchange rates in admin Settings
- Verify JavaScript is enabled in browser
- Clear browser cache

### File Structure

```
rental-cars/
├── admin/                  # Admin panel files
│   ├── index.php          # Car management
│   ├── add-car.php        # Add new car
│   ├── edit-car.php       # Edit car
│   ├── settings.php       # System settings
│   ├── login.php          # Admin login
│   └── logout.php         # Logout handler
├── assets/
│   └── images/            # Car images and logo
├── config/
│   └── app.php           # Main configuration file
├── config.php            # Database and helper functions
├── car_rental.sql        # Database schema
├── header.php            # Site header
├── footer.php            # Site footer
├── index.php             # Homepage
├── car-detail.php        # Car details page
├── about-us.php          # About page
├── contact.php           # Contact page
└── README.md             # This file
```

---

## 📝 Additional Notes

### Best Practices

1. **Regular Backups**: Backup your database regularly
2. **Image Optimization**: Optimize car images before upload (recommended: 800x600px, < 500KB)
3. **Security**: Change default admin password immediately
4. **Updates**: Keep PHP and MySQL updated to latest stable versions
5. **Testing**: Test all features after customization

### Performance Tips

- Optimize images before uploading (use tools like TinyPNG)
- Enable PHP OPcache for better performance
- Use a CDN for static assets on live servers
- Regular database optimization

### Browser Compatibility

The website is tested and compatible with:
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+
- Mobile browsers (iOS Safari, Chrome Mobile)

---

## 🎯 Quick Start Checklist

- [ ] Upload files to web server
- [ ] Create database
- [ ] Import `car_rental.sql`
- [ ] Configure database in `/config/app.php`
- [ ] Set file permissions for `assets/images/`
- [ ] Access website: `http://localhost/rental-cars/`
- [ ] Log in to admin: `http://localhost/rental-cars/admin/`
- [ ] Change company name in `/config/app.php`
- [ ] Upload logo via admin panel
- [ ] Update WhatsApp number
- [ ] Update email address
- [ ] Configure currency rates
- [ ] Change default admin password
- [ ] Add your first car
- [ ] Test booking via WhatsApp
- [ ] Customize colors and content

---

**Version**: 1.0  
**Last Updated**: 2024  
**License**: Single Domain License

---

*For detailed customization instructions, see `CUSTOMIZATION_GUIDE.md`*
