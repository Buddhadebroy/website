/*!
 * VuloPilot site script - plain JavaScript, no jQuery, no dependencies.
 *
 * CONTENTS (search for the number, e.g. "7. Image slider")
 *   0. Shared helpers      $, $$, reduce-motion flag
 *   1. Header              mega menu, hover intent, mobile menu, scroll progress line
 *   2. Consultation popup  open/close, exit intent, form submit
 *   3. Scroll reveal       classes vp-reveal, vp-reveal-left/right/zoom/fade/down, vp-stagger
 *   4. Image zoom + frame  classes vp-zoom, vp-frame
 *   5. Count-up numbers    class vp-count
 *   6. Kadence TOC         highlights the current heading in .kb-table-of-content-nav
 *   7. Image slider        .image-slider-wrapper > .image-slider (styles in style.css, section 18)
 *   8. Boot                runs everything; window.VP_REFRESH(scope) re-runs it for AJAX content
 */
(function () {
	'use strict';

	/* ------------------------------------------------------------------ *
	 * 0. Shared helpers
	 * ------------------------------------------------------------------ */
	var doc = document,
		root = doc.documentElement;
	var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var $ = function (s, c) {
		return (c || doc).querySelector(s);
	};
	var $$ = function (s, c) {
		return Array.prototype.slice.call((c || doc).querySelectorAll(s));
	};

	window.VP_REVEAL_READY = true; // tells the <head> fallback that animations are handled

	/* ------------------------------------------------------------------ *
	 * 1. Header
	 * ------------------------------------------------------------------ */
	function initHeader(header) {
		if (header.dataset.vpReady) return;
		header.dataset.vpReady = '1';
		var links = $('.vp-nav-links', header);
		var pill = $('.vp-nav-pill', header);
		var pops = $('.vp-pops', header);
		var current = null;

		function placePill(btn) {
			if (!pill || !links) return;
			if (!btn) {
				pill.classList.remove('is-on');
				return;
			}
			var first = !pill.classList.contains('is-on');
			if (first) pill.style.transition = 'none';
			pill.style.transform = 'translate(' + btn.offsetLeft + 'px,' + btn.offsetTop + 'px)';
			pill.style.width = btn.offsetWidth + 'px';
			pill.style.height = btn.offsetHeight + 'px';
			if (first) {
				void pill.offsetWidth;
				pill.style.transition = '';
			}
			pill.classList.add('is-on');
		}

		// Hover intent: a short wait before opening and a longer one before closing, so the
		// menu does not flicker when the pointer crosses the bar or the gap below it.
		var openTimer = 0,
			closeTimer = 0;
		var OPEN_DELAY = 70,
			CLOSE_DELAY = 260;

		function later(id) {
			clearTimeout(closeTimer);
			clearTimeout(openTimer);
			if (id === null) {
				closeTimer = setTimeout(function () {
					open(null);
				}, CLOSE_DELAY);
				return;
			}
			// Already open: switch at once (smooth cross-fade). Closed: wait a moment.
			if (current) open(id);
			else
				openTimer = setTimeout(function () {
					open(id);
				}, OPEN_DELAY);
		}

		function stay() {
			clearTimeout(closeTimer);
		}

		function open(id) {
			clearTimeout(openTimer);
			clearTimeout(closeTimer);
			if (current === id) return;
			var switching = !!(current && id);
			if (pops) pops.classList.toggle('is-switching', switching);
			$$('[data-vp-open]', header).forEach(function (b) {
				var on = b.getAttribute('data-vp-open') === id;
				b.classList.toggle('is-open', on);
				b.setAttribute('aria-expanded', on ? 'true' : 'false');
				if (on) placePill(b);
			});
			$$('.vp-pop', header).forEach(function (p) {
				var on = p.getAttribute('data-vp-pop') === id;
				if (on && !p.classList.contains('is-open')) {
					if (!switching) {
						p.classList.add('is-entering');
						void p.offsetWidth;
						p.classList.remove('is-entering');
						// Rows slide in only when the menu first opens, not on every switch.
						p.classList.add('is-fresh');
						clearTimeout(p._vpFresh);
						p._vpFresh = setTimeout(function () {
							p.classList.remove('is-fresh');
						}, 450);
					}
					p.classList.add('is-open');
					var active = $('.vp-rail-btn.is-on', p);
					if (active) railTo(p, active, true);
				} else if (!on) {
					p.classList.remove('is-open');
					clearRows(p);
				}
			});
			if (pops) pops.classList.toggle('has-open', !!id);
			if (!id) placePill(null);
			current = id;
		}
		header._vpClose = function () {
			open(null);
		};
		$$('[data-vp-open]', header).forEach(function (b) {
			var id = b.getAttribute('data-vp-open');
			b.addEventListener('mouseenter', function () {
				later(id);
			});
			b.addEventListener('mouseleave', function () {
				clearTimeout(openTimer);
			});
			b.addEventListener('click', function () {
				open(current === id ? null : id);
			});
		});
		$$('.vp-nav-link:not([data-vp-open])', header).forEach(function (a) {
			a.addEventListener('mouseenter', function () {
				later(null);
			});
		});
		header.addEventListener('mouseenter', stay);
		header.addEventListener('mouseleave', function () {
			later(null);
		});
		if (pops) pops.addEventListener('mouseenter', stay);
		doc.addEventListener('keydown', function (e) {
			if (e.key === 'Escape') open(null);
		});
		doc.addEventListener('click', function (e) {
			if (current && !header.contains(e.target)) open(null);
		});

		// Rail (left column) + category panels.
		function railTo(pop, btn, instant) {
			var railPill = $('.vp-rail-pill', pop);
			$$('.vp-rail-btn', pop).forEach(function (b) {
				b.classList.toggle('is-on', b === btn);
			});
			if (railPill) {
				if (instant || !railPill.classList.contains('is-on')) {
					railPill.style.transition = 'none';
				}
				railPill.style.transform = 'translateY(' + btn.parentNode.offsetTop + 'px)';
				railPill.style.height = btn.offsetHeight + 'px';
				void railPill.offsetWidth;
				railPill.style.transition = '';
				railPill.classList.add('is-on');
			}
			var id = btn.getAttribute('data-vp-cat');
			$$('.vp-cat', pop).forEach(function (c) {
				var on = c.getAttribute('data-vp-panel') === id;
				if (on !== c.classList.contains('is-active')) {
					c.classList.toggle('is-active', on);
				}
			});
			clearRows(pop);
		}
		$$('.vp-pop', header).forEach(function (pop) {
			$$('.vp-rail-btn', pop).forEach(function (btn) {
				var go = function () {
					clearTimeout(pop._vpRail);
					if (!btn.classList.contains('is-on')) railTo(pop, btn);
				};
				// Small hover delay, so moving the pointer diagonally to a link does not flip categories.
				var soon = function () {
					clearTimeout(pop._vpRail);
					pop._vpRail = setTimeout(go, 90);
				};
				btn.addEventListener('mouseenter', soon);
				btn.addEventListener('mouseleave', function () {
					clearTimeout(pop._vpRail);
				});
				btn.addEventListener('focus', go);
				btn.addEventListener('click', go);
			});

			// Moving highlight behind the hovered link.
			$$('.vp-rows', pop).forEach(function (ul) {
				var hl = $('.vp-row-hl', ul);
				$$('.vp-row', ul).forEach(function (a) {
					var on = function () {
						$$('.vp-row.is-on', pop).forEach(function (x) {
							if (x !== a) x.classList.remove('is-on');
						});
						a.classList.add('is-on');
						var li = a.parentNode;
						var first = !hl.classList.contains('is-on');
						if (first) hl.style.transition = 'none';
						hl.style.transform = 'translate(' + li.offsetLeft + 'px,' + li.offsetTop + 'px)';
						hl.style.width = li.offsetWidth + 'px';
						hl.style.height = li.offsetHeight + 'px';
						if (first) {
							void hl.offsetWidth;
							hl.style.transition = '';
						}
						hl.classList.add('is-on');
					};
					a.addEventListener('mouseenter', on);
					a.addEventListener('focus', on);
				});
			});
			pop.addEventListener('mouseleave', function () {
				clearRows(pop);
			});
			$$('.vp-cats a', pop).forEach(function (a) {
				a.addEventListener('click', function () {
					open(null);
				});
			});
		});

		function clearRows(pop) {
			$$('.vp-row.is-on', pop).forEach(function (x) {
				x.classList.remove('is-on');
			});
			$$('.vp-row-hl', pop).forEach(function (x) {
				x.classList.remove('is-on');
			});
		}

		// Mobile.
		var burger = $('[data-vp-burger]', header);
		var mobile = $('[data-vp-mobile]', header);
		if (burger && mobile) {
			burger.addEventListener('click', function () {
				var on = !mobile.classList.contains('is-open');
				mobile.classList.toggle('is-open', on);
				burger.setAttribute('aria-expanded', on ? 'true' : 'false');
			});
			$$('a', mobile).forEach(function (a) {
				a.addEventListener('click', function () {
					mobile.classList.remove('is-open');
					burger.setAttribute('aria-expanded', 'false');
				});
			});
		}

		// Scroll progress line.
		var bar = $('.vp-progress', header);
		if (bar) {
			var cur = 0,
				target = 0,
				raf = 0;

			var measure = function () {
				var max = Math.max(1, root.scrollHeight - window.innerHeight);
				target = Math.min(1, Math.max(0, window.pageYOffset / max));
				if (!raf) raf = requestAnimationFrame(step);
			};

			var step = function () {
				cur += (target - cur) * (reduce ? 1 : 0.14);
				if (Math.abs(target - cur) < 0.0005) cur = target;
				bar.style.transform = 'scaleX(' + cur.toFixed(4) + ')';
				raf = cur === target ? 0 : requestAnimationFrame(step);
			};

			window.addEventListener('scroll', measure, { passive: true });
			window.addEventListener('resize', measure);
			measure();
		}
	}

	/* ------------------------------------------------------------------ *
	 * 2. Consultation popup
	 * ------------------------------------------------------------------ */
	function initConsult() {
		var box = $('[data-vp-consult]');
		if (!box) return;
		var cfg = window.VP_SITE || {};
		var KEY = 'vp-consult-dismissed';
		var store = {
			get: function () {
				try {
					return Number(localStorage.getItem(KEY) || 0);
				} catch (e) {
					return 0;
				}
			},
			set: function () {
				try {
					localStorage.setItem(KEY, String(Date.now()));
				} catch (e) {}
			},
		};
		var form = $('.vp-consult-form', box);
		var err = $('.vp-consult-err', box);
		var label = $('.vp-consult-label', box);
		var lastFocus = null;

		function open(fresh) {
			if (fresh) {
				box.classList.remove('is-sent');
				if (err) err.classList.remove('is-on');
			}
			lastFocus = doc.activeElement;
			box.classList.add('is-open');
			box.setAttribute('aria-hidden', 'false');
			root.classList.add('vp-lock');
			setTimeout(function () {
				var f = $('input[name="name"]', box);
				if (f && !box.classList.contains('is-sent')) f.focus();
			}, 250);
		}

		function close() {
			box.classList.remove('is-open');
			box.setAttribute('aria-hidden', 'true');
			root.classList.remove('vp-lock');
			store.set();
			if (lastFocus && lastFocus.focus) lastFocus.focus();
		}

		window.VP_OPEN_CONSULT = function () {
			open(true);
		};

		window.addEventListener('vp:consult', function () {
			open(true);
		});
		doc.addEventListener('click', function (e) {
			var t = e.target.closest ? e.target.closest('.vp-open-consult, a[href="#consult"], a[href$="#consult"]') : null;
			if (t) {
				e.preventDefault();
				open(true);
			}
		});
		$$('[data-vp-close]', box).forEach(function (b) {
			b.addEventListener('click', close);
		});
		doc.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && box.classList.contains('is-open')) close();
		});

		// Exit intent (desktop) and a late offer on touch screens.
		if (cfg.exit) {
			var recent = Date.now() - store.get() < 7 * 24 * 3600 * 1000;
			var armed = false;
			setTimeout(function () {
				armed = true;
			}, 6000);
			doc.addEventListener('mouseleave', function (e) {
				if (armed && !recent && e.clientY <= 0) {
					armed = false;
					open(true);
				}
			});
			if (!recent && window.matchMedia('(pointer: coarse)').matches) {
				setTimeout(function () {
					if (!box.classList.contains('is-open')) open(true);
				}, 45000);
			}
		}
		if (!form || !err || !label) return;
		form.addEventListener('submit', function (e) {
			e.preventDefault();
			if (!form.reportValidity()) return;
			var btn = $('button[type="submit"]', form);
			var text = label.textContent;
			btn.disabled = true;
			label.textContent = 'Sending…';
			err.classList.remove('is-on');
			var fd = new FormData(form);
			var req;
			if (cfg.endpoint) {
				var data = {};
				fd.forEach(function (v, k) {
					data[k] = v;
				});
				data.source = 'exit-popup';
				req = fetch(cfg.endpoint, {
					method: 'POST',
					headers: { 'Content-Type': 'application/json' },
					body: JSON.stringify(data),
				}).then(function (r) {
					if (!r.ok) throw new Error('failed');
				});
			} else {
				fd.append('action', 'vp_consult');
				fd.append('nonce', cfg.nonce);
				req = fetch(cfg.ajax, {
					method: 'POST',
					body: fd,
					credentials: 'same-origin',
				})
					.then(function (r) {
						return r.json();
					})
					.then(function (j) {
						if (!j || !j.success) {
							throw new Error((j && j.data && j.data.message) || 'failed');
						}
					});
			}
			req
				.then(function () {
					box.classList.add('is-sent');
					store.set();
					form.reset();
				})
				.catch(function (x) {
					err.textContent =
						x && x.message && x.message !== 'failed'
							? x.message
							: 'Something went wrong. Please try again, or email us directly.';
					err.classList.add('is-on');
				})
				.then(function () {
					btn.disabled = false;
					label.textContent = text;
				});
		});
	}

	/* ------------------------------------------------------------------ *
	 * 3. Scroll reveal
	 * ------------------------------------------------------------------ */
	var REVEAL = '.vp-reveal, .vp-reveal-left, .vp-reveal-right, .vp-reveal-zoom, .vp-reveal-fade, .vp-reveal-down';

	function staggerItems(el) {
		var marked = $$('.vp-reveal-item', el);
		if (marked.length) return marked;
		var c = el,
			guard = 0;
		while (c.children.length === 1 && guard++ < 6) {
			c = c.children[0];
		}
		return Array.prototype.slice.call(c.children).filter(function (x) {
			return (
				!/^(STYLE|SCRIPT|LINK)$/.test(x.tagName) &&
				!x.classList.contains('kt-row-layout-overlay') &&
				!x.classList.contains('kb-blocks-bg-video-container')
			);
		});
	}

	function show(el) {
		el.classList.add('vp-in');
		var d = parseFloat(getComputedStyle(el).transitionDelay) || 0;
		var dur = parseFloat(getComputedStyle(el).transitionDuration) || 0.8;
		if (el.classList.contains('vp-reveal-repeat')) return;
		setTimeout(
			function () {
				el.classList.add('vp-done');
			},
			(d + dur) * 1000 + 50,
		);
	}

	function hide(el) {
		el.classList.remove('vp-in', 'vp-done');
	}

	function initReveal(scope) {
		var step = 0.1;
		$$('.vp-stagger', scope).forEach(function (wrap) {
			if (wrap.dataset.vpStagger) return;
			wrap.dataset.vpStagger = '1';
			var s = wrap.classList.contains('vp-stagger-fast')
				? 0.06
				: wrap.classList.contains('vp-stagger-slow')
					? 0.18
					: step;
			staggerItems(wrap).forEach(function (item, i) {
				if (!item.matches(REVEAL)) item.classList.add('vp-reveal-item');
				if (!item.style.getPropertyValue('--vp-delay')) {
					item.style.setProperty('--vp-delay', (i * s).toFixed(2) + 's');
				}
				if (wrap.classList.contains('vp-reveal-repeat')) {
					item.classList.add('vp-reveal-repeat');
				}
			});
		});
		var targets = $$(REVEAL + ', .vp-stagger', scope).filter(function (el) {
			return !el.dataset.vpWatch;
		});
		if (!('IntersectionObserver' in window) || reduce) {
			targets.forEach(function (el) {
				el.classList.add('vp-in', 'vp-done');
				$$('.vp-reveal-item', el).forEach(function (i) {
					i.classList.add('vp-in', 'vp-done');
				});
			});
			return;
		}
		var io = new IntersectionObserver(
			function (entries) {
				entries.forEach(function (en) {
					var el = en.target;
					var items = el.classList.contains('vp-stagger') ? $$('.vp-reveal-item', el) : [];
					if (en.isIntersecting) {
						if (el.matches(REVEAL)) show(el);
						el.classList.add('vp-in');
						items.forEach(show);
						if (!el.classList.contains('vp-reveal-repeat')) {
							io.unobserve(el);
						}
					} else if (el.classList.contains('vp-reveal-repeat') && en.boundingClientRect.top > 0) {
						hide(el);
						items.forEach(hide);
					}
				});
			},
			{
				rootMargin: '0px 0px -40px 0px',
				threshold: 0,
			},
		);
		targets.forEach(function (el) {
			el.dataset.vpWatch = '1';
			io.observe(el);
		});
	}

	/* ------------------------------------------------------------------ *
	 * 4. Image zoom + frame
	 * ------------------------------------------------------------------ */
	var ICON_MAX =
		'<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 3h6v6"/><path d="m21 3-7 7"/><path d="m3 21 7-7"/><path d="M9 21H3v-6"/></svg>';
	var ICON_X =
		'<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>';

	function imagesOf(el) {
		return el.tagName === 'IMG' ? [el] : $$('img', el);
	}

	function fullSrc(img) {
		var a = img.closest('a');
		if (a && /\.(jpe?g|png|gif|webp|avif|svg)(\?.*)?$/i.test(a.getAttribute('href') || '')) {
			return a.href;
		}
		if (img.dataset.fullUrl) return img.dataset.fullUrl;
		var best = img.currentSrc || img.src;
		var w = 0;
		(img.getAttribute('srcset') || '').split(',').forEach(function (p) {
			var m = p.trim().split(/\s+/);
			var n = parseInt(m[1], 10) || 0;
			if (n > w) {
				w = n;
				best = m[0];
			}
		});
		return best;
	}

	function lightbox(img) {
		var lb = doc.createElement('div');
		lb.className = 'vp-lightbox';
		lb.setAttribute('role', 'dialog');
		lb.setAttribute('aria-modal', 'true');
		lb.setAttribute('aria-label', img.alt || 'Image');
		lb.innerHTML = '<button type="button" class="vp-lightbox-close" aria-label="Close">' + ICON_X + '</button>';
		var big = doc.createElement('img');
		big.src = fullSrc(img);
		big.alt = img.alt || '';
		if (img.naturalWidth) {
			big.width = img.naturalWidth;
			big.height = img.naturalHeight;
		}
		lb.appendChild(big);
		var fig = img.closest('figure');
		var cap = fig && $('figcaption', fig);
		if (cap) {
			var c = doc.createElement('p');
			c.className = 'vp-lightbox-cap';
			c.textContent = cap.textContent;
			lb.appendChild(c);
		}
		var prev = doc.activeElement;

		var close = function () {
			lb.classList.remove('is-open');
			root.classList.remove('vp-lock');
			doc.removeEventListener('keydown', key);
			setTimeout(function () {
				lb.remove();
			}, 200);
			if (prev && prev.focus) prev.focus();
		};

		var key = function (e) {
			if (e.key === 'Escape') close();
		};
		lb.addEventListener('click', close);
		big.addEventListener('click', function (e) {
			e.stopPropagation();
		});
		doc.addEventListener('keydown', key);
		doc.body.appendChild(lb);
		root.classList.add('vp-lock');
		requestAnimationFrame(function () {
			lb.classList.add('is-open');
			$('.vp-lightbox-close', lb).focus();
		});
	}

	function frame(img, host) {
		if (img.closest('.vp-frame-box')) return;
		var node = img.parentNode.tagName === 'A' ? img.parentNode : img;
		var box = doc.createElement('div');
		box.className = 'vp-frame-box';
		var url = host.getAttribute('data-url') || img.getAttribute('title') || 'wp-admin / VuloPilot';
		box.innerHTML = '<div class="vp-frame-bar" aria-hidden="true"><i></i><i></i><i></i><span></span></div>';
		$('span', box).textContent = url;
		node.parentNode.insertBefore(box, node);
		box.appendChild(node);
	}

	function zoomable(img) {
		if (img.dataset.vpZoom) return;
		img.dataset.vpZoom = '1';
		var host = img.parentNode.tagName === 'A' ? img.parentNode : img;
		var wrap = doc.createElement('span');
		wrap.className = 'vp-zoom-target';
		wrap.setAttribute('role', 'button');
		wrap.setAttribute('tabindex', '0');
		wrap.setAttribute('aria-label', 'Enlarge image' + (img.alt ? ': ' + img.alt : ''));
		host.parentNode.insertBefore(wrap, host);
		wrap.appendChild(host);
		var btn = doc.createElement('span');
		btn.className = 'vp-zoom-btn';
		btn.innerHTML = ICON_MAX;
		wrap.appendChild(btn);

		var go = function (e) {
			e.preventDefault();
			lightbox(img);
		};
		wrap.addEventListener('click', go);
		wrap.addEventListener('keydown', function (e) {
			if (e.key === 'Enter' || e.key === ' ') go(e);
		});
	}

	function initImages(scope) {
		$$('.vp-frame', scope).forEach(function (el) {
			imagesOf(el).forEach(function (img) {
				frame(img, el);
			});
		});
		$$('.vp-zoom', scope).forEach(function (el) {
			imagesOf(el).forEach(zoomable);
		});
	}

	/* ------------------------------------------------------------------ *
	 * 5. Count-up numbers
	 * ------------------------------------------------------------------ */
	function initCount(scope) {
		var els = $$('.vp-count', scope).filter(function (el) {
			return !el.dataset.vpCount;
		});
		if (!els.length) return;

		var run = function (el) {
			var walker = doc.createTreeWalker(el, NodeFilter.SHOW_TEXT);
			var node;
			while ((node = walker.nextNode())) {
				if (/\d/.test(node.nodeValue)) break;
			}
			if (!node) return;
			var m = node.nodeValue.match(/(\d[\d,]*\.?\d*)/);
			var raw = m[1];
			var to = parseFloat(raw.replace(/,/g, ''));
			var dec = (raw.split('.')[1] || '').length;
			var comma = raw.indexOf(',') > -1;
			var before = node.nodeValue.slice(0, m.index);
			var after = node.nodeValue.slice(m.index + raw.length);
			var dur = (parseFloat(el.getAttribute('data-duration')) || 1.6) * 1000;
			var t0 = null;

			var fmt = function (v) {
				var s = v.toFixed(dec);
				return comma
					? Number(s).toLocaleString('en-US', {
							minimumFractionDigits: dec,
							maximumFractionDigits: dec,
						})
					: s;
			};

			var tick = function (t) {
				if (!t0) t0 = t;
				var p = Math.min(1, (t - t0) / dur);
				var e = 1 - Math.pow(1 - p, 3);
				node.nodeValue = before + fmt(to * e) + after;
				if (p < 1) requestAnimationFrame(tick);
			};
			node.nodeValue = before + fmt(0) + after;
			requestAnimationFrame(tick);
		};
		if (reduce || !('IntersectionObserver' in window)) return;
		var io = new IntersectionObserver(
			function (en) {
				en.forEach(function (x) {
					if (x.isIntersecting) {
						io.unobserve(x.target);
						run(x.target);
					}
				});
			},
			{
				threshold: 0.4,
			},
		);
		els.forEach(function (el) {
			el.dataset.vpCount = '1';
			io.observe(el);
		});
	}

	/* ------------------------------------------------------------------ *
	 * 6. Kadence table of contents auto-scroll
	 *
	 * Uses only:
	 * .kb-table-of-content-nav
	 * .kb-table-of-content-list
	 *
	 * The scrollable element is the existing UL/list.
	 * No jQuery and no additional dependencies.
	 * ------------------------------------------------------------------ */
	function initTableOfContents() {
		var navs = $$('.kb-table-of-content-nav');
		if (!navs.length) return;
		navs.forEach(function (nav) {
			if (nav.dataset.vpTocReady) return;
			var list = $('.kb-table-of-content-list', nav);

			// Support the existing list class or the UL inside the nav.
			if (!list) list = $('ul', nav);
			if (!list) return;
			nav.dataset.vpTocReady = '1';
			var links = $$('a[href*="#"]', list);
			var items = [];
			var activeItem = null;
			var ticking = false;
			var scrollFrame = 0;
			links.forEach(function (link) {
				var href = link.getAttribute('href');
				if (!href) return;
				var hashIndex = href.indexOf('#');
				if (hashIndex === -1 || hashIndex === href.length - 1) return;
				var id;
				try {
					id = decodeURIComponent(href.slice(hashIndex + 1));
				} catch (e) {
					id = href.slice(hashIndex + 1);
				}
				var section = doc.getElementById(id);
				if (!section) return;
				var item = link.closest('li');
				if (!item) return;
				items.push({
					link: link,
					item: item,
					section: section,
				});
			});
			if (!items.length) return;

			// Keep the currently active TOC item visible in the right-side list.
			function keepItemVisible(item) {
				if (!item) return;
				var listRect = list.getBoundingClientRect();
				var itemRect = item.getBoundingClientRect();
				var above = itemRect.top < listRect.top;
				var below = itemRect.bottom > listRect.bottom;
				if (!above && !below) return;
				var targetTop = list.scrollTop + itemRect.top - listRect.top - list.clientHeight / 2 + itemRect.height / 2;
				var maxScroll = Math.max(0, list.scrollHeight - list.clientHeight);
				targetTop = Math.max(0, Math.min(targetTop, maxScroll));
				if (Math.abs(list.scrollTop - targetTop) < 2) return;
				if (scrollFrame) cancelAnimationFrame(scrollFrame);
				if (reduce) {
					list.scrollTop = targetTop;
					return;
				}

				// Animate only when the active item leaves the visible list.
				var start = list.scrollTop;
				var distance = targetTop - start;
				var startTime = null;
				var duration = 180;

				function animate(time) {
					if (startTime === null) startTime = time;
					var progress = Math.min(1, (time - startTime) / duration);
					var eased = 1 - Math.pow(1 - progress, 3);
					list.scrollTop = start + distance * eased;
					if (progress < 1) {
						scrollFrame = requestAnimationFrame(animate);
					} else {
						scrollFrame = 0;
					}
				}
				scrollFrame = requestAnimationFrame(animate);
			}

			function updateActiveItem() {
				ticking = false;
				var threshold = Math.min(180, window.innerHeight * 0.25);
				var current = null;
				items.forEach(function (entry) {
					if (entry.section.getBoundingClientRect().top <= threshold) {
						current = entry;
					}
				});

				// Before the first heading reaches the threshold, select the first item.
				if (!current) {
					var firstSectionTop = items[0].section.getBoundingClientRect().top;
					if (firstSectionTop > threshold) {
						current = items[0];
					}
				}
				if (!current || current.item === activeItem) return;
				activeItem = current.item;
				items.forEach(function (entry) {
					entry.item.classList.toggle('active', entry.item === activeItem);
				});
				keepItemVisible(activeItem);
			}

			function requestUpdate() {
				if (ticking) return;
				ticking = true;
				window.requestAnimationFrame(updateActiveItem);
			}

			// Track article scrolling and viewport changes.
			window.addEventListener('scroll', requestUpdate, { passive: true });
			window.addEventListener('resize', requestUpdate);

			// Update immediately on TOC link clicks.
			links.forEach(function (link) {
				link.addEventListener('click', function () {
					window.setTimeout(requestUpdate, 100);
				});
			});

			// Initialize the current active item.
			requestUpdate();
		});
	}

	/* ------------------------------------------------------------------ *
	 * 7. Image slider
	 * Kadence row "image-slider-wrapper" > columns "image-slider" > image +
	 * caption heading "image-slider-title". Adds arrows, dots, a counter,
	 * keyboard arrows and swipe.
	 * ------------------------------------------------------------------ */
	var ICON_PREV =
		'<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M11 6l-6 6 6 6"/></svg>';
	var ICON_NEXT =
		'<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>';

	function initSlider(wrapper, sliderIndex) {
		if (wrapper.classList.contains('vp-slider-ready')) {
			return;
		}
		var slides = $$('.image-slider', wrapper);
		if (slides.length < 2) {
			return;
		}

		// Remove any old per-slide arrows left in the page (older layout).
		$$('.image-slider-previous, .image-slider-next', wrapper).forEach(function (el) {
			var col = el.closest('.wp-block-kadence-column');
			(col || el).remove();
		});
		var track = slides[0].parentElement;
		var count = slides.length;
		var current = 0;
		var id = 'vp-slider-' + sliderIndex;

		// Viewport clips the track so only one slide shows.
		var viewport = document.createElement('div');
		viewport.className = 'vp-slider-viewport';
		track.parentNode.insertBefore(viewport, track);
		viewport.appendChild(track);
		track.classList.add('vp-slider-track');
		track.id = id + '-track';
		wrapper.setAttribute('role', 'region');
		wrapper.setAttribute('aria-roledescription', 'carousel');
		if (!wrapper.getAttribute('aria-label')) {
			wrapper.setAttribute('aria-label', 'VuloPilot dashboard screenshots');
		}
		var captions = slides.map(function (slide, i) {
			slide.classList.add('vp-slide');
			slide.setAttribute('role', 'group');
			slide.setAttribute('aria-roledescription', 'slide');
			slide.setAttribute('aria-label', i + 1 + ' of ' + count);
			var title = slide.querySelector('.image-slider-title');
			return title ? title.textContent.trim() : '';
		});

		// Controls bar.
		var controls = document.createElement('div');
		controls.className = 'vp-slider-controls';
		var prev = document.createElement('button');
		prev.type = 'button';
		prev.className = 'vp-slider-btn vp-slider-prev';
		prev.setAttribute('aria-label', 'Previous screenshot');
		prev.setAttribute('aria-controls', track.id);
		prev.innerHTML = ICON_PREV;
		var next = document.createElement('button');
		next.type = 'button';
		next.className = 'vp-slider-btn vp-slider-next';
		next.setAttribute('aria-label', 'Next screenshot');
		next.setAttribute('aria-controls', track.id);
		next.innerHTML = ICON_NEXT;
		var meta = document.createElement('div');
		meta.className = 'vp-slider-meta';
		var caption = document.createElement('p');
		caption.className = 'vp-slider-caption';
		caption.setAttribute('aria-live', 'polite');
		var bottom = document.createElement('div');
		bottom.className = 'vp-slider-bottom';
		var dots = document.createElement('div');
		dots.className = 'vp-slider-dots';
		var dotButtons = slides.map(function (slide, i) {
			var dot = document.createElement('button');
			dot.type = 'button';
			dot.className = 'vp-slider-dot';
			dot.setAttribute('aria-label', 'Show screenshot ' + (i + 1) + (captions[i] ? ': ' + captions[i] : ''));
			dot.setAttribute('aria-controls', track.id);
			dot.addEventListener('click', function () {
				goTo(i);
			});
			dots.appendChild(dot);
			return dot;
		});
		var counter = document.createElement('span');
		counter.className = 'vp-slider-counter';
		counter.setAttribute('aria-hidden', 'true');
		bottom.appendChild(dots);
		bottom.appendChild(counter);
		meta.appendChild(caption);
		meta.appendChild(bottom);
		controls.appendChild(prev);
		controls.appendChild(meta);
		controls.appendChild(next);
		viewport.parentNode.insertBefore(controls, viewport.nextSibling);

		function goTo(index) {
			current = (index + count) % count;
			track.style.transform = 'translate3d(' + -current * 100 + '%, 0, 0)';
			slides.forEach(function (slide, i) {
				var active = i === current;
				slide.classList.toggle('is-active', active);
				slide.setAttribute('aria-hidden', active ? 'false' : 'true');
				if ('inert' in slide) {
					slide.inert = !active;
				}
			});
			dotButtons.forEach(function (dot, i) {
				var active = i === current;
				dot.classList.toggle('is-active', active);
				if (active) {
					dot.setAttribute('aria-current', 'true');
				} else {
					dot.removeAttribute('aria-current');
				}
			});
			caption.textContent = captions[current];
			counter.textContent = current + 1 + ' / ' + count;
		}
		prev.addEventListener('click', function () {
			goTo(current - 1);
		});
		next.addEventListener('click', function () {
			goTo(current + 1);
		});

		// Keyboard: left / right arrows while focus is inside the slider.
		wrapper.addEventListener('keydown', function (e) {
			if (e.key === 'ArrowLeft') {
				e.preventDefault();
				goTo(current - 1);
			} else if (e.key === 'ArrowRight') {
				e.preventDefault();
				goTo(current + 1);
			}
		});

		// Swipe on touch screens.
		var startX = null;
		var startY = null;
		viewport.addEventListener(
			'touchstart',
			function (e) {
				startX = e.touches[0].clientX;
				startY = e.touches[0].clientY;
			},
			{ passive: true },
		);
		viewport.addEventListener('touchend', function (e) {
			if (startX === null) {
				return;
			}
			var dx = e.changedTouches[0].clientX - startX;
			var dy = e.changedTouches[0].clientY - startY;
			if (Math.abs(dx) > 40 && Math.abs(dx) > Math.abs(dy)) {
				goTo(current + (dx < 0 ? 1 : -1));
			}
			startX = null;
		});
		wrapper.classList.add('vp-slider-ready');
		goTo(0);
	}

	function initSliders() {
		$$('.image-slider-wrapper').forEach(initSlider);
	}

	/* ------------------------------------------------------------------ *
	 * 8. Boot
	 * ------------------------------------------------------------------ */
	function boot() {
		initSliders(); // first, so the reveal script sees the finished slider markup
		$$('[data-vp-header]').forEach(initHeader);
		initConsult();
		initReveal(doc);
		initImages(doc);
		initCount(doc);
		initTableOfContents();
	}

	// Re-run for content injected later (e.g. AJAX-loaded sections).
	window.VP_REFRESH = function (scope) {
		scope = scope || doc;
		initReveal(scope);
		initImages(scope);
		initCount(scope);
		$$('[data-vp-header]', scope).forEach(initHeader);

		// TOC navigation may be inserted dynamically.
		initTableOfContents();
	};
	if (doc.readyState === 'loading') {
		doc.addEventListener('DOMContentLoaded', boot);
	} else {
		boot();
	}
})();