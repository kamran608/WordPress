/**
 * Swrice Plugin Page Manager - Admin JavaScript
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        // Initialize tab functionality
        initTabs();
        
        // Initialize character counters
        initCharacterCounters();
        
        // Initialize shortcode copy functionality
        initShortcodeCopy();
        
        // Initialize form validation
        initFormValidation();
        
        // Initialize tooltips
        initTooltips();
        
        // Initialize auto-save
        initAutoSave();
        
    });
    
    /**
     * Initialize tab functionality
     */
    function initTabs() {
        $('.sppm-tab-link').on('click', function(e) {
            e.preventDefault();
            
            const $this = $(this);
            const target = $this.attr('href');
            
            // Remove active class from all tabs and content
            $('.sppm-tab-link').removeClass('active');
            $('.sppm-tab-content').removeClass('active');
            
            // Add active class to clicked tab and corresponding content
            $this.addClass('active');
            $(target).addClass('active');
            
            // Save active tab in localStorage
            localStorage.setItem('sppm_active_tab', target);
        });
        
        // Restore active tab from localStorage
        const activeTab = localStorage.getItem('sppm_active_tab');
        if (activeTab && $(activeTab).length) {
            $('.sppm-tab-link[href="' + activeTab + '"]').click();
        }
    }
    
    /**
     * Initialize character counters
     */
    function initCharacterCounters() {
        // Meta title counter (recommended: 50-60 characters)
        $('#meta_title').on('input', function() {
            updateCharacterCounter($(this), 60, 50);
        });
        
        // Meta description counter (recommended: 150-160 characters)
        $('#meta_description').on('input', function() {
            updateCharacterCounter($(this), 160, 150);
        });
        
        // Hero subtitle counter
        $('#hero_subtitle').on('input', function() {
            updateCharacterCounter($(this), 200, 150);
        });
        
        // Initialize counters on page load
        $('#meta_title, #meta_description, #hero_subtitle').trigger('input');
    }
    
    /**
     * Update character counter
     */
    function updateCharacterCounter($field, maxLength, warningLength) {
        const currentLength = $field.val().length;
        let $counter = $field.siblings('.sppm-char-counter');
        
        if (!$counter.length) {
            $counter = $('<div class="sppm-char-counter"></div>');
            $field.after($counter);
        }
        
        $counter.text(currentLength + '/' + maxLength + ' characters');
        
        // Remove existing classes
        $counter.removeClass('warning error');
        
        // Add appropriate class based on length
        if (currentLength > maxLength) {
            $counter.addClass('error');
        } else if (currentLength > warningLength) {
            $counter.addClass('warning');
        }
    }
    
    /**
     * Initialize shortcode copy functionality
     */
    function initShortcodeCopy() {
        // Copy shortcode on click
        $(document).on('click', '.sppm-shortcode-display input, .column-shortcode code', function() {
            $(this).select();
            document.execCommand('copy');
            
            // Show success message
            showNotification('Shortcode copied to clipboard!', 'success');
        });
        
        // Add copy button to shortcode display
        $('.sppm-shortcode-display').each(function() {
            const $this = $(this);
            if (!$this.find('.sppm-copy-btn').length) {
                $this.append('<button type="button" class="button sppm-copy-btn">Copy</button>');
            }
        });
        
        // Handle copy button click
        $(document).on('click', '.sppm-copy-btn', function() {
            const $input = $(this).siblings('input');
            $input.select();
            document.execCommand('copy');
            
            const $btn = $(this);
            const originalText = $btn.text();
            $btn.text('Copied!').prop('disabled', true);
            
            setTimeout(function() {
                $btn.text(originalText).prop('disabled', false);
            }, 2000);
        });
    }
    
    /**
     * Initialize form validation
     */
    function initFormValidation() {
        // Validate required fields before submit
        $('#post').on('submit', function(e) {
            let hasErrors = false;
            const requiredFields = ['#meta_title', '#meta_description'];
            
            requiredFields.forEach(function(field) {
                const $field = $(field);
                if ($field.length && !$field.val().trim()) {
                    $field.addClass('error');
                    hasErrors = true;
                } else {
                    $field.removeClass('error');
                }
            });
            
            if (hasErrors) {
                e.preventDefault();
                showNotification('Please fill in all required fields.', 'error');
                
                // Switch to SEO tab if errors are there
                $('.sppm-tab-link[href="#tab-seo"]').click();
            }
        });
        
        // Remove error class on input
        $(document).on('input', '.error', function() {
            $(this).removeClass('error');
        });
    }
    
    /**
     * Initialize tooltips
     */
    function initTooltips() {
        // Add tooltips to form labels
        const tooltips = {
            'meta_title': 'The title that appears in search engine results. Keep it under 60 characters for best results.',
            'meta_description': 'The description that appears in search engine results. Keep it between 150-160 characters.',
            'meta_keywords': 'Keywords related to your plugin, separated by commas. Used by some search engines.',
            'plugin_price': 'The current selling price of your plugin (without currency symbol).',
            'plugin_original_price': 'The original price to show discounts. Leave empty if no discount.',
            'buy_now_shortcode': 'Paste your payment processor shortcode here (PayPal, Stripe, etc.).'
        };
        
        Object.keys(tooltips).forEach(function(fieldId) {
            const $label = $('label[for="' + fieldId + '"]');
            if ($label.length) {
                $label.addClass('sppm-tooltip').attr('data-tooltip', tooltips[fieldId]);
            }
        });
    }
    
    /**
     * Initialize auto-save functionality
     */
    function initAutoSave() {
        let autoSaveTimer;
        
        // Auto-save on input change
        $('.sppm-tab-content input, .sppm-tab-content textarea').on('input', function() {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(function() {
                autoSaveData();
            }, 5000); // Auto-save after 5 seconds of inactivity
        });
        
        // Save data to localStorage
        function autoSaveData() {
            const formData = {};
            $('.sppm-tab-content input, .sppm-tab-content textarea').each(function() {
                const $field = $(this);
                if ($field.attr('name')) {
                    formData[$field.attr('name')] = $field.val();
                }
            });
            
            localStorage.setItem('sppm_auto_save_' + $('#post_ID').val(), JSON.stringify(formData));
            showNotification('Draft saved automatically', 'info', 2000);
        }
        
        // Restore auto-saved data
        function restoreAutoSavedData() {
            const postId = $('#post_ID').val();
            const savedData = localStorage.getItem('sppm_auto_save_' + postId);
            
            if (savedData) {
                try {
                    const formData = JSON.parse(savedData);
                    Object.keys(formData).forEach(function(fieldName) {
                        const $field = $('[name="' + fieldName + '"]');
                        if ($field.length && !$field.val()) {
                            $field.val(formData[fieldName]);
                        }
                    });
                    
                    showNotification('Auto-saved data restored', 'info', 3000);
                } catch (e) {
                    console.log('Error restoring auto-saved data:', e);
                }
            }
        }
        
        // Restore on page load
        if ($('#post_ID').val()) {
            restoreAutoSavedData();
        }
        
        // Clear auto-save data on successful submit
        $('#post').on('submit', function() {
            const postId = $('#post_ID').val();
            localStorage.removeItem('sppm_auto_save_' + postId);
        });
    }
    
    /**
     * Show notification message
     */
    function showNotification(message, type, duration) {
        type = type || 'info';
        duration = duration || 4000;
        
        const $notification = $('<div class="sppm-notification sppm-notification-' + type + '">' + message + '</div>');
        
        $notification.css({
            'position': 'fixed',
            'top': '32px',
            'right': '20px',
            'background': type === 'success' ? '#00a32a' : type === 'error' ? '#dc3545' : '#2271b1',
            'color': 'white',
            'padding': '12px 20px',
            'border-radius': '6px',
            'z-index': '9999',
            'box-shadow': '0 5px 15px rgba(0,0,0,0.3)',
            'transform': 'translateX(100%)',
            'transition': 'transform 0.3s ease'
        });
        
        $('body').append($notification);
        
        // Animate in
        setTimeout(function() {
            $notification.css('transform', 'translateX(0)');
        }, 100);
        
        // Animate out and remove
        setTimeout(function() {
            $notification.css('transform', 'translateX(100%)');
            setTimeout(function() {
                $notification.remove();
            }, 300);
        }, duration);
    }
    
    /**
     * Initialize preview functionality
     */
    function initPreview() {
        // Add preview button
        if ($('#post_ID').val() && !$('.sppm-preview-btn').length) {
            const postId = $('#post_ID').val();
            const previewUrl = ajaxurl + '?action=sppm_preview&post_id=' + postId;
            const $previewBtn = $('<a href="' + previewUrl + '" target="_blank" class="sppm-preview-btn">Preview Plugin Page</a>');
            
            $('.sppm-quick-actions').append($previewBtn);
        }
    }
    
    initPreview();
    
    /**
     * Initialize media uploader for featured image
     */
    function initMediaUploader() {
        let mediaUploader;
        
        $(document).on('click', '.sppm-upload-image', function(e) {
            e.preventDefault();
            
            const $button = $(this);
            const $input = $button.siblings('input');
            const $preview = $button.siblings('.sppm-image-preview');
            
            // If the uploader object has already been created, reopen the dialog
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }
            
            // Create the media uploader
            mediaUploader = wp.media({
                title: 'Choose Plugin Image',
                button: {
                    text: 'Choose Image'
                },
                multiple: false
            });
            
            // When a file is selected, grab the URL and set it as the text field's value
            mediaUploader.on('select', function() {
                const attachment = mediaUploader.state().get('selection').first().toJSON();
                $input.val(attachment.url);
                $preview.html('<img src="' + attachment.url + '" style="max-width: 200px; height: auto;" />');
            });
            
            // Open the uploader dialog
            mediaUploader.open();
        });
        
        // Remove image
        $(document).on('click', '.sppm-remove-image', function(e) {
            e.preventDefault();
            
            const $button = $(this);
            const $input = $button.siblings('input');
            const $preview = $button.siblings('.sppm-image-preview');
            
            $input.val('');
            $preview.empty();
        });
    }
    
    initMediaUploader();
    
    /**
     * Initialize rich text editor for content areas
     */
    function initRichTextEditor() {
        // Initialize TinyMCE for large textarea fields
        const richTextFields = ['#plugin_features', '#plugin_testimonials', '#plugin_faq', '#plugin_bonuses'];
        
        richTextFields.forEach(function(fieldId) {
            const $field = $(fieldId);
            if ($field.length && typeof tinymce !== 'undefined') {
                // Add rich text toggle button
                const $toggleBtn = $('<button type="button" class="button sppm-rich-text-toggle">Rich Text</button>');
                $field.before($toggleBtn);
                
                $toggleBtn.on('click', function() {
                    const editorId = fieldId.replace('#', '');
                    
                    if (tinymce.get(editorId)) {
                        tinymce.get(editorId).remove();
                        $(this).text('Rich Text');
                    } else {
                        tinymce.init({
                            selector: fieldId,
                            height: 300,
                            menubar: false,
                            plugins: 'lists link image code',
                            toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist | link image | code'
                        });
                        $(this).text('Plain Text');
                    }
                });
            }
        });
    }
    
    // Initialize rich text editor if TinyMCE is available
    if (typeof tinymce !== 'undefined') {
        initRichTextEditor();
    }
    
    /**
     * Initialize Section Manager functionality
     */
    function initSectionManager() {
        // Initialize sortable functionality
        if ($('#sppm-section-sortable').length) {
            $('#sppm-section-sortable').sortable({
                handle: '.sppm-section-handle',
                placeholder: 'ui-sortable-placeholder',
                cursor: 'move',
                tolerance: 'pointer',
                opacity: 0.8,
                start: function(event, ui) {
                    ui.placeholder.height(ui.item.height());
                },
                update: function(event, ui) {
                    // Update the hidden inputs with new order
                    updateSectionOrder();
                    
                    // Show save notification
                    showSaveNotification('Section order updated. Don\'t forget to save your changes!');
                }
            });
        }
        
        // Handle toggle switches
        $('.sppm-toggle-switch input').on('change', function() {
            const $this = $(this);
            const sectionName = $this.attr('name').match(/\[(.*?)\]/)[1];
            const isEnabled = $this.is(':checked');
            
            // Visual feedback
            const $sectionItem = $this.closest('.sppm-section-item');
            if (isEnabled) {
                $sectionItem.removeClass('sppm-section-disabled');
            } else {
                $sectionItem.addClass('sppm-section-disabled');
            }
            
            // Show save notification
            const status = isEnabled ? 'enabled' : 'disabled';
            showSaveNotification(`Section "${sectionName}" ${status}. Don't forget to save your changes!`);
        });
    }
    
    /**
     * Update section order hidden inputs
     */
    function updateSectionOrder() {
        $('#sppm-section-sortable .sppm-section-item').each(function(index) {
            const sectionKey = $(this).data('section');
            $(this).find('input[name="section_order[]"]').val(sectionKey);
        });
    }
    
    /**
     * Show save notification
     */
    function showSaveNotification(message) {
        // Remove existing notifications
        $('.sppm-save-notification').remove();
        
        // Create notification
        const $notification = $('<div class="sppm-save-notification">' + message + '</div>');
        
        // Add to page
        $('.sppm-section-manager').prepend($notification);
        
        // Auto-hide after 3 seconds
        setTimeout(function() {
            $notification.fadeOut(300, function() {
                $(this).remove();
            });
        }, 3000);
    }
    
    // Initialize section manager when document is ready
    $(document).ready(function() {
        initSectionManager();
    });
    
})(jQuery);
