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

// Get filter parameters
$filterAvailability = $_GET['availability'] ?? 'all';
$filterFuel = $_GET['fuel'] ?? 'all';
$filterGear = $_GET['gear'] ?? 'all';
$filterName = trim($_GET['name'] ?? '');
$sortPrice = $_GET['sort_price'] ?? 'default';

// Build query with filters
$query = "SELECT * FROM cars WHERE 1=1";
$params = [];
$types = "";

// Filter by availability
if ($filterAvailability !== 'all') {
    $query .= " AND availability = ?";
    $params[] = $filterAvailability;
    $types .= "s";
}

// Build query to filter by specifications (fuel and gear)
// We'll filter in PHP after fetching since JSON filtering in MySQL can be complex
$query .= " ORDER BY ";

// Sort by price
if ($sortPrice === 'low_high') {
    $query .= "price ASC";
} elseif ($sortPrice === 'high_low') {
    $query .= "price DESC";
} else {
    $query .= "created_at DESC";
}

$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$allCars = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Get all unique car names for the dropdown
$nameQuery = "SELECT DISTINCT name FROM cars ORDER BY name ASC";
$nameStmt = $conn->prepare($nameQuery);
$nameStmt->execute();
$nameResult = $nameStmt->get_result();
$allCarNames = [];
while ($row = $nameResult->fetch_assoc()) {
    $allCarNames[] = $row['name'];
}
$nameStmt->close();

// Filter by fuel, gear, and name in PHP (since they're in JSON)
$cars = [];
foreach ($allCars as $car) {
    $specs = ['fuel' => 'Petrol', 'gear_box' => 'Automat'];
    if (!empty($car['specifications'])) {
        $carSpecs = json_decode($car['specifications'], true);
        if (is_array($carSpecs)) {
            $specs = array_merge($specs, $carSpecs);
        }
    }
    
    // Filter by name (exact match)
    if (!empty($filterName) && $car['name'] !== $filterName) {
        continue;
    }
    
    // Filter by fuel
    if ($filterFuel !== 'all' && $specs['fuel'] !== $filterFuel) {
        continue;
    }
    
    // Filter by gear
    if ($filterGear !== 'all' && $specs['gear_box'] !== $filterGear) {
        continue;
    }
    
    $cars[] = $car;
}

$conn->close();
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <h2 class="mb-0"><i class="bi bi-gear"></i> Admin Panel - Manage Cars</h2>
    <a href="add-car.php" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add New Car
    </a>
</div>

<!-- Filters -->
<div class="card mb-4" style="border-radius: 12px;">
    <div class="card-body">
        <form method="GET" action="" class="row g-3 align-items-end">
            <div class="col-md-12 mb-3">
                <label for="name" class="form-label">Car</label>
                <select class="form-select" id="name" name="name" onchange="filterCars()">
                    <option value="" <?php echo empty($filterName) ? 'selected' : ''; ?>>All</option>
                    <?php foreach ($allCarNames as $carName): ?>
                        <option value="<?php echo htmlspecialchars($carName); ?>" <?php echo $filterName === $carName ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($carName); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label for="availability" class="form-label">Availability</label>
                <select class="form-select" id="availability" name="availability" onchange="filterCars()">
                    <option value="all" <?php echo $filterAvailability === 'all' ? 'selected' : ''; ?>>All Cars</option>
                    <option value="available" <?php echo $filterAvailability === 'available' ? 'selected' : ''; ?>>Available</option>
                    <option value="unavailable" <?php echo $filterAvailability === 'unavailable' ? 'selected' : ''; ?>>Unavailable</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="fuel" class="form-label">Fuel Type</label>
                <select class="form-select" id="fuel" name="fuel" onchange="filterCars()">
                    <option value="all" <?php echo $filterFuel === 'all' ? 'selected' : ''; ?>>All Fuel Types</option>
                    <option value="Petrol" <?php echo $filterFuel === 'Petrol' ? 'selected' : ''; ?>>Petrol</option>
                    <option value="Diesel" <?php echo $filterFuel === 'Diesel' ? 'selected' : ''; ?>>Diesel</option>
                    <option value="Hybrid" <?php echo $filterFuel === 'Hybrid' ? 'selected' : ''; ?>>Hybrid</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="gear" class="form-label">Gear Box</label>
                <select class="form-select" id="gear" name="gear" onchange="filterCars()">
                    <option value="all" <?php echo $filterGear === 'all' ? 'selected' : ''; ?>>All Gear Types</option>
                    <option value="Automat" <?php echo $filterGear === 'Automat' ? 'selected' : ''; ?>>Automat</option>
                    <option value="Manual" <?php echo $filterGear === 'Manual' ? 'selected' : ''; ?>>Manual</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="sort_price" class="form-label">Sort by Price</label>
                <select class="form-select" id="sort_price" name="sort_price" onchange="filterCars()">
                    <option value="default" <?php echo $sortPrice === 'default' ? 'selected' : ''; ?>>Default</option>
                    <option value="low_high" <?php echo $sortPrice === 'low_high' ? 'selected' : ''; ?>>Low to High</option>
                    <option value="high_low" <?php echo $sortPrice === 'high_low' ? 'selected' : ''; ?>>High to Low</option>
                </select>
            </div>
        </form>
    </div>
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

