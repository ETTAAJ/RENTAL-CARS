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
            'USD': 1.0,
            'EUR': 0.92,
            'GBP': 0.79
        };
        
        // Use currency symbols from database
        const currencySymbols = window.CURRENCY_SYMBOLS || {
            'USD': '$',
            'EUR': '€',
            'GBP': '£'
        };
        
        // Get base currency (first currency with rate 1.0 or default)
        const baseCurrency = window.DEFAULT_CURRENCY || Object.keys(exchangeRates).find(code => exchangeRates[code] === 1.0) || 'USD';
        
        function formatPrice(price) {
            const formatted = parseFloat(price).toFixed(2);
            return parseFloat(formatted).toString();
        }
        
        function convertPrice(priceBase, toCurrency) {
            // If converting to base currency or currency not found, return original price
            if (toCurrency === baseCurrency || !exchangeRates[toCurrency]) {
                return priceBase;
            }
            // Convert from base currency to target currency
            // rate_to_base represents: 1 base currency = X target currency
            // So to convert: priceBase * exchangeRates[toCurrency]
            const targetRate = exchangeRates[toCurrency] || 1.0;
            return priceBase * targetRate;
        }
        
        function updatePrices(currency) {
            // Update all price elements with data-price-mad attribute (legacy) or data-price-base
            document.querySelectorAll('[data-price-mad], [data-price-base]').forEach(element => {
                // Support both legacy data-price-mad and new data-price-base
                const priceBase = parseFloat(element.getAttribute('data-price-base') || element.getAttribute('data-price-mad'));
                if (isNaN(priceBase)) return;
                
                const convertedPrice = convertPrice(priceBase, currency);
                const formattedPrice = formatPrice(convertedPrice);
                const symbol = currencySymbols[currency] || currency;
                
                // Check if element has separate currency span
                const currencySpan = element.querySelector('.price-currency');
                const amountSpan = element.querySelector('.price-amount');
                
                if (currencySpan && amountSpan) {
                    amountSpan.textContent = formattedPrice;
                    currencySpan.textContent = symbol;
                } else {
                    // Update text content directly
                    // Format: symbol + price for most currencies, price + symbol for base currency
                    if (currency === baseCurrency) {
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
        const savedCurrency = localStorage.getItem('currency') || baseCurrency;
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
            
            .site-footer {
                padding: 2rem 0 1rem;
            }
            
            .footer-container {
                padding: 0 0.75rem;
                max-width: 100%;
            }
            
            .site-footer .row {
                margin-left: 0;
                margin-right: 0;
            }
            
            .site-footer .col-md-4 {
                width: 100% !important;
                margin-bottom: 1.5rem;
                padding: 0;
            }
            
            .footer-text {
                font-size: 0.9rem;
            }
            
            .site-footer h5 {
                font-size: 1.1rem;
                margin-bottom: 0.75rem;
            }
            
            .footer-link {
                font-size: 1.3rem;
            }
        }
        
        @media (max-width: 576px) {
            .floating-phone-btn {
                bottom: 15px;
                right: 15px;
                width: 50px;
                height: 50px;
                font-size: 1.4rem;
            }
            
            .site-footer {
                padding: 1.5rem 0 1rem;
            }
            
            .footer-container {
                padding: 0 0.5rem;
            }
            
            .footer-text {
                font-size: 0.85rem;
                line-height: 1.6;
            }
            
            .site-footer h5 {
                font-size: 1rem;
                margin-bottom: 0.5rem;
            }
            
            .footer-link {
                font-size: 1.2rem;
            }
            
            .footer-copyright {
                font-size: 0.8rem;
            }
        }
        
        /* Specific optimization for 428px width (iPhone 12/13 Pro Max) */
        @media (max-width: 428px) {
            .site-footer {
                padding: 2rem 0 1rem;
            }
            
            .footer-container {
                padding: 0 0.75rem;
                max-width: 100%;
            }
            
            .site-footer .row {
                margin-bottom: 1.5rem;
                margin-left: 0;
                margin-right: 0;
            }
            
            .site-footer .col-md-4 {
                width: 100% !important;
                margin-bottom: 1.5rem;
                padding: 0;
            }
            
            .site-footer h5 {
                font-size: 1.05rem;
                margin-bottom: 0.75rem;
            }
            
            .footer-text {
                font-size: 0.88rem;
                line-height: 1.7;
            }
            
            .footer-link {
                font-size: 1.25rem;
            }
            
            .footer-copyright {
                font-size: 0.82rem;
                padding-top: 1rem;
            }
            
            .floating-phone-btn {
                bottom: 18px;
                right: 18px;
                width: 52px;
                height: 52px;
                font-size: 1.5rem;
            }
        }
        
        /* Hide on admin pages */
        body.admin-page .floating-phone-btn {
            display: none;
        }
        
        /* Desktop/PC Responsive Design for Footer - Centered */
        @media (min-width: 1200px) {
            .site-footer {
                padding: 4rem 0 2rem;
                display: flex;
                justify-content: center;
            }
            
            .footer-container {
                max-width: 1400px;
                margin-left: auto;
                margin-right: auto;
                padding: 0 2rem;
                width: 100%;
            }
            
            .site-footer .row {
                max-width: 100%;
                margin-left: auto;
                margin-right: auto;
            }
            
            .footer-text {
                font-size: 1rem;
                line-height: 1.9;
            }
            
            .footer-link {
                font-size: 1.1rem;
            }
        }
        
        @media (min-width: 992px) and (max-width: 1199px) {
            .footer-container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 1.5rem;
            }
        }
        
        @media (min-width: 768px) {
            .floating-phone-btn {
                width: 65px;
                height: 65px;
                font-size: 2rem;
                bottom: 40px;
                right: 40px;
            }
        }
    </style>
    
    <footer class="site-footer">
        <div class="container footer-container" style="margin-left: auto; margin-right: auto; width: 100%; max-width: 1400px;">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 style="font-weight: 800; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.75rem; letter-spacing: 0.5px; color: var(--primary);">
                        <img src="<?php echo htmlspecialchars($logo_path ?? getLogoPath()); ?>" alt="<?php echo htmlspecialchars(getSiteName()); ?> Logo" style="width: 35px; height: 35px; border-radius: 50%; object-fit: contain;" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
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
                        <span style="background: linear-gradient(135deg, #6C5CE7 0%, #FF6B35 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-size: 1.2rem; letter-spacing: 1px;"><?php echo htmlspecialchars(getSiteName()); ?></span>
                    </h5>
                    <p class="footer-text"><?php echo htmlspecialchars(getAppConfig('footer_description', 'Your trusted partner for car rentals. We offer the best deals on quality vehicles with exceptional service.')); ?></p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 style="font-weight: 700; margin-bottom: 1rem; color: var(--primary);">Contact Info</h5>
                    <?php
                    require_once 'config.php';
                    $formatted_phone = getFormattedPhoneNumber();
                    ?>
                    <p class="footer-text" style="margin-bottom: 0.5rem;"><i class="bi bi-telephone-fill" style="color: var(--primary-orange); margin-right: 0.5rem;"></i> <?php echo htmlspecialchars($formatted_phone); ?></p>
                    <p class="footer-text" style="margin-bottom: 0.5rem;"><i class="bi bi-envelope-fill" style="color: var(--primary-orange); margin-right: 0.5rem;"></i> <?php echo htmlspecialchars(getContactEmail()); ?></p>
                    <p class="footer-text"><i class="bi bi-geo-alt-fill" style="color: var(--primary-orange); margin-right: 0.5rem;"></i> <?php echo htmlspecialchars(getBusinessAddress()); ?></p>
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
                <p style="margin: 0;">&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars(getSiteName()); ?>. All rights reserved.</p>
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

