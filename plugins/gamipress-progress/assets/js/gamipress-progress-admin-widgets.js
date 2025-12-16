(function( $ ) {

    // Helper function to build a widget field selector
    function gamipress_progress_widget_field_selector( field ) {
        return '[id^="widget-gamipress_progress"][id$="[' + field + ']"]';
    }

    // from field
    $('body').on('change', 'select[id^="widget-gamipress_progress"][id$="[from]"]', function() {
        var from = $(this).val();

        var box = $(this).closest('.cmb2-metabox');

        var fields = {
            'points': box.find( gamipress_progress_widget_field_selector( 'points' ) ).closest('.cmb-row'),
            'points_type': box.find( gamipress_progress_widget_field_selector( 'points_type' ) ).closest('.cmb-row'),
            'achievement_type': box.find( gamipress_progress_widget_field_selector( 'achievement_type' ) ).closest('.cmb-row'),
            'achievement': box.find( gamipress_progress_widget_field_selector( 'achievement' ) ).closest('.cmb-row'),
            'rank_type': box.find( gamipress_progress_widget_field_selector( 'rank_type' ) ).closest('.cmb-row'),
            'rank': box.find( gamipress_progress_widget_field_selector( 'rank' ) ).closest('.cmb-row'),
            'current_rank': box.find( gamipress_progress_widget_field_selector( 'rank_type' ) ).closest('.cmb-row'),
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

            if( target.closest('.cmb-tabs-wrap').length ) {
                // Just show if item tab is active
                if( target.hasClass('cmb-tab-active-item') ) {
                    target.slideDown();
                }
            } else {
                target.slideDown();
            }

            target.removeClass('cmb2-tab-ignore');
        });

        // Hide style tab
        if( from === 'points_type'
            || from === 'achievement'
            || from === 'rank'
            || from === 'current_rank' ) {
            $(this).closest('.widget-content').find('[id^="widget_gamipress_progress_widget_box-tab"][id$="[style]"]').slideUp();
        } else {
            $(this).closest('.widget-content').find('[id^="widget_gamipress_progress_widget_box-tab"][id$="[style]"]').slideDown();
        }
    });

    $( 'select[id^="widget-gamipress_progress"][id$="[from]"]' ).trigger('change');

    // Initialize on widgets area
    $(document).on('widget-updated widget-added', function(e, widget) {

        // from field
        widget.find( 'select[id^="widget-gamipress_progress"][id$="[from]"]' ).trigger('change');

    });

})( jQuery );