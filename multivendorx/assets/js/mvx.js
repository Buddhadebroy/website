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
    if (!$(this).closest('.p5-still').length) return; /* only the multilingual card rotates */
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
  $('.tilt-off').on('mousemove', function (e) {
    var o = $(this).offset(), x = (e.pageX - o.left) / $(this).outerWidth() - .5, y = (e.pageY - o.top) / $(this).outerHeight() - .5;
    $(this).css('transform', 'perspective(1200px) rotateY(' + (x * 6) + 'deg) rotateX(' + (-y * 6) + 'deg)');
  }).on('mouseleave', function () { $(this).css('transform', ''); });
});

/* v3: vendor dashboard demo cycles its panes while visible */
jQuery(function ($) {
  $('[data-vd]').each(function () {
    var $d = $(this), $b = $d.find('.vd-nav button'), i = 0, t = null, vis = false, hov = false;
    function go(n) { i = n % $b.length; $b.removeClass('on').eq(i).addClass('on'); $d.find('.vd-p').removeClass('on').eq(i).addClass('on'); }
    function tick() { clearInterval(t); t = setInterval(function () { if (vis && !hov) go(i + 1); }, 4200); }
    $b.on('click', function () { go($b.index(this)); });
    $d.on('mouseenter', function () { hov = true; }).on('mouseleave', function () { hov = false; });
    /* auto-cycling removed: panes change only on click */
  });
});

/* v3: tools tabs */
jQuery(function ($) {
  $('[data-tlx]').each(function () {
    var $x = $(this);
    $x.on('click', '.tl-t', function () {
      var id = $(this).data('tl');
      $x.find('.tl-t').removeClass('on'); $(this).addClass('on');
      $x.find('.tl-p').removeClass('on').filter('[data-tl="' + id + '"]').addClass('on');
    });
  });
});

/* v3: marketplace type tabs */
jQuery(function ($) {
  $('[data-mtx]').on('click', '.mt-t', function () {
    var $x = $(this).closest('[data-mtx]'), i = $(this).data('mt');
    $x.find('.mt-t').removeClass('on'); $(this).addClass('on');
    $x.find('.mt-p').removeClass('on').filter('[data-mt="' + i + '"]').addClass('on');
  });
});


$(function () {
  /* vs Dokan: feature tables with fixed side counter */
  $('[data-fscroll]').each(function () {
    var $w = $(this), $cats = $w.find('.fs-cat'), $li = $w.find('[data-fs-i]');
    var total = +$w.find('[data-fs-total]').data('fs-total') || 1, cur = -1;
    var $tm = $w.find('[data-fs-tm]'), $td = $w.find('[data-fs-td]'), $bm = $w.find('[data-fs-bm]'), $bd = $w.find('[data-fs-bd]');
    function count($el, to) {
      var el = $el[0]; if (!el) return;
      if (el._raf) cancelAnimationFrame(el._raf);
      var from = +el.textContent || 0, t0 = null; if (from === to) return;
      (function step(t) { if (!t0) t0 = t; var k = Math.min(1, (t - t0) / 450);
        el.textContent = Math.round(from + (to - from) * k); if (k < 1) el._raf = requestAnimationFrame(step); })(performance.now());
    }
    function upd() {
      var line = window.innerHeight * 0.45, idx = 0;
      $cats.each(function (i) { if (this.getBoundingClientRect().top <= line) idx = i; });
      if (idx === cur) return; cur = idx;
      var m = 0, d = 0;
      $cats.each(function (i) { if (i <= idx) { m += +$(this).data('m'); d += +$(this).data('d'); } });
      $li.each(function (i) { $(this).toggleClass('done', i < idx).toggleClass('on', i === idx); });
      count($tm, m); count($td, d);
      $bm.css('--w', (m / total * 100) + '%'); $bd.css('--w', (d / total * 100) + '%');
    }
    $li.on('click', function () {
      var t = $cats.eq(+$(this).data('fs-i')).offset().top - 150;
      $('html,body').animate({ scrollTop: t }, 500);
    });
    $(window).on('scroll resize', upd); upd();
  });
});