<div id="no-cars-message" class="alert alert-info" style="display: none;">
    <h5>No cars found.</h5>
    <p>No cars match the selected filters.</p>
</div>

<div id="cars-container" class="row g-4">
    <?php foreach ($allCars as $car): 
            $discountedPrice = calculateDiscountedPrice($car['price'], $car['discount']);
            $hasDiscount = $car['discount'] > 0;
        ?>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm" style="border: none; border-radius: 12px; overflow: hidden;">
                    <div class="position-relative">
                        <?php if ($hasDiscount): ?>
                            <span class="badge bg-danger position-absolute top-0 end-0 m-2" style="font-size: 0.9rem;">
                                <?php echo formatDiscount($car['discount']); ?>% OFF
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

<script>
// Store all cars data for filtering
const allCarsData = <?php echo json_encode($allCars); ?>;

function filterCars() {
    const filterName = document.getElementById('name').value;
    const filterAvailability = document.getElementById('availability').value;
    const filterFuel = document.getElementById('fuel').value;
    const filterGear = document.getElementById('gear').value;
    const sortPrice = document.getElementById('sort_price').value;
    
    // Filter cars
    let filteredCars = allCarsData.filter(car => {
        // Filter by name
        if (filterName && car.name !== filterName) {
            return false;
        }
        
        // Filter by availability
        if (filterAvailability !== 'all' && car.availability !== filterAvailability) {
            return false;
        }
        
        // Parse specifications
        let specs = {fuel: 'Petrol', gear_box: 'Automat'};
        if (car.specifications) {
            try {
                const carSpecs = JSON.parse(car.specifications);
                if (carSpecs) {
                    specs = {...specs, ...carSpecs};
                }
            } catch(e) {}
        }
        
        // Filter by fuel
        if (filterFuel !== 'all' && specs.fuel !== filterFuel) {
            return false;
        }
        
        // Filter by gear
        if (filterGear !== 'all' && specs.gear_box !== filterGear) {
            return false;
        }
        
        return true;
    });
    
    // Sort cars
    if (sortPrice === 'low_high') {
        filteredCars.sort((a, b) => parseFloat(a.price) - parseFloat(b.price));
    } else if (sortPrice === 'high_low') {
        filteredCars.sort((a, b) => parseFloat(b.price) - parseFloat(a.price));
    }
    
    // Update URL without reload
    const params = new URLSearchParams();
    if (filterName) params.set('name', filterName);
    if (filterAvailability !== 'all') params.set('availability', filterAvailability);
    if (filterFuel !== 'all') params.set('fuel', filterFuel);
    if (filterGear !== 'all') params.set('gear', filterGear);
    if (sortPrice !== 'default') params.set('sort_price', sortPrice);
    window.history.pushState({}, '', '?' + params.toString());
    
    // Render filtered cars
    renderCars(filteredCars);
}

