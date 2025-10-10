
(function ($) {
    "use strict";
  
    jQuery(document).ready(function () {
      // data background
      $("[data-background]").each(function () {
        $(this).css(
          "background-image",
          "url(" + $(this).attr("data-background") + ")"
        );
      });
  
      // animated hamburger icon
      $(".navbar-toggler").on("click", function () {
        $(this).toggleClass("toggle-active");
        $(".navbar").toggleClass("navbar-active");
      });
  
      // position navbar on scroll and resize
      $(window).on("scroll", function (e) {
        var scroll = $(window).scrollTop();
        if ((scroll > 10) | (scroll == 10)) {
          $(".header").addClass("header-active");
          $(".header--secondary").addClass("header--secondary-active");
        } else {
          $(".header").removeClass("header-active");
          $(".header--secondary").removeClass("header--secondary-active");
        }
      });
  
      $(window).resize(function () {
        if ($(".dropdown-menu").hasClass("show")) {
          $(".dropdown-menu").removeClass("show");
          $(".dropdown-toggle").removeClass("show");
        }
        $(".navbar").removeClass("navbar-active");
        $(".navbar-collapse").removeClass("show");
        $(".navbar-toggler").removeClass("toggle-active");
      });
  
    
      // gallery tab content
      $(".gallery-tab-content").hide();
      $(".gallery-tab-content:nth-of-type(3)").show();
      $(".gallery-tab-btn").on("click", function () {
        $(".gallery-tab-btn").removeClass("gallery-tab-btn-active");
        $(this).addClass("gallery-tab-btn-active");
        $(".gallery-tab-content").hide();
        var activeGallery = $(this).attr("href");
        $(activeGallery).fadeIn(200);
        return false;
      });
  
      // Scroll Bottom To Top
      var ScrollTop = $(".scrollToTop");
      $(window).on("scroll", function () {
        if ($(this).scrollTop() < 300) {
          ScrollTop.removeClass("active");
        } else {
          ScrollTop.addClass("active");
        }
      });
  
      $(".scrollToTop").on("click", function () {
        $("html, body").animate(
          {
            scrollTop: 0,
          },
          300
        );
        return false;
      });
    });
  })(jQuery);
  