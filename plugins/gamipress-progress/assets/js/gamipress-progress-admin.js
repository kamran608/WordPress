(function( $ ) {
    var prefix = '.gamipress-progress-';

    // Text fields
    var text_selector = prefix + 'text-pattern';

    // Bar fields
    var bar_selector = prefix + 'bar-color, '
        + prefix + 'bar-background-color, '
        + prefix + 'bar-text, '
        + prefix + 'bar-text-color, '
        + prefix + 'bar-text-format, '
        + prefix + 'bar-stripe, '
        + prefix + 'bar-animate';

    // Radial bar fields
    var radial_bar_selector = prefix + 'radial-bar-color, '
        + prefix + 'radial-bar-background-color, '
        + prefix + 'radial-bar-text, '
        + prefix + 'radial-bar-text-color, '
        + prefix + 'radial-bar-text-format, '
        + prefix + 'radial-bar-text-background-color, '
        + prefix + 'radial-bar-size';

    // Image fields
    var image_selector = prefix + 'image-complete, '
        + prefix + 'image-complete-size, '
        + prefix + 'image-complete-width, '
        + prefix + 'image-complete-height, '
        + prefix + 'image-incomplete, '
        + prefix + 'image-incomplete-size, '
        + prefix + 'image-incomplete-width, '
        + prefix + 'image-incomplete-height';

    // All fields
    var all_selector = text_selector + ', '
        + bar_selector  + ', '
        + radial_bar_selector  + ', '
        + image_selector;

    // Type change event
    $( prefix + 'type input').on('change', function(e) {

        var is_load = e.originalEvent === undefined;

        var $this = $(this);
        var type_el = $this.closest( prefix + 'type' );
        var type = $this.val();

        var inputs = {
            'text': type_el.siblings(text_selector),
            'bar': type_el.siblings(bar_selector),
            'radial-bar': type_el.siblings(radial_bar_selector),
            'image': type_el.siblings(image_selector),
        };

        // If there are tabs, just show if item tab is active
        var in_tabs = $this.closest('.cmb-tabs-wrap').length;
        var tab_active = ( in_tabs && $this.closest('.cmb-row').hasClass('cmb-tab-active-item') );

        Object.keys(inputs).forEach( function( value ) {

            var fields = inputs[value];

            if( type === value ) {

                var show = true;

                if( in_tabs && ! tab_active )
                    show = false;

                if( show ) {
                    // Show fields
                    if( is_load )
                        fields.show();
                    else
                        fields.slideDown();
                }

                fields.removeClass('cmb2-tab-ignore')
            } else {
                // Hide fields
                if( is_load )
                    fields.hide().addClass('cmb2-tab-ignore');
                else
                    fields.slideUp().addClass('cmb2-tab-ignore');
            }
        } );

        // Preview
        gamipress_progress_admin_update_preview( type_el.siblings( prefix + 'preview' ) );
    });

    // Force change event on type inputs to initialize shortcode editor inputs
    $( prefix + 'type').find('input:checked').trigger('change');

    // Toggle initial load
    $(prefix + 'toggle input').each(function() {
        var toggle_el = $(this).closest(prefix +'toggle');

        var target = toggle_el.siblings(
            prefix + 'preview, '
            + prefix + 'progress-calc, '
            + prefix + 'type '
        );

        var inputs = toggle_el.siblings(all_selector);

        if( $(this).prop('checked') ) {
            target.show();
            toggle_el.siblings( prefix + 'type').find('input:checked').trigger('change');
        } else {
            target.hide();
            inputs.hide();
        }
    });

    // Toogle change event
    $(prefix + 'toggle input').on('change', function() {
        var toggle_el = $(this).closest(prefix +'toggle');

        var target = toggle_el.siblings(
            prefix + 'preview, '
            + prefix + 'progress-calc, '
            + prefix + 'type '
        );

        var inputs = toggle_el.siblings(all_selector);

        if( $(this).prop('checked') ) {
            target.slideDown();
            toggle_el.siblings( prefix + 'type').find('input:checked').trigger('change');
        } else {
            target.slideUp();
            inputs.slideUp();
        }
    });

    // Live Preview events

    // KeyUp events
    $(    prefix + 'text-pattern input, '
        + prefix + 'radial-bar-size input, '
        + prefix + 'image-complete-size input, '
        + prefix + 'image-complete-width input, '
        + prefix + 'image-complete-height input, '
        + prefix + 'image-incomplete-size input, '
        + prefix + 'image-incomplete-width input, '
        + prefix + 'image-incomplete-height input'
    ).keyup( function() {
        gamipress_progress_admin_update_preview( $(this).closest( '.cmb-row' ).siblings( prefix + 'preview' ) );
    } );

    // Color pickers (timeout is to wait to wpColorPicker initialization)
    setTimeout( function() {
        $(    prefix + 'bar-color .wp-color-picker, '
            + prefix + 'bar-background-color .wp-color-picker, '
            + prefix + 'bar-text-color .wp-color-picker, '
            + prefix + 'radial-bar-color .wp-color-picker, '
            + prefix + 'radial-bar-background-color .wp-color-picker, '
            + prefix + 'radial-bar-text-color .wp-color-picker, '
            + prefix + 'radial-bar-text-background-color .wp-color-picker'
        ).wpColorPicker('option', 'change', function(e, ui) {
            gamipress_progress_admin_update_preview( $(e.target).closest( '.cmb-row' ).siblings( prefix + 'preview' ) );
        } );

    }, 1000 );

    // Change events
    $(    prefix + 'text-pattern input, '
        + prefix + 'bar-text input, '
        + prefix + 'bar-text-format input, '
        + prefix + 'bar-stripe input, '
        + prefix + 'bar-animate input, '
        + prefix + 'radial-bar-text input, '
        + prefix + 'radial-bar-text-format input, '
        + prefix + 'radial-bar-size input, '
        + prefix + 'image-complete input.cmb2-upload-file, '
        + prefix + 'image-complete input, '
        + prefix + 'image-complete-size input, '
        + prefix + 'image-complete-width input, '
        + prefix + 'image-complete-height input, '
        + prefix + 'image-incomplete input.cmb2-upload-file'
        + prefix + 'image-incomplete input, '
        + prefix + 'image-incomplete-size input, '
        + prefix + 'image-incomplete-width input, '
        + prefix + 'image-incomplete-height input'
    ).on('change', function() {
        gamipress_progress_admin_update_preview( $(this).closest( '.cmb-row' ).siblings( prefix + 'preview' ) );
    });

    // Property change events
    setInterval(function() {
        $(prefix + 'image-complete input.cmb2-upload-file, '
        + prefix + 'image-incomplete input.cmb2-upload-file').each( function() {
            gamipress_progress_observe_input_changes( $(this) );
        } );
    }, 500);


    // Update preview element
    function gamipress_progress_admin_update_preview( preview_el ) {

        var type = preview_el.siblings( prefix + 'type' ).find('input:checked').val();

        if( type === 'text' ) {
            // Text settings

            var pattern = preview_el.siblings(prefix + 'text-pattern').find('input').val();

            preview_el.find(prefix + 'preview-text').text( pattern.replace('{current}', '3').replace('{total}', '5') );

            preview_el.find(prefix + 'preview-text').show();
            preview_el.find(prefix + 'preview-bar').hide();
            preview_el.find(prefix + 'preview-radial-bar').hide();
            preview_el.find(prefix + 'preview-image').hide();

        } else if( type === 'bar' ) {
            // Bar settings

            var color = preview_el.siblings(prefix + 'bar-color').find('input.cmb2-colorpicker').val();
            var background = preview_el.siblings(prefix + 'bar-background-color').find('input.cmb2-colorpicker').val();
            var text = preview_el.siblings(prefix + 'bar-text').find('input').prop('checked');
            var text_color = preview_el.siblings(prefix + 'bar-text-color').find('input.cmb2-colorpicker').val();
            var text_format = preview_el.siblings(prefix + 'bar-text-format').find('input:checked').val();
            var stripe = preview_el.siblings(prefix + 'bar-stripe').find('input').prop('checked');
            var animate = preview_el.siblings(prefix + 'bar-animate').find('input').prop('checked');

            // Background color
            preview_el.find(prefix + 'preview-bar ' + prefix + 'bar').css({backgroundColor: background});

            // Bar color
            preview_el.find(prefix + 'preview-bar ' + prefix + 'bar-completed').css({backgroundColor: color});

            // Text style
            if( text ) {
                preview_el.find(prefix + 'preview-bar ' + prefix + 'bar-completed span').css({display: 'inline', color: text_color});
            } else {
                preview_el.find(prefix + 'preview-bar ' + prefix + 'bar-completed span').css({display: 'none', color: text_color});
            }

            // Text format
            preview_el.find(prefix + 'preview-bar ' + prefix + 'bar-completed span').text( ( text_format === 'percent' ? '60%' : '3/5' ) );

            // Stripe style
            if( stripe ) {
                preview_el.find(prefix + 'preview-bar ' + prefix + 'bar-completed').addClass('gamipress-progress-bar-striped');
            } else {
                preview_el.find(prefix + 'preview-bar ' + prefix + 'bar-completed').removeClass('gamipress-progress-bar-striped');
            }

            // Animate effect
            if( animate ) {
                preview_el.find(prefix + 'preview-bar ' + prefix + 'bar-completed').addClass('gamipress-progress-bar-animated');
            } else {
                preview_el.find(prefix + 'preview-bar ' + prefix + 'bar-completed').removeClass('gamipress-progress-bar-animated');
            }

            // Toggle previews visibility
            preview_el.find(prefix + 'preview-text').hide();
            preview_el.find(prefix + 'preview-bar').show();
            preview_el.find(prefix + 'preview-radial-bar').hide();
            preview_el.find(prefix + 'preview-image').hide();

        } else if( type === 'radial-bar' ) {
            // Radial bar settings

            var color = preview_el.siblings(prefix + 'radial-bar-color').find('input.cmb2-colorpicker').val();
            var background = preview_el.siblings(prefix + 'radial-bar-background-color').find('input.cmb2-colorpicker').val();
            var text = preview_el.siblings(prefix + 'radial-bar-text').find('input').prop('checked');
            var text_color = preview_el.siblings(prefix + 'radial-bar-text-color').find('input.cmb2-colorpicker').val();
            var text_format = preview_el.siblings(prefix + 'radial-bar-text-format').find('input:checked').val();
            var text_background = preview_el.siblings(prefix + 'radial-bar-text-background-color').find('input.cmb2-colorpicker').val();
            var size = preview_el.siblings(prefix + 'radial-bar-size').find('input').val();

            // Bar, Background color and size
            preview_el.find(prefix + 'preview-radial-bar ' + prefix + 'radial-bar').css({
                backgroundImage: 'linear-gradient(0deg, ' + color + ' 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(270deg, ' + color + ' 50%, ' + background + ' 50%, ' + background + ')',
                width: size + 'px',
                height: size + 'px'
            });

            // Text style
            preview_el.find(prefix + 'preview-radial-bar ' + prefix + 'radial-bar > span').css({
                backgroundColor: text_background
            });

            preview_el.find(prefix + 'preview-radial-bar ' + prefix + 'radial-bar > span > span').css({
                display: ( text ) ? 'inline' : 'none',
                color: text_color
            });

            // Text format
            preview_el.find(prefix + 'preview-radial-bar ' + prefix + 'radial-bar > span > span').text( ( text_format === 'percent' ? '60%' : '3/5' ) );

            // Size style
            if( stripe ) {
                preview_el.find(prefix + 'preview-radial-bar ' + prefix + 'bar-completed').addClass('gamipress-progress-bar-striped');
            } else {
                preview_el.find(prefix + 'preview-radial-bar ' + prefix + 'bar-completed').removeClass('gamipress-progress-bar-striped');
            }

            // Toggle previews visibility
            preview_el.find(prefix + 'preview-text').hide();
            preview_el.find(prefix + 'preview-bar').hide();
            preview_el.find(prefix + 'preview-radial-bar').show();
            preview_el.find(prefix + 'preview-image').hide();

        } else if( type === 'image' ) {
            // Image settings

            var complete = preview_el.siblings(prefix + 'image-complete');

            // Try to get complete image from a file input or fallback to a text field
            complete = ( complete.find('input.cmb2-upload-file').length ? complete.find('input.cmb2-upload-file').val() : complete.find('input').val() );

            var complete_size = {};

            if( preview_el.siblings(prefix + 'image-complete-size').length ) {
                // Size from complete size field
                complete_size = {
                    width: preview_el.siblings(prefix + 'image-complete-size').find('input[name$="[width]"]').val(),
                    height: preview_el.siblings(prefix + 'image-complete-size').find('input[name$="[height]"]').val()
                };
            } else {
                // Size from complete width and height fields
                complete_size = {
                    width: preview_el.siblings(prefix + 'image-complete-width').find('input').val(),
                    height: preview_el.siblings(prefix + 'image-complete-height').find('input').val()
                };
            }

            var incomplete = preview_el.siblings(prefix + 'image-incomplete');

            // Try to get incomplete image from a file input or fallback to a text field
            incomplete = ( incomplete.find('input.cmb2-upload-file').length ? incomplete.find('input.cmb2-upload-file').val() : incomplete.find('input').val() );


            var incomplete_size = {};

            if( preview_el.siblings(prefix + 'image-incomplete-size').length ) {
                // Size from incomplete size field
                incomplete_size = {
                    width: preview_el.siblings(prefix + 'image-incomplete-size').find('input[name$="[width]"]').val(),
                    height: preview_el.siblings(prefix + 'image-incomplete-size').find('input[name$="[height]"]').val()
                };
            } else {
                // Size from incomplete width and height fields
                incomplete_size = {
                    width: preview_el.siblings(prefix + 'image-incomplete-width').find('input').val(),
                    height: preview_el.siblings(prefix + 'image-incomplete-height').find('input').val()
                };
            }

            var complete_img = complete.length ? '<img src="' + complete + '" style="width: ' + complete_size.width + 'px; height: ' + complete_size.height + 'px;"/>' : '<div class="gamipress-progress-image-placeholder gamipress-progress-complete-image-placeholder"><i class="dashicons dashicons-camera"></i></div>';
            var incomplete_img = incomplete.length ? '<img src="' + incomplete + '" style="width: ' + incomplete_size.width + 'px; height: ' + incomplete_size.height + 'px;"/>' : '<div class="gamipress-progress-image-placeholder gamipress-progress-incomplete-image-placeholder"><i class="dashicons dashicons-camera"></i></div>';

            preview_el.find(prefix + 'preview-image').html( complete_img + complete_img + complete_img + incomplete_img + incomplete_img );

            preview_el.find(prefix + 'preview-text').hide();
            preview_el.find(prefix + 'preview-bar').hide();
            preview_el.find(prefix + 'preview-radial-bar').hide();
            preview_el.find(prefix + 'preview-image').show();
        }

    }

    // Change value listener
    function gamipress_progress_observe_input_changes( element ) {

        if( element.data('old-value') !== element.val() ) {
            element.trigger('change');

            element.data('old-value', element.val());
            return;
        }

    }
})( jQuery );