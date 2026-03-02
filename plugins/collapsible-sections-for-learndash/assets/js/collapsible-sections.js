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
    
    // Debug logging to track section expansion issues
    console.log('🔍 CSLD: Document ready, initializing section toggles...');
    
    // Initialize custom section toggles immediately
    initCustomSectionToggles();
    
    // Add multiple initialization attempts to handle LearnDash Modern UI interference
    setTimeout(function() {
        console.log('🕐 CSLD: Re-initializing after 500ms (LearnDash Modern UI protection)...');
        initCustomSectionToggles();
    }, 500);
    
    setTimeout(function() {
        console.log('🕐 CSLD: Re-initializing after 1000ms (LearnDash Modern UI protection)...');
        initCustomSectionToggles();
    }, 1000);
    
    setTimeout(function() {
        console.log('🕐 CSLD: Final re-initialization after 2000ms...');
        initCustomSectionToggles();
        monitorSectionChanges();
    }, 2000);
    
    function monitorSectionChanges() {
        // Monitor all section toggle buttons for unexpected changes
        $('.custom-section-toggle-btn, .custom-modern-section-toggle-btn').each(function() {
            var $toggleBtn = $(this);
            var sectionId = $toggleBtn.data('custom-section-id');
            var initialState = $toggleBtn.hasClass('expanded');
            
            console.log('📊 CSLD: Monitoring section ' + sectionId + ' - initial state:', initialState);
            
            // Use MutationObserver to detect and fix interference
            var observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'attributes' && 
                        (mutation.attributeName === 'class' || mutation.attributeName === 'aria-expanded')) {
                        var currentState = $toggleBtn.hasClass('expanded');
                        var shouldExpand = $toggleBtn.data('should-expand') === true || $toggleBtn.data('should-expand') === 'true';
                        
                        // Check if the state was changed incorrectly
                        if (shouldExpand && !currentState) {
                            console.log('🚨 CSLD: INTERFERENCE DETECTED! Section ' + sectionId + ' should be expanded but was collapsed by external code - FIXING...');
                            
                            // Fix the state immediately
                            setTimeout(function() {
                                validateSingleSectionState($toggleBtn);
                            }, 10); // Small delay to let the interfering code finish
                        } else if (!shouldExpand && currentState) {
                            console.log('🚨 CSLD: INTERFERENCE DETECTED! Section ' + sectionId + ' should be collapsed but was expanded by external code - FIXING...');
                            
                            // Fix the state immediately
                            setTimeout(function() {
                                validateSingleSectionState($toggleBtn);
                            }, 10);
                        }
                        
                        initialState = currentState; // Update for next comparison
                    }
                });
            });
            
            observer.observe($toggleBtn[0], {
                attributes: true,
                attributeFilter: ['class', 'aria-expanded']
            });
        });
    }
    
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
            
            // Skip if already initialized (prevent duplicate event handlers)
            if ($toggleBtn.data('csld-initialized')) {
                console.log('🔄 CSLD: Section ' + sectionId + ' already initialized, re-validating state...');
                // Just re-validate the state without adding new event handlers
                validateSingleSectionState($toggleBtn);
                return;
            }
            
            // Determine content selector based on UI type
            if ($toggleBtn.hasClass('custom-modern-section-toggle-btn')) {
                // Modern UI - content selector
                $sectionContent = $('#custom-modern-section-content-' + sectionId);
            } else {
                // Classic UI - content selector
                $sectionContent = $('#custom-section-content-' + sectionId);
            }
            
            // Read PHP-determined state from DOM data attributes
            var isFirstSection = $toggleBtn.data('is-first-section') === true || $toggleBtn.data('is-first-section') === 'true';
            var shouldExpand = $toggleBtn.data('should-expand') === true || $toggleBtn.data('should-expand') === 'true';
            var isAlreadyExpanded = $toggleBtn.hasClass('expanded') || $toggleBtn.attr('aria-expanded') === 'true';
            
            // Debug logging for each section
            console.log('🔍 CSLD: Section ' + sectionId + ' - isFirstSection:', isFirstSection, 
                       'shouldExpand:', shouldExpand, 'isAlreadyExpanded:', isAlreadyExpanded);
            
            if (shouldExpand) {
                // PHP determined this section should be expanded - ensure it is
                console.log('✅ CSLD: Section ' + sectionId + ' - Expanding (PHP-determined)');
                $toggleBtn.addClass('expanded');
                $toggleBtn.attr('aria-expanded', 'true');
                
                if ($toggleBtn.hasClass('custom-modern-section-toggle-btn')) {
                    // Modern UI uses CSS classes
                    $sectionContent.addClass('expanded');
                } else {
                    // Classic UI uses jQuery show/hide
                    $sectionContent.show();
                }
                
                // Ensure icon shows arrow-down (expanded state)
                var $icon = $toggleBtn.find('.custom-toggle-icon');
                $icon.removeClass('dashicons-arrow-right').addClass('dashicons-arrow-down');
            } else {
                // PHP determined this section should be collapsed - ensure it is
                console.log('❌ CSLD: Section ' + sectionId + ' - Collapsing (PHP-determined)');
                $toggleBtn.removeClass('expanded');
                $toggleBtn.attr('aria-expanded', 'false');
                
                if ($toggleBtn.hasClass('custom-modern-section-toggle-btn')) {
                    // Modern UI uses CSS classes
                    $sectionContent.removeClass('expanded');
                } else {
                    // Classic UI uses jQuery show/hide
                    $sectionContent.hide();
                }
                
                // Ensure icon shows arrow-right (collapsed state)
                var $icon = $toggleBtn.find('.custom-toggle-icon');
                $icon.removeClass('dashicons-arrow-down').addClass('dashicons-arrow-right');
            }
            
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
            
            // Mark this section as initialized
            $toggleBtn.data('csld-initialized', true);
        });
        
        // Add state validation to ensure exactly one section is expanded
        validateAccordionState();
    }
    
    function validateSingleSectionState($toggleBtn) {
        var sectionId = $toggleBtn.data('custom-section-id');
        var shouldExpand = $toggleBtn.data('should-expand') === true || $toggleBtn.data('should-expand') === 'true';
        var isCurrentlyExpanded = $toggleBtn.hasClass('expanded');
        
        console.log('🔍 CSLD: Re-validating section ' + sectionId + ' - shouldExpand:', shouldExpand, 'isCurrentlyExpanded:', isCurrentlyExpanded);
        
        // Get content element
        var $sectionContent = $toggleBtn.hasClass('custom-modern-section-toggle-btn') 
            ? $('#custom-modern-section-content-' + sectionId)
            : $('#custom-section-content-' + sectionId);
        
        if (shouldExpand && !isCurrentlyExpanded) {
            // Should be expanded but isn't - fix it
            console.log('⚠️ CSLD: Section ' + sectionId + ' should be expanded but was collapsed - fixing...');
            $toggleBtn.addClass('expanded');
            $toggleBtn.attr('aria-expanded', 'true');
            
            if ($toggleBtn.hasClass('custom-modern-section-toggle-btn')) {
                $sectionContent.addClass('expanded');
            } else {
                $sectionContent.show();
            }
            
            // Update icon
            var $icon = $toggleBtn.find('.custom-toggle-icon');
            $icon.removeClass('dashicons-arrow-right').addClass('dashicons-arrow-down');
        } else if (!shouldExpand && isCurrentlyExpanded) {
            // Should be collapsed but isn't - fix it
            console.log('⚠️ CSLD: Section ' + sectionId + ' should be collapsed but was expanded - fixing...');
            $toggleBtn.removeClass('expanded');
            $toggleBtn.attr('aria-expanded', 'false');
            
            if ($toggleBtn.hasClass('custom-modern-section-toggle-btn')) {
                $sectionContent.removeClass('expanded');
            } else {
                $sectionContent.hide();
            }
            
            // Update icon
            var $icon = $toggleBtn.find('.custom-toggle-icon');
            $icon.removeClass('dashicons-arrow-down').addClass('dashicons-arrow-right');
        }
    }
    
    function validateAccordionState() {
        var $allToggleBtns = $('.custom-section-toggle-btn, .custom-modern-section-toggle-btn');
        var expandedSections = $allToggleBtns.filter('.expanded');
        var sectionsToExpand = $allToggleBtns.filter('[data-should-expand="true"]');
        
        console.log('🔍 CSLD: State validation - found', expandedSections.length, 'expanded sections,', sectionsToExpand.length, 'should be expanded');
        
        if (expandedSections.length === 0 && sectionsToExpand.length > 0) {
            // No sections expanded but some should be - expand the first one that should be expanded
            var $firstSection = sectionsToExpand.first();
            
            if ($firstSection.length > 0) {
                console.log('⚠️ CSLD: No sections expanded but setting enabled - expanding first section');
                $firstSection.addClass('expanded');
                $firstSection.attr('aria-expanded', 'true');
                
                var sectionId = $firstSection.data('custom-section-id');
                var $content = $firstSection.hasClass('custom-modern-section-toggle-btn') 
                    ? $('#custom-modern-section-content-' + sectionId)
                    : $('#custom-section-content-' + sectionId);
                
                if ($firstSection.hasClass('custom-modern-section-toggle-btn')) {
                    $content.addClass('expanded');
                } else {
                    $content.show();
                }
                
                // Update icon
                var $icon = $firstSection.find('.custom-toggle-icon');
                $icon.removeClass('dashicons-arrow-right').addClass('dashicons-arrow-down');
            }
        } else if (expandedSections.length === 0 && sectionsToExpand.length === 0) {
            // No sections expanded and none should be expanded (setting disabled) - this is correct
            console.log('✅ CSLD: Setting disabled - all sections correctly collapsed');
        } else if (expandedSections.length > 1) {
            // Multiple sections expanded - collapse all except the first one
            console.log('⚠️ CSLD: Multiple sections expanded - keeping only the first');
            expandedSections.slice(1).each(function() {
                var $btn = $(this);
                $btn.removeClass('expanded');
                $btn.attr('aria-expanded', 'false');
                
                var sectionId = $btn.data('custom-section-id');
                var $content = $btn.hasClass('custom-modern-section-toggle-btn') 
                    ? $('#custom-modern-section-content-' + sectionId)
                    : $('#custom-section-content-' + sectionId);
                
                if ($btn.hasClass('custom-modern-section-toggle-btn')) {
                    $content.removeClass('expanded');
                } else {
                    $content.hide();
                }
                
                // Update icon to collapsed state
                var $icon = $btn.find('.custom-toggle-icon');
                $icon.removeClass('dashicons-arrow-down').addClass('dashicons-arrow-right');
            });
        }
    }
    
    function toggleCustomSection($toggleBtn, $sectionContent) {
        var isExpanded = $toggleBtn.hasClass('expanded');
        var $icon = $toggleBtn.find('.custom-toggle-icon');
        var isModernUI = $toggleBtn.hasClass('custom-modern-section-toggle-btn');
        
        if (isExpanded) {
            // Collapse section
            $toggleBtn.removeClass('expanded');
            $toggleBtn.attr('aria-expanded', 'false');
            
            if (isModernUI) {
                // Modern UI uses CSS classes
                $sectionContent.removeClass('expanded');
            } else {
                // Classic UI uses jQuery show/hide
                $sectionContent.hide();
            }
            
            // Change icon from arrow-down to arrow-right
            $icon.removeClass('dashicons-arrow-down').addClass('dashicons-arrow-right');
        } else {
            // Expand section
            $toggleBtn.addClass('expanded');
            $toggleBtn.attr('aria-expanded', 'true');
            
            if (isModernUI) {
                // Modern UI uses CSS classes
                $sectionContent.addClass('expanded');
            } else {
                // Classic UI uses jQuery show/hide
                $sectionContent.show();
            }
            
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
            console.log('🔍 CSLD: Found main expand button:', $mainExpandButton[0]);
            
            // Monitor the main expand button for any changes
            var expandButtonObserver = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'attributes') {
                        var isExpanded = $mainExpandButton.hasClass('ld-expanded') || 
                                       $mainExpandButton.attr('aria-expanded') === 'true';
                        console.log('🔄 CSLD: Main expand button state changed - expanded:', isExpanded, 
                                   'attribute:', mutation.attributeName);
                    }
                });
            });
            
            expandButtonObserver.observe($mainExpandButton[0], {
                attributes: true,
                attributeFilter: ['class', 'aria-expanded']
            });
            // Get the expand/collapse behavior setting
            var expandBehavior = (typeof csld_settings !== 'undefined' && csld_settings.expand_collapse_behavior) 
                ? csld_settings.expand_collapse_behavior 
                : 'all_content';
            
            if (expandBehavior === 'sections_only') {
                // SECTIONS ONLY BEHAVIOR - Current working implementation
                initSectionsOnlyBehavior($mainExpandButton);
            } else {
                // ALL CONTENT BEHAVIOR - But be conservative to prevent unwanted expansions
                // Since PHP now handles first section expansion, we only need to handle user clicks
                initConservativeAllContentBehavior($mainExpandButton);
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
                        
                        if ($sectionToggle.hasClass('custom-modern-section-toggle-btn')) {
                            // Modern UI uses CSS classes
                            $sectionContent.addClass('expanded');
                        } else {
                            // Classic UI uses jQuery show/hide
                            $sectionContent.show();
                        }
                        
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
                        
                        if ($sectionToggle.hasClass('custom-modern-section-toggle-btn')) {
                            // Modern UI uses CSS classes
                            $sectionContent.removeClass('expanded');
                        } else {
                            // Classic UI uses jQuery show/hide
                            $sectionContent.hide();
                        }
                        
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
    
    function initConservativeAllContentBehavior($mainExpandButton) {
        // CONSERVATIVE ALL CONTENT BEHAVIOR
        // Since PHP now handles first section expansion, we only respond to explicit user clicks
        // This prevents automatic expansions that were causing the second section to expand
        
        $mainExpandButton.on('click.customSectionIntercept', function(e) {
            // Only respond to direct user clicks, not programmatic triggers
            if (!e.isTrusted) {
                console.log('🚫 CSLD: Ignoring programmatic click on expand button');
                return; // Ignore programmatic clicks
            }
            
            console.log('👆 CSLD: User clicked expand all button');
            
            var $button = $(this);
            // Check for both Classic UI (ld-expanded) and Modern UI (aria-expanded) states
            var isCurrentlyExpanded = $button.hasClass('ld-expanded') || $button.attr('aria-expanded') === 'true';
            
            console.log('🔍 CSLD: Expand button state - isCurrentlyExpanded:', isCurrentlyExpanded);
            
            // If user is clicking to expand all content
            if (!isCurrentlyExpanded) {
                // Expand all sections when user explicitly clicks "Expand All"
                console.log('🚀 CSLD: Expanding all sections (user requested)');
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
                        
                        if ($sectionToggle.hasClass('custom-modern-section-toggle-btn')) {
                            // Modern UI uses CSS classes
                            $sectionContent.addClass('expanded');
                        } else {
                            // Classic UI uses jQuery show/hide
                            $sectionContent.show();
                        }
                        
                        // Change icon from arrow-right to arrow-down
                        $icon.removeClass('dashicons-arrow-right').addClass('dashicons-arrow-down');
                    }
                });
            }
            // Note: We don't handle collapse here - let LearnDash handle that naturally
        });
        
        // Remove the MutationObserver entirely to prevent automatic responses
        // This eliminates the source of unwanted section expansions
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
