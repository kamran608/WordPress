(function( $ ) { 'use strict';
    $( document ).ready( function() {

        let LDNINJAS_menus = {

            init: function() {

                this.UploadIconForTheMenu();
            },   

            /** 
             *  Upload Icon For Menu
             */ 
            UploadIconForTheMenu: function() {

                var customUploader;

                $('.ldninjas-upload-media-button').on('click', function (e) {
                    e.preventDefault();

                    var uploadButton = $(this);

                    if (customUploader) {
                        customUploader.open();
                        return;
                    }

                    customUploader = wp.media.frames.file_frame = wp.media({
                        title: 'Choose Media',
                        button: {
                            text: 'Choose Media'
                        },
                        multiple: false
                    });

                    customUploader.on('select', function () {
                        let attachment = customUploader.state().get('selection').first().toJSON();
                        let attachmentUrl = attachment.url;
                        let attachmentID = uploadButton.attr( 'data-id' );
                        $( document ).find( '.ldninjas-menu-icon-preview-'+attachmentID ).html( '<img src="'+attachmentUrl+'">' );
                        $( document ).find( '#ldninjas-menu-icon-'+attachmentID ).val( attachmentUrl );
                    } );
                    customUploader.open();
                } );
            }
        };

        LDNINJAS_menus.init();
    });
})( jQuery );