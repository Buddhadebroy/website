/* CatalogX site interactions (jQuery). Only graphics animate; text and cards stay still apart from a bottom-to-top reveal. */
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

  /* hero quote flow: cycles the 3 steps (graphic only) */
  $('.flow').each(function () {
    var $f = $(this), $s = $f.find('.flow-steps span'), $st = $f.find('.fs'), i = 0, t;
    function go(n) { i = n; $s.removeClass('on').eq(n).addClass('on'); $st.removeClass('on').eq(n).addClass('on'); }
    function next() { go((i + 1) % $st.length); }
    function play() { clearInterval(t); t = setInterval(next, 4200); }
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

  /* features filter: module + tier + search */
  $('.fx-wrap').each(function () {
    var $w = $(this), $rows = $w.find('.fx'), cat = 'all', tier = 'all', q = '';
    function apply() {
      var n = 0;
      $rows.each(function () {
        var $r = $(this), ok = (cat === 'all' || $r.data('cat') === cat) && (tier === 'all' || $r.data('tier') === tier) && (!q || $r.text().toLowerCase().indexOf(q) > -1);
        $r.toggleClass('hide', !ok); if (ok) n++;
      });
      $w.find('.fx-count b').text(n);
      $w.find('.fx-empty').toggle(n === 0);
    }
    $w.find('.fx-cat').each(function () {
      var c = $(this).data('cat');
      $(this).find('em').text(c === 'all' ? $rows.length : $rows.filter('[data-cat="' + c + '"]').length);
    });
    $w.find('.fx-cat').on('click', function () { $w.find('.fx-cat').removeClass('on'); $(this).addClass('on'); cat = $(this).data('cat'); apply(); });
    $w.find('.fx-tier button').on('click', function () { $w.find('.fx-tier button').removeClass('on'); $(this).addClass('on'); tier = $(this).data('tier'); apply(); });
    $w.find('.fx-search input').on('input', function () { q = $.trim(this.value.toLowerCase()); apply(); });
    apply();
  });

  /* pricing comparison: group filter */
  $('.cmp-wrap').each(function () {
    var $w = $(this);
    $w.find('.fx-cat').on('click', function () {
      var g = $(this).data('cat');
      $w.find('.fx-cat').removeClass('on'); $(this).addClass('on');
      $w.find('tbody tr').each(function () { $(this).toggleClass('hide', g !== 'all' && $(this).data('g') !== g); });
    });
  });

  /* blocks filter */
  $('.blk-filter button').on('click', function () {
    var t = $(this).data('tier');
    $(this).addClass('on').siblings().removeClass('on');
    $('.blk').each(function () { $(this).toggleClass('hide', t !== 'all' && $(this).data('tier') !== t); });
  });
})(jQuery);

/* blog category filter */
(function ($) {
  $('.blog-tabs .fx-cat').on('click', function () {
    var c = $(this).data('cat');
    $(this).addClass('on').siblings().removeClass('on');
    $('.blog-wrap .post').each(function () { $(this).toggleClass('hide', c !== 'all' && $(this).data('cat') !== c); });
  });
})(jQuery);
