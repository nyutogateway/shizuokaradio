/**
 * SHIZUOKA OCEANS RADIO
 * 静的HTMLのインラインJSをテーマ用に切り出したもの
 */
(function ($) {
  'use strict';

  /* ヘッダーSPメニューの開閉 */
  $('#header-sp-toggler').on('click', function () {
    if ($(this).hasClass('active')) {
      $(this).removeClass('active');
      $('#header-sp-menu').fadeOut();
      $('body').css('overflow', 'inherit');
    } else {
      $(this).addClass('active');
      $('#header-sp-menu').fadeIn();
      $('body').css('overflow', 'hidden');
    }
  });
  $('#header-sp-menu').on('click', function () {
    $(this).fadeOut();
    $('#header-sp-toggler').removeClass('active');
    $('body').css('overflow', 'inherit');
  });

  /* 150px超のスクロールで追従ヘッダーを出す */
  $(window).on('scroll', function () {
    $('#header-sticky').toggleClass('active', $(this).scrollTop() > 150);
  });

  /* 番組スライダー（自動再生・ホバーで停止） */
  var slickBase = {
    infinite: true, slidesToScroll: 1, autoplay: true, autoplaySpeed: 4000,
    pauseOnHover: true, dots: false,
    prevArrow: '<div class="slider-nav slider-nav--prev"><i class="fa-solid fa-arrow-left-long"></i></div>',
    nextArrow: '<div class="slider-nav slider-nav--next"><i class="fa-solid fa-arrow-right-long"></i></div>'
  };

  if ($('#home-program-slider').length) {
    $('#home-program-slider').slick($.extend({}, slickBase, {
      slidesToShow: 3,
      appendArrows: $('#home-program .arrows'),
      responsive: [
        { breakpoint: 1200, settings: { slidesToShow: 2 } },
        { breakpoint: 767, settings: { slidesToShow: 1 } }
      ]
    }));
  }

  if ($('#home-personality-slider').length) {
    $('#home-personality-slider').slick($.extend({}, slickBase, {
      slidesToShow: 4,
      appendArrows: $('#home-personality .arrows'),
      responsive: [
        { breakpoint: 1200, settings: { slidesToShow: 3 } },
        { breakpoint: 767, settings: { slidesToShow: 2 } }
      ]
    }));
  }
})(jQuery);
