/**
 * Collapsible Sections for LearnDash - Clean JavaScript Implementation
 * Simple, reliable toggle functionality without complex interference detection
 * 
 * @package CollapsibleSectionsLearnDash
 * @version 2.0
 */

jQuery(document).ready(function($) {
    'use strict';
    
    console.log('🔍 CSLD: Initializing clean section toggles...');
    
    // Initialize section toggles for both Classic and Modern UI
    initSectionToggles();
    
    function initSectionToggles() {
        // Classic UI toggle buttons
        $('.custom-section-toggle-btn').off('click.csld').on('click.csld', function(e) {
            e.preventDefault();
            var $toggleBtn = $(this);
            var sectionId = $toggleBtn.data('custom-section-id');
            var $content = $('#custom-section-content-' + sectionId);
            
            toggleClassicSection($toggleBtn, $content);
        });
        
        // Modern UI toggle buttons
        $('.custom-modern-section-toggle-btn').off('click.csld').on('click.csld', function(e) {
            e.preventDefault();
            var $toggleBtn = $(this);
            var sectionId = $toggleBtn.data('custom-section-id');
            var $content = $('#custom-modern-section-content-' + sectionId);
            
            toggleModernSection($toggleBtn, $content);
        });
        
        // Keyboard support for both UI types
        $('.custom-section-toggle-btn, .custom-modern-section-toggle-btn').off('keydown.csld').on('keydown.csld', function(e) {
            if (e.which === 13 || e.which === 32) { // Enter or Space
                e.preventDefault();
                $(this).trigger('click');
            }
        });
        
        console.log('✅ CSLD: Section toggles initialized successfully');
    }
    
    function toggleClassicSection($toggleBtn, $content) {
        var isExpanded = $toggleBtn.hasClass('expanded');
        var $icon = $toggleBtn.find('.custom-toggle-icon');
        
        if (isExpanded) {
            // Collapse section
            $toggleBtn.removeClass('expanded');
            $toggleBtn.attr('aria-expanded', 'false');
            $content.hide();
            $icon.removeClass('dashicons-arrow-down').addClass('dashicons-arrow-right');
            console.log('📁 CSLD: Classic section collapsed');
        } else {
            // Expand section
            $toggleBtn.addClass('expanded');
            $toggleBtn.attr('aria-expanded', 'true');
            $content.show();
            $icon.removeClass('dashicons-arrow-right').addClass('dashicons-arrow-down');
            console.log('📂 CSLD: Classic section expanded');
        }
    }
    
    function toggleModernSection($toggleBtn, $content) {
        var isExpanded = $toggleBtn.hasClass('expanded');
        var $icon = $toggleBtn.find('.custom-toggle-icon');
        
        if (isExpanded) {
            // Collapse section
            $toggleBtn.removeClass('expanded');
            $toggleBtn.attr('aria-expanded', 'false');
            $content.removeClass('expanded');
            $icon.removeClass('dashicons-arrow-down').addClass('dashicons-arrow-right');
            console.log('📁 CSLD: Modern section collapsed');
        } else {
            // Expand section
            $toggleBtn.addClass('expanded');
            $toggleBtn.attr('aria-expanded', 'true');
            $content.addClass('expanded');
            $icon.removeClass('dashicons-arrow-right').addClass('dashicons-arrow-down');
            console.log('📂 CSLD: Modern section expanded');
        }
    }
    
    // Handle Expand All / Collapse All buttons if they exist
    $(document).on('click', '.custom-expand-all-btn', function(e) {
        e.preventDefault();
        console.log('🔄 CSLD: Expand All clicked');
        
        // Expand all Classic UI sections
        $('.custom-section-toggle-btn').each(function() {
            var $toggleBtn = $(this);
            var sectionId = $toggleBtn.data('custom-section-id');
            var $content = $('#custom-section-content-' + sectionId);
            
            if (!$toggleBtn.hasClass('expanded')) {
                $toggleBtn.addClass('expanded');
                $toggleBtn.attr('aria-expanded', 'true');
                $content.show();
                $toggleBtn.find('.custom-toggle-icon').removeClass('dashicons-arrow-right').addClass('dashicons-arrow-down');
            }
        });
        
        // Expand all Modern UI sections
        $('.custom-modern-section-toggle-btn').each(function() {
            var $toggleBtn = $(this);
            var sectionId = $toggleBtn.data('custom-section-id');
            var $content = $('#custom-modern-section-content-' + sectionId);
            
            if (!$toggleBtn.hasClass('expanded')) {
                $toggleBtn.addClass('expanded');
                $toggleBtn.attr('aria-expanded', 'true');
                $content.addClass('expanded');
                $toggleBtn.find('.custom-toggle-icon').removeClass('dashicons-arrow-right').addClass('dashicons-arrow-down');
            }
        });
    });
    
    $(document).on('click', '.custom-collapse-all-btn', function(e) {
        e.preventDefault();
        console.log('🔄 CSLD: Collapse All clicked');
        
        // Collapse all Classic UI sections
        $('.custom-section-toggle-btn').each(function() {
            var $toggleBtn = $(this);
            var sectionId = $toggleBtn.data('custom-section-id');
            var $content = $('#custom-section-content-' + sectionId);
            
            if ($toggleBtn.hasClass('expanded')) {
                $toggleBtn.removeClass('expanded');
                $toggleBtn.attr('aria-expanded', 'false');
                $content.hide();
                $toggleBtn.find('.custom-toggle-icon').removeClass('dashicons-arrow-down').addClass('dashicons-arrow-right');
            }
        });
        
        // Collapse all Modern UI sections
        $('.custom-modern-section-toggle-btn').each(function() {
            var $toggleBtn = $(this);
            var sectionId = $toggleBtn.data('custom-section-id');
            var $content = $('#custom-modern-section-content-' + sectionId);
            
            if ($toggleBtn.hasClass('expanded')) {
                $toggleBtn.removeClass('expanded');
                $toggleBtn.attr('aria-expanded', 'false');
                $content.removeClass('expanded');
                $toggleBtn.find('.custom-toggle-icon').removeClass('dashicons-arrow-down').addClass('dashicons-arrow-right');
            }
        });
    });
});
