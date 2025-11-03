(function( $ ) { 'use strict';
    $( document ).ready( function() {

        let  SwriceFrontend = {

            init: function() {
                
                this.updateRightArrow();
				this.switchSwriceTab();
            },      
	
			/**
             * Switch swrice tab
             */
            switchSwriceTab: function() {

                $( document ).on( 'click', '.swrice-tab', function() {

                    let self = $( this );
                    $( '.swrice-tab' ).removeClass( 'active' );
                    self.addClass( 'active' );

                    let currentTab = parseInt( self.text() );
                    $( '.tab-content' ).hide();
                    $( '.tab-content-'+currentTab ).show();

                    $( '.tab-heading' ).hide();
                    $( '.tab-heading-'+currentTab ).show();
                } );
            },
			
            /** 
            * Generate Buyer Code
            */
            updateRightArrow: function() {

                $( document ).on( 'click', '.right-arrow', function() {

                    let self = $( this );
                    let parent = self.parents( '.clients-review-wrap' );
                    let totalReviews = parseInt( parent.find( '.client-list' ).attr( 'data-total_reviews' ) );

                    $('.main-client-img').each(function(){
                        var currentClass = $(this).attr('class').split(' ')[1];
                        var currentIndex = parseInt(currentClass.split('-')[1]);
                        var prevIndex = currentIndex == 1 ? totalReviews : currentIndex - 1;
                        var prevClass = 'user-' + prevIndex;
                        $(this).removeClass(currentClass).addClass(prevClass);
                    });

                    let reviewContent = $('.main-client-img.user-2').find('.review-content').text();
                    $('.review-section .content').fadeOut(200, function() {
                        $(this).html(reviewContent).fadeIn(200);
                    });
                } );

                $( document ).on( 'click', '.left-arrow', function() {

                    let self = $( this );
                    let parent = self.parents( '.clients-review-wrap' );
                    let totalReviews = parseInt( parent.find( '.client-list' ).attr( 'data-total_reviews' ) );

                    $('.main-client-img').each(function(){
                        var currentClass = $(this).attr('class').split(' ')[1];
                        var currentIndex = parseInt(currentClass.split('-')[1]);
                        var nextIndex = currentIndex == totalReviews ? 1 : currentIndex + 1;
                        var nextClass = 'user-' + nextIndex;
                        $(this).removeClass(currentClass).addClass(nextClass);
                    });

                    let reviewContent = $('.main-client-img.user-2').find('.review-content').text();
                    $('.review-section .content').fadeOut(200, function() {
                        $(this).html(reviewContent).fadeIn(200);
                    });
                } );
            },
        };

        SwriceFrontend.init();
    });
})( jQuery );