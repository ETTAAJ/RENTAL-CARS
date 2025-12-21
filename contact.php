<?php
require_once 'config.php';

$pageTitle = 'Contact Us - ' . getSiteName();
include 'header.php';

// WhatsApp number for contact
$whatsappNumber = WHATSAPP_NUMBER;
$formattedPhone = getFormattedPhoneNumber();
?>

<div class="contact-page">
    <div class="container" style="margin-left: auto; margin-right: auto; width: 100%; max-width: 1400px;">
        <!-- Page Header -->
        <div class="contact-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Contact</li>
                </ol>
            </nav>
        </div>

        <!-- Contact Form Section - Mobile First -->
        <div class="contact-form-card">
            <div class="form-header">
                <h2>Send Us a Message</h2>
                <div class="header-accent"></div>
                <p>Fill out the form and we'll respond within minutes</p>
            </div>
            
            <form id="contactForm" onsubmit="sendContactToWhatsApp(event);">
                <div class="form-group">
                    <label for="contact_name">Your Name</label>
                    <input type="text" class="form-input" id="contact_name" name="name" placeholder="Enter your full name" required>
                </div>
                
                <div class="form-group">
                    <label for="contact_email">Your Email</label>
                    <input type="email" class="form-input" id="contact_email" name="email" placeholder="Enter your email address" required>
                </div>
                
                <div class="form-group">
                    <label for="contact_message">Your Message</label>
                    <textarea class="form-input form-textarea" id="contact_message" name="message" placeholder="How can we help you?" rows="5" required></textarea>
                </div>
                
                <button type="submit" class="submit-btn">
                    <span>Send Message</span>
                    <i class="bi bi-arrow-right"></i>
                </button>
            </form>
        </div>

        <!-- Contact Info Cards -->
        <div class="contact-info-grid">
            <!-- Phone & WhatsApp -->
            <a href="https://wa.me/<?php echo $whatsappNumber; ?>" target="_blank" class="info-card">
                <div class="info-icon">
                    <i class="bi bi-telephone-fill"></i>
                </div>
                <div class="info-content">
                    <h4>Phone & WhatsApp (24/7)</h4>
                    <p class="info-highlight"><?php echo $formattedPhone; ?></p>
                </div>
            </a>

            <!-- Email -->
            <a href="mailto:<?php echo htmlspecialchars(getContactEmail()); ?>" class="info-card">
                <div class="info-icon">
                    <i class="bi bi-envelope-fill"></i>
                </div>
                <div class="info-content">
                    <h4>Email</h4>
                    <p class="info-highlight"><?php echo htmlspecialchars(getContactEmail()); ?></p>
                </div>
            </a>

            <!-- Operating Hours -->
            <div class="info-card">
                <div class="info-icon">
                    <i class="bi bi-clock-fill"></i>
                </div>
                <div class="info-content">
                    <h4>Operating Hours</h4>
                    <p class="info-highlight">24/7 - Always Available</p>
                </div>
            </div>
        </div>

        <!-- Follow Us Section -->
        <div class="social-section">
            <h3>Follow Us</h3>
            <div class="social-icons">
                <a href="#" class="social-icon instagram" aria-label="Instagram">
                    <i class="bi bi-instagram"></i>
                </a>
                <a href="#" class="social-icon facebook" aria-label="Facebook">
                    <i class="bi bi-facebook"></i>
                </a>
                <a href="https://wa.me/<?php echo $whatsappNumber; ?>" target="_blank" class="social-icon whatsapp" aria-label="WhatsApp">
                    <i class="bi bi-whatsapp"></i>
                </a>
            </div>
        </div>

        <!-- Map Section -->
        <div class="map-section">
            <h3>Find Us</h3>
            <div class="map-wrapper">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3238.5!2d-78.8!3d35.7!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzXCsDQyJzAwLjAiTiA3OMKwNDgnMDAuMCJX!5e0!3m2!1sen!2sus!4v1234567890" 
                    width="100%" 
                    height="100%" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>
</div>

<style>
/* Contact Page - Modern Mobile-First Design */
.contact-page {
    padding: 1rem 0 3rem;
    min-height: calc(100vh - 80px);
}

