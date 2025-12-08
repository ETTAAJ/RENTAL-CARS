<?php
require_once 'config.php';

$pageTitle = 'Contact Us - RENTAL CARS';
include 'header.php';

// WhatsApp number for contact
$whatsappNumber = WHATSAPP_NUMBER;
?>

<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4" style="--bs-breadcrumb-divider: ' / ';">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Contact Us</li>
        </ol>
    </nav>

    <!-- Contact Us Section -->
    <div class="py-5">
        <h2 class="section-title">Contact Us</h2>
        
        <!-- Contact Information Cards -->
        <div class="row mb-5">
            <div class="col-md-6 mb-4">
                <div style="padding: 2rem; background: var(--card); border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); height: 100%; border: 1px solid var(--border); text-align: center;">
                    <div style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--primary-purple), var(--primary-orange)); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                        <i class="bi bi-geo-alt-fill" style="font-size: 1.8rem; color: white;"></i>
                    </div>
                    <h4 style="font-weight: 700; color: var(--dark-text); margin-bottom: 1rem; font-size: 1.3rem;">Our Location</h4>
                    <p style="color: var(--muted); line-height: 1.8; margin: 0;">Oxford Ave. Cary, NC 27511</p>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div style="padding: 2rem; background: var(--card); border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); height: 100%; border: 1px solid var(--border); text-align: center;">
                    <div style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--primary-purple), var(--primary-orange)); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                        <i class="bi bi-envelope-fill" style="font-size: 1.8rem; color: white;"></i>
                    </div>
                    <h4 style="font-weight: 700; color: var(--dark-text); margin-bottom: 1rem; font-size: 1.3rem;">Email Address</h4>
                    <p style="color: var(--muted); line-height: 1.8; margin: 0;">info@carrental.com</p>
                </div>
            </div>
        </div>

        <!-- Contact Form and Map Section -->
        <div class="row">
            <!-- Contact Form -->
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div style="padding: 2.5rem; background: var(--card); border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border: 1px solid var(--border);">
                    <h3 style="font-size: 2rem; font-weight: 800; color: var(--dark-text); margin-bottom: 1.5rem;">Get In Touch</h3>
                    <p style="color: var(--muted); line-height: 1.8; margin-bottom: 2rem;">
                        Have a question or need assistance? Fill out the form below and we'll get back to you as soon as possible.
                    </p>
                    
                    <form id="contactForm" onsubmit="sendContactToWhatsApp(event);">
                        <div class="mb-3">
                            <label for="contact_name" class="form-label" style="font-weight: 600; color: var(--dark-text);">Full Name *</label>
                            <input type="text" class="form-control" id="contact_name" name="name" required 
                                   style="padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border); background: var(--card); color: var(--dark-text);">
                        </div>
                        
                        <div class="mb-3">
                            <label for="contact_email" class="form-label" style="font-weight: 600; color: var(--dark-text);">Email Address *</label>
                            <input type="email" class="form-control" id="contact_email" name="email" required 
                                   style="padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border); background: var(--card); color: var(--dark-text);">
                        </div>
                        
                        <div class="mb-3">
                            <label for="contact_phone" class="form-label" style="font-weight: 600; color: var(--dark-text);">Phone Number *</label>
                            <input type="tel" class="form-control" id="contact_phone" name="phone" required 
                                   style="padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border); background: var(--card); color: var(--dark-text);">
                        </div>
                        
                        <div class="mb-3">
                            <label for="contact_subject" class="form-label" style="font-weight: 600; color: var(--dark-text);">Subject *</label>
                            <select class="form-select" id="contact_subject" name="subject" required 
                                    style="padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border); background: var(--card); color: var(--dark-text);">
                                <option value="">Select a subject</option>
                                <option value="General Inquiry">General Inquiry</option>
                                <option value="Booking Question">Booking Question</option>
                                <option value="Technical Support">Technical Support</option>
                                <option value="Feedback">Feedback</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="contact_message" class="form-label" style="font-weight: 600; color: var(--dark-text);">Message *</label>
                            <textarea class="form-control" id="contact_message" name="message" rows="5" required 
                                      style="padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border); background: var(--card); color: var(--dark-text); resize: vertical;"></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-purple w-100" style="padding: 1rem; font-size: 1.1rem; font-weight: 600;">
                            <i class="bi bi-whatsapp"></i> Send via WhatsApp
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Map Section -->
            <div class="col-lg-6">
                <div style="padding: 2.5rem; background: var(--card); border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border: 1px solid var(--border); height: 100%;">
                    <h3 style="font-size: 2rem; font-weight: 800; color: var(--dark-text); margin-bottom: 1.5rem;">Find Us</h3>
                    <p style="color: var(--muted); line-height: 1.8; margin-bottom: 2rem;">
                        Visit our office or reach out to us through any of the contact methods above.
                    </p>
                    
                    <!-- Map Placeholder -->
                    <div style="width: 100%; height: 400px; background: var(--bg); border-radius: 16px; display: flex; align-items: center; justify-content: center; border: 1px solid var(--border); position: relative; overflow: hidden;">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3238.5!2d-78.8!3d35.7!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzXCsDQyJzAwLjAiTiA3OMKwNDgnMDAuMCJX!5e0!3m2!1sen!2sus!4v1234567890" 
                            width="100%" 
                            height="100%" 
                            style="border:0; border-radius: 16px;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                    
                    <!-- Additional Contact Info -->
                    <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border);">
                        <h5 style="font-weight: 700; color: var(--dark-text); margin-bottom: 1rem;">Business Hours</h5>
                        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                            <div style="display: flex; justify-content: space-between; color: var(--muted);">
                                <span>Monday - Friday</span>
                                <span style="font-weight: 600; color: var(--dark-text);">9:00 AM - 6:00 PM</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; color: var(--muted);">
                                <span>Saturday</span>
                                <span style="font-weight: 600; color: var(--dark-text);">10:00 AM - 4:00 PM</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; color: var(--muted);">
                                <span>Sunday</span>
                                <span style="font-weight: 600; color: var(--dark-text);">Closed</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Contact Section -->
        <div class="row mt-5">
            <div class="col-12">
                <div style="padding: 2.5rem; background: linear-gradient(135deg, var(--primary-purple), var(--primary-orange)); border-radius: 20px; text-align: center; color: white;">
                    <h3 style="font-size: 2rem; font-weight: 800; margin-bottom: 1rem; color: white;">Need Immediate Assistance?</h3>
                    <p style="font-size: 1.1rem; margin-bottom: 2rem; opacity: 0.95;">
                        Contact us directly via WhatsApp for instant support
                    </p>
                    <a href="https://wa.me/<?php echo $whatsappNumber; ?>" target="_blank" class="btn" 
                       style="background: white; color: var(--primary-purple); padding: 1rem 2.5rem; border-radius: 8px; font-weight: 700; font-size: 1.1rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; transition: transform 0.3s;"
                       onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.2)'"
                       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                        <i class="bi bi-whatsapp" style="font-size: 1.5rem;"></i>
                        Chat on WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Function to send contact form data to WhatsApp
function sendContactToWhatsApp(event) {
    event.preventDefault();
    
    const form = document.getElementById('contactForm');
    const name = document.getElementById('contact_name').value.trim();
    const email = document.getElementById('contact_email').value.trim();
    const phone = document.getElementById('contact_phone').value.trim();
    const subject = document.getElementById('contact_subject').value;
    const message = document.getElementById('contact_message').value.trim();
    
    // Validation
    if (!name || !email || !phone || !subject || !message) {
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
    const whatsappMessage = `*Contact Form Submission*

*Name:* ${name}
*Email:* ${email}
*Phone:* ${phone}
*Subject:* ${subject}

*Message:*
${message}

---
This message was sent from the RENTAL CARS website contact form.`;

    // Encode message for URL
    const encodedMessage = encodeURIComponent(whatsappMessage);
    
    // Create WhatsApp link
    const whatsappLink = `https://wa.me/<?php echo $whatsappNumber; ?>?text=${encodedMessage}`;
    
    // Open WhatsApp in new tab
    window.open(whatsappLink, '_blank');
    
    // Show success message
    alert('Thank you for contacting us! We will get back to you soon.');
    
    // Reset form
    form.reset();
    
    return false;
}
</script>

<?php include 'footer.php'; ?>

