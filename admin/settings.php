<?php
require_once '../config.php';

$pageTitle = 'Settings - Admin Panel';
include 'header.php';

$conn = getDBConnection();

// Create settings table if it doesn't exist
$createTable = "CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
$conn->query($createTable);

// Create currencies table if it doesn't exist
$createCurrenciesTable = "CREATE TABLE IF NOT EXISTS currencies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(10) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    symbol VARCHAR(10) DEFAULT '',
    rate_to_base DECIMAL(10,6) DEFAULT 1.000000,
    is_active TINYINT(1) DEFAULT 1,
    is_base TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
$conn->query($createCurrenciesTable);

// Check if is_base column exists, if not add it (migration for existing tables)
$checkColumn = $conn->query("SHOW COLUMNS FROM currencies LIKE 'is_base'");
if ($checkColumn->num_rows == 0) {
    $conn->query("ALTER TABLE currencies ADD COLUMN is_base TINYINT(1) DEFAULT 0 AFTER is_active");
    // Set first currency as base if none exists
    $firstCurrency = $conn->query("SELECT id FROM currencies ORDER BY id LIMIT 1");
    if ($firstCurrency && $firstCurrency->num_rows > 0) {
        $firstRow = $firstCurrency->fetch_assoc();
        $firstId = $firstRow['id'];
        $conn->query("UPDATE currencies SET is_base = 1, rate_to_base = 1.0 WHERE id = $firstId");
    }
}

// Check if rate_to_base column exists, if not add it (migration for existing tables)
$checkRateColumn = $conn->query("SHOW COLUMNS FROM currencies LIKE 'rate_to_base'");
if ($checkRateColumn->num_rows == 0) {
    $conn->query("ALTER TABLE currencies ADD COLUMN rate_to_base DECIMAL(10,6) DEFAULT 1.000000 AFTER symbol");
    // Set default rates for existing currencies
    $conn->query("UPDATE currencies SET rate_to_base = 1.0 WHERE rate_to_base IS NULL OR rate_to_base = 0");
}

// Initialize default currencies if table is empty
$checkCurrencies = $conn->query("SELECT COUNT(*) as count FROM currencies");
$currencyCount = $checkCurrencies->fetch_assoc()['count'];
if ($currencyCount == 0) {
    $defaultCurrencies = [
        ['code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$', 'rate_to_base' => 1.000000, 'is_base' => 1],
        ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€', 'rate_to_base' => 0.92],
        ['code' => 'GBP', 'name' => 'British Pound', 'symbol' => '£', 'rate_to_base' => 0.79],
        ['code' => 'MAD', 'name' => 'Dirham', 'symbol' => 'MAD', 'rate_to_base' => 10.0]
    ];
    $stmt = $conn->prepare("INSERT INTO currencies (code, name, symbol, rate_to_base, is_base) VALUES (?, ?, ?, ?, ?)");
    foreach ($defaultCurrencies as $currency) {
        $isBase = $currency['is_base'] ?? 0;
        $stmt->bind_param("sssdi", $currency['code'], $currency['name'], $currency['symbol'], $currency['rate_to_base'], $isBase);
        $stmt->execute();
    }
    $stmt->close();
}

$successMessage = '';
$errorMessage = '';

// Handle Currency CRUD operations
if (isset($_GET['action']) && isset($_GET['currency_id'])) {
    $action = $_GET['action'];
    $currencyId = intval($_GET['currency_id']);
    
    if ($action === 'delete') {
        // Check if currency is being used as default
        $checkDefault = $conn->prepare("SELECT setting_value FROM settings WHERE setting_key = 'default_currency'");
        $checkDefault->execute();
        $result = $checkDefault->get_result();
        $defaultCurrency = $result->fetch_assoc()['setting_value'] ?? '';
        $checkDefault->close();
        
        // Get currency code
        $getCurrency = $conn->prepare("SELECT code, is_base FROM currencies WHERE id = ?");
        $getCurrency->bind_param("i", $currencyId);
        $getCurrency->execute();
        $currencyResult = $getCurrency->get_result();
        $currency = $currencyResult->fetch_assoc();
        $getCurrency->close();
        
        if ($currency && $currency['is_base'] == 1) {
            $errorMessage = "Cannot delete the base currency. Please set another currency as base first.";
        } elseif ($currency && $currency['code'] === $defaultCurrency) {
            $errorMessage = "Cannot delete the default currency. Please change the default currency first.";
        } else {
            $deleteStmt = $conn->prepare("DELETE FROM currencies WHERE id = ?");
            $deleteStmt->bind_param("i", $currencyId);
            if ($deleteStmt->execute()) {
                $successMessage = "Currency deleted successfully!";
            } else {
                $errorMessage = "Failed to delete currency.";
            }
            $deleteStmt->close();
        }
    } elseif ($action === 'set_base') {
        // Set currency as base (set all others to not base, update rates)
        $conn->begin_transaction();
        try {
            // Get current base currency rate
            $getCurrentBase = $conn->query("SELECT code, rate_to_base FROM currencies WHERE is_base = 1 LIMIT 1");
            $currentBase = $getCurrentBase->fetch_assoc();
            
            // Get new base currency
            $getNewBase = $conn->prepare("SELECT code, rate_to_base FROM currencies WHERE id = ?");
            $getNewBase->bind_param("i", $currencyId);
            $getNewBase->execute();
            $newBaseResult = $getNewBase->get_result();
            $newBase = $newBaseResult->fetch_assoc();
            $getNewBase->close();
            
            if ($newBase && $currentBase) {
                // Calculate conversion factor
                $conversionFactor = $currentBase['rate_to_base'] / $newBase['rate_to_base'];
                
                // Update all rates relative to new base
                $updateRates = $conn->prepare("UPDATE currencies SET rate_to_base = rate_to_base * ?");
                $updateRates->bind_param("d", $conversionFactor);
                $updateRates->execute();
                $updateRates->close();
            }
            
            // Set all currencies to not base
            $conn->query("UPDATE currencies SET is_base = 0");
            
            // Set selected currency as base
            $setBase = $conn->prepare("UPDATE currencies SET is_base = 1, rate_to_base = 1.0 WHERE id = ?");
            $setBase->bind_param("i", $currencyId);
            $setBase->execute();
            $setBase->close();
            
            $conn->commit();
            $successMessage = "Base currency updated successfully!";
        } catch (Exception $e) {
            $conn->rollback();
            $errorMessage = "Failed to update base currency: " . $e->getMessage();
        }
    }
}

