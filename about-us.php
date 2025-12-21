<?php
require_once 'config.php';

$pageTitle = 'About Us - ' . getSiteName();
include 'header.php';
?>

<style>
    /* Global Centering for All Content */
    .about-container {
        margin-left: auto;
        margin-right: auto;
    }
    
    .about-section {
        margin-left: auto;
        margin-right: auto;
    }
    
    .row {
        margin-left: auto;
        margin-right: auto;
    }
    
    /* Hero Section */
    .about-hero {
        background: linear-gradient(135deg, rgba(108, 92, 231, 0.1) 0%, rgba(255, 107, 53, 0.1) 100%);
        border-radius: 24px;
        padding: 4rem 2rem;
        margin-bottom: 4rem;
        text-align: center;
    }
    
    .about-hero h1 {
        font-size: 3.5rem;
        font-weight: 800;
        color: var(--dark-text);
        margin-bottom: 1.5rem;
        background: linear-gradient(135deg, var(--primary-purple), var(--primary-orange));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .about-hero p {
        font-size: 1.25rem;
        color: var(--muted);
        max-width: 800px;
        margin: 0 auto;
        line-height: 1.8;
    }
    
    /* Feature Cards */
    .feature-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 2.5rem;
        height: 100%;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    
    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.15);
        border-color: var(--primary-purple);
    }
    
    .feature-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--primary-purple), var(--primary-orange));
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        box-shadow: 0 8px 20px rgba(108, 92, 231, 0.3);
    }
    
    .feature-icon i {
        font-size: 2.5rem;
        color: white;
    }
    
    .feature-card h4 {
        font-weight: 700;
        color: var(--dark-text);
        margin-bottom: 1rem;
        font-size: 1.5rem;
    }
    
    .feature-card p {
        color: var(--muted);
        line-height: 1.8;
        margin: 0;
    }
    
    /* Statistics Section */
    .stats-section {
        background: linear-gradient(135deg, var(--primary-purple) 0%, var(--primary-orange) 100%);
        border-radius: 24px;
        padding: 4rem 2rem;
        margin: 4rem 0;
        color: white;
    }
    
    .stat-item {
        text-align: center;
        padding: 1.5rem;
    }
    
    .stat-number {
        font-size: 4.5rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        line-height: 1;
    }
    
    .stat-label {
        font-size: 1.3rem;
        font-weight: 600;
        opacity: 0.95;
    }
    
    /* Why Choose Us Section */
    .why-choose-section {
        margin: 4rem 0;
    }
    
    .why-choose-item {
        display: flex;
        align-items: start;
        gap: 1.5rem;
        padding: 2rem;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 16px;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }
    
    .why-choose-item:hover {
        transform: translateX(10px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        border-color: var(--primary-orange);
    }
    
    .why-choose-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--primary-purple), var(--primary-orange));
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .why-choose-icon i {
        font-size: 1.8rem;
        color: white;
    }
    
    .why-choose-content h5 {
        font-weight: 700;
        color: var(--dark-text);
        margin-bottom: 0.5rem;
        font-size: 1.25rem;
    }
    
    .why-choose-content p {
        color: var(--muted);
        margin: 0;
        line-height: 1.7;
    }
    
    /* Mission Section */
    .mission-section {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 24px;
        padding: 4rem 3rem;
        margin: 4rem 0;
        text-align: center;
    }
    
    .mission-section h2 {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--dark-text);
        margin-bottom: 1.5rem;
    }
    
    .mission-section p {
        font-size: 1.1rem;
        color: var(--muted);
        line-height: 1.9;
        max-width: 900px;
        margin: 0 auto;
    }
    
    /* Desktop/PC Responsive Design - Centered */
    @media (min-width: 1200px) {
        .about-container {
            max-width: 1400px !important;
            margin-left: auto !important;
            margin-right: auto !important;
            padding: 0 2rem;
            width: 100%;
        }
        
        .about-section {
            width: 100%;
            max-width: 1400px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .about-hero {
            padding: 5rem 3rem;
        }
        
        .about-hero h1 {
            font-size: 4rem;
        }
        
        .about-hero p {
            font-size: 1.4rem;
        }
        
        .feature-card {
            padding: 3rem;
        }
        
        .stats-section {
            padding: 5rem 3rem;
        }
        
        .stat-number {
            font-size: 5.5rem;
        }
        
        .mission-section {
            padding: 5rem 4rem;
        }
        
        .row {
            margin-left: auto;
            margin-right: auto;
        }
    }
    
    @media (min-width: 992px) and (max-width: 1199px) {
        .about-container {
            max-width: 1200px !important;
            margin-left: auto !important;
            margin-right: auto !important;
            padding: 0 1.5rem;
            width: 100%;
        }
        
        .about-section {
            margin-left: auto;
            margin-right: auto;
        }
        
        .about-hero h1 {
            font-size: 3rem;
        }
        
        .stat-number {
            font-size: 4.5rem;
        }
        
        .row {
            margin-left: auto;
            margin-right: auto;
        }
    }
    
    @media (max-width: 991px) {
        .about-container {
            padding: 0 1rem;
            max-width: 100% !important;
            margin-left: auto !important;
            margin-right: auto !important;
            width: 100%;
        }
        
        .about-section {
            padding: 2rem 0;
            width: 100%;
            margin-left: auto;
            margin-right: auto;
        }
        
        .about-hero {
            padding: 3rem 1.5rem;
            margin-bottom: 3rem;
        }
        
        .about-hero h1 {
            font-size: 2.5rem;
        }
        
        .about-hero p {
            font-size: 1.1rem;
        }
        
        .feature-card {
            padding: 2rem;
            margin-bottom: 1.5rem;
        }
        
        .stats-section {
            padding: 3rem 1.5rem;
            margin: 3rem 0;
        }
        
        .stat-number {
            font-size: 3.5rem;
        }
        
        .mission-section {
            padding: 3rem 2rem;
        }
        
        .row {
            margin-left: auto;
            margin-right: auto;
        }
    }
    
    @media (max-width: 768px) {
        .about-container {
            padding: 0 0.75rem;
            max-width: 100% !important;
            margin-left: auto !important;
            margin-right: auto !important;
            width: 100%;
        }
        
        .about-section {
            padding: 1.5rem 0;
            width: 100%;
            margin-left: auto;
            margin-right: auto;
        }
        
        .about-hero {
            padding: 2.5rem 1.5rem;
            margin-bottom: 2rem;
            border-radius: 16px;
        }
        
        .about-hero h1 {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        
        .about-hero p {
            font-size: 1rem;
        }
        
        .feature-card {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .feature-icon {
            width: 70px;
            height: 70px;
            margin-bottom: 1.25rem;
        }
        
        .feature-icon i {
            font-size: 2rem;
        }
        
        .feature-card h4 {
            font-size: 1.3rem;
        }
        
        .stats-section {
            padding: 2.5rem 1.5rem;
            margin: 2.5rem 0;
            border-radius: 16px;
        }
        
        .stat-item {
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .stat-number {
            font-size: 3rem;
        }
        
        .stat-label {
            font-size: 1.1rem;
        }
        
        .why-choose-section {
            margin: 2.5rem 0;
        }
        
        .why-choose-item {
            padding: 1.5rem;
            margin-bottom: 1.25rem;
        }
        
        .mission-section {
            padding: 2.5rem 1.5rem;
            margin: 2.5rem 0;
            border-radius: 16px;
        }
        
        .mission-section h2 {
            font-size: 2rem;
        }
        
        .mission-section p {
            font-size: 1rem;
        }
        
        .section-title {
            font-size: 1.75rem !important;
            margin-bottom: 2rem !important;
            text-align: center;
        }
        
        .row {
            margin-left: auto;
            margin-right: auto;
        }
    }
    
    @media (max-width: 576px) {
        .about-container {
            padding: 0 0.5rem;
            max-width: 100% !important;
            margin-left: auto !important;
            margin-right: auto !important;
            width: 100%;
        }
        
        .about-hero {
            padding: 2rem 1rem;
        }
        
        .about-hero h1 {
            font-size: 1.75rem;
        }
        
        .about-hero p {
            font-size: 0.95rem;
        }
        
        .feature-card {
            padding: 1.25rem;
        }
        
        .feature-icon {
            width: 60px;
            height: 60px;
        }
        
        .feature-icon i {
            font-size: 1.8rem;
        }
        
        .feature-card h4 {
            font-size: 1.2rem;
        }
        
        .stats-section {
            padding: 2rem 1rem;
        }
        
        .stat-number {
            font-size: 2.5rem;
        }
        
        .stat-label {
            font-size: 1rem;
        }
        
        .why-choose-item {
            padding: 1.25rem;
            flex-direction: column;
            text-align: center;
        }
        
        .why-choose-icon {
            margin: 0 auto;
        }
        
        .mission-section {
            padding: 2rem 1rem;
        }
        
        .mission-section h2 {
            font-size: 1.75rem;
        }
        
        .section-title {
            font-size: 1.5rem !important;
            text-align: center;
        }
        
        .row {
            margin-left: auto;
            margin-right: auto;
        }
    }
    
    /* Specific optimization for 428px width (iPhone 12/13 Pro Max) */
    @media (max-width: 428px) {
        .about-container {
            padding: 0 0.75rem;
            max-width: 100% !important;
            margin-left: auto !important;
            margin-right: auto !important;
            width: 100%;
        }
        
        .about-section {
            padding: 1.75rem 0;
            width: 100%;
            margin-left: auto;
            margin-right: auto;
        }
        
        .about-hero {
            padding: 2rem 1.25rem;
            margin-bottom: 2rem;
        }
        
        .about-hero h1 {
            font-size: 1.8rem;
        }
        
        .about-hero p {
            font-size: 0.95rem;
        }
        
        .feature-card {
            padding: 1.5rem;
        }
        
        .stats-section {
            padding: 2rem 1.25rem;
        }
        
        .stat-number {
            font-size: 2.75rem;
        }
        
        .mission-section {
            padding: 2rem 1.25rem;
        }
        
        .mission-section h2 {
            font-size: 1.8rem;
        }
        
        .section-title {
            font-size: 1.7rem !important;
            margin-bottom: 1.75rem !important;
            padding: 0 0.5rem;
            text-align: center;
        }
        
        .row {
            margin-left: auto;
            margin-right: auto;
        }
    }
</style>

<div class="container about-container" style="margin-left: auto; margin-right: auto; width: 100%; max-width: 1400px;">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4" style="--bs-breadcrumb-divider: ' / ';">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">About Us</li>
        </ol>
    </nav>

    <!-- About Us Section -->
    <div class="py-5 about-section" style="margin-left: auto; margin-right: auto;">
        
        <!-- Hero Section -->
        <div class="about-hero">
            <h1>About <?php echo htmlspecialchars(getSiteName()); ?></h1>
            <p>We are a trusted car rental company dedicated to providing exceptional service and quality vehicles. With years of experience in the industry, we strive to make every journey memorable and comfortable for our customers.</p>
        </div>

        <!-- Features Grid -->
        <div class="mb-5">
            <h2 class="section-title" style="text-align: center; margin-bottom: 3rem;">Why Choose Us</h2>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-car-front"></i>
                        </div>
                        <h4>Wide Selection</h4>
                        <p>Choose from our extensive fleet of premium vehicles, from economy to luxury, all maintained to the highest standards.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h4>Safe & Reliable</h4>
                        <p>All our vehicles undergo regular maintenance and safety checks to ensure your peace of mind on every journey.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-clock"></i>
                        </div>
                        <h4>24/7 Support</h4>
                        <p>Our dedicated customer support team is available around the clock to assist you with any questions or concerns.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <h4>Best Prices</h4>
                        <p>Competitive pricing with flexible rental options. No hidden fees, transparent pricing for all our services.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="stats-section">
            <div class="row">
                <div class="col-md-4">
                    <div class="stat-item">
                        <div class="stat-number">20k+</div>
                        <div class="stat-label">Happy Customers</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-item">
                        <div class="stat-number">540+</div>
                        <div class="stat-label">Cars in Fleet</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-item">
                        <div class="stat-number">25+</div>
                        <div class="stat-label">Years of Experience</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Why Choose Us Details -->
        <div class="why-choose-section">
            <h2 class="section-title" style="text-align: center; margin-bottom: 3rem;">What Makes Us Different</h2>
            <div class="row">
                <div class="col-lg-6">
                    <div class="why-choose-item">
                        <div class="why-choose-icon">
                            <i class="bi bi-check-lg"></i>
                        </div>
                        <div class="why-choose-content">
                            <h5>Premium Vehicle Fleet</h5>
                            <p>From economy cars to luxury vehicles, we offer a diverse range of well-maintained cars to suit every need and budget.</p>
                        </div>
                    </div>
                    <div class="why-choose-item">
                        <div class="why-choose-icon">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <div class="why-choose-content">
                            <h5>Easy Booking Process</h5>
                            <p>Book your car in minutes through our simple and secure online booking system. Quick, easy, and hassle-free.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="why-choose-item">
                        <div class="why-choose-icon">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div class="why-choose-content">
                            <h5>Flexible Rental Options</h5>
                            <p>Choose from daily, weekly, or monthly rentals with flexible pick-up and return times to fit your schedule.</p>
                        </div>
                    </div>
                    <div class="why-choose-item">
                        <div class="why-choose-icon">
                            <i class="bi bi-headset"></i>
                        </div>
                        <div class="why-choose-content">
                            <h5>24/7 Customer Support</h5>
                            <p>Our dedicated support team is available around the clock to assist you with any questions or emergency situations.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mission Statement -->
        <div class="mission-section">
            <h2>Our Mission</h2>
            <p>At <?php echo htmlspecialchars(getSiteName()); ?>, our mission is to provide exceptional car rental services that exceed our customers' expectations. We are committed to offering quality vehicles, competitive prices, and outstanding customer service. Every journey with us is designed to be comfortable, safe, and memorable. We believe in building long-lasting relationships with our customers through trust, reliability, and continuous improvement.</p>
        </div>

    </div>
</div>

<?php include 'footer.php'; ?>
