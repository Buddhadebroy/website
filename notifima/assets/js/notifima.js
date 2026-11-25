/* Notifima site interactions (jQuery). Only graphics animate; text and cards stay still apart from a bottom-to-top reveal. */
(function ($) {
  'use strict';
  var $doc = $(document), $win = $(window), $body = $('body');
  document.documentElement.classList.add('js');

  /* header shadow on scroll */
  var $hdr = $('.hdr');
  function onScroll() { $hdr.toggleClass('scrolled', $win.scrollTop() > 8); }
  $win.on('scroll', onScroll); onScroll();

  /* mega menus: hover on desktop, click on any device */
  var closeT;
  $('.nav-item.has-mega').each(function () {
    var $it = $(this);
    $it.on('mouseenter', function () {
      if (window.matchMedia('(hover:hover)').matches) { clearTimeout(closeT); $('.nav-item.open').not($it).removeClass('open').find('.nav-link').attr('aria-expanded', 'false'); $it.addClass('open').find('.nav-link').attr('aria-expanded', 'true'); }
    }).on('mouseleave', function () {
      if (window.matchMedia('(hover:hover)').matches) closeT = setTimeout(function () { $it.removeClass('open').find('.nav-link').attr('aria-expanded', 'false'); }, 160);
    });
    $it.find('.nav-link').on('click', function () {
      var open = !$it.hasClass('open');
      $('.nav-item.open').removeClass('open').find('.nav-link').attr('aria-expanded', 'false');
      $it.toggleClass('open', open).find('.nav-link').attr('aria-expanded', open ? 'true' : 'false');
    });
  });
  $doc.on('click', function (e) { if (!$(e.target).closest('.nav-item').length) $('.nav-item.open').removeClass('open').find('.nav-link').attr('aria-expanded', 'false'); });
  $doc.on('keydown', function (e) { if (e.key === 'Escape') { $('.nav-item.open').removeClass('open'); $body.removeClass('menu-open'); } });

  /* mobile drawer */
  $('.burger').on('click', function () { var o = !$body.hasClass('menu-open'); $body.toggleClass('menu-open', o); $('.drawer').attr('aria-hidden', o ? 'false' : 'true'); });
  $('.dr-h').on('click', function () { $(this).parent().toggleClass('open'); });
  $('.drawer a').on('click', function () { $body.removeClass('menu-open'); });

  /* reveal: fade up only (bottom to top) */
  var $rv = $('.rv');
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (es) {
      es.forEach(function (e) { if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); } });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });
    $rv.each(function () { io.observe(this); });
  } else { $rv.addClass('in'); }

  /* hero flow: plays the 3 steps once, then rests on the last one. Click a step to replay it. */
  $('.flow').each(function () {
    var $f = $(this), $s = $f.find('.flow-steps span'), $st = $f.find('.fs'), i = 0, t;
    function go(n) { i = n; $s.removeClass('on').eq(n).addClass('on'); $st.removeClass('on').eq(n).addClass('on'); }
    function play() { clearInterval(t); t = setInterval(function () { if (i >= $st.length - 1) { clearInterval(t); return; } go(i + 1); }, 4200); }
    $s.on('click', function () { go($s.index(this)); play(); });
    go(0); play();
  });

  /* tabs (pillars) */
  $('[data-tabs]').each(function () {
    var $w = $(this), $t = $w.find('.tab'), $p = $w.find('.pane');
    $t.on('click', function () { var n = $t.index(this); $t.removeClass('on').attr('aria-selected', 'false'); $(this).addClass('on').attr('aria-selected', 'true'); $p.removeClass('on').eq(n).addClass('on'); });
  });

  /* FAQ accordion */
  $('.qa-q').on('click', function () {
    var $qa = $(this).closest('.qa'), open = !$qa.hasClass('open');
    $qa.toggleClass('open', open).find('.qa-a').stop(true).slideToggle(200);
    $(this).attr('aria-expanded', open ? 'true' : 'false');
  });

})(jQuery);
