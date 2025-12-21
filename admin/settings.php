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

$successMessage = '';
$errorMessage = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $madToEur = floatval($_POST['mad_to_eur'] ?? 0.092);
    $madToUsd = floatval($_POST['mad_to_usd'] ?? 0.10);
    $defaultCurrency = $_POST['default_currency'] ?? 'MAD';
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
        if ($madToEur <= 0 || $madToUsd <= 0) {
            $errorMessage = 'Exchange rates must be greater than 0.';
        } elseif (!in_array($defaultCurrency, ['MAD', 'EUR', 'USD'])) {
            $errorMessage = 'Invalid default currency.';
        } else {
            // Save settings
            $settings = [
                'mad_to_eur' => $madToEur,
                'mad_to_usd' => $madToUsd,
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

// Set defaults if not set
$madToEur = $settings['mad_to_eur'] ?? 0.092;
$madToUsd = $settings['mad_to_usd'] ?? 0.10;
$defaultCurrency = $settings['default_currency'] ?? 'MAD';
$whatsappNumber = $settings['whatsapp_number'] ?? WHATSAPP_NUMBER;
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

                    <!-- Currency Settings -->
                    <h5 class="mb-3" style="color: #6C5CE7; font-weight: 700;">
                        <i class="bi bi-currency-exchange"></i> Currency Settings
                    </h5>
                    
                    <div class="mb-4">
                        <label for="default_currency" class="form-label">Default Currency *</label>
                        <select class="form-select" id="default_currency" name="default_currency" required>
                            <option value="MAD" <?php echo $defaultCurrency === 'MAD' ? 'selected' : ''; ?>>MAD (Moroccan Dirham)</option>
                            <option value="EUR" <?php echo $defaultCurrency === 'EUR' ? 'selected' : ''; ?>>EUR (Euro)</option>
                            <option value="USD" <?php echo $defaultCurrency === 'USD' ? 'selected' : ''; ?>>USD (US Dollar)</option>
                        </select>
                        <small class="text-muted">This will be the default currency displayed on the website.</small>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="mad_to_eur" class="form-label">MAD to EUR Exchange Rate *</label>
                            <input type="number" class="form-control" id="mad_to_eur" name="mad_to_eur" 
                                   step="0.0001" min="0.0001" required 
                                   value="<?php echo htmlspecialchars($madToEur); ?>">
                            <small class="text-muted">1 MAD = <?php echo htmlspecialchars($madToEur); ?> EUR</small>
                        </div>
                        <div class="col-md-6">
                            <label for="mad_to_usd" class="form-label">MAD to USD Exchange Rate *</label>
                            <input type="number" class="form-control" id="mad_to_usd" name="mad_to_usd" 
                                   step="0.0001" min="0.0001" required 
                                   value="<?php echo htmlspecialchars($madToUsd); ?>">
                            <small class="text-muted">1 MAD = <?php echo htmlspecialchars($madToUsd); ?> USD</small>
                        </div>
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
</script>

<?php include 'footer.php'; ?>

