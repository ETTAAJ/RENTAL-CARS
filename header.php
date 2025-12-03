<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'RENTAL CARS - Best Deals on Car Rentals'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-purple: #6C5CE7;
            --primary-orange: #FF6B35;
            --dark-text: #2D3436;
            --light-bg: #F8F9FA;
            --white: #FFFFFF;
            --bg: var(--light-bg);
            --bg-dark: #ffffff;
            --card: var(--white);
            --border: #E0E0E0;
            --primary: var(--dark-text);
            --muted: #636E72;
            --hover-bg: rgba(108, 92, 231, 0.1);
        }
        
        .dark {
            --bg: #1a1a1a;
            --bg-dark: #2d2d2d;
            --card: #2d2d2d;
            --border: #404040;
            --primary: #ffffff;
            --muted: #a0a0a0;
            --light-bg: #1a1a1a;
            --dark-text: #ffffff;
            --white: #2d2d2d;
            --hover-bg: rgba(108, 92, 231, 0.2);
        }
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background-color: var(--bg);
            color: var(--primary);
            transition: background-color 0.3s, color 0.3s;
        }
        
        .navbar {
            background-color: var(--white) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 1rem 0;
        }
        
        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            color: var(--dark-text) !important;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            letter-spacing: 0.5px;
        }
        
        .navbar-brand:hover {
            color: var(--dark-text) !important;
        }
        
        .logo-img {
            height: 45px;
            width: 45px;
            flex-shrink: 0;
            object-fit: contain;
        }
        
        .navbar-brand-top img.logo-img {
            border-radius: 50%;
            object-fit: contain;
            background: transparent;
        }
        
        .logo-text {
            font-weight: 800;
            font-size: 1.5rem;
            letter-spacing: 1px;
        }
        
        .logo-text span:first-child {
            color: var(--primary-purple);
        }
        
        .logo-text span:last-child {
            color: var(--primary-orange);
        }
        
        .navbar-nav .nav-link {
            color: var(--dark-text) !important;
            font-weight: 500;
            margin: 0 0.5rem;
            transition: color 0.3s;
        }
        
        .navbar-nav .nav-link:hover {
            color: var(--primary-purple) !important;
        }
        
        .navbar-nav .nav-link.active {
            color: var(--primary-purple) !important;
        }
        
        .help-contact {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--dark-text);
            font-weight: 500;
        }
        
        .help-contact i {
            color: var(--primary-orange);
        }
        
        .btn-purple {
            background-color: var(--primary-purple);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .btn-purple:hover {
            background-color: #5a4dd4;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(108, 92, 231, 0.3);
        }
        
        .btn-orange {
            background-color: var(--primary-orange);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .btn-orange:hover {
            background-color: #e55a2b;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
        }
        
        .discount-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--primary-orange);
            color: white;
            padding: 8px 15px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.9rem;
            z-index: 10;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        
        .car-card {
            background: var(--white);
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s;
            height: 100%;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border: none;
        }
        
        .car-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .car-card.has-discount {
            border: 2px solid var(--primary-orange);
        }
        
        .car-image {
            height: 220px;
            object-fit: cover;
            width: 100%;
        }
        
        .car-card-body {
            padding: 1.5rem;
        }
        
        .car-title {
            font-weight: 700;
            font-size: 1.3rem;
            color: var(--dark-text);
            margin-bottom: 1rem;
        }
        
        .car-price {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-purple);
            margin-bottom: 1rem;
        }
        
        .price-original {
            text-decoration: line-through;
            color: var(--muted);
            font-size: 1rem;
            font-weight: 400;
            margin-right: 0.5rem;
        }
        
        .price-discounted {
            color: var(--primary-purple);
            font-weight: 700;
        }
        
        .car-features {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }
        
        .car-feature {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--muted);
            font-size: 0.9rem;
        }
        
        .car-feature i {
            color: var(--primary-purple);
            font-size: 1.1rem;
        }
        
        .hero-section-video {
            position: relative;
            width: 100%;
            height: 90vh;
            min-height: 600px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .hero-video {
            position: absolute;
            top: 50%;
            left: 50%;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            transform: translate(-50%, -50%);
            z-index: 1;
            object-fit: cover;
        }
        
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(108, 92, 231, 0.85) 0%, rgba(255, 107, 53, 0.75) 100%);
            z-index: 2;
        }
        
        .hero-content-wrapper {
            position: relative;
            z-index: 3;
            width: 100%;
            padding: 4rem 0;
        }
        
        .hero-content {
            color: white;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.2;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }
        
        .hero-subtitle {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            opacity: 0.95;
            text-shadow: 0 1px 5px rgba(0,0,0,0.2);
        }
        
        .hero-booking-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 15px 50px rgba(0,0,0,0.3);
            backdrop-filter: blur(10px);
        }
        
        @media (max-width: 991px) {
            .hero-section-video {
                height: 70vh;
                min-height: 500px;
            }
            
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
            
            .hero-content-wrapper {
                padding: 2rem 0;
            }
        }
        
        @media (max-width: 768px) {
            .hero-section-video {
                height: 60vh;
                min-height: 400px;
            }
            
            .hero-title {
                font-size: 2rem;
            }
            
            .hero-booking-card {
                padding: 1.5rem;
                margin-top: 2rem;
            }
        }
        
        .section-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--dark-text);
            margin-bottom: 3rem;
            text-align: center;
        }
        
        .mobile-sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: 280px;
            max-width: 85vw;
            background: var(--bg-dark);
            box-shadow: 2px 0 15px rgba(0,0,0,0.2);
            z-index: 2000;
            padding: 0;
            display: none;
            flex-direction: column;
            transition: transform 0.3s ease;
            border-right: 1px solid var(--border);
            overflow-y: auto;
        }
        
        .mobile-sidebar > div {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        
        .mobile-sidebar.closed {
            transform: translateX(-100%);
        }
        
        .mobile-sidebar.open {
            transform: translateX(0);
        }
        
        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1999;
            display: none;
            transition: opacity 0.3s ease;
        }
        
        .sidebar-overlay.show {
            display: block;
        }
        
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: 280px;
            background: var(--bg-dark);
            box-shadow: 2px 0 15px rgba(0,0,0,0.08);
            z-index: 1000;
            padding: 2rem 1.5rem;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
            border-right: 1px solid var(--border);
        }
        
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 3rem;
            text-decoration: none;
        }
        
        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
            flex: 1;
        }
        
        .sidebar-nav .nav-item {
            margin-bottom: 0.5rem;
        }
        
        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            padding: 1rem 1.25rem;
            color: var(--dark-text);
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s;
            font-weight: 500;
            gap: 0.75rem;
        }
        
        .sidebar-nav .nav-link i {
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }
        
        .sidebar-nav .nav-link:hover {
            background: var(--hover-bg);
            color: var(--primary-purple);
            transform: translateX(5px);
        }
        
        .sidebar-nav .nav-link.active {
            background: linear-gradient(135deg, var(--primary-purple), var(--primary-orange));
            color: white;
        }
        
        .sidebar-nav .nav-link.active i {
            color: white;
        }
        
        .sidebar-footer {
            margin-top: auto;
            padding-top: 2rem;
            border-top: 1px solid var(--border);
        }
        
        .help-contact {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--primary);
            font-weight: 500;
            padding: 1rem;
            background: var(--card);
            border-radius: 12px;
            border: 1px solid var(--border);
        }
        
        .help-contact i {
            color: var(--primary-orange);
            font-size: 1.3rem;
        }
        
        .top-navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1500;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            border-bottom: 1px solid var(--border);
            transition: background-color 0.3s, border-color 0.3s;
        }
        
        .dark .top-navbar {
            background: rgba(45, 45, 45, 0.95);
            box-shadow: 0 2px 15px rgba(0,0,0,0.3);
        }
        
        .main-content {
            padding-top: 80px;
        }
        
        .navbar-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 2rem;
            max-width: 1400px;
            margin: 0 auto;
            min-height: 70px;
        }
        
        @media (max-width: 991px) {
            .main-content {
                padding-top: 70px;
            }
            
            .navbar-content {
                padding: 0.75rem 1rem;
                min-height: 60px;
            }
        }
        
        .navbar-brand-top {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            transition: transform 0.3s;
        }
        
        .navbar-brand-top:hover {
            transform: scale(1.02);
        }
        
        .navbar-brand-top .logo-img {
            width: 45px;
            height: 45px;
            flex-shrink: 0;
        }
        
        .navbar-brand-top .logo-text {
            font-weight: 800;
            font-size: 1.5rem;
            letter-spacing: 1px;
            background: linear-gradient(135deg, var(--primary-purple) 0%, var(--primary-orange) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .navbar-nav-top {
            display: flex;
            align-items: center;
            gap: 2rem;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        
        .navbar-nav-top .nav-link-top {
            color: var(--dark-text);
            text-decoration: none;
            font-weight: 500;
            font-size: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s;
            position: relative;
        }
        
        .navbar-nav-top .nav-link-top:hover {
            color: var(--primary-purple);
            background: rgba(108, 92, 231, 0.1);
        }
        
        .navbar-nav-top .nav-link-top.active {
            color: var(--primary-purple);
            font-weight: 600;
        }
        
        .navbar-nav-top .nav-link-top.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 30px;
            height: 3px;
            background: linear-gradient(135deg, var(--primary-purple), var(--primary-orange));
            border-radius: 2px;
        }
        
        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
        }
        
        .main-content.no-sidebar {
            margin-left: 0;
        }
        
        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1001;
            background: var(--white);
            border: 1px solid #E0E0E0;
            border-radius: 8px;
            padding: 0.75rem;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        
        .sidebar-toggle:hover {
            background: #F8F9FA;
            transform: scale(1.05);
        }
        
        .sidebar-toggle i {
            font-size: 1.5rem;
            color: var(--dark-text);
            transition: transform 0.3s;
        }
        
        .sidebar-toggle .icon-hamburger {
            display: block;
        }
        
        .sidebar-toggle .icon-close {
            display: none;
        }
        
        @media (max-width: 991px) {
            .top-navbar {
                position: fixed !important;
            }
            
            .navbar-nav-top {
                display: none !important;
            }
            
            .navbar-actions {
                gap: 0.75rem;
            }
            
            .navbar-actions .whatsapp-link,
            .navbar-actions .help-contact {
                display: none !important;
            }
            
            .navbar-actions .theme-toggle {
                display: none !important;
            }
            
            .navbar-actions .currency-selector {
                display: none !important;
            }
            
            .navbar-actions #open-mobile-sidebar {
                display: flex !important;
                align-items: center;
                justify-content: center;
            }
            
            .mobile-sidebar {
                display: flex !important;
            }
            
            .sidebar {
                display: none !important;
            }
            
            .sidebar-toggle {
                display: none !important;
            }
            
            .main-content {
                margin-left: 0 !important;
                padding-top: 70px;
            }
        }
        
        @media (min-width: 992px) {
            .mobile-sidebar {
                display: none !important;
            }
            
            .sidebar-overlay {
                display: none !important;
            }
            
            .navbar-actions .whatsapp-link {
                display: flex !important;
            }
            
            .navbar-actions .theme-toggle {
                display: block !important;
            }
            
            .navbar-actions #open-mobile-sidebar {
                display: none !important;
            }
        }
        
        @media (max-width: 768px) {
            .navbar-brand-top .logo-text {
                font-size: 1.2rem;
            }
            
            .navbar-brand-top .logo-img {
                width: 35px;
                height: 35px;
            }
        }
        
        main {
            padding: 2rem 0;
        }
        
        .container {
            max-width: 1200px;
        }
        
        /* Theme Toggle */
        .theme-toggle {
            position: relative;
            width: 50px;
            height: 26px;
        }
        .theme-toggle input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .theme-toggle .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #94a3b8;
            transition: .4s;
            border-radius: 34px;
        }
        .theme-toggle .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        .theme-toggle input:checked + .slider {
            background: linear-gradient(135deg, var(--primary-purple), var(--primary-orange));
        }
        .theme-toggle input:checked + .slider:before {
            transform: translateX(24px);
        }
        .theme-toggle .sun, .theme-toggle .moon {
            position: absolute;
            top: 3px;
            width: 20px;
            height: 20px;
            transition: opacity .3s;
            pointer-events: none;
        }
        .theme-toggle .sun {
            left: 4px;
            opacity: 1;
        }
        .theme-toggle .moon {
            right: 4px;
            opacity: 0;
        }
        .theme-toggle input:checked ~ .sun {
            opacity: 0;
        }
        .theme-toggle input:checked ~ .moon {
            opacity: 1;
        }
        
        /* Logo Spin Animation */
        .logo-spin {
            transition: transform 0.6s ease-in-out;
        }
        .logo-spin:hover {
            transform: rotate(360deg);
        }
        
        /* WhatsApp Link */
        .whatsapp-link {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary-purple);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }
        .whatsapp-link:hover {
            color: var(--primary-orange);
        }
        .whatsapp-link i {
            font-size: 1.5rem;
        }
        
        /* Currency Selector */
        .currency-selector {
            position: relative;
        }
        
        .currency-selector select {
            background: var(--card);
            color: var(--dark-text);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 0.5rem 2rem 0.5rem 0.75rem;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            transition: all 0.3s;
            min-width: 80px;
        }
        
        .currency-selector select:hover {
            border-color: var(--primary-purple);
        }
        
        .currency-selector select:focus {
            outline: none;
            border-color: var(--primary-purple);
            box-shadow: 0 0 0 3px rgba(108, 92, 231, 0.1);
        }
        
        .currency-selector::after {
            content: '▼';
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: var(--primary-purple);
            font-size: 0.7rem;
        }
        
        .price-amount {
            display: inline;
        }
        
        .price-currency {
            display: inline;
            margin-left: 0.25rem;
        }
        
        /* Modal Dark Mode Support */
        .modal-content {
            background-color: var(--card);
            color: var(--primary);
            border-color: var(--border);
        }
        
        .modal-header {
            border-bottom-color: var(--border);
        }
        
        .modal-footer {
            border-top-color: var(--border);
        }
        
        .form-control, .form-select {
            background-color: var(--card);
            color: var(--dark-text);
            border-color: var(--border);
        }
        
        .form-control:focus, .form-select:focus {
            background-color: var(--card);
            color: var(--dark-text);
            border-color: var(--primary-purple);
            box-shadow: 0 0 0 0.2rem rgba(108, 92, 231, 0.25);
        }
        
        .form-label {
            color: var(--dark-text);
        }
        
        .alert {
            background-color: var(--card);
            border-color: var(--border);
            color: var(--primary);
        }
        
        .text-muted {
            color: var(--muted) !important;
        }
        
        /* Breadcrumb Styling */
        .breadcrumb {
            background-color: transparent;
            padding: 0;
            margin-bottom: 0;
        }
        
        .breadcrumb-item {
            color: var(--muted);
        }
        
        .breadcrumb-item.active {
            color: var(--muted);
        }
        
        .breadcrumb-item + .breadcrumb-item::before {
            color: var(--muted);
            content: var(--bs-breadcrumb-divider, "/");
        }
        
        .breadcrumb-item a {
            color: var(--primary-purple);
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .breadcrumb-item a:hover {
            color: var(--primary-orange);
        }
        
        /* Alert Styling */
        .alert {
            background-color: var(--card);
            border-color: var(--border);
            color: var(--primary);
        }
        
        .alert-info {
            background-color: var(--card);
            border-color: var(--border);
            color: var(--primary);
        }
        
        .alert-success {
            background-color: rgba(76, 175, 80, 0.1);
            border-color: rgba(76, 175, 80, 0.3);
            color: var(--primary);
        }
        
        .alert-danger {
            background-color: rgba(244, 67, 54, 0.1);
            border-color: rgba(244, 67, 54, 0.3);
            color: var(--primary);
        }
        
        /* Card Styling */
        .card {
            background-color: var(--card);
            border-color: var(--border);
            color: var(--primary);
        }
        
        .card-header {
            background-color: var(--card);
            border-bottom-color: var(--border);
            color: var(--primary);
        }
        
        .card-title, .card-body h5, .card-body h4, .card-body h3 {
            color: var(--dark-text);
        }
        
        .card-text {
            color: var(--primary);
        }
        
        /* Badge Styling */
        .badge {
            color: white;
        }
        
        /* Table Styling */
        .table {
            color: var(--primary);
        }
        
        .table-borderless td {
            color: var(--primary);
        }
    </style>
    <script>
        // Exchange rates from database (output by PHP)
        window.EXCHANGE_RATES = <?php echo json_encode(EXCHANGE_RATES); ?>;
    </script>
</head>
<body>
    <?php
    // WhatsApp configuration
    $whatsapp_number = WHATSAPP_NUMBER;
    $formatted_number = "+996 247-1680";
    $wa_link = "https://wa.me/" . $whatsapp_number;
    
    // Get logo path from database
    $logo_path = getLogoPath();
    ?>
    
    <!-- Mobile Sidebar -->
    <div id="mobile-sidebar" class="mobile-sidebar closed">
        <div style="padding: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <a href="index.php" style="display: flex; align-items: center; gap: 0.75rem; text-decoration: none;">
                    <img src="<?php echo htmlspecialchars($logo_path); ?>" alt="RENTAL CARS Logo" style="width: 40px; height: 40px; border-radius: 50%; object-fit: contain;" class="logo-spin" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <svg width="40" height="40" viewBox="0 0 45 45" fill="none" xmlns="http://www.w3.org/2000/svg" class="logo-spin" style="display: none;">
                        <rect width="45" height="45" rx="10" fill="url(#mobileLogoGradient)"/>
                        <path d="M22.5 12L28 18H26V28H19V18H17L22.5 12Z" fill="white"/>
                        <path d="M15 30H30V32H15V30Z" fill="white"/>
                        <defs>
                            <linearGradient id="mobileLogoGradient" x1="0" y1="0" x2="45" y2="45" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#6C5CE7"/>
                                <stop offset="1" stop-color="#FF6B35"/>
                            </linearGradient>
                        </defs>
                    </svg>
                    <span style="font-size: 1.2rem; font-weight: 800; letter-spacing: 1px;">
                        <span style="color: var(--primary-purple);">RENTAL</span> 
                        <span style="color: var(--primary-orange);">CARS</span>
                    </span>
                </a>
                <button id="close-mobile-sidebar" style="background: none; border: none; color: var(--primary); cursor: pointer; font-size: 1.5rem;">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            
            <nav style="display: flex; flex-direction: column; gap: 0.5rem; margin-bottom: 2rem;">
                <a href="index.php" class="nav-link" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 8px; text-decoration: none; color: var(--primary); transition: all 0.3s;" onmouseover="this.style.background='var(--hover-bg)'; this.style.color='var(--primary-purple)'" onmouseout="this.style.background='transparent'; this.style.color='var(--primary)'">
                    <i class="bi bi-house-door"></i> <span>Home</span>
                </a>
                <a href="index.php#vehicles" class="nav-link" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 8px; text-decoration: none; color: var(--primary); transition: all 0.3s;" onmouseover="this.style.background='var(--hover-bg)'; this.style.color='var(--primary-purple)'" onmouseout="this.style.background='transparent'; this.style.color='var(--primary)'">
                    <i class="bi bi-car-front"></i> <span>Vehicles</span>
                </a>
                <a href="about-us.php" class="nav-link" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 8px; text-decoration: none; color: var(--primary); transition: all 0.3s;" onmouseover="this.style.background='var(--hover-bg)'; this.style.color='var(--primary-purple)'" onmouseout="this.style.background='transparent'; this.style.color='var(--primary)'">
                    <i class="bi bi-info-circle"></i> <span>About Us</span>
                </a>
                <a href="#contact" class="nav-link" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 8px; text-decoration: none; color: var(--primary); transition: all 0.3s;" onmouseover="this.style.background='var(--hover-bg)'; this.style.color='var(--primary-purple)'" onmouseout="this.style.background='transparent'; this.style.color='var(--primary)'">
                    <i class="bi bi-envelope"></i> <span>Contact Us</span>
                </a>
            </nav>
            
            <div style="margin-top: auto; padding-top: 2rem; border-top: 1px solid var(--border);">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                    <span style="font-size: 0.9rem; color: var(--muted);">Currency</span>
                    <div class="currency-selector" style="min-width: 80px;">
                        <select id="currency-selector-mobile" aria-label="Select Currency" style="width: 100%;">
                            <option value="MAD">MAD</option>
                            <option value="EUR">EUR</option>
                            <option value="USD">USD</option>
                        </select>
                    </div>
                </div>
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                    <span style="font-size: 0.9rem; color: var(--muted);">Dark Mode</span>
                    <label class="theme-toggle">
                        <input type="checkbox" id="theme-switch-mobile">
                        <span class="slider">
                            <svg class="sun" fill="currentColor" viewBox="0 0 20 20" style="color: #fbbf24;"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 6.95a.5.5 0 01-.707 0l-1.414-1.414a.5.5 0 01.707-.707l1.414 1.414a.5.5 0 010 .707zM5.464 5.464a.5.5 0 01-.707 0L3.343 4.05a.5.5 0 11.707-.707l1.414 1.414a.5.5 0 010 .707zm9.072 9.072a.5.5 0 01-.707 0l-1.414-1.414a.5.5 0 11.707-.707l1.414 1.414a.5.5 0 010 .707zM5.464 14.536a.5.5 0 01-.707 0L3.343 13.122a.5.5 0 11.707-.707l1.414 1.414a.5.5 0 010 .707z"/></svg>
                            <svg class="moon" fill="currentColor" viewBox="0 0 20 20" style="color: #6b7280;"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8 0 1010.586 10.586z"/></svg>
                        </span>
                    </label>
                </div>
                <a href="<?php echo $wa_link; ?>" target="_blank" class="whatsapp-link">
                    <i class="bi bi-whatsapp"></i>
                    <span><?php echo $formatted_number; ?></span>
                </a>
            </div>
        </div>
    </div>
    
    <div id="sidebar-overlay" class="sidebar-overlay"></div>
    
    <!-- Top Navbar -->
    <nav class="top-navbar">
        <div class="navbar-content">
            <a href="index.php" class="navbar-brand-top">
                <img src="<?php echo htmlspecialchars($logo_path); ?>" alt="RENTAL CARS Logo" class="logo-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <svg width="45" height="45" viewBox="0 0 45 45" fill="none" xmlns="http://www.w3.org/2000/svg" class="logo-img" style="display: none;">
                    <rect width="45" height="45" rx="10" fill="url(#logoGradient)"/>
                    <path d="M22.5 12L28 18H26V28H19V18H17L22.5 12Z" fill="white"/>
                    <path d="M15 30H30V32H15V30Z" fill="white"/>
                    <defs>
                        <linearGradient id="logoGradient" x1="0" y1="0" x2="45" y2="45" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#6C5CE7"/>
                            <stop offset="1" stop-color="#FF6B35"/>
                        </linearGradient>
                    </defs>
                </svg>
                <span class="logo-text">
                    <span style="color: var(--primary-purple);">RENTAL</span> 
                    <span style="color: var(--primary-orange);">CARS</span>
                </span>
            </a>
            
            <ul class="navbar-nav-top">
                <li>
                    <a href="index.php" class="nav-link-top <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">
                        Home
                    </a>
                </li>
                <li>
                    <a href="index.php#vehicles" class="nav-link-top">
                        Vehicles
                    </a>
                </li>
                <li>
                    <a href="about-us.php" class="nav-link-top <?php echo (basename($_SERVER['PHP_SELF']) == 'about-us.php') ? 'active' : ''; ?>">
                        About Us
                    </a>
                </li>
                <li>
                    <a href="#contact" class="nav-link-top">
                        Contact Us
                    </a>
                </li>
            </ul>
            
            <div class="navbar-actions">
                <a href="<?php echo $wa_link; ?>" target="_blank" class="whatsapp-link" style="display: none;">
                    <i class="bi bi-whatsapp"></i>
                    <span><?php echo $formatted_number; ?></span>
                </a>
                <div class="help-contact" style="display: none;">
                    <i class="bi bi-telephone-fill"></i>
                    <div>
                        <div style="font-size: 0.85rem; color: var(--muted);">Need help?</div>
                        <div style="font-weight: 600;"><?php echo $formatted_number; ?></div>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div class="currency-selector">
                        <select id="currency-selector-desktop" aria-label="Select Currency">
                            <option value="MAD">MAD</option>
                            <option value="EUR">EUR</option>
                            <option value="USD">USD</option>
                        </select>
                    </div>
                    <span style="font-size: 0.9rem; color: var(--muted); display: none;">Dark Mode</span>
                    <label class="theme-toggle">
                        <input type="checkbox" id="theme-switch-desktop">
                        <span class="slider">
                            <svg class="sun" fill="currentColor" viewBox="0 0 20 20" style="color: #fbbf24;"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 6.95a.5.5 0 01-.707 0l-1.414-1.414a.5.5 0 01.707-.707l1.414 1.414a.5.5 0 010 .707zM5.464 5.464a.5.5 0 01-.707 0L3.343 4.05a.5.5 0 11.707-.707l1.414 1.414a.5.5 0 010 .707zm9.072 9.072a.5.5 0 01-.707 0l-1.414-1.414a.5.5 0 11.707-.707l1.414 1.414a.5.5 0 010 .707zM5.464 14.536a.5.5 0 01-.707 0L3.343 13.122a.5.5 0 11.707-.707l1.414 1.414a.5.5 0 010 .707z"/></svg>
                            <svg class="moon" fill="currentColor" viewBox="0 0 20 20" style="color: #6b7280;"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8 0 1010.586 10.586z"/></svg>
                        </span>
                    </label>
                </div>
                <button id="open-mobile-sidebar" style="display: none; background: transparent; border: none; border-radius: 8px; padding: 0.5rem; cursor: pointer; color: var(--primary); transition: all 0.3s;" onmouseover="this.style.color='var(--primary-purple)'" onmouseout="this.style.color='var(--primary)'">
                    <i class="bi bi-list" style="font-size: 1.8rem;"></i>
                </button>
            </div>
        </div>
    </nav>
    
    <?php 
    // Check if we're in admin panel - if so, don't show sidebar
    $isAdmin = (strpos($_SERVER['PHP_SELF'], '/admin/') !== false);
    $showSidebar = false; // Set to false to hide sidebar from main pages
    if (!$isAdmin && $showSidebar): 
    ?>
    <!-- Sidebar Toggle Button (Mobile) -->
    <button class="sidebar-toggle" id="sidebarToggle" onclick="toggleSidebar()">
        <i class="bi bi-list icon-hamburger"></i>
        <i class="bi bi-x-lg icon-close"></i>
    </button>
    
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <a href="index.php" class="sidebar-brand">
            <img src="assets/images/RENTAL-CARS.png" alt="RENTAL CARS Logo" class="logo-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
            <svg width="45" height="45" viewBox="0 0 45 45" fill="none" xmlns="http://www.w3.org/2000/svg" class="logo-img" style="display: none;">
                <rect width="45" height="45" rx="10" fill="url(#logoGradient)"/>
                <path d="M22.5 12L28 18H26V28H19V18H17L22.5 12Z" fill="white"/>
                <path d="M15 30H30V32H15V30Z" fill="white"/>
                <defs>
                    <linearGradient id="logoGradient" x1="0" y1="0" x2="45" y2="45" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#6C5CE7"/>
                        <stop offset="1" stop-color="#FF6B35"/>
                    </linearGradient>
                </defs>
            </svg>
            <span class="logo-text">
                <span style="color: var(--primary-purple);">RENTAL</span> 
                <span style="color: var(--primary-orange);">CARS</span>
            </span>
        </a>
        
        <ul class="sidebar-nav">
            <li class="nav-item">
                <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>" href="index.php">
                    <i class="bi bi-house-door"></i>
                    <span>Home</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php#vehicles">
                    <i class="bi bi-car-front"></i>
                    <span>Vehicles</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'about-us.php') ? 'active' : ''; ?>" href="about-us.php">
                    <i class="bi bi-info-circle"></i>
                    <span>About Us</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#contact">
                    <i class="bi bi-envelope"></i>
                    <span>Contact Us</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admin/index.php">
                    <i class="bi bi-gear"></i>
                    <span>Admin Panel</span>
                </a>
            </li>
        </ul>
        
        <div class="sidebar-footer">
            <div class="help-contact">
                <i class="bi bi-telephone-fill"></i>
                <div>
                    <div style="font-size: 0.85rem; color: #636E72;">Need help?</div>
                    <div style="font-weight: 600; font-size: 0.95rem;">+996 247-1680</div>
                </div>
            </div>
        </div>
    </aside>
    <?php endif; ?>
    
    <!-- Main Content -->
    <div class="main-content <?php echo $isAdmin ? 'no-sidebar' : ''; ?>">
        <main>


