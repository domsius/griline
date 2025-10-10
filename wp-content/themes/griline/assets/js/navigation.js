(function($) {
    'use strict';

    // Initialize Nice Select
    $(document).ready(function() {
        $('select').niceSelect();
    });

    // Initialize Magnific Popup
    $(document).ready(function() {
        $('.popup-video').magnificPopup({
            type: 'iframe',
            mainClass: 'mfp-fade',
            removalDelay: 160,
            preloader: false,
            fixedContentPos: true
        });
    });

    // Initialize Slick Carousel
    $(document).ready(function() {
        $('.testimonial-slider').slick({
            dots: true,
            infinite: true,
            speed: 500,
            slidesToShow: 1,
            adaptiveHeight: true
        });
    });

    // Initialize Odometer
    $(document).ready(function() {
        $('.odometer').each(function() {
            $(this).odometer({
                value: $(this).data('value'),
                format: 'd'
            });
        });
    });

    // Mobile Menu Toggle
    $('.navbar-toggler').on('click', function() {
        $(this).toggleClass('active');
    });

    // Scroll to Top
    $(window).scroll(function() {
        if ($(this).scrollTop() > 100) {
            $('.scroll-to-top').fadeIn();
        } else {
            $('.scroll-to-top').fadeOut();
        }
    });

    $('.scroll-to-top').click(function() {
        $('html, body').animate({scrollTop: 0}, 800);
        return false;
    });

})(jQuery); 