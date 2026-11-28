/*!
 * VuloMail theme: interactive parts (menus, live log, scroll stories, demos, explorers).
 * Mirrors src/components/{header,home,interactive,visuals}.tsx of the original site, with the same timings.
 */

(function () {
	'use strict';
	var fx = window.vmFx;
	if (!fx) return;
	var EASE = fx.EASE_CSS, reduce = fx.reduce, Spring = fx.Spring;
	function $(s, c) { return (c || document).querySelector(s); }
	function $$(s, c) { return Array.prototype.slice.call((c || document).querySelectorAll(s)); }
	function animate(el, frames, opts) { return el.animate(frames, Object.assign({ fill: 'forwards', easing: EASE }, opts)); }
	/** Value of a design token (Customize → VuloMail → Design tokens). */
	function tok(n) { return getComputedStyle(document.documentElement).getPropertyValue(n).trim(); }
	// Ticks pause while the tab is hidden: animations don't run there, so nodes waiting for onfinish would pile up.
	function ticker(len, ms, fn) { if (reduce) return; var i = 0; setInterval(function () { if (document.hidden) return; i = (i + 1) % len; fn(i); }, ms); }

	/* ================================================================ header dropdowns */

	$$('[data-vm-nav]').forEach(function (nav) {
		var drops = $$('[data-vm-dropdown]', nav);
		function close(d) {
			if (!d.classList.contains('is-open')) return;
			d.classList.remove('is-open');
			var btn = $('button', d), panel = $('.vm-dropdown', d), chev = $('.lucide-chevron-down', d);
			btn.setAttribute('aria-expanded', 'false');
			chev.classList.remove('vm:rotate-180');
			// Each open/close replaces the previous animation: a finished close (fill: forwards) left in place kept
			// the panel at opacity 0 and blurred the next time it opened.
			if (panel._vmAnim) panel._vmAnim.cancel();
			var a = panel._vmAnim = animate(panel, [{ opacity: 1, transform: 'translateY(0) scale(1)', filter: 'blur(0px)' }, { opacity: 0, transform: 'translateY(6px) scale(0.98)', filter: 'blur(4px)' }], { duration: 300 });
			a.onfinish = function () { if (!d.classList.contains('is-open')) { panel.hidden = true; } a.cancel(); };
		}
		function open(d) {
			drops.forEach(function (o) { if (o !== d) close(o); });
			if (d.classList.contains('is-open')) return;
			d.classList.add('is-open');
			var btn = $('button', d), panel = $('.vm-dropdown', d), chev = $('.lucide-chevron-down', d);
			btn.setAttribute('aria-expanded', 'true');
			chev.classList.add('vm:rotate-180');
			panel.hidden = false;
			if (panel._vmAnim) panel._vmAnim.cancel();
			panel._vmAnim = animate(panel, [{ opacity: 0, transform: 'translateY(10px) scale(0.97)', filter: 'blur(6px)' }, { opacity: 1, transform: 'translateY(0) scale(1)', filter: 'blur(0px)' }], { duration: 300, fill: 'none' });
		}
		drops.forEach(function (d) {
			d.addEventListener('mouseenter', function () { open(d); });
			$('button', d).addEventListener('click', function () { d.classList.contains('is-open') ? close(d) : open(d); });
		});
		$$('[data-vm-plain]', nav).forEach(function (a) { a.addEventListener('mouseenter', function () { drops.forEach(close); }); });
		nav.addEventListener('mouseleave', function () { drops.forEach(close); });
		document.addEventListener('keydown', function (e) { if (e.key === 'Escape') drops.forEach(close); });
	});

	/* ================================================================ mobile menu (circle reveal from the button) */
	const accessibleText = document.createElement('span');
accessibleText.className = 'screen-reader-text';
accessibleText.textContent = originalText;

const animatedText = document.createElement('span');
animatedText.setAttribute('aria-hidden', 'true');

// Append your animated word elements to animatedText.

// Replace the original heading content.
heading.replaceChildren(accessibleText, animatedText);
	var toggle = $('[data-vm-menu-toggle]'), menu = $('[data-vm-mobile-menu]');
	if (toggle && menu) {
		// The full-screen menu lives directly in <body>: never inside the header (blur / transform would trap it).
		document.body.appendChild(menu);
		// The phone menu's button is a copy of the header button, so editing one in Kadence edits both.
		var hb = document.querySelector('.vm-header .vm-btn-header');
		if (hb && !menu.querySelector('.vm-btn-mobile')) {
			var copy = hb.cloneNode(true);
			copy.classList.add('vm-btn-mobile');
			var wrap = document.createElement('div');
			wrap.setAttribute('data-vm-mm', 'rise');
			wrap.appendChild(copy);
			menu.querySelector('.vm\\:space-y-8').appendChild(wrap);
		}
		var openM = false, items = $$('[data-vm-mm]', menu);
		var circle0 = 'circle(0% at calc(100% - 44px) 44px)', circle1 = 'circle(150% at calc(100% - 44px) 44px)';
		toggle.addEventListener('click', function () {
			openM = !openM;
			toggle.setAttribute('aria-expanded', String(openM));
			toggle.setAttribute('aria-label', openM ? 'Close menu' : 'Open menu');
			document.body.classList.toggle('vm-menu-open', openM);
			document.documentElement.style.overflow = openM ? 'hidden' : '';
			var show = $(openM ? '.vm-menu-close' : '.vm-menu-open', toggle), hide = $(openM ? '.vm-menu-open' : '.vm-menu-close', toggle);
			animate(hide, [{ transform: 'rotate(0)', opacity: 1 }, { transform: 'rotate(90deg)', opacity: 0 }], { duration: 200 }).onfinish = function () {
				hide.hidden = true; show.hidden = false;
				animate(show, [{ transform: 'rotate(-90deg)', opacity: 0 }, { transform: 'rotate(0)', opacity: 1 }], { duration: 200 });
			};
			if (openM) {
				menu.hidden = false;
				animate(menu, [{ clipPath: circle0 }, { clipPath: circle1 }], { duration: 600 });
				items.forEach(function (it, i) {
					var d = 150 + i * 40;
					if (it.dataset.vmMm === 'fade') animate(it, [{ opacity: 0 }, { opacity: 1 }], { duration: 300, delay: d, fill: 'both' });
					else animate(it, [{ opacity: 0, transform: 'translateY(20px)' }, { opacity: 1, transform: 'none' }], { duration: 500, delay: d, fill: 'both' });
				});
			} else {
				animate(menu, [{ clipPath: circle1 }, { clipPath: circle0 }], { duration: 600 }).onfinish = function () { if (!openM) menu.hidden = true; };
			}
		});
	}

	/* ================================================================ Live delivery log (new entry every 2.4 s) */

	$$('[data-vm-livelog]').forEach(function (box) {
		var rows = parseInt(box.dataset.vmLivelog, 10) || 4;
		var ul = $('ul', box), tpl = $('template', box);
		var feed = $$('li', tpl.content);
		var tick = 0;
		ticker(1000, 2400, function () {
			// Drop rows left over from a fade-out that never finished (e.g. a background tab).
			$$('li', ul).forEach(function (li, i) { if (i >= rows || li.style.position === 'absolute') li.remove(); });
			tick++;
			var lis = $$('li', ul);
			var before = lis.map(function (li) { return li.offsetTop; });
			var last = lis[lis.length - 1];
			var fresh = feed[((tick % feed.length) + feed.length) % feed.length].cloneNode(true);
			ul.insertBefore(fresh, ul.firstChild);
			if (lis.length >= rows && last) {
				var top = before[before.length - 1];
				last.style.position = 'absolute';
				last.style.top = top + 'px';
				last.style.left = '0';
				last.style.right = '0';
				animate(last, [{ opacity: 1 }, { opacity: 0 }], { duration: 200 }).onfinish = function () { last.remove(); };
			}
			lis.forEach(function (li, i) {
				if (li === last && lis.length >= rows) return;
				var dy = before[i] - li.offsetTop;
				if (dy) animate(li, [{ transform: 'translateY(' + dy + 'px)' }, { transform: 'none' }], { duration: 600 });
			});
			animate(fresh, [{ opacity: 0, transform: 'translateY(-16px)', backgroundColor: tok('--log-highlight') }, { opacity: 1, transform: 'none', backgroundColor: 'transparent' }], { duration: 600 });
		});
	});

	/* ================================================================ Hero visual layers (pointer + scroll depth) */

	var layers = $$('[data-vm-layer]');
	if (layers.length && !reduce) {
		var mx = 0, my = 0, sy = 0;
		var small = window.matchMedia('(max-width: 639px)');
		function apply() {
			layers.forEach(function (l) {
				var d = parseFloat(l.dataset.vmLayer);
				var scr = Math.min(1, Math.max(0, sy / 800)) * -d * (small.matches ? 30 : 160);
				l.style.transform = 'translateX(' + mx * d * 30 + 'px) translateY(' + (my * d * 30 + scr) + 'px)';
			});
		}
		var sX = new Spring(0, 60, 18, 1, function (v) { mx = v; apply(); });
		var sY = new Spring(0, 60, 18, 1, function (v) { my = v; apply(); });
		window.addEventListener('pointermove', function (e) { sX.set(e.clientX / window.innerWidth - 0.5); sY.set(e.clientY / window.innerHeight - 0.5); });
		fx.onScroll(function (y) { sy = y; apply(); });
	}

	/* ================================================================ Pipeline story (sticky, 5 steps) */

	$$('[data-vm-pipeline], .vm-pipeline.kb-row-layout-wrap').forEach(function (sec) {
		var steps = $$('[data-vm-step], .vm-pipe-step', sec), bars = $$('[data-vm-bar]', sec), env = $('[data-vm-envelope]', sec);
		var nodes = {};
		$$('[data-vm-node]', sec).forEach(function (n) { nodes[n.dataset.vmNode] = n; });
		var tpl = $('[data-vm-logentry-tpl]', sec);
		if (!tpl || !env || !steps.length) return;
		steps.forEach(function (st, i) { if (i) st.hidden = true; });
		var entry = tpl.content.firstElementChild.cloneNode(true);
		entry.hidden = true;
		var n = steps.length, active = 0, shown = 0, busy = false;
				var ringVar = { idle: 'var(--line)', on: 'var(--ink)', fail: 'var(--red)', ok: 'var(--green)' };
		var bg = { idle: 'var(--surface)', on: 'var(--surface)', fail: 'var(--red-soft)', ok: 'var(--green-soft)' };
		var nodeSprings = {};
		Object.keys(nodes).forEach(function (k) {
			var el = nodes[k], st = { y: 0, o: 0.55 };
			function ap() { el.style.transform = st.y ? 'translateY(' + st.y + 'px)' : ''; el.style.opacity = st.o; }
			nodeSprings[k] = { y: new Spring(0, 260, 20, 1, function (v) { st.y = v; ap(); }), o: new Spring(0.55, 260, 20, 1, function (v) { st.o = v; ap(); }), state: 'idle' };
		});
		function pill(el, on) {
			if (on && el.hidden) { el.hidden = false; new Spring(0, 500, 25, 1, function (v) { el.style.transform = 'scale(' + v + ')'; }).set(1); }
			else if (!on && !el.hidden) { var s = new Spring(1, 500, 25, 1, function (v) { el.style.transform = 'scale(' + Math.max(0, v) + ')'; if (v < 0.02) el.hidden = true; }); s.set(0); }
		}
		function setNode(k, state) {
			var ns = nodeSprings[k], el = nodes[k];
			if (ns.state === state) return;
			ns.state = state;
			el.style.borderColor = ringVar[state];
			el.style.backgroundColor = bg[state];
			ns.y.set(state === 'idle' ? 0 : -2);
			ns.o.set(state === 'idle' ? 0.55 : 1);
			pill($('.vm-node-fail', el), state === 'fail');
			pill($('.vm-node-ok', el), state === 'ok');
		}
		function render() {
			setNode('wp', 'on');
			setNode('vm', active >= 1 ? 'on' : 'idle');
			setNode('primary', active >= 2 ? (active >= 3 ? 'fail' : 'on') : 'idle');
			setNode('backup', active >= 3 ? 'ok' : 'idle');
			bars.forEach(function (b, i) { var on = i <= active; if (b.dataset.on !== String(on)) { b.dataset.on = String(on); animate(b, [{ transform: getComputedStyle(b).transform }, { transform: 'scaleX(' + (on ? 1 : 0) + ')' }], { duration: 500 }); } });
			var wantEntry = active >= 4;
			if (wantEntry && entry.hidden) {
				tpl.previousElementSibling.appendChild(entry);
				entry.hidden = false;
				var h = entry.scrollHeight;
				animate(entry, [{ opacity: 0, transform: 'translateY(20px)', height: '0px' }, { opacity: 1, transform: 'none', height: h + 'px' }], { duration: 500 }).onfinish = function () { entry.style.height = 'auto'; entry.style.opacity = 1; entry.getAnimations().forEach(function (a) { a.cancel(); }); };
			} else if (!wantEntry && !entry.hidden && !entry.dataset.closing) {
				entry.dataset.closing = '1';
				animate(entry, [{ opacity: 1, height: entry.offsetHeight + 'px' }, { opacity: 0, height: '0px' }], { duration: 500 }).onfinish = function () { delete entry.dataset.closing; if (active < 4) { entry.hidden = true; entry.getAnimations().forEach(function (a) { a.cancel(); }); entry.remove(); } };
			}
			swap();
		}
		function swap() {
			if (busy || shown === active) return;
			busy = true;
			var out = steps[shown];
			animate(out, [{ opacity: 1, transform: 'none', filter: 'blur(0px)' }, { opacity: 0, transform: 'translateY(-20px)', filter: 'blur(6px)' }], { duration: 500 }).onfinish = function () {
				out.hidden = true; out.getAnimations().forEach(function (a) { a.cancel(); });
				shown = active;
				var inn = steps[shown];
				inn.hidden = false;
				animate(inn, [{ opacity: 0, transform: 'translateY(30px)', filter: 'blur(8px)' }, { opacity: 1, transform: 'none', filter: 'blur(0px)' }], { duration: 500 }).onfinish = function () { busy = false; inn.getAnimations().forEach(function (a) { a.cancel(); }); swap(); };
			};
		}
		var stops = [0, 0.2, 0.4, 0.6, 0.8, 1], tops = [0, 20, 40, 62, 84, 92];
		var envSpring = new Spring(0, 120, 30, 1, function (p) {
			var i = 0; while (i < stops.length - 2 && p > stops[i + 1]) i++;
			var t = (p - stops[i]) / (stops[i + 1] - stops[i]);
			t = Math.min(1, Math.max(0, t));
			env.style.top = (tops[i] + (tops[i + 1] - tops[i]) * t) + '%';
		});
		var first = true;
		fx.onScroll(function (y) {
			var top = fx.pageTop(sec), h = sec.offsetHeight, vh = window.innerHeight;
			var p = Math.min(1, Math.max(0, (y - top) / Math.max(1, h - vh)));
			var a = Math.min(n - 1, Math.floor(p * n));
			if (first) { first = false; envSpring.jump(p); } else envSpring.set(p);
			if (a !== active) { active = a; }
			render();
		});
	});

	/* ================================================================ Principles track (sticky, horizontal) */

	$$('[data-vm-track], .vm-principles.kb-row-layout-wrap').forEach(function (sec) {
		var row = $('[data-vm-track-x], .vm-track-x > .kt-row-column-wrap', sec);
		if (!row) return;
		var s = new Spring(0, 90, 24, 1, function (v) { row.style.transform = v ? 'translateX(' + v + '%)' : ''; });
		var first = true;
		fx.onScroll(function (y) {
			var top = fx.pageTop(sec), h = sec.offsetHeight, vh = window.innerHeight;
			var p = Math.min(1, Math.max(0, (y - top) / Math.max(1, h - vh)));
			if (reduce) return;
			if (first) { first = false; s.jump(-62 * p); } else s.set(-62 * p);
		});
	});

	/* ================================================================ Footer wordmark (scroll-linked) */

	$$('[data-vm-wordmark]').forEach(function (p) {
		var footer = p.closest('footer') || p.parentElement;
		fx.onScroll(function (y) {
			var top = fx.pageTop(footer), h = footer.offsetHeight, vh = window.innerHeight;
			var t = Math.min(1, Math.max(0, (y + vh - top) / Math.max(1, h)));
			var o = t < 0.6 ? (t / 0.6) * 0.4 : 0.4 + ((t - 0.6) / 0.4) * 0.6;
			p.style.transform = 'translateX(' + (-18 + 18 * t) + '%)';
			p.style.opacity = o;
		});
	});

	/* ================================================================ Bento demos */

	$$('[data-vm-failover]').forEach(function (box) {
		var cells = $$('[data-vm-fo]', box);
		function paint(tick) {
			cells.forEach(function (c, i) {
				var state = i < tick ? 'fail' : i === tick ? 'ok' : 'idle';
				c.style.transition = 'background-color .4s, border-color .4s, transform .4s';
				c.style.backgroundColor = state === 'fail' ? 'var(--red-soft)' : state === 'ok' ? 'var(--green-soft)' : 'var(--surface)';
				c.style.borderColor = state === 'fail' ? 'var(--red)' : state === 'ok' ? 'var(--green)' : 'var(--line)';
				c.style.transform = 'translateY(' + (state === 'ok' ? -4 : 0) + 'px)';
				$('span', c).textContent = state === 'fail' ? 'failed' : state === 'ok' ? 'delivered' : 'standby';
			});
		}
		ticker(3, 1600, paint);
	});

	$$('[data-vm-diag]').forEach(function (ul) {
		fx.inView(ul, function () {
			$$('[data-vm-diag-i]', ul).forEach(function (li, i) {
				animate(li, [{ opacity: 0, transform: 'translateX(-12px)' }, { opacity: 1, transform: 'none' }], { duration: 500, delay: 150 + i * 180 });
			});
		}, '-80px');
	});

	$$('[data-vm-secret]').forEach(function (box) {
		var label = $('[data-vm-secret-label]', box), out = $('[data-vm-secret-text]', box);
		var chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz0123456789•';
		var id = null;
		function run(hover) {
			label.textContent = hover ? 'What the browser sees' : 'What the database holds';
			var target = hover ? '••••••••word' : 'vm1:Qm9nYXNzZWNyZXQ…';
			var frame = 0;
			clearInterval(id);
			id = setInterval(function () {
				frame++;
				out.textContent = target.split('').map(function (c, i) { return i < frame / 1.5 ? c : chars[Math.floor(Math.random() * chars.length)]; }).join('');
				if (frame / 1.5 > target.length) clearInterval(id);
			}, 28);
		}
		run(false);
		box.addEventListener('mouseenter', function () { run(true); });
		box.addEventListener('mouseleave', function () { run(false); });
	});

	/* Toggles: knob springs left↔right (stiffness 500, damping 30) */
	function bindToggle(btn, onChange) {
		var knob = $('.vm-knob', btn);
		var on = btn.getAttribute('aria-pressed') === 'true' || btn.getAttribute('aria-checked') === 'true';
		var onCls = btn.dataset.on || 'vm:bg-ink';
		var s = new Spring(on ? 24 : 4, 500, 30, 1, function (v) { knob.style.left = v + 'px'; });
		btn.addEventListener('click', function (e) {
			e.preventDefault();
			e.stopPropagation();
			on = !on;
			btn.setAttribute(btn.hasAttribute('aria-checked') ? 'aria-checked' : 'aria-pressed', String(on));
			btn.classList.toggle(onCls, on);
			btn.classList.toggle('vm:bg-line-strong', !on);
			s.set(on ? 24 : 4);
			if (onChange) onChange(on);
			btn.dispatchEvent(new CustomEvent('vm-toggle', { bubbles: true, detail: on }));
		});
	}
	window.vmBindToggle = bindToggle;
	$$('[data-vm-toggle]').forEach(function (b) { if (!b.dataset.vmBound) { b.dataset.vmBound = '1'; bindToggle(b); } });

	$$('[data-vm-sources]').forEach(function (box) {
		var list = JSON.parse(box.dataset.vmSources);
		ticker(list.length, 1400, function (i) {
			var old = box.firstElementChild;
			var fresh = document.createElement('span');
			fresh.textContent = list[i];
			box.appendChild(fresh);
			old.style.position = 'absolute';
			old.style.left = '0.75rem';
			animate(old, [{ transform: 'none', opacity: 1 }, { transform: 'translateY(-24px)', opacity: 0 }], { duration: 400 }).onfinish = function () { old.remove(); };
			animate(fresh, [{ transform: 'translateY(24px)', opacity: 0 }, { transform: 'none', opacity: 1 }], { duration: 400 });
		});
	});

	/* ================================================================ Phone: messages pop in, then the typing dots loop */

	$$('[data-vm-phone]').forEach(function (ph) {
		var msgs = $$('[data-vm-msg]', ph), typing = $('[data-vm-typing]', ph);
		fx.inView(ph, function () {
			msgs.forEach(function (m, i) {
				var st = { o: 0, y: 20, s: 0.9 };
				function ap() { m.style.opacity = Math.max(0, Math.min(1, st.o)); m.style.transform = 'translateY(' + st.y + 'px) scale(' + st.s + ')'; }
				setTimeout(function () {
					new Spring(0, 260, 22, 1, function (v) { st.o = v; ap(); }).set(1);
					new Spring(20, 260, 22, 1, function (v) { st.y = v; ap(); }).set(0);
					new Spring(0.9, 260, 22, 1, function (v) { st.s = v; ap(); }).set(1);
				}, reduce ? 0 : (400 + i * 700));
			});
			if (typing && !reduce) {
				typing.animate([{ opacity: 0, offset: 0 }, { opacity: 1, offset: 0.2353 }, { opacity: 1, offset: 0.4706 }, { opacity: 0, offset: 0.7059 }, { opacity: 0, offset: 1 }],
					{ duration: 3400, delay: 400 + msgs.length * 700, iterations: Infinity, easing: 'ease-in-out' });
			}
		}, '-120px');
	});

	/* ================================================================ FAQ: question / answer pairs (instant open, like <details>) */

	document.querySelectorAll('.vm-faq-item').forEach(function (item) {
		var q = item.querySelector('.vm-faq-q');
		if (!q) return;
		q.setAttribute('role', 'button');
		q.setAttribute('tabindex', '0');
		q.setAttribute('aria-expanded', 'false');
		function toggle() {
			var open = !item.classList.contains('vm-open');
			item.classList.toggle('vm-open', open);
			q.setAttribute('aria-expanded', String(open));
		}
		q.addEventListener('click', toggle);
		q.addEventListener('keydown', function (e) { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); toggle(); } });
	});


	/* ================================================================ segmented pill (moving ink background) */

	function segment(buttons, active, cls) {
		buttons.forEach(function (b) {
			var on = b === active;
			var bg = $('.vm-seg-bg', b);
			if (on && !bg) { bg = document.createElement('span'); bg.className = 'vm-seg-bg vm:absolute vm:inset-0 ' + (cls || 'vm:rounded-full vm:bg-ink'); b.insertBefore(bg, b.firstChild); }
			if (!on && bg) bg.remove();
			b.classList.toggle(b.closest('[data-vm-codetabs]') ? 'vm:text-white' : 'vm:text-paper', on);
			b.classList.toggle(b.closest('[data-vm-codetabs]') ? 'vm:text-white/45' : 'vm:text-ink-soft', !on);
		});
	}

	/* ================================================================ On this page (scroll spy) */

	$$('[data-vm-spy]').forEach(function (nav) {
		var links = $$('[data-vm-spy-link]', nav), bar = $('.vm-spy-bar', nav), cur = null;
		var sp = { y: new Spring(0, 400, 34, 1, function (v) { bar.style.top = v + 'px'; }), h: new Spring(0, 400, 34, 1, function (v) { bar.style.height = v + 'px'; }) };
		function set(id) {
			if (id === cur) return;
			cur = id;
			links.forEach(function (a) {
				var on = a.dataset.vmSpyLink === id;
				a.classList.toggle('vm:font-medium', on); a.classList.toggle('vm:text-ink', on);
				a.classList.toggle('vm:text-ink-mute', !on); a.classList.toggle('vm:hover:text-ink', !on);
				if (on) { var li = a.parentElement; sp.y.set(li.offsetTop); sp.h.set(li.offsetHeight); }
			});
		}
		var first = links[0] && links[0].parentElement;
		if (first) { sp.y.jump(first.offsetTop); sp.h.jump(first.offsetHeight); cur = links[0].dataset.vmSpyLink; }
		var io = new IntersectionObserver(function (entries) {
			var vis = entries.filter(function (e) { return e.isIntersecting; }).sort(function (a, b) { return a.boundingClientRect.top - b.boundingClientRect.top; });
			if (vis[0]) set(vis[0].target.id);
		}, { rootMargin: '-35% 0px -55% 0px' });
		links.forEach(function (a) { var t = document.getElementById(a.dataset.vmSpyLink); if (t) io.observe(t); });
	});

	/* ================================================================ Failover simulator */

	$$('[data-vm-failsim]').forEach(function (box) {
		var icons = $('[data-vm-icons]', box).innerHTML.split('|');
		var chain = $('[data-vm-chain]', box), result = $('[data-vm-result]', box), status = $('[data-vm-status]', box), att = $('[data-vm-attempts]', box);
		function val(k) { return $('[data-vm-sim="' + k + '"]', box).getAttribute('aria-checked') === 'true'; }
		function render() {
			var p = val('primary'), has = val('has'), b = val('backup'), last = val('last');
			var steps = [{ name: 'Primary · Mailgun', ok: p }];
			if (!p && has) steps.push({ name: 'Backup · SMTP', ok: b });
			if (!p && (!has || !b) && last) steps.push({ name: 'WordPress default mailer', ok: true });
			var delivered = steps[steps.length - 1].ok;
			var st = !delivered ? 'Failed' : steps.length === 1 ? 'Sent' : 'Sent via backup';
			var tone = !delivered ? 'vm:bg-red-soft vm:text-red' : steps.length === 1 ? 'vm:bg-green-soft vm:text-green' : 'vm:bg-blue-soft vm:text-blue';
			chain.innerHTML = steps.map(function (s) {
				return '<div data-vm-chain-step><div class="vm:flex vm:justify-center vm:py-1.5 vm:text-ink-mute">' + icons[0] + '</div><div class="vm:flex vm:items-center vm:justify-between vm:rounded-2xl vm:border-2 vm:px-4 vm:py-3 ' + (s.ok ? 'vm:border-green vm:bg-green-soft' : 'vm:border-red vm:bg-red-soft') + '"><span class="vm:text-sm vm:font-medium">' + s.name + '</span><span class="vm-sim-dot vm:grid vm:h-6 vm:w-6 vm:place-items-center vm:rounded-full ' + (s.ok ? 'vm:bg-green' : 'vm:bg-red') + ' vm:text-white">' + (s.ok ? icons[1] : icons[2]) + '</span></div></div>';
			}).join('');
			$$('[data-vm-chain-step]', chain).forEach(function (el, i) {
				animate(el, [{ opacity: 0, transform: 'translateY(-10px)' }, { opacity: 1, transform: 'none' }], { duration: 400, delay: i * 450, fill: 'both' });
				var dot = $('.vm-sim-dot', el); dot.style.transform = 'scale(0)';
				setTimeout(function () { new Spring(0, 400, 10, 1, function (v) { dot.style.transform = 'scale(' + v + ')'; }).set(1); }, i * 450 + 300);
			});
			status.className = 'vm:rounded-full vm:px-2.5 vm:py-1 vm:text-xs vm:font-medium ' + tone;
			status.textContent = st;
			animate(result, [{ opacity: 0, transform: 'translateY(10px)' }, { opacity: 1, transform: 'none' }], { duration: 400, delay: steps.length * 450, fill: 'both', easing: 'ease' });
			att.textContent = steps.length + (steps.length === 1 ? ' attempt' : ' attempts') + ' recorded on one row.' + (!delivered ? ' An “email failed” SMS alert would fire, at most once every 15 minutes.' : '');
		}
		box.addEventListener('vm-toggle', render);
	});

	/* ================================================================ Dashboard bars */

	$$('[data-vm-dash]').forEach(function (box) {
		var bars = $('[data-vm-bars]', box), btns = $$('[data-vm-days]', box), days = 7, seen = false;
		function seed(n) { var x = Math.sin(n * 9301 + 49297) * 233280; return x - Math.floor(x); }
		function draw() {
			var n = days === 7 ? 7 : days === 30 ? 15 : 18, h = '';
			for (var i = 0; i < n; i++) {
				var s = 0.35 + seed(i + days) * 0.6, f = seed(i * 3 + days) > 0.75 ? 0.05 + seed(i) * 0.08 : 0;
				h += '<div class="vm:flex vm:h-full vm:flex-1 vm:flex-col vm:justify-end vm:gap-0.5"><div class="vm:origin-bottom vm:rounded-sm vm:bg-red" style="height:' + f * 100 + '%;transform:scaleY(0)"></div><div class="vm:origin-bottom vm:rounded-t-md vm:bg-ink/85" style="height:' + s * 100 + '%;transform:scaleY(0)"></div></div>';
			}
			bars.innerHTML = h;
			if (seen) grow();
		}
		function grow() { $$('[data-vm-bars] > div', box).forEach(function (c, i) { $$('div', c).forEach(function (b) { animate(b, [{ transform: 'scaleY(0)' }, { transform: 'scaleY(1)' }], { duration: 600, delay: i * 30, fill: 'both' }); }); }); }
		btns.forEach(function (b) { b.addEventListener('click', function () { days = +b.dataset.vmDays; btns.forEach(function (x) { x.classList.toggle('vm:bg-ink', x === b); x.classList.toggle('vm:text-paper', x === b); x.classList.toggle('vm:text-ink-soft', x !== b); }); draw(); }); });
		draw();
		fx.inView(box, function () { seen = true; grow(); }, '-80px');
	});

	/* ================================================================ Diagnostics list (replays every time it comes into view) */

	$$('[data-vm-diaglist]').forEach(function (box) {
		var vis = false;
		function play() { $$('li', box).forEach(function (li, i) { animate(li, [{ opacity: 0, transform: 'translateX(-10px)' }, { opacity: 1, transform: 'none' }], { duration: 400, delay: i * 80, fill: 'both' }); }); }
		function hide() { $$('li', box).forEach(function (li) { li.getAnimations().forEach(function (a) { a.cancel(); }); }); }
		fx.inView(box, function (on) { vis = on; if (on) play(); else hide(); }, '-100px', false);
		$('[data-vm-rerun]', box).addEventListener('click', function () { hide(); if (vis) play(); });
	});

	/* ================================================================ Key diagram (sequenced) */

	$$('[data-vm-keydiag]').forEach(function (box) {
		var el = function (k) { return $('[data-vm-k="' + k + '"]', box); };
		fx.inView(box, function () {
			animate(el(0), [{ opacity: 0, transform: 'translateX(-30px)' }, { opacity: 1, transform: 'none' }], { duration: 700, fill: 'both' });
			animate(el(1), [{ opacity: 0, transform: 'translateX(30px)' }, { opacity: 1, transform: 'none' }], { duration: 700, fill: 'both' });
			animate(el(2), [{ transform: 'scaleY(0)' }, { transform: 'scaleY(1)' }], { duration: 500, delay: 600, fill: 'both', easing: 'ease-out' });
			setTimeout(function () { var st = { o: 0, s: 0.8 }; function ap() { el(3).style.opacity = Math.min(1, st.o); el(3).style.transform = 'scale(' + st.s + ')'; } new Spring(0, 260, 20, 1, function (v) { st.o = v; ap(); }).set(1); new Spring(0.8, 260, 20, 1, function (v) { st.s = v; ap(); }).set(1); }, 900);
			animate(el(4), [{ transform: 'scaleY(0)' }, { transform: 'scaleY(1)' }], { duration: 500, delay: 1200, fill: 'both', easing: 'ease-out' });
			animate(el(5), [{ opacity: 0, transform: 'translateY(20px)' }, { opacity: 1, transform: 'none' }], { duration: 600, delay: 1500, fill: 'both' });
			animate(el(6), [{ opacity: 0 }, { opacity: 1 }], { duration: 300, delay: 1900, fill: 'both', easing: 'ease-out' });
		}, '-100px');
	});

	/* ================================================================ Provider explorer */

	$$('[data-vm-providers]').forEach(function (box) {
		var filter = 'all', q = '', btns = $$('[data-vm-filter]', box), cards = $$('[data-vm-cards] > button', box), empty = $('[data-vm-empty]', box), input = $('[data-vm-search]', box);
		function apply() {
			var n = 0;
			cards.forEach(function (c) {
				var k = c.dataset.kind;
				var ok = (filter === 'all' || (filter === 'smtp' ? (k === 'smtp' || k === 'preset') : k === filter)) && (!q || c.dataset.name.indexOf(q) >= 0);
				if (ok && c.hidden) { c.hidden = false; animate(c, [{ opacity: 0, transform: 'scale(0.94) translateY(20px)' }, { opacity: 1, transform: 'none' }], { duration: 450 }); }
				if (!ok) c.hidden = true;
				if (ok) n++;
			});
			empty.hidden = n > 0;
			$('span', empty).textContent = input.value;
		}
		btns.forEach(function (b) { b.addEventListener('click', function () { filter = b.dataset.vmFilter; segment(btns, b); apply(); }); });
		input.addEventListener('input', function () { q = input.value.trim().toLowerCase(); filter = 'all'; segment(btns, btns[0]); apply(); });
		cards.forEach(function (c) {
			c.addEventListener('click', function () {
				var open = c.getAttribute('aria-expanded') !== 'true';
				cards.forEach(function (o) { if (o !== c && o.getAttribute('aria-expanded') === 'true') toggleCard(o, false); });
				toggleCard(c, open);
			});
		});
		function toggleCard(c, open) {
			var more = $('.vm-prov-more', c);
			c.setAttribute('aria-expanded', String(open));
			$('.lucide-plus', c).classList.toggle('vm:rotate-45', open);
			$('.vm-prov-label', c).textContent = open ? 'Hide details' : 'Details';
			if (open) { more.hidden = false; var h = more.scrollHeight; animate(more, [{ height: '0px', opacity: 0 }, { height: h + 'px', opacity: 1 }], { duration: 400 }).onfinish = function () { more.getAnimations().forEach(function (a) { a.cancel(); }); }; }
			else { animate(more, [{ height: more.offsetHeight + 'px', opacity: 1 }, { height: '0px', opacity: 0 }], { duration: 400 }).onfinish = function () { more.hidden = true; more.getAnimations().forEach(function (a) { a.cancel(); }); }; }
		}
		cards.forEach(function (c) { c.addEventListener('mouseenter', function () { c.style.transition = 'transform .3s'; c.style.transform = 'translateY(-4px)'; }); c.addEventListener('mouseleave', function () { c.style.transform = ''; }); });
	});

	/* ================================================================ SMS alert explorer */

	$$('[data-vm-alerts]').forEach(function (box) {
		var data = JSON.parse(box.dataset.vmAlerts), groups = data.groups, enabled = data.enabled, edits = {};
		var gi = 0, sel = groups[0].alerts[0].id;
		var list = $('[data-vm-alert-list]', box), to = $('[data-vm-to]', box), tpl = $('[data-vm-tpl]', box), ph = $('[data-vm-ph]', box);
		var gbtns = $$('[data-vm-group]', box), lockHtml = $('[data-vm-lock]', box).innerHTML;
		function esc(t) { return String(t).replace(/[&<>"]/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]; }); }
		function fill(t) { return t.replace(/\{(\w+)\}/g, function (m, k) { return data.samples[k] !== undefined ? data.samples[k] : m; }); }
		function segs(t) { var len = Array.from(t).length, basic = /^[\x20-\x7E\n\r]*$/.test(t), single = basic ? 160 : 70, multi = basic ? 153 : 67; return { len: len, basic: basic, count: len === 0 ? 0 : len <= single ? 1 : Math.ceil(len / multi) }; }
		function alert() { var g = groups[gi]; return g.alerts.filter(function (a) { return a.id === sel; })[0] || g.alerts[0]; }
		function drawList(anim) {
			var g = groups[gi];
			to.innerHTML = 'Sent to: <b class="vm:font-medium vm:text-ink-soft">' + esc(g.to) + '</b>' + (g.requires ? lockHtml.replace('Requires </span>', 'Requires ' + esc(g.requires) + '</span>') : '');
			list.innerHTML = g.alerts.map(function (a) {
				var on = !!enabled[a.id];
				return '<li><div role="button" tabindex="0" data-id="' + a.id + '" class="vm:flex vm:cursor-pointer vm:items-center vm:gap-4 vm:rounded-2xl vm:px-4 vm:py-3 vm:transition-colors ' + (a.id === sel ? 'vm:bg-paper' : 'vm:hover:bg-paper/60') + '"><span class="vm:min-w-0 vm:flex-1"><span class="vm:block vm:text-sm vm:font-medium">' + esc(a.label) + '</span><span class="vm:block vm:text-xs vm:text-ink-mute">' + esc(a.when) + '</span></span>' +
					'<button type="button" aria-label="Switch ' + esc(a.label) + ' ' + (on ? 'off' : 'on') + '" aria-pressed="' + on + '" data-vm-toggle data-on="vm:bg-green" class="vm:relative vm:h-6 vm:w-11 vm:shrink-0 vm:rounded-full vm:transition-colors vm:duration-300 ' + (on ? 'vm:bg-green' : 'vm:bg-line-strong') + '"><span class="vm-knob vm:absolute vm:top-1 vm:h-4 vm:w-4 vm:rounded-full vm:bg-white vm:shadow" style="left:' + (on ? 24 : 4) + 'px"></span></button></div></li>';
			}).join('');
			$$('[data-vm-toggle]', list).forEach(function (b) {
				var id = b.closest('[data-id]').dataset.id;
				bindToggle(b, function (on) { enabled[id] = on; sel = id; drawList(false); drawCard(); });
			});
			$$('[data-id]', list).forEach(function (r) {
				r.addEventListener('click', function () { sel = r.dataset.id; drawList(false); drawCard(); });
				r.addEventListener('keydown', function (e) { if (e.key === 'Enter') { sel = r.dataset.id; drawList(false); drawCard(); } });
			});
			if (anim) animate(list, [{ opacity: 0, transform: 'translateY(10px)' }, { opacity: 1, transform: 'none' }], { duration: 300 });
		}
		var lastId = null;
		function drawCard() {
			var a = alert(), on = !!enabled[a.id], text = edits[a.id] !== undefined ? edits[a.id] : a.template;
			var st = $('[data-vm-state]', box);
			st.className = 'vm:rounded-full vm:px-2.5 vm:py-1 vm:text-xs vm:font-medium ' + (on ? 'vm:bg-green-soft vm:text-green' : 'vm:bg-paper-deep vm:text-ink-mute');
			st.textContent = on ? 'On' : 'Off by default';
			$('[data-vm-title]', box).textContent = a.label;
			if (document.activeElement !== tpl) tpl.value = text;
			ph.innerHTML = a.placeholders.map(function (p) { return '<button type="button" data-ph="' + p + '" class="vm:rounded-md vm:bg-blue-soft vm:px-2 vm:py-1 vm:font-mono vm:text-[0.7rem] vm:text-blue vm:transition-colors vm:hover:bg-blue vm:hover:text-white">{' + p + '}</button>'; }).join('');
			$$('[data-ph]', ph).forEach(function (b) { b.addEventListener('click', function () { edits[a.id] = (edits[a.id] !== undefined ? edits[a.id] : a.template) + ' {' + b.dataset.ph + '}'; tpl.value = edits[a.id]; animate(b, [{ transform: 'scale(0.92)' }, { transform: 'none' }], { duration: 200 }); drawCard(); }); });
			var prev = fill(text), s = segs(prev), pv = $('[data-vm-preview]', box);
			pv.innerHTML = prev ? esc(prev) : '<span class="vm:text-paper/40">Empty template uses the default.</span>';
			if (lastId !== a.id) { lastId = a.id; animate(pv, [{ opacity: 0, transform: 'translateY(8px) scale(0.98)' }, { opacity: 1, transform: 'none' }], { duration: 300 }); }
			$('[data-vm-chars]', box).textContent = s.len + ' chars · ' + (s.basic ? 'basic Latin' : 'Unicode');
			var sg = $('[data-vm-segs]', box), txt = s.count + (s.count === 1 ? ' segment' : ' segments');
			if (sg.textContent !== txt) { sg.textContent = txt; sg.style.display = 'inline-block'; animate(sg, [{ transform: 'scale(1.3)', color: tok('--red') }, { transform: 'scale(1)', color: tok('--ink-mute') }], { duration: 400 }); }
		}
		tpl.addEventListener('input', function () { edits[alert().id] = tpl.value; drawCard(); });
		gbtns.forEach(function (b, i) { b.addEventListener('click', function () { gi = i; sel = groups[i].alerts[0].id; segment(gbtns, b); drawList(true); drawCard(); }); });
		drawList(false); drawCard();
	});

	/* ================================================================ FAQ explorer */

	$$('[data-vm-faqx]').forEach(function (box) {
		var cat = 'All', q = '', cbtns = $$('[data-vm-cat]', box), items = $$('li[data-cat]', box), none = $('[data-vm-none-tpl]', box).content.firstElementChild.cloneNode(true), input = $('[data-vm-q]', box);
		function apply() {
			var n = 0;
			items.forEach(function (li) { var ok = (cat === 'All' || li.dataset.cat === cat) && li.dataset.text.indexOf(q) >= 0; if (ok && li.hidden) animate(li, [{ opacity: 0 }, { opacity: 1 }], { duration: 300 }); li.hidden = !ok; if (ok) n++; });
			if (n > 0) { if (none.parentNode) none.parentNode.removeChild(none); } else { items[0].parentNode.appendChild(none); } $('span', none).textContent = input.value;
		}
		cbtns.forEach(function (b) { b.addEventListener('click', function () { cat = b.dataset.vmCat; segment(cbtns, b); apply(); }); });
		input.addEventListener('input', function () { q = input.value.toLowerCase(); apply(); });
		items.forEach(function (li) {
			var btn = $('button', li), panel = btn.nextElementSibling, plus = $('.vm-faq-plus', li);
			btn.addEventListener('click', function () {
				var open = btn.getAttribute('aria-expanded') !== 'true';
				items.forEach(function (o) { if (o !== li && $('button', o).getAttribute('aria-expanded') === 'true') set(o, false); });
				set(li, open);
			});
		});
		function set(li, open) {
			var btn = $('button', li), panel = btn.nextElementSibling, plus = $('.vm-faq-plus', li);
			btn.setAttribute('aria-expanded', String(open));
			plus.style.transition = 'transform .3s, background-color .3s, color .3s';
			plus.style.transform = open ? 'rotate(45deg)' : 'none';
			plus.style.backgroundColor = open ? 'var(--ink)' : 'var(--surface)';
			plus.style.color = open ? 'var(--paper)' : 'var(--ink)';
			if (open) { panel.hidden = false; var h = panel.scrollHeight; animate(panel, [{ height: '0px', opacity: 0 }, { height: h + 'px', opacity: 1 }], { duration: 400 }).onfinish = function () { panel.getAnimations().forEach(function (a) { a.cancel(); }); }; }
			else { animate(panel, [{ height: panel.offsetHeight + 'px', opacity: 1 }, { height: '0px', opacity: 0 }], { duration: 400 }).onfinish = function () { panel.hidden = true; panel.getAnimations().forEach(function (a) { a.cancel(); }); }; }
		}
	});

	/* ================================================================ Code tabs */

	$$('[data-vm-codetabs]').forEach(function (box) {
		var tabs = $$('[data-vm-tab]', box), panels = $$('[data-vm-panel]', box), cur = 0, copy = $('[data-vm-copy]', box), check = $('[data-vm-check]', box).innerHTML;
		tabs.forEach(function (t, i) {
			t.addEventListener('click', function () {
				if (i === cur) return;
				segment(tabs, t, 'vm:rounded-xl vm:bg-white/10');
				var out = panels[cur]; cur = i;
				animate(out, [{ opacity: 1, transform: 'none' }, { opacity: 0, transform: 'translateY(-6px)' }], { duration: 300 }).onfinish = function () {
					out.hidden = true; out.getAnimations().forEach(function (a) { a.cancel(); });
					panels[i].hidden = false;
					animate(panels[i], [{ opacity: 0, transform: 'translateY(10px)', filter: 'blur(4px)' }, { opacity: 1, transform: 'none', filter: 'blur(0px)' }], { duration: 300 });
				};
				$('[data-vm-file]', box).textContent = t.dataset.file;
			});
		});
		copy.addEventListener('click', function () {
			var code = $('code', panels[cur]).textContent;
			if (navigator.clipboard) navigator.clipboard.writeText(code);
			copy.innerHTML = '<span class="vm:flex vm:items-center vm:gap-1">' + check + ' Copied</span>';
			setTimeout(function () { copy.innerHTML = '<span class="vm:flex vm:items-center vm:gap-1">Copy</span>'; }, 1400);
		});
	});
})();