/* home: marketplace types change tab on scroll */
$(function () {
  var $pin = $('[data-mtx-pin]'); if (!$pin.length) return;
  var $x = $pin.find('[data-mtx]'), $t = $x.find('.mt-t'), n = $t.length, cur = -1;
  function active() { return $pin.height() > window.innerHeight + 100; }
  function show(i) {
    if (i === cur) return; cur = i;
    $t.removeClass('on').eq(i).addClass('on');
    $x.find('.mt-p').removeClass('on').filter('[data-mt="' + i + '"]').addClass('on');
  }
  function upd() {
    if (!active()) { $t.removeClass('done').find('.mt-pr').css('width', ''); return; }
    var top = $pin.offset().top, span = $pin.height() - window.innerHeight;
    var p = Math.min(0.9999, Math.max(0, ($(window).scrollTop() - top) / span));
    var f = p * n, i = Math.floor(f);
    show(i);
    $t.each(function (k) { $(this).toggleClass('done', k < i).find('.mt-pr').css('width', k === i ? ((f - i) * 100) + '%' : (k < i ? '100%' : '0')); });
  }
  $x.on('click', '.mt-t', function (e) {
    if (!active()) return;
    e.stopImmediatePropagation();
    var i = +$(this).data('mt'), top = $pin.offset().top, span = $pin.height() - window.innerHeight;
    $('html,body').animate({ scrollTop: top + span * (i + 0.05) / n }, 450);
  });
  $(window).on('scroll resize', upd); upd();
});


/* Home: FAQ accordion + modules filter (uploaded design) */
$(function(){
  var $f=$('.rf [data-faq]');
  if($f.length){
    var paint=function($it,open){
      var $b=$it.children('button'),$s=$b.children('span');
      $b.attr('aria-expanded',open?'true':'false');
      $it.children('p').prop('hidden',!open);
      $s.eq(0).css('color',open?'#5007aa':'#9ca3af');
      $s.eq(2).css({background:open?'#5007aa':'#f4eefb',color:open?'#fff':'#5007aa',transform:'rotate('+(open?45:0)+'deg)'});
    };
    $f.children('button').on('click',function(){
      var $it=$(this).parent(),open=$(this).attr('aria-expanded')!=='true';
      $f.each(function(){paint($(this),false)});
      if(open)paint($it,true);
    });
  }
  var $m=$('#modules');
  if(!$m.length)return;
  var CATS={"all": ["All modules", "Every module in MultiVendorX. Each one is a switch."], "marketplace_types": ["Marketplace Types", "Decide what kind of marketplace you run."], "product_types": ["Product Types", "Let stores sell more than simple products."], "store_management": ["Store Management", "The tools each store uses to run its shop."], "payment_management": ["Payment Management", "How money reaches your stores."], "shipping_management": ["Shipping Management", "How stores price and send parcels."], "customer_experience": ["Customer Experience", "Help shoppers trust and talk to stores."], "analytics_tools": ["Marketing Tools", "Help stores get found and sell more."], "marketplace_boosters": ["Marketplace Boosters", "Grow revenue and keep quality high."], "notification": ["Notification", "Keep every store in the loop."], "integration": ["Integration", "Connect the plugins you already use."]},cat='all',tier='all',$cards=$m.find('.rf-mxcard');
  var inT=function($c){var p=$c.attr('data-p')==='1';return tier==='all'||(tier==='pro'?p:!p)};
  var inC=function($c,id){return id==='all'||(' '+$c.attr('data-c')+' ').indexOf(' '+id+' ')>-1};
  var draw=function(){
    var n=0;
    $cards.each(function(){var $c=$(this),on=inC($c,cat)&&inT($c);$c.toggleClass('is-off',!on);if(on)n++});
    $m.find('[data-cat]').each(function(){
      var $b=$(this),id=$b.attr('data-cat'),on=id===cat,k=0;
      $cards.each(function(){if(inC($(this),id)&&inT($(this)))k++});
      $b.css({background:on?'#f1ebfb':'transparent',color:on?'#5007aa':'#3d4155',fontWeight:on?700:500}).attr('aria-pressed',on);
      $b.children('span').last().text(k);
    });
    $m.find('[data-tier]').each(function(){
      var $b=$(this),on=$b.attr('data-tier')===tier;
      $b.attr('aria-pressed',on);
    });
    var c=CATS[cat]||CATS.all;
    $m.find('[data-mt]').text(c[0]);$m.find('[data-mb]').text(c[1]);
    $m.find('[data-mn]').text(n===1?'1 module':n+' modules');
  };
  $m.on('click','[data-cat]',function(){cat=$(this).attr('data-cat');draw()});
  $m.on('click','[data-tier]',function(){tier=$(this).attr('data-tier');draw()});
});

