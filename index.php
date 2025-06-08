<?php
// index.php
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FLEXCEE Tech - Web Development & IT Training</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
 <!-- SOCIAL MEDIA PIXEL -->
    <!-- 📊 Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXX"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-XXXXXXX');
</script>

<!-- 🎯 Facebook Pixel -->
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '3896289297056000'); 
  fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=3896289297056000&ev=PageView&noscript=1"
/></noscript>
<!-- SOCIAL MEDIA PIXEL ENDED.  -->

</head>
<body>
    <header id="home">
        <nav>
            <a href="#home" class="logo">
                <img src="image/logo.png" alt="FLEXCEE Tech Logo">
                <!-- <span>Tech</span> -->
            </a>
            <button class="menu-toggle" aria-label="Open menu">&#9776;</button>
            <ul>
                <li><a href="#home">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#portfolio">Portfolio</a></li>
                <li><a href="#templates">Our Work</a></li>
                <li><a href="#contact">Contact</a></li>
                <li><button id="darkToggle" class="toggle-btn"><i class="fas fa-moon"></i></button></li>
            </ul>
        </nav>
        <section class="hero">
            <div class="hero-content">
                <h1 class="slide-title">We Build & Manage Stunning Websites</h1>
                <p class="slide-subtitle">Specialized in Shopify, WordPress, React & Custom Web Apps</p>
                <a href="#contact" class="cta-button">
                    <span class="cta-icon">💬</span>
                    <span class="cta-text">Chat with Our Team</span>
                    <span class="cta-arrow">→</span>
                </a>
            </div>
        </section>
    </header>

    <section id="about" class="section animate-on-scroll">
        <h2>About FLEXCEE Tech</h2>
        <div class="about-content">
            <p>FLEXCEE Tech is a leading web technology Agency that specializes in creating high-performance websites and providing comprehensive IT training. With a global presence spanning the US, UK, Europe, and Africa, we've established ourselves as a trusted partner for businesses seeking digital transformation.</p>
            <div class="about-stats">
                <div class="stat-item">
                    <i class="fas fa-globe"></i>
                    <h3>Global Reach</h3>
                    <p>500+ Clients Worldwide</p>
                </div>
                <div class="stat-item">
                    <i class="fas fa-code"></i>
                    <h3>Expert Team</h3>
                    <p>50+ Skilled Developers</p>
                </div>
                <div class="stat-item">
                    <i class="fas fa-graduation-cap"></i>
                    <h3>Training</h3>
                    <p>1000+ Graduates</p>
                </div>
            </div>
        </div>
    </section>

    <section id="services" class="section animate-on-scroll">
        <h2>Our Services</h2>
        <div class="services-grid">
            <div class="service">
                <i class="fas fa-laptop-code"></i>
                <h3>Website Design & Development</h3>
                <ul>
                    <li>Custom Shopify Store Development</li>
                    <li>WordPress & Elementor Expert Solutions</li>
                    <li>React & Next.js Web Applications</li>
                    <li>E-commerce Solutions</li>
                    <li>Responsive Web Design</li>
                </ul>
            </div>
            <div class="service">
                <i class="fas fa-tools"></i>
                <h3>Website Management</h3>
                <ul>
                    <li>24/7 Technical Support</li>
                    <li>Advanced SEO Optimization</li>
                    <li>Performance Monitoring & Optimization</li>
                    <li>Security Updates & Maintenance</li>
                    <li>Content Management</li>
                </ul>
            </div>
            <div class="service">
                <i class="fas fa-chalkboard-teacher"></i>
                <h3>IT & Programming Training</h3>
                <ul>
                    <li>Frontend Development Bootcamp</li>
                    <li>Backend Development Masterclass</li>
                    <li>Full-Stack Development Program</li>
                    <li>Cloud & DevOps Training</li>
                    <li>Career Development Support</li>
                </ul>
            </div>
        </div>
    </section>

    <section id="portfolio" class="section animate-on-scroll">
        <h2>Our Portfolio</h2>
        <div class="portfolio-slider">
            <div class="portfolio-slide active">
                <img src="image/1.jpg" alt="Modern Business Website">
                <div class="portfolio-info">
                    <h3>Modern Business Website</h3>
                    <p>Corporate, Clean, Responsive</p>
                </div>
            </div>
            <div class="portfolio-slide">
                <img src="image/2.jpg" alt="E-commerce Store">
                <div class="portfolio-info">
                    <h3>E-commerce Store</h3>
                    <p>Shopify, WooCommerce, Custom Cart</p>
                </div>
            </div>
            <div class="portfolio-slide">
                <img src="image/3.jpg" alt="Portfolio & Resume">
                <div class="portfolio-info">
                    <h3>Portfolio & Resume</h3>
                    <p>Personal Branding, CV, Portfolio</p>
                </div>
            </div>
            <div class="portfolio-slide">
                <img src="image/4.jpg" alt="Creative Agency">
                <div class="portfolio-info">
                    <h3>Creative Agency</h3>
                    <p>Agency, Studio, Creative</p>
                </div>
            </div>
            <div class="portfolio-slide">
                <img src="image/5.jpg" alt="Landing Page">
                <div class="portfolio-info">
                    <h3>Landing Page</h3>
                    <p>Marketing, Product, App</p>
                </div>
            </div>
            <div class="portfolio-slide">
                <img src="image/6.jpg" alt="Restaurant & Food">
                <div class="portfolio-info">
                    <h3>Restaurant & Food</h3>
                    <p>Menu, Booking, Delivery</p>
                </div>
            </div>
            <div class="portfolio-slide">
                <img src="image/7.jpg" alt="Education & Courses">
                <div class="portfolio-info">
                    <h3>Education & Courses</h3>
                    <p>Online Learning, School, Courses</p>
                </div>
            </div>
            <div class="portfolio-slide">
                <img src="image/8.jpg" alt="Health & Fitness">
                <div class="portfolio-info">
                    <h3>Health & Fitness</h3>
                    <p>Gym, Yoga, Trainer</p>
                </div>
            </div>
            <div class="portfolio-slide">
                <img src="image/9.jpg" alt="Real Estate">
                <div class="portfolio-info">
                    <h3>Real Estate</h3>
                    <p>Property, Listings, Agents</p>
                </div>
            </div>
            <div class="portfolio-slide">
                <img src="image/10.jpg" alt="Travel & Tourism">
                <div class="portfolio-info">
                    <h3>Travel & Tourism</h3>
                    <p>Booking, Destinations, Tours</p>
                </div>
            </div>
            <div class="portfolio-slide">
                <img src="image/11.jpg" alt="Blog & Magazine">
                <div class="portfolio-info">
                    <h3>Blog & Magazine</h3>
                    <p>News, Articles, Stories</p>
                </div>
            </div>
            <button class="portfolio-slider-btn prev">&#10094;</button>
            <button class="portfolio-slider-btn next">&#10095;</button>
            <div class="portfolio-indicators"></div>
        </div>
    </section>

    

    <section id="contact" class="section animate-on-scroll">
        <h2>Contact Us</h2>
        <div class="contact-container">
            <form class="contact-form" id="contactForm">
                <h3>Send us a Message</h3>
                <div class="form-message"></div>
                <div class="form-group">
                    <label for="name">Your Name *</label>
                    <input type="text" id="name" name="name" required>
                    <i class="fas fa-user"></i>
                </div>
                <div class="form-group">
                    <label for="email">Your Email *</label>
                    <input type="email" id="email" name="email" required>
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone">
                    <i class="fas fa-phone"></i>
                </div>
                <div class="form-group">
                    <label for="service">Service Interested In *</label>
                    <select id="service" name="service" required>
                        <option value="">Select Service</option>
                        <option value="web-design">Web Design & Development</option>
                        <option value="web-management">Website Management</option>
                        <option value="training">IT Training</option>
                    </select>
                    <i class="fas fa-cog"></i>
                </div>
                <div class="form-group">
                    <label for="message">Your Message *</label>
                    <textarea id="message" name="message" required></textarea>
                    <i class="fas fa-comment"></i>
                </div>
                <button type="submit" class="btn">Send Message</button>
            </form>
            <div class="contact-info">
                <div class="info-item">
                    <i class="fas fa-phone"></i>
                    <h3>Phone</h3>
                    <p>+1 502-234-5880</p>
                </div>
                <div class="info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <h3>Address</h3>
                    <p>105 Chipman Str, Owosso, MI 48867</p>
                </div>
                <div class="info-item">
                    <i class="fas fa-envelope"></i>
                    <h3>Email</h3>
                    <p>info@flexceetech.world</p>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-content">
            <div class="footer-logo">
                <img src="image/logo.png" alt="FLEXCEE Tech Logo">
                <span>FLEXCEE Tech</span>
            </div>
            <div class="footer-links">
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="#home">Home</a></li>
                        <li><a href="#about">About</a></li>
                        <li><a href="#services">Services</a></li>
                        <li><a href="#portfolio">Portfolio</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Services</h4>
                    <ul>
                        <li><a href="#services">Web Development</a></li>
                        <li><a href="#services">Website Management</a></li>
                        <li><a href="#services">IT Training</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Connect With Us</h4>
                    <div class="social-links">
                        <a href="https://www.facebook.com/flexceetech/"><i class="fab fa-facebook"></i></a>
                        <a href="https://twitter.com/flexceetech/"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.linkedin.com/in/collinsezih/"><i class="fab fa-linkedin"></i></a>
                        <a href="https://www.instagram.com/flexceetech/"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-section payment-methods">
                <h4>Payment Methods</h4>
                <div class="payment-icons">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b5/PayPal.svg/200px-PayPal.svg.png" alt="PayPal" style="height:30px; margin-right:10px;">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/4/41/Visa_Logo.png/200px-Visa_Logo.png" alt="Visa" style="height:30px; margin-right:10px;">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/04/Mastercard-logo.png/200px-Mastercard-logo.png" alt="MasterCard" style="height:30px;">
                </div>
                <p style="font-size: 0.9rem; margin-top: 0.5rem; color: #ccc;">Secure checkout powered by Stripe & PayPal</p>
            </div>
            <p class="copyright">&copy; <?= date('Y') ?> FLEXCEE Tech. All rights reserved.</p>
        </div>
    </footer>

    <a href="#home" id="scrollToTop" title="Back to Top"><i class="fas fa-arrow-up"></i></a>
    <a href="https://wa.me/message/" class="whatsapp-float" target="_blank" title="Chat on WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>

    <script src="script.js"></script>
    <script>
    // Contact form handler (robust, prevents double submit)
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        let isSubmitting = false;
        contactForm.onsubmit = function(e) {
            e.preventDefault();
            if (isSubmitting) return false; // Prevent double submit
            isSubmitting = true;
            const form = this;
            const formMessage = form.querySelector('.form-message');
            const submitBtn = form.querySelector('button[type="submit"]');
            form.classList.add('form-loading');
            submitBtn.disabled = true;
            formMessage.textContent = '';
            formMessage.style.display = 'none';
            // Get form data
            const formData = new FormData(form);
            fetch('process_contact.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                form.classList.remove('form-loading');
                submitBtn.disabled = false;
                isSubmitting = false;
                formMessage.textContent = data.message || 'Thank you for your message!';
                formMessage.className = 'form-message ' + (data.success ? 'success' : 'error');
                formMessage.style.display = 'block';
                if (data.success) {
                    form.reset();
                }
                setTimeout(() => {
                    formMessage.style.display = 'none';
                }, 5000);
            })
            .catch(error => {
                form.classList.remove('form-loading');
                submitBtn.disabled = false;
                isSubmitting = false;
                formMessage.textContent = 'Sorry, there was an error sending your message. Please try again later.';
                formMessage.className = 'form-message error';
                formMessage.style.display = 'block';
            });
            return false;
        };
    }
    </script>
    <style>
    /* Base styles and resets */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Poppins', sans-serif;
        line-height: 1.6;
        overflow-x: hidden;
    }

    /* Section styling with full width */
    .section {
        width: 100%;
        padding: 5rem 5vw !important;
        margin: 0 !important;
        position: relative;
        background: linear-gradient(135deg, #fff 60%, #f8f9fa 100%);
    }

    .section:nth-child(even) {
        background: linear-gradient(135deg, #f8f9fa 60%, #fff 100%);
    }

    .section > * {
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* Templates Slider Styling */
    .templates-slider {
        position: relative;
        width: 100%;
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .template-slide {
        display: none;
        width: 100%;
        opacity: 0;
        transition: opacity 0.5s ease;
    }

    .template-slide.active {
        display: block;
        opacity: 1;
    }

    .template-slide img {
        width: 100%;
        height: auto;
        max-height: 600px;
        object-fit: contain;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(228,9,118,0.15);
    }

    .template-info {
        margin-top: 1.5rem;
        text-align: center;
    }

    .template-info h3 {
        color: #e40976;
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }

    .template-info p {
        color: #666;
        font-size: 1.1rem;
    }

    /* Slider Controls */
    .slider-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #e40976, #f1d91e);
        border: none;
        border-radius: 50%;
        color: white;
        font-size: 1.5rem;
        cursor: pointer;
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 15px rgba(228,9,118,0.2);
        transition: all 0.3s ease;
    }

    .slider-btn:hover {
        transform: translateY(-50%) scale(1.1);
        box-shadow: 0 6px 20px rgba(228,9,118,0.3);
    }

    .slider-btn.prev {
        left: 0;
    }

    .slider-btn.next {
        right: 0;
    }

    /* Services Grid */
    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 2rem;
        padding: 1rem;
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
    }

    .service {
        background: #fff;
        padding: 2rem;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(228,9,118,0.08);
        transition: transform 0.3s ease;
    }

    /* Portfolio Slider */
    .portfolio-slider {
        position: relative;
        width: 100%;
        max-width: 900px;
        margin: 2rem auto;
        overflow: hidden;
        border-radius: 20px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        background: #fff;
    }

    .portfolio-slide {
        display: none;
        width: 100%;
        opacity: 0;
        transition: opacity 0.7s cubic-bezier(.4,0,.2,1), transform 0.7s cubic-bezier(.4,0,.2,1);
        transform: scale(0.98) translateY(30px);
        position: absolute;
        left: 0; top: 0;
    }

    .portfolio-slide.active {
        display: block;
        opacity: 1;
        position: relative;
        transform: scale(1) translateY(0);
        z-index: 2;
        animation: fadeInSlide 0.7s cubic-bezier(.4,0,.2,1);
    }

    @keyframes fadeInSlide {
        from { opacity: 0; transform: scale(0.98) translateY(30px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }

    .portfolio-slide img {
        width: 100%;
        height: 400px;
        object-fit: cover;
        border-radius: 20px 20px 0 0;
        box-shadow: 0 2px 12px rgba(0,0,0,0.07);
    }

    .portfolio-info {
        padding: 1.5rem;
        text-align: center;
    }

    .portfolio-info h3 {
        color: #e40976;
        font-size: 1.3rem;
        margin-bottom: 0.5rem;
    }

    .portfolio-info p {
        color: #666;
        font-size: 1rem;
    }

    .portfolio-slider-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 44px;
        height: 44px;
        background: linear-gradient(135deg, #e40976, #f1d91e);
        border: none;
        border-radius: 50%;
        color: #fff;
        font-size: 1.5rem;
        cursor: pointer;
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 15px rgba(228,9,118,0.15);
        transition: all 0.3s ease;
    }

    .portfolio-slider-btn.prev { left: 10px; }
    .portfolio-slider-btn.next { right: 10px; }
    .portfolio-slider-btn:hover { background: #e40976; }

    .portfolio-indicators {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin: 1rem 0 1.5rem;
    }

    .portfolio-indicators .indicator {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #eee;
        border: 2px solid #e40976;
        cursor: pointer;
        transition: background 0.3s;
    }

    .portfolio-indicators .indicator.active {
        background: #e40976;
        border-color: #f1d91e;
        transform: scale(1.2);
    }

    @media (max-width: 900px) {
        .portfolio-slider { max-width: 100%; }
        .portfolio-slide img { height: 250px; }
    }

    @media (max-width: 600px) {
        .portfolio-slider { border-radius: 10px; }
        .portfolio-slide img { height: 160px; border-radius: 10px 10px 0 0; }
        .portfolio-info { padding: 1rem; }
    }

    /* Mobile Menu */
    @media (max-width: 768px) {
        .section {
            padding: 3rem 1rem !important;
        }

        .templates-slider {
            padding: 0;
        }

        .template-slide img {
            max-height: 400px;
        }

        .slider-btn {
            width: 40px;
            height: 40px;
            font-size: 1.2rem;
        }

        .services-grid,
        .portfolio-slider {
            grid-template-columns: 1fr;
            padding: 0.5rem;
        }

        .contact-container {
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        nav {
            padding: 1rem;
        }

        nav ul {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #fff;
            padding: 1rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            border-radius: 0 0 15px 15px;
            z-index: 1000;
        }

        nav ul.open {
            display: flex;
            flex-direction: column;
        }

        nav ul li {
            margin: 0.5rem 0;
        }

        nav ul li a {
            display: block;
            padding: 0.8rem 1rem;
            color: #333;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        nav ul li a:hover {
            background: #f8f9fa;
            color: #e40976;
            transform: translateX(5px);
        }

        .menu-toggle {
            display: block;
            background: none;
            border: none;
            font-size: 1.8rem;
            color: #e40976;
            cursor: pointer;
            padding: 0.5rem;
            transition: transform 0.3s ease;
        }

        .menu-toggle:hover {
            transform: scale(1.1);
        }
    }

    /* Tablet and smaller desktop */
    @media (min-width: 769px) and (max-width: 1200px) {
        .section {
            padding: 4rem 3vw !important;
        }

        .services-grid,
        .portfolio-slider {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const slides = document.querySelectorAll('.portfolio-slide');
        const prevBtn = document.querySelector('.portfolio-slider-btn.prev');
        const nextBtn = document.querySelector('.portfolio-slider-btn.next');
        const indicatorsContainer = document.querySelector('.portfolio-indicators');
        let current = 0;
        let interval;

        function showSlide(idx) {
            slides.forEach((slide, i) => {
                slide.classList.toggle('active', i === idx);
            });
            document.querySelectorAll('.portfolio-indicators .indicator').forEach((dot, i) => {
                dot.classList.toggle('active', i === idx);
            });
            current = idx;
        }
        function nextSlide() {
            showSlide((current + 1) % slides.length);
        }
        function prevSlide() {
            showSlide((current - 1 + slides.length) % slides.length);
        }
        function startAuto() {
            interval = setInterval(nextSlide, 4000);
        }
        function stopAuto() {
            clearInterval(interval);
        }
        // Create indicators
        slides.forEach((_, i) => {
            const dot = document.createElement('span');
            dot.className = 'indicator' + (i === 0 ? ' active' : '');
            dot.addEventListener('click', () => showSlide(i));
            indicatorsContainer.appendChild(dot);
        });
        if (prevBtn) prevBtn.addEventListener('click', prevSlide);
        if (nextBtn) nextBtn.addEventListener('click', nextSlide);
        // Auto slide
        startAuto();
        document.querySelector('.portfolio-slider').addEventListener('mouseenter', stopAuto);
        document.querySelector('.portfolio-slider').addEventListener('mouseleave', startAuto);
        // Responsive fix: show first slide
        showSlide(0);
    });
    </script>
</body>
</html>
