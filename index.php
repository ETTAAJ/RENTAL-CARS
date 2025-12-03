<?php
require_once 'config.php';

$pageTitle = 'Home - RENTAL CARS';
include 'header.php';

$conn = getDBConnection();

// Get filter parameters
$filterFuel = $_GET['fuel'] ?? 'all';
$filterGear = $_GET['gear'] ?? 'all';
$filterName = trim($_GET['name'] ?? '');
$sortPrice = $_GET['sort_price'] ?? 'default';

// Build query with filters
$query = "SELECT * FROM cars WHERE availability = 'available'";
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
$stmt->execute();
$result = $stmt->get_result();
$allCars = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Get all unique car names for the dropdown
$nameQuery = "SELECT DISTINCT name FROM cars WHERE availability = 'available' ORDER BY name ASC";
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

<div class="container-fluid px-0">
    <!-- Hero Section with Video -->
    <div class="hero-section-video">
        <video autoplay muted loop playsinline class="hero-video">
            <source src="vidio/vidio-marrakech.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="hero-content-wrapper">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8 text-center hero-content">
                        <h1 class="hero-title">Experience the road like never before</h1>
                        <p class="hero-subtitle">Aliquam adipiscing velit semper morbi. Purus non eu cursus porttitor tristique et gravida. Quis nunc interdum gravida ullamcorper.</p>
                        <a href="#vehicles" class="btn btn-orange btn-lg">View all cars</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <!-- Vehicles Section -->
    <div class="mb-5 mt-5" id="vehicles">
        
        <!-- Filters -->
        <div class="card mb-4" style="background: var(--card); border: 1px solid var(--border); border-radius: 12px;">
            <div class="card-body">
                <form method="GET" action="" class="row g-3 align-items-end">
                    <div class="col-md-12 mb-3">
                        <label for="name" class="form-label" style="color: var(--dark-text); font-weight: 600;">Car</label>
                        <select class="form-select" id="name" name="name" onchange="filterCars()" style="background: var(--card); border: 1px solid var(--border); color: var(--dark-text);">
                            <option value="" <?php echo empty($filterName) ? 'selected' : ''; ?>>All</option>
                            <?php foreach ($allCarNames as $carName): ?>
                                <option value="<?php echo htmlspecialchars($carName); ?>" <?php echo $filterName === $carName ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($carName); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="fuel" class="form-label" style="color: var(--dark-text); font-weight: 600;">Fuel Type</label>
                        <select class="form-select" id="fuel" name="fuel" onchange="filterCars()" style="background: var(--card); border: 1px solid var(--border); color: var(--dark-text);">
                            <option value="all" <?php echo $filterFuel === 'all' ? 'selected' : ''; ?>>All Fuel Types</option>
                            <option value="Petrol" <?php echo $filterFuel === 'Petrol' ? 'selected' : ''; ?>>Petrol</option>
                            <option value="Diesel" <?php echo $filterFuel === 'Diesel' ? 'selected' : ''; ?>>Diesel</option>
                            <option value="Hybrid" <?php echo $filterFuel === 'Hybrid' ? 'selected' : ''; ?>>Hybrid</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="gear" class="form-label" style="color: var(--dark-text); font-weight: 600;">Gear Box</label>
                        <select class="form-select" id="gear" name="gear" onchange="filterCars()" style="background: var(--card); border: 1px solid var(--border); color: var(--dark-text);">
                            <option value="all" <?php echo $filterGear === 'all' ? 'selected' : ''; ?>>All Gear Types</option>
                            <option value="Automat" <?php echo $filterGear === 'Automat' ? 'selected' : ''; ?>>Automat</option>
                            <option value="Manual" <?php echo $filterGear === 'Manual' ? 'selected' : ''; ?>>Manual</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="sort_price" class="form-label" style="color: var(--dark-text); font-weight: 600;">Sort by Price</label>
                        <select class="form-select" id="sort_price" name="sort_price" onchange="filterCars()" style="background: var(--card); border: 1px solid var(--border); color: var(--dark-text);">
                            <option value="default" <?php echo $sortPrice === 'default' ? 'selected' : ''; ?>>Default</option>
                            <option value="low_high" <?php echo $sortPrice === 'low_high' ? 'selected' : ''; ?>>Low to High</option>
                            <option value="high_low" <?php echo $sortPrice === 'high_low' ? 'selected' : ''; ?>>High to Low</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <div id="no-cars-message" class="alert alert-info text-center" style="background: var(--card); border: 1px solid var(--border); border-radius: 12px; padding: 2rem; display: none;">
            <h4 style="color: var(--dark-text);">No cars available at the moment.</h4>
            <p style="color: var(--muted);">No cars match the selected filters.</p>
        </div>
        
        <div id="cars-container" class="row g-4">
            <?php foreach ($allCars as $car): 
                    $discountedPrice = calculateDiscountedPrice($car['price'], $car['discount']);
                    $hasDiscount = $car['discount'] > 0;
                ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="car-card <?php echo $hasDiscount ? 'has-discount' : ''; ?>">
                            <div class="position-relative">
                                <?php if ($hasDiscount): ?>
                                    <span class="discount-badge"><?php echo formatDiscount($car['discount']); ?>% OFF</span>
                                <?php endif; ?>
                                <img src="<?php echo htmlspecialchars($car['image']); ?>" 
                                     class="car-image" 
                                     alt="<?php echo htmlspecialchars($car['name']); ?>"
                                     onerror="this.src='https://via.placeholder.com/800x600?text=Car+Image'">
                            </div>
                            <div class="car-card-body">
                                <h5 class="car-title"><?php echo htmlspecialchars($car['name']); ?></h5>
                                <div class="car-price">
                                    <?php if ($hasDiscount): ?>
                                        <span class="price-original" data-price-mad="<?php echo $car['price']; ?>">
                                            <span class="price-amount"><?php echo formatPrice($car['price']); ?></span>
                                            <span class="price-currency">MAD</span>
                                        </span>
                                    <?php endif; ?>
                                    <span class="price-discounted" data-price-mad="<?php echo $discountedPrice; ?>">
                                        <span class="price-amount"><?php echo formatPrice($discountedPrice); ?></span>
                                        <span class="price-currency">MAD</span> / day
                                    </span>
                                </div>
                                <div class="car-features">
                                    <?php
                                    // Parse specifications if they exist
                                    $carSpecs = ['gear_box' => 'Automat', 'fuel' => 'Petrol', 'air_conditioner' => 'Yes'];
                                    if (!empty($car['specifications'])) {
                                        $parsedSpecs = json_decode($car['specifications'], true);
                                        if (is_array($parsedSpecs)) {
                                            $carSpecs = array_merge($carSpecs, $parsedSpecs);
                                        }
                                    }
                                    ?>
                                    <div class="car-feature">
                                        <i class="bi bi-gear-fill"></i>
                                        <span><?php echo htmlspecialchars($carSpecs['gear_box']); ?></span>
                                    </div>
                                    <div class="car-feature">
                                        <i class="bi bi-fuel-pump-fill"></i>
                                        <span><?php echo htmlspecialchars($carSpecs['fuel']); ?></span>
                                    </div>
                                    <div class="car-feature">
                                        <i class="bi bi-snow"></i>
                                        <span><?php echo htmlspecialchars($carSpecs['air_conditioner']); ?></span>
                                    </div>
                                </div>
                                <a href="car-detail.php?id=<?php echo $car['id']; ?>" class="btn btn-purple w-100">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
