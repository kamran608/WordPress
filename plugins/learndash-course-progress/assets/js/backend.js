(function( $ ) { 'use strict';

    $( document ).ready( function() {

        let LCPBackend = {

            init: function() {

                this.enqueueColorPicker();
            },

            /**
             * Enqueue Color Picker
             */
            enqueueColorPicker: function() {

                $( '.lcp-color-picker' ).wpColorPicker();
            },
        };

        LCPBackend.init();
    });
})( jQuery );