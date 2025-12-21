<?php
require_once 'config.php';

$pageTitle = 'Car Details - ' . getSiteName();
include 'header.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$carId = intval($_GET['id']);
$conn = getDBConnection();

// Fetch car details
$stmt = $conn->prepare("SELECT * FROM cars WHERE id = ? AND availability = 'available'");
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

$discountedPrice = calculateDiscountedPrice($car['price'], $car['discount']);
$hasDiscount = $car['discount'] > 0;

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

// WhatsApp number for direct booking
$whatsappNumber = WHATSAPP_NUMBER;

// Fetch other cars (excluding current car)
$stmt = $conn->prepare("SELECT * FROM cars WHERE id != ? AND availability = 'available' ORDER BY RAND() LIMIT 6");
$stmt->bind_param("i", $carId);
$stmt->execute();
$otherCarsResult = $stmt->get_result();
$otherCars = $otherCarsResult->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$conn->close();
?>

<style>
    /* Global Centering for All Content */
    .car-detail-container {
        margin-left: auto;
        margin-right: auto;
    }
    
    .car-detail-row {
        margin-left: auto;
        margin-right: auto;
    }
    
    .row {
        margin-left: auto;
        margin-right: auto;
    }
    
    /* Desktop/PC Responsive Design for Car Detail - Centered */
    @media (min-width: 1200px) {
        .car-detail-container {
            max-width: 1400px !important;
            margin-left: auto !important;
            margin-right: auto !important;
            padding: 0 2rem;
            width: 100%;
        }
        
        .car-detail-row {
            width: 100%;
            margin-left: auto;
            margin-right: auto;
        }
        
        .car-detail-row {
            gap: 3rem;
        }
        
        .car-image-main {
            height: 500px !important;
            margin: 0 auto;
        }
        
        .car-thumbnail {
            width: 120px !important;
            height: 100px !important;
        }
        
        .car-title-detail {
            font-size: 3rem !important;
        }
        
        .car-price-detail {
            font-size: 2.5rem !important;
        }
        
        .car-specs-grid {
            gap: 1.5rem !important;
            margin: 0 auto;
        }
        
        .car-spec-item {
            padding: 1.5rem !important;
        }
        
        .row {
            margin-left: auto;
            margin-right: auto;
        }
    }
    
    @media (min-width: 992px) and (max-width: 1199px) {
        .car-detail-container {
            max-width: 1200px !important;
            margin-left: auto !important;
            margin-right: auto !important;
            padding: 0 1.5rem;
            width: 100%;
        }
        
        .car-detail-row {
            margin-left: auto;
            margin-right: auto;
        }
        
        .car-image-main {
            height: 450px !important;
            margin: 0 auto;
        }
        
        .car-title-detail {
            font-size: 2.5rem !important;
        }
        
        .row {
            margin-left: auto;
            margin-right: auto;
        }
    }
    
    @media (max-width: 991px) {
        .car-detail-container {
            padding: 0 1rem;
            max-width: 100% !important;
            margin-left: auto !important;
            margin-right: auto !important;
            width: 100%;
        }
        
        .car-detail-row {
            gap: 2rem;
            margin-left: auto;
            margin-right: auto;
        }
        
        .car-image-main {
            height: 350px !important;
            margin: 0 auto;
        }
        
        .car-title-detail {
            font-size: 2rem !important;
        }
        
        .car-price-detail {
            font-size: 1.75rem !important;
        }
        
        .row {
            margin-left: auto;
            margin-right: auto;
        }
    }
    
    @media (max-width: 768px) {
        .car-detail-container {
            padding: 0 0.75rem;
            max-width: 100% !important;
            margin-left: auto !important;
            margin-right: auto !important;
            width: 100%;
        }
        
        .car-detail-row {
            margin: 0 auto;
        }
        
        .car-detail-row .col-lg-6 {
            width: 100% !important;
            padding: 0;
            margin-bottom: 1.5rem;
            margin-left: auto;
            margin-right: auto;
        }
        
        .car-image-main {
            height: 300px !important;
            border-radius: 12px !important;
            width: 100%;
            margin: 0 auto;
        }
        
        .car-title-detail {
            font-size: 1.75rem !important;
            margin-bottom: 0.75rem !important;
            text-align: center;
        }
        
        .car-price-detail {
            font-size: 1.5rem !important;
            text-align: center;
        }
        
        .car-thumbnail {
            width: 80px !important;
            height: 70px !important;
        }
        
        .car-spec-item {
            padding: 0.75rem !important;
        }
        
        .car-specs-grid {
            margin: 0 auto;
        }
        
        .car-specs-grid .col-6 {
            width: 50% !important;
            padding: 0.5rem;
            margin-left: auto;
            margin-right: auto;
        }
        
        .btn-purple {
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
        }
        
        .modal-content {
            margin: 1rem auto;
        }
        
        .row {
            margin-left: auto;
            margin-right: auto;
        }
    }
    
    @media (max-width: 576px) {
        .car-detail-container {
            padding: 0 0.5rem;
            max-width: 100% !important;
            margin-left: auto !important;
            margin-right: auto !important;
            width: 100%;
        }
        
        .car-detail-row {
            margin-left: auto;
            margin-right: auto;
        }
        
        .car-image-main {
            height: 250px !important;
            margin: 0 auto;
        }
        
        .car-title-detail {
            font-size: 1.5rem !important;
            text-align: center;
        }
        
        .car-price-detail {
            font-size: 1.25rem !important;
            text-align: center;
        }
        
        .car-thumbnail {
            width: 70px !important;
            height: 60px !important;
        }
        
        .car-spec-item {
            padding: 0.5rem !important;
            font-size: 0.9rem;
        }
        
        .car-specs-grid {
            margin: 0 auto;
        }
        
        .car-specs-grid .col-6 {
            padding: 0.25rem;
            margin-left: auto;
            margin-right: auto;
        }
        
        .modal-dialog {
            margin: 0.5rem auto;
        }
        
        .modal-content {
            border-radius: 12px;
            margin: 0 auto;
        }
        
        .row {
            margin-left: auto;
            margin-right: auto;
        }
    }
    
    /* Specific optimization for 428px width (iPhone 12/13 Pro Max) */
    @media (max-width: 428px) {
        .car-detail-container {
            padding: 0 0.75rem;
            max-width: 100% !important;
            margin-left: auto !important;
            margin-right: auto !important;
            width: 100%;
        }
        
        .car-detail-row {
            gap: 1.5rem;
            margin: 0 auto;
        }
        
        .car-detail-row .col-lg-6 {
            width: 100% !important;
            padding: 0;
            margin-bottom: 1.5rem;
            margin-left: auto;
            margin-right: auto;
        }
        
        .car-image-main {
            height: 280px !important;
            border-radius: 12px !important;
            width: 100%;
            margin: 0 auto;
        }
        
        .car-thumbnail {
            width: 75px !important;
            height: 65px !important;
            border-radius: 6px !important;
        }
        
        .car-title-detail {
            font-size: 1.6rem !important;
            margin-bottom: 0.75rem !important;
            line-height: 1.3;
            text-align: center;
        }
        
        .car-price-detail {
            font-size: 1.4rem !important;
            margin-bottom: 1.25rem !important;
            text-align: center;
        }
        
        .car-specs-grid {
            gap: 0.75rem !important;
            margin: 0 auto;
        }
        
        .car-specs-grid .col-6 {
            width: 100% !important;
            padding: 0.5rem;
            margin-left: auto;
            margin-right: auto;
        }
        
        .car-spec-item {
            padding: 0.85rem !important;
        }
        
        .car-spec-item i {
            font-size: 1.3rem !important;
        }
        
        .car-spec-item div {
            font-size: 0.9rem;
        }
        
        .car-spec-item div:first-child {
            font-size: 0.8rem;
        }
        
        .btn-purple {
            padding: 0.85rem 1.5rem;
            font-size: 1rem;
            min-height: 48px;
            margin-bottom: 1.25rem;
            margin-left: auto;
            margin-right: auto;
            display: block;
        }
        
        .modal-dialog {
            margin: 0.75rem auto;
        }
        
        .modal-content {
            margin: 0 auto;
        }
        
        .modal-header {
            padding: 1.25rem;
        }
        
        .modal-body {
            padding: 1.25rem;
        }
        
        .modal-title {
            font-size: 1.3rem;
        }
        
        .form-label {
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            font-size: 16px;
            padding: 0.75rem;
            margin-bottom: 1rem;
        }
        
        .alert {
            padding: 1rem;
            font-size: 0.9rem;
        }
        
        #total-price {
            font-size: 1.1rem;
        }
        
        .row {
            margin-left: auto;
            margin-right: auto;
        }
    }
