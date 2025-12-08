<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'car_rental');

// WhatsApp number for booking notifications (format: country code + number, no + or spaces)
// Load from database if available, otherwise use default
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
    return '212769323828'; // Default
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
// Load from database if available, otherwise use defaults
function getExchangeRates() {
    try {
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT setting_key, setting_value FROM settings WHERE setting_key IN ('mad_to_eur', 'mad_to_usd')");
        $stmt->execute();
        $result = $stmt->get_result();
        $rates = ['MAD' => 1.0, 'EUR' => 0.092, 'USD' => 0.10];
        
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
        // Return defaults if database error
        return ['MAD' => 1.0, 'EUR' => 0.092, 'USD' => 0.10];
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
function getLogoPath() {
    try {
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT setting_value FROM settings WHERE setting_key = 'logo_path'");
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $logoPath = trim($row['setting_value']);
            if (!empty($logoPath) && file_exists('../' . $logoPath)) {
                $stmt->close();
                $conn->close();
                return $logoPath;
            }
        }
        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        // Continue to default
    }
    return 'assets/images/RENTAL-CARS.png'; // Default
}

// Helper function to get social media URL from database
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
    return ''; // Return empty if not set
}
?>

