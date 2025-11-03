(function( $ ) { 'use strict';
    $( document ).ready( function() {

        let  AffiliateUser = {

            init: function() {
                
                this.Display_payment_info();
                this.inputFile();
            },      

            /** 
            * Generate Buyer Code
            */
            Display_payment_info: function() {

                $( document ).on( 'change', '.aie-payment-info-box', function() {
                    let self = $( this );
                    let paymentDetails = {
                      accountOwnerName: "MUHAMMAD ZEESHAN",
                      bankName: "Meezan Bank",
                      accountNumber: "01860106691596",
                      IBAN: "PK86MEZN0001860106691596"
                    };

                    // Create the payment details HTML
                    let paymentDetailsHTML = '<div class="aie-payment-details">';
                    paymentDetailsHTML += '<div class="aie-account-owner-name">NAME: ' + paymentDetails.accountOwnerName + '</div>';
                    paymentDetailsHTML += '<div>BANK NAME: ' + paymentDetails.bankName + '</div>';
                    paymentDetailsHTML += '<div>Account Number: ' + paymentDetails.accountNumber + '</div>';
                    paymentDetailsHTML += '<div>IBAN: ' + paymentDetails.IBAN + '</div>';
                    paymentDetailsHTML += '</div>';
                    if ( self.val() == 'bank_transfer' ) {
                        
                        self.parents( '.aie-form-container' ).find( '.aie-payment-details' ).html( paymentDetailsHTML );
                    }
					let easypaisaDetailsHTML = '<div class="aie-payment-details">';
					easypaisaDetailsHTML+= '<div class="aie-account-owner-name">NAME: ' + 'MUHAMMAD KAMRAN' + '</div>';
					easypaisaDetailsHTML+= '<div class="aie-account-owner-name">EASYPAISA: '+'03172917904'+'</div>';
					easypaisaDetailsHTML+= '</div>';
                    if ( self.val() == 'easypaisa' ) {
                        self.parents( '.aie-form-container' ).find( '.aie-payment-details' ).html( easypaisaDetailsHTML );
                    }

                } );
            },
            inputFile: function() {
                $( document ).on( 'click', '.upload-field', function() {
                  let fileInput = $( this ).closest( '.form-group' ).find( '.input-file' );
                  fileInput.trigger('click');
                } );

                $( document ).on( 'change', '.input-file', function() {
                  let fileName = $( '.input-file' ).val().replace( /C:\\fakepath\\/i, '' );
                  $( document ).find( '.aie-form-control' ).val( fileName );
                } );
            }  
        };

        AffiliateUser.init();
    });
})( jQuery );