// Store all cars data for filtering
const allCarsData = <?php echo json_encode($allCars); ?>;

function filterCars() {
    const filterName = document.getElementById('name').value;
    const filterFuel = document.getElementById('fuel').value;
    const filterGear = document.getElementById('gear').value;
    const sortPrice = document.getElementById('sort_price').value;
    
    // Filter cars
    let filteredCars = allCarsData.filter(car => {
        // Filter by name
        if (filterName && car.name !== filterName) {
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
        let specs = {fuel: 'Petrol', gear_box: 'Automat', air_conditioner: 'Yes'};
        if (car.specifications) {
            try {
                const carSpecs = JSON.parse(car.specifications);
                if (carSpecs) {
                    specs = {...specs, ...carSpecs};
                }
            } catch(e) {}
        }
        
        const carCard = `
            <div class="col-md-6 col-lg-4">
                <div class="car-card ${hasDiscount ? 'has-discount' : ''}">
                    <div class="position-relative">
                        ${hasDiscount ? `<span class="discount-badge">${parseFloat(car.discount).toFixed(0)}% OFF</span>` : ''}
                        <img src="${car.image.replace(/"/g, '&quot;')}" 
                             class="car-image" 
                             alt="${car.name.replace(/"/g, '&quot;')}"
                             onerror="this.src='https://via.placeholder.com/800x600?text=Car+Image'">
                    </div>
                    <div class="car-card-body">
                        <h5 class="car-title">${car.name.replace(/</g, '&lt;').replace(/>/g, '&gt;')}</h5>
                        <div class="car-price">
                            ${hasDiscount ? `
                                <span class="price-original" data-price-mad="${car.price}">
                                    <span class="price-amount">${parseFloat(car.price).toFixed(0)}</span>
                                    <span class="price-currency">MAD</span>
                                </span>
                            ` : ''}
                            <span class="price-discounted" data-price-mad="${discountedPrice}">
                                <span class="price-amount">${parseFloat(discountedPrice).toFixed(0)}</span>
                                <span class="price-currency">MAD</span> / day
                            </span>
                        </div>
                        <div class="car-features">
                            <div class="car-feature">
                                <i class="bi bi-gear-fill"></i>
                                <span>${specs.gear_box || 'Automat'}</span>
                            </div>
                            <div class="car-feature">
                                <i class="bi bi-fuel-pump-fill"></i>
                                <span>${specs.fuel || 'Petrol'}</span>
                            </div>
                            <div class="car-feature">
                                <i class="bi bi-snow"></i>
                                <span>${specs.air_conditioner || 'Yes'}</span>
                            </div>
                        </div>
                        <a href="car-detail.php?id=${car.id}" class="btn btn-purple w-100">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        `;
        container.innerHTML += carCard;
    });
    
    // Update prices with currency conversion if needed
    if (typeof updatePrices === 'function') {
        const currentCurrency = localStorage.getItem('currency') || 'MAD';
        updatePrices(currentCurrency);
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    filterCars();
});
</script>

<?php include 'footer.php'; ?>

