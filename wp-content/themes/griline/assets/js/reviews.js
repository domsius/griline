$(document).ready(function() {
    $('.rating input').click(function() {
        var rating = $(this).val();

        if (rating == 5) {
            var reviewLink = 'https://g.page/r/CS9rCJe-YhEZEBM/review';
            
            window.location.href = reviewLink;
        } else {
            $('.message-box').show();
        }
    });

    $('#feedbackForm').submit(function(event) {
        event.preventDefault();
        var rating = $('input[name="rating"]:checked').val();
        var message = $('#message').val();
        
        // Replace with your NEW deployment URL
        var scriptURL = 'https://script.google.com/macros/s/AKfycbwDhU1X3TEb0vOq_L8-LBW2KvQDSx19CXN4CWPY8IHmHSRU7815ZrCdodO9MCIvTMUg/exec';
        
        // Show loading state
        var $button = $(this).find('button');
        $button.prop('disabled', true).text('Siunčiama...');
        
        $.ajax({
            url: scriptURL,
            method: 'GET',
            dataType: 'jsonp',
            data: {
                rating: rating,
                message: message,
                callback: 'jsonpCallback' // Explicitly name the callback
            },
            timeout: 10000 // 10 second timeout
        })
        .done(function(data) {
            $('.message-box').html('<p class="success">Ačiū už jūsų atsiliepimą!</p>');
            $button.prop('disabled', false).text('Siųsti');
            console.log(data);
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.error('Status:', textStatus);
            console.error('Error:', errorThrown);
            console.error('Response:', jqXHR);
            $('.message-box').html('<p class="error">Įvyko klaida. Bandykite dar kartą.</p>');
            $button.prop('disabled', false).text('Siųsti');
        });
    });
});

// Define global callback function
window.jsonpCallback = function(response) {
    console.log('Response received:', response);
};