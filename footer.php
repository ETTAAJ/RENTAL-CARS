        </main>
    </div>
    
    <?php
    // Get logo path if not already set
    if (!isset($logo_path)) {
        $logo_path = getLogoPath();
    }
    ?>
    
    <script>
        // Mobile Sidebar Toggle
        const mobileSidebar = document.getElementById('mobile-sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const openMobileBtn = document.getElementById('open-mobile-sidebar');
        const closeMobileBtn = document.getElementById('close-mobile-sidebar');
        
        if (openMobileBtn) {
            openMobileBtn.addEventListener('click', () => {
                mobileSidebar.classList.replace('closed', 'open');
                sidebarOverlay.classList.add('show');
            });
        }
        
        const closeMobileSidebar = () => {
            mobileSidebar.classList.replace('open', 'closed');
            sidebarOverlay.classList.remove('show');
        };
        
        if (closeMobileBtn) {
            closeMobileBtn.addEventListener('click', closeMobileSidebar);
        }
        
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', closeMobileSidebar);
        }
        
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            if (!sidebar) return;
            
            const toggles = document.querySelectorAll('.sidebar-toggle');
            
            sidebar.classList.toggle('show');
            
            // Toggle icons for all toggle buttons
            toggles.forEach(toggle => {
                const hamburgerIcon = toggle.querySelector('.icon-hamburger');
                const closeIcon = toggle.querySelector('.icon-close');
                
                if (sidebar.classList.contains('show')) {
                    hamburgerIcon.style.display = 'none';
                    closeIcon.style.display = 'block';
                } else {
                    hamburgerIcon.style.display = 'block';
                    closeIcon.style.display = 'none';
                }
            });
        }
        
        // Theme Toggle
        const html = document.documentElement;
        const mobileToggle = document.getElementById('theme-switch-mobile');
        const desktopToggle = document.getElementById('theme-switch-desktop');
        
        const applyTheme = (isDark) => {
            if (isDark) {
                html.classList.add('dark');
            } else {
                html.classList.remove('dark');
            }
            if (mobileToggle) mobileToggle.checked = isDark;
            if (desktopToggle) desktopToggle.checked = isDark;
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        };
        
        const saved = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const isDark = saved === 'dark' || (!saved && prefersDark);
        applyTheme(isDark);
        
        [mobileToggle, desktopToggle].forEach(toggle => {
            if (toggle) {
                toggle.addEventListener('change', () => applyTheme(toggle.checked));
            }
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggles = document.querySelectorAll('.sidebar-toggle');
            
            if (window.innerWidth <= 991) {
                const isClickInsideSidebar = sidebar.contains(event.target);
                const isClickOnToggle = Array.from(toggles).some(toggle => toggle.contains(event.target));
                
                if (!isClickInsideSidebar && !isClickOnToggle) {
                    sidebar.classList.remove('show');
                    toggles.forEach(toggle => {
                        const hamburgerIcon = toggle.querySelector('.icon-hamburger');
                        const closeIcon = toggle.querySelector('.icon-close');
                        hamburgerIcon.style.display = 'block';
                        closeIcon.style.display = 'none';
                    });
                }
            }
        });
        
        // Update icon on window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const toggles = document.querySelectorAll('.sidebar-toggle');
            
            if (window.innerWidth > 991) {
                sidebar.classList.remove('show');
                toggles.forEach(toggle => {
                    const hamburgerIcon = toggle.querySelector('.icon-hamburger');
                    const closeIcon = toggle.querySelector('.icon-close');
                    hamburgerIcon.style.display = 'block';
                    closeIcon.style.display = 'none';
                });
            }
        });
        
        // Currency Conversion
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
        
        function formatPrice(price) {
            const formatted = parseFloat(price).toFixed(2);
            return parseFloat(formatted).toString();
        }
        
        function convertPrice(priceMAD, toCurrency) {
            if (toCurrency === 'MAD' || !exchangeRates[toCurrency]) {
                return priceMAD;
            }
            return priceMAD * exchangeRates[toCurrency];
        }
        
        function updatePrices(currency) {
            // Update all price elements with data-price-mad attribute
            document.querySelectorAll('[data-price-mad]').forEach(element => {
                const priceMAD = parseFloat(element.getAttribute('data-price-mad'));
                if (isNaN(priceMAD)) return;
                
                const convertedPrice = convertPrice(priceMAD, currency);
                const formattedPrice = formatPrice(convertedPrice);
                const symbol = currencySymbols[currency] || 'MAD';
                
                // Check if element has separate currency span
                const currencySpan = element.querySelector('.price-currency');
                const amountSpan = element.querySelector('.price-amount');
                
                if (currencySpan && amountSpan) {
                    amountSpan.textContent = formattedPrice;
                    currencySpan.textContent = symbol;
                } else {
                    // Update text content directly
                    if (currency === 'MAD') {
                        element.textContent = formattedPrice + ' ' + symbol;
                    } else {
                        element.textContent = symbol + formattedPrice;
                    }
                }
            });
        }
        
        function setCurrency(currency) {
            localStorage.setItem('currency', currency);
            updatePrices(currency);
            
            // Update selectors
            const desktopSelector = document.getElementById('currency-selector-desktop');
            const mobileSelector = document.getElementById('currency-selector-mobile');
            if (desktopSelector) desktopSelector.value = currency;
            if (mobileSelector) mobileSelector.value = currency;
        }
        
        // Initialize currency
        const savedCurrency = localStorage.getItem('currency') || 'MAD';
        setCurrency(savedCurrency);
        
        // Add event listeners
        const desktopSelector = document.getElementById('currency-selector-desktop');
        const mobileSelector = document.getElementById('currency-selector-mobile');
        
        if (desktopSelector) {
            desktopSelector.addEventListener('change', function() {
                setCurrency(this.value);
            });
        }
        
        if (mobileSelector) {
            mobileSelector.addEventListener('change', function() {
                setCurrency(this.value);
            });
        }
    </script>
    
    <style>
        .site-footer {
            background-color: var(--bg-dark);
            color: var(--primary);
            padding: 3rem 0 1.5rem;
            margin-top: 4rem;
            border-top: 1px solid var(--border);
            transition: background-color 0.3s, color 0.3s, border-color 0.3s;
        }
        
        .footer-text {
            color: var(--muted);
            line-height: 1.8;
        }
        
        .footer-link {
            color: var(--muted);
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-link:hover {
            color: var(--primary-purple);
        }
        
        .footer-hr {
            border-color: var(--border);
            margin: 2rem 0 1rem;
        }
        
        .footer-copyright {
            color: var(--muted);
        }
        
        /* Floating WhatsApp Button */
        .floating-phone-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #25D366, #128C7E);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4);
            z-index: 1000;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            color: white;
            font-size: 1.8rem;
        }
        
        .floating-phone-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 25px rgba(37, 211, 102, 0.6);
            color: white;
        }
        
        .floating-phone-btn:active {
            transform: scale(0.95);
        }
        
        .floating-phone-btn .phone-pulse {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: rgba(37, 211, 102, 0.4);
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }
            100% {
                transform: scale(1.5);
                opacity: 0;
            }
        }
        
        .floating-phone-btn i {
            position: relative;
            z-index: 1;
        }
        
        @media (max-width: 768px) {
            .floating-phone-btn {
                bottom: 20px;
                right: 20px;
                width: 55px;
                height: 55px;
                font-size: 1.6rem;
            }
        }
        
        /* Hide on admin pages */
        body.admin-page .floating-phone-btn {
            display: none;
        }
    </style>
    
    <footer class="site-footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 style="font-weight: 800; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.75rem; letter-spacing: 0.5px; color: var(--primary);">
                        <img src="<?php echo htmlspecialchars($logo_path ?? 'assets/images/RENTAL-CARS.png'); ?>" alt="RENTAL CARS Logo" style="width: 35px; height: 35px; border-radius: 50%; object-fit: contain;" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <svg width="35" height="35" viewBox="0 0 45 45" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: none;">
                            <rect width="45" height="45" rx="10" fill="url(#footerLogoGradient)"/>
                            <path d="M22.5 12L28 18H26V28H19V18H17L22.5 12Z" fill="white"/>
                            <path d="M15 30H30V32H15V30Z" fill="white"/>
                            <defs>
                                <linearGradient id="footerLogoGradient" x1="0" y1="0" x2="45" y2="45" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#6C5CE7"/>
                                    <stop offset="1" stop-color="#FF6B35"/>
                                </linearGradient>
                            </defs>
                        </svg>
                        <span style="background: linear-gradient(135deg, #6C5CE7 0%, #FF6B35 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-size: 1.2rem; letter-spacing: 1px;">RENTAL CARS</span>
                    </h5>
                    <p class="footer-text">Your trusted partner for car rentals. We offer the best deals on quality vehicles with exceptional service.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 style="font-weight: 700; margin-bottom: 1rem; color: var(--primary);">Contact Info</h5>
                    <?php
                    require_once 'config.php';
                    $formatted_phone = getFormattedPhoneNumber();
                    ?>
                    <p class="footer-text" style="margin-bottom: 0.5rem;"><i class="bi bi-telephone-fill" style="color: var(--primary-orange); margin-right: 0.5rem;"></i> <?php echo htmlspecialchars($formatted_phone); ?></p>
                    <p class="footer-text" style="margin-bottom: 0.5rem;"><i class="bi bi-envelope-fill" style="color: var(--primary-orange); margin-right: 0.5rem;"></i> info@carrental.com</p>
                    <p class="footer-text"><i class="bi bi-geo-alt-fill" style="color: var(--primary-orange); margin-right: 0.5rem;"></i> Oxford Ave. Cary, NC 27511</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 style="font-weight: 700; margin-bottom: 1rem; color: var(--primary);">Follow Us</h5>
                    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                        <?php
                        require_once 'config.php';
                        $facebookUrl = getSocialMediaUrl('facebook');
                        $twitterUrl = getSocialMediaUrl('twitter');
                        $instagramUrl = getSocialMediaUrl('instagram');
                        $linkedinUrl = getSocialMediaUrl('linkedin');
                        $youtubeUrl = getSocialMediaUrl('youtube');
                        
                        if (!empty($facebookUrl)): ?>
                            <a href="<?php echo htmlspecialchars($facebookUrl); ?>" target="_blank" rel="noopener noreferrer" class="footer-link" title="Facebook">
                                <i class="bi bi-facebook" style="font-size: 1.5rem;"></i>
                            </a>
                        <?php endif; ?>
                        
                        <?php if (!empty($twitterUrl)): ?>
                            <a href="<?php echo htmlspecialchars($twitterUrl); ?>" target="_blank" rel="noopener noreferrer" class="footer-link" title="Twitter">
                                <i class="bi bi-twitter" style="font-size: 1.5rem;"></i>
                            </a>
                        <?php endif; ?>
                        
                        <?php if (!empty($instagramUrl)): ?>
                            <a href="<?php echo htmlspecialchars($instagramUrl); ?>" target="_blank" rel="noopener noreferrer" class="footer-link" title="Instagram">
                                <i class="bi bi-instagram" style="font-size: 1.5rem;"></i>
                            </a>
                        <?php endif; ?>
                        
                        <?php if (!empty($linkedinUrl)): ?>
                            <a href="<?php echo htmlspecialchars($linkedinUrl); ?>" target="_blank" rel="noopener noreferrer" class="footer-link" title="LinkedIn">
                                <i class="bi bi-linkedin" style="font-size: 1.5rem;"></i>
                            </a>
                        <?php endif; ?>
                        
                        <?php if (!empty($youtubeUrl)): ?>
                            <a href="<?php echo htmlspecialchars($youtubeUrl); ?>" target="_blank" rel="noopener noreferrer" class="footer-link" title="YouTube">
                                <i class="bi bi-youtube" style="font-size: 1.5rem;"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <hr class="footer-hr">
            <div class="text-center footer-copyright">
                <p style="margin: 0;">&copy; <?php echo date('Y'); ?> RENTAL CARS. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <?php
    // Check if we're in admin panel
    $isAdmin = (strpos($_SERVER['PHP_SELF'], '/admin/') !== false);
    
    if (!$isAdmin) {
        // Get WhatsApp number
        require_once 'config.php';
        $whatsapp_number = WHATSAPP_NUMBER;
        $wa_link = "https://wa.me/" . $whatsapp_number;
        $phone_link = "tel:+" . $whatsapp_number;
    ?>
    <!-- Floating WhatsApp Button -->
    <a href="<?php echo $wa_link; ?>" target="_blank" class="floating-phone-btn" title="Contact us on WhatsApp">
        <div class="phone-pulse"></div>
        <i class="bi bi-whatsapp"></i>
    </a>
    <?php } ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

