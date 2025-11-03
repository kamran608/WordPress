/**
 * Swrice Plugin Page Manager - Frontend JavaScript
 * Dynamic Plugin Sales Page Functionality
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        // FAQ Accordion Functionality
        $('.sppm-faq-question').on('click', function() {
            const $faqItem = $(this).closest('.sppm-faq-item');
            const isOpen = $faqItem.hasClass('open');
            
            // Close all FAQ items
            $('.sppm-faq-item').removeClass('open');
            $('.sppm-faq-question span').text('+');
            
            // Open clicked item if it wasn't already open
            if (!isOpen) {
                $faqItem.addClass('open');
                $(this).find('span').text('-');
            }
        });
        
        // Button keyboard accessibility
        $('.sppm-btn').on('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                $(this)[0].click();
            }
        });
        
        // Smooth scrolling for anchor links
        $('a[href^="#"]').on('click', function(e) {
            const target = $(this.getAttribute('href'));
            if (target.length) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: target.offset().top - 20
                }, 800);
            }
        });
        
        // Add loading states for buttons
        $('.sppm-btn-primary').on('click', function() {
            const $btn = $(this);
            const originalText = $btn.text();
            
            if (!$btn.hasClass('loading')) {
                $btn.addClass('loading').text('Processing...');
                
                // Reset after 3 seconds (adjust as needed)
                setTimeout(function() {
                    $btn.removeClass('loading').text(originalText);
                }, 3000);
            }
        });
        
    });
    
})(jQuery);
