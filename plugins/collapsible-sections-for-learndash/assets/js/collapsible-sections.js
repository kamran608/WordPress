/**
 * Collapsible Sections for LearnDash - Frontend JavaScript
 * Completely independent from LearnDash's existing functionality
 * Uses unique selectors and classes to avoid any conflicts
 * 
 * @package CollapsibleSectionsLearnDash
 * @version 1.0
 */

jQuery(document).ready(function($) {
    'use strict';
    
    // Initialize custom section toggles
    initCustomSectionToggles();
    
    function initCustomSectionToggles() {
        // Find all custom section toggle buttons for both Classic and Modern UI
        var toggleSelectors = [
            '.custom-section-toggle-btn',        // Classic UI
            '.custom-modern-section-toggle-btn'  // Modern UI
        ];
        
        $(toggleSelectors.join(', ')).each(function() {
            var $toggleBtn = $(this);
            var sectionId = $toggleBtn.data('custom-section-id');
            var $sectionContent;
            
            // Determine content selector based on UI type
            if ($toggleBtn.hasClass('custom-modern-section-toggle-btn')) {
                // Modern UI - content selector
                $sectionContent = $('#custom-modern-section-content-' + sectionId);
            } else {
                // Classic UI - content selector
                $sectionContent = $('#custom-section-content-' + sectionId);
            }
            
            // Ensure section content is hidden by default
            $sectionContent.hide();
            
            // Add click handler to toggle button
            $toggleBtn.on('click.customSectionToggle', function(e) {
                e.preventDefault();
                e.stopImmediatePropagation(); // Completely stop event propagation
                toggleCustomSection($toggleBtn, $sectionContent);
                return false;
            });
            
            // Add keyboard support (Enter and Space)
            $toggleBtn.on('keydown.customSectionToggle', function(e) {
                if (e.which === 13 || e.which === 32) { // Enter or Space
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    toggleCustomSection($toggleBtn, $sectionContent);
                    return false;
                }
            });
        });
    }
    
    function toggleCustomSection($toggleBtn, $sectionContent) {
        var isExpanded = $toggleBtn.hasClass('expanded');
        var $icon = $toggleBtn.find('.custom-toggle-icon');
        
        if (isExpanded) {
            // Collapse section
            $toggleBtn.removeClass('expanded');
            $toggleBtn.attr('aria-expanded', 'false');
            $sectionContent.hide();
            
            // Change icon from arrow-down to arrow-right
            $icon.removeClass('dashicons-arrow-down').addClass('dashicons-arrow-right');
        } else {
            // Expand section
            $toggleBtn.addClass('expanded');
            $toggleBtn.attr('aria-expanded', 'true');
            $sectionContent.show();
            
            // Change icon from arrow-right to arrow-down
            $icon.removeClass('dashicons-arrow-right').addClass('dashicons-arrow-down');
        }
    }
    
    // Integration with LearnDash's Expand All functionality
    initExpandAllIntegration();
    
    function initExpandAllIntegration() {
        // Find the main expand/collapse button - support both Classic and Modern UI
        var $mainExpandButton = $('.ld-expand-button[data-ld-expands]').first(); // Classic UI
        
        // If Classic UI button not found, try Modern UI selector
        if (!$mainExpandButton.length) {
            $mainExpandButton = $('.ld-accordion__expand-button[data-ld-expand-button="true"]').first(); // Modern UI
        }
        
        if ($mainExpandButton.length) {
            // Get the expand/collapse behavior setting
            var expandBehavior = (typeof csld_settings !== 'undefined' && csld_settings.expand_collapse_behavior) 
                ? csld_settings.expand_collapse_behavior 
                : 'all_content';
            
            if (expandBehavior === 'sections_only') {
                // SECTIONS ONLY BEHAVIOR - Current working implementation
                initSectionsOnlyBehavior($mainExpandButton);
            } else {
                // ALL CONTENT BEHAVIOR - Default behavior (expand everything)
                initAllContentBehavior($mainExpandButton);
            }
        }
    }
    
    function initSectionsOnlyBehavior($mainExpandButton) {
        // COMPLETELY OVERRIDE the click event to ONLY expand sections, NOT lessons
        $mainExpandButton.off('click'); // Remove LearnDash's original handler
        
        $mainExpandButton.on('click.customSectionOnly', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            
            var $button = $(this);
            // Check for both Classic UI (ld-expanded) and Modern UI (aria-expanded) states
            var isCurrentlyExpanded = $button.hasClass('ld-expanded') || $button.attr('aria-expanded') === 'true';
            
            if (!isCurrentlyExpanded) {
                
                // ONLY expand sections, do NOT let LearnDash expand lessons
                // Support both Classic and Modern UI
                $('.custom-section-toggle-btn, .custom-modern-section-toggle-btn').each(function() {
                    var $sectionToggle = $(this);
                    var sectionId = $sectionToggle.data('custom-section-id');
                    var $sectionContent;
                    
                    // Determine content selector based on UI type
                    if ($sectionToggle.hasClass('custom-modern-section-toggle-btn')) {
                        $sectionContent = $('#custom-modern-section-content-' + sectionId);
                    } else {
                        $sectionContent = $('#custom-section-content-' + sectionId);
                    }
                    
                    var $icon = $sectionToggle.find('.custom-toggle-icon');
                    
                    if (!$sectionToggle.hasClass('expanded')) {
                        $sectionToggle.addClass('expanded');
                        $sectionToggle.attr('aria-expanded', 'true');
                        $sectionContent.show();
                        
                        // Change icon from arrow-right to arrow-down
                        $icon.removeClass('dashicons-arrow-right').addClass('dashicons-arrow-down');
                    }
                });
                
                // Update button state to expanded - support both Classic and Modern UI
                $button.addClass('ld-expanded');
                $button.attr('aria-expanded', 'true');
                
                // Update button text - support both Classic and Modern UI
                var $textElement = $button.find('.ld-text, .ld-accordion__expand-button-text');
                $textElement.text($button.data('ld-collapse-text') || 'Collapse All');
                
            } else {
                
                // Collapse all sections
                // Support both Classic and Modern UI
                $('.custom-section-toggle-btn, .custom-modern-section-toggle-btn').each(function() {
                    var $sectionToggle = $(this);
                    var sectionId = $sectionToggle.data('custom-section-id');
                    var $sectionContent;
                    
                    // Determine content selector based on UI type
                    if ($sectionToggle.hasClass('custom-modern-section-toggle-btn')) {
                        $sectionContent = $('#custom-modern-section-content-' + sectionId);
                    } else {
                        $sectionContent = $('#custom-section-content-' + sectionId);
                    }
                    
                    var $icon = $sectionToggle.find('.custom-toggle-icon');
                    
                    if ($sectionToggle.hasClass('expanded')) {
                        $sectionToggle.removeClass('expanded');
                        $sectionToggle.attr('aria-expanded', 'false');
                        $sectionContent.hide();
                        
                        // Change icon from arrow-down to arrow-right
                        $icon.removeClass('dashicons-arrow-down').addClass('dashicons-arrow-right');
                    }
                });
                
                // Update button state to collapsed - support both Classic and Modern UI
                $button.removeClass('ld-expanded');
                $button.attr('aria-expanded', 'false');
                
                // Update button text - support both Classic and Modern UI
                var $textElement = $button.find('.ld-text, .ld-accordion__expand-button-text');
                $textElement.text($button.data('ld-expand-text') || 'Expand All');
            }
            
            return false;
        });
    }
    
    function initAllContentBehavior($mainExpandButton) {
        // ALL CONTENT BEHAVIOR - INTERCEPT BEFORE LearnDash processes (like PR #3)
        // This is the key difference - we need to run BEFORE LearnDash, not after
        
        $mainExpandButton.on('click.customSectionIntercept', function(e) {
            // Don't prevent default - let LearnDash handle its own content after we're done
            // Don't stop propagation - let LearnDash's handler run too
            
            var $button = $(this);
            // Check for both Classic UI (ld-expanded) and Modern UI (aria-expanded) states
            var isCurrentlyExpanded = $button.hasClass('ld-expanded') || $button.attr('aria-expanded') === 'true';
            

            
            // If we're about to expand (button is currently collapsed)
            if (!isCurrentlyExpanded) {

                
                // FIRST: Expand all sections immediately BEFORE LearnDash processes
                // Support both Classic and Modern UI
                $('.custom-section-toggle-btn, .custom-modern-section-toggle-btn').each(function() {
                    var $sectionToggle = $(this);
                    var sectionId = $sectionToggle.data('custom-section-id');
                    var $sectionContent;
                    
                    // Determine content selector based on UI type
                    if ($sectionToggle.hasClass('custom-modern-section-toggle-btn')) {
                        $sectionContent = $('#custom-modern-section-content-' + sectionId);
                    } else {
                        $sectionContent = $('#custom-section-content-' + sectionId);
                    }
                    
                    var $icon = $sectionToggle.find('.custom-toggle-icon');
                    
                    if (!$sectionToggle.hasClass('expanded')) {
                        $sectionToggle.addClass('expanded');
                        $sectionToggle.attr('aria-expanded', 'true');
                        $sectionContent.show();
                        
                        // Change icon from arrow-right to arrow-down
                        $icon.removeClass('dashicons-arrow-right').addClass('dashicons-arrow-down');
                    }
                });
                

            } else {

            }
        });
        
        // ALSO watch for state changes to sync collapse (using MutationObserver like PR #3)
        var observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && (mutation.attributeName === 'class' || mutation.attributeName === 'aria-expanded')) {
                    var $button = $(mutation.target);
                    
                    // Check for both Classic UI and Modern UI expand buttons
                    if ($button.attr('data-ld-expands') || $button.attr('data-ld-expand-button')) {
                        var isExpanded = $button.hasClass('ld-expanded') || $button.attr('aria-expanded') === 'true';
                        
                        // Only handle collapse case here (expand is handled by click intercept)
                        if (!isExpanded) {

                            // Support both Classic and Modern UI
                            $('.custom-section-toggle-btn, .custom-modern-section-toggle-btn').each(function() {
                                var $sectionToggle = $(this);
                                var sectionId = $sectionToggle.data('custom-section-id');
                                var $sectionContent;
                                
                                // Determine content selector based on UI type
                                if ($sectionToggle.hasClass('custom-modern-section-toggle-btn')) {
                                    $sectionContent = $('#custom-modern-section-content-' + sectionId);
                                } else {
                                    $sectionContent = $('#custom-section-content-' + sectionId);
                                }
                                
                                var $icon = $sectionToggle.find('.custom-toggle-icon');
                                
                                if ($sectionToggle.hasClass('expanded')) {
                                    $sectionToggle.removeClass('expanded');
                                    $sectionToggle.attr('aria-expanded', 'false');
                                    $sectionContent.hide();
                                    
                                    // Change icon from arrow-down to arrow-right
                                    $icon.removeClass('dashicons-arrow-down').addClass('dashicons-arrow-right');
                                }
                            });
                        }
                    }
                }
            });
        });
        
        observer.observe($mainExpandButton[0], {
            attributes: true,
            attributeFilter: ['class', 'aria-expanded']
        });
    }
    
    // Handle window resize to ensure proper layout
    $(window).on('resize.customSectionToggle', function() {
        // Recalculate any necessary dimensions if needed
        // This is a placeholder for any responsive adjustments
    });
    
    // Optional: Save section state in localStorage (completely separate from LearnDash)
    function saveCustomSectionState(sectionId, isExpanded) {
        if (typeof(Storage) !== "undefined") {
            var courseId = $('.ld-item-list-items').attr('id');
            if (courseId) {
                var storageKey = 'custom_section_state_' + courseId;
                var sectionStates = JSON.parse(localStorage.getItem(storageKey) || '{}');
                sectionStates[sectionId] = isExpanded;
                localStorage.setItem(storageKey, JSON.stringify(sectionStates));
            }
        }
    }
    
    function loadCustomSectionState(sectionId) {
        if (typeof(Storage) !== "undefined") {
            var courseId = $('.ld-item-list-items').attr('id');
            if (courseId) {
                var storageKey = 'custom_section_state_' + courseId;
                var sectionStates = JSON.parse(localStorage.getItem(storageKey) || '{}');
                return sectionStates[sectionId] || false;
            }
        }
        return false;
    }
    
    // Uncomment the following lines if you want to persist section states
    /*
    // Load saved states on page load
    $('.custom-section-toggle-btn').each(function() {
        var $toggleBtn = $(this);
        var sectionId = $toggleBtn.data('custom-section-id');
        var $sectionContent = $('#custom-section-content-' + sectionId);
        var savedState = loadCustomSectionState(sectionId);
        
        if (savedState) {
            $toggleBtn.addClass('expanded');
            $toggleBtn.attr('aria-expanded', 'true');
            $sectionContent.show();
        }
    });
    
    // Save state when sections are toggled
    $(document).on('click.customSectionToggle', '.custom-section-toggle-btn', function() {
        var $toggleBtn = $(this);
        var sectionId = $toggleBtn.data('custom-section-id');
        var isExpanded = $toggleBtn.hasClass('expanded');
        saveCustomSectionState(sectionId, isExpanded);
    });
    */
});
