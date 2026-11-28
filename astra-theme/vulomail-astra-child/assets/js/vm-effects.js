/*!
 * VuloMail theme effects. Re-implements the original site's Motion (Framer Motion) primitives with the same
 * numbers: Reveal, Stagger, SplitWords, Parallax, Magnetic, Tilt, CountUp, ScrollProgress, the header's
 * scroll behaviour and Lenis smooth scrolling. Values are taken from src/components/motion.tsx & header.tsx.
 *
 * Kadence blocks opt in with classes (Advanced → Additional CSS class):
 *   vm-rv            reveal on scroll (0.9s, y 28px, blur 8px, -60px margin)
 *   vm-rv-now        …on load instead of on scroll
 *   vm-rv-d12        delay 0.12s (any number = hundredths of a second)
 *   vm-rv-y10        distance 10px
 *   vm-stagger       on a Row Layout: its columns with vm-st rise in one after another (0.08s apart)
 *   vm-split         split a heading into words that rise in (0.06s apart)
 *   vm-split-15-30   delays (hundredths) for the 2nd, 3rd … line/em of the heading
 *   vm-tilt          3D tilt toward the pointer (max 6°; vm-tilt-3 for 3°)
 *   vm-magnetic      leans toward the pointer (Kadence buttons with vm-btn get it automatically)
 *   vm-countup       on a <span data-to="18" data-prefix="$">: counts up when seen (1.6s)
 */
