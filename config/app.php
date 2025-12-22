<?php
/**
 * APPLICATION CONFIGURATION FILE
 * 
 * This file contains all customizable branding and configuration values.
 * Update these values to customize the application for your business.
 * 
 * IMPORTANT: After modifying this file, clear any caches if applicable.
 */

return [
    // ============================================
    // BRANDING CONFIGURATION
    // ============================================
    
    /**
     * Site Name
     * The name of your car rental business
     * Used in page titles, headers, footers, and throughout the site
     */
    'site_name' => 'Your Company Name',
    
    /**
     * Site Tagline
     * A short description or tagline for your business
     * Used in meta descriptions and some page headers
     */
    'site_tagline' => 'Best Deals on Car Rentals',
    
    /**
     * Logo Path
     * Path to your company logo image file
     * Default: assets/images/logo-placeholder.png
     * 
     * CUSTOMIZATION: Replace 'logo-placeholder.png' with your actual logo filename
     * Supported formats: PNG, JPG, SVG
     */
    'logo_path' => 'assets/images/logo-placeholder.png',
    
    // ============================================
    // CONTACT INFORMATION
    // ============================================
    
    /**
     * WhatsApp Number
     * Your WhatsApp business number for bookings and inquiries
     * Format: Country code + number (no + or spaces)
     * Example: 1234567890
     * 
     * CUSTOMIZATION: Replace with your actual WhatsApp number
     */
    'whatsapp_number' => '1234567890',
    
    /**
     * Contact Email
     * Your business email address
     * Used in contact forms, footer, and email links
     * 
     * CUSTOMIZATION: Replace with your actual business email
     */
    'contact_email' => 'example@email.com',
    
    /**
     * Business Address
     * Your physical business address
     * Used in footer and contact pages
     * 
     * CUSTOMIZATION: Replace with your actual business address
     */
    'business_address' => 'Your Business Address, City, Country',
    
    /**
     * Business Phone
     * Your business phone number (formatted for display)
     * Used in footer and contact pages
     * 
     * CUSTOMIZATION: Replace with your actual phone number
     */
    'business_phone' => '+000000000',
    
    // ============================================
    // DOMAIN & URL CONFIGURATION
    // ============================================
    
    /**
     * Domain Name
     * Your website domain (without http:// or https://)
     * Used in meta tags and some references
     * 
     * CUSTOMIZATION: Replace with your actual domain
     */
    'domain' => 'yourdomain.com',
    
    /**
     * Site URL
     * Full URL to your website (with protocol)
     * Used in meta tags and social sharing
     * 
     * CUSTOMIZATION: Replace with your actual website URL
     */
    'site_url' => 'https://yourdomain.com',
    
    // ============================================
    // CURRENCY CONFIGURATION
    // ============================================
    
    /**
     * Default Currency
     * The base currency for your pricing
     * Options: USD, EUR, GBP, etc. (use ISO currency codes)
     * 
     * CUSTOMIZATION: Change to your local currency code
     */
    'default_currency' => 'USD',
    
    /**
     * Exchange Rates
     * Exchange rates from base currency to other currencies
     * Base currency rate should always be 1.0
     * 
     * CUSTOMIZATION: Update rates according to your base currency
     * These can also be managed from Admin Panel > Settings
     * Example: If USD is your base currency, 1 USD = 1.0, then set EUR and other rates accordingly
     */
    'currency_rates' => [
        'USD' => 1.0,    // Base currency (always 1.0)
        'EUR' => 0.92,   // 1 USD = 0.92 EUR (example - update with current rates)
        'GBP' => 0.79,   // 1 USD = 0.79 GBP (example - update with current rates)
    ],
    
    // ============================================
    // SOCIAL MEDIA LINKS
    // ============================================
    
    /**
     * Social Media URLs
     * Links to your social media profiles
     * Leave empty string if you don't use a platform
     * 
     * CUSTOMIZATION: Replace with your actual social media URLs
     * These can also be managed from Admin Panel > Settings
     */
    'social_media' => [
        'facebook' => '',
        'twitter' => '',
        'instagram' => '',
        'linkedin' => '',
        'youtube' => '',
    ],
    
    // ============================================
    // DATABASE CONFIGURATION
    // ============================================
    
    /**
     * Database Settings
     * Database connection parameters
     * 
     * CUSTOMIZATION: Update with your database credentials
     * Note: These are kept here for reference but actual DB config
     * should remain in config.php for security
     */
    'database' => [
        'host' => 'localhost',
        'user' => 'root',
        'pass' => '',
        'name' => 'car_rental',
    ],
    
    // ============================================
    // ADMIN PANEL CONFIGURATION
    // ============================================
    
    /**
     * Admin Default Credentials
     * Default admin username and password
     * IMPORTANT: Change these after first login!
     * 
     * NOTE: Default credentials are documented in README.md
     * For security, these are not stored in code but created during installation
     */
    'admin' => [
        'default_username' => 'admin',
        // Password is not stored here for security reasons
        // Default password is documented in README.md
    ],
    
    // ============================================
    // ADDITIONAL SETTINGS
    // ============================================
    
    /**
     * Operating Hours
     * Display text for business operating hours
     * 
     * CUSTOMIZATION: Update with your actual operating hours
     */
    'operating_hours' => '24/7 - Always Available',
    
    /**
     * Meta Description
     * Default meta description for SEO
     * 
     * CUSTOMIZATION: Update with your business description
     */
    'meta_description' => 'Your trusted partner for car rentals. We offer the best deals on quality vehicles with exceptional service.',
    
    /**
     * Footer Description
     * Description text shown in footer
     * 
     * CUSTOMIZATION: Update with your business description
     */
    'footer_description' => 'Your trusted partner for car rentals. We offer the best deals on quality vehicles with exceptional service.',
];

