<?php
/**
 * MAIN CONFIGURATION FILE
 * 
 * This file loads the centralized application configuration and provides
 * helper functions for accessing configuration values.
 * 
 * CUSTOMIZATION: Most branding values should be changed in /config/app.php
 */

// Load centralized application configuration
$appConfig = require_once __DIR__ . '/config/app.php';

// Database configuration
// CUSTOMIZATION: Update these with your database credentials
define('DB_HOST', $appConfig['database']['host']);
define('DB_USER', $appConfig['database']['user']);
define('DB_PASS', $appConfig['database']['pass']);
define('DB_NAME', $appConfig['database']['name']);

/**
 * Get application configuration value
 * 
 * @param string $key Configuration key (supports dot notation, e.g., 'social_media.facebook')
 * @param mixed $default Default value if key not found
 * @return mixed Configuration value
 */
function getAppConfig($key, $default = null) {
    global $appConfig;
    $keys = explode('.', $key);
    $value = $appConfig;
    
    foreach ($keys as $k) {
        if (isset($value[$k])) {
            $value = $value[$k];
        } else {
            return $default;
        }
    }
    
    return $value;
}

/**
 * Get site name
 * CUSTOMIZATION: Change in /config/app.php
 */
function getSiteName() {
    return getAppConfig('site_name', 'Your Company Name');
}

/**
 * Get site tagline
 * CUSTOMIZATION: Change in /config/app.php
 */
function getSiteTagline() {
    return getAppConfig('site_tagline', 'Best Deals on Car Rentals');
}

/**
 * Get contact email
 * CUSTOMIZATION: Change in /config/app.php
 */
function getContactEmail() {
    return getAppConfig('contact_email', 'example@email.com');
}

/**
 * Get business address
 * CUSTOMIZATION: Change in /config/app.php
 */
function getBusinessAddress() {
    return getAppConfig('business_address', 'Your Business Address, City, Country');
}

/**
 * Get business phone
 * CUSTOMIZATION: Change in /config/app.php
 */
function getBusinessPhone() {
    return getAppConfig('business_phone', '+000000000');
}

/**
 * Get domain name
 * CUSTOMIZATION: Change in /config/app.php
 */
function getDomain() {
    return getAppConfig('domain', 'yourdomain.com');
}

/**
 * Get site URL
 * CUSTOMIZATION: Change in /config/app.php
 */
function getSiteUrl() {
    return getAppConfig('site_url', 'https://yourdomain.com');
}

// WhatsApp number for booking notifications (format: country code + number, no + or spaces)
// Load from database if available, otherwise use config file
function getWhatsAppNumber() {
    try {
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT setting_value FROM settings WHERE setting_key = 'whatsapp_number'");
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $number = trim($row['setting_value']);
            if (!empty($number)) {
                $stmt->close();
                $conn->close();
                return $number;
            }
        }
        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        // Continue to default
    }
    // CUSTOMIZATION: Change default WhatsApp number in /config/app.php
    return getAppConfig('whatsapp_number', '000000000');
}

define('WHATSAPP_NUMBER', getWhatsAppNumber());

// Format WhatsApp number for display (adds + and formatting)
function getFormattedPhoneNumber() {
    $number = WHATSAPP_NUMBER;
    // Remove any non-digit characters
    $number = preg_replace('/\D/', '', $number);
    
    // Format based on length
    if (strlen($number) >= 10) {
        // Format: +212 653-330752 (Morocco format) or similar
        if (strlen($number) == 12 && substr($number, 0, 3) == '212') {
            // Morocco: +212 653-330752
            return '+' . substr($number, 0, 3) . ' ' . substr($number, 3, 3) . '-' . substr($number, 6);
        } elseif (strlen($number) == 12 && substr($number, 0, 3) == '996') {
            // Kyrgyzstan: +996 247-1680
            return '+' . substr($number, 0, 3) . ' ' . substr($number, 3, 3) . '-' . substr($number, 6);
        } else {
            // Default formatting: +XX XXX-XXXXXX
            $countryCode = substr($number, 0, strlen($number) - 9);
            $rest = substr($number, strlen($countryCode));
            if (strlen($rest) >= 6) {
                return '+' . $countryCode . ' ' . substr($rest, 0, 3) . '-' . substr($rest, 3);
            }
        }
    }
    
    // Fallback: just add +
    return '+' . $number;
}

