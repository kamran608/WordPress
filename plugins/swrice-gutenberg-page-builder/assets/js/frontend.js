/**
 * Swrice Plugin Sell Page Builder - Frontend JavaScript
 * Handles interactive elements on the frontend
 */

(function($) {
    'use strict';

    // FAQ Toggle Functionality
    function initFAQToggle() {
        $('.sppm-faq-question').on('click', function() {
            const $faqItem = $(this).closest('.sppm-faq-item');
            const $answer = $faqItem.find('.sppm-faq-answer');
            const $icon = $(this).find('span').last();
            
            // Toggle active class
            $faqItem.toggleClass('active');
            
            // Toggle answer visibility
            $answer.slideToggle(300);
            
            // Update icon
            if ($faqItem.hasClass('active')) {
                $icon.text('−');
            } else {
                $icon.text('+');
            }
        });
    }

    // Smooth scrolling for anchor links
    function initSmoothScrolling() {
        $('a[href^="#"]').on('click', function(e) {
            const target = $(this.getAttribute('href'));
            if (target.length) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: target.offset().top - 100
                }, 800);
            }
        });
    }

    // Button hover effects
    function initButtonEffects() {
        $('.sppm-btn').on('mouseenter', function() {
            $(this).addClass('hover');
        }).on('mouseleave', function() {
            $(this).removeClass('hover');
        });
    }

    // Initialize animations on scroll (if needed)
    function initScrollAnimations() {
        if (typeof IntersectionObserver !== 'undefined') {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-in');
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });

            // Observe sections for animation
            document.querySelectorAll('.sppm-section').forEach(section => {
                observer.observe(section);
            });
        }
    }

    // Initialize all functionality when DOM is ready

    // Simple Screenshots Slider with Arrow Navigation
    function initScreenshotsGallery() {
        const sliders = document.querySelectorAll('.sppm-screenshots-slider[data-slider="simple"]');
        
        sliders.forEach(slider => {
            const slides = Array.from(slider.querySelectorAll('.sppm-slide'));
            const arrowLeft = slider.querySelector('.sppm-arrow-left');
            const arrowRight = slider.querySelector('.sppm-arrow-right');
            const totalSlides = slides.length;

            if (totalSlides <= 1) return; // No navigation needed for single image

            let currentIndex = 0;

            // Update active slide
            function updateSlide() {
                slides.forEach((slide, index) => {
                    slide.classList.toggle('active', index === currentIndex);
                });
            }

            // Navigation functions
            function nextImage() {
                currentIndex = (currentIndex + 1) % totalSlides;
                updateSlide();
            }

            function prevImage() {
                currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
                updateSlide();
            }

            // Event listeners for arrows
            if (arrowLeft) {
                arrowLeft.addEventListener('click', prevImage);
            }

            if (arrowRight) {
                arrowRight.addEventListener('click', nextImage);
            }
        });
    }

    // Premium Video Player
    function initPremiumVideoPlayers() {
        const players = document.querySelectorAll('[data-video-player="premium"]');
        
        players.forEach(player => {
            const embedContainer = player.querySelector('.sppm-video-embed-premium');
            if (!embedContainer) return;

            const thumbnail = embedContainer.querySelector('.sppm-video-thumbnail-premium');
            const playButton = embedContainer.querySelector('.sppm-play-button-premium');
            const iframe = embedContainer.querySelector('.sppm-video-iframe-premium');
            const embedUrl = embedContainer.getAttribute('data-embed-url');

            if (!playButton || !iframe || !embedUrl) return;

            // Play button click handler
            playButton.addEventListener('click', () => {
                // Add autoplay parameter
                const separator = embedUrl.includes('?') ? '&' : '?';
                const autoplayUrl = `${embedUrl}${separator}autoplay=1&rel=0&modestbranding=1`;
                
                // Load iframe
                iframe.src = autoplayUrl;
                iframe.style.display = 'block';
                
                // Hide thumbnail
                thumbnail.style.opacity = '0';
                setTimeout(() => {
                    thumbnail.style.display = 'none';
                }, 300);
            });

            // Add ripple effect
            playButton.addEventListener('click', (e) => {
                const ripple = playButton.querySelector('.sppm-play-ripple');
                ripple.style.animation = 'none';
                ripple.offsetHeight; // Trigger reflow
                ripple.style.animation = 'sppm-ripple 0.6s ease-out';
            });
        });
    }

    $(document).ready(function() {
        initFAQToggle();
        initSmoothScrolling();
        initButtonEffects();
        initScrollAnimations();
        initScreenshotsGallery();
        initPremiumVideoPlayers();
    });

})(jQuery);