/* Home: marketplace type tabs + "show all modules" */
$(function(){
  var $t=$('.nx-types');
  if($t.length){
    $t.on('click','.nx-tab',function(){
      var i=$(this).attr('data-tab');
      $t.find('.nx-tab').removeClass('is-on').attr('aria-selected','false');
      $(this).addClass('is-on').attr('aria-selected','true');
      $t.find('.nx-panel').each(function(){var on=$(this).attr('data-panel')===i;$(this).toggleClass('is-on',on).prop('hidden',!on)});
    });
  }
  var $w=$('.nx-mods');
  if(!$w.length)return;
  var open=false,LIMIT=12;
  var cut=function(){
    var n=0;
    $w.find('.rf-mxcard').each(function(){var $c=$(this);$c.removeClass('is-cut');if(!$c.hasClass('is-off')){n++;if(n>LIMIT)$c.addClass('is-cut')}});
    var short=!open&&n>LIMIT;
    $w.toggleClass('is-short',short);
    $w.find('.nx-more').prop('hidden',!short).find('button').text('Show all '+n+' modules');
  };
  $w.on('click','[data-cat],[data-tier]',function(){setTimeout(cut,0)});
  $('#modules').on('click','[data-tier]',function(){setTimeout(cut,0)});
  $w.on('click','[data-more]',function(){open=true;cut()});
  cut();
});

/* Home: how-it-works stepper */
$(function(){
  var $j=$('.nx-journey');
  if(!$j.length)return;
  var go=function(i){
    i=Math.max(0,Math.min(3,+i));
    $j[0].style.setProperty('--at',i);
    $j.find('.nx-node').each(function(k){$(this).toggleClass('is-on',k===i).toggleClass('is-done',k<i).attr('aria-current',k===i?'step':null)});
    $j.find('.nx-stage-i').each(function(k){$(this).toggleClass('is-on',k===i).prop('hidden',k!==i)});
  };
  $j.on('click','.nx-node',function(){go($(this).attr('data-step'))});
  $j.on('click','button[data-go]',function(){go($(this).attr('data-go'))});
  go(0);
});

/* Home: how-it-works scroll story */
$(function(){
  var $sx=$('.sx');
  if(!$sx.length)return;
  var $items=$sx.find('.sx-item'),$panes=$sx.find('.sx-pane'),cur=-1;
  var set=function(i){
    if(i===cur)return;cur=i;
    $items.each(function(k){$(this).toggleClass('is-on',k===i).toggleClass('is-done',k<i)});
    $panes.each(function(k){$(this).toggleClass('is-on',k===i)});
    $sx[0].style.setProperty('--sxp',(i/($items.length-1)*100)+'%');
  };
  var pick=function(){
    var mid=window.innerHeight*0.5,best=0,bd=1e9;
    $panes.each(function(k){var r=this.getBoundingClientRect(),c=r.top+r.height/2,d=Math.abs(c-mid);if(d<bd){bd=d;best=k}});
    set(best);
  };
  $(window).on('scroll resize',function(){window.requestAnimationFrame(pick)});
  $items.on('click',function(){
    var $p=$panes.eq(+$(this).attr('data-sx'));
    window.scrollTo({top:$p.offset().top-(window.innerHeight-$p.outerHeight())/2,behavior:'smooth'});
  });
  pick();
});