// Handle Currency Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['currency_action'])) {
    $currencyAction = $_POST['currency_action'];
    $currencyCode = strtoupper(trim($_POST['currency_code'] ?? ''));
    $currencyName = trim($_POST['currency_name'] ?? '');
    $currencySymbol = trim($_POST['currency_symbol'] ?? '');
    $currencyRate = floatval($_POST['currency_rate'] ?? 1.0);
    $currencyId = isset($_POST['currency_id']) ? intval($_POST['currency_id']) : 0;
    $isBase = isset($_POST['is_base']) ? intval($_POST['is_base']) : 0;
    
    // Validation
    if (empty($currencyCode) || empty($currencyName)) {
        $errorMessage = "Currency code and name are required.";
    } elseif (strlen($currencyCode) > 10) {
        $errorMessage = "Currency code must be 10 characters or less.";
    } elseif ($currencyRate <= 0) {
        $errorMessage = "Exchange rate must be greater than 0.";
    } else {
        if ($currencyAction === 'add') {
            // Check if code already exists
            $checkCode = $conn->prepare("SELECT id FROM currencies WHERE code = ?");
            $checkCode->bind_param("s", $currencyCode);
            $checkCode->execute();
            if ($checkCode->get_result()->num_rows > 0) {
                $errorMessage = "Currency code already exists.";
            } else {
                // If setting as base, unset other bases
                if ($isBase) {
                    $conn->query("UPDATE currencies SET is_base = 0");
                    $currencyRate = 1.0; // Base currency always has rate 1.0
                }
                
                $insertStmt = $conn->prepare("INSERT INTO currencies (code, name, symbol, rate_to_base, is_base) VALUES (?, ?, ?, ?, ?)");
                $insertStmt->bind_param("sssdi", $currencyCode, $currencyName, $currencySymbol, $currencyRate, $isBase);
                if ($insertStmt->execute()) {
                    $successMessage = "Currency added successfully!";
                } else {
                    $errorMessage = "Failed to add currency.";
                }
                $insertStmt->close();
            }
            $checkCode->close();
        } elseif ($currencyAction === 'edit' && $currencyId > 0) {
            // Check if code already exists (excluding current currency)
            $checkCode = $conn->prepare("SELECT id FROM currencies WHERE code = ? AND id != ?");
            $checkCode->bind_param("si", $currencyCode, $currencyId);
            $checkCode->execute();
            if ($checkCode->get_result()->num_rows > 0) {
                $errorMessage = "Currency code already exists.";
            } else {
                // Get old currency data
                $getOldCurrency = $conn->prepare("SELECT code, is_base, rate_to_base FROM currencies WHERE id = ?");
                $getOldCurrency->bind_param("i", $currencyId);
                $getOldCurrency->execute();
                $oldCurrencyResult = $getOldCurrency->get_result();
                $oldCurrency = $oldCurrencyResult->fetch_assoc();
                $getOldCurrency->close();
                
                // If setting as base, unset other bases and set rate to 1.0
                if ($isBase && (!$oldCurrency || $oldCurrency['is_base'] != 1)) {
                    // Calculate conversion factor if changing base
                    if ($oldCurrency && $oldCurrency['rate_to_base'] > 0) {
                        $conversionFactor = $oldCurrency['rate_to_base'];
                        $conn->query("UPDATE currencies SET rate_to_base = rate_to_base / $conversionFactor");
                    }
                    $conn->query("UPDATE currencies SET is_base = 0");
                    $currencyRate = 1.0;
                } elseif ($oldCurrency && $oldCurrency['is_base'] == 1 && !$isBase) {
                    // Cannot unset base without setting another as base
                    $errorMessage = "Cannot remove base currency status. Please set another currency as base first.";
                    $checkCode->close();
                    goto skip_edit;
                }
                
                $updateStmt = $conn->prepare("UPDATE currencies SET code = ?, name = ?, symbol = ?, rate_to_base = ?, is_base = ? WHERE id = ?");
                $updateStmt->bind_param("sssdii", $currencyCode, $currencyName, $currencySymbol, $currencyRate, $isBase, $currencyId);
                if ($updateStmt->execute()) {
                    $successMessage = "Currency updated successfully!";
                } else {
                    $errorMessage = "Failed to update currency.";
                }
                $updateStmt->close();
            }
            $checkCode->close();
        }
        skip_edit:
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $baseToEur = floatval($_POST['base_to_eur'] ?? 0.92);
    $baseToUsd = floatval($_POST['base_to_usd'] ?? 1.0);
    $defaultCurrency = $_POST['default_currency'] ?? 'USD';
    $whatsappNumber = trim($_POST['whatsapp_number'] ?? '');
    
    // Social media links
    $facebookUrl = trim($_POST['facebook_url'] ?? '');
    $twitterUrl = trim($_POST['twitter_url'] ?? '');
    $instagramUrl = trim($_POST['instagram_url'] ?? '');
    $linkedinUrl = trim($_POST['linkedin_url'] ?? '');
    $youtubeUrl = trim($_POST['youtube_url'] ?? '');
    
    // Handle logo upload
    $logoPath = '';
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../assets/images/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $file = $_FILES['logo'];
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        
        // Validate file type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, $allowedTypes)) {
            $errorMessage = 'Invalid logo file type. Please upload a JPEG, PNG, GIF, or WebP image.';
        } elseif ($file['size'] > $maxSize) {
            $errorMessage = 'Logo file size too large. Maximum size is 5MB.';
        } else {
            // Generate filename
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            // CUSTOMIZATION: Logo filename - change in /config/app.php
            $logoName = 'logo-placeholder'; // Default from config
            $filename = $logoName . '.' . $extension;
            $uploadPath = $uploadDir . $filename;
            
            // Delete old logo if exists (supports multiple formats)
            $oldLogo = $uploadDir . $logoName . '.*';
            $oldFiles = glob($oldLogo);
            foreach ($oldFiles as $oldFile) {
                if (is_file($oldFile)) {
                    unlink($oldFile);
                }
            }
            
            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                $logoPath = 'assets/images/' . $filename;
            } else {
                $errorMessage = "Failed to upload logo. Please try again.";
            }
        }
    }
    
    // Validate
    if (empty($errorMessage)) {
        if ($baseToEur <= 0 || $baseToUsd <= 0) {
            $errorMessage = 'Exchange rates must be greater than 0.';
        } else {
            // Validate default currency exists in currencies table
            $checkCurrency = $conn->prepare("SELECT id FROM currencies WHERE code = ? AND is_active = 1");
            $checkCurrency->bind_param("s", $defaultCurrency);
            $checkCurrency->execute();
            if ($checkCurrency->get_result()->num_rows === 0) {
                $errorMessage = 'Invalid default currency. Please select a valid currency from the list.';
            } else {
                // Calculate rates based on selected base currency
                // Store rates as: base_to_eur and base_to_usd (for backward compatibility, keep mad_to_* keys)
                $settings = [
                'mad_to_eur' => $baseToEur,  // Keep key name for backward compatibility
                'mad_to_usd' => $baseToUsd,  // Keep key name for backward compatibility
                'base_to_eur' => $baseToEur,  // New generic key
                'base_to_usd' => $baseToUsd,  // New generic key
                'default_currency' => $defaultCurrency,
                'whatsapp_number' => $whatsappNumber,
                'facebook_url' => $facebookUrl,
                'twitter_url' => $twitterUrl,
                'instagram_url' => $instagramUrl,
                'linkedin_url' => $linkedinUrl,
                'youtube_url' => $youtubeUrl
            ];
            
            // Add logo path if uploaded
            if (!empty($logoPath)) {
                $settings['logo_path'] = $logoPath;
            }
            
            $allSuccess = true;
            foreach ($settings as $key => $value) {
                $stmt = $conn->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?, updated_at = CURRENT_TIMESTAMP");
                $stmt->bind_param("sss", $key, $value, $value);
                if (!$stmt->execute()) {
                    $allSuccess = false;
                }
                $stmt->close();
            }
            
            if ($allSuccess) {
                $successMessage = "Settings saved successfully!";
            } else {
                $errorMessage = "Failed to save some settings.";
            }
            }
            $checkCurrency->close();
        }
    }
}