.contact-page .container {
    max-width: 600px;
    margin: 0 auto;
    padding: 0 1rem;
}

/* Page Header */
.contact-header {
    margin-bottom: 1.5rem;
}

.contact-header .breadcrumb {
    background: transparent;
    padding: 0;
    margin: 0;
    font-size: 0.875rem;
}

.contact-header .breadcrumb-item a {
    color: var(--primary-purple);
    text-decoration: none;
}

.contact-header .breadcrumb-item.active {
    color: var(--muted);
}

/* Contact Form Card */
.contact-form-card {
    background: var(--card);
    border-radius: 20px;
    padding: 1.75rem;
    margin-bottom: 1.25rem;
    border: 1px solid var(--border);
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

.form-header {
    margin-bottom: 1.75rem;
}

.form-header h2 {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--dark-text);
    margin-bottom: 0.5rem;
    line-height: 1.2;
}

.header-accent {
    width: 50px;
    height: 4px;
    background: linear-gradient(135deg, var(--primary-purple), var(--primary-orange));
    border-radius: 2px;
    margin-bottom: 0.75rem;
}

.form-header p {
    color: var(--muted);
    font-size: 0.95rem;
    margin: 0;
}

/* Form Groups */
.form-group {
    margin-bottom: 1.25rem;
}

.form-group label {
    display: block;
    font-weight: 600;
    color: var(--primary-orange);
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.form-input {
    width: 100%;
    padding: 1rem;
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: 12px;
    color: var(--dark-text);
    font-size: 16px; /* Prevents iOS zoom */
    transition: all 0.3s ease;
    -webkit-appearance: none;
    appearance: none;
}

.form-input::placeholder {
    color: var(--muted);
    opacity: 0.7;
}

.form-input:focus {
    outline: none;
    border-color: var(--primary-purple);
    box-shadow: 0 0 0 3px rgba(108, 92, 231, 0.15);
}

.form-textarea {
    resize: vertical;
    min-height: 120px;
}

/* Submit Button */
.submit-btn {
    width: 100%;
    padding: 1rem 1.5rem;
    background: linear-gradient(135deg, var(--primary-orange), #e55a2b);
    border: none;
    border-radius: 12px;
    color: white;
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    transition: all 0.3s ease;
    margin-top: 0.5rem;
}

.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(255, 107, 53, 0.35);
}

.submit-btn:active {
    transform: translateY(0);
}

.submit-btn i {
    font-size: 1.1rem;
    transition: transform 0.3s ease;
}

.submit-btn:hover i {
    transform: translateX(4px);
}

/* Contact Info Grid */
.contact-info-grid {
    display: flex;
    flex-direction: column;
    gap: 0.875rem;
    margin-bottom: 1.5rem;
}

/* Info Card */
.info-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 1.25rem;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.info-card:hover {
    border-color: var(--primary-orange);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
}

.info-icon {
    width: 50px;
    height: 50px;
    min-width: 50px;
    background: var(--bg);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.info-icon i {
    font-size: 1.4rem;
    color: var(--primary-orange);
}

.info-content {
    flex: 1;
}

.info-content h4 {
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--dark-text);
    margin: 0 0 0.25rem;
}

.info-highlight {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--primary-orange);
    margin: 0;
}

/* Social Section */
.social-section {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 1.5rem;
    text-align: center;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.social-section h3 {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--dark-text);
    margin-bottom: 1rem;
}

.social-icons {
    display: flex;
    justify-content: center;
    gap: 1rem;
}

.social-icon {
    width: 55px;
    height: 55px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.3s ease;
}

.social-icon i {
    font-size: 1.5rem;
    color: white;
}

.social-icon.instagram {
    background: linear-gradient(135deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);
}

.social-icon.facebook {
    background: #1877f2;
}

.social-icon.whatsapp {
    background: #25d366;
}

.social-icon:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
}

/* Map Section */
.map-section {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.map-section h3 {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--dark-text);
    margin-bottom: 1rem;
}

.map-wrapper {
    width: 100%;
    height: 200px;
    border-radius: 12px;
    overflow: hidden;
    background: var(--bg);
}

