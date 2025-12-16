(function( $ ) {

    // Current user field
    $('#gamipress_progress_current_user').on('change', function() {
        var target = $(this).closest('.cmb-row').next(); // User ID field

        if( $(this).prop('checked') ) {
            target.slideUp().addClass('cmb2-tab-ignore');
        } else {
            if( target.closest('.cmb-tabs-wrap').length ) {
                // Just show if item tab is active
                if( target.hasClass('cmb-tab-active-item') ) {
                    target.slideDown();
                }
            } else {
                target.slideDown();
            }

            target.removeClass('cmb2-tab-ignore');
        }
    });

    // from field
    $('#gamipress_progress_from').on('change', function() {
        var from = $(this).val();

        var fields = {
            'points': $('.cmb2-id-gamipress-progress-points'),
            'points_type': $('.cmb2-id-gamipress-progress-points-type'),
            'achievement_type': $('.cmb2-id-gamipress-progress-achievement-type'),
            'achievement': $('.cmb2-id-gamipress-progress-achievement'),
            'rank_type': $('.cmb2-id-gamipress-progress-rank-type'),
            'rank': $('.cmb2-id-gamipress-progress-rank'),
            'current_rank': $('.cmb2-id-gamipress-progress-rank-type'),
        };

        var fields_to_show = [ from ];

        if( from === 'points' )
            fields_to_show = [ 'points', 'points_type' ];

        // Hide fields
        Object.keys(fields).forEach(function( field ) {
            if( fields_to_show.indexOf( field ) === -1 ) {
                fields[field].slideUp().addClass('cmb2-tab-ignore');
            }
        });

        // Show fields
        fields_to_show.forEach(function( field ) {

            var target = fields[field];

            if( target !== undefined ) {

                if( target.closest('.cmb-tabs-wrap').length ) {
                    // Just show if item tab is active
                    if( target.hasClass('cmb-tab-active-item') ) {
                        target.slideDown();
                    }
                } else {
                    target.slideDown();
                }

                target.removeClass('cmb2-tab-ignore');

            }
        });

        // Hide style tab
        if( from === 'points_type'
            || from === 'achievement'
            || from === 'rank'
            || from === 'current_rank' ) {
            $('#gamipress_progress_box-tab-gamipress_progress_style').slideUp();
        } else {
            $('#gamipress_progress_box-tab-gamipress_progress_style').slideDown();
        }

    });

    $('#gamipress_progress_from').trigger('change');

    // Parse [gamipress_progress] atts
    $('body').on( 'gamipress_shortcode_attributes', '#gamipress_progress_wrapper', function( e, args ) {

        // From fields
        var fields = [
            'points',
            'points_type',
            'achievement_type',
            'achievement',
            'rank_type',
            'rank',
        ];

        var fields_to_show = [ args.attributes.from ];

        if( args.attributes.from === 'points' )
            fields_to_show = [ 'points', 'points_type' ];

        var fields_to_remove = [];

        // Add to fields to remove the fields that aren't to be shown
        fields.forEach(function( field ) {
            if( fields_to_show.indexOf( field ) === -1 ) {
                fields_to_remove = fields_to_remove.concat( field );
            }
        });

        // Progress fields

        // Text fields
        var text_fields = ['text_pattern'];

        // Bar fields
        var bar_fields = ['bar_color',
            'bar_background_color',
            'bar_text',
            'bar_text_color',
            'bar_text_format',
            'bar_stripe',
            'bar_animate'];

        // Radial bar fields
        var radial_bar_fields = ['radial_bar_color',
            'radial_bar_background_color',
            'radial_bar_text',
            'radial_bar_text_color',
            'radial_bar_text_format',
            'radial_bar_text_background_color',
            'radial_bar_size'];

        // Image fields
        var image_fields = ['image_complete',
            'image_complete_size',
            'image_complete_width',
            'image_complete_height',
            'image_incomplete',
            'image_incomplete_size',
            'image_incomplete_width',
            'image_incomplete_height'];

        if( args.attributes.from === 'points_type'
            || args.attributes.from === 'achievement'
            || args.attributes.from === 'rank'
            || args.attributes.from === 'current_rank' ) {
            // Remove all progress fields if from is setup to one of this options
            fields_to_remove = fields_to_remove.concat(
                ['type'],
                text_fields,
                bar_fields,
                radial_bar_fields,
                image_fields
            );
        } else {
            // Fields to remove based on type attribute value
            switch( args.attributes.type ) {
                case 'text':
                    fields_to_remove = fields_to_remove.concat(
                        //text_fields,
                        bar_fields,
                        radial_bar_fields,
                        image_fields
                    );
                    break;
                case 'bar':
                    fields_to_remove = fields_to_remove.concat(
                        text_fields,
                        //bar_fields,
                        radial_bar_fields,
                        image_fields
                    );
                    break;
                case 'radial-bar':
                    fields_to_remove = fields_to_remove.concat(
                        text_fields,
                        bar_fields,
                        //radial_bar_fields,
                        image_fields
                    );
                    break;
                case 'image':
                    fields_to_remove = fields_to_remove.concat(
                        text_fields,
                        bar_fields,
                        radial_bar_fields,
                        //image_fields
                    );
                    break;
            }
        }

        // Remove non-required attributes
        fields_to_remove.forEach(function( field ) {
            delete args.attributes[field];
        });

    } );

})( jQuery );