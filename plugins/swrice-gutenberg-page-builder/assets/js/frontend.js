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

    // Professional Screenshots Gallery with Lightbox
    function initScreenshotsGallery() {
        const galleries = document.querySelectorAll('.sppm-screenshots-gallery[data-gallery="professional"]');
        
        galleries.forEach(gallery => {
            const mainDisplay = gallery.querySelector('[data-main-display]');
            const mainImage = gallery.querySelector('.sppm-main-image');
            const thumbnails = Array.from(gallery.querySelectorAll('.sppm-thumbnail'));
            const thumbnailsTrack = gallery.querySelector('[data-thumbnails-track]');
            const expandBtn = gallery.querySelector('.sppm-expand-btn');
            const lightbox = gallery.querySelector('[data-lightbox]');
            const lightboxImage = gallery.querySelector('.sppm-lightbox-image');
            const lightboxClose = gallery.querySelectorAll('[data-lightbox-close]');
            const lightboxPrev = gallery.querySelector('.sppm-lightbox-prev');
            const lightboxNext = gallery.querySelector('.sppm-lightbox-next');
            const navPrev = gallery.querySelector('.sppm-nav-prev');
            const navNext = gallery.querySelector('.sppm-nav-next');

            let currentIndex = 0;
            let thumbnailOffset = 0;
            const thumbnailsPerView = 4;

            // Update main image
            function updateMainImage(index) {
                const thumbnail = thumbnails[index];
                if (!thumbnail) return;

                const fullSrc = thumbnail.getAttribute('data-full-src');
                const alt = thumbnail.querySelector('img').alt;

                mainImage.src = fullSrc;
                mainImage.alt = alt;
                mainImage.setAttribute('data-full-src', fullSrc);

                // Update active thumbnail
                thumbnails.forEach((thumb, i) => {
                    thumb.classList.toggle('active', i === index);
                });

                currentIndex = index;
            }

            // Thumbnail navigation
            function updateThumbnailsPosition() {
                if (!thumbnailsTrack) return;
                const thumbnailWidth = 120; // Including gap
                const translateX = -thumbnailOffset * thumbnailWidth;
                thumbnailsTrack.style.transform = `translateX(${translateX}px)`;
                
                // Update nav buttons
                if (navPrev) navPrev.disabled = thumbnailOffset === 0;
                if (navNext) navNext.disabled = thumbnailOffset >= thumbnails.length - thumbnailsPerView;
            }

            // Lightbox functions
            function openLightbox(index = currentIndex) {
                if (!lightbox) return;
                
                const thumbnail = thumbnails[index];
                const fullSrc = thumbnail.getAttribute('data-full-src');
                const alt = thumbnail.querySelector('img').alt;

                lightboxImage.src = fullSrc;
                lightboxImage.alt = alt;
                lightbox.setAttribute('aria-hidden', 'false');
                lightbox.classList.add('active');
                document.body.style.overflow = 'hidden';
                currentIndex = index;
            }

            function closeLightbox() {
                if (!lightbox) return;
                
                lightbox.setAttribute('aria-hidden', 'true');
                lightbox.classList.remove('active');
                document.body.style.overflow = '';
            }

            function navigateLightbox(direction) {
                const newIndex = direction === 'next' 
                    ? (currentIndex + 1) % thumbnails.length
                    : (currentIndex - 1 + thumbnails.length) % thumbnails.length;
                
                const thumbnail = thumbnails[newIndex];
                const fullSrc = thumbnail.getAttribute('data-full-src');
                const alt = thumbnail.querySelector('img').alt;

                lightboxImage.src = fullSrc;
                lightboxImage.alt = alt;
                currentIndex = newIndex;
                
                // Update main gallery too
                updateMainImage(newIndex);
            }

            // Event listeners
            thumbnails.forEach((thumbnail, index) => {
                thumbnail.addEventListener('click', () => updateMainImage(index));
            });

            if (expandBtn) {
                expandBtn.addEventListener('click', () => openLightbox());
            }

            lightboxClose.forEach(btn => {
                btn.addEventListener('click', closeLightbox);
            });

            if (lightboxPrev) {
                lightboxPrev.addEventListener('click', () => navigateLightbox('prev'));
            }

            if (lightboxNext) {
                lightboxNext.addEventListener('click', () => navigateLightbox('next'));
            }

            if (navPrev) {
                navPrev.addEventListener('click', () => {
                    thumbnailOffset = Math.max(0, thumbnailOffset - 1);
                    updateThumbnailsPosition();
                });
            }

            if (navNext) {
                navNext.addEventListener('click', () => {
                    thumbnailOffset = Math.min(thumbnails.length - thumbnailsPerView, thumbnailOffset + 1);
                    updateThumbnailsPosition();
                });
            }

            // Keyboard navigation
            document.addEventListener('keydown', (e) => {
                if (!lightbox.classList.contains('active')) return;
                
                switch(e.key) {
                    case 'Escape':
                        closeLightbox();
                        break;
                    case 'ArrowLeft':
                        navigateLightbox('prev');
                        break;
                    case 'ArrowRight':
                        navigateLightbox('next');
                        break;
                }
            });

            // Initialize
            updateThumbnailsPosition();
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