// Load current settings
$stmt = $conn->prepare("SELECT setting_key, setting_value FROM settings");
$stmt->execute();
$result = $stmt->get_result();
$settings = [];
while ($row = $result->fetch_assoc()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}
$stmt->close();

// Load currencies from database
$currencies = [];
$currenciesQuery = $conn->query("SELECT * FROM currencies WHERE is_active = 1 ORDER BY is_base DESC, code ASC");
while ($row = $currenciesQuery->fetch_assoc()) {
    $currencies[] = $row;
}

// Get base currency
$baseCurrency = null;
foreach ($currencies as $curr) {
    if ($curr['is_base'] == 1) {
        $baseCurrency = $curr;
        break;
    }
}
if (!$baseCurrency && !empty($currencies)) {
    // If no base set, set first currency as base
    $baseCurrency = $currencies[0];
    $conn->query("UPDATE currencies SET is_base = 1, rate_to_base = 1.0 WHERE id = " . $baseCurrency['id']);
    $baseCurrency['is_base'] = 1;
    $baseCurrency['rate_to_base'] = 1.0;
}

// Set defaults if not set
// Try new generic keys first, fall back to old keys for backward compatibility
$baseToEur = $settings['base_to_eur'] ?? $settings['mad_to_eur'] ?? 0.92;
$baseToUsd = $settings['base_to_usd'] ?? $settings['mad_to_usd'] ?? 1.0;
$defaultCurrency = $settings['default_currency'] ?? ($baseCurrency ? $baseCurrency['code'] : 'USD');
$whatsappNumber = $settings['whatsapp_number'] ?? WHATSAPP_NUMBER;