/* Home: migration animation (ported from the uploaded design) */
$(function(){
  var $m=$('#migrate.mg');
  if(!$m.length)return;
  var A='#5007aa',t=0,src=0,ys=[21,48,75];
  var TOT={rs:48,rp:1284,ro:3906},SP={rs:[12,40],rp:[28,72],ro:[48,96]};
  var STEPS=[[0,12],[12,40],[28,72],[48,96],[96,999]];
  var ICON={rs:['#5007aa','M3 9l1.5-5h15L21 9M3 9h18v2a3 3 0 0 1-6 0 3 3 0 0 1-6 0 3 3 0 0 1-6 0zM5 14v6h14v-6'],rp:['#2563eb','M21 8l-9-5-9 5 9 5 9-5zM3 8v8l9 5 9-5V8M12 13v8'],ro:['#16a34a','M6 2h12v20l-3-2-3 2-3-2-3 2zM9 7h6M9 11h6M9 15h4']};
  var TY={rs:34,rp:52,ro:70},DL={rs:[0,.8],rp:[.3,1.1],ro:[.6,1.4]};
  var $pk=$m.find('[data-pk]'),flows={};
  var pkHtml=function(k){var y=ys[src];return DL[k].map(function(d){return '<span style="--x1:30%;--y1:'+y+'%;--x2:60%;--y2:'+TY[k]+'%;animation-delay:'+d+'s"><span><svg viewBox="0 0 24 24" stroke="'+ICON[k][0]+'"><path d="'+ICON[k][1]+'"/></svg></span></span>'}).join('')};
  var prog=function(a,b){return Math.max(0,Math.min(1,(t-a)/(b-a)))};
  var render=function(){
    var done=t>=96,scan=t<12;
    $.each(TOT,function(k,tot){
      var p=prog(SP[k][0],SP[k][1]),$r=$m.find('[data-row="'+k+'"]');
      $r.find('[data-cnt]').text(Math.round(tot*p).toLocaleString('en-US'));
      $r.find('[data-bar]').css('width',(p*100).toFixed(1)+'%');
      $r.find('[data-dot]').text(p>=1?'✓':'').css('background',p>=1?'#16a34a':(p>0?A:'#d4d4dc'));
      var on=t>=SP[k][0]&&t<SP[k][1];
      if(on!==!!flows[k]){flows[k]=on;$pk.find('[data-k="'+k+'"]').remove();if(on)$pk.append($('<div data-k="'+k+'" style="display:contents">').html(pkHtml(k)))}
    });
    $m.find('[data-src]').each(function(){
      var i=+$(this).attr('data-src'),on=i===src;
      $(this).css({borderColor:on?A:'#ece6f6',opacity:on?1:.45});
      $(this).children('span').first().css('background',on?A:'#b6b3c4');
      $(this).find('[data-st]').text(on?(scan?'Scanning…':(done?'Exported ✓':'Exporting…')):'Supported');
    });
    $m.find('[data-line]').each(function(){var y=ys[src];$(this).attr('d','M30 '+y+' C45 '+y+' 45 '+[34,52,70][+$(this).attr('data-line')]+' 60 '+[34,52,70][+$(this).attr('data-line')])});
    $m.find('[data-mh]').text(done?'Complete':(scan?'Waiting':'Importing…')).css('color',done?'#15803d':A);
    $m.find('[data-done]').css('opacity',done?1:0);
    $m.find('[data-mstep]').each(function(){
      var i=+$(this).attr('data-mstep'),a=STEPS[i][0],b=STEPS[i][1],isDone=t>=b,isOn=t>=a&&t<b;
      $(this).css({background:isOn?A:'#fff',color:isOn?'#fff':(isDone?'#15803d':'#6b7280'),borderColor:isOn?A:(isDone?'#bbf7d0':'#ece6f6')});
      $(this).children('span').first().text(isDone?'✓':(i+1)).css('background',isOn?'rgba(255,255,255,.3)':(isDone?'#16a34a':'#c9c6d6'));
    });
  };
  var reduce=window.matchMedia&&window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  render();
  setInterval(function(){t++;if(t>125){t=0;src=(src+1)%3}render()},80);
});

/* Home: how-it-works timeline reveal + progress line */
$(function(){
  var $hw=$('.hw');
  if(!$hw.length)return;
  var rows=$hw.find('.hw-row').get();
  var tick=function(){
    var r=$hw[0].getBoundingClientRect(),vh=window.innerHeight;
    var p=Math.max(0,Math.min(1,(vh*.6-r.top)/r.height));
    $hw[0].style.setProperty('--hwp',p.toFixed(3));
    rows.forEach(function(el){if(el.getBoundingClientRect().top<vh*.78)el.classList.add('is-in')});
  };
  $(window).on('scroll resize',tick);tick();
});