(function () {
	'use strict';

	var doc = document.documentElement;
	doc.classList.add('vm-js');
	var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var EASE = [0.22, 1, 0.36, 1];
	var EASE_CSS = 'cubic-bezier(0.22, 1, 0.36, 1)';

	/* ---------------------------------------------------------------- tiny animation kit */

	function bezier(p1x, p1y, p2x, p2y) {
		function a(a1, a2) { return 1 - 3 * a2 + 3 * a1; }
		function b(a1, a2) { return 3 * a2 - 6 * a1; }
		function c(a1) { return 3 * a1; }
		function calc(t, a1, a2) { return ((a(a1, a2) * t + b(a1, a2)) * t + c(a1)) * t; }
		function slope(t, a1, a2) { return 3 * a(a1, a2) * t * t + 2 * b(a1, a2) * t + c(a1); }
		return function (x) {
			if (x <= 0) return 0;
			if (x >= 1) return 1;
			var t = x;
			for (var i = 0; i < 8; i++) {
				var s = slope(t, p1x, p2x);
				if (Math.abs(s) < 1e-6) break;
				t -= (calc(t, p1x, p2x) - x) / s;
			}
			var lo = 0, hi = 1;
			for (var k = 0; k < 20 && Math.abs(calc(t, p1x, p2x) - x) > 1e-7; k++) {
				if (calc(t, p1x, p2x) < x) lo = t; else hi = t;
				t = (lo + hi) / 2;
			}
			return calc(t, p1y, p2y);
		};
	}
	var ease = bezier(EASE[0], EASE[1], EASE[2], EASE[3]);

	/** Tween 0→1 over `duration` s after `delay` s, calling step(eased). */
	function tween(duration, delay, step, done, easing) {
		var fn = easing || ease;
		var start = null;
		function frame(now) {
			if (start === null) start = now + delay * 1000;
			var t = Math.min(1, Math.max(0, (now - start) / (duration * 1000)));
			if (now >= start) step(fn(t));
			if (t < 1) requestAnimationFrame(frame); else if (done) done();
		}
		requestAnimationFrame(frame);
	}

	/** Damped spring that follows a target, like Motion's useSpring(stiffness, damping, mass). */
	function Spring(value, stiffness, damping, mass, onUpdate) {
		this.v = value; this.target = value; this.vel = 0;
		this.k = stiffness; this.c = damping; this.m = mass || 1;
		this.cb = onUpdate; this.running = false;
	}
	Spring.prototype.set = function (target) {
		this.target = target;
		if (!this.running) { this.running = true; this.last = null; loop.add(this); }
	};
	Spring.prototype.jump = function (v) { this.v = this.target = v; this.vel = 0; this.cb(v); };
	Spring.prototype.tick = function (now) {
		if (this.last === null) { this.last = now; return true; }
		var dt = Math.min(0.064, (now - this.last) / 1000);
		this.last = now;
		var steps = Math.ceil(dt / 0.002), h = dt / steps;
		for (var i = 0; i < steps; i++) {
			var f = -this.k * (this.v - this.target) - this.c * this.vel;
			this.vel += (f / this.m) * h;
			this.v += this.vel * h;
		}
		var rest = Math.abs(this.vel) < 0.05 && Math.abs(this.v - this.target) < 0.005;
		if (rest) { this.v = this.target; this.vel = 0; }
		this.cb(this.v);
		if (rest) { this.running = false; return false; }
		return true;
	};
	var loop = {
		items: [], on: false,
		add: function (s) { if (this.items.indexOf(s) < 0) this.items.push(s); if (!this.on) { this.on = true; requestAnimationFrame(this.run); } },
		run: function (now) {
			loop.items = loop.items.filter(function (s) { return s.tick(now); });
			if (loop.items.length) requestAnimationFrame(loop.run); else loop.on = false;
		}
	};

	/** Untransformed page offset (what Motion's useScroll measures). */
	function pageTop(el) {
		var y = 0;
		while (el) { y += el.offsetTop; el = el.offsetParent; }
		return y;
	}

	var scrollFns = [];
	function onScroll(fn) { scrollFns.push(fn); fn(window.scrollY); }
	var ticking = false;
	function runScroll() { ticking = false; var y = window.scrollY; for (var i = 0; i < scrollFns.length; i++) scrollFns[i](y); }
	window.addEventListener('scroll', function () { if (!ticking) { ticking = true; requestAnimationFrame(runScroll); } }, { passive: true });
	window.addEventListener('resize', function () { requestAnimationFrame(runScroll); });

	function inView(el, cb, margin, once) {
		if (!('IntersectionObserver' in window)) { cb(true); return; }
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (e) {
				if (e.isIntersecting) { cb(true); if (once !== false) io.disconnect(); }
				else if (once === false) cb(false);
			});
		}, { rootMargin: margin || '0px', threshold: 0 });
		io.observe(el);
	}

	function num(cls, prefix, el) {
		var m = (el.className.baseVal !== undefined ? el.className.baseVal : el.className).match(new RegExp('(?:^|\\s)' + prefix + '(-?\\d+)(?:\\s|$)'));
		return m ? parseInt(m[1], 10) : null;
	}

	window.vmFx = { tween: tween, Spring: Spring, ease: ease, bezier: bezier, inView: inView, onScroll: onScroll, pageTop: pageTop, reduce: reduce, EASE_CSS: EASE_CSS };

	/* ---------------------------------------------------------------- Reveal (vm-rv, data-vm-reveal) */

	function reveal(el, delay, y, duration, immediate) {
		if (reduce) { el.classList.add('vm-rv-ready', 'vm-in'); return; }
		el.style.setProperty('--vm-rv-y', y + 'px');
		el.style.setProperty('--vm-rv-blur', (el.dataset.blur || 8) + 'px');
		el.classList.add('vm-rv-ready');
		function show() {
			el.style.transition = 'opacity ' + duration + 's ' + EASE_CSS + ' ' + delay + 's, transform ' + duration + 's ' + EASE_CSS + ' ' + delay + 's, filter ' + duration + 's ' + EASE_CSS + ' ' + delay + 's';
			el.classList.add('vm-in');
		}
		if (immediate) requestAnimationFrame(function () { requestAnimationFrame(show); });
		else inView(el, show, '-60px');
	}
	document.querySelectorAll('.vm-rv').forEach(function (el) {
		var d = num(el.className, 'vm-rv-d', el), y = num(el.className, 'vm-rv-y', el);
		reveal(el, d ? d / 100 : 0, y === null ? 28 : y, 0.9, el.classList.contains('vm-rv-now'));
	});
	document.querySelectorAll('[data-vm-reveal]').forEach(function (el) {
		reveal(el, parseFloat(el.dataset.delay || 0), el.dataset.y ? parseFloat(el.dataset.y) : 28, 0.9, el.hasAttribute('data-now'));
	});

	/* ---------------------------------------------------------------- Stagger (vm-stagger > vm-st, data-vm-stagger > data-vm-st) */

	function stagger(container, items, gap) {
		items.forEach(function (it) {
			it.style.setProperty('--vm-rv-y', '24px');
			it.style.setProperty('--vm-rv-blur', '6px');
			it.classList.add('vm-rv-ready'); if (reduce) it.classList.add('vm-in');
		});
		if (reduce) return;
		inView(container, function () {
			items.forEach(function (it, i) {
				var d = i * gap;
				it.style.transition = 'opacity .8s ' + EASE_CSS + ' ' + d + 's, transform .8s ' + EASE_CSS + ' ' + d + 's, filter .8s ' + EASE_CSS + ' ' + d + 's';
				it.classList.add('vm-in');
			});
		}, '-60px');
	}
	document.querySelectorAll('.vm-stagger').forEach(function (c) {
		stagger(c, Array.prototype.slice.call(c.querySelectorAll('.vm-st')).filter(function (x) { return x.closest('.vm-stagger') === c; }), 0.08);
	});
	document.querySelectorAll('[data-vm-stagger]').forEach(function (c) {
		stagger(c, Array.prototype.slice.call(c.querySelectorAll('[data-vm-st]')).filter(function (x) { return x.closest('[data-vm-stagger]') === c; }), parseFloat(c.dataset.vmStagger || 0.08));
	});

	/* ---------------------------------------------------------------- SplitWords (vm-split) */

	function splitText(text, delay, out) {
		var words = text.trim().split(/\s+/).filter(Boolean);
		var outer = document.createElement('span');
		outer.setAttribute('aria-label', text.trim());
		words.forEach(function (w, i) {
			var wrap = document.createElement('span');
			wrap.setAttribute('aria-hidden', 'true');
			wrap.className = 'vm-word';
			var inner = document.createElement('span');
			inner.className = 'vm-word-in';
			// Same nodes React renders ({w}{"\u00a0"} → "w", <!-- -->, NBSP): the comment keeps the trailing space measurable.
			inner.appendChild(document.createTextNode(w));
			if (i < words.length - 1) { inner.appendChild(document.createComment('')); inner.appendChild(document.createTextNode('\u00a0')); }
			inner.style.transitionDelay = (delay + i * 0.06) + 's';
			wrap.appendChild(inner);
			outer.appendChild(wrap);
		});
		out.appendChild(outer);
	}
	document.querySelectorAll('.vm-split').forEach(function (h) {
		var seq = (h.className.match(/vm-split-([\d-]+)/) || [0, ''])[1].split('-').filter(Boolean).map(function (n) { return parseInt(n, 10) / 100; });
		seq.unshift(0);
		var seg = 0;
		var nodes = Array.prototype.slice.call(h.childNodes);
		var frag = document.createDocumentFragment();
		nodes.forEach(function (n) {
			if (n.nodeType === 3) {
				var t = n.textContent;
				if (t.trim()) {
					if (/^\s/.test(t)) frag.appendChild(document.createTextNode(' '));
					splitText(t, seq[Math.min(seg, seq.length - 1)], frag);
					if (/\s$/.test(t)) frag.appendChild(document.createTextNode(' '));
					seg++;
				} else if (t.length) frag.appendChild(document.createTextNode(' '));
			} else if (n.nodeName === 'BR') {
				frag.appendChild(n);
			} else {
				var d = seq[Math.min(seg, seq.length - 1)];
				var text = n.textContent;
				n.textContent = '';
				splitText(text, d, n);
				if (n.classList && n.classList.contains('vm-draw')) {
					n.insertAdjacentHTML('beforeend', '<svg viewBox="0 0 300 20" class="vm-draw-svg" aria-hidden="true"><path d="M2 14 C 80 4, 200 4, 298 12" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"></path></svg>');
				}
				frag.appendChild(n);
				seg++;
			}
		});
		h.textContent = '';
		h.appendChild(frag);
		h.classList.add('vm-split-ready');
		if (reduce) { h.classList.add('vm-in'); return; }
		requestAnimationFrame(function () { requestAnimationFrame(function () { h.classList.add('vm-in'); }); });
	});

	/* ---------------------------------------------------------------- data-vm-enter (one-off entrance on load) */

	document.querySelectorAll('[data-vm-enter]').forEach(function (el) {
		var o;
		try { o = JSON.parse(el.getAttribute('data-vm-enter')); } catch (e) { return; }
		function tf(s) { return 'translateY(' + (s.y || 0) + 'px)' + (s.rotate !== undefined ? ' rotate(' + s.rotate + 'deg)' : '') + (s.scale !== undefined ? ' scale(' + s.scale + ')' : ''); }
		if (reduce) { el.style.opacity = o.to.opacity; el.style.transform = tf(o.to); return; }
		// Final state goes inline first; the animation only fills backwards, so nothing is left on the element afterwards.
		el.style.opacity = o.to.opacity;
		el.style.transform = tf(o.to);
		el.animate([{ opacity: o.from.opacity, transform: tf(o.from) }, { opacity: o.to.opacity, transform: tf(o.to) }], { duration: o.duration * 1000, delay: (o.delay || 0) * 1000, easing: EASE_CSS, fill: 'backwards' });
	});

	/* ---------------------------------------------------------------- Parallax (data-vm-parallax="speed", data-vm-rotate) */

	document.querySelectorAll('[data-vm-parallax]').forEach(function (el) {
		if (reduce) return;
		var speed = parseFloat(el.dataset.vmParallax) || 0.2;
		var rot = parseFloat(el.dataset.vmRotate || 0);
		var range = 300 * speed;
		var y = 0, r = 0;
		function apply() { el.style.transform = 'translateY(' + y + 'px)' + (rot ? ' rotate(' + r + 'deg)' : ''); }
		var spring = new Spring(0, 120, 30, 0.4, function (v) { y = v; apply(); });
		var first = true;
		onScroll(function (sy) {
			var top = pageTop(el), h = el.offsetHeight, vh = window.innerHeight;
			var p = Math.min(1, Math.max(0, (sy + vh - top) / (vh + h)));
			var target = range + (-range - range) * p;
			r = -rot + 2 * rot * p;
			if (first) { first = false; spring.jump(target); } else { spring.set(target); apply(); }
		});
	});

	/* ---------------------------------------------------------------- Magnetic (vm-btn primary/ghost/light, vm-magnetic, data-vm-magnetic) */

	document.querySelectorAll('.vm-btn-primary, .vm-btn-ghost, .vm-btn-light, .vm-magnetic, [data-vm-magnetic]').forEach(function (el) {
		var strength = parseFloat(el.dataset.vmMagnetic || 0.25);
		var x = 0, y = 0;
		function apply() { el.style.translate = (Math.abs(x) < 0.01 && Math.abs(y) < 0.01) ? '' : x + 'px ' + y + 'px'; }
		var sx = new Spring(0, 220, 16, 0.3, function (v) { x = v; apply(); });
		var syy = new Spring(0, 220, 16, 0.3, function (v) { y = v; apply(); });
		el.addEventListener('pointermove', function (e) {
			if (reduce || e.pointerType !== 'mouse') return;
			var r = el.getBoundingClientRect();
			sx.set((e.clientX - r.left - r.width / 2) * strength);
			syy.set((e.clientY - r.top - r.height / 2) * strength);
		});
		el.addEventListener('pointerleave', function () { sx.set(0); syy.set(0); });
	});

	/* ---------------------------------------------------------------- Tilt (vm-tilt, data-vm-tilt="max") */

	document.querySelectorAll('.vm-tilt, [data-vm-tilt]').forEach(function (el) {
		var max = parseFloat(el.dataset.vmTilt || num(el.className, 'vm-tilt-', el) || 6);
		el.classList.add('vm-tilt-ready');
		var glow = document.createElement('div');
		glow.className = 'vm-tilt-glow';
		glow.setAttribute('aria-hidden', 'true');
		el.appendChild(glow);
		var rx = 0, ry = 0, px = 0.5, py = 0.5;
		function apply() {
			el.style.transform = (Math.abs(rx) < 0.001 && Math.abs(ry) < 0.001) ? '' : 'perspective(900px) rotateX(' + rx + 'deg) rotateY(' + ry + 'deg)';
			glow.style.background = 'radial-gradient(420px circle at ' + px * 100 + '% ' + py * 100 + '%, color-mix(in srgb, var(--surface) 75%, transparent), transparent 45%)';
		}
		var sX = new Spring(0, 200, 20, 1, function (v) { rx = v; apply(); });
		var sY = new Spring(0, 200, 20, 1, function (v) { ry = v; apply(); });
		apply();
		el.addEventListener('pointermove', function (e) {
			if (e.pointerType !== 'mouse') return;
			var r = el.getBoundingClientRect();
			px = (e.clientX - r.left) / r.width; py = (e.clientY - r.top) / r.height;
			if (!reduce) { sX.set(max + (-2 * max) * py); sY.set(-max + 2 * max * px); }
			apply();
		});
		el.addEventListener('pointerleave', function () { px = 0.5; py = 0.5; sX.set(0); sY.set(0); apply(); });
	});

	/* ---------------------------------------------------------------- CountUp */

	document.querySelectorAll('.vm-countup[data-to]').forEach(function (el) {
		var to = parseFloat(el.dataset.to), prefix = el.dataset.prefix || '', suffix = el.dataset.suffix || '';
		el.textContent = prefix + '0' + suffix;
		inView(el, function () {
			if (reduce) { el.textContent = prefix + to + suffix; return; }
			tween(1.6, 0, function (t) { el.textContent = prefix + Math.round(to * t) + suffix; });
		});
	});

	/* ---------------------------------------------------------------- Scroll progress bar */

	var bar = document.querySelector('.vm-progress');
	if (bar) {
		var sp = new Spring(0, 140, 30, 1, function (v) { bar.style.transform = 'scaleX(' + v + ')'; });
		var firstP = true;
		onScroll(function (y) {
			var max = Math.max(1, doc.scrollHeight - window.innerHeight);
			var p = Math.min(1, Math.max(0, y / max));
			if (firstP) { firstP = false; sp.jump(p); } else sp.set(p);
		});
	}

	/* ---------------------------------------------------------------- Header: scrolled state + hide on scroll down */

	var header = document.querySelector('.vm-header');
	var hideHeader = !!(window.vmConfig || {}).hideHeader; // Customize → VuloMail; off by default, so the header always stays
	if (header) {
		var prev = window.scrollY;
		header.style.transition = 'transform .45s ' + EASE_CSS;
		onScroll(function (y) {
			var open = document.body.classList.contains('vm-menu-open') || !!header.querySelector('[data-vm-dropdown].is-open');
			header.classList.toggle('vm-scrolled', y > 24);
			var hide = hideHeader && y > 320 && y > prev && !open;
			// -110px like the original, plus the WordPress admin bar when it is shown, so nothing peeks out.
			header.style.transform = hide ? 'translateY(calc(-110px - var(--wp-admin--admin-bar--height, 0px)))' : '';
			prev = y;
		});
	}

	/* ---------------------------------------------------------------- position: sticky needs ancestors without overflow: hidden */

	function unclip(el) {
		var p = el.parentElement;
		while (p && p !== document.body && p !== doc) {
			var cs = getComputedStyle(p);
			if (cs.overflowX === 'hidden' || cs.overflowY === 'hidden') { p.style.overflow = 'clip'; }
			p = p.parentElement;
		}
	}
	document.querySelectorAll('[data-vm-pipeline], [data-vm-track], .vm-pipeline, .vm-principles, .vm-sticky').forEach(unclip);

	/* ---------------------------------------------------------------- Lenis smooth scroll */

	var cfg = window.vmConfig || {};
	if (cfg.smooth && !reduce && window.Lenis && !doc.classList.contains('vm-editor')) {
		var lenis = new window.Lenis({ lerp: 0.11, wheelMultiplier: 0.95, anchors: true });
		window.vmLenis = lenis;
		requestAnimationFrame(function raf(t) { lenis.raf(t); requestAnimationFrame(raf); });
	}
})();
