/* Notifima site behaviour (header, mega menus, phone menu, reveal, FAQ, filters). From the original notifima.js. */
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
  $doc.on('click', function (e) { if ($body.hasClass('menu-open') && !$(e.target).closest('.hdr-bar').length) $body.removeClass('menu-open'); });

  /* reveal: fade up only (bottom to top) */
  var $rv = $('.rv');
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (es) {
      es.forEach(function (e) { if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); } });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });
    $rv.each(function () { io.observe(this); });
  } else { $rv.addClass('in'); }

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

  /* data-t / data-cat, or the nf-t-… / nf-cat-… class a Kadence block carries instead */
  function nfData(el, k) {
    var v = $(el).data(k);
    if (v !== undefined) { return String(v); }
    var m = (' ' + (el.className || '') + ' ').match(new RegExp('\\snf-' + k + '-(\\S+)\\s'));
    return m ? m[1] : undefined;
  }

  /* blog category filter */
  $('.cats .cat-b').on('click', function () {
    var c = $(this).data('cat');
    $('.cat-b').removeClass('on'); $(this).addClass('on');
    $('.post[data-cat], .post[class*="nf-cat-"]').each(function () { $(this).toggleClass('hide nf-hide', c !== 'all' && (' ' + (nfData(this, 'cat') || '') + ' ').indexOf(' ' + c + ' ') < 0); });
  });

  /* changelog type filter */
  $('.cl-filter button').on('click', function () {
    var t = $(this).data('t');
    $('.cl-filter button').removeClass('on'); $(this).addClass('on');
    $('.rel').each(function () {
      var $li = $(this).find('li[data-t], [class*="nf-t-"]');
      $li.each(function () { $(this).toggleClass('hide nf-hide', t !== 'all' && nfData(this, 't') !== t); });
      $(this).toggleClass('hide nf-hide', $li.not('.nf-hide').length === 0);
    });
  });

  /* header mini cart: click the cart icon to open, click outside or Esc to close */
  $(document).on('click', '.nf-cart-btn', function (e) {
    e.preventDefault();
    var $c = $(this).closest('.nf-cart'), open = !$c.hasClass('open');
    $('.nf-cart').removeClass('open').find('.nf-cart-btn').attr('aria-expanded', 'false');
    $c.toggleClass('open', open); $(this).attr('aria-expanded', open ? 'true' : 'false');
  });
  $(document).on('click', function (e) { if (!$(e.target).closest('.nf-cart').length) { $('.nf-cart').removeClass('open').find('.nf-cart-btn').attr('aria-expanded', 'false'); } });
  $(document).on('keydown', function (e) { if (e.key === 'Escape') { $('.nf-cart').removeClass('open').find('.nf-cart-btn').attr('aria-expanded', 'false'); } });

  window.NF_READY = true;

  /* single post: highlight the "On this page" link of the section being read */
  var $toc = $('.bp-toc a');
  if ($toc.length) {
    var $hs = $toc.map(function () { return document.getElementById(decodeURIComponent(this.getAttribute('href').slice(1))); });
    var spy = function () {
      var cur = 0;
      $hs.each(function (i) { if (this && this.getBoundingClientRect().top < 160) cur = i; });
      $toc.removeClass('on').eq(cur).addClass('on');
    };
    $win.on('scroll', spy); spy();
  }

})(jQuery);
