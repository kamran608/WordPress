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

    // WordPress-style Screenshots Gallery
    function initScreenshotsGallery() {
        const galleries = document.querySelectorAll('.sppm-screenshots-gallery[data-gallery="wordpress-style"]');
        
        galleries.forEach(gallery => {
            const slides = Array.from(gallery.querySelectorAll('.sppm-screenshot-slide'));
            const thumbnails = Array.from(gallery.querySelectorAll('.sppm-thumbnail'));
            const prevArrow = gallery.querySelector('.sppm-nav-prev');
            const nextArrow = gallery.querySelector('.sppm-nav-next');
            const totalSlides = slides.length;

            if (totalSlides <= 1) return; // No navigation needed for single image

            let currentIndex = 0;

            // Update active slide and thumbnail
            function updateGallery() {
                slides.forEach((slide, index) => {
                    slide.classList.toggle('active', index === currentIndex);
                });
                thumbnails.forEach((thumb, index) => {
                    thumb.classList.toggle('active', index === currentIndex);
                });
            }

            // Navigation functions
            function goToSlide(index) {
                currentIndex = index;
                updateGallery();
            }

            function nextImage() {
                currentIndex = (currentIndex + 1) % totalSlides;
                updateGallery();
            }

            function prevImage() {
                currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
                updateGallery();
            }

            // Event listeners for arrows
            if (prevArrow) {
                prevArrow.addEventListener('click', prevImage);
            }

            if (nextArrow) {
                nextArrow.addEventListener('click', nextImage);
            }

            // Event listeners for thumbnails
            thumbnails.forEach((thumbnail, index) => {
                thumbnail.addEventListener('click', () => goToSlide(index));
            });
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
