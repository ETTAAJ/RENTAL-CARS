<?php
require_once 'config.php';

$pageTitle = 'Home - ' . getSiteName();
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

<style>
    /* Desktop/PC Responsive Design for Homepage - Centered */
    @media (min-width: 1200px) {
        .home-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        
        .hero-section-video {
            height: 95vh !important;
            min-height: 700px !important;
        }
        
        .hero-content-wrapper .container {
            max-width: 1400px;
            margin-left: auto;
            margin-right: auto;
            padding: 0 2rem;
        }
        
        .hero-title {
            font-size: 4rem !important;
        }
        
        .hero-subtitle {
            font-size: 1.5rem !important;
        }
        
        .vehicles-section {
            padding: 4rem 0;
        }
        
        .filter-card {
            padding: 2rem !important;
        }
        
        .car-card {
            transition: transform 0.3s ease;
        }
        
        .car-card:hover {
            transform: translateY(-10px) !important;
        }
        
        #cars-container {
            justify-content: center;
        }
    }
    
    @media (min-width: 992px) and (max-width: 1199px) {
        .home-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }
        
        .hero-content-wrapper .container {
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
            padding: 0 1.5rem;
        }
        
        .hero-section-video {
            height: 85vh !important;
            min-height: 650px !important;
        }
        
        .hero-title {
            font-size: 3.5rem !important;
        }
    }
    
    @media (max-width: 991px) {
        .home-container {
            padding: 0 1rem;
        }
        
        .hero-content-wrapper .container {
            padding: 0 1rem;
        }
        
        .vehicles-section {
            padding: 2rem 0;
        }
        
        .filter-card {
            padding: 1.5rem !important;
        }
    }
    
    /* Mobile Responsive Design */
    @media (max-width: 768px) {
        .home-container {
            padding: 0 0.75rem;
            width: 100%;
            max-width: 100%;
        }
        
        .hero-section-video {
            width: 100% !important;
            height: 60vh !important;
            min-height: 400px !important;
        }
        
        .hero-content-wrapper {
            width: 100% !important;
        }
        
        .hero-title {
            font-size: 1.75rem !important;
            line-height: 1.3;
            padding: 0 0.5rem;
        }
        
        .hero-subtitle {
            font-size: 0.95rem !important;
            padding: 0 1rem;
            line-height: 1.6;
        }
        
        .vehicles-section {
            padding: 1.5rem 0;
        }
        
        .filter-card {
            padding: 1rem !important;
            width: 100%;
        }
        
        .filter-card .row {
            margin: 0 !important;
            width: 100%;
        }
        
        .filter-card .col-md-4,
        .filter-card .col-md-12 {
            width: 100% !important;
            padding: 0.5rem 0 !important;
            margin-bottom: 0.75rem;
        }
        
        .filter-card .col-md-4:last-child,
        .filter-card .col-md-12:last-child {
            margin-bottom: 0;
        }
        
        .car-card {
            margin-bottom: 1.5rem;
        }
        
        #cars-container {
            padding: 0;
            margin: 0;
            display: flex;
            flex-wrap: wrap;
        }
        
        #cars-container .col-md-6,
        #cars-container .col-lg-4 {
            width: 100% !important;
            max-width: 100% !important;
            padding: 0.5rem;
            flex: 0 0 100%;
        }
    }
    
    @media (max-width: 576px) {
        .home-container {
            padding: 0 0.5rem;
        }
        
        .hero-title {
            font-size: 1.5rem !important;
        }
        
        .hero-subtitle {
            font-size: 0.9rem !important;
        }
        
        .filter-card {
            padding: 0.75rem !important;
        }
        
        .btn-orange {
            padding: 0.6rem 1.5rem;
            font-size: 0.9rem;
        }
    }
    
    /* Specific optimization for 428px width (iPhone 12/13 Pro Max) */
    @media (max-width: 428px) {
        .home-container {
            padding: 0 0.75rem;
        }
        
        .hero-title {
            font-size: 1.6rem !important;
            line-height: 1.3;
            padding: 0 0.5rem;
        }
        
        .hero-subtitle {
            font-size: 0.95rem !important;
            line-height: 1.6;
            padding: 0 1rem;
        }
        
        .hero-content-wrapper .container {
            padding: 0 0.75rem;
        }
        
        .vehicles-section {
            padding: 1.75rem 0;
        }
        
        .filter-card {
            padding: 1rem !important;
            margin-bottom: 1.5rem;
        }
        
        .filter-card .row {
            gap: 0.75rem;
        }
        
        .filter-card .col-md-4,
        .filter-card .col-md-12 {
            padding: 0.5rem 0;
        }
        
        .filter-card .form-label {
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        
        .filter-card .form-select {
            font-size: 16px;
            padding: 0.7rem;
        }
        
        #cars-container {
            padding: 0 0.25rem;
        }
        
        #cars-container .col-md-6,
        #cars-container .col-lg-4 {
            padding: 0.5rem;
        }
        
        .car-card {
            border-radius: 10px;
            margin-bottom: 1.25rem;
        }
        
        .car-image {
            height: 220px;
        }
        
        .car-card-body {
            padding: 1rem;
        }
        
        .car-title {
            font-size: 1.15rem;
            margin-bottom: 0.75rem;
        }
        
        .car-price {
            font-size: 1.3rem;
            margin-bottom: 0.75rem;
        }
        
        .car-features {
            gap: 0.75rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }
        
        .car-feature {
            font-size: 0.85rem;
        }
        
        .btn-purple {
            padding: 0.7rem 1rem;
            font-size: 0.95rem;
            min-height: 44px;
        }
        
        .btn-orange {
            padding: 0.7rem 1.5rem;
            font-size: 0.95rem;
            min-height: 44px;
        }
    }
    
    /* Ensure all content is centered */
    .container-fluid {
        width: 100%;
        overflow-x: hidden;
    }
    
    .container-fluid .container {
        margin: 0 auto;
    }
    
    /* Prevent horizontal scroll */
    body {
        overflow-x: hidden;
        width: 100%;
    }
    
    /* Hero Animated Background */
    .hero-animated {
        position: relative;
        overflow: hidden;
    }
    
    .hero-animated-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #6C5CE7 0%, #FF6B35 50%, #6C5CE7 100%);
        background-size: 200% 200%;
        animation: gradientShift 8s ease infinite;
        z-index: 1;
    }
    
    @keyframes gradientShift {
        0% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
        100% {
            background-position: 0% 50%;
        }
    }
    
    .hero-animated-shapes {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 2;
        overflow: hidden;
    }
    
    .hero-animated-shapes .shape {
        position: absolute;
        border-radius: 50%;
        opacity: 0.3;
        animation: float 20s infinite ease-in-out;
    }
    
    .shape-1 {
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        top: 10%;
        left: 10%;
        animation-delay: 0s;
    }
    
    .shape-2 {
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.15);
        top: 60%;
        right: 15%;
        animation-delay: 2s;
    }
    
    .shape-3 {
        width: 250px;
        height: 250px;
        background: rgba(255, 255, 255, 0.1);
        bottom: 20%;
        left: 20%;
        animation-delay: 4s;
    }
    
    .shape-4 {
        width: 180px;
        height: 180px;
        background: rgba(255, 255, 255, 0.12);
        top: 30%;
        right: 30%;
        animation-delay: 6s;
    }
    
    .shape-5 {
        width: 220px;
        height: 220px;
        background: rgba(255, 255, 255, 0.1);
        bottom: 10%;
        right: 10%;
        animation-delay: 8s;
    }
    
    @keyframes float {
        0%, 100% {
            transform: translate(0, 0) scale(1);
        }
        25% {
            transform: translate(30px, -30px) scale(1.1);
        }
        50% {
            transform: translate(-20px, 20px) scale(0.9);
        }
        75% {
            transform: translate(20px, 30px) scale(1.05);
        }
    }
    
    /* Fix filter card on mobile */
    @media (max-width: 768px) {
        .filter-card .row {
            margin: 0 !important;
        }
        
        .filter-card .col-md-4,
        .filter-card .col-md-12 {
            width: 100% !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            margin-bottom: 1rem;
        }
        
        #cars-container {
            display: flex !important;
            flex-direction: column;
        }
        
        #cars-container .col-md-6,
        #cars-container .col-lg-4 {
            width: 100% !important;
            max-width: 100% !important;
            padding: 0.5rem !important;
        }
        
        .shape-1,
        .shape-2,
        .shape-3,
        .shape-4,
        .shape-5 {
            width: 150px !important;
            height: 150px !important;
        }
    }
    
    @media (max-width: 428px) {
        .filter-card .col-md-4,
        .filter-card .col-md-12 {
            margin-bottom: 0.75rem;
        }
        
        .shape-1,
        .shape-2,
        .shape-3,
        .shape-4,
        .shape-5 {
            width: 100px !important;
            height: 100px !important;
        }
    }
