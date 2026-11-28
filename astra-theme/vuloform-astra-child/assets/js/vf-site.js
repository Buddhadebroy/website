/*!
 * VuloForm site widgets (no dependencies). Effects (reveal, scroll, marquee) live in vf-effects.js.
 *
 *   1. Header        scrolled glass state, mobile menu
 *   2. Tabs          .vf-tabs > .vf-tab (+ .vf-tab-label, or your own .vf-tab-list of buttons or Kadence text blocks)
 *   3. Accordion     .vf-accordion > .vf-acc-item > .vf-acc-q + .vf-acc-a (one open at a time; Kadence text blocks too)
 *   4. Copy button   .vf-copy copies the visible .vf-code
 *   5. Demos         data-vf="hero-form" | "conditional" | "calc" | "builder-app"
 *   6. Blog TOC      highlights the heading you are reading
 */
(function () {
	'use strict';

	var doc = document;
	var $ = function (s, c) { return (c || doc).querySelector(s); };
	var $$ = function (s, c) { return Array.prototype.slice.call((c || doc).querySelectorAll(s)); };
	var reduce = window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches;

	/* 1. Header -------------------------------------------------------------- */
	function initNav() {
		// The header is the synced pattern "VuloForm Header" (Kadence Row Layout with class vf-site-header).
		var nav = $('.vf-site-header') || $('[data-vf="nav"]');
		if (!nav) return;
		var onScroll = function () { nav.classList.toggle('is-scrolled', window.scrollY > 24); };
		window.addEventListener('scroll', onScroll, { passive: true });
		onScroll();
		var btn = $('.vf-burger', nav), menu = $('.vf-mobile', nav);
		if (!btn || !menu) return;
		// The phone menu hangs under the pill (top-20 left-4 right-4 of the header), not inside it.
		nav.appendChild(menu);
		// Last item = a copy of the header button (Kadence button with class vf-nav-cta): edit the button in one place.
		var cta = $('.vf-nav-cta a', nav);
		if (cta && !$('.vf-mobile-cta', menu)) {
			var li = doc.createElement('li'), link = doc.createElement('a');
			link.href = cta.href;
			link.className = 'vf-mobile-cta';
			link.textContent = cta.textContent.trim();
			li.appendChild(link);
			menu.appendChild(li);
		}
		var set = function (open) { btn.setAttribute('aria-expanded', open); menu.hidden = !open; nav.classList.toggle('is-open', open); };
		btn.addEventListener('click', function () { set(menu.hidden); });
		$$('a', menu).forEach(function (a) { a.addEventListener('click', function () { set(false); }); });
	}

	/* 2. Tabs: builds pill buttons from each .vf-tab-label, or uses the buttons in .vf-tab-list. ---- */
	function initTabs(ctx) {
		$$('.vf-tabs', ctx).forEach(function (box, n) {
			if (box.dataset.vfTabs) return;
			box.dataset.vfTabs = 1;
			var panels = $$('.vf-tab', box).filter(function (p) { return p.closest('.vf-tabs') === box; });
			if (!panels.length) return;
			var list = $('.vf-tab-list', box), buttons;
			if (!list) {
				list = doc.createElement('div');
				list.className = 'vf-tab-list';
				list.setAttribute('role', 'tablist');
				panels.forEach(function (p, i) {
					var label = $('.vf-tab-label', p), b = doc.createElement('button');
					b.type = 'button';
					b.setAttribute('role', 'tab');
					b.textContent = label ? label.textContent.trim() : 'Tab ' + (i + 1);
					list.appendChild(b);
				});
				box.insertBefore(list, box.firstChild);
			}
			buttons = $$('button', list);
			if (!buttons.length) {
				// Kadence: a column with one text block per tab -> real buttons.
				$$('.wp-block-kadence-advancedheading, .vf-tab-btn', list).forEach(function (h) {
					var b = doc.createElement('button');
					b.type = 'button';
					b.setAttribute('role', 'tab');
					b.textContent = h.textContent.trim();
					list.appendChild(b);
				});
				list.setAttribute('role', 'tablist');
				buttons = $$(':scope > button', list);
			}
			var pill = doc.createElement('span');
			pill.className = 'vf-tab-pill';
			pill.setAttribute('aria-hidden', 'true');
			list.insertBefore(pill, list.firstChild);
			function select(i, first) {
				buttons.forEach(function (b, k) {
					b.setAttribute('aria-selected', k === i);
					b.setAttribute('aria-controls', 'vf-tab-' + n + '-' + k);
					b.classList.toggle('is-active', k === i);
					b.tabIndex = k === i ? 0 : -1;
				});
				panels.forEach(function (p, k) {
					p.id = 'vf-tab-' + n + '-' + k;
					p.setAttribute('role', 'tabpanel');
					p.hidden = k !== i;
					p.classList.toggle('is-active', k === i && !first);
				});
				var b = buttons[i];
				pill.style.width = b.offsetWidth + 'px';
				pill.style.height = b.offsetHeight + 'px';
				pill.style.transform = 'translate(' + b.offsetLeft + 'px,' + b.offsetTop + 'px)';
				if (window.VF_FX_INIT && !first) window.VF_FX_INIT(panels[i]);
				$$('.vf-copy', box).forEach(function (c) { c.textContent = c.dataset.label || c.textContent; });
			}
			buttons.forEach(function (b, i) {
				b.addEventListener('click', function () { select(i); });
				b.addEventListener('keydown', function (e) {
					var d = e.key === 'ArrowRight' ? 1 : e.key === 'ArrowLeft' ? -1 : 0;
					if (d) { var k = (i + d + buttons.length) % buttons.length; select(k); buttons[k].focus(); }
				});
			});
			select(0, true);
			window.addEventListener('resize', function () { var a = buttons.findIndex(function (b) { return b.classList.contains('is-active'); }); select(a < 0 ? 0 : a, true); });
		});
	}

	/* 3. Accordion ------------------------------------------------------------- */
	function initAccordion(ctx) {
		$$('.vf-accordion', ctx).forEach(function (acc) {
			$$('.vf-acc-q', acc).forEach(function (q) {
				if (q.tagName !== 'BUTTON') { // Kadence text block: make it act like a button
					q.setAttribute('role', 'button');
					q.tabIndex = 0;
					q.setAttribute('aria-expanded', q.closest('.vf-acc-item').classList.contains('is-open'));
					q.addEventListener('keydown', function (e) { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); q.click(); } });
				}
				q.addEventListener('click', function () {
					var item = q.closest('.vf-acc-item'), open = !item.classList.contains('is-open');
					$$('.vf-acc-item', acc).forEach(function (it) { it.classList.remove('is-open'); $('.vf-acc-q', it).setAttribute('aria-expanded', 'false'); });
					item.classList.toggle('is-open', open);
					q.setAttribute('aria-expanded', open);
				});
			});
		});
	}

	/* 4. Copy ---------------------------------------------------------------- */
	function initCopy(ctx) {
		$$('.vf-copy', ctx).forEach(function (btn) {
			btn.dataset.label = btn.textContent;
			btn.addEventListener('click', function () {
				var box = btn.closest('.vf-tabs') || btn.closest('section') || doc;
				var code = $$('.vf-code', box).filter(function (c) { return !c.closest('[hidden]'); })[0];
				if (code && navigator.clipboard) navigator.clipboard.writeText(code.textContent).catch(function () {});
				btn.textContent = btn.dataset.done || 'Copied ✓';
				setTimeout(function () { btn.textContent = btn.dataset.label; }, 1600);
			});
		});
	}

	/* 5. Demos ----------------------------------------------------------------- */
	function onVisible(el, fn) {
		if (!('IntersectionObserver' in window)) return fn();
		var io = new IntersectionObserver(function (en) { if (en[0].isIntersecting) { io.disconnect(); fn(); } });
		io.observe(el);
	}

	// Hero card: name + email type themselves, then the conditional field opens.
	function heroForm(card) {
		onVisible(card, function () {
			var fields = $$('[data-type]', card), email = $('.vf-hf-email', card);
			fields.forEach(function (f) {
				var text = f.dataset.type, caret = f.nextElementSibling, i = 0;
				setTimeout(function () {
					if (caret) caret.hidden = false;
					var iv = setInterval(function () {
						f.textContent = text.slice(0, ++i);
						if (f.closest('.vf-hf-email')) email.classList.add('is-typing');
						if (i >= text.length) {
							clearInterval(iv);
							if (caret) caret.hidden = true;
							var ok = f.parentNode.querySelector('.vf-hf-ok');
							if (ok) { ok.hidden = false; ok.classList.add('vf-pop-in'); }
						}
					}, reduce ? 0 : +f.dataset.speed);
				}, reduce ? 0 : +f.dataset.at);
			});
			setTimeout(function () { card.classList.add('is-topic'); }, reduce ? 0 : 3700);
		});
	}

	// Conditional logic: clicking a topic opens the extra field when it matches data-show.
	function conditional(box) {
		var btns = $$('.vf-choice', box);
		function pick(b) {
			btns.forEach(function (x) { x.setAttribute('aria-checked', x === b); });
			box.classList.toggle('is-open', b.textContent.trim() === box.dataset.show);
		}
		btns.forEach(function (b) { b.addEventListener('click', function () { pick(b); }); });
	}

	// Calculator: package × quantity, two steps, the total counts to the new value.
	function calc(box) {
		var qty = 3, price = 24, step = 0, shown = 72, raf = 0;
		var total = $('.vf-calc-total', box), qn = $('.vf-qty-n', box), steps = $$('.vf-calc-step', box);
		function render() {
			var to = qty * price, from = shown, t0 = null;
			qn.textContent = qty;
			cancelAnimationFrame(raf);
			(function tick(t) {
				if (t0 === null) t0 = t;
				var p = reduce ? 1 : Math.min(1, (t - t0) / 600), e = 1 - Math.pow(1 - p, 3);
				shown = from + (to - from) * e;
				total.textContent = '$' + shown.toFixed(2);
				if (p < 1) raf = requestAnimationFrame(tick);
			})(performance.now());
		}
		function go(s) {
			step = s;
			box.dataset.step = s;
			steps.forEach(function (el, i) { el.hidden = i !== s; el.classList.toggle('vf-slide-in', i === s); });
			$$('.vf-calc-dot', box).forEach(function (d, i) { d.classList.toggle('is-on', i <= s); d.classList.toggle('is-current', i === s); });
			$('.vf-calc-n', box).textContent = s + 1;
			$('.vf-calc-back', box).hidden = s === 0;
			$('.vf-calc-next', box).textContent = s === 0 ? 'Next →' : 'Start over';
		}
		$$('.vf-plan', box).forEach(function (b) {
			b.addEventListener('click', function () {
				price = +b.dataset.price;
				$$('.vf-plan', box).forEach(function (x) { x.classList.toggle('is-on', x === b); });
				render();
			});
		});
		$$('.vf-qty', box).forEach(function (b) {
			b.addEventListener('click', function () { qty = Math.min(20, Math.max(1, qty + +b.dataset.d)); render(); });
		});
		$('.vf-calc-next', box).addEventListener('click', function () { go(step === 0 ? 1 : 0); });
		$('.vf-calc-back', box).addEventListener('click', function () { go(0); });
		go(0);
	}

	// Builder app on its own (not inside a sticky-steps section): play the 4 steps every 3 s.
	function builderApp(app) {
		if (app.closest('.vf-sticky-steps')) return;
		var host = app.parentNode, s = 0;
		host.dataset.step = 0;
		onVisible(app, function () { setInterval(function () { s = (s + 1) % 4; host.dataset.step = s; }, 3000); });
	}

	var demos = { 'hero-form': heroForm, conditional: conditional, calc: calc, 'builder-app': builderApp };
	function initDemos(ctx) {
		$$('[data-vf]', ctx).forEach(function (el) {
			var fn = demos[el.dataset.vf];
			if (fn && !el.dataset.vfOn) { el.dataset.vfOn = 1; fn(el); }
		});
	}

	/* 6. Blog TOC: the heading that crossed into the top band is "current". ---- */
	function initToc() {
		var links = $$('.vf-toc-nav .vf-toc-link');
		if (!links.length || !('IntersectionObserver' in window)) return;
		var set = function (id) { $$('.vf-toc-link').forEach(function (a) { var on = a.getAttribute('href') === '#' + id; a.classList.toggle('is-current', on); if (on) a.setAttribute('aria-current', 'location'); else a.removeAttribute('aria-current'); }); };
		var io = new IntersectionObserver(function (entries) {
			var hit = entries.filter(function (e) { return e.isIntersecting; }).sort(function (a, b) { return a.boundingClientRect.top - b.boundingClientRect.top; })[0];
			if (hit) set(hit.target.id);
		}, { rootMargin: '-100px 0px -65% 0px' });
		links.forEach(function (a) { var h = doc.getElementById(a.getAttribute('href').slice(1)); if (h) io.observe(h); });
		set(links[0].getAttribute('href').slice(1));
	}

	function init(ctx) {
		initTabs(ctx);
		initAccordion(ctx);
		initCopy(ctx);
		initDemos(ctx);
	}
	window.VF_SITE_INIT = init;

	var start = function () { initNav(); init(doc); initToc(); };
	if (doc.readyState === 'loading') doc.addEventListener('DOMContentLoaded', start); else start();
})();
