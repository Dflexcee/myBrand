// script.js

// Dark mode toggle
const toggleBtn = document.getElementById('darkToggle');
if (toggleBtn) {
    toggleBtn.addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');
        const icon = toggleBtn.querySelector('i');
        icon.classList.toggle('fa-moon');
        icon.classList.toggle('fa-sun');
    });
}

// Scroll to top button
const scrollBtn = document.getElementById('scrollToTop');
if (scrollBtn) {
    window.addEventListener('scroll', () => {
        scrollBtn.style.display = window.scrollY > 200 ? 'block' : 'none';
    });

    scrollBtn.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

// Hero Slider
const heroSlides = [
    {
        title: "🚀 Build Your Website in 72 Hours",
        subtitle: "For business owners in the U.S., UK & beyond — Shopify, WordPress & custom solutions.",
        image: "https://images.unsplash.com/photo-1543269865-cbf427effbad"
    },
    {
        title: "We Build & Manage Stunning Websites",
        subtitle: "Specialized in Shopify, WordPress, React & Custom Web Apps",
        image: "https://images.unsplash.com/photo-1543269865-cbf427effbad"
    },
    // {
    //     title: "Expert IT Training & Development",
    //     subtitle: "Learn from Industry Professionals",
    //     image: "https://images.unsplash.com/photo-1521791136064-7986c2920216"
    // },
    {
        title: "Custom Web Solutions with 48hrs Delivery",
        subtitle: "Tailored to Your Business Needs",
        image: "https://images.unsplash.com/photo-1551434678-e076c223a692"
    }
];

let currentSlide = 0;
const heroSection = document.querySelector('.hero');
const heroContent = document.querySelector('.hero-content');
let slideInterval;

function updateSlide() {
    const slide = heroSlides[currentSlide];
    heroSection.style.backgroundImage = `url(${slide.image})`;
    
    // Add fade-out animation
    heroContent.style.opacity = '0';
    heroContent.style.transform = 'translateY(20px)';
    
    setTimeout(() => {
        heroContent.innerHTML = `
            <h1 class="slide-title">${slide.title}</h1>
            <p class="slide-subtitle">${slide.subtitle}</p>
            <a href="#contact" class="cta-button">
                <span class="cta-icon">💬</span>
                <span class="cta-text">Chat with Our Team</span>
                <span class="cta-arrow">→</span>
            </a>
        `;
        
        // Add fade-in animation
        heroContent.style.opacity = '1';
        heroContent.style.transform = 'translateY(0)';
    }, 500);
    
    // Update slider indicators
    updateSliderIndicators();
}

function nextSlide() {
    currentSlide = (currentSlide + 1) % heroSlides.length;
    updateSlide();
}

function prevSlide() {
    currentSlide = (currentSlide - 1 + heroSlides.length) % heroSlides.length;
    updateSlide();
}

function goToSlide(index) {
    currentSlide = index;
    updateSlide();
}

function updateSliderIndicators() {
    const indicators = document.querySelector('.slider-indicators');
    if (indicators) {
        const dots = indicators.querySelectorAll('.indicator');
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === currentSlide);
        });
    }
}

function startSlideInterval() {
    stopSlideInterval();
    slideInterval = setInterval(nextSlide, 5000);
}

function stopSlideInterval() {
    if (slideInterval) {
        clearInterval(slideInterval);
    }
}

// Initialize slider
updateSlide();
startSlideInterval();

// Add slider controls to the DOM
const sliderControls = document.createElement('div');
sliderControls.className = 'slider-controls';
sliderControls.innerHTML = `
    <button class="slider-btn prev-btn"><i class="fas fa-chevron-left"></i></button>
    <div class="slider-indicators">
        ${heroSlides.map((_, index) => `
            <button class="indicator ${index === 0 ? 'active' : ''}" data-slide="${index}"></button>
        `).join('')}
    </div>
    <button class="slider-btn next-btn"><i class="fas fa-chevron-right"></i></button>
`;
heroSection.appendChild(sliderControls);

// Add event listeners for slider controls
document.querySelector('.prev-btn').addEventListener('click', () => {
    prevSlide();
    startSlideInterval();
});

document.querySelector('.next-btn').addEventListener('click', () => {
    nextSlide();
    startSlideInterval();
});

document.querySelectorAll('.indicator').forEach(dot => {
    dot.addEventListener('click', () => {
        const slideIndex = parseInt(dot.dataset.slide);
        goToSlide(slideIndex);
        startSlideInterval();
    });
});

// Pause slider on hover
heroSection.addEventListener('mouseenter', stopSlideInterval);
heroSection.addEventListener('mouseleave', startSlideInterval);

// Smooth scroll for navigation links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            const headerOffset = 80;
            const elementPosition = target.getBoundingClientRect().top;
            const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

            window.scrollTo({
                top: offsetPosition,
                behavior: 'smooth'
            });
        }
    });
});

// Animate elements on scroll
const animateOnScroll = () => {
    const elements = document.querySelectorAll('.animate-on-scroll');
    elements.forEach(element => {
        const elementTop = element.getBoundingClientRect().top;
        const elementBottom = element.getBoundingClientRect().bottom;
        
        if (elementTop < window.innerHeight * 0.8 && elementBottom > 0) {
            element.classList.add('visible');
        }
    });
};

// Throttle scroll event for better performance
let ticking = false;
window.addEventListener('scroll', () => {
    if (!ticking) {
        window.requestAnimationFrame(() => {
            animateOnScroll();
            ticking = false;
        });
        ticking = true;
    }
});

// Initial animation check
window.addEventListener('load', () => {
    animateOnScroll();
    // Add initial animation to hero content
    heroContent.style.opacity = '1';
    heroContent.style.transform = 'translateY(0)';
});

// Contact form handler (robust, prevents double submit)
const contactForm = document.getElementById('contactForm');
if (contactForm) {
    let isSubmitting = false;
    contactForm.onsubmit = function(e) {
        e.preventDefault();
        fbq('track', 'Lead'); // 🟣 Fire Facebook 'Lead' event
        if (isSubmitting) return false;
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
