/*!
 * VuloForm effects – runs every class in assets/css/vf-effects.css (list at the top of that file).
 * No dependencies. Works with Lenis smooth scrolling. Call window.VF_FX_INIT(element) after adding markup with AJAX.
 *
 *   1. Reveal       IntersectionObserver adds .is-in (vf-reveal*, vf-words, vf-draw, vf-grow-x, vf-count, vf-inview, vf-stagger)
 *   2. Scroll       one rAF loop writes --p / --pi / --pe (and data-step for vf-sticky-steps)
 *   3. Marquee      copies the items so the row loops without a gap (also on a Kadence column)
 *   4. Pointer      vf-tilt / vf-follow (smoothed)
 */
(function () {
	'use strict';

	var doc = document, root = doc.documentElement;
	var reduce = window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches;
	var $$ = function (s, c) { return Array.prototype.slice.call((c || doc).querySelectorAll(s)); };
	var clamp = function (v) { return v < 0 ? 0 : v > 1 ? 1 : v; };
	window.VF_FX = true;

	var REVEAL = '[class*="vf-reveal"], .vf-words, .vf-draw, .vf-grow-x, .vf-count, .vf-inview';
	var SCROLL = '.vf-parallax, .vf-parallax-reverse, .vf-drift-left, .vf-drift-right, .vf-exit-up, .vf-exit-down, .vf-exit-fade, .vf-scroll-zoom, .vf-enter-up, .vf-scroll-rotate';
	var scrollEls = [], steps = [];

	/** Children of a block, looking through Kadence's inner wrappers (.kt-row-column-wrap / .kt-inside-inner-col). */
	function kids(el) {
		var c = el.children;
		while (c.length === 1 && /\bkt-(inside-inner-col|row-column-wrap)\b/.test(c[0].className)) { el = c[0]; c = el.children; }
		return c;
	}

	/* ------------------------------------------------------------------ *
	 * 1. Reveal
	 * ------------------------------------------------------------------ */
	function splitWords(el) {
		var i = 0;
		(function walk(node) {
			Array.prototype.slice.call(node.childNodes).forEach(function (n) {
				if (n.nodeType === 3 && n.nodeValue.trim()) {
					var frag = doc.createDocumentFragment();
					n.nodeValue.split(/(\s+)/).forEach(function (part) {
						if (!part) return;
						if (/^\s+$/.test(part)) { frag.appendChild(doc.createTextNode(part)); return; }
						var w = doc.createElement('span'), inner = doc.createElement('span');
						w.className = 'vf-w'; inner.textContent = part; inner.style.setProperty('--vf-i', i++);
						w.appendChild(inner); frag.appendChild(w);
					});
					n.parentNode.replaceChild(frag, n);
				} else if (n.nodeType === 1 && n.tagName === 'MARK' && !n.classList.contains('vf-w-line')) {
					var inner = doc.createElement('span');
					while (n.firstChild) inner.appendChild(n.firstChild);
					n.classList.add('vf-w-line'); n.appendChild(inner);
				} else if (n.nodeType === 1 && n.tagName !== 'svg' && !n.classList.contains('vf-w')) {
					walk(n);
				}
			});
		})(el);
	}

	function countUp(el) {
		var m = el.textContent.match(/-?[\d,.]+/);
		if (!m || reduce) return;
		var to = parseFloat(m[0].replace(/,/g, '')), dec = (m[0].split('.')[1] || '').length, t0 = null;
		var tpl = el.textContent.replace(m[0], '#');
		(function step(t) {
			if (t0 === null) t0 = t;
			var p = Math.min(1, (t - t0) / 1400), v = to * (1 - Math.pow(1 - p, 3));
			el.textContent = tpl.replace('#', v.toFixed(dec));
			if (p < 1) requestAnimationFrame(step);
		})(performance.now());
	}

	function show(el) {
		el.classList.add('is-in');
		if (el.classList.contains('vf-count') && !el.dataset.counted) { el.dataset.counted = 1; countUp(el); }
	}

	var io = 'IntersectionObserver' in window ? new IntersectionObserver(function (entries) {
		entries.forEach(function (en) {
			var el = en.target, items = el.classList.contains('vf-stagger') ? kids(el) : [el];
			if (en.isIntersecting) {
				if (el.classList.contains('vf-stagger')) Array.prototype.forEach.call(items, show);
				show(el);
				if (!el.classList.contains('vf-repeat')) io.unobserve(el);
			} else if (el.classList.contains('vf-repeat') && en.boundingClientRect.top > 0) {
				el.classList.remove('is-in');
				Array.prototype.forEach.call(items, function (i) { i.classList.remove('is-in'); });
			}
		});
	}, { rootMargin: '0px 0px -80px 0px' }) : null;

	function initReveal(ctx) {
		// Stagger: each child becomes a reveal item with its own index.
		$$('.vf-stagger', ctx).forEach(function (wrap) {
			Array.prototype.forEach.call(kids(wrap), function (c, i) {
				if (!/vf-reveal/.test(c.className)) c.classList.add('vf-reveal');
				c.style.setProperty('--vf-i', i);
				c.dataset.vfStagger = 1;
			});
			if (io) io.observe(wrap); else show(wrap);
		});
		$$(REVEAL, ctx).forEach(function (el) {
			if (el.dataset.vfStagger || el.dataset.vfSeen) return;
			el.dataset.vfSeen = 1;
			if (el.classList.contains('vf-words')) splitWords(el);
			if (el.classList.contains('vf-draw')) el.setAttribute('pathLength', '1');
			if (io && !reduce) io.observe(el); else show(el);
		});
	}

	/* ------------------------------------------------------------------ *
	 * 2. Scroll-linked values
	 * ------------------------------------------------------------------ */
	function initScroll(ctx) {
		$$(SCROLL, ctx).forEach(function (el) {
			if (el.dataset.vfScroll) return;
			el.dataset.vfScroll = 1;
			// Progress is measured on the whole section (like the original): a Kadence Row counts as one.
			scrollEls.push({ el: el, box: el.closest('.vf-fx-root, [data-fx-root], .kb-row-layout-wrap') || el });
		});
		$$('.vf-sticky-steps', ctx).forEach(function (sec) {
			if (sec.dataset.vfSteps) return;
			sec.dataset.vfSteps = 1;
			// Step lists: "<strong>Title</strong><br>Body" becomes title + collapsible body.
			$$('.vf-steps > li', sec).forEach(function (li, i) {
				if (li.querySelector('.vf-step-title')) return;
				var strong = li.querySelector('strong, b'), title = doc.createElement('p'), body = doc.createElement('div'), p = doc.createElement('p'), span = doc.createElement('span');
				title.className = 'vf-step-title';
				title.innerHTML = '<span class="vf-step-num">' + (i + 1) + '</span>';
				if (strong) { title.appendChild(strong); }
				while (li.firstChild) { var n = li.firstChild; if (n.nodeName === 'BR' && !span.childNodes.length) { li.removeChild(n); continue; } span.appendChild(n); }
				body.className = 'vf-step-body'; p.appendChild(span); body.appendChild(p);
				li.appendChild(title); li.appendChild(body);
			});
			var item = { el: sec, items: $$('.vf-steps > li, .vf-step', sec) };
			steps.push(item);
			unclip(sec);
			// Click (or Enter / Space on) a step: scroll to the point where that step is the active one.
			item.items.forEach(function (li, i) {
				li.setAttribute('role', 'button');
				li.setAttribute('tabindex', '0');
				li.classList.add('vf-step-click');
				var go = function () {
					var n = item.items.length || 4, vh = window.innerHeight;
					var r = sec.getBoundingClientRect(), top = r.top + window.scrollY;
					var p = Math.min(1, (i + 0.5) / (n * 1.05)); // middle of this step's range (same formula as measure())
					var y = Math.round(top + p * Math.max(0, r.height - vh));
					if (window.vfLenis) window.vfLenis.scrollTo(y, { duration: 1.2 });
					else window.scrollTo({ top: y, behavior: matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth' });
				};
				li.addEventListener('click', function (e) { if (!e.target.closest('a')) go(); });
				li.addEventListener('keydown', function (e) { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); go(); } });
			});
		});
		if (doc.querySelector('.vf-page-progress')) root.dataset.vfPage = 1;
	}

	/**
	 * position:sticky stops working when a parent (theme or page-builder wrapper) has overflow hidden/auto.
	 * Switch those parents to overflow "clip": still no sideways scrollbar, but sticky works again.
	 */
	function unclip(el) {
		for (var p = el.parentElement; p && p !== doc.body && p !== root; p = p.parentElement) {
			var cs = getComputedStyle(p);
			if (/(hidden|auto|scroll)/.test(cs.overflowX + cs.overflowY)) { p.style.overflowX = 'clip'; p.style.overflowY = 'visible'; }
		}
	}

	var ticking = false;
	function measure() {
		ticking = false;
		var vh = window.innerHeight;
		scrollEls.forEach(function (o) {
			var r = o.box.getBoundingClientRect();
			if (r.bottom < -vh || r.top > vh * 2) return; // far away: skip
			var s = o.el.style;
			s.setProperty('--p', clamp((vh - r.top) / (vh + r.height)).toFixed(4));
			s.setProperty('--pi', clamp((vh - r.top) / Math.max(1, r.height)).toFixed(4));
			s.setProperty('--pe', clamp(-r.top / Math.max(1, r.height)).toFixed(4));
		});
		steps.forEach(function (o) {
			var r = o.el.getBoundingClientRect(), n = o.items.length || 4;
			var p = clamp(-r.top / Math.max(1, r.height - vh));
			var step = Math.min(n - 1, Math.floor(p * n * 1.05));
			o.el.style.setProperty('--p', p.toFixed(4));
			if (o.last !== step) {
				o.last = step;
				o.el.dataset.step = step;
				o.items.forEach(function (li, i) { li.classList.toggle('is-active', i === step); li.classList.toggle('is-done', i <= step); });
			}
		});
		if (root.dataset.vfPage) root.style.setProperty('--vf-page', clamp(window.scrollY / Math.max(1, root.scrollHeight - vh)).toFixed(4));
	}
	function onScroll() { if (!ticking) { ticking = true; requestAnimationFrame(measure); } }

	/* ------------------------------------------------------------------ *
	 * 3. Marquee: repeat the items until one copy is wider than the screen, then double it.
	 * ------------------------------------------------------------------ */
	function initMarquee(ctx) {
		$$('.vf-marquee, .vf-marquee-reverse', ctx).forEach(function (track) {
			if (track.dataset.vfMarquee) return;
			track.dataset.vfMarquee = 1;
			// Kadence column: the column becomes the clipping wrapper, its inner box becomes the moving row.
			var inner = track.querySelector(':scope > .kt-inside-inner-col');
			if (inner) {
				var cls = track.classList.contains('vf-marquee-reverse') ? 'vf-marquee-reverse' : 'vf-marquee';
				track.classList.remove(cls);
				track.classList.add('vf-marquee-wrap');
				inner.classList.add(cls);
				track = inner;
			}
			var wrap = track.parentNode;
			if (!wrap.classList.contains('vf-marquee-wrap') && !wrap.classList.contains('vf-fade-edges')) {
				wrap = doc.createElement('div');
				wrap.className = 'vf-marquee-wrap' + (track.classList.contains('vf-fade-edges') ? ' vf-fade-edges' : '');
				track.classList.remove('vf-fade-edges');
				track.parentNode.insertBefore(wrap, track);
				wrap.appendChild(track);
			}
			var items = Array.prototype.slice.call(track.children), setW = track.scrollWidth || 1;
			var copies = Math.max(1, Math.ceil((window.innerWidth * 1.2) / setW));
			for (var k = 1; k < copies * 2; k++) {
				items.forEach(function (it) { var c = it.cloneNode(true); c.setAttribute('aria-hidden', 'true'); track.appendChild(c); });
			}
		});
	}

	/* ------------------------------------------------------------------ *
	 * 4. Pointer: vf-tilt / vf-follow read --mx / --my (-0.5 … 0.5) from their area.
	 * ------------------------------------------------------------------ */
	function initPointer(ctx) {
		if (reduce || !matchMedia('(pointer: fine)').matches) return;
		$$('.vf-tilt, .vf-follow', ctx).forEach(function (el) {
			var area = el.closest('[data-tilt-area], .vf-tilt-area, section') || el.parentNode;
			if (area.dataset.vfPointer) return;
			area.dataset.vfPointer = 1;
			var tx = 0, ty = 0, x = 0, y = 0, raf = 0;
			function loop() {
				x += (tx - x) * 0.12; y += (ty - y) * 0.12;
				area.style.setProperty('--mx', x.toFixed(4)); area.style.setProperty('--my', y.toFixed(4));
				raf = Math.abs(tx - x) + Math.abs(ty - y) > 0.001 ? requestAnimationFrame(loop) : 0;
			}
			area.addEventListener('pointermove', function (e) {
				var r = area.getBoundingClientRect();
				tx = (e.clientX - r.left) / r.width - 0.5; ty = (e.clientY - r.top) / r.height - 0.5;
				if (!raf) raf = requestAnimationFrame(loop);
			});
			area.addEventListener('pointerleave', function () { tx = ty = 0; if (!raf) raf = requestAnimationFrame(loop); });
		});
	}

	/* ------------------------------------------------------------------ */
	function init(ctx) {
		ctx = ctx || doc;
		initMarquee(ctx);
		initReveal(ctx);
		initScroll(ctx);
		initPointer(ctx);
		measure();
	}
	window.VF_FX_INIT = init;

	window.addEventListener('scroll', onScroll, { passive: true });
	window.addEventListener('resize', onScroll);
	if (doc.readyState === 'loading') doc.addEventListener('DOMContentLoaded', function () { init(); }); else init();
})();
