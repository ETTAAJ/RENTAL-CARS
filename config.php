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
        // Generic formatting: +XX XXX-XXXXXX
        // Automatically detects country code and formats accordingly
        $countryCode = substr($number, 0, strlen($number) - 9);
        $rest = substr($number, strlen($countryCode));
        if (strlen($rest) >= 6) {
            return '+' . $countryCode . ' ' . substr($rest, 0, 3) . '-' . substr($rest, 3);
        }
        // Alternative format for 12-digit numbers: +XXX XXX-XXXXXX
        if (strlen($number) == 12) {
            return '+' . substr($number, 0, 3) . ' ' . substr($number, 3, 3) . '-' . substr($number, 6);
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
            error_log("Database connection failed: " . $conn->connect_error);
            // Don't expose database errors to users
            header('HTTP/1.1 500 Internal Server Error');
            die("Database connection error. Please contact the administrator.");
        }
        
        return $conn;
    } catch (Exception $e) {
        error_log("Database connection error: " . $e->getMessage());
        // Don't expose database errors to users
        header('HTTP/1.1 500 Internal Server Error');
        die("Database connection error. Please contact the administrator.");
    }
}

// Helper function to calculate discounted price
function calculateDiscountedPrice($price, $discount) {
    if ($discount > 0) {
        return $price - ($price * $discount / 100);
    }
    return $price;
}

// Get all active currencies from database
function getCurrencies() {
    try {
        $conn = getDBConnection();
        // Check if currencies table exists
        $tableCheck = $conn->query("SHOW TABLES LIKE 'currencies'");
        if ($tableCheck->num_rows == 0) {
            $conn->close();
            return [];
        }
        
        $stmt = $conn->prepare("SELECT code, name, symbol, rate_to_base, is_base FROM currencies WHERE is_active = 1 ORDER BY is_base DESC, code ASC");
        $stmt->execute();
        $result = $stmt->get_result();
        $currencies = [];
        
        while ($row = $result->fetch_assoc()) {
            $currencies[] = $row;
        }
        
        $stmt->close();
        $conn->close();
        return $currencies;
    } catch (Exception $e) {
        return [];
    }
}

// Get base currency from database
function getBaseCurrency() {
    try {
        $conn = getDBConnection();
        $tableCheck = $conn->query("SHOW TABLES LIKE 'currencies'");
        if ($tableCheck->num_rows == 0) {
            $conn->close();
            return 'USD'; // Default fallback
        }
        
        $stmt = $conn->prepare("SELECT code FROM currencies WHERE is_base = 1 AND is_active = 1 LIMIT 1");
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $base = $row['code'];
            $stmt->close();
            $conn->close();
            return $base;
        }
        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        // Continue to fallback
    }
    
    // Fallback to default currency from settings or config
    try {
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT setting_value FROM settings WHERE setting_key = 'default_currency'");
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $default = $row['setting_value'];
            $stmt->close();
            $conn->close();
            return $default;
        }
        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        // Continue to config fallback
    }
    
    return getAppConfig('default_currency', 'USD');
}

// Exchange rates - Load from currencies table
// CUSTOMIZATION: Manage currencies from Admin Panel > Settings > Currency Management
function getExchangeRates() {
    try {
        $conn = getDBConnection();
        $tableCheck = $conn->query("SHOW TABLES LIKE 'currencies'");
        if ($tableCheck->num_rows == 0) {
            $conn->close();
            // Fallback to legacy settings or config
            return getLegacyExchangeRates();
        }
        
        // Get base currency
        $baseCurrency = getBaseCurrency();
        
        // Get all currencies
        $stmt = $conn->prepare("SELECT code, rate_to_base FROM currencies WHERE is_active = 1");
        $stmt->execute();
        $result = $stmt->get_result();
        
        $rates = [];
        while ($row = $result->fetch_assoc()) {
            $rates[$row['code']] = floatval($row['rate_to_base']);
        }
        
        $stmt->close();
        $conn->close();
        
        // Ensure base currency is always 1.0
        if (isset($rates[$baseCurrency])) {
            $rates[$baseCurrency] = 1.0;
        }
        
        return $rates;
    } catch (Exception $e) {
        return getLegacyExchangeRates();
    }
}

// Legacy exchange rates (for backward compatibility)
function getLegacyExchangeRates() {
    try {
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT setting_key, setting_value FROM settings WHERE setting_key IN ('mad_to_eur', 'mad_to_usd', 'base_to_eur', 'base_to_usd')");
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Get default rates from config
        $defaultRates = getAppConfig('currency_rates', ['USD' => 1.0, 'EUR' => 0.92, 'GBP' => 0.79]);
        $rates = $defaultRates;
        
        $baseCurrency = getBaseCurrency();
        $rates[$baseCurrency] = 1.0; // Base currency always 1.0
        
        while ($row = $result->fetch_assoc()) {
            if ($row['setting_key'] === 'mad_to_eur' || $row['setting_key'] === 'base_to_eur') {
                $rates['EUR'] = floatval($row['setting_value']);
            } elseif ($row['setting_key'] === 'mad_to_usd' || $row['setting_key'] === 'base_to_usd') {
                $rates['USD'] = floatval($row['setting_value']);
            }
        }
        
        $stmt->close();
        $conn->close();
        return $rates;
    } catch (Exception $e) {
        // Return defaults from config if database error
        $defaultRates = getAppConfig('currency_rates', ['USD' => 1.0, 'EUR' => 0.92, 'GBP' => 0.79]);
        $baseCurrency = getBaseCurrency();
        $defaultRates[$baseCurrency] = 1.0;
        return $defaultRates;
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

// Helper function to convert price from base currency to another currency
function convertPrice($priceBase, $toCurrency = null) {
    if ($toCurrency === null) {
        $toCurrency = getBaseCurrency();
    }
    
    $baseCurrency = getBaseCurrency();
    
    // If converting to base currency or currency not found, return original price
    if ($toCurrency === $baseCurrency || !isset(EXCHANGE_RATES[$toCurrency])) {
        return $priceBase;
    }
    
    // Convert from base currency to target currency
    // rate_to_base represents: 1 base currency = X target currency
    // So to convert: priceBase * EXCHANGE_RATES[toCurrency]
    $targetRate = EXCHANGE_RATES[$toCurrency] ?? 1.0;
    
    return $priceBase * $targetRate;
}

// Helper function to get currency symbol from database
function getCurrencySymbol($currency = null) {
    if ($currency === null) {
        $currency = getBaseCurrency();
    }
    
    try {
        $conn = getDBConnection();
        $tableCheck = $conn->query("SHOW TABLES LIKE 'currencies'");
        if ($tableCheck->num_rows > 0) {
            $stmt = $conn->prepare("SELECT symbol FROM currencies WHERE code = ? AND is_active = 1 LIMIT 1");
            $stmt->bind_param("s", $currency);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc() && !empty($row['symbol'])) {
                $symbol = $row['symbol'];
                $stmt->close();
                $conn->close();
                return $symbol;
            }
            $stmt->close();
        }
        $conn->close();
    } catch (Exception $e) {
        // Continue to fallback
    }
    
    // Fallback to default symbols
    $symbols = [
        'MAD' => 'MAD',
        'EUR' => '€',
        'USD' => '$',
        'GBP' => '£'
    ];
    return $symbols[$currency] ?? $currency;
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

// CSRF Protection Functions
function generateCSRFToken() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCSRFToken($token) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function getCSRFTokenField() {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(generateCSRFToken()) . '">';
}
?>

