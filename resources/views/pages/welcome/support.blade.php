<!-- Support Section -->
<section id="support" class="support-section">
    <div class="support-container">
        
        <div class="section-title text-center support-header">
            <span class="sub-heading">Smart Support Channels</span>
            <h3>Always Connected</h3>
            <p>Our automated systems and expert team are available 24/7. Use our <strong>Smart AI Assistant</strong> in the hero section for instant help, or reach out below.</p>
        </div>

        <div class="support-content-wrapper">
            <!-- Support Info -->
            <div class="support-info-card">
                <div class="support-info-bg-blob"></div>
                <h3>Contact Information</h3>
                <p class="support-info-desc">Fill out the form and our team will get back to you within 24 hours.</p>

                <div class="support-contact-list">
                    <div class="contact-item">
                        <div class="contact-icon"><i class="fas fa-phone-alt"></i></div>
                        <div class="contact-text">
                            <span>Call Us</span>
                            <a href="tel:08064333983">08064333983</a>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon"><i class="fab fa-whatsapp"></i></div>
                        <div class="contact-text">
                            <span>WhatsApp Support</span>
                            <a href="https://wa.me/2348064333983" target="_blank">Chat Now</a>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div class="contact-text">
                            <span>Visit Us</span>
                            <address>Babantude Adelke Street<br>Apapa, Lagos</address>
                        </div>
                    </div>
                </div>

                <div class="social-links-support">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>

            <!-- Support Form -->
            <div class="support-form-wrapper">
                <h3>Send Us a Message</h3>

                @if(session('success'))
                    <div class="alert-box alert-success">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert-box alert-error">
                        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    </div>
                @endif

                <form id="contact-support-form" action="{{ route('support.send') }}" method="POST">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label>Your Name</label>
                            <input type="text" name="name" class="form-control" placeholder="John Doe" required>
                        </div>
                        <div class="form-group">
                            <label>Your Email</label>
                            <input type="email" name="email" class="form-control" placeholder="john@example.com" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Subject</label>
                        <input type="text" name="subject" class="form-control" placeholder="How can we help?" required>
                    </div>
                    <div class="form-group">
                        <label>Message</label>
                        <textarea name="message" rows="4" class="form-control" placeholder="Describe your issue in detail..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary support-submit-btn">
                        <span>Send Message</span>
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
<!-- End Support Section -->