/* Home: type cards spotlight follows the pointer */
$(function(){
  var $g=$('.bxc .bx');
  if(!$g.length)return;
  $g.on('pointermove',function(e){var r=this.getBoundingClientRect();this.style.setProperty('--mx',(e.clientX-r.left)+'px');this.style.setProperty('--my',(e.clientY-r.top)+'px')});
});
/* niche playbook: sticky stage tracker */
$(function(){
  var nav=document.querySelector('.np-snav'); if(!nav)return;
  var secs=[].slice.call(document.querySelectorAll('section[data-stage]')), links=[].slice.call(nav.querySelectorAll('a'));
  function upd(){
    var mid=window.innerHeight*0.45, cur=-1;
    secs.forEach(function(s){var r=s.getBoundingClientRect(); if(r.top<mid&&r.bottom>mid) cur=+s.getAttribute('data-stage');});
    nav.classList.toggle('show',cur>=0);
    links.forEach(function(a,i){a.classList.toggle('on',i===cur);a.classList.toggle('done',cur>=0&&i<cur);});
  }
  window.addEventListener('scroll',upd,{passive:true}); window.addEventListener('resize',upd); upd();
});

/* features page: readable list; area nav scrollspy, plan and search filters */
$(function(){
  var $r=$('[data-fl]'); if(!$r.length)return;
  var st={p:'all',q:''},$a=$r.find('.fl-nav a'),secs=$a.map(function(){return document.querySelector($(this).attr('href'));}).get();
  function run(){
    var n=0;
    $r.find('.fl-s').each(function(){var c=0;$(this).find('li').each(function(){var $l=$(this),ok=(st.p==='all'||$l.data('p')===st.p)&&(!st.q||$l.text().toLowerCase().indexOf(st.q)>-1);$l.toggle(ok);if(ok)c++;});$(this).toggle(c>0);n+=c;});
    $r.find('.fl-empty').prop('hidden',n>0);spy();
  }
  function spy(){var k=-1;secs.forEach(function(s,i){if(s&&s.offsetParent&&s.getBoundingClientRect().top<=160)k=i;});$a.removeClass('on');if(k>-1)$a.eq(k).addClass('on');
    var on=$a.filter('.on')[0];if(on&&window.innerWidth<=900){var nav=on.parentNode;nav.scrollLeft=on.offsetLeft-nav.clientWidth/2+on.clientWidth/2;}}
  $r.on('input','[data-fl-q]',function(){st.q=$.trim(this.value).toLowerCase();run();});
  $r.on('click','[data-fl-p]',function(){$r.find('[data-fl-p]').removeClass('on');$(this).addClass('on');st.p=$(this).data('fl-p');run();});
  $(window).on('scroll',spy);spy();
});

/* compare features: search, differences, exclusives, collapse, area nav */
$(function(){
  var $r=$('[data-nc]'); if(!$r.length)return;
  var $rows=$r.find('.nc-r');
  $rows.each(function(){var $n=$(this).find('.nm');$n.data('raw',$n.text());});
  function apply(){
    var q=$.trim($r.find('[data-nc-q]').val()||'').toLowerCase(),diff=$r.find('[data-nc-diff]').prop('checked'),ex=$r.find('[data-nc-ex]').prop('checked'),n=0,
        re=q?new RegExp('('+q.replace(/[.*+?^${}()|[\]\\]/g,'\\$&')+')','ig'):null;
    $r.find('.nc-s').each(function(){
      var $s=$(this),c=0;
      $s.find('.nc-r').each(function(){
        var $c=$(this),$n=$c.find('.nm'),raw=$n.data('raw'),ok=(!q||raw.toLowerCase().indexOf(q)>-1)&&(!diff||$c.data('diff')==1)&&(!ex||$c.data('ex')==1);
        $c.toggle(ok);if(ok)c++;
        var h=$('<i>').text(raw).html();$n.html(re&&ok?h.replace(re,'<mark>$1</mark>'):h);
      });
      $s.toggle(c>0);if(q||diff||ex)$s.addClass('open');n+=c;
    });
    $r.find('[data-nc-n]').text(n);$r.find('.nc-empty').prop('hidden',n>0);
  }
  $r.on('input','[data-nc-q]',apply);
  $r.on('change','[data-nc-diff],[data-nc-ex]',apply);
  $r.on('click','.nc-sh',function(){var $s=$(this).parent().toggleClass('open');$(this).attr('aria-expanded',$s.hasClass('open'));});
  $r.on('change','[data-nc-jump]',function(){var el=document.getElementById(this.value);if(el){$(el).addClass('open');el.scrollIntoView({behavior:'smooth'});}});
  var $a=$r.find('.nc-side a'),secs=$a.map(function(){return document.querySelector($(this).attr('href'));}).get();
  $a.on('click',function(){$($(this).attr('href')).addClass('open');});
  function spy(){var k=-1;secs.forEach(function(s,i){if(s&&s.offsetParent&&s.getBoundingClientRect().top<=220)k=i;});$a.removeClass('on');if(k>-1)$a.eq(k).addClass('on');}
  $(window).on('scroll',spy);spy();
});