// Create database connection
function getDBConnection() {
    try {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        return $conn;
    } catch (Exception $e) {
        die("Database connection error: " . $e->getMessage());
    }
}

// Helper function to calculate discounted price
function calculateDiscountedPrice($price, $discount) {
    if ($discount > 0) {
        return $price - ($price * $discount / 100);
    }
    return $price;
}

// Exchange rates (base currency: MAD)
// Load from database if available, otherwise use config file
// CUSTOMIZATION: Change default rates in /config/app.php
function getExchangeRates() {
    try {
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT setting_key, setting_value FROM settings WHERE setting_key IN ('mad_to_eur', 'mad_to_usd')");
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Get default rates from config
        $defaultRates = getAppConfig('currency_rates', ['MAD' => 1.0, 'EUR' => 0.092, 'USD' => 0.10]);
        $rates = $defaultRates;
        
        while ($row = $result->fetch_assoc()) {
            if ($row['setting_key'] === 'mad_to_eur') {
                $rates['EUR'] = floatval($row['setting_value']);
            } elseif ($row['setting_key'] === 'mad_to_usd') {
                $rates['USD'] = floatval($row['setting_value']);
            }
        }
        
        $stmt->close();
        $conn->close();
        return $rates;
    } catch (Exception $e) {
        // Return defaults from config if database error
        return getAppConfig('currency_rates', ['MAD' => 1.0, 'EUR' => 0.092, 'USD' => 0.10]);
    }
}

$exchangeRates = getExchangeRates();
define('EXCHANGE_RATES', $exchangeRates);

// Helper function to format price
function formatPrice($price) {
    // Format with 2 decimals, then remove trailing zeros
    $formatted = number_format($price, 2, '.', '');
    // Remove trailing zeros and decimal point if not needed
    return rtrim(rtrim($formatted, '0'), '.');
}

// Helper function to format discount percentage
function formatDiscount($discount) {
    // Format with 2 decimals, then remove trailing zeros
    $formatted = number_format($discount, 2, '.', '');
    // Remove trailing zeros and decimal point if not needed
    return rtrim(rtrim($formatted, '0'), '.');
}

// Helper function to convert price from MAD to another currency
function convertPrice($priceMAD, $toCurrency = 'MAD') {
    if ($toCurrency === 'MAD' || !isset(EXCHANGE_RATES[$toCurrency])) {
        return $priceMAD;
    }
    return $priceMAD * EXCHANGE_RATES[$toCurrency];
}

// Helper function to get currency symbol
function getCurrencySymbol($currency = 'MAD') {
    $symbols = [
        'MAD' => 'MAD',
        'EUR' => '€',
        'USD' => '$'
    ];
    return $symbols[$currency] ?? 'MAD';
}

// Helper function to get logo path from database
// CUSTOMIZATION: Change default logo path in /config/app.php
function getLogoPath() {
    try {
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT setting_value FROM settings WHERE setting_key = 'logo_path'");
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $logoPath = trim($row['setting_value']);
            if (!empty($logoPath)) {
                // Check if file exists (handle both admin and root contexts)
                $checkPath = (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../' . $logoPath : $logoPath;
                if (file_exists($checkPath)) {
                    $stmt->close();
                    $conn->close();
                    return $logoPath;
                }
            }
        }
        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        // Continue to default
    }
    // CUSTOMIZATION: Change default logo path in /config/app.php
    return getAppConfig('logo_path', 'assets/images/logo-placeholder.png');
}

// Helper function to get social media URL from database
// CUSTOMIZATION: Change default social media URLs in /config/app.php
function getSocialMediaUrl($platform) {
    try {
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
        $key = $platform . '_url';
        $stmt->bind_param("s", $key);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $url = trim($row['setting_value']);
            if (!empty($url)) {
                $stmt->close();
                $conn->close();
                return $url;
            }
        }
        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        // Continue to default
    }
    // CUSTOMIZATION: Change default social media URLs in /config/app.php
    return getAppConfig('social_media.' . $platform, '');
}

// Admin authentication functions
function requireAdminLogin() {
    // Start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Check if admin is logged in
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        // Get the current script name to determine the login redirect path
        $currentFile = basename($_SERVER['PHP_SELF']);
        $loginUrl = 'login.php';
        
        // If we're in a subdirectory, adjust the path
        if (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) {
            $loginUrl = 'login.php';
        }
        
        header('Location: ' . $loginUrl);
        exit;
    }
}

function isAdminLoggedIn() {
    // Start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}
?>

