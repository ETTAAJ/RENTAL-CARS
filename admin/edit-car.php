<?php
require_once '../config.php';

$pageTitle = 'Edit Car - Admin Panel';
include 'header.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$carId = intval($_GET['id']);
$conn = getDBConnection();

// Fetch car details
$stmt = $conn->prepare("SELECT * FROM cars WHERE id = ?");
$stmt->bind_param("i", $carId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    $conn->close();
    header('Location: index.php');
    exit;
}

$car = $result->fetch_assoc();
$stmt->close();

// Parse specifications if they exist
$specifications = [
    'gear_box' => 'Automat',
    'fuel' => 'Petrol',
    'doors' => '4',
    'air_conditioner' => 'Yes',
    'seats' => '5',
    'distance' => '500 km'
];

if (!empty($car['specifications'])) {
    $carSpecs = json_decode($car['specifications'], true);
    if (is_array($carSpecs)) {
        $specifications = array_merge($specifications, $carSpecs);
    }
}

$errorMessage = '';
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $discount = floatval($_POST['discount'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $availability = $_POST['availability'] ?? 'available';
    
    // Get specifications
    $specifications = [
        'gear_box' => trim($_POST['gear_box'] ?? 'Automat'),
        'fuel' => trim($_POST['fuel'] ?? 'Petrol'),
        'doors' => trim($_POST['doors'] ?? '4'),
        'air_conditioner' => trim($_POST['air_conditioner'] ?? 'Yes'),
        'seats' => trim($_POST['seats'] ?? '5'),
        'distance' => trim($_POST['distance'] ?? '500 km')
    ];
    $specificationsJson = json_encode($specifications);
    
    // Validation
    if (empty($name) || $price <= 0) {
        $errorMessage = 'Please fill in all required fields (Name and Price).';
    } elseif ($discount < 0 || $discount > 100) {
        $errorMessage = 'Discount must be between 0 and 100.';
    } else {
        // Check for duplicate car name only if the name has changed
        $nameChanged = trim($name) !== trim($car['name']);
        if ($nameChanged) {
            $checkStmt = $conn->prepare("SELECT id FROM cars WHERE name = ? AND id != ?");
            $checkStmt->bind_param("si", $name, $carId);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();
            if ($checkResult->num_rows > 0) {
                $errorMessage = 'A car with this name already exists. Please use a different name.';
                $checkStmt->close();
            } else {
                $checkStmt->close();
            }
        }
        
        // Only proceed if no error occurred
        if (empty($errorMessage)) {
            
        $imagePath = $car['image']; // Keep existing image by default
        
        // Handle file upload if a new image is provided
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../assets/images/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $file = $_FILES['image'];
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            $maxSize = 5 * 1024 * 1024; // 5MB
            
            // Validate file type
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            
            if (!in_array($mimeType, $allowedTypes)) {
                $errorMessage = 'Invalid file type. Please upload a JPEG, PNG, GIF, or WebP image.';
            } elseif ($file['size'] > $maxSize) {
                $errorMessage = 'File size too large. Maximum size is 5MB.';
            } else {
                // Generate filename based on car name
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                // Sanitize car name for filename (remove special characters, replace spaces with hyphens)
                $sanitizedName = preg_replace('/[^a-zA-Z0-9\-_]/', '', str_replace(' ', '-', $name));
                $sanitizedName = strtolower($sanitizedName);
                // If name is empty after sanitization, use fallback
                if (empty($sanitizedName)) {
                    $sanitizedName = 'car-' . $carId;
                }
                $filename = $sanitizedName . '.' . $extension;
                $uploadPath = $uploadDir . $filename;
                
                // If file already exists and it's not the current car's image, add timestamp
                if (file_exists($uploadPath)) {
                    $currentImagePath = '../' . $car['image'];
                    if ($uploadPath !== $currentImagePath) {
                        $filename = $sanitizedName . '-' . time() . '.' . $extension;
                        $uploadPath = $uploadDir . $filename;
                    }
                }
                
                if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                    // Delete old image if it exists and is in assets/images folder
                    $oldImagePath = '../' . $car['image'];
                    if (file_exists($oldImagePath) && strpos($car['image'], 'assets/images/') === 0) {
                        unlink($oldImagePath);
                    }
                    
                    $imagePath = 'assets/images/' . $filename;
                } else {
                    $errorMessage = "Failed to upload image. Please try again.";
                }
            }
        }
        
        // Update database if no error occurred
        if (empty($errorMessage)) {
            // Check if specifications column exists, if not create it
            $checkColumn = $conn->query("SHOW COLUMNS FROM cars LIKE 'specifications'");
            if ($checkColumn->num_rows == 0) {
                $conn->query("ALTER TABLE cars ADD COLUMN specifications JSON DEFAULT NULL");
            }
            
            $stmt = $conn->prepare("UPDATE cars SET name = ?, image = ?, price = ?, discount = ?, description = ?, availability = ?, specifications = ? WHERE id = ?");
            $stmt->bind_param("ssddsssi", $name, $imagePath, $price, $discount, $description, $availability, $specificationsJson, $carId);
            
            if ($stmt->execute()) {
                $successMessage = "Car updated successfully!";
                // Refresh car data
                $car['name'] = $name;
                $car['image'] = $imagePath;
                $car['price'] = $price;
                $car['discount'] = $discount;
                $car['description'] = $description;
                $car['availability'] = $availability;
            } else {
                $errorMessage = "Failed to update car. Please try again.";
            }
            $stmt->close();
        }
    }
    }
}

