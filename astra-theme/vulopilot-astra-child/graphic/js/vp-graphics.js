/*!
 * VuloPilot graphics (2.1) – plain JavaScript, no dependencies.
 * Widgets for the graphics in graphic/parts, found by data-vpg="name":
 *   scan  – the features-hub scan list (rows finish one by one, then it starts again)
 *   flip  – two views that swap on their own; clicking a tab keeps it
 *   tabs  – tabs that move to the next one on their own; hover pauses, click picks
 *   loop  – plays the animations inside again every few seconds while visible
 * Enter animations, typing and before/after swaps come from vp-motion.js (window.VP_MOTION_REPLAY).
 * Call window.VP_GRAPHICS(element) after adding markup with AJAX.
 */
(function () {
	'use strict';

	var doc = document;
	var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var $ = function (s, c) { return (c || doc).querySelector(s); };
	var $$ = function (s, c) { return Array.prototype.slice.call((c || doc).querySelectorAll(s)); };
	var replay = function (el) { if (window.VP_MOTION_REPLAY) window.VP_MOTION_REPLAY(el); };

	/** Run fn(true) when el scrolls into view and fn(false) when it leaves. */
	function whenVisible(el, fn) {
		if (!('IntersectionObserver' in window)) { fn(true); return; }
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (e) { fn(e.isIntersecting); });
		}, { threshold: 0.15 });
		io.observe(el);
	}

	var W = {};

	/* Features hub: scan rows one after another, hold the result, start again. */
	W.scan = function (el) {
		var rows = $$('[data-row]', el), bar = $('[data-bar]', el), shown = $('[data-shown]', el);
		var total = $('[data-total]', el), status = $('[data-status]', el);
		var step = parseInt(el.dataset.step, 10) || 450, hold = parseInt(el.dataset.hold, 10) || 8;
		var n = rows.length, done = 0, timer = 0;
		function render() {
			var s = Math.min(done, n), sum = 0;
			rows.forEach(function (r, i) {
				r.classList.toggle('is-ready', i < s);
				if (i < s) sum += parseInt(r.dataset.n, 10) || 0;
			});
			if (bar) bar.style.width = (s / n * 100) + '%';
			if (shown) shown.textContent = s;
			if (total) total.textContent = sum;
			var finished = s === n;
			el.classList.toggle('is-running', !finished);
			if (status) status.textContent = finished ? el.dataset.done : el.dataset.scanning;
		}
		if (reduce) return;
		whenVisible(el, function (on) {
			clearInterval(timer);
			if (!on) return;
			timer = setInterval(function () { done = done >= n + hold ? 0 : done + 1; render(); }, step);
		});
		done = 0;
		render();
	};

	/* Two (or more) views that swap on their own. */
	W.flip = function (el) {
		var tabs = $$('[data-flip-tab]', el), panels = $$('[data-flip-panel]', el);
		var ms = parseInt(el.dataset.interval, 10) || 4200, cur = 0, held = false, timer = 0;
		function go(i) {
			cur = i;
			tabs.forEach(function (t, k) { t.setAttribute('aria-selected', k === i ? 'true' : 'false'); });
			panels.forEach(function (p, k) {
				var on = k === i;
				if (on && !p.classList.contains('is-on')) { p.classList.add('is-on'); replay(p); } else if (!on) p.classList.remove('is-on');
			});
		}
		tabs.forEach(function (t, i) { t.addEventListener('click', function () { held = true; clearInterval(timer); go(i); }); });
		if (reduce) return;
		whenVisible(el, function (on) {
			clearInterval(timer);
			if (on && !held) timer = setInterval(function () { go((cur + 1) % panels.length); }, ms);
		});
	};

	/* Tabs that advance on their own. */
	W.tabs = function (el) {
		var tabs = $$('[data-vpg-tab]', el), panels = $$('[data-vpg-panel]', el);
		var ms = parseInt(el.dataset.interval, 10) || 4000, cur = 0, held = false, hover = false, visible = false, timer = 0;
		el.style.setProperty('--vpg-ms', ms / 1000 + 's');
		function playing() { return visible && !held && !hover && !reduce; }
		function schedule() {
			clearTimeout(timer);
			var on = playing();
			el.classList.remove('is-playing');
			if (!on) return;
			void el.offsetWidth; // restart the progress line
			el.classList.add('is-playing');
			timer = setTimeout(function () { go((cur + 1) % tabs.length); }, ms);
		}
		function go(i) {
			cur = i;
			tabs.forEach(function (t, k) { t.classList.toggle('is-on', k === i); t.setAttribute('aria-selected', k === i ? 'true' : 'false'); });
			panels.forEach(function (p, k) {
				var on = k === i;
				if (on && !p.classList.contains('is-on')) { p.classList.add('is-on'); replay(p); } else if (!on) p.classList.remove('is-on');
			});
			schedule();
		}
		tabs.forEach(function (t, i) { t.addEventListener('click', function () { held = true; go(i); }); });
		el.addEventListener('mouseenter', function () { hover = true; schedule(); });
		el.addEventListener('mouseleave', function () { hover = false; schedule(); });
		whenVisible(el, function (on) { visible = on; schedule(); });
	};

	/* Play the animations inside again every few seconds while the graphic is on screen. */
	W.loop = function (el) {
		var ms = parseInt(el.dataset.every, 10) || 10000, timer = 0, hover = false;
		if (reduce) return;
		el.addEventListener('mouseenter', function () { hover = true; });
		el.addEventListener('mouseleave', function () { hover = false; });
		whenVisible(el, function (on) {
			clearInterval(timer);
			if (on) timer = setInterval(function () { if (!hover && !doc.hidden) replay(el); }, ms);
		});
	};

	function init(scope) {
		scope = scope || doc;
		var list = $$('[data-vpg]', scope);
		if (scope.getAttribute && scope.getAttribute('data-vpg')) list.unshift(scope);
		list.forEach(function (el) {
			var name = el.getAttribute('data-vpg');
			if (!W[name] || el._vpgWidget) return;
			el._vpgWidget = true;
			try { W[name](el); } catch (err) { if (window.console) console.warn('[vp-graphics ' + name + ']', err); }
		});
	}
	window.VP_GRAPHICS = init;

	if (doc.readyState === 'loading') doc.addEventListener('DOMContentLoaded', function () { init(); });
	else init();
})();
