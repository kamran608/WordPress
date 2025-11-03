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
    $(document).ready(function() {
        initFAQToggle();
        initSmoothScrolling();
        initButtonEffects();
        initScrollAnimations();
    });

})(jQuery);
