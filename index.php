<?php
require_once 'config.php';

$pageTitle = 'Home - RENTAL CARS';
include 'header.php';

$conn = getDBConnection();

// Fetch all available cars
$stmt = $conn->prepare("SELECT * FROM cars WHERE availability = 'available' ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
$cars = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
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

        <?php if (empty($cars)): ?>
            <div class="alert alert-info text-center" style="background: var(--card); border: 1px solid var(--border); border-radius: 12px; padding: 2rem;">
                <h4 style="color: var(--dark-text);">No cars available at the moment.</h4>
                <p style="color: var(--muted);">Please check back later or contact us for more information.</p>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($cars as $car): 
                    $discountedPrice = calculateDiscountedPrice($car['price'], $car['discount']);
                    $hasDiscount = $car['discount'] > 0;
                ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="car-card <?php echo $hasDiscount ? 'has-discount' : ''; ?>">
                            <div class="position-relative">
                                <?php if ($hasDiscount): ?>
                                    <span class="discount-badge"><?php echo $car['discount']; ?>% OFF</span>
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
                                    $carSpecs = ['gear_box' => 'Automat', 'fuel' => '95', 'air_conditioner' => 'Yes'];
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
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>