function renderCars(cars) {
    const container = document.getElementById('cars-container');
    const noCarsMsg = document.getElementById('no-cars-message');
    
    if (cars.length === 0) {
        container.style.display = 'none';
        noCarsMsg.style.display = 'block';
        return;
    }
    
    noCarsMsg.style.display = 'none';
    container.style.display = 'flex';
    container.innerHTML = '';
    
    cars.forEach(car => {
        const discountedPrice = parseFloat(car.price) * (1 - parseFloat(car.discount) / 100);
        const hasDiscount = parseFloat(car.discount) > 0;
        
        // Parse specifications
        let specs = {fuel: 'Petrol', gear_box: 'Automat'};
        if (car.specifications) {
            try {
                const carSpecs = JSON.parse(car.specifications);
                if (carSpecs) {
                    specs = {...specs, ...carSpecs};
                }
            } catch(e) {}
        }
        
        // Get image path
        let imgPath = car.image;
        if (!imgPath.startsWith('http://') && !imgPath.startsWith('https://')) {
            if (imgPath.startsWith('assets/')) {
                imgPath = '../' + imgPath;
            } else if (!imgPath.startsWith('/')) {
                imgPath = '../' + imgPath;
            }
        }
        
        const carCard = `
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm" style="border: none; border-radius: 12px; overflow: hidden;">
                    <div class="position-relative">
                        ${hasDiscount ? `<span class="badge bg-danger position-absolute top-0 end-0 m-2" style="font-size: 0.9rem;">${parseFloat(car.discount).toFixed(0)}% OFF</span>` : ''}
                        <img src="${imgPath.replace(/"/g, '&quot;')}" 
                             class="card-img-top" 
                             alt="${car.name.replace(/"/g, '&quot;')}"
                             style="height: 200px; object-fit: cover;"
                             onerror="this.src='https://via.placeholder.com/400x200?text=No+Image'">
                    </div>
                    <div class="card-body d-flex flex-column">
                        <div class="mb-2">
                            <span class="badge bg-secondary" style="font-size: 0.75rem;">ID: ${car.id}</span>
                            <span class="badge bg-${car.availability === 'available' ? 'success' : 'danger'} ms-2" style="font-size: 0.75rem;">
                                ${car.availability.charAt(0).toUpperCase() + car.availability.slice(1)}
                            </span>
                        </div>
                        <h5 class="card-title mb-3" style="font-weight: 700; font-size: 1.25rem;">
                            ${car.name.replace(/</g, '&lt;').replace(/>/g, '&gt;')}
                        </h5>
                        <div class="mb-3">
                            <div style="font-size: 0.9rem; color: #6c757d; margin-bottom: 0.25rem;">Price per Day:</div>
                            ${hasDiscount ? `
                                <div>
                                    <span style="text-decoration: line-through; color: #95a5a6; font-size: 0.9rem;">
                                        ${parseFloat(car.price).toFixed(0)} MAD
                                    </span>
                                    <span class="ms-2" style="color: #6C5CE7; font-weight: 700; font-size: 1.1rem;">
                                        ${parseFloat(discountedPrice).toFixed(0)} MAD
                                    </span>
                                </div>
                            ` : `
                                <span style="color: #6C5CE7; font-weight: 700; font-size: 1.1rem;">
                                    ${parseFloat(car.price).toFixed(0)} MAD
                                </span>
                            `}
                        </div>
                        <div class="mt-auto">
                            <div class="d-grid gap-2 d-md-flex">
                                <a href="edit-car.php?id=${car.id}" class="btn btn-warning flex-fill">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <a href="?delete=${car.id}" 
                                   class="btn btn-danger flex-fill"
                                   onclick="return confirm('Are you sure you want to delete this car?');">
                                    <i class="bi bi-trash"></i> Delete
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        container.innerHTML += carCard;
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    filterCars();
});
</script>

<?php include 'footer.php'; ?>

