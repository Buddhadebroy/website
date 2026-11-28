/* Plan comparison block: accordion, "Paid extras only" and Expand / Collapse all. */
(function () {
	var ease = 'cubic-bezier(.22,1,.36,1)';
	function setOpen(group, open, instant) {
		var btn = group.querySelector('[data-vp-cm-toggle]');
		var body = group.querySelector('[data-vp-cm-body]');
		var chev = group.querySelector('.vp-cm-chev');
		var isOpen = btn.getAttribute('aria-expanded') === 'true';
		if (open === isOpen) return;
		btn.setAttribute('aria-expanded', open ? 'true' : 'false');
		if (chev) chev.classList.toggle('rotate-180', open);
		body.style.transition = 'none';
		if (open) {
			body.hidden = false;
			body.style.height = '0px'; body.style.opacity = '0';
			var h = body.scrollHeight;
			void body.offsetHeight;
			if (instant) { body.style.height = ''; body.style.opacity = ''; return; }
			body.style.transition = 'height .25s ' + ease + ', opacity .25s ' + ease;
			body.style.height = h + 'px'; body.style.opacity = '1';
			setTimeout(function () { if (btn.getAttribute('aria-expanded') === 'true') { body.style.transition = 'none'; body.style.height = ''; } }, 260);
		} else {
			body.style.height = body.scrollHeight + 'px';
			void body.offsetHeight;
			if (instant) { body.style.height = '0px'; body.style.opacity = '0'; body.hidden = true; return; }
			body.style.transition = 'height .25s ' + ease + ', opacity .25s ' + ease;
			body.style.height = '0px'; body.style.opacity = '0';
			setTimeout(function () { if (btn.getAttribute('aria-expanded') === 'false') body.hidden = true; }, 260);
		}
	}
	function init(root) {
		if (root.dataset.vpCmReady) return;
		root.dataset.vpCmReady = '1';
		var only = root.querySelector('[data-vp-cm-only]');
		var all = root.querySelector('[data-vp-cm-all]');
		var groups = [].slice.call(root.querySelectorAll('[data-vp-cm-group]'));
		var visible = function () { return groups.filter(function (g) { return !g.hidden; }); };
		var syncAll = function () {
			var v = visible();
			var allOpen = v.length && v.every(function (g) { return g.querySelector('[data-vp-cm-toggle]').getAttribute('aria-expanded') === 'true'; });
			all.textContent = allOpen ? all.dataset.collapse : all.dataset.expand;
			return allOpen;
		};
		groups.forEach(function (g) {
			g.querySelector('[data-vp-cm-toggle]').addEventListener('click', function () {
				setOpen(g, this.getAttribute('aria-expanded') !== 'true');
				syncAll();
			});
		});
		all.addEventListener('click', function () {
			var open = !syncAll();
			visible().forEach(function (g) { setOpen(g, open); });
			all.textContent = open ? all.dataset.collapse : all.dataset.expand;
		});
		only.addEventListener('change', function () {
			var on = only.checked;
			groups.forEach(function (g) {
				g.hidden = on && g.dataset.hasPaid !== '1';
				[].forEach.call(g.querySelectorAll('.vp-cm-free'), function (r) { r.hidden = on; });
			});
			syncAll();
		});
	}
	function boot() { [].forEach.call(document.querySelectorAll('[data-vp-cm]'), init); }
	if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', boot); else boot();
})();