</style>

<div class="container-fluid px-0" style="width: 100%; overflow-x: hidden; margin: 0; padding: 0;">
    <!-- Hero Section with Animated Background -->
    <div class="hero-section-video hero-animated" style="width: 100%; position: relative; margin: 0; padding: 0;">
        <div class="hero-animated-bg"></div>
        <div class="hero-animated-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
            <div class="shape shape-4"></div>
            <div class="shape shape-5"></div>
        </div>
        <div class="hero-overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(135deg, rgba(0, 0, 0, 0.2) 0%, rgba(0, 0, 0, 0.1) 100%); z-index: 2;"></div>
        <div class="hero-content-wrapper" style="width: 100%; position: relative; z-index: 3;">
            <div class="container" style="margin-left: auto; margin-right: auto; max-width: 1400px; padding: 0 1rem;">
                <div class="row justify-content-center" style="margin: 0;">
                    <div class="col-lg-8 text-center hero-content" style="padding: 0 0.5rem;">
                        <h1 class="hero-title">Experience the road like never before</h1>
                        <p class="hero-subtitle">Aliquam adipiscing velit semper morbi. Purus non eu cursus porttitor tristique et gravida. Quis nunc interdum gravida ullamcorper.</p>
                        <a href="#vehicles" class="btn btn-orange btn-lg">View all cars</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container home-container" style="margin-left: auto; margin-right: auto; width: 100%; max-width: 1400px;">
    <!-- Vehicles Section -->
    <div class="mb-5 mt-5 vehicles-section" id="vehicles">
        
        <!-- Filters -->
        <div class="card mb-4 filter-card" style="background: var(--card); border: 1px solid var(--border); border-radius: 12px; width: 100%;">
            <div class="card-body" style="width: 100%;">
                <form method="GET" action="" class="row g-3 align-items-end" style="margin: 0; width: 100%;">
                    <div class="col-md-12 mb-3" style="width: 100%; padding: 0;">
                        <label for="name" class="form-label" style="color: var(--dark-text); font-weight: 600;">Car</label>
                        <select class="form-select" id="name" name="name" onchange="filterCars()" style="background: var(--card); border: 1px solid var(--border); color: var(--dark-text); width: 100%;">
                            <option value="" <?php echo empty($filterName) ? 'selected' : ''; ?>>All</option>
                            <?php foreach ($allCarNames as $carName): ?>
                                <option value="<?php echo htmlspecialchars($carName); ?>" <?php echo $filterName === $carName ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($carName); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4" style="width: 100%; padding: 0;">
                        <label for="fuel" class="form-label" style="color: var(--dark-text); font-weight: 600;">Fuel Type</label>
                        <select class="form-select" id="fuel" name="fuel" onchange="filterCars()" style="background: var(--card); border: 1px solid var(--border); color: var(--dark-text); width: 100%;">
                            <option value="all" <?php echo $filterFuel === 'all' ? 'selected' : ''; ?>>All Fuel Types</option>
                            <option value="Petrol" <?php echo $filterFuel === 'Petrol' ? 'selected' : ''; ?>>Petrol</option>
                            <option value="Diesel" <?php echo $filterFuel === 'Diesel' ? 'selected' : ''; ?>>Diesel</option>
                            <option value="Hybrid" <?php echo $filterFuel === 'Hybrid' ? 'selected' : ''; ?>>Hybrid</option>
                        </select>
                    </div>
                    <div class="col-md-4" style="width: 100%; padding: 0;">
                        <label for="gear" class="form-label" style="color: var(--dark-text); font-weight: 600;">Gear Box</label>
                        <select class="form-select" id="gear" name="gear" onchange="filterCars()" style="background: var(--card); border: 1px solid var(--border); color: var(--dark-text); width: 100%;">
                            <option value="all" <?php echo $filterGear === 'all' ? 'selected' : ''; ?>>All Gear Types</option>
                            <option value="Automat" <?php echo $filterGear === 'Automat' ? 'selected' : ''; ?>>Automat</option>
                            <option value="Manual" <?php echo $filterGear === 'Manual' ? 'selected' : ''; ?>>Manual</option>
                        </select>
                    </div>
                    <div class="col-md-4" style="width: 100%; padding: 0;">
                        <label for="sort_price" class="form-label" style="color: var(--dark-text); font-weight: 600;">Sort by Price</label>
                        <select class="form-select" id="sort_price" name="sort_price" onchange="filterCars()" style="background: var(--card); border: 1px solid var(--border); color: var(--dark-text); width: 100%;">
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
        
        <div id="cars-container" class="row g-4" style="margin: 0; width: 100%;">
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