$conn->close();
?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-pencil"></i> Edit Car</h2>
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
                    <div class="mb-3">
                        <label for="name" class="form-label">Car Name *</label>
                        <input type="text" class="form-control" id="name" name="name" required 
                               value="<?php echo htmlspecialchars($car['name']); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Car Image</label>
                        <input type="file" class="form-control" id="image" name="image" 
                               accept="image/jpeg,image/jpg,image/png,image/gif,image/webp"
                               onchange="previewImage(this)">
                        <small class="text-muted">Upload a new image to replace the current one (JPEG, PNG, GIF, or WebP, Max 5MB). Leave empty to keep current image.</small>
                        <?php if ($car['image']): ?>
                            <div class="mt-2">
                                <p class="mb-1"><strong>Current Image:</strong></p>
                                <img src="../<?php echo htmlspecialchars($car['image']); ?>" 
                                     alt="Current Image" 
                                     id="currentImage"
                                     style="max-width: 300px; height: auto; border-radius: 8px; border: 1px solid #ddd;"
                                     onerror="this.style.display='none'">
                            </div>
                        <?php endif; ?>
                        <div id="imagePreview" class="mt-2" style="display: none;">
                            <p class="mb-1"><strong>New Image Preview:</strong></p>
                            <img id="previewImg" src="" alt="Preview" style="max-width: 300px; height: auto; border-radius: 8px; border: 1px solid #ddd;">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Price per Day (MAD) *</label>
                            <input type="number" class="form-control" id="price" name="price" 
                                   step="0.01" min="0" required 
                                   value="<?php echo htmlspecialchars($car['price']); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="discount" class="form-label">Discount (%)</label>
                            <input type="number" class="form-control" id="discount" name="discount" 
                                   step="0.01" min="0" max="100" 
                                   value="<?php echo htmlspecialchars($car['discount']); ?>">
                            <small class="text-muted">Enter 0 for no discount</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4"><?php echo htmlspecialchars($car['description']); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="availability" class="form-label">Availability *</label>
                        <select class="form-select" id="availability" name="availability" required>
                            <option value="available" <?php echo ($car['availability'] === 'available') ? 'selected' : ''; ?>>Available</option>
                            <option value="unavailable" <?php echo ($car['availability'] === 'unavailable') ? 'selected' : ''; ?>>Unavailable</option>
                        </select>
                    </div>

                    <hr class="my-4">

                    <!-- Technical Specifications -->
                    <h5 class="mb-3" style="color: #6C5CE7; font-weight: 700;">
                        <i class="bi bi-gear"></i> Technical Specifications
                    </h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="gear_box" class="form-label">Gear Box</label>
                            <select class="form-select" id="gear_box" name="gear_box">
                                <option value="Automat" <?php echo ($specifications['gear_box'] === 'Automat') ? 'selected' : ''; ?>>Automat</option>
                                <option value="Manual" <?php echo ($specifications['gear_box'] === 'Manual') ? 'selected' : ''; ?>>Manual</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="fuel" class="form-label">Fuel</label>
                            <select class="form-select" id="fuel" name="fuel">
                                <option value="Petrol" <?php echo ($specifications['fuel'] === 'Petrol') ? 'selected' : ''; ?>>Petrol</option>
                                <option value="Diesel" <?php echo ($specifications['fuel'] === 'Diesel') ? 'selected' : ''; ?>>Diesel</option>
                                <option value="Hybrid" <?php echo ($specifications['fuel'] === 'Hybrid') ? 'selected' : ''; ?>>Hybrid</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="doors" class="form-label">Doors</label>
                            <input type="text" class="form-control" id="doors" name="doors" 
                                   value="<?php echo htmlspecialchars($specifications['doors']); ?>"
                                   placeholder="4">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="air_conditioner" class="form-label">Air Conditioner</label>
                            <select class="form-select" id="air_conditioner" name="air_conditioner">
                                <option value="Yes" <?php echo ($specifications['air_conditioner'] === 'Yes') ? 'selected' : ''; ?>>Yes</option>
                                <option value="No" <?php echo ($specifications['air_conditioner'] === 'No') ? 'selected' : ''; ?>>No</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="seats" class="form-label">Seats</label>
                            <input type="text" class="form-control" id="seats" name="seats" 
                                   value="<?php echo htmlspecialchars($specifications['seats']); ?>"
                                   placeholder="5">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="distance" class="form-label">Distance</label>
                            <input type="text" class="form-control" id="distance" name="distance" 
                                   value="<?php echo htmlspecialchars($specifications['distance']); ?>"
                                   placeholder="500 km">
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-circle"></i> Update Car
                        </button>
                        <a href="index.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const currentImage = document.getElementById('currentImage');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
            if (currentImage) {
                currentImage.style.opacity = '0.5';
            }
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.style.display = 'none';
        if (currentImage) {
            currentImage.style.opacity = '1';
        }
    }
}
</script>

<?php include 'footer.php'; ?>

