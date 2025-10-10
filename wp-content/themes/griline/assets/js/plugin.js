
(function ($) {
    "use strict";
  
    jQuery(document).ready(function () {
      // wow init
      new WOW().init();
  
      // menu slider
      $(".menu-slider")
        .not(".slick-initialized")
        .slick({
          infinite: true,
          autoplay: true,
          focusOnSelect: true,
          speed: 2000,
          slidesToShow: 6,
          slidesToScroll: 1,
          arrows: true,
          dots: false,
          prevArrow: $(".prev-two"),
          nextArrow: $(".next-two"),
          centerMode: true,
          centerPadding: "0px",
          responsive: [
            {
              breakpoint: 1400,
              settings: {
                slidesToShow: 4,
              },
            },
            {
              breakpoint: 992,
              settings: {
                slidesToShow: 3,
              },
            },
            {
              breakpoint: 768,
              settings: {
                slidesToShow: 2,
              },
            },
            {
              breakpoint: 425,
              settings: {
                slidesToShow: 1,
              },
            },
          ],
        });
  
      // order now form popup
      if (document.querySelector(".order-now") !== null) {
        $(".order-now").magnificPopup({
          type: "inline",
          midClick: true,
          mainClass: "mfp-fade",
        });
      }
  
      // select item
      $(".select-item").niceSelect();
  
      // odometer counter
      $(".odometer-item").each(function () {
        $(this).isInViewport(function (status) {
          if (status === "entered") {
            for (
              var i = 0;
              i < document.querySelectorAll(".odometer").length;
              i++
            ) {
              var el = document.querySelectorAll(".odometer")[i];
              el.innerHTML = el.getAttribute("data-odometer-final");
            }
          }
        });
      });
  
      // testimonial slider
      $(".testimonial-slider")
        .not(".slick-initialized")
        .slick({
          infinite: true,
          autoplay: true,
          focusOnSelect: true,
          slidesToShow: 3,
          slidesToScroll: 1,
          arrows: false,
          dots: false,
          centerMode: true,
          centerPadding: "0px",
          responsive: [
            {
              breakpoint: 992,
              settings: {
                slidesToShow: 2,
              },
            },
            {
              breakpoint: 768,
              settings: {
                slidesToShow: 1,
              },
            },
          ],
        });
  
      // testimonial slider two
      $(".testimonial-two-slider-wrapper")
        .not(".slick-initialized")
        .slick({
          infinite: true,
          autoplay: true,
          focusOnSelect: true,
          speed: 1000,
          slidesToShow: 1,
          slidesToScroll: 1,
          arrows: true,
          dots: false,
          prevArrow: $(".testimonial-two-prev"),
          nextArrow: $(".testimonial-two-next"),
        });
  
      // pizza slider
      $(".pizza-slider").not(".slick-initialized").slick({
        infinite: true,
        autoplay: true,
        focusOnSelect: true,
        speed: 1000,
        slidesToShow: 3,
        slidesToScroll: 1,
        arrows: false,
        dots: true,
        centerMode: true,
        centerPadding: '0px',
        responsive: [
          {
              breakpoint: 992,
              settings: {
                  slidesToShow: 2,
              },
          },
          {
            breakpoint: 768,
            settings: {
                slidesToShow: 1,
            },
        }
      ]
      });
  
      // burger slider
      $(".burger-slider").not(".slick-initialized").slick({
        infinite: true,
        autoplay: true,
        focusOnSelect: true,
        speed: 1000,
        slidesToShow: 3,
        slidesToScroll: 1,
        arrows: false,
        dots: true,
        centerMode: true,
        centerPadding: '0px',
        responsive: [
          {
              breakpoint: 992,
              settings: {
                  slidesToShow: 2,
              },
          },
          {
            breakpoint: 768,
            settings: {
                slidesToShow: 1,
            },
        }
      ]
      });
  
      // burger slider
      $(".pasta-slider").not(".slick-initialized").slick({
        infinite: true,
        autoplay: true,
        focusOnSelect: true,
        speed: 1000,
        slidesToShow: 3,
        slidesToScroll: 1,
        arrows: false,
        dots: true,
        centerMode: true,
        centerPadding: '0px',
        responsive: [
          {
              breakpoint: 992,
              settings: {
                  slidesToShow: 2,
              },
          },
          {
            breakpoint: 768,
            settings: {
                slidesToShow: 1,
            },
        }
      ]
      });
  
      // drinks slider
      $(".drinks-slider").not(".slick-initialized").slick({
        infinite: true,
        autoplay: true,
        focusOnSelect: true,
        speed: 1000,
        slidesToShow: 3,
        slidesToScroll: 1,
        arrows: false,
        dots: true,
        centerMode: true,
        centerPadding: '0px',
        responsive: [
          {
              breakpoint: 992,
              settings: {
                  slidesToShow: 2,
              },
          },
          {
            breakpoint: 768,
            settings: {
                slidesToShow: 1,
            },
        }
      ]
      });
  
      // select dish
      $(".select-dish").niceSelect();
  
      // video popup
      if (document.querySelector(".video-popup") !== null) {
        $(".video-popup").magnificPopup({
            disableOn: 768,
            type: "iframe",
            mainClass: "mfp-fade",
            removalDelay: 160,
            preloader: false,
            fixedContentPos: false,
        });
    }
  
    });
  })(jQuery);
  