.map-wrapper iframe {
    width: 100%;
    height: 100%;
}

/* Tablet Styles */
@media (min-width: 768px) {
    .contact-page .container {
        max-width: 700px;
        margin: 0 auto;
        padding: 0 2rem;
    }
    
    .contact-form-card {
        padding: 2.5rem;
    }
    
    .form-header h2 {
        font-size: 1.75rem;
    }
    
    .contact-info-grid {
        gap: 1rem;
    }
    
    .info-card {
        padding: 1.5rem;
    }
    
    .map-wrapper {
        height: 250px;
    }
}

/* Desktop Styles */
@media (min-width: 992px) {
    .contact-page {
        padding: 2rem 0 4rem;
    }
    
    .contact-page .container {
        max-width: 1100px;
        margin: 0 auto;
    }
}

/* Mobile Responsive Design */
@media (max-width: 768px) {
    .contact-page {
        padding: 1rem 0 2rem;
    }
    
    .contact-page .container {
        padding: 0 0.75rem;
        max-width: 100%;
    }
    
    .contact-header {
        margin-bottom: 1.5rem;
    }
    
    .contact-form-card {
        padding: 1.5rem !important;
        border-radius: 12px;
        width: 100%;
    }
    
    .form-header h2 {
        font-size: 1.5rem !important;
    }
    
    .form-header p {
        font-size: 0.9rem;
    }
    
    .form-input {
        padding: 0.75rem;
        font-size: 16px; /* Prevents zoom on iOS */
        width: 100%;
    }
    
    .form-textarea {
        min-height: 120px;
        width: 100%;
    }
    
    .submit-btn {
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
        width: 100%;
    }
    
    .contact-info-grid {
        display: flex !important;
        flex-direction: column !important;
        gap: 1rem;
    }
    
    .info-card {
        width: 100% !important;
        padding: 1.25rem;
    }
    
    .info-icon {
        width: 50px;
        height: 50px;
    }
    
    .info-icon i {
        font-size: 1.5rem;
    }
    
    .social-section {
        padding: 1.5rem 0;
        width: 100%;
    }
    
    .social-section h3 {
        font-size: 1.25rem;
    }
    
    .social-icons {
        gap: 1rem;
    }
    
    .social-icon {
        width: 45px;
        height: 45px;
        font-size: 1.2rem;
    }
    
    .map-section {
        width: 100%;
    }
    
    .map-wrapper {
        width: 100%;
    }
}

