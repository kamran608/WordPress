(function( $ ) { 'use strict';
    $( document ).ready( function() {

        let SWRICE_Header = {

            init: function() {
                this.header_menu();
                this.menu_show_and_hide();
                this.AddOverFlowBahaviour();
            },   
   
            header_menu: function () {
                
                $('body').on( 'click','.swrice-menu-bar-icon', function() {

                    let self = $( this );
                    $( 'body' ).css( 'overflow','hidden' );
                    let menuListWrap = self.parents( '.swrice-header-wrapper' ).find( '.swrice-menu-list-wrap' );
                    menuListWrap.addClass( 'swrice-menu-open' );
                    menuListWrap.css({ 'display': 'block', 'left': '-100%' } );
                    menuListWrap.animate( { 'left': '0' }, 300);
                } );

                $('body').on( 'click','.swrice-menu-bar-icon-mob', function() {
                    $( 'body' ).css( 'overflow','auto' );
                    let self = $( this );
                    let menuListWrap = self.parents( '.swrice-header-wrapper' ).find( '.swrice-menu-list-wrap' );
                    menuListWrap.removeClass( 'swrice-menu-open' );
                    menuListWrap.animate( { 'left': '-100%' }, 300, function() {
                        menuListWrap.css( 'display', 'none' );
                    } );

                } );

            },
            /**     
            *  Show and Hide Submenu
            */ 
            menu_show_and_hide: function () {

                $(document).on('click', '.swrice-down-arrow-icon', function() {
                    let windowWidth = $(window).width(); // Get window width
                    
                    if (windowWidth <= 1024) {
                        let self = $(this);
                        let parentElement = self.parents('.swrice-order-list');
                        let bottomContent = parentElement.find('.swrice-bottom-content');
                        bottomContent.toggleClass('ld-menu-active');
                        $('.swrice-bottom-content').not(bottomContent).removeClass('ld-menu-active');
                        
                        $('.swrice-down-arrow-icon').removeClass('fa-chevron-up').addClass('fa-chevron-down');
                        
                        if (bottomContent.hasClass('ld-menu-active')) {
                            self.removeClass('fa-chevron-down').addClass('fa-chevron-up');
                        } else {
                            self.removeClass('fa-chevron-up').addClass('fa-chevron-down');
                        }
                        
                        // Check if window width is less than or equal to 800
                        if (windowWidth <= 800) {
                            $('html, body, .swrice-menu-list-wrap').animate({
                                scrollTop: parentElement.offset().top - $('.swrice-header-mob-wrapper').offset().top
                            }, 500);
                        }
                    }
                });

                $( window ).on( 'resize', function () {
                    if ( $( window ).width() > 1024) {
                        $( document ).find( '.swrice-down-arrow-icon' ).removeClass( 'fa-chevron-up' ).addClass( 'fa-chevron-down' );
                    }
                } );
            },

            /**
             *  Add overflow occourding to div behaviour
             */ 
            AddOverFlowBahaviour: function() {
                $(window).resize(function(){
                    if ($('.swrice-menu-list-wrap').css('display') == 'flex') {
                        $('body').css('overflow','auto');
                    }
                });
                $(window).trigger('resize');
            }

        };

        SWRICE_Header.init();
    });
})( jQuery );