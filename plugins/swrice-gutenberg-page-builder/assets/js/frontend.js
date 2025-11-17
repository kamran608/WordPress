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

    // Screenshots Slider - per-instance, responsive
    function initScreenshotsSliders() {
        const sliders = document.querySelectorAll('.sppm-screenshots-container[data-slider="sppm"]');
        sliders.forEach(container => {
            const viewport = container.querySelector('.sppm-screenshots-viewport');
            const track = container.querySelector('.sppm-screenshots-track');
            const slides = Array.from(container.querySelectorAll('.sppm-screenshot-slide'));
            const prevBtn = container.querySelector('.sppm-arrow-left');
            const nextBtn = container.querySelector('.sppm-arrow-right');
            const dots = Array.from(container.querySelectorAll('.sppm-dot'));

            if (!viewport || !track || slides.length === 0) return;

            let index = 0;
            let slideWidth = 0;
            let isDragging = false;
            let startX = 0;
            let currentTranslate = 0;
            let prevTranslate = 0;
            let animationID = 0;

            function setPositionByIndex() {
                currentTranslate = -index * slideWidth;
                setSliderPosition();
                updateActiveStates();
            }

            function setSliderPosition() {
                track.style.transform = `translateX(${currentTranslate}px)`;
            }

            function updateActiveStates() {
                slides.forEach((s, i) => s.classList.toggle('is-active', i === index));
                dots.forEach((d, i) => {
                    d.classList.toggle('active', i === index);
                    d.setAttribute('aria-selected', String(i === index));
                });
            }

            function recalc() {
                slideWidth = viewport.clientWidth;
                slides.forEach(s => { s.style.width = `${slideWidth}px`; });
                track.style.width = `${slideWidth * slides.length}px`;
                setPositionByIndex();
            }

            function next() {
                index = (index + 1) % slides.length;
                setPositionByIndex();
            }

            function prev() {
                index = (index - 1 + slides.length) % slides.length;
                setPositionByIndex();
            }

            function animation() {
                setSliderPosition();
                if (isDragging) requestAnimationFrame(animation);
            }

            function touchStart(e) {
                isDragging = true;
                startX = getPositionX(e);
                prevTranslate = currentTranslate;
                track.classList.add('is-grabbing');
                animationID = requestAnimationFrame(animation);
            }

            function touchMove(e) {
                if (!isDragging) return;
                const currentPosition = getPositionX(e);
                const diff = currentPosition - startX;
                currentTranslate = prevTranslate + diff;
            }

            function touchEnd() {
                cancelAnimationFrame(animationID);
                isDragging = false;
                track.classList.remove('is-grabbing');
                const movedBy = currentTranslate - prevTranslate;

                if (movedBy < -50) index = Math.min(index + 1, slides.length - 1);
                if (movedBy > 50) index = Math.max(index - 1, 0);
                setPositionByIndex();
            }

            function getPositionX(e) {
                return e.type.startsWith('mouse') ? e.pageX : (e.touches ? e.touches[0].clientX : e.clientX);
            }

            // Events
            if (prevBtn) prevBtn.addEventListener('click', prev);
            if (nextBtn) nextBtn.addEventListener('click', next);
            dots.forEach((dot, i) => dot.addEventListener('click', () => { index = i; setPositionByIndex(); }));

            // Touch/Mouse drag
            track.addEventListener('touchstart', touchStart, { passive: true });
            track.addEventListener('touchmove', touchMove, { passive: true });
            track.addEventListener('touchend', touchEnd);
            track.addEventListener('mousedown', touchStart);
            track.addEventListener('mousemove', touchMove);
            track.addEventListener('mouseup', touchEnd);
            track.addEventListener('mouseleave', () => { if (isDragging) touchEnd(); });

            window.addEventListener('resize', recalc);
            recalc();
        });
    }

    // Video embed loader with custom play overlay
    function initVideoEmbeds() {
        document.querySelectorAll('.sppm-video-embed[data-embed-url]').forEach(container => {
            const thumbnail = container.querySelector('.sppm-video-thumbnail');
            const iframe = container.querySelector('.sppm-video-iframe');
            if (!thumbnail || !iframe) return;
            const baseUrl = container.getAttribute('data-embed-url');

            thumbnail.addEventListener('click', () => {
                const sep = baseUrl.includes('?') ? '&' : '?';
                iframe.src = `${baseUrl}${sep}autoplay=1`;
                iframe.style.display = 'block';
                thumbnail.style.display = 'none';
            });
        });
    }

    $(document).ready(function() {
        initFAQToggle();
        initSmoothScrolling();
        initButtonEffects();
        initScrollAnimations();
        initScreenshotsSliders();
        initVideoEmbeds();
    });

})(jQuery);
