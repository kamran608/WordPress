jQuery(document).ready(function($){
    $('#swr-contact-form').on('submit', function(e){
        e.preventDefault();
        $('.swr-loader').show();
        $('#swr-message').hide().removeClass('success error').html('');  // Clear previous messages

        $.post(swrice_ajax.ajax_url, {
            action: 'swr_contact_submit',
            name: $('#swr_name').val(),
            email: $('#swr_email').val(),
            reason: $('#swr_reason').val(),
            message: $('#swr_message').val()
        }, function(response){
            $('.swr-loader').hide();
            
            if(response.success){
                $('#swr-message').addClass('success').html(response.data).fadeIn();
                $('#swr-contact-form')[0].reset(); // Reset form fields after submission
            } else {
                $('#swr-message').addClass('error').html(response.data).fadeIn();
            }
        });
    });
});
