(function( $ ) { 'use strict';

    $( document ).ready( function() {

        var BRCFrontend = {

            init: function() {
				
                this.updateLikeAndCommentCount();
            },
			
            /**
             * Update like and comment count
             */
            updateLikeAndCommentCount: function() {
                
                if( 'on' != BRC.likes_count ) {
                    return false;
                }

                $( document ).ajaxComplete(function(event, xhr, settings) {

                    if( ( settings.data && settings.data.indexOf( 'action=activity_mark_fav' ) !== -1 ) 
                        || settings.data && settings.data.indexOf( 'action=activity_mark_unfav' ) !== -1 ) {

                        let dataParams = new URLSearchParams(settings.data);
                        let itemId = dataParams.get('item_id');

                        $.ajax({
                            url: BRC.ajax_url,
                            type: 'POST',
                            data: {
                                action: 'brc_update_reaction_count',
                                security: BRC.nonce,
                                activity_id: itemId
                            },
                            success: function(response) {

                                let reactionCount = parseInt( response.data.count );

                                $( '#activity-'+itemId ).find( '.bp-generic-meta .generic-button a' ).find( '.like-count' ).after( '<span style="color: #000;" class="brb-like-count"> (' + reactionCount + ')</span>' );
                            },
                            error: function() {
                                
                            }
                        });
                    }
                });
            },
        };

        BRCFrontend.init();
    });
})( jQuery );