/* vs Dokan: pricing calculator (yearly list prices, checked 27 Nov 2026) */
$(function () {
  var $c = $('[data-dzcalc]'); if (!$c.length) return;
  var st = { s: 0, f: 2 };
  var DK = [
    { n: 'Starter', s: '1 site', m: '3 modules', p: 149 },
    { n: 'Professional', s: '3 sites', m: '23 modules', p: 249 },
    { n: 'Business', s: '5 sites', m: '42+ modules', p: 499 },
    { n: 'Enterprise', s: '10 sites', m: '42+ modules', p: 4995, life: 1 }
  ];
  var MX = [
    { n: 'Starter', s: '1 site', p: 149 }, { n: 'Growth', s: '3 sites', p: 199 },
    { n: 'Agency', s: '10 sites', p: 299 }, { n: 'Agency', s: '10 sites', p: 299 }
  ];
  function f(n) { return '$' + n.toLocaleString('en-US'); }
  function o(k) { return $c.find('[data-o="' + k + '"]'); }
  function upd() {
    var d = DK[Math.max(st.s, st.f)], m = MX[st.s];
    o('mp').text(m.n + ' · ' + m.s + ' · every module'); o('mv').text(f(m.p)); o('mu').text('/ year');
    o('dp').text(d.n + ' · ' + d.s + ' · ' + d.m); o('dv').text(f(d.p)); o('du').text(d.life ? 'one time' : '/ year');
    var $s = o('sv').parent();
    if (d.life) {
      o('mb').css('--w', '30%'); o('db').css('--w', '100%');
      o('sv').text('Dokan has no yearly plan for 10 sites');
      o('s3').text('Its lifetime Enterprise plan equals ' + Math.floor(d.p / m.p) + ' years of MultiVendorX Agency.');
      $s.removeClass('flip'); return;
    }
    var hi = Math.max(d.p, m.p);
    o('mb').css('--w', (m.p / hi * 100) + '%'); o('db').css('--w', (d.p / hi * 100) + '%');
    var sv = d.p - m.p;
    if (sv > 0) { o('sv').text('You keep ' + f(sv) + ' a year'); o('s3').text('That is ' + f(sv * 3) + ' over three years, with every module included.'); $s.removeClass('flip'); }
    else { o('sv').text('Same price, more included'); o('s3').text('MultiVendorX gives every module at this price. Dokan ' + d.n + ' has ' + d.m + '.'); $s.addClass('flip'); }
  }
  $c.on('click', '.dz-seg button', function () {
    var $b = $(this), $g = $b.parent(); $g.find('button').removeClass('on'); $b.addClass('on');
    st[$g.data('k')] = +$b.data('v'); upd();
  });
  upd();
});

