# Customization Guide - Generic Version

This document summarizes all the changes made to create a generic, resellable version of the car rental system.

## Summary of Changes

### 1. Centralized Configuration File
**Created:** `/config/app.php`

This file contains all customizable branding and configuration values:
- Site name, tagline, and logo path
- Contact information (email, phone, address)
- WhatsApp number
- Domain and site URL
- Currency exchange rates
- Social media links
- Database credentials (reference)
- Admin default credentials

**All values are clearly documented with comments indicating where customization is needed.**

### 2. Updated Configuration System
**Modified:** `config.php`

- Added `getAppConfig()` function to access configuration values
- Added helper functions: `getSiteName()`, `getSiteTagline()`, `getContactEmail()`, `getBusinessAddress()`, `getBusinessPhone()`, `getDomain()`, `getSiteUrl()`
- Updated all default values to use config file instead of hardcoded values
- Added CUSTOMIZATION comments throughout

### 3. Replaced Hardcoded Branding Values

#### Files Updated:

**header.php**
- Replaced "RENTAL CARS" with `getSiteName()`
- Updated logo alt text to use config
- Updated page title to use config

**footer.php**
- Replaced "RENTAL CARS" with `getSiteName()`
- Replaced hardcoded email with `getContactEmail()`
- Replaced hardcoded address with `getBusinessAddress()`
- Updated footer description to use config

**index.php**
- Updated page title to use `getSiteName()`

**contact.php**
- Updated page title to use `getSiteName()`
- Replaced hardcoded email with `getContactEmail()`
- Updated WhatsApp message to include site name from config

**car-detail.php**
- Updated page title to use `getSiteName()`

**about-us.php**
- Updated page title to use `getSiteName()`

**admin/login.php**
- Updated page title to use `getSiteName()`

**admin/settings.php**
- Replaced "RENTAL-CARS.png" with generic logo filename from config
- Updated placeholder values to use config defaults
- Added CUSTOMIZATION comments

### 4. Default Values Replaced

| Original Value | New Generic Value | Location |
|---------------|------------------|----------|
| "RENTAL CARS" | "Your Company Name" | `/config/app.php` |
| "212769323828" | "1234567890" | `/config/app.php` |
| "info@carrental.com" | "example@email.com" | `/config/app.php` |
| "Oxford Ave. Cary, NC 27511" | "Your Business Address, City, Country" | `/config/app.php` |
| "RENTAL-CARS.png" | "logo-placeholder.png" | `/config/app.php` |
| "contact@rentalcars.com" | "example@email.com" | `/config/app.php` |
| "rental-cars" (folder) | N/A (kept as is) | N/A |

### 5. Documentation Updates

**README.md**
- Added customization guide at the top
- Updated configuration instructions to reference `/config/app.php`
- Added note about generic version
- Updated default values documentation

## How to Customize

### Step 1: Update Branding
1. Open `/config/app.php`
2. Update `site_name` with your company name
3. Update `logo_path` with your logo file path
4. Replace the logo file at the specified path

### Step 2: Update Contact Information
1. In `/config/app.php`, update:
   - `contact_email` - Your business email
   - `whatsapp_number` - Your WhatsApp number (format: country code + number, no + or spaces)
   - `business_address` - Your physical address
   - `business_phone` - Your phone number

### Step 3: Update Domain & URLs
1. In `/config/app.php`, update:
   - `domain` - Your website domain
   - `site_url` - Full URL to your website

### Step 4: Configure Currency

The system includes a complete Currency Management system accessible from the admin panel.

#### Via Admin Panel (Recommended)

1. Log in to admin panel
2. Go to **Settings** → **Currency Management**
3. Click **"Add New Currency"** to add currencies
4. For each currency, provide:
   - Currency Code (e.g., USD, EUR, GBP)
   - Currency Name (e.g., US Dollar, Euro)
   - Currency Symbol (e.g., $, €, £)
   - Exchange Rate (relative to base currency)
5. Set one currency as **Base Currency** (rate will be 1.0)
6. You can edit, delete, or change the base currency anytime

#### Via Config File (Fallback)

If no currencies exist in the database, the system will use values from `/config/app.php`:
1. Open `/config/app.php`
2. Update:
   - `default_currency` - Your base currency code (e.g., 'USD')
   - `currency_rates` - Exchange rates (base currency should be 1.0)

**Note**: Once currencies are added via admin panel, the database values take precedence over config file values.

### Step 5: Add Social Media Links
1. In `/config/app.php`, update the `social_media` array with your social media URLs
2. Leave empty string if you don't use a platform

### Step 6: Database Configuration
1. Update database credentials in `config.php` or `/config/app.php`
2. Import your database schema

## Important Notes

1. **Logo File**: Make sure to place your logo file at the path specified in `logo_path` (default: `assets/images/logo-placeholder.png`)

2. **WhatsApp Number**: Format should be country code + number without + or spaces (e.g., "1234567890")

3. **Currency Management**: 
   - Use Admin Panel > Settings > Currency Management for full CRUD operations
   - Add, edit, delete currencies and set base currency dynamically
   - Exchange rates are managed per currency
   - Database values override config file values once currencies are added
   - The base currency rate is always 1.0 and cannot be changed

4. **Social Media**: Links can also be managed from Admin Panel > Settings

5. **Admin Credentials**: Default admin credentials are in `/config/app.php`. **Change these after first login!**

## Files Modified

- `/config/app.php` (NEW)
- `config.php`
- `header.php`
- `footer.php`
- `index.php`
- `contact.php`
- `car-detail.php`
- `about-us.php`
- `admin/login.php`
- `admin/settings.php` (Currency CRUD system)
- `admin/index.php` (Dynamic currency display)
- `admin/add-car.php` (Dynamic currency display)
- `admin/edit-car.php` (Dynamic currency display)
- `README.md`

## Testing Checklist

After customization, test:
- [ ] Site name appears correctly in all pages
- [ ] Logo displays correctly
- [ ] Contact email links work
- [ ] WhatsApp booking functionality works
- [ ] Footer displays correct information
- [ ] Admin panel loads correctly
- [ ] Currency Management system works (add, edit, delete, set base)
- [ ] Currency conversion works on frontend
- [ ] Base currency displays correctly in admin panel
- [ ] All prices show correct currency code (not hardcoded)
- [ ] Social media links work (if configured)

## Support

For issues or questions about customization, refer to the comments in `/config/app.php` which provide detailed guidance for each configuration option.

