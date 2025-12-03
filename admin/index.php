<?php
require_once '../config.php';

$pageTitle = 'Admin Panel - Manage Cars';
include 'header.php';

$conn = getDBConnection();

// Handle delete action
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $deleteId = intval($_GET['delete']);
    
    // First, get the car image path to delete the file
    $stmt = $conn->prepare("SELECT image FROM cars WHERE id = ?");
    $stmt->bind_param("i", $deleteId);
    $stmt->execute();
    $result = $stmt->get_result();
    $car = $result->fetch_assoc();
    $stmt->close();
    
    // Delete the car from database
    $stmt = $conn->prepare("DELETE FROM cars WHERE id = ?");
    $stmt->bind_param("i", $deleteId);
    if ($stmt->execute()) {
        // Delete the image file if it exists and is in assets/images folder
        if ($car && isset($car['image']) && strpos($car['image'], 'assets/images/') === 0) {
            $imagePath = '../' . $car['image'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        $successMessage = "Car deleted successfully!";
    } else {
        $errorMessage = "Failed to delete car.";
    }
    $stmt->close();
}

// Fetch all cars
$stmt = $conn->prepare("SELECT * FROM cars ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
$cars = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <h2 class="mb-0"><i class="bi bi-gear"></i> Admin Panel - Manage Cars</h2>
    <a href="add-car.php" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add New Car
    </a>
</div>

<?php if (isset($successMessage)): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle"></i> <?php echo htmlspecialchars($successMessage); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($errorMessage)): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($errorMessage); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (empty($cars)): ?>
    <div class="alert alert-info">
        <h5>No cars found.</h5>
        <p>Click "Add New Car" to add your first car.</p>
    </div>
<?php else: ?>
    <div class="row g-4">
        <?php foreach ($cars as $car): 
            $discountedPrice = calculateDiscountedPrice($car['price'], $car['discount']);
            $hasDiscount = $car['discount'] > 0;
        ?>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm" style="border: none; border-radius: 12px; overflow: hidden;">
                    <div class="position-relative">
                        <?php if ($hasDiscount): ?>
                            <span class="badge bg-danger position-absolute top-0 end-0 m-2" style="font-size: 0.9rem;">
                                <?php echo $car['discount']; ?>% OFF
                            </span>
                        <?php endif; ?>
                        <img src="<?php 
                            $imgPath = $car['image'];
                            // If image path doesn't start with http:// or https://, prepend ../
                            if (strpos($imgPath, 'http://') !== 0 && strpos($imgPath, 'https://') !== 0) {
                                if (strpos($imgPath, 'assets/') === 0) {
                                    $imgPath = '../' . $imgPath;
                                } elseif (strpos($imgPath, '/') !== 0) {
                                    $imgPath = '../' . $imgPath;
                                }
                            }
                            echo htmlspecialchars($imgPath);
                        ?>" 
                             class="card-img-top" 
                             alt="<?php echo htmlspecialchars($car['name']); ?>"
                             style="height: 200px; object-fit: cover;"
                             onerror="this.src='https://via.placeholder.com/400x200?text=No+Image'">
                    </div>
                    <div class="card-body d-flex flex-column">
                        <div class="mb-2">
                            <span class="badge bg-secondary" style="font-size: 0.75rem;">ID: <?php echo $car['id']; ?></span>
                            <span class="badge bg-<?php echo $car['availability'] === 'available' ? 'success' : 'danger'; ?> ms-2" style="font-size: 0.75rem;">
                                <?php echo ucfirst($car['availability']); ?>
                            </span>
                        </div>
                        <h5 class="card-title mb-3" style="font-weight: 700; font-size: 1.25rem;">
                            <?php echo htmlspecialchars($car['name']); ?>
                        </h5>
                        <div class="mb-3">
                            <div style="font-size: 0.9rem; color: #6c757d; margin-bottom: 0.25rem;">Price per Day:</div>
                            <?php if ($hasDiscount): ?>
                                <div>
                                    <span style="text-decoration: line-through; color: #95a5a6; font-size: 0.9rem;">
                                        <?php echo formatPrice($car['price']); ?> MAD
                                    </span>
                                    <span class="ms-2" style="color: #6C5CE7; font-weight: 700; font-size: 1.1rem;">
                                        <?php echo formatPrice($discountedPrice); ?> MAD
                                    </span>
                                </div>
                            <?php else: ?>
                                <span style="color: #6C5CE7; font-weight: 700; font-size: 1.1rem;">
                                    <?php echo formatPrice($car['price']); ?> MAD
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="mt-auto">
                            <div class="d-grid gap-2 d-md-flex">
                                <a href="edit-car.php?id=<?php echo $car['id']; ?>" class="btn btn-warning flex-fill">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <a href="?delete=<?php echo $car['id']; ?>" 
                                   class="btn btn-danger flex-fill"
                                   onclick="return confirm('Are you sure you want to delete this car?');">
                                    <i class="bi bi-trash"></i> Delete
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include 'footer.php'; ?>