</style>

<div class="container car-detail-container" style="margin-left: auto; margin-right: auto; width: 100%; max-width: 1400px;">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4" style="--bs-breadcrumb-divider: ' / ';">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($car['name']); ?></li>
        </ol>
    </nav>

    <div class="row mb-5 car-detail-row" style="margin-left: auto; margin-right: auto;">
        <!-- Car Images -->
        <div class="col-lg-6 mb-4">
            <div class="position-relative mb-3">
                <?php if ($hasDiscount): ?>
                    <span class="discount-badge"><?php echo formatDiscount($car['discount']); ?>% OFF</span>
                <?php endif; ?>
                <img src="<?php echo htmlspecialchars($car['image']); ?>" 
                     class="img-fluid rounded car-image-main" 
                     style="width: 100%; height: 400px; object-fit: cover; border-radius: 16px;"
                     alt="<?php echo htmlspecialchars($car['name']); ?>"
                     id="mainCarImage"
                     onerror="this.src='https://via.placeholder.com/800x600?text=Car+Image'">
            </div>
            <!-- Thumbnails -->
            <div class="d-flex gap-2">
                <img src="<?php echo htmlspecialchars($car['image']); ?>" 
                     class="img-thumbnail car-thumbnail" 
                     style="width: 100px; height: 80px; object-fit: cover; cursor: pointer; border-radius: 8px;"
                     onclick="document.getElementById('mainCarImage').src = this.src"
                     onerror="this.src='https://via.placeholder.com/100x80?text=Car'">
                <img src="<?php echo htmlspecialchars($car['image']); ?>" 
                     class="img-thumbnail car-thumbnail" 
                     style="width: 100px; height: 80px; object-fit: cover; cursor: pointer; border-radius: 8px;"
                     onclick="document.getElementById('mainCarImage').src = this.src"
                     onerror="this.src='https://via.placeholder.com/100x80?text=Car'">
                <img src="<?php echo htmlspecialchars($car['image']); ?>" 
                     class="img-thumbnail car-thumbnail" 
                     style="width: 100px; height: 80px; object-fit: cover; cursor: pointer; border-radius: 8px;"
                     onclick="document.getElementById('mainCarImage').src = this.src"
                     onerror="this.src='https://via.placeholder.com/100x80?text=Car'">
            </div>
        </div>

        <!-- Car Details -->
        <div class="col-lg-6">
            <h1 class="car-title-detail" style="font-size: 2.5rem; font-weight: 800; color: var(--dark-text); margin-bottom: 1rem;">
                <?php echo htmlspecialchars($car['name']); ?>
            </h1>
            
            <div class="mb-4">
                <?php if ($hasDiscount): ?>
                    <span class="price-original" style="font-size: 1.2rem;" data-price-mad="<?php echo $car['price']; ?>">
                        <span class="price-amount"><?php echo formatPrice($car['price']); ?></span>
                        <span class="price-currency">MAD</span>
                    </span>
                <?php endif; ?>
                <span class="price-discounted car-price-detail" style="font-size: 2rem;" data-price-mad="<?php echo $discountedPrice; ?>">
                    <span class="price-amount"><?php echo formatPrice($discountedPrice); ?></span>
                    <span class="price-currency">MAD</span> / day
                </span>
            </div>
            
            <!-- Technical Specifications -->
            <div class="mb-4">
                <h5 style="font-weight: 700; margin-bottom: 1.5rem; color: var(--dark-text);">Technical Specification</h5>
                <div class="row g-3 car-specs-grid">
                    <div class="col-6">
                        <div class="car-spec-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: var(--card); border: 1px solid var(--border); border-radius: 12px;">
                            <i class="bi bi-gear-fill" style="font-size: 1.5rem; color: var(--primary-purple);"></i>
                            <div>
                                <div style="font-size: 0.85rem; color: var(--muted);">Gear Box</div>
                                <div style="font-weight: 600; color: var(--dark-text);"><?php echo htmlspecialchars($specifications['gear_box']); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="car-spec-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: var(--card); border: 1px solid var(--border); border-radius: 12px;">
                            <i class="bi bi-fuel-pump-fill" style="font-size: 1.5rem; color: var(--primary-purple);"></i>
                            <div>
                                <div style="font-size: 0.85rem; color: var(--muted);">Fuel</div>
                                <div style="font-weight: 600; color: var(--dark-text);"><?php echo htmlspecialchars($specifications['fuel']); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="car-spec-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: var(--card); border: 1px solid var(--border); border-radius: 12px;">
                            <i class="bi bi-door-open" style="font-size: 1.5rem; color: var(--primary-purple);"></i>
                            <div>
                                <div style="font-size: 0.85rem; color: var(--muted);">Doors</div>
                                <div style="font-weight: 600; color: var(--dark-text);"><?php echo htmlspecialchars($specifications['doors']); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="car-spec-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: var(--card); border: 1px solid var(--border); border-radius: 12px;">
                            <i class="bi bi-snow" style="font-size: 1.5rem; color: var(--primary-purple);"></i>
                            <div>
                                <div style="font-size: 0.85rem; color: var(--muted);">Air Conditioner</div>
                                <div style="font-weight: 600; color: var(--dark-text);"><?php echo htmlspecialchars($specifications['air_conditioner']); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="car-spec-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: var(--card); border: 1px solid var(--border); border-radius: 12px;">
                            <i class="bi bi-people-fill" style="font-size: 1.5rem; color: var(--primary-purple);"></i>
                            <div>
                                <div style="font-size: 0.85rem; color: var(--muted);">Seats</div>
                                <div style="font-weight: 600; color: var(--dark-text);"><?php echo htmlspecialchars($specifications['seats']); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="car-spec-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: var(--card); border: 1px solid var(--border); border-radius: 12px;">
                            <i class="bi bi-speedometer2" style="font-size: 1.5rem; color: var(--primary-purple);"></i>
                            <div>
                                <div style="font-size: 0.85rem; color: var(--muted);">Distance</div>
                                <div style="font-weight: 600; color: var(--dark-text);"><?php echo htmlspecialchars($specifications['distance']); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rent Button -->
            <button type="button" class="btn btn-purple btn-lg w-100 mb-4" data-bs-toggle="modal" data-bs-target="#bookingModal" style="padding: 1rem; font-size: 1.1rem;">
                Rent a car
            </button>

            <!-- Car Equipment -->
            <div>
                <h5 style="font-weight: 700; margin-bottom: 1rem; color: var(--dark-text);">Car Equipment</h5>
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <i class="bi bi-check-circle-fill" style="color: var(--primary-purple); font-size: 1.2rem;"></i>
                        <span style="color: var(--dark-text);">ABS</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <i class="bi bi-check-circle-fill" style="color: var(--primary-purple); font-size: 1.2rem;"></i>
                        <span style="color: var(--dark-text);">Air Bags</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <i class="bi bi-check-circle-fill" style="color: var(--primary-purple); font-size: 1.2rem;"></i>
                        <span style="color: var(--dark-text);">Cruise Control</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <i class="bi bi-check-circle-fill" style="color: var(--primary-purple); font-size: 1.2rem;"></i>
                        <span style="color: var(--dark-text);">Air Conditioner</span>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="mt-4">
                <h5 style="font-weight: 700; margin-bottom: 1rem; color: var(--dark-text);">Description</h5>
                <p style="color: var(--muted); line-height: 1.8;"><?php echo nl2br(htmlspecialchars($car['description'])); ?></p>
            </div>
        </div>
    </div>

    <!-- Booking Modal -->
    <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none;">
                <div class="modal-header" style="border-bottom: 1px solid var(--border); padding: 1.5rem;">
                    <h5 class="modal-title" id="bookingModalLabel" style="font-weight: 700; font-size: 1.5rem; color: var(--dark-text);">Book This Car</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 1.5rem;">
                    <form id="bookingForm" onsubmit="sendToWhatsApp(event);">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label" style="font-weight: 600; color: var(--dark-text);">Full Name *</label>
                                <input type="text" class="form-control" id="name" name="name" required 
                                       style="padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border); background: var(--card); color: var(--dark-text);">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label" style="font-weight: 600; color: var(--dark-text);">Phone Number *</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required 
                                       style="padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border); background: var(--card); color: var(--dark-text);">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label" style="font-weight: 600; color: var(--dark-text);">Email Address *</label>
                            <input type="email" class="form-control" id="email" name="email" required 
                                   style="padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border); background: var(--card); color: var(--dark-text);">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label" style="font-weight: 600; color: var(--dark-text);">Start Date *</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" required 
                                       style="padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border); background: var(--card); color: var(--dark-text);"
                                       min="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label" style="font-weight: 600; color: var(--dark-text);">End Date *</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" required 
                                       style="padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border); background: var(--card); color: var(--dark-text);"
                                       min="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                        <div class="alert" style="background: var(--card); border: 1px solid var(--border); border-radius: 12px; padding: 1rem;">
                            <strong style="color: var(--dark-text);">Estimated Total:</strong> 
                            <span id="total-price" data-price-mad="0" style="color: var(--primary-purple); font-weight: 700; font-size: 1.2rem;">
                                <span class="price-amount">0</span>
                                <span class="price-currency">MAD</span>
                            </span>
                            <small style="color: var(--muted); display: block; margin-top: 0.5rem;">(Calculated based on selected dates)</small>
                        </div>
                        <button type="submit" class="btn btn-purple w-100" style="padding: 1rem; font-size: 1.1rem;">
                            <i class="bi bi-whatsapp"></i> Send to WhatsApp
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Other Cars Section -->
    <?php if (!empty($otherCars)): ?>
        <div class="mt-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="section-title" style="text-align: left; margin-bottom: 0; font-size: 2rem;">Other cars</h2>
                <a href="index.php" style="color: var(--primary-purple); text-decoration: none; font-weight: 600;">
                    View All <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="row g-4">
                <?php foreach ($otherCars as $otherCar): 
                    $otherDiscountedPrice = calculateDiscountedPrice($otherCar['price'], $otherCar['discount']);
                    $otherHasDiscount = $otherCar['discount'] > 0;
                ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="car-card <?php echo $otherHasDiscount ? 'has-discount' : ''; ?>">
                            <div class="position-relative">
                                <?php if ($otherHasDiscount): ?>
                                    <span class="discount-badge"><?php echo formatDiscount($otherCar['discount']); ?>% OFF</span>
                                <?php endif; ?>
                                <img src="<?php echo htmlspecialchars($otherCar['image']); ?>" 
                                     class="car-image" 
                                     alt="<?php echo htmlspecialchars($otherCar['name']); ?>"
                                     onerror="this.src='https://via.placeholder.com/800x600?text=Car+Image'">
                            </div>
                            <div class="car-card-body">
                                <h5 class="car-title"><?php echo htmlspecialchars($otherCar['name']); ?></h5>
                                <div class="car-price">
                                    <?php if ($otherHasDiscount): ?>
                                        <span class="price-original" data-price-mad="<?php echo $otherCar['price']; ?>">
                                            <span class="price-amount"><?php echo formatPrice($otherCar['price']); ?></span>
                                            <span class="price-currency">MAD</span>
                                        </span>
                                    <?php endif; ?>
                                    <span class="price-discounted" data-price-mad="<?php echo $otherDiscountedPrice; ?>">
                                        <span class="price-amount"><?php echo formatPrice($otherDiscountedPrice); ?></span>
                                        <span class="price-currency">MAD</span> / day
                                    </span>
                                </div>
                                <div class="car-features">
                                    <div class="car-feature">
                                        <i class="bi bi-gear-fill"></i>
                                        <span><?php 
                                            $otherSpecs = ['gear_box' => 'Automat', 'fuel' => 'Petrol', 'air_conditioner' => 'Yes'];
                                            if (!empty($otherCar['specifications'])) {
                                                $otherCarSpecs = json_decode($otherCar['specifications'], true);
                                                if (is_array($otherCarSpecs)) {
                                                    $otherSpecs = array_merge($otherSpecs, $otherCarSpecs);
                                                }
                                            }
                                            echo htmlspecialchars($otherSpecs['gear_box']); 
                                        ?></span>
                                    </div>
                                    <div class="car-feature">
                                        <i class="bi bi-fuel-pump-fill"></i>
                                        <span><?php echo htmlspecialchars($otherSpecs['fuel']); ?></span>
                                    </div>
                                    <div class="car-feature">
                                        <i class="bi bi-snow"></i>
                                        <span><?php echo htmlspecialchars($otherSpecs['air_conditioner']); ?></span>
                                    </div>
                                </div>
                                <a href="car-detail.php?id=<?php echo $otherCar['id']; ?>" class="btn btn-purple w-100">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
