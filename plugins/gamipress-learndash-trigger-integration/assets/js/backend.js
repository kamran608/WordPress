(function( $ ) { 'use strict';

    $( document ).ready( function() {

        var GLTIBackend = {

            init: function() {

                this.addGamiPressTrigger();
                this.deleteTrigger();
            },

            /**
            * Deleting Triggrt 
            */
            deleteTrigger: function() {

                $( document ).on( 'click', '.glc-delete-trigger', function() {
                    
                    if ( confirm( 'Are you sure you want to delete this trigger' ) ) {

                        let self = $( this );
                        let triggerID = self.data( 'trigger_id' ); 
                        let ajax_url = GLTI.ajax_url;
                        let data = {
                            'action'        : 'glc_deleting_trigger',
                            'trigger_id'    : triggerID
                        };

                        $.post( ajax_url, data, function (response) {
                            let resp = JSON.parse(response);
                            if( resp.status == 'true' ) {
                                
                                self.parents( 'tr' ).remove();
                                if ( $( '.glc-display-trigger' ).find( 'tr' ).length == 0 ) {

                                    $( document ).find( '.glc-display-trigger' ).html( '<tr><td colspan="7">No Triggers Found.</td></tr>' )
                                }
                            }
                        } );
                    }
                } );
            },

            /**
             * Add gamipress trigger
             */
            addGamiPressTrigger: function() {

                $( document ).on( 'click', '.glc-add-trigger', function() {

                    let self = $( this );
                    
                    let selectElement = $('.glc-point-types');
                    let selectedOption = selectElement.find( 'option:selected' );
                    let pointTypeID = selectedOption.data( 'point_type_id' );
                    let postID = $( '.glc-trigger-wrapper' ).data( 'post_id' );

                    let PointType = $( '.glc-point-types' ).val();
                    let Points = $( '.glc-number-of-point' ).val();
                    let LimitedType = $( '.glc-limited-time' ).val();
                    let MaxEearning = $( '.glc-max-number-earning' ).val();

                    if ( !PointType || !Points ) {
                        alert( 'Please select fields' );
                       return false;
                    }
                    self.text( 'Adding...' );
                    let ajax_url = GLTI.ajax_url;
                    let data = {
                        'action'        : 'glc_add_trigger',
                        'point_type'    : PointType,
                        'points'        : Points,
                        'limited_time'  : LimitedType,
                        'max_amount'    : MaxEearning,
                        'glc_label'     : $( '.glc-label' ).val(),
                        'point_type_id' : pointTypeID,
                        'post_id'       : postID
                    };

                    $.post( ajax_url, data, function (response) {
                        let resp = JSON.parse(response);
                        if( resp.status == 'true' ) {

                            $( document ).find( '.glc-display-trigger' ).html( resp.trigger_content );
                            $( '.glc-point-types' ).val('');
                            $( '.glc-number-of-point' ).val('');
                            $( '.glc-limited-time' ).val('');
                            $( '.glc-max-number-earning' ).val('');
                            $( '.glc-label' ).val('');

                            self.text( 'Add Trigger' );
                        }
                    } );
                } );
            },
        };

        GLTIBackend.init();
    });
})( jQuery );