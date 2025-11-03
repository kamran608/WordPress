/**
 * Collapsible Sections for LearnDash - Admin JavaScript
 * 
 * @package CollapsibleSectionsLearnDash
 * @version 1.0
 */

jQuery(document).ready(function($) {
    'use strict';
    
    // Initialize color pickers
    initColorPickers();
    
    // Initialize form handlers
    initFormHandlers();
    
    /**
     * Initialize WordPress color pickers
     */
    function initColorPickers() {
        $('.csld-color-picker').wpColorPicker({
            change: function(event, ui) {
                // Optional: Add real-time preview functionality here
                var $input = $(this);
                var color = ui.color.toString();
                $input.val(color);
            },
            clear: function() {
                // Handle color clear
                var $input = $(this);
                var defaultColor = $input.data('default-color');
                if (defaultColor) {
                    $input.val(defaultColor);
                }
            }
        });
    }
    
    /**
     * Initialize form handlers
     */
    function initFormHandlers() {
        // Save settings form
        $('#csld-settings-form').on('submit', function(e) {
            e.preventDefault();
            saveSettings();
        });
        
        // Reset settings button
        $('#csld-reset-settings').on('click', function(e) {
            e.preventDefault();
            resetSettings();
        });
    }
    
    /**
     * Save settings via AJAX
     */
    function saveSettings() {
        var $form = $('#csld-settings-form');
        var $saveButton = $('#csld-save-settings');
        var $message = $('#csld-save-message');
        
        // Show loading state
        $saveButton.prop('disabled', true).text(csld_admin.saving_text);
        $form.addClass('csld-loading');
        
        // Prepare form data
        var formData = {
            action: 'csld_save_settings',
            nonce: $('#csld_nonce').val(),
            enable_plugin: $('#enable_plugin').is(':checked') ? 'yes' : 'no',
            toggler_outer_color: $('#toggler_outer_color').val(),
            toggler_inner_color: $('#toggler_inner_color').val(),
            section_background_color: $('#section_background_color').val(),
            section_border_color: $('#section_border_color').val(),
            expand_collapse_behavior: $('#expand_collapse_behavior').val()
        };
        
        // Send AJAX request
        $.ajax({
            url: csld_admin.ajax_url,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    showMessage('Settings saved successfully!', 'success');
                } else {
                    showMessage('Error saving settings: ' + (response.data || 'Unknown error'), 'error');
                }
            },
            error: function(xhr, status, error) {
                showMessage('AJAX error: ' + error, 'error');
            },
            complete: function() {
                // Remove loading state
                $saveButton.prop('disabled', false).text('Save Settings');
                $form.removeClass('csld-loading');
            }
        });
    }
    
    /**
     * Reset settings to defaults
     */
    function resetSettings() {
        if (!confirm('Are you sure you want to reset all settings to their default values?')) {
            return;
        }
        
        var $form = $('#csld-settings-form');
        var $resetButton = $('#csld-reset-settings');
        var $message = $('#csld-save-message');
        
        // Show loading state
        $resetButton.prop('disabled', true).text('Resetting...');
        $form.addClass('csld-loading');
        
        // Prepare default values
        var defaultData = {
            action: 'csld_save_settings',
            nonce: $('#csld_nonce').val(),
            enable_plugin: 'yes',
            toggler_outer_color: '#093b7d',
            toggler_inner_color: '#a3a5a9',
            section_background_color: '#ffffff',
            section_border_color: '#e2e7ed',
            expand_collapse_behavior: 'all_content'
        };
        
        // Send AJAX request to save defaults
        $.ajax({
            url: csld_admin.ajax_url,
            type: 'POST',
            data: defaultData,
            success: function(response) {
                if (response.success) {
                    // Update toggle switch to default (enabled)
                    $('#enable_plugin').prop('checked', true);
                    
                    // Update color pickers to default values
                    $('#toggler_outer_color').wpColorPicker('color', '#093b7d');
                    $('#toggler_inner_color').wpColorPicker('color', '#a3a5a9');
                    $('#section_background_color').wpColorPicker('color', '#ffffff');
                    $('#section_border_color').wpColorPicker('color', '#e2e7ed');
                    
                    // Update dropdown to default value
                    $('#expand_collapse_behavior').val('all_content');
                    
                    showMessage('Settings reset to defaults and saved successfully!', 'success');
                } else {
                    showMessage('Error resetting settings: ' + (response.data || 'Unknown error'), 'error');
                }
            },
            error: function(xhr, status, error) {
                showMessage('Error resetting settings: ' + error, 'error');
            },
            complete: function() {
                // Remove loading state
                $resetButton.prop('disabled', false).text('Reset to Defaults');
                $form.removeClass('csld-loading');
            }
        });
    }
    
    /**
     * Show admin message
     */
    function showMessage(message, type) {
        var $message = $('#csld-save-message');
        var messageClass = type === 'error' ? 'notice-error' : 'notice-success';
        
        $message
            .removeClass('notice-success notice-error')
            .addClass(messageClass)
            .find('p')
            .text(message);
        
        $message.show();
        
        // Auto-hide after 5 seconds
        setTimeout(function() {
            $message.fadeOut();
        }, 5000);
        
        // Scroll to message
        $('html, body').animate({
            scrollTop: $message.offset().top - 100
        }, 500);
    }
    
    /**
     * Handle dismissible notices
     */
    $(document).on('click', '.notice-dismiss', function() {
        $(this).parent().fadeOut();
    });
    
    /**
     * Add confirmation for potentially destructive actions
     */
    $('.csld-destructive-action').on('click', function(e) {
        var confirmMessage = $(this).data('confirm') || 'Are you sure you want to perform this action?';
        if (!confirm(confirmMessage)) {
            e.preventDefault();
            return false;
        }
    });
    
    /**
     * Initialize tooltips (if needed)
     */
    function initTooltips() {
        $('[data-tooltip]').each(function() {
            var $element = $(this);
            var tooltipText = $element.data('tooltip');
            
            $element.attr('title', tooltipText);
        });
    }
    
    // Initialize tooltips
    initTooltips();
    
    /**
     * Handle tab navigation (if we add tabs in the future)
     */
    function initTabs() {
        $('.csld-tab-nav a').on('click', function(e) {
            e.preventDefault();
            
            var $tab = $(this);
            var targetTab = $tab.attr('href');
            
            // Update active tab
            $('.csld-tab-nav a').removeClass('nav-tab-active');
            $tab.addClass('nav-tab-active');
            
            // Show target content
            $('.csld-tab-content').hide();
            $(targetTab).show();
        });
    }
    
    // Initialize tabs if they exist
    if ($('.csld-tab-nav').length) {
        initTabs();
    }
});
