/*!
 * VuloPilot motion (no dependencies, no React).
 * Animates the PHP sections in /template-parts:
 *   1. Engine     – data-m enter animations, score rings, counters, typing / swap / sort effects
 *   2. Widgets    – rotating words, mouse tilt, hero scanner, noise slider, how-it-works stepper,
 *                   dashboard demo, product tour, tabs, plan billing switch, table column hover,
 *                   FAQ accordion, screenshot lightbox
 * Each widget looks for data-vp="name". Call window.VP_MOTION(element) after adding markup with AJAX.
 */
(function () {
	'use strict';

	var doc = document;
	var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var $ = function (s, c) { return (c || doc).querySelector(s); };
	var $$ = function (s, c) { return Array.prototype.slice.call((c || doc).querySelectorAll(s)); };
	var clamp = function (v) { return Math.min(1, Math.max(0, v)); };
	var reflow = function (el) { return el.offsetWidth; };
	window.VP_REVEAL_READY = true;

	/* ------------------------------------------------------------------ *
	 * Easing
	 * ------------------------------------------------------------------ */
	function bezier(x1, y1, x2, y2) {
		function a(p1, p2) { return 1 - 3 * p2 + 3 * p1; }
		function b(p1, p2) { return 3 * p2 - 6 * p1; }
		function c(p1) { return 3 * p1; }
		function calc(t, p1, p2) { return ((a(p1, p2) * t + b(p1, p2)) * t + c(p1)) * t; }
		function slope(t, p1, p2) { return 3 * a(p1, p2) * t * t + 2 * b(p1, p2) * t + c(p1); }
		return function (x) {
			if (x <= 0) return 0;
			if (x >= 1) return 1;
			var t = x;
			for (var i = 0; i < 8; i++) {
				var s = slope(t, x1, x2);
				if (Math.abs(s) < 1e-6) break;
				t -= (calc(t, x1, x2) - x) / s;
			}
			return calc(t, y1, y2);
		};
	}
	var EASE = {
		out: function (t) { return 1 - Math.pow(1 - t, 3); },
		vp: bezier(0.22, 1, 0.36, 1),
		inout: bezier(0.65, 0, 0.35, 1)
	};

	/** Tween a number. Returns a stop function. */
	function tween(from, to, ms, ease, onUpdate, onDone) {
		var t0 = null, raf = 0, stopped = false;
		if (reduce || ms <= 0) { onUpdate(to); if (onDone) onDone(); return function () {}; }
		function step(t) {
			if (stopped) return;
			if (t0 === null) t0 = t;
			var p = Math.min(1, (t - t0) / ms);
			onUpdate(from + (to - from) * (ease || EASE.out)(p));
			if (p < 1) raf = requestAnimationFrame(step); else if (onDone) onDone();
		}
		raf = requestAnimationFrame(step);
		return function () { stopped = true; cancelAnimationFrame(raf); };
	}

	/* ------------------------------------------------------------------ *
	 * 1. Engine: units = [data-m], .vp-ring, [data-fx]
	 * ------------------------------------------------------------------ */
	var UNITS = '[data-m], .vp-ring, [data-fx]';

	function num(el, to, ms, suffix) {
		if (el._vpStop) el._vpStop();
		suffix = suffix || '';
		el._vpStop = tween(0, to, ms, EASE.out, function (v) { el.textContent = Math.round(v) + suffix; });
	}

	function setInitial(el) {
		if (el.classList.contains('vp-ring')) {
			var arc = $('.vp-ring-arc', el), n = $('.vp-ring-num', el);
			if (arc) arc.style.strokeDashoffset = '100';
			if (n) { if (n._vpStop) n._vpStop(); n.textContent = '0'; }
			return;
		}
		if (el.hasAttribute('data-fx')) { fxReset(el); }
		if (!el.hasAttribute('data-m')) return;
		if (el.dataset.mW) el.style.width = '0';
		if (el.dataset.mH) el.style.height = '0';
		if (el.dataset.mPath) {
			el.setAttribute('pathLength', '1');
			el.style.strokeDasharray = '1';
			el.style.strokeDashoffset = '1';
		}
		if (el.dataset.mN) {
			if (el._vpStop) el._vpStop();
			el.textContent = '0';
		}
	}

	function play(el) {
		if (el.classList.contains('vp-ring')) {
			var arc = $('.vp-ring-arc', el), n = $('.vp-ring-num', el), v = parseFloat(arc ? arc.dataset.value : 0) || 0;
			if (arc) arc.style.strokeDashoffset = String(100 - v);
			if (n) num(n, Math.round(v), 1600);
			return;
		}
		if (el.hasAttribute('data-fx')) fxStart(el);
		if (!el.hasAttribute('data-m')) return;
		el.classList.add('m-in');
		if (el.dataset.mW) el.style.width = el.dataset.mW;
		if (el.dataset.mH) el.style.height = el.dataset.mH;
		if (el.dataset.mPath) el.style.strokeDashoffset = '0';
		var cs = getComputedStyle(el);
		var d = parseFloat(cs.getPropertyValue('--md')) || 0, t = parseFloat(cs.getPropertyValue('--mt')) || 0.6;
		if (el.dataset.mN) {
			var to = parseFloat(el.dataset.mN) || 0;
			setTimeout(function () { num(el, to, t * 1000); }, d * 1000);
		}
		clearTimeout(el._vpDone);
		el._vpDone = setTimeout(function () { el.classList.add('m-done'); }, (d + t) * 1000 + 60);
	}

	function reset(el) {
		clearTimeout(el._vpDone);
		el.classList.add('m-reset');
		var arc = el.classList.contains('vp-ring') ? $('.vp-ring-arc', el) : null;
		if (arc) arc.classList.add('m-reset');
		el.classList.remove('m-in', 'm-done');
		setInitial(el);
		reflow(el);
		el.classList.remove('m-reset');
		if (arc) { reflow(arc); arc.classList.remove('m-reset'); }
	}

	var io = null;
	/* Zero-size units (bars that grow from 0) are watched through their parent. */
	function observe(el) {
		if (!io) { play(el); return; }
		var target = (el.dataset.mW || el.dataset.mH) && el.parentElement ? el.parentElement : el;
		var list = target._vpWatch || (target._vpWatch = []);
		if (list.indexOf(el) < 0) list.push(el);
		io.unobserve(target);
		io.observe(target);
	}
	if ('IntersectionObserver' in window && !reduce) {
		io = new IntersectionObserver(function (entries) {
			entries.forEach(function (en) {
				if (!en.isIntersecting) return;
				var t = en.target, list = t._vpWatch || [];
				io.unobserve(t);
				t._vpWatch = [];
				list.forEach(play);
			});
		}, { rootMargin: '0px 0px -40px 0px', threshold: 0 });
	}

	/** Units inside scope (and scope itself). */
	function unitsOf(scope) {
		var list = $$(UNITS, scope);
		if (scope.matches && scope.matches(UNITS)) list.unshift(scope);
		return list;
	}

	/** Restart every animation inside scope (used when a tab / step / screen is shown again). */
	function replay(scope) {
		unitsOf(scope).forEach(function (el) { reset(el); observe(el); });
	}

	function initEngine(scope) {
		var loads = [];
		unitsOf(scope).forEach(function (el) {
			if (el._vpUnit) return;
			el._vpUnit = true;
			if (el.hasAttribute('data-fx')) fxInit(el);
			el.classList.add('m-reset');
			setInitial(el);
			if (el.getAttribute('data-m') === 'load') loads.push(el); else observe(el);
		});
		reflow(doc.body);
		unitsOf(scope).forEach(function (el) { el.classList.remove('m-reset'); });
		requestAnimationFrame(function () { requestAnimationFrame(function () { loads.forEach(play); }); });
	}

	/* Effects: type (typewriter), swap (before → after after N s), sort (rank list after N s) */
	function fxInit(el) {
		var kind = el.getAttribute('data-fx');
		if (kind === 'type') el._vpText = el.textContent.replace(/\s+/g, ' ').trim();
		if (kind === 'sort') {
			var ul = $('ul', el);
			el._vpOrder = ul ? $$(':scope > li', ul) : [];
		}
	}

	function setSwapped(el, on) {
		el.classList.toggle('is-swapped', on);
		$$('[data-when]', el).forEach(function (w) {
			if (w.closest('[data-fx]') !== el) return;
			var show = (w.getAttribute('data-when') === 'after') === on;
			if (show && w.hidden) { w.hidden = false; replay(w); } else if (!show) w.hidden = true;
		});
	}

	function sortList(el, sorted) {
		var ul = $('ul', el);
		if (!ul) return;
		var items = el._vpOrder.slice();
		var first = items.map(function (li) { return li.getBoundingClientRect().top; });
		var next = sorted ? items.slice().sort(function (a, b) { return a.dataset.rank - b.dataset.rank; }) : items;
		next.forEach(function (li) { ul.appendChild(li); });
		items.forEach(function (li, i) {
			var badge = $('[data-rank-badge]', li);
			if (badge) {
				badge.textContent = sorted ? String(parseInt(li.dataset.rank, 10) + 1) : '·';
				badge.style.background = sorted ? li.dataset.color : 'var(--line)';
				badge.style.color = sorted ? '#fff' : 'var(--muted)';
			}
			if (!sorted || reduce) { li.style.transform = ''; return; }
			var dy = first[i] - li.getBoundingClientRect().top;
			li.style.transition = 'none';
			li.style.transform = 'translateY(' + dy + 'px)';
			reflow(li);
			li.style.transition = '';
			li.style.transform = '';
		});
	}

	function fxReset(el) {
		clearTimeout(el._vpT);
		clearInterval(el._vpI);
		var kind = el.getAttribute('data-fx');
		if (kind === 'type' && el._vpText !== undefined) el.textContent = '';
		if (kind === 'swap' || kind === 'sort') setSwapped(el, false);
		if (kind === 'sort' && el._vpOrder) sortList(el, false);
	}

	function fxStart(el) {
		fxReset(el);
		var kind = el.getAttribute('data-fx');
		var after = (parseFloat(el.getAttribute('data-after')) || 0) * 1000;
		if (kind === 'type') {
			var text = el._vpText || '', i = 0, step = parseInt(el.getAttribute('data-step'), 10) || 2, ms = parseInt(el.getAttribute('data-ms'), 10) || 40;
			var node = doc.createTextNode(''), caret = doc.createElement('span');
			caret.className = 'caret';
			caret.textContent = '▍';
			el.textContent = '';
			el.appendChild(node);
			el.appendChild(caret);
			if (reduce) { node.nodeValue = text; caret.remove(); return; }
			el._vpI = setInterval(function () {
				i = Math.min(text.length, i + step);
				node.nodeValue = text.slice(0, i);
				if (i >= text.length) { clearInterval(el._vpI); caret.remove(); }
			}, ms);
		}
		if (kind === 'swap') el._vpT = setTimeout(function () { setSwapped(el, true); }, after);
		if (kind === 'sort') el._vpT = setTimeout(function () { setSwapped(el, true); sortList(el, true); }, after);
	}

	/* ------------------------------------------------------------------ *
	 * Helpers for widgets
	 * ------------------------------------------------------------------ */
	function swapClasses(el, on, onCls, offCls) {
		if (!el) return;
		(onCls || '').split(/\s+/).forEach(function (c) { if (c) el.classList.toggle(c, on); });
		(offCls || '').split(/\s+/).forEach(function (c) { if (c) el.classList.toggle(c, !on); });
	}

	/** Moving highlight behind the active button of a [data-pill-group]. */
	function placePill(group, btn, instant) {
		var pill = $(':scope > .vp-pill', group);
		if (!pill || !btn) return;
		if (instant || !pill.classList.contains('is-on')) pill.classList.add('no-anim');
		pill.style.transform = 'translate(' + btn.offsetLeft + 'px,' + btn.offsetTop + 'px)';
		pill.style.width = btn.offsetWidth + 'px';
		pill.style.height = btn.offsetHeight + 'px';
		reflow(pill);
		pill.classList.remove('no-anim');
		pill.classList.add('is-on');
	}
	function pillWatch(group, getActive) {
		var go = function () { placePill(group, getActive(), true); };
		go();
		window.addEventListener('resize', go);
		if (doc.fonts && doc.fonts.ready) doc.fonts.ready.then(go);
		if ('IntersectionObserver' in window) {
			var o = new IntersectionObserver(function (e) { if (e[0].isIntersecting) { go(); o.disconnect(); } });
			o.observe(group);
		}
	}

	/** Show one panel of a set, hide the rest, and replay its animations. */
	function showPanel(panels, match) {
		panels.forEach(function (p) {
			var on = match(p);
			if (on && p.hidden) { replay(p); p.hidden = false; } else if (!on) p.hidden = true;
		});
	}

	/* ------------------------------------------------------------------ *
	 * 2. Widgets
	 * ------------------------------------------------------------------ */
	var W = {};

	/* Rotating words in the hero heading */
	W.rotator = function (el) {
		var items = $$('[data-rot-item]', el), i = 0, ms = parseInt(el.dataset.interval, 10) || 2600;
		if (items.length < 2) return;
		setInterval(function () {
			items[i].classList.remove('is-on');
			i = (i + 1) % items.length;
			items[i].classList.add('is-on');
		}, ms);
	};

	/* Mouse tilt / parallax. data-tilt="x:-30:30 rx:6:-6 ry:-8:8" on children. data-tilt-local = relative to the element. */
	W.tilt = function (el) {
		if (reduce || window.matchMedia('(hover: none)').matches) return;
		var local = el.hasAttribute('data-tilt-local');
		var targets = $$('[data-tilt]', el).map(function (t) {
			var spec = {};
			t.getAttribute('data-tilt').split(/\s+/).forEach(function (p) {
				var a = p.split(':');
				if (a.length === 3) spec[a[0]] = [parseFloat(a[1]), parseFloat(a[2])];
			});
			var m = (t.getAttribute('style') || '').match(/perspective\((\d+)px\)/);
			return { el: t, spec: spec, persp: m ? m[1] : 0 };
		});
		var tx = 0, ty = 0, cx = 0, cy = 0, raf = 0;
		var map = function (v, r) { return r[0] + (v + 1) / 2 * (r[1] - r[0]); };
		function frame() {
			cx += (tx - cx) * 0.08;
			cy += (ty - cy) * 0.08;
			if (Math.abs(tx - cx) < 0.001 && Math.abs(ty - cy) < 0.001) { cx = tx; cy = ty; raf = 0; } else raf = requestAnimationFrame(frame);
			targets.forEach(function (t) {
				var s = t.spec, tr = '';
				if (t.persp) tr += 'perspective(' + t.persp + 'px) ';
				if (s.x) tr += 'translateX(' + map(cx, s.x).toFixed(2) + 'px) ';
				if (s.rx) tr += 'rotateX(' + map(cy, s.rx).toFixed(2) + 'deg) ';
				if (s.ry) tr += 'rotateY(' + map(cx, s.ry).toFixed(2) + 'deg)';
				t.el.style.transform = tr.trim();
			});
		}
		var kick = function () { if (!raf) raf = requestAnimationFrame(frame); };
		el.addEventListener('mousemove', function (e) {
			if (local) {
				var r = el.getBoundingClientRect();
				tx = ((e.clientX - r.left) / r.width - 0.5) * 2;
				ty = ((e.clientY - r.top) / r.height - 0.5) * 2;
			} else {
				tx = (e.clientX / window.innerWidth - 0.5) * 2;
				ty = (e.clientY / window.innerHeight - 0.5) * 2;
			}
			kick();
		});
		if (local) el.addEventListener('mouseleave', function () { tx = 0; ty = 0; kick(); });
	};

	/* Hero graphic: problems turn into ticks one by one, the score rises, then it starts over */
	W.scanner = function (el) {
		var marks = $$('[data-mark]', el), score = $('[data-score]', el), bar = $('[data-scorebar]', el);
		var start = parseInt(el.dataset.start, 10) || 58, step = parseInt(el.dataset.step, 10) || 9, t = 0;
		function render() {
			var o = Math.min(t, marks.length), s = start + o * step;
			marks.forEach(function (m, i) {
				var done = i < o;
				$('[data-ping]', m).hidden = done;
				$('[data-num]', m).hidden = done;
				$('[data-tick]', m).hidden = !done;
				swapClasses($('[data-badge]', m), done, 'bg-sage', 'bg-rose');
			});
			if (score) score.textContent = s;
			if (bar) bar.style.height = s + '%';
		}
		if (reduce) { t = marks.length; render(); return; }
		setInterval(function () { t = t >= marks.length + 2 ? 0 : t + 1; render(); }, 1100);
	};

	/* Problem section: drag from noise to signal */
	W.noise = function (el) {
		var chips = $$('[data-chip]', el), rows = $$('[data-row]', el), rowsBox = $('[data-rows]', el);
		var range = $('[data-range]', el), hint = $('[data-hint]', el), n = $('[data-n]', el), label = $('[data-n-label]', el);
		var btn = $('[data-replay]', el), icPlay = $('[data-ic-play]', el), icReplay = $('[data-ic-replay]', el);
		var signal = parseInt(el.dataset.signalCount, 10) || rows.length, stop = null, a = 0;
		function render(v) {
			a = v;
			var cnt = Math.round(100 * (1 - clamp((a - 0.05) / 0.8))), r = clamp((a - 0.5) / 0.4);
			chips.forEach(function (c, u) {
				var d = clamp((a - u * 0.012) / 0.55), x = +c.dataset.x, y = +c.dataset.y, rot = +c.dataset.r;
				c.style.left = (x + (42 - x) * d) + '%';
				c.style.top = (y + (46 - y) * d) + '%';
				c.style.opacity = 1 - d;
				c.style.transform = 'rotate(' + (rot * (1 - d)) + 'deg) scale(' + (1 - d * 0.6) + ')';
				c.style.filter = 'blur(' + (d * 4) + 'px)';
			});
			rows.forEach(function (row, u) {
				var d = clamp((r - u * 0.18) / 0.5);
				row.style.opacity = d;
				row.style.transform = 'translateY(' + ((1 - d) * 24) + 'px) scale(' + (0.94 + d * 0.06) + ')';
			});
			if (rowsBox) rowsBox.style.pointerEvents = r > 0.8 ? 'auto' : 'none';
			if (hint) hint.style.opacity = 1 - r;
			n.textContent = cnt > 0 ? cnt : signal;
			n.style.color = cnt > 0 ? 'var(--rose)' : 'var(--sage)';
			label.textContent = cnt > 0 ? label.dataset.noise : label.dataset.signal;
			range.value = Math.round(a * 100);
			range.style.background = 'linear-gradient(90deg, var(--sage) ' + (a * 100) + '%, var(--line) ' + (a * 100) + '%)';
			icPlay.hidden = a >= 1;
			icReplay.hidden = a < 1;
		}
		function run() {
			if (stop) stop();
			render(0);
			var timer = setTimeout(function () { stop = tween(0, 1, 4200, EASE.inout, render); }, 500);
			stop = function () { clearTimeout(timer); };
		}
		range.addEventListener('input', function () { if (stop) stop(); render(range.value / 100); });
		btn.addEventListener('click', run);
		render(0);
		if ('IntersectionObserver' in window) {
			var o = new IntersectionObserver(function (e) { if (e[0].isIntersecting) { o.disconnect(); run(); } }, { threshold: 0.4 });
			o.observe(el);
		} else run();
	};

	/* How it works: five steps that play by themselves (paused while hovered) */
	W.stepper = function (el) {
		var btns = $$('[data-step-btn]', el), panels = $$('[data-panel]', el), bar = $('[data-bar]', el), next = $('[data-next]', el);
		var status = $('[data-status]', el), ms = parseInt(el.dataset.interval, 10) || 5600, last = btns.length - 1;
		var cur = 0, paused = false, timer = 0;
		var CUR = 'border-sage bg-sage text-white shadow-[0_10px_30px_-8px_rgba(0,41,145,.6)]',
			DONE = 'border-sage bg-sage-soft text-sage',
			TODO = 'border-line bg-surface text-muted group-hover:border-sage/50';
		function schedule() {
			clearTimeout(timer);
			if (!paused) timer = setTimeout(function () { go((cur + 1) % btns.length); }, ms);
			if (next) {
				var show = cur < last && !paused;
				next.hidden = !show;
				if (show) {
					next.style.left = (cur / last * 100) + '%';
					next.classList.remove('vp-step-next');
					reflow(next);
					next.classList.add('vp-step-next');
				}
			}
			if (status) status.textContent = paused ? status.dataset.pause : status.dataset.play;
		}
		function go(i) {
			cur = i;
			if (bar) bar.style.width = (last ? cur / last * 100 : 0) + '%';
			btns.forEach(function (b, l) {
				var isCur = l === cur, done = l < cur, ic = $('[data-step-ic]', b);
				b.setAttribute('aria-current', isCur ? 'true' : 'false');
				[CUR, DONE, TODO].forEach(function (s) { s.split(' ').forEach(function (c) { ic.classList.remove(c); }); });
				(isCur ? CUR : done ? DONE : TODO).split(' ').forEach(function (c) { ic.classList.add(c); });
				$('[data-ic-step]', b).hidden = done;
				$('[data-ic-done]', b).hidden = !done;
				$('[data-halo]', b).hidden = !isCur;
				swapClasses($('[data-step-num]', b), isCur, 'text-sage', 'text-muted');
				swapClasses($('[data-step-label]', b), isCur, 'text-ink', 'text-muted');
			});
			showPanel(panels, function (p) { return +p.getAttribute('data-panel') === cur; });
			schedule();
		}
		btns.forEach(function (b, i) { b.addEventListener('click', function () { go(i); }); });
		$('[data-prev]', el).addEventListener('click', function () { go((cur + last) % (last + 1)); });
		$('[data-next-btn]', el).addEventListener('click', function () { go((cur + 1) % (last + 1)); });
		el.addEventListener('mouseenter', function () { paused = true; schedule(); });
		el.addEventListener('mouseleave', function () { paused = false; schedule(); });
		if (bar) bar.classList.add('vp-step-bar');
		go(0);
	};

	/* Dashboard demo */
	W.dash = function (el) {
		var data = JSON.parse(el.getAttribute('data-vp-data') || '{}'), tabsData = data.tabs || {}, txt = data.text || {};
		var tabs = $$('[data-tab]', el), group = $('[data-pill-group]', el);
		var ring = $('.vp-ring', el), good = $('[data-good]', el), sub = $('[data-sub]', el), chart = $('[data-chart]', el);
		var vals = $$('[data-tile-val]', el), bars = $$('[data-tile-bar]', el), open = $('[data-open]', el);
		var tasks = $$('[data-task]', el), scanBtn = $('[data-scan]', el), layer = $('[data-scan-layer]', el);
		var tab = tabs.length ? tabs[0].dataset.tab : '', approved = [], scanning = false;

		function chartSvg(pts, key) {
			var n = pts.length, xy = pts.map(function (v, i) { return [n > 1 ? i / (n - 1) * 520 : 0, 150 - (v - 20) / 80 * 134 - 8]; });
			var d = xy.map(function (p, i) { return (i ? 'L' : 'M') + p[0].toFixed(1) + ',' + p[1].toFixed(1); }).join(' ');
			var h = '<svg viewBox="0 0 520 150" class="h-40 w-full" preserveAspectRatio="none" aria-hidden="true"><defs><linearGradient id="g-' + key + '" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="var(--sage)" stop-opacity=".28"></stop><stop offset="1" stop-color="var(--sage)" stop-opacity="0"></stop></linearGradient></defs>';
			for (var l = 0; l < 4; l++) { var y = 150 / 4 * l + 8; h += '<line x1="0" x2="520" y1="' + y + '" y2="' + y + '" stroke="var(--line)" stroke-dasharray="3 5"></line>'; }
			h += '<path d="' + d + ' L520,150 L0,150 Z" fill="url(#g-' + key + ')" data-m="view" style="--fo:0;--md:.5s;--mt:.8s"></path>';
			h += '<path d="' + d + '" fill="none" stroke="var(--sage)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" data-m="view" data-m-path="1" style="--mt:1.2s;--me:ease-out"></path>';
			xy.forEach(function (p, i) { h += '<circle cx="' + p[0] + '" cy="' + p[1] + '" r="' + (i === n - 1 ? 5 : 3) + '" fill="var(--surface)" stroke="var(--sage)" stroke-width="2" data-m="view" style="--fs:0;--md:' + (0.3 + i * 0.1).toFixed(2) + 's"></circle>'; });
			return h + '</svg>';
		}
		function setRing(v) {
			var arc = $('.vp-ring-arc', ring), n = $('.vp-ring-num', ring);
			arc.dataset.value = v;
			n.dataset.n = v;
			replay(ring);
		}
		function render(tabChanged) {
			var d = tabsData[tab], r = approved.length * 3;
			if (!d) return;
			var overall = Math.min(100, d.overall + r);
			setRing(overall);
			good.textContent = overall >= 80 ? txt.good : txt.needs;
			sub.textContent = (txt.sub || '%s').replace('%s', tab);
			if (tabChanged) {
				chart.innerHTML = chartSvg(d.line, tab.replace(/\s/g, ''));
				initEngine(chart);
			}
			vals.forEach(function (v, i) {
				var x = Math.min(100, d.tiles[i] + (i === 0 ? r : 0));
				if (v.textContent !== String(x)) { v.textContent = x; replay(v); }
				var b = bars[i];
				if (b && b.dataset.mW !== x + '%') { b.dataset.mW = x + '%'; replay(b); }
			});
			open.textContent = tasks.length - approved.length;
		}
		tabs.forEach(function (b) {
			b.addEventListener('click', function () {
				if (tab === b.dataset.tab) return;
				tab = b.dataset.tab;
				tabs.forEach(function (x) {
					var on = x === b;
					x.setAttribute('aria-pressed', on ? 'true' : 'false');
					swapClasses($('span', x), on, 'text-bg', 'text-muted hover:text-ink');
				});
				placePill(group, b);
				render(true);
			});
		});
		if (group) pillWatch(group, function () { return $('[data-tab][aria-pressed="true"]', el); });
		tasks.forEach(function (li, i) {
			var btn = $('[data-approve]', li), dot = $('[data-dot]', li), text = $('[data-task-text]', li);
			btn.addEventListener('click', function () {
				var on = approved.indexOf(i) < 0;
				approved = on ? approved.concat(i) : approved.filter(function (x) { return x !== i; });
				dot.style.background = on ? 'var(--sage)' : dot.dataset.color;
				swapClasses(text, on, 'text-muted line-through', '');
				swapClasses(btn, on, 'bg-sage-soft text-sage', 'bg-ink text-bg hover:bg-sage');
				$('[data-when="before"]', btn).hidden = on;
				$('[data-when="after"]', btn).hidden = !on;
				render(false);
			});
		});
		if (scanBtn) scanBtn.addEventListener('click', function () {
			if (scanning) return;
			scanning = true;
			var label = $('[data-scan-label]', scanBtn);
			$('[data-when="before"]', scanBtn).hidden = true;
			$('[data-when="after"]', scanBtn).hidden = false;
			label.textContent = txt.scanning;
			var line = doc.createElement('div');
			line.className = 'vp-scan-once pointer-events-none absolute inset-x-0 z-10 h-16 -translate-y-1/2 bg-gradient-to-b from-transparent via-sage/15 to-transparent';
			layer.appendChild(line);
			setTimeout(function () {
				scanning = false;
				line.remove();
				$('[data-when="before"]', scanBtn).hidden = false;
				$('[data-when="after"]', scanBtn).hidden = true;
				label.textContent = txt.scan;
			}, 2200);
		});
	};

	/* Product tour: groups, screens and numbered hotspots */
	W.tour = function (el) {
		var order = (el.dataset.order || '').split(','), ms = parseInt(el.dataset.interval, 10) || 4200;
		var screens = $$('[data-screen]', el), titles = $$('[data-screen-title]', el), lists = $$('[data-screen-list]', el);
		var groups = $$('[data-group]', el), mobiles = $$('[data-mobile-group]', el), pos = $('[data-pos]', el);
		var hoverBox = $('[data-tilt-local]', el) || el;
		var key = order[0], hs = 0, paused = false, timer = 0;

		function hotspotsCount() {
			var p = screens.filter(function (s) { return s.dataset.screen === key; })[0];
			return p ? $$('[data-hs-wrap]', p).length : 0;
		}
		function setHs(i) {
			hs = i;
			var p = screens.filter(function (s) { return s.dataset.screen === key; })[0];
			var list = lists.filter(function (l) { return l.dataset.screenList === key; })[0];
			if (p) $$('[data-hs-wrap]', p).forEach(function (w, j) {
				var on = j === hs, tip = $('[data-hs-tip]', w);
				$('[data-hs-ping]', w).hidden = !on;
				swapClasses($('[data-hs-dot]', w), on, 'bg-flare', 'bg-sage');
				if (on && tip.hidden) { replay(tip); tip.hidden = false; } else if (!on) tip.hidden = true;
			});
			if (list) $$('[data-hs-card]', list).forEach(function (c, j) {
				var on = j === hs;
				swapClasses(c, on, 'border-sage/40 bg-surface shadow-[0_16px_36px_-26px_rgba(0,41,145,.6)]', 'border-line bg-surface/50 hover:bg-surface');
				swapClasses($('[data-hs-dot]', c), on, 'bg-flare', 'bg-sage');
			});
		}
		function restart() {
			clearInterval(timer);
			timer = setInterval(function () {
				var n = hotspotsCount();
				if (!paused && n > 1) setHs((hs + 1) % n);
			}, ms);
		}
		function go(k) {
			key = k;
			showPanel(screens, function (p) { return p.dataset.screen === k; });
			titles.forEach(function (t) { t.hidden = t.dataset.screenTitle !== k; });
			lists.forEach(function (l) { l.hidden = l.dataset.screenList !== k; });
			groups.forEach(function (g) {
				var on = g.dataset.group.split(',').indexOf(k) > -1, btn = $('button', g), chev = $('svg.lucide-chevron-right', btn);
				swapClasses(g, on, 'is-active border-sage/30 bg-surface shadow-[0_18px_40px_-28px_rgba(0,41,145,.5)]', 'border-transparent hover:bg-surface/70');
				swapClasses($('[data-group-ic]', g), on, 'bg-sage text-white', 'bg-sage-soft text-sage');
				if (chev) swapClasses(chev, on, 'rotate-90 text-sage', '');
				$$('[data-sub]', g).forEach(function (s) {
					var sel = s.dataset.sub === k;
					swapClasses($('[data-sub-bg]', s), !sel, 'opacity-0', '');
					swapClasses($('[data-sub-dot]', s), sel, 'bg-sage', 'bg-line');
					swapClasses($('[data-sub-label]', s), sel, 'font-semibold text-sage', 'text-muted');
				});
			});
			mobiles.forEach(function (m) {
				m.hidden = m.dataset.mobileGroup.split(',').indexOf(k) < 0;
				$$('[data-chip]', m).forEach(function (c) { swapClasses(c, c.dataset.chip === k, 'bg-ink text-bg', 'border border-line text-muted'); });
			});
			if (pos) pos.textContent = order.indexOf(k) + 1;
			setHs(0);
			restart();
		}
		el.addEventListener('click', function (e) {
			var t = e.target.closest('[data-go], [data-hs], [data-prev], [data-next-btn]');
			if (!t || !el.contains(t)) return;
			if (t.hasAttribute('data-go')) go(t.getAttribute('data-go'));
			else if (t.hasAttribute('data-hs')) setHs(parseInt(t.getAttribute('data-hs'), 10));
			else {
				var i = order.indexOf(key) + (t.hasAttribute('data-prev') ? -1 : 1);
				go(order[(i + order.length) % order.length]);
			}
		});
		hoverBox.addEventListener('mouseenter', function () { paused = true; });
		hoverBox.addEventListener('mouseleave', function () { paused = false; });
		restart();
	};

	/* Tabs with a moving highlight: [data-tab] buttons + [data-panel] panels (Features section) */
	W.tabs = function (el) {
		var group = $('[data-pill-group]', el), btns = $$('[data-tab]', el), panels = $$('[data-panel]', el);
		btns.forEach(function (b) {
			b.addEventListener('click', function () {
				btns.forEach(function (x) {
					var on = x === b;
					x.setAttribute('aria-pressed', on ? 'true' : 'false');
					swapClasses(x, on, x.dataset.on, x.dataset.off);
				});
				placePill(group, b);
				showPanel(panels, function (p) { return p.getAttribute('data-panel') === b.getAttribute('data-tab'); });
			});
		});
		if (group) pillWatch(group, function () { return $('[data-tab][aria-pressed="true"]', el); });
	};

	/* Plans: Monthly / Yearly */
	W.billing = function (el) {
		var group = $('[data-pill-group]', el), btns = $$('[data-tab]', el), prices = $$('[data-price]', el), ctas = $$('[data-plan-cta]', el), yearly = false;
		function setCtas() {
			ctas.forEach(function (a) {
				var href = yearly ? a.dataset.hrefYearly : a.dataset.hrefMonthly;
				if (href) a.setAttribute('href', href);
			});
		}
		setCtas();
		btns.forEach(function (b) {
			b.addEventListener('click', function () {
				var y = b.dataset.tab === 'yearly';
				if (y === yearly) return;
				yearly = y;
				btns.forEach(function (x) {
					var on = x === b;
					x.setAttribute('aria-pressed', on ? 'true' : 'false');
					swapClasses($('span', x), on, 'text-bg', 'text-muted');
				});
				placePill(group, b);
				$$('[data-when]', el).forEach(function (w) { w.hidden = (w.getAttribute('data-when') === 'after') !== yearly; });
				prices.forEach(function (p) {
					if (p._vpStop) p._vpStop();
					var from = parseFloat(p.textContent) || 0, to = parseFloat(yearly ? p.dataset.yearly : p.dataset.monthly);
					p._vpStop = tween(from, to, 450, EASE.vp, function (v) { p.textContent = Math.round(v); });
				});
				setCtas();
			});
		});
		if (group) pillWatch(group, function () { return $('[data-tab][aria-pressed="true"]', el); });
	};

	/* Comparison table: highlight a column on hover */
	W.colhover = function (el) {
		var cells = $$('[data-col][data-hl]', el), cur = null;
		function set(col) {
			if (col === cur) return;
			cells.forEach(function (c) {
				var on = c.dataset.col === col;
				c.dataset.hl.split(' ').forEach(function (k) { c.classList.toggle(k, on); });
			});
			cur = col;
		}
		cells.forEach(function (c) {
			c.addEventListener('mouseenter', function () { set(c.dataset.col); });
			c.addEventListener('mouseleave', function () { set(null); });
		});
	};

	/* FAQ accordion: one answer open at a time */
	function initAccordion(acc) {
		if (acc._vpReady) return;
		acc._vpReady = true;
		var ease = 'cubic-bezier(.22,1,.36,1)';
		function toggle(btn, open) {
			var body = btn.closest('h3').nextElementSibling, ic = $('svg', btn);
			btn.setAttribute('aria-expanded', open ? 'true' : 'false');
			if (ic) ic.classList.toggle('rotate-45', open);
			body.style.transition = 'none';
			if (open) {
				body.hidden = false;
				body.style.height = '0px';
				body.style.opacity = '0';
				var h = body.scrollHeight;
				reflow(body);
				body.style.transition = 'height .3s ' + ease + ', opacity .3s ' + ease;
				body.style.height = h + 'px';
				body.style.opacity = '1';
				setTimeout(function () { if (btn.getAttribute('aria-expanded') === 'true') { body.style.transition = 'none'; body.style.height = ''; } }, 320);
			} else {
				body.style.height = body.scrollHeight + 'px';
				reflow(body);
				body.style.transition = 'height .3s ' + ease + ', opacity .3s ' + ease;
				body.style.height = '0px';
				body.style.opacity = '0';
				setTimeout(function () { if (btn.getAttribute('aria-expanded') === 'false') body.hidden = true; }, 320);
			}
		}
		var btns = $$('[data-vp-acc-btn]', acc);
		btns.forEach(function (b) {
			b.addEventListener('click', function () {
				var open = b.getAttribute('aria-expanded') !== 'true';
				btns.forEach(function (x) { if (x !== b && x.getAttribute('aria-expanded') === 'true') toggle(x, false); });
				toggle(b, open);
			});
		});
	}

	/* Screenshot lightbox */
	function initLightbox() {
		if (doc._vpLightbox) return;
		doc._vpLightbox = true;
		doc.addEventListener('click', function (e) {
			var btn = e.target.closest ? e.target.closest('[data-vp-lightbox]') : null;
			if (!btn) return;
			var img = $('img', btn);
			if (!img) return;
			e.preventDefault();
			var lb = doc.createElement('div'), prev = doc.activeElement;
			lb.className = 'vp-shot-lb';
			lb.setAttribute('role', 'dialog');
			lb.setAttribute('aria-modal', 'true');
			lb.setAttribute('aria-label', img.alt || 'Screenshot');
			lb.innerHTML = '<button type="button" aria-label="Close"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg></button>';
			var big = doc.createElement('img');
			big.src = img.currentSrc || img.src;
			big.alt = img.alt;
			big.width = img.width;
			big.height = img.height;
			lb.appendChild(big);
			var close = function () {
				lb.classList.remove('is-open');
				doc.body.style.overflow = '';
				doc.removeEventListener('keydown', key);
				setTimeout(function () { lb.remove(); }, 250);
				if (prev && prev.focus) prev.focus();
			};
			var key = function (ev) { if (ev.key === 'Escape') close(); };
			lb.addEventListener('click', close);
			big.addEventListener('click', function (ev) { ev.stopPropagation(); });
			doc.addEventListener('keydown', key);
			doc.body.appendChild(lb);
			doc.body.style.overflow = 'hidden';
			requestAnimationFrame(function () { lb.classList.add('is-open'); $('button', lb).focus(); });
		});
	}

	/* ------------------------------------------------------------------ *
	 * Boot
	 * ------------------------------------------------------------------ */
	function init(scope) {
		scope = scope || doc;
		$$('[data-vp]', scope).forEach(function (el) {
			var name = el.getAttribute('data-vp');
			if (!W[name] || el._vpWidget) return;
			el._vpWidget = true;
			try { W[name](el); } catch (err) { if (window.console) console.warn('[vp-motion ' + name + ']', err); }
		});
		$$('[data-vp-accordion]', scope).forEach(initAccordion);
		initEngine(scope === doc ? doc.body : scope);
		initLightbox();
	}
	window.VP_MOTION = init;
	window.VP_MOTION_REPLAY = replay;

	if (doc.readyState === 'loading') doc.addEventListener('DOMContentLoaded', function () { init(); });
	else init();
})();