/* vs Dokan: feature explorer (area tabs, filter, search) */
$(function () {
  var $x = $('[data-dzfx]'); if (!$x.length) return;
  var $t = $x.find('.dz-fx-t'), $p = $x.find('.dz-fx-p'), $q = $x.find('[data-q]'), $st = $x.find('[data-st]'), $e = $x.find('[data-empty]'), f = 'all';
  function show(i) {
    $t.removeClass('on').attr('aria-selected', 'false').eq(i).addClass('on').attr('aria-selected', 'true');
    $p.prop('hidden', true).eq(i).prop('hidden', false);
  }
  function apply() {
    var q = ($q.val() || '').trim().toLowerCase(), on = q || f !== 'all', n = 0;
    $x.toggleClass('searching', !!q).toggleClass('open-all', on);
    $x.find('.dz-fx-r').each(function () {
      var r = this.dataset, ok = (!q || r.q.indexOf(q) > -1) && (f === 'all' || r[f] === '1');
      $(this).toggleClass('hide', !ok); if (ok) n++;
    });
    $x.find('.dz-fx-g').each(function () { $(this).toggleClass('none', on && !$(this).find('.dz-fx-r:not(.hide)').length); });
    $p.each(function () { $(this).toggleClass('none', !!q && !$(this).find('.dz-fx-r:not(.hide)').length); });
    var vis = q ? n : $p.not('[hidden]').find('.dz-fx-r:not(.hide)').length;
    $st.text(on ? (q ? n + ' of 258 features match' : vis + ' features in this area') : '');
    $e.prop('hidden', !(q && !n));
  }
  $t.on('click', function () {
    if ($q.val()) { $q.val(''); }
    show(+$(this).data('t')); apply();
    if (window.innerWidth < 980) this.scrollIntoView({ block: 'nearest', inline: 'center', behavior: 'smooth' });
  });
  $x.find('[data-f] button').on('click', function () {
    $(this).addClass('on').siblings().removeClass('on'); f = $(this).data('v'); apply();
  });
  $q.on('input', apply);
  var $b = $x.find('[data-body]'), $m = $x.find('.dz-fx-main');
  function edge() { var b = $b[0]; $m.toggleClass('at-end', b.scrollTop + b.clientHeight >= b.scrollHeight - 4); }
  $b.on('scroll', edge);
  $t.on('click', function () { $b.scrollTop(0); edge(); });
  $x.on('click', '.dz-fx-next', function () { $t.eq(+$(this).data('next')).trigger('click').trigger('focus'); });
  $t.on('keydown', function (e) {
    var k = e.key, i = $t.index(this), n = $t.length, j = -1;
    if (k === 'ArrowDown' || k === 'ArrowRight') j = (i + 1) % n;
    else if (k === 'ArrowUp' || k === 'ArrowLeft') j = (i - 1 + n) % n;
    else if (k === 'Home') j = 0; else if (k === 'End') j = n - 1;
    if (j > -1) { e.preventDefault(); $t.eq(j).trigger('click').trigger('focus'); }
  });
  $q.on('input', function () { $b.scrollTop(0); edge(); });
  apply(); edge();
});

/* vs Dokan (r49): flip tiles loop + sticky bar */
jQuery(function ($) {
  var $t = $('[data-flip] .bw-tile'); if (!$t.length) return;
  var rm = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var i = 0, paused = false, $wrap = $('[data-flip]');
  $wrap.on('mouseenter', function () { paused = true; $t.removeClass('f'); }).on('mouseleave', function () { paused = false; });
  if (!rm) setInterval(function () {
    if (paused || document.hidden) return;
    $t.removeClass('f'); $t.eq(i % $t.length).addClass('f'); i++;
  }, 2200);
});
jQuery(function ($) {
  var $s = $('[data-bwsticky]'), $h = $('.bw-hero'), $end = $('.bw-fin'); if (!$s.length) return;
  $(window).on('scroll', function () {
    var y = window.scrollY, past = y > $h.offset().top + $h.outerHeight() - 100;
    var atEnd = y + window.innerHeight > $end.offset().top + 120;
    var on = past && !atEnd; $s.toggleClass('on', on).attr('aria-hidden', on ? 'false' : 'true');
    $s.find('a').attr('tabindex', on ? '0' : '-1');
  });
});

/* vs Dokan (r50): idea picker tabs */
jQuery(function ($) {
  var $x = $('[data-ideas]'); if (!$x.length) return;
  $x.on('click', '.bw-it', function () {
    var i = $(this).data('i');
    $x.find('.bw-it').removeClass('on').attr('aria-selected', 'false');
    $(this).addClass('on').attr('aria-selected', 'true');
    $x.find('.bw-ip').removeClass('on').attr('hidden', true);
    $x.find('.bw-ip[data-p="' + i + '"]').addClass('on').removeAttr('hidden');
    if (window.innerWidth < 900) this.scrollIntoView({ block: 'nearest', inline: 'center', behavior: 'smooth' });
  });
});
