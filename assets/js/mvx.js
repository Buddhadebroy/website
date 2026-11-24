/* MultiVendorX site interactions — jQuery 3 */
jQuery(function ($) {
  var $w = $(window), $hdr = $('.hdr');

  /* sticky header shadow */
  $w.on('scroll', function () { $hdr.toggleClass('scrolled', $w.scrollTop() > 10); }).trigger('scroll');

  /* mega menus: hover on desktop, click as fallback (touch) */
  var t;
  $('.nav .has-mega').on('mouseenter', function () {
    clearTimeout(t); $(this).addClass('open').siblings().removeClass('open');
  }).on('mouseleave', function () {
    var $i = $(this); t = setTimeout(function () { $i.removeClass('open'); }, 160);
  }).children('.nl').on('click', function (e) {
    e.preventDefault(); $(this).parent().toggleClass('open').siblings().removeClass('open');
  });
  $(document).on('click', function (e) { if (!$(e.target).closest('.nav').length) $('.nav .item').removeClass('open'); });
  $(document).on('keydown', function (e) { if (e.key === 'Escape') { $('.nav .item').removeClass('open'); closeDrawer(); } });

  /* sliding hover pill behind nav links */
  var $pill = $('.nav-pill'), $bar = $('.hdr-bar');
  $('.nav .nl').on('mouseenter focus', function () {
    var r = this.getBoundingClientRect(), b = $bar[0].getBoundingClientRect();
    $pill.css({ left: r.left - b.left, width: r.width });
  });

  /* marketplace mega: hover a type to swap the animated preview */
  $('.mm-i').on('mouseenter focus', function () {
    var k = $(this).data('pv'), $m = $(this).closest('.mega-in');
    $(this).addClass('on').siblings('.mm-i').removeClass('on');
    $m.find('.pv').removeClass('on').filter('[data-pv="' + k + '"]').addClass('on');
  });
  /* soft spotlight that follows the cursor inside menus */
  $('[data-spot]').on('mousemove', function (e) {
    var r = this.getBoundingClientRect();
    this.style.setProperty('--mx', (e.clientX - r.left) + 'px'); this.style.setProperty('--my', (e.clientY - r.top) + 'px');
  });

  /* mobile drawer */
  function closeDrawer() { $('.drawer').removeClass('open').attr('aria-hidden', 'true'); $('body').removeClass('scroll-lock'); }
  $('.hdr .burger').on('click', function () { $('.drawer').addClass('open').attr('aria-hidden', 'false'); $('body').addClass('scroll-lock'); });
  $('.drawer .scrim, .drawer .d-close').on('click', closeDrawer);
  $('.d-acc > button').on('click', function () {
    var $a = $(this).parent(); $a.toggleClass('open').find('.d-body').stop().slideToggle(220);
  });

  /* reveal on scroll + stagger */
  $('[data-stagger]').each(function () {
    var step = +$(this).data('stagger') || 80;
    $(this).children().each(function (i) { $(this).addClass('rv').css('transition-delay', (i * step) + 'ms'); });
  });
  var io = 'IntersectionObserver' in window ? new IntersectionObserver(function (es) {
    es.forEach(function (e) { if (e.isIntersecting) { $(e.target).addClass('in'); io.unobserve(e.target); } });
  }, { threshold: .12, rootMargin: '0px 0px -40px 0px' }) : null;
  $('.rv').each(function () { io ? io.observe(this) : $(this).addClass('in'); });

  /* count up */
  function countUp(el) {
    var $e = $(el), to = parseFloat($e.data('to')), dec = ($e.data('to') + '').split('.')[1], d = dec ? dec.length : 0,
        pre = $e.data('pre') || '', suf = $e.data('suf') || '';
    $({ v: 0 }).animate({ v: to }, { duration: 1600, easing: 'swing',
      step: function (v) { $e.text(pre + Number(v.toFixed(d)).toLocaleString('en-US', { minimumFractionDigits: d }) + suf); },
      complete: function () { $e.text(pre + Number(to).toLocaleString('en-US', { minimumFractionDigits: d }) + suf); } });
  }
  var co = 'IntersectionObserver' in window ? new IntersectionObserver(function (es) {
    es.forEach(function (e) { if (e.isIntersecting) { countUp(e.target); co.unobserve(e.target); } });
  }, { threshold: .6 }) : null;
  $('.count[data-to]').each(function () { co ? co.observe(this) : countUp(this); });

  /* tabs */
  $('[data-tabs]').each(function () {
    var $box = $(this);
    $box.find('[data-tab]').on('click', function () {
      var id = $(this).data('tab');
      $box.find('[data-tab]').removeClass('on').attr('aria-selected', 'false');
      $(this).addClass('on').attr('aria-selected', 'true');
      $box.find('[data-panel]').removeClass('on').filter('[data-panel="' + id + '"]').addClass('on');
    });
  });


  /* feature explorer: auto-advance while visible, pause on hover (CSS) */
  $('[data-fx]').each(function () {
    var $fx = $(this), el = this;
    if ('IntersectionObserver' in window) new IntersectionObserver(function (es) {
      es.forEach(function (e) { $fx.toggleClass('run', e.isIntersecting); });
    }, { threshold: .35 }).observe(el);
    $fx.on('animationend', '.fx-p', function () {
      var $t = $fx.find('.fx-t'), i = $t.index($t.filter('.on')), $n = $t.eq((i + 1) % $t.length);
      $n.trigger('click');
      var bar = $fx.find('.fx-bar')[0]; bar.scrollTo({ left: $n[0].offsetLeft - 20, behavior: 'smooth' });
    });
  });


  /* marketplace types: the active tab follows scroll position */
  $('[data-mks]').each(function () {
    var $m = $(this), $t = $m.find('.mks-t'), $p = $m.find('.mks-p'), n = $p.length, cur = -1;
    var flat = function () { return window.innerWidth < 1000 || window.innerHeight < 640; };
    function go(i) {
      if (i === cur) return; cur = i;
      $t.removeClass('on').eq(i).addClass('on');
      $p.removeClass('on past').each(function (k) { if (k < i) $(this).addClass('past'); }).eq(i).addClass('on');
    }
    function upd() {
      if (flat()) { $m.addClass('flat'); return; }
      $m.removeClass('flat');
      var top = $m.offset().top - 84, range = $m.outerHeight() - $m.find('.mks-stick').outerHeight();
      var pr = Math.min(.9999, Math.max(0, ($w.scrollTop() - top) / range)), f = pr * n, i = Math.floor(f);
      go(i);
      $t.each(function (k) { this.style.setProperty('--f', k < i ? 1 : k === i ? (f - i) : 0); });
    }
    $t.on('click', function () {
      var i = +$(this).data('i');
      if (flat()) return;
      var top = $m.offset().top - 84, range = $m.outerHeight() - $m.find('.mks-stick').outerHeight();
      $('html,body').stop().animate({ scrollTop: top + (i + .5) / n * range }, 600);
    });
    $w.on('scroll resize', upd); upd();
  });


  /* features page: search, Free/Pro, area and module filters */
  $('[data-fp]').each(function () {
    var $r = $(this), st = { q: '', plan: 'all', cat: 'all', mc: 'all', view: 'feat' }, $q = $('[data-fp-q]');
    $r.find('.fp-f b').each(function () { $(this).data('raw', $(this).text()); });
    function esc(s) { return s.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); }
    function apply() {
      var q = st.q.trim().toLowerCase(), n = 0, re = q ? new RegExp('(' + esc(q) + ')', 'ig') : null;
      if (st.view === 'feat') {
        $r.find('.fp-sec').each(function () {
          var $s = $(this), c = 0, catOk = st.cat === 'all' || $s.data('cat') === st.cat;
          $s.find('.fp-f').each(function () {
            var $f = $(this), ok = catOk && (st.plan === 'all' || $f.data('t') === st.plan) && (!q || $f.data('n').indexOf(q) > -1);
            $f.toggle(ok); if (ok) c++;
            var $b = $f.children('b'), raw = $b.data('raw');
            $b.html(re && ok ? $('<i>').text(raw).html().replace(re, '<mark>$1</mark>') : $('<i>').text(raw).html());
          });
          $s.toggle(c > 0).find('.fp-cnt .n').text(c).next().text(c === 1 ? ' feature' : ' features'); n += c;
        });
        /* live counts in the sidebar */
        $r.find('.fp-c').each(function () {
          var k = $(this).data('cat'), c = 0;
          $r.find(k === 'all' ? '.fp-f' : '.fp-sec[data-cat="' + k + '"] .fp-f').each(function () {
            if ((st.plan === 'all' || $(this).data('t') === st.plan) && (!q || $(this).data('n').indexOf(q) > -1)) c++;
          });
          $(this).toggleClass('zero', !c).find('em').text(c);
        });
      } else {
        $r.find('.md').each(function () {
          var $m = $(this), ok = (st.mc === 'all' || $m.data('mc') === st.mc) && (st.plan === 'all' || $m.data('t') === st.plan) && (!q || $m.data('n').indexOf(q) > -1);
          $m.toggle(ok); if (ok) n++;
        });
      }
      $r.find('[data-fp-n]').text(n); $r.find('[data-fp-l]').text(st.view === 'feat' ? (n === 1 ? 'feature' : 'features') : (n === 1 ? 'module' : 'modules'));
      $r.find('.fp-empty').prop('hidden', n > 0);
    }
    $q.on('input', function () { st.q = this.value; $q.not(this).val(this.value); apply(); });
    $q.first().on('keydown', function (e) { if (e.key === 'Enter') $('html,body').animate({ scrollTop: $r.offset().top - 90 }, 400); });
    $(document).on('keydown', function (e) { if (e.key === '/' && !$(e.target).is('input,textarea')) { e.preventDefault(); $q.first().focus(); } });
    $r.find('[data-plan]').on('click', function () { st.plan = $(this).data('plan'); $(this).addClass('on').siblings().removeClass('on'); apply(); });
    $r.find('[data-view]').on('click', function () {
      st.view = $(this).data('view'); $(this).addClass('on').siblings().removeClass('on');
      $r.toggleClass('mod', st.view === 'mod'); $r.find('[data-only]').each(function () { this.hidden = $(this).data('only') !== st.view; });
      $r.find('.fp-bar .fp-search input').attr('placeholder', st.view === 'feat' ? 'Search features' : 'Search modules'); apply();
    });
    $r.find('.fp-c').on('click', function () {
      st.cat = $(this).data('cat'); $r.find('.fp-c').removeClass('on'); $(this).addClass('on');
      $r.find('[data-fp-cur]').text($(this).find('span').text()); $r.find('.fp-side').removeClass('open'); apply();
      $('html,body').animate({ scrollTop: $r.offset().top - 90 }, 400);
    });
    $r.find('.fp-side-t').on('click', function () { $(this).parent().toggleClass('open'); });
    $r.find('.fp-mc').on('click', function () { st.mc = $(this).data('mc'); $(this).addClass('on').siblings().removeClass('on'); apply(); });
    $r.find('[data-fp-reset]').on('click', function () {
      st.q = ''; $q.val(''); st.plan = 'all'; st.cat = 'all'; st.mc = 'all';
      $r.find('[data-plan="all"],.fp-c[data-cat="all"],.fp-mc[data-mc="all"]').trigger('click');
    });
  });


  /* sticky section nav with scrollspy (vs Dokan, compare) */
  $('[data-spy]').each(function () {
    var $n = $(this), $a = $n.find('a[href^="#"]'), $in = $n.find('.dk-nav-in');
    function spy() {
      var y = $w.scrollTop() + 170, cur = $a.first();
      $a.each(function () { var $t = $($(this).attr('href')); if ($t.length && $t.offset().top <= y) cur = $(this); });
      if (!cur.hasClass('on')) { $a.removeClass('on'); cur.addClass('on'); $in.stop().animate({ scrollLeft: cur[0].offsetLeft - 40 }, 250); }
    }
    $w.on('scroll', spy); spy();
  });


  /* compare features: search, area, differences, exclusives, collapse */
  $('[data-cf]').each(function () {
    var $r = $(this), $rows = $r.find('.cr');
    $rows.each(function () { var $f = $(this).children('.f'); $f.data('raw', $f.contents().first().text()); });
    function apply() {
      var q = ($r.find('[data-cf-q]').val() || '').trim().toLowerCase(), cat = $r.find('[data-cf-cat]').val(),
          diff = $r.find('[data-cf-diff]').prop('checked'), ex = $r.find('[data-cf-ex]').prop('checked'), n = 0,
          re = q ? new RegExp('(' + q.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'ig') : null;
      $r.find('.cs').each(function () {
        var $s = $(this), c = 0, okc = cat === 'all' || $s.data('cat') === cat;
        $s.find('.cr').each(function () {
          var $c = $(this), ok = okc && (!q || $c.data('n').indexOf(q) > -1) && (!diff || $c.data('diff') == 1) && (!ex || $c.data('ex') == 1);
          $c.toggle(ok); if (ok) c++;
          var $t = $c.children('.f').contents().first(), raw = $c.children('.f').data('raw'), h = $('<i>').text(raw).html();
          $t.replaceWith(re && ok ? $('<span>').html(h.replace(re, '<mark>$1</mark>')) : $('<span>').html(h));
        });
        $s.toggle(c > 0); if (q || diff || ex || cat !== 'all') $s.addClass('open'); n += c;
      });
      $r.find('[data-cf-n]').text(n); $r.find('.fp-empty').prop('hidden', n > 0);
    }
    $r.find('[data-cf-q]').on('input', apply);
    $r.find('[data-cf-cat],[data-cf-diff],[data-cf-ex]').on('change', apply);
    $r.find('.cs-h').on('click', function () { $(this).parent().toggleClass('open'); });
    $r.find('[data-cf-fold]').on('click', function () {
      var open = $(this).text() === 'Expand all'; $r.find('.cs').toggleClass('open', open); $(this).text(open ? 'Collapse all' : 'Expand all');
    });
  });


  /* blog: category chips + search */
  $('[data-bl]').each(function () {
    var $r = $(this), cat = 'All', $q = $('[data-bl-q]');
    function apply() {
      var q = ($q.val() || '').trim().toLowerCase(), n = 0;
      $r.find('.bl-card').each(function () {
        var ok = (cat === 'All' || $(this).data('bc') === cat) && (!q || $(this).data('n').indexOf(q) > -1);
        $(this).toggle(ok); if (ok) n++;
      });
      $r.find('[data-bl-n]').text(n); $r.find('.fp-empty').prop('hidden', n > 0);
    }
    $r.find('.bl-c').on('click', function () { cat = $(this).data('bc'); $(this).addClass('on').siblings().removeClass('on'); apply(); });
    $q.on('input', function () { apply(); if (this.value.length === 1) $('html,body').animate({ scrollTop: $r.offset().top - 90 }, 400); });
  });

  /* accordion */
  $('.faq .q').on('click', function () {
    var $it = $(this).closest('.item'), open = $it.hasClass('open');
    $it.siblings('.open').removeClass('open').find('.a').slideUp(240);
    $it.toggleClass('open', !open).find('.a').stop().slideToggle(240);
  });
  $('.faq .item.open .a').show();

  /* rotating words */
  $('[data-rotate]').each(function () {
    var $e = $(this), words = $e.data('rotate').split('|'), i = 0;
    setInterval(function () {
      i = (i + 1) % words.length;
      $e.addClass('out'); setTimeout(function () { $e.text(words[i]).removeClass('out'); }, 300);
    }, 2400);
  });

  /* pricing: show full comparison */
  $('[data-expand]').on('click', function () {
    var $t = $($(this).data('expand')); $t.toggleClass('expanded');
    $(this).find('span').text($t.hasClass('expanded') ? 'Show less' : 'Show the full comparison');
  });

  /* fake form submit (static site) */
  $('form[data-demo]').on('submit', function (e) {
    e.preventDefault(); var $f = $(this);
    $f.find('[type=submit]').text('Sent ✓').prop('disabled', true);
    $f.find('.form-ok').slideDown(200);
  });

  /* tilt on hover for .tilt elements */
  $('.tilt').on('mousemove', function (e) {
    var o = $(this).offset(), x = (e.pageX - o.left) / $(this).outerWidth() - .5, y = (e.pageY - o.top) / $(this).outerHeight() - .5;
    $(this).css('transform', 'perspective(1200px) rotateY(' + (x * 6) + 'deg) rotateX(' + (-y * 6) + 'deg)');
  }).on('mouseleave', function () { $(this).css('transform', ''); });
});