@media (max-width: 576px) {
    .contact-page .container {
        padding: 0 0.5rem;
    }
    
    .contact-form-card {
        padding: 1rem !important;
    }
    
    .form-header h2 {
        font-size: 1.25rem !important;
    }
    
    .form-input {
        padding: 0.65rem;
        font-size: 16px;
    }
    
    .info-card {
        padding: 1rem;
    }
    
    .info-card h4 {
        font-size: 1rem;
    }
    
    .info-highlight {
        font-size: 0.9rem;
    }
    
    .social-icon {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
}

/* Specific optimization for 428px width (iPhone 12/13 Pro Max) */
@media (max-width: 428px) {
    .contact-page {
        padding: 1.25rem 0 2rem;
    }
    
    .contact-page .container {
        padding: 0 0.75rem;
        max-width: 100%;
    }
    
    .contact-header {
        margin-bottom: 1.25rem;
    }
    
    .contact-form-card {
        padding: 1.25rem !important;
        border-radius: 12px;
        width: 100%;
    }
    
    .form-header h2 {
        font-size: 1.4rem !important;
        margin-bottom: 0.5rem;
    }
    
    .form-header p {
        font-size: 0.9rem;
    }
    
    .form-group {
        margin-bottom: 1.25rem;
    }
    
    .form-label {
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    
    .form-input {
        padding: 0.75rem;
        font-size: 16px;
        border-radius: 8px;
        width: 100%;
    }
    
    .form-textarea {
        min-height: 130px;
        font-size: 16px;
        width: 100%;
    }
    
    .submit-btn {
        padding: 0.85rem 1.5rem;
        font-size: 1rem;
        min-height: 48px;
        width: 100%;
    }
    
    .contact-info-grid {
        display: flex !important;
        flex-direction: column !important;
        gap: 0.75rem;
        margin-top: 1.5rem;
    }
    
    .info-card {
        width: 100% !important;
        padding: 1.25rem;
        border-radius: 12px;
    }
    
    .info-icon {
        width: 55px;
        height: 55px;
        margin-bottom: 1rem;
    }
    
    .info-icon i {
        font-size: 1.6rem;
    }
    
    .info-card h4 {
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }
    
    .info-highlight {
        font-size: 0.95rem;
    }
    
    .social-section {
        padding: 1.75rem 0;
        width: 100%;
    }
    
    .social-section h3 {
        font-size: 1.3rem;
        margin-bottom: 1rem;
    }
    
    .social-icons {
        gap: 0.75rem;
        justify-content: center;
    }
    
    .social-icon {
        width: 42px;
        height: 42px;
        font-size: 1.1rem;
    }
    
    .map-section {
        width: 100%;
    }
    
    .map-wrapper {
        width: 100%;
    }
}
    
    .contact-form-card {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        padding: 3rem;
    }
    
    .form-header {
        margin-bottom: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .form-header h2 {
        font-size: 2.25rem;
    }
    
    .form-header p {
        font-size: 1.05rem;
    }
    
    .contact-info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
    }
    
    .info-card {
        flex-direction: column;
        text-align: center;
        padding: 2rem 1.5rem;
    }
    
    .info-icon {
        margin: 0 auto 1rem;
        width: 60px;
        height: 60px;
    }
    
    .info-icon i {
        font-size: 1.6rem;
    }
    
    .social-section {
        padding: 2rem;
    }
    
    .social-icons {
        gap: 1.25rem;
    }
    
    .social-icon {
        width: 65px;
        height: 65px;
    }
    
    .social-icon i {
        font-size: 1.75rem;
    }
    
    .map-section {
        padding: 2rem;
    }
    
    .map-wrapper {
        height: 350px;
    }
}

/* Large Desktop */
@media (min-width: 1200px) {
    .contact-form-card {
        gap: 4rem;
        padding: 3.5rem;
    }
    
    .form-header h2 {
        font-size: 2.5rem;
    }
}

/* Dark Mode Adjustments */
.dark .contact-form-card,
.dark .info-card,
.dark .social-section,
.dark .map-section {
    background: var(--card);
    border-color: var(--border);
}

.dark .form-input {
    background: rgba(255,255,255,0.05);
    border-color: var(--border);
    color: var(--dark-text);
}

.dark .form-input::placeholder {
    color: var(--muted);
}

.dark .info-icon {
    background: rgba(255,255,255,0.05);
}
</style>

<script>
// Site name from PHP configuration
const siteName = <?php echo json_encode(getSiteName()); ?>;

// Function to send contact form data to WhatsApp
function sendContactToWhatsApp(event) {
    event.preventDefault();
    
    const form = document.getElementById('contactForm');
    const name = document.getElementById('contact_name').value.trim();
    const email = document.getElementById('contact_email').value.trim();
    const message = document.getElementById('contact_message').value.trim();
    
    // Validation
    if (!name || !email || !message) {
        alert('Please fill in all fields.');
        return false;
    }
    
    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert('Please enter a valid email address.');
        return false;
    }
    
    // Create WhatsApp message
    const whatsappMessage = `*New Contact Message*

*Name:* ${name}
*Email:* ${email}

*Message:*
${message}

---
Sent from ${siteName} website`;

    // Encode message for URL
    const encodedMessage = encodeURIComponent(whatsappMessage);
    
    // Create WhatsApp link
    const whatsappLink = `https://wa.me/<?php echo $whatsappNumber; ?>?text=${encodedMessage}`;
    
    // Open WhatsApp in new tab
    window.open(whatsappLink, '_blank');
    
    // Show success message
    alert('Thank you! Your message will be sent via WhatsApp.');
    
    // Reset form
    form.reset();
    
    return false;
}
</script>

<?php include 'footer.php'; ?>