// Get currency for editing (if edit action)
$editCurrency = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['currency_id'])) {
    $editId = intval($_GET['currency_id']);
    $editStmt = $conn->prepare("SELECT * FROM currencies WHERE id = ?");
    $editStmt->bind_param("i", $editId);
    $editStmt->execute();
    $editResult = $editStmt->get_result();
    $editCurrency = $editResult->fetch_assoc();
    $editStmt->close();
}
// CUSTOMIZATION: Default logo path - change in /config/app.php
$logoPath = $settings['logo_path'] ?? getAppConfig('logo_path', 'assets/images/logo-placeholder.png');
$facebookUrl = $settings['facebook_url'] ?? '';
$twitterUrl = $settings['twitter_url'] ?? '';
$instagramUrl = $settings['instagram_url'] ?? '';
$linkedinUrl = $settings['linkedin_url'] ?? '';
$youtubeUrl = $settings['youtube_url'] ?? '';

// Check if logo file exists
$logoFullPath = '../' . $logoPath;
if (!file_exists($logoFullPath)) {
    // CUSTOMIZATION: Default logo path - change in /config/app.php
    $logoPath = getAppConfig('logo_path', 'assets/images/logo-placeholder.png');
}

$conn->close();
?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-sliders"></i> Settings</h2>
            <a href="index.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Admin Panel
            </a>
        </div>

        <?php if ($successMessage): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle"></i> <?php echo htmlspecialchars($successMessage); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($errorMessage): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($errorMessage); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="" enctype="multipart/form-data">
                    <!-- Logo Settings -->
                    <h5 class="mb-3" style="color: #6C5CE7; font-weight: 700;">
                        <i class="bi bi-image"></i> Logo Settings
                    </h5>
                    
                    <div class="mb-4">
                        <label for="logo" class="form-label">Website Logo</label>
                        <input type="file" class="form-control" id="logo" name="logo" 
                               accept="image/jpeg,image/jpg,image/png,image/gif,image/webp"
                               onchange="previewLogo(this)">
                        <small class="text-muted">Upload a new logo to replace the current one (JPEG, PNG, GIF, or WebP, Max 5MB).</small>
                        <div class="mt-3">
                            <p class="mb-2"><strong>Current Logo:</strong></p>
                            <img src="../<?php echo htmlspecialchars($logoPath); ?>" 
                                 alt="Current Logo" 
                                 id="currentLogo"
                                 style="max-width: 200px; height: auto; border-radius: 8px; border: 1px solid #ddd; padding: 5px; background: white;"
                                 onerror="this.src='../<?php echo htmlspecialchars(getAppConfig('logo_path', 'assets/images/logo-placeholder.png')); ?>'">
                        </div>
                        <div id="logoPreview" class="mt-3" style="display: none;">
                            <p class="mb-2"><strong>New Logo Preview:</strong></p>
                            <img id="previewLogoImg" src="" alt="Preview" style="max-width: 200px; height: auto; border-radius: 8px; border: 1px solid #ddd; padding: 5px; background: white;">
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Currency Management (CRUD) -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0" style="color: #6C5CE7; font-weight: 700;">
                            <i class="bi bi-currency-exchange"></i> Currency Management
                        </h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#currencyModal" onclick="resetCurrencyForm()">
                            <i class="bi bi-plus-circle"></i> Add New Currency
                        </button>
                    </div>
                    
                    <style>
                        .currency-card {
                            background: white;
                            border: 2px solid #e9ecef;
                            border-radius: 16px;
                            padding: 1.5rem;
                            transition: all 0.3s ease;
                            height: 100%;
                            position: relative;
                            overflow: hidden;
                        }
                        
                        .currency-card:hover {
                            transform: translateY(-5px);
                            box-shadow: 0 8px 25px rgba(108, 92, 231, 0.15);
                            border-color: #6C5CE7;
                        }
                        
                        .currency-card.base-currency {
                            background: linear-gradient(135deg, #6C5CE7 0%, #FF6B35 100%);
                            color: white;
                            border-color: #6C5CE7;
                        }
                        
                        .currency-card.base-currency .currency-code,
                        .currency-card.base-currency .currency-name,
                        .currency-card.base-currency .currency-symbol,
                        .currency-card.base-currency .currency-rate,
                        .currency-card.base-currency .currency-label {
                            color: white;
                        }
                        
                        .currency-card.base-currency .badge {
                            background: rgba(255, 255, 255, 0.25) !important;
                            color: white !important;
                            border: 1px solid rgba(255, 255, 255, 0.3);
                        }
                        
                        .currency-icon {
                            width: 60px;
                            height: 60px;
                            background: linear-gradient(135deg, #6C5CE7, #FF6B35);
                            border-radius: 12px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            font-size: 1.5rem;
                            color: white;
                            margin-bottom: 1rem;
                        }
                        
                        .currency-card.base-currency .currency-icon {
                            background: rgba(255, 255, 255, 0.2);
                            backdrop-filter: blur(10px);
                        }
                        
                        .currency-code {
                            font-size: 1.75rem;
                            font-weight: 800;
                            color: #2D3436;
                            margin-bottom: 0.25rem;
                        }
                        
                        .currency-name {
                            font-size: 0.95rem;
                            color: #636e72;
                            margin-bottom: 0.5rem;
                            font-weight: 500;
                        }
                        
                        .currency-symbol {
                            font-size: 1.5rem;
                            color: #6C5CE7;
                            font-weight: 600;
                            margin-bottom: 0.75rem;
                        }
                        
                        .currency-rate {
                            font-size: 1.1rem;
                            color: #2D3436;
                            font-weight: 600;
                            margin-bottom: 0.5rem;
                        }
                        
                        .currency-label {
                            font-size: 0.85rem;
                            color: #636e72;
                            text-transform: uppercase;
                            letter-spacing: 0.5px;
                        }
                        
                        .currency-actions {
                            margin-top: 1.25rem;
                            padding-top: 1.25rem;
                            border-top: 1px solid #e9ecef;
                            display: flex;
                            gap: 0.5rem;
                            flex-wrap: wrap;
                        }
                        
                        .currency-card.base-currency .currency-actions {
                            border-top-color: rgba(255, 255, 255, 0.2);
                        }
                        
                        .currency-actions .btn {
                            flex: 1;
                            min-width: 80px;
                            font-size: 0.85rem;
                            padding: 0.5rem 0.75rem;
                        }
                        
                        .base-badge {
                            position: absolute;
                            top: 1rem;
                            right: 1rem;
                        }
                    </style>
                    
                    <?php if (empty($currencies)): ?>
                        <div class="card text-center py-5" style="border: 2px dashed #dee2e6; background: #f8f9fa;">
                            <div class="card-body">
                                <i class="bi bi-currency-exchange" style="font-size: 3rem; color: #adb5bd; margin-bottom: 1rem;"></i>
                                <h5 class="text-muted">No Currencies Found</h5>
                                <p class="text-muted mb-3">Get started by adding your first currency</p>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#currencyModal" onclick="resetCurrencyForm()">
                                    <i class="bi bi-plus-circle"></i> Add Your First Currency
                                </button>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="row g-4">
                            <?php foreach ($currencies as $curr): ?>
                                <div class="col-md-6 col-lg-4">
                                    <div class="currency-card <?php echo $curr['is_base'] == 1 ? 'base-currency' : ''; ?>">
                                        <?php if ($curr['is_base'] == 1): ?>
                                            <span class="badge bg-light text-dark base-badge">
                                                <i class="bi bi-star-fill"></i> Base Currency
                                            </span>
                                        <?php endif; ?>
                                        
                                        <div class="currency-icon">
                                            <i class="bi bi-cash-coin"></i>
                                        </div>
                                        
                                        <div class="currency-code">
                                            <?php echo htmlspecialchars($curr['code']); ?>
                                        </div>
                                        
                                        <div class="currency-name">
                                            <?php echo htmlspecialchars($curr['name']); ?>
                                        </div>
                                        
                                        <?php if (!empty($curr['symbol'])): ?>
                                            <div class="currency-symbol">
                                                <?php echo htmlspecialchars($curr['symbol']); ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="currency-rate">
                                            <?php echo number_format($curr['rate_to_base'], 6); ?>
                                        </div>
                                        <div class="currency-label">
                                            Rate to Base
                                        </div>
                                        
                                        <div class="currency-actions">
                                            <?php if ($curr['is_base'] != 1): ?>
                                                <a href="?action=set_base&currency_id=<?php echo $curr['id']; ?>" 
                                                   class="btn btn-sm btn-info" 
                                                   onclick="return confirm('Set <?php echo htmlspecialchars($curr['code']); ?> as base currency? All rates will be recalculated.')"
                                                   title="Set as Base Currency"
                                                   style="background: #17a2b8; border-color: #17a2b8;">
                                                    <i class="bi bi-star"></i> Set Base
                                                </a>
                                            <?php endif; ?>
                                            <button type="button" 
                                                    class="btn btn-sm btn-warning" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#currencyModal"
                                                    onclick="editCurrency(<?php echo htmlspecialchars(json_encode($curr)); ?>)"
                                                    style="background: #ffc107; border-color: #ffc107; color: #000;">
                                                <i class="bi bi-pencil"></i> Edit
                                            </button>
                                            <a href="?action=delete&currency_id=<?php echo $curr['id']; ?>" 
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('Are you sure you want to delete this currency? This action cannot be undone.')"
                                               style="background: #dc3545; border-color: #dc3545;">
                                                <i class="bi bi-trash"></i> Delete
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Currency Modal for Add/Edit -->
                    <div class="modal fade" id="currencyModal" tabindex="-1" aria-labelledby="currencyModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="POST" action="">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="currencyModalLabel">
                                            <i class="bi bi-currency-exchange"></i> 
                                            <span id="modalTitle">Add New Currency</span>
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="currency_action" id="currency_action" value="add">
                                        <input type="hidden" name="currency_id" id="currency_id" value="0">
                                        
                                        <div class="mb-3">
                                            <label for="currency_code" class="form-label">Currency Code *</label>
                                            <input type="text" class="form-control" id="currency_code" name="currency_code" 
                                                   maxlength="10" required placeholder="e.g., USD, EUR, GBP"
                                                   pattern="[A-Z]{2,10}" title="2-10 uppercase letters">
                                            <small class="text-muted">ISO currency code (e.g., USD, EUR, GBP). 2-10 uppercase letters.</small>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="currency_name" class="form-label">Currency Name *</label>
                                            <input type="text" class="form-control" id="currency_name" name="currency_name" 
                                                   maxlength="100" required placeholder="e.g., US Dollar, Euro">
                                            <small class="text-muted">Full name of the currency.</small>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="currency_symbol" class="form-label">Currency Symbol</label>
                                            <input type="text" class="form-control" id="currency_symbol" name="currency_symbol" 
                                                   maxlength="10" placeholder="e.g., $, €, £">
                                            <small class="text-muted">Symbol to display with prices (optional).</small>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="currency_rate" class="form-label">Exchange Rate to Base Currency *</label>
                                            <input type="number" class="form-control" id="currency_rate" name="currency_rate" 
                                                   step="0.000001" min="0.000001" required value="1.0">
                                            <small class="text-muted">
                                                Rate relative to base currency. Base currency rate is always 1.0.
                                                <span id="base-currency-info">Current base: <?php echo $baseCurrency ? htmlspecialchars($baseCurrency['code']) : 'None'; ?></span>
                                            </small>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="is_base" name="is_base" value="1">
                                                <label class="form-check-label" for="is_base">
                                                    Set as Base Currency
                                                </label>
                                            </div>
                                            <small class="text-muted">Base currency rate is always 1.0. Setting this will recalculate all other rates.</small>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-check-circle"></i> Save Currency
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Currency Exchange Rates (Legacy - for backward compatibility) -->
                    <h5 class="mb-3" style="color: #6C5CE7; font-weight: 700;">
                        <i class="bi bi-sliders"></i> Legacy Exchange Rates
                    </h5>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i> <strong>Note:</strong> These fields are for backward compatibility. It's recommended to manage currencies using the Currency Management section above.
                    </div>
                    
                    <div class="mb-4">
                        <label for="default_currency" class="form-label">Default Currency *</label>
                        <select class="form-select" id="default_currency" name="default_currency" required>
                            <?php foreach ($currencies as $curr): ?>
                                <option value="<?php echo htmlspecialchars($curr['code']); ?>" <?php echo $defaultCurrency === $curr['code'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($curr['code']); ?> - <?php echo htmlspecialchars($curr['name']); ?>
                                    <?php if ($curr['is_base'] == 1): ?> (Base)<?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted">This will be the default currency displayed on the website.</small>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="base_to_eur" class="form-label"><?php echo htmlspecialchars($defaultCurrency); ?> to EUR Exchange Rate *</label>
                            <input type="number" class="form-control" id="base_to_eur" name="base_to_eur" 
                                   step="0.0001" min="0.0001" required 
                                   value="<?php echo htmlspecialchars($baseToEur); ?>">
                            <small class="text-muted">1 <?php echo htmlspecialchars($defaultCurrency); ?> = <span id="eur-rate-display"><?php echo htmlspecialchars($baseToEur); ?></span> EUR</small>
                        </div>
                        <div class="col-md-6">
                            <label for="base_to_usd" class="form-label"><?php echo htmlspecialchars($defaultCurrency); ?> to USD Exchange Rate *</label>
                            <input type="number" class="form-control" id="base_to_usd" name="base_to_usd" 
                                   step="0.0001" min="0.0001" required 
                                   value="<?php echo htmlspecialchars($baseToUsd); ?>">
                            <small class="text-muted">1 <?php echo htmlspecialchars($defaultCurrency); ?> = <span id="usd-rate-display"><?php echo htmlspecialchars($baseToUsd); ?></span> USD</small>
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> <strong>Note:</strong> These rates convert from your base currency (<?php echo htmlspecialchars($defaultCurrency); ?>) to EUR and USD. If your base currency is USD, the USD rate should be 1.0. If EUR, the EUR rate should be 1.0.
                    </div>

                    <hr class="my-4">

                    <!-- General Settings -->
                    <h5 class="mb-3" style="color: #6C5CE7; font-weight: 700;">
                        <i class="bi bi-gear"></i> General Settings
                    </h5>

                    <div class="mb-4">
                        <label for="whatsapp_number" class="form-label">WhatsApp Number *</label>
                        <input type="text" class="form-control" id="whatsapp_number" name="whatsapp_number" 
                               required 
                               value="<?php echo htmlspecialchars($whatsappNumber); ?>"
                               placeholder="<?php echo htmlspecialchars(getAppConfig('whatsapp_number', '1234567890')); ?>">
                        <small class="text-muted">Format: Country code + number (no + or spaces). Example: <?php echo htmlspecialchars(getAppConfig('whatsapp_number', '1234567890')); ?></small>
                    </div>

                    <hr class="my-4">

                    <!-- Social Media Settings -->
                    <h5 class="mb-3" style="color: #6C5CE7; font-weight: 700;">
                        <i class="bi bi-share"></i> Social Media Links
                    </h5>
                    
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label for="facebook_url" class="form-label">
                                <i class="bi bi-facebook" style="color: #1877F2;"></i> Facebook URL
                            </label>
                            <input type="url" class="form-control" id="facebook_url" name="facebook_url" 
                                   value="<?php echo htmlspecialchars($facebookUrl); ?>"
                                   placeholder="https://facebook.com/yourpage">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="twitter_url" class="form-label">
                                <i class="bi bi-twitter" style="color: #1DA1F2;"></i> Twitter URL
                            </label>
                            <input type="url" class="form-control" id="twitter_url" name="twitter_url" 
                                   value="<?php echo htmlspecialchars($twitterUrl); ?>"
                                   placeholder="https://twitter.com/yourhandle">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="instagram_url" class="form-label">
                                <i class="bi bi-instagram" style="color: #E4405F;"></i> Instagram URL
                            </label>
                            <input type="url" class="form-control" id="instagram_url" name="instagram_url" 
                                   value="<?php echo htmlspecialchars($instagramUrl); ?>"
                                   placeholder="https://instagram.com/yourhandle">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="linkedin_url" class="form-label">
                                <i class="bi bi-linkedin" style="color: #0077B5;"></i> LinkedIn URL
                            </label>
                            <input type="url" class="form-control" id="linkedin_url" name="linkedin_url" 
                                   value="<?php echo htmlspecialchars($linkedinUrl); ?>"
                                   placeholder="https://linkedin.com/company/yourcompany">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="youtube_url" class="form-label">
                                <i class="bi bi-youtube" style="color: #FF0000;"></i> YouTube URL
                            </label>
                            <input type="url" class="form-control" id="youtube_url" name="youtube_url" 
                                   value="<?php echo htmlspecialchars($youtubeUrl); ?>"
                                   placeholder="https://youtube.com/@yourchannel">
                        </div>
                    </div>
                    <small class="text-muted d-block mb-4">Leave fields empty to hide social media icons in the footer.</small>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-circle"></i> Save Settings
                        </button>
                        <a href="index.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Card -->
        <div class="card mt-4" style="background: #E3F2FD; border: none;">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-info-circle"></i> Information</h6>
                <p class="card-text mb-0" style="font-size: 0.9rem;">
                    <strong>Exchange Rates:</strong> These rates are used to convert prices on the website. Update them regularly to reflect current market rates.<br>
                    <strong>Default Currency:</strong> This is the currency that will be shown by default when users first visit the site.<br>
                    <strong>WhatsApp Number:</strong> This number will be used for booking notifications sent via WhatsApp.<br>
                    <strong>Social Media Links:</strong> Add your social media URLs to display them in the website footer. Leave fields empty to hide icons.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
function previewLogo(input) {
    const preview = document.getElementById('logoPreview');
    const previewImg = document.getElementById('previewLogoImg');
    const currentLogo = document.getElementById('currentLogo');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
            if (currentLogo) {
                currentLogo.style.opacity = '0.5';
            }
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.style.display = 'none';
        if (currentLogo) {
            currentLogo.style.opacity = '1';
        }
    }
}

// Currency Management Functions
function resetCurrencyForm() {
    document.getElementById('currency_action').value = 'add';
    document.getElementById('currency_id').value = '0';
    document.getElementById('modalTitle').textContent = 'Add New Currency';
    document.getElementById('currency_code').value = '';
    document.getElementById('currency_name').value = '';
    document.getElementById('currency_symbol').value = '';
    document.getElementById('currency_rate').value = '1.0';
    document.getElementById('currency_code').readOnly = false;
    document.getElementById('is_base').checked = false;
    document.getElementById('currency_rate').readOnly = false;
    document.getElementById('currency_rate').style.backgroundColor = '';
    updateBaseCurrencyInfo();
}

function editCurrency(currency) {
    document.getElementById('currency_action').value = 'edit';
    document.getElementById('currency_id').value = currency.id;
    document.getElementById('modalTitle').textContent = 'Edit Currency';
    document.getElementById('currency_code').value = currency.code;
    document.getElementById('currency_name').value = currency.name;
    document.getElementById('currency_symbol').value = currency.symbol || '';
    document.getElementById('currency_rate').value = currency.rate_to_base;
    document.getElementById('is_base').checked = currency.is_base == 1;
    document.getElementById('currency_code').readOnly = true; // Prevent code change
    updateBaseCurrencyInfo();
    
    // If editing base currency, lock rate to 1.0
    if (currency.is_base == 1) {
        document.getElementById('currency_rate').value = '1.0';
        document.getElementById('currency_rate').readOnly = true;
        document.getElementById('currency_rate').style.backgroundColor = '#f8f9fa';
        document.getElementById('is_base').disabled = true;
    } else {
        document.getElementById('currency_rate').readOnly = false;
        document.getElementById('currency_rate').style.backgroundColor = '';
        document.getElementById('is_base').disabled = false;
    }
}

function updateBaseCurrencyInfo() {
    const isBaseChecked = document.getElementById('is_base').checked;
    const rateInput = document.getElementById('currency_rate');
    
    if (isBaseChecked) {
        rateInput.value = '1.0';
        rateInput.readOnly = true;
        rateInput.style.backgroundColor = '#f8f9fa';
    } else {
        rateInput.readOnly = false;
        rateInput.style.backgroundColor = '';
    }
}

// Update currency labels dynamically
document.addEventListener('DOMContentLoaded', function() {
    // Handle is_base checkbox change
    const isBaseCheckbox = document.getElementById('is_base');
    if (isBaseCheckbox) {
        isBaseCheckbox.addEventListener('change', updateBaseCurrencyInfo);
    }
    
    // Auto-uppercase currency code
    const currencyCodeInput = document.getElementById('currency_code');
    if (currencyCodeInput) {
        currencyCodeInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    }
    
    // Auto-open modal if editing currency from URL
    <?php if ($editCurrency): ?>
    const editCurrencyData = <?php echo json_encode($editCurrency); ?>;
    const modal = new bootstrap.Modal(document.getElementById('currencyModal'));
    editCurrency(editCurrencyData);
    modal.show();
    <?php endif; ?>
    
    const currencySelect = document.getElementById('default_currency');
    const eurLabel = document.querySelector('label[for="base_to_eur"]');
    const usdLabel = document.querySelector('label[for="base_to_usd"]');
    const eurInput = document.getElementById('base_to_eur');
    const usdInput = document.getElementById('base_to_usd');
    const eurDisplay = document.getElementById('eur-rate-display');
    const usdDisplay = document.getElementById('usd-rate-display');
    const infoAlert = document.querySelector('.alert-info');
    
    function updateCurrencyLabels() {
        const selectedCurrency = currencySelect.value;
        
        if (eurLabel) {
            eurLabel.textContent = selectedCurrency + ' to EUR Exchange Rate *';
        }
        if (usdLabel) {
            usdLabel.textContent = selectedCurrency + ' to USD Exchange Rate *';
        }
        
        // Update small text
        const eurSmall = eurInput?.parentElement.querySelector('small');
        const usdSmall = usdInput?.parentElement.querySelector('small');
        if (eurSmall) {
            eurSmall.innerHTML = '1 ' + selectedCurrency + ' = <span id="eur-rate-display">' + (eurInput?.value || '0') + '</span> EUR';
        }
        if (usdSmall) {
            usdSmall.innerHTML = '1 ' + selectedCurrency + ' = <span id="usd-rate-display">' + (usdInput?.value || '0') + '</span> USD';
        }
        
        // Update info alert and lock rates for base currency
        if (infoAlert) {
            // Reset readonly states
            if (usdInput) {
                usdInput.readOnly = false;
                usdInput.style.backgroundColor = '';
            }
            if (eurInput) {
                eurInput.readOnly = false;
                eurInput.style.backgroundColor = '';
            }
            
            // Lock the rate that matches the base currency to 1.0
            if (selectedCurrency === 'USD' && usdInput) {
                usdInput.value = '1.0';
                usdInput.readOnly = true;
                usdInput.style.backgroundColor = '#f8f9fa';
                infoAlert.innerHTML = '<i class="bi bi-info-circle"></i> <strong>Note:</strong> Since USD is your base currency, the USD rate is locked at 1.0. Enter the USD to EUR conversion rate in the EUR field.';
            } else if (selectedCurrency === 'EUR' && eurInput) {
                eurInput.value = '1.0';
                eurInput.readOnly = true;
                eurInput.style.backgroundColor = '#f8f9fa';
                infoAlert.innerHTML = '<i class="bi bi-info-circle"></i> <strong>Note:</strong> Since EUR is your base currency, the EUR rate is locked at 1.0. Enter the EUR to USD conversion rate in the USD field.';
            } else {
                infoAlert.innerHTML = '<i class="bi bi-info-circle"></i> <strong>Note:</strong> These rates convert from your base currency (' + selectedCurrency + ') to EUR and USD. Enter the current exchange rates.';
            }
        }
        
        // Update display spans
        if (eurDisplay) {
            eurDisplay.textContent = eurInput?.value || '0';
        }
        if (usdDisplay) {
            usdDisplay.textContent = usdInput?.value || '0';
        }
    }
    
    if (currencySelect) {
        currencySelect.addEventListener('change', updateCurrencyLabels);
        updateCurrencyLabels(); // Initial update
    }
    
    // Update displays when inputs change
    if (eurInput) {
        eurInput.addEventListener('input', function() {
            if (eurDisplay) {
                eurDisplay.textContent = this.value;
            }
        });
    }
    
    if (usdInput) {
        usdInput.addEventListener('input', function() {
            if (usdDisplay) {
                usdDisplay.textContent = this.value;
            }
        });
    }
});
</script>

<?php include 'footer.php'; ?>