// WhatsApp configuration
const whatsappNumber = '<?php echo $whatsappNumber; ?>';
const carName = '<?php echo addslashes($car['name']); ?>';
const dailyPrice = <?php echo $discountedPrice; ?>;

// Helper function to parse date string as local date (avoid timezone issues)
function parseLocalDate(dateString) {
    const parts = dateString.split('-');
    return new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]));
}

// Helper function to get today's date as local date (no time)
function getTodayLocal() {
    const today = new Date();
    return new Date(today.getFullYear(), today.getMonth(), today.getDate());
}

// Helper function to calculate days between two dates
function calculateDays(startDateStr, endDateStr) {
    const start = parseLocalDate(startDateStr);
    const end = parseLocalDate(endDateStr);
    const diffTime = end - start;
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    return diffDays;
}

// Helper function to format date for display
function formatDateForDisplay(dateString) {
    const date = parseLocalDate(dateString);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
}

// Helper function to add one day to a date string
function addOneDay(dateString) {
    const date = parseLocalDate(dateString);
    date.setDate(date.getDate() + 1);
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

// Function to send form data to WhatsApp
function sendToWhatsApp(event) {
    event.preventDefault();
    
    const form = document.getElementById('bookingForm');
    const name = document.getElementById('name').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const email = document.getElementById('email').value.trim();
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    
    // Validation
    if (!name || !phone || !email || !startDate || !endDate) {
        alert('Please fill in all fields.');
        return false;
    }
    
    // Validate dates using local date parsing
    const start = parseLocalDate(startDate);
    const end = parseLocalDate(endDate);
    const today = getTodayLocal();
    
    if (start < today) {
        alert('Start date cannot be in the past.');
        return false;
    }
    
    if (end <= start) {
        alert('End date must be after start date.');
        return false;
    }
    
    // Calculate total price
    const days = calculateDays(startDate, endDate);
    const totalPriceMAD = dailyPrice * days;
    
    // Get current currency
    const currentCurrency = localStorage.getItem('currency') || 'MAD';
    // Use rates from database if available, otherwise use defaults
    const exchangeRates = window.EXCHANGE_RATES || {
        'MAD': 1.0,
        'EUR': 0.092,
        'USD': 0.10
    };
    const currencySymbols = {
        'MAD': 'MAD',
        'EUR': '€',
        'USD': '$'
    };
    
    function convertPrice(priceMAD, toCurrency) {
        if (toCurrency === 'MAD' || !exchangeRates[toCurrency]) {
            return priceMAD;
        }
        return priceMAD * exchangeRates[toCurrency];
    }
    
    function formatPrice(price) {
        const formatted = parseFloat(price).toFixed(2);
        return parseFloat(formatted).toString();
    }
    
    const dailyPriceConverted = convertPrice(dailyPrice, currentCurrency);
    const totalPriceConverted = convertPrice(totalPriceMAD, currentCurrency);
    const symbol = currencySymbols[currentCurrency] || 'MAD';
    
    // Format dates for display
    const formattedStartDate = formatDateForDisplay(startDate);
    const formattedEndDate = formatDateForDisplay(endDate);
    
    // Create WhatsApp message
    const message = `*Car Rental Booking Request*

*Car:* ${carName}
*Name:* ${name}
*Phone:* ${phone}
*Email:* ${email}
*Start Date:* ${formattedStartDate}
*End Date:* ${formattedEndDate}
*Rental Days:* ${days} day(s)
*Daily Price:* ${formatPrice(dailyPriceConverted)} ${symbol}
*Total Price:* ${formatPrice(totalPriceConverted)} ${symbol}

Thank you for choosing us!`;

    // Encode message for URL
    const encodedMessage = encodeURIComponent(message);
    
    // Create WhatsApp link
    const whatsappLink = `https://wa.me/${whatsappNumber}?text=${encodedMessage}`;
    
    // Open WhatsApp in new tab
    window.open(whatsappLink, '_blank');
    
    // Close modal after a short delay
    setTimeout(function() {
        const modal = bootstrap.Modal.getInstance(document.getElementById('bookingModal'));
        if (modal) {
            modal.hide();
        }
        // Reset form
        form.reset();
        const totalPriceSpan = document.getElementById('total-price');
        totalPriceSpan.setAttribute('data-price-mad', '0');
        totalPriceSpan.querySelector('.price-amount').textContent = '0';
        totalPriceSpan.querySelector('.price-currency').textContent = localStorage.getItem('currency') || 'MAD';
    }, 500);
    
    return false;
}

// Calculate total price based on dates
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const totalPriceSpan = document.getElementById('total-price');
    
    if (startDateInput && endDateInput && totalPriceSpan) {
        function calculateTotal() {
            if (startDateInput.value && endDateInput.value) {
                const start = parseLocalDate(startDateInput.value);
                const end = parseLocalDate(endDateInput.value);
                if (end > start) {
                    const days = calculateDays(startDateInput.value, endDateInput.value);
                    const totalMAD = dailyPrice * days;
                    totalPriceSpan.setAttribute('data-price-mad', totalMAD);
                    
                    // Update with current currency
                    const currentCurrency = localStorage.getItem('currency') || 'MAD';
                    // Use rates from database if available, otherwise use defaults
                    const exchangeRates = window.EXCHANGE_RATES || {
                        'MAD': 1.0,
                        'EUR': 0.092,
                        'USD': 0.10
                    };
                    const currencySymbols = {
                        'MAD': 'MAD',
                        'EUR': '€',
                        'USD': '$'
                    };
                    
                    function convertPrice(priceMAD, toCurrency) {
                        if (toCurrency === 'MAD' || !exchangeRates[toCurrency]) {
                            return priceMAD;
                        }
                        return priceMAD * exchangeRates[toCurrency];
                    }
                    
                    function formatPrice(price) {
                        const formatted = parseFloat(price).toFixed(2);
                        return parseFloat(formatted).toString();
                    }
                    
                    const convertedTotal = convertPrice(totalMAD, currentCurrency);
                    const symbol = currencySymbols[currentCurrency] || 'MAD';
                    
                    totalPriceSpan.querySelector('.price-amount').textContent = formatPrice(convertedTotal);
                    totalPriceSpan.querySelector('.price-currency').textContent = symbol;
                } else {
                    totalPriceSpan.setAttribute('data-price-mad', '0');
                    totalPriceSpan.querySelector('.price-amount').textContent = '0';
                    totalPriceSpan.querySelector('.price-currency').textContent = localStorage.getItem('currency') || 'MAD';
                }
            }
        }
        
        startDateInput.addEventListener('change', calculateTotal);
        endDateInput.addEventListener('change', calculateTotal);
        
        // Update end date min when start date changes
        startDateInput.addEventListener('change', function() {
            if (this.value) {
                const nextDay = addOneDay(this.value);
                endDateInput.min = nextDay;
            }
        });
    }
});
</script>

<?php include 'footer.php'; ?>

