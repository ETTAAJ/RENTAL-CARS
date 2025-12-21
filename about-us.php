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
        
        .about-section {
            padding: 4rem 0;
        }
        
        .about-title {
            font-size: 3rem !important;
        }
        
        .about-feature-grid {
            gap: 2rem !important;
            margin: 0 auto;
        }
        
        .about-video-section {
            height: 600px !important;
            margin: 3rem auto;
            max-width: 100%;
        }
        
        .about-statistics {
            padding: 3rem 0;
            margin: 0 auto;
        }
        
        .about-stat-number {
            font-size: 5rem !important;
        }
        
        .about-memories-section {
            padding: 2rem 0;
            margin: 0 auto;
        }
        
        /* Center all rows */
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
        
        .about-title {
            font-size: 2.75rem !important;
        }
        
        .about-video-section {
            height: 550px !important;
            margin: 0 auto;
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
        
        .about-title {
            font-size: 2rem !important;
        }
        
        .about-video-section {
            height: 400px !important;
            margin: 0 auto;
            width: 100%;
        }
        
        .about-stat-number {
            font-size: 3rem !important;
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
        
        .about-title {
            font-size: 1.75rem !important;
            margin-bottom: 1.5rem !important;
            text-align: center;
        }
        
        .about-video-section {
            height: 300px !important;
            border-radius: 12px !important;
            width: 100%;
            margin: 0 auto;
        }
        
        .about-stat-number {
            font-size: 2.5rem !important;
        }
        
        .about-feature-grid {
            margin: 0 auto;
        }
        
        .about-feature-grid .col-md-6 {
            width: 100% !important;
            margin-bottom: 1rem;
            padding: 0;
            margin-left: auto;
            margin-right: auto;
        }
        
        .about-statistics {
            margin: 0 auto;
        }
        
        .about-statistics .col-md-4 {
            width: 100% !important;
            margin-bottom: 1.5rem;
            padding: 0;
            margin-left: auto;
            margin-right: auto;
        }
        
        .about-memories-section {
            padding: 1rem 0;
            margin: 0 auto;
        }
        
        .about-memories-section .col-lg-6 {
            width: 100% !important;
            padding: 0;
            margin-left: auto;
            margin-right: auto;
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
        
        .about-section {
            margin-left: auto;
            margin-right: auto;
        }
        
        .about-title {
            font-size: 1.5rem !important;
            text-align: center;
        }
        
        .about-video-section {
            height: 250px !important;
            margin: 0 auto;
        }
        
        .about-stat-number {
            font-size: 2rem !important;
        }
        
        .about-feature-grid {
            margin: 0 auto;
        }
        
        .about-feature-grid .col-md-6 {
            padding: 1rem !important;
            margin-left: auto;
            margin-right: auto;
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
        
        .section-title {
            font-size: 1.7rem !important;
            margin-bottom: 1.75rem !important;
            padding: 0 0.5rem;
            text-align: center;
        }
        
        .about-title {
            font-size: 1.6rem !important;
            margin-bottom: 1.5rem !important;
            line-height: 1.3;
            text-align: center;
        }
        
        .about-video-section {
            height: 280px !important;
            border-radius: 12px !important;
            margin-bottom: 2rem !important;
            width: 100%;
            margin-left: auto;
            margin-right: auto;
        }
        
        .about-statistics {
            padding: 2rem 0;
            margin: 0 auto;
        }
        
        .about-stat-number {
            font-size: 2.25rem !important;
        }
        
        .about-statistics .col-md-4 {
            width: 100% !important;
            margin-bottom: 1.5rem;
            padding: 0;
            margin-left: auto;
            margin-right: auto;
        }
        
        .about-feature-grid {
            gap: 1rem !important;
            margin: 0 auto;
        }
        
        .about-feature-grid .col-md-6 {
            width: 100% !important;
            padding: 1.25rem !important;
            margin-bottom: 1rem;
            margin-left: auto;
            margin-right: auto;
        }
        
        .about-feature-grid .col-md-6 h4 {
            font-size: 1.2rem;
            margin-bottom: 0.75rem;
        }
        
        .about-feature-grid .col-md-6 p {
            font-size: 0.9rem;
            line-height: 1.7;
        }
        
        .about-memories-section {
            padding: 1.25rem 0;
            margin: 0 auto;
        }
        
        .about-memories-section .about-title {
            font-size: 1.6rem !important;
            margin-bottom: 1.5rem !important;
            text-align: center;
        }
        
        .about-memories-section p {
            font-size: 1rem;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        
        .about-memories-section .col-lg-6 {
            width: 100% !important;
            padding: 0;
            margin-left: auto;
            margin-right: auto;
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
        <h2 class="section-title" style="text-align: center;">About Us</h2>
        <div class="row align-items-center mb-5">
            <div class="col-lg-6">
                <h3 class="about-title" style="font-size: 2.5rem; font-weight: 800; color: var(--dark-text); margin-bottom: 2rem; line-height: 1.2;">
                    Where every drive feels extraordinary
                </h3>
            </div>
            <div class="col-lg-6">
                <div class="row g-4 about-feature-grid">
                    <div class="col-md-6">
                        <div style="padding: 2rem; background: var(--card); border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); height: 100%; border: 1px solid var(--border);">
                            <div style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--primary-purple), var(--primary-orange)); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem;">
                                <i class="bi bi-car-front" style="font-size: 1.8rem; color: white;"></i>
                            </div>
                            <h4 style="font-weight: 700; color: var(--dark-text); margin-bottom: 1rem; font-size: 1.3rem;">Variety Brands</h4>
                            <p style="color: var(--muted); line-height: 1.8; margin: 0;">Diam tincidunt tincidunt erat at semper fermentum. Id ultricies quis adipiscing velit semper morbi.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div style="padding: 2rem; background: var(--card); border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); height: 100%; border: 1px solid var(--border);">
                            <div style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--primary-purple), var(--primary-orange)); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem;">
                                <i class="bi bi-speedometer2" style="font-size: 1.8rem; color: white;"></i>
                            </div>
                            <h4 style="font-weight: 700; color: var(--dark-text); margin-bottom: 1rem; font-size: 1.3rem;">Maximum Freedom</h4>
                            <p style="color: var(--muted); line-height: 1.8; margin: 0;">Gravida auctor fermentum morbi vulputate ac egestas orcietium convallis pretium convallis.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div style="padding: 2rem; background: var(--card); border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); height: 100%; border: 1px solid var(--border);">
                            <div style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--primary-purple), var(--primary-orange)); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem;">
                                <i class="bi bi-headset" style="font-size: 1.8rem; color: white;"></i>
                            </div>
                            <h4 style="font-weight: 700; color: var(--dark-text); margin-bottom: 1rem; font-size: 1.3rem;">Reliable Support</h4>
                            <p style="color: var(--muted); line-height: 1.8; margin: 0;">Pretium convallis id diam sed commodo vestibulum lobortis volutpat adipiscing velit semper.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div style="padding: 2rem; background: var(--card); border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); height: 100%; border: 1px solid var(--border);">
                            <div style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--primary-purple), var(--primary-orange)); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem;">
                                <i class="bi bi-phone" style="font-size: 1.8rem; color: white;"></i>
                            </div>
                            <h4 style="font-weight: 700; color: var(--dark-text); margin-bottom: 1rem; font-size: 1.3rem;">Flexibility On The Go</h4>
                            <p style="color: var(--muted); line-height: 1.8; margin: 0;">Aliquam adipiscing velit semper morbi purus non eu cursus porttitor tristique et gravida.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Video Section -->
        <div class="mb-5 about-video-section" style="position: relative; border-radius: 20px; overflow: hidden; height: 500px; background: #000;">
            <video id="about-video" autoplay muted loop playsinline style="width: 100%; height: 100%; object-fit: cover;">
                <source src="vidio/vidio-marrakech.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <div id="video-overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(135deg, rgba(108, 92, 231, 0.3), rgba(255, 107, 53, 0.3)); display: flex; align-items: center; justify-content: center; transition: opacity 0.3s;">
                <div id="play-button" style="width: 100px; height: 100px; background: var(--primary-purple); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 30px rgba(108, 92, 231, 0.4); transition: transform 0.3s; cursor: pointer;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                    <i class="bi bi-play-fill" style="font-size: 2.5rem; color: white; margin-left: 5px;"></i>
                </div>
            </div>
        </div>
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const video = document.getElementById('about-video');
                const overlay = document.getElementById('video-overlay');
                const playButton = document.getElementById('play-button');
                
                if (playButton && video) {
                    playButton.addEventListener('click', function() {
                        if (video.paused) {
                            video.play();
                            overlay.style.opacity = '0';
                            setTimeout(() => overlay.style.display = 'none', 300);
                        } else {
                            video.pause();
                            overlay.style.display = 'flex';
                            overlay.style.opacity = '1';
                        }
                    });
                }
            });
        </script>

        <!-- Statistics Section -->
        <div class="row text-center mb-5 about-statistics" style="margin-left: auto; margin-right: auto;">
            <div class="col-md-4 mb-4">
                <div class="about-stat-number" style="font-size: 4rem; font-weight: 800; color: var(--primary-purple); margin-bottom: 0.5rem;">20k+</div>
                <div style="font-size: 1.2rem; color: var(--muted); font-weight: 600;">Happy customers</div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="about-stat-number" style="font-size: 4rem; font-weight: 800; color: var(--primary-purple); margin-bottom: 0.5rem;">540+</div>
                <div style="font-size: 1.2rem; color: var(--muted); font-weight: 600;">Cars in fleet</div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="about-stat-number" style="font-size: 4rem; font-weight: 800; color: var(--primary-purple); margin-bottom: 0.5rem;">25+</div>
                <div style="font-size: 1.2rem; color: var(--muted); font-weight: 600;">Years of experience</div>
            </div>
        </div>

        <!-- Unlock Memories Section -->
        <div class="row align-items-center about-memories-section">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h3 class="about-title" style="font-size: 2.5rem; font-weight: 800; color: var(--dark-text); margin-bottom: 2rem; line-height: 1.2;">
                    Unlock unforgettable memories on the road
                </h3>
                <p style="color: var(--muted); line-height: 1.8; margin-bottom: 2rem; font-size: 1.1rem;">
                    Aliquam adipiscing velit semper morbi. Purus non eu cursus porttitor tristique et gravida. Quis nunc interdum gravida ullamcorper.
                </p>
                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <div style="display: flex; align-items: start; gap: 1rem;">
                        <div style="width: 50px; height: 50px; background: linear-gradient(135deg, var(--primary-purple), var(--primary-orange)); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="bi bi-check-lg" style="font-size: 1.5rem; color: white;"></i>
                        </div>
                        <div>
                            <h5 style="font-weight: 700; color: var(--dark-text); margin-bottom: 0.5rem;">Wide range of vehicles</h5>
                            <p style="color: var(--muted); margin: 0; line-height: 1.6;">From economy to luxury, we have the perfect car for every journey and budget.</p>
                        </div>
                    </div>
                    <div style="display: flex; align-items: start; gap: 1rem;">
                        <div style="width: 50px; height: 50px; background: linear-gradient(135deg, var(--primary-purple), var(--primary-orange)); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="bi bi-calendar-check" style="font-size: 1.5rem; color: white;"></i>
                        </div>
                        <div>
                            <h5 style="font-weight: 700; color: var(--dark-text); margin-bottom: 0.5rem;">Easy booking process</h5>
                            <p style="color: var(--muted); margin: 0; line-height: 1.6;">Book your car in minutes with our simple and secure online booking system.</p>
                        </div>
                    </div>
                    <div style="display: flex; align-items: start; gap: 1rem;">
                        <div style="width: 50px; height: 50px; background: linear-gradient(135deg, var(--primary-purple), var(--primary-orange)); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="bi bi-clock" style="font-size: 1.5rem; color: white;"></i>
                        </div>
                        <div>
                            <h5 style="font-weight: 700; color: var(--dark-text); margin-bottom: 0.5rem;">Flexible rental options</h5>
                            <p style="color: var(--muted); margin: 0; line-height: 1.6;">Choose from daily, weekly, or monthly rentals with flexible pick-up and return times.</p>
                        </div>
                    </div>
                    <div style="display: flex; align-items: start; gap: 1rem;">
                        <div style="width: 50px; height: 50px; background: linear-gradient(135deg, var(--primary-purple), var(--primary-orange)); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="bi bi-shield-check" style="font-size: 1.5rem; color: white;"></i>
                        </div>
                        <div>
                            <h5 style="font-weight: 700; color: var(--dark-text); margin-bottom: 0.5rem;">24/7 customer support</h5>
                            <p style="color: var(--muted); margin: 0; line-height: 1.6;">Our dedicated support team is available around the clock to assist you.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

