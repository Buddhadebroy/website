/* Interactive checklist block: progress saved in localStorage under "chk:<id>" (same key as the Next.js site). */
(function () {
	var ON = ['border-sage/40', 'bg-sage-soft/60'], OFF = ['border-line', 'bg-surface', 'hover:border-ink/30'];
	var BOX_ON = ['border-sage', 'bg-sage', 'text-white'], BOX_OFF = ['border-line'];
	function swap(el, add, rem) { rem.forEach(function (c) { el.classList.remove(c); }); add.forEach(function (c) { el.classList.add(c); }); }
	function init(root) {
		if (root.dataset.vpReady) return;
		root.dataset.vpReady = '1';
		var key = 'chk:' + root.getAttribute('data-vp-chk');
		var items = [].slice.call(root.querySelectorAll('[data-vp-chk-item]'));
		var ring = root.querySelector('[data-vp-chk-ring]');
		var pctEl = root.querySelector('[data-vp-chk-pct]');
		var countEl = root.querySelector('[data-vp-chk-count]');
		var msgEl = root.querySelector('[data-vp-chk-msg]');
		var C = parseFloat(ring.getAttribute('stroke-dasharray'));
		var done = [];
		try { done = JSON.parse(localStorage.getItem(key) || '[]') || []; } catch (e) {}
		var seen = false, raf = 0;

		function animateRing(pct) {
			// Same as <Ring key={pct}>: restart from 0 and run for 1.6s with ease-out.
			cancelAnimationFrame(raf);
			ring.style.transition = 'none';
			ring.setAttribute('stroke-dashoffset', C);
			var t0 = null, dur = 1600;
			var step = function (t) {
				if (!t0) t0 = t;
				var p = Math.min(1, (t - t0) / dur), e = 1 - Math.pow(1 - p, 2);
				ring.setAttribute('stroke-dashoffset', C * (1 - (pct / 100) * e));
				pctEl.textContent = Math.round(pct * e);
				if (p < 1) raf = requestAnimationFrame(step);
			};
			raf = requestAnimationFrame(step);
		}
		function render(anim) {
			var total = items.length, n = 0;
			items.forEach(function (b) {
				var on = done.indexOf(b.getAttribute('data-vp-chk-item')) > -1;
				if (on) n++;
				b.setAttribute('aria-pressed', on ? 'true' : 'false');
				swap(b, on ? ON : OFF, on ? OFF : ON);
				swap(b.querySelector('.vp-chk-box'), on ? BOX_ON : BOX_OFF, on ? BOX_OFF : BOX_ON);
				b.querySelector('.vp-chk-tick').hidden = !on;
				var t = b.querySelector('.vp-chk-text');
				t.classList.toggle('line-through', on); t.classList.toggle('text-muted', on);
			});
			var pct = total ? Math.round((n / total) * 100) : 0;
			countEl.textContent = root.dataset.count.replace('{done}', n).replace('{total}', total);
			msgEl.textContent = pct === 100 ? root.dataset.complete : root.dataset.progress;
			if (anim) animateRing(pct);
		}
		function save() { try { localStorage.setItem(key, JSON.stringify(done)); } catch (e) {} }
		items.forEach(function (b) {
			b.addEventListener('click', function () {
				var v = b.getAttribute('data-vp-chk-item'), i = done.indexOf(v);
				if (i > -1) done.splice(i, 1); else done.push(v);
				save(); render(true);
			});
		});
		var reset = root.querySelector('[data-vp-chk-reset]');
		if (reset) reset.addEventListener('click', function () { done = []; save(); render(true); });
		// Download: a plain-text copy of the checklist with the visitor's ticks ([x] done, [ ] to do).
		var dlBtn = root.querySelector('[data-vp-chk-download]');
		if (dlBtn) dlBtn.addEventListener('click', function () {
			var title = root.dataset.title || document.title, n = 0, lines = [];
			[].forEach.call(root.querySelectorAll('[data-vp-chk-group]'), function (g) {
				var name = g.getAttribute('data-vp-chk-group');
				if (name) lines.push('', name.toUpperCase());
				[].forEach.call(g.querySelectorAll('[data-vp-chk-item]'), function (b) {
					var v = b.getAttribute('data-vp-chk-item'), on = done.indexOf(v) > -1;
					if (on) n++;
					lines.push((on ? '[x] ' : '[ ] ') + v);
				});
			});
			var text = [title, n + ' of ' + items.length + ' done', location.href.split('#')[0]].concat(lines).join('\r\n') + '\r\n';
			var url = URL.createObjectURL(new Blob([text], { type: 'text/plain;charset=utf-8' }));
			var a = document.createElement('a');
			a.href = url; a.download = root.getAttribute('data-vp-chk') + '.txt';
			document.body.appendChild(a); a.click(); a.remove();
			setTimeout(function () { URL.revokeObjectURL(url); }, 1000);
		});
		render(false);
		// First ring animation when it scrolls into view (whileInView, once).
		if ('IntersectionObserver' in window) {
			var io = new IntersectionObserver(function (en) {
				if (en[0].isIntersecting && !seen) { seen = true; io.disconnect(); render(true); }
			});
			io.observe(ring.closest('svg'));
		} else render(true);
	}
	function boot() { [].forEach.call(document.querySelectorAll('[data-vp-chk]'), init); }
	if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', boot); else boot();
})();
