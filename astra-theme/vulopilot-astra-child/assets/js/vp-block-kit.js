/*!
 * VuloPilot block kit – small editor controls shared by the editable blocks
 * (How it works stepper, Product Tour). Plain wp.element, no build step.
 * Exposes window.VPKit.
 */
(function (wp) {
	'use strict';
	var el = wp.element.createElement, Fragment = wp.element.Fragment;
	var __ = wp.i18n.__;
	var C = wp.components, BE = wp.blockEditor;
	var DATA = window.VP_EDITOR || { registry: {}, icons: [], screens: [] };

	/** Text area where each line is one item. Stored as a string with line breaks. */
	function Lines(p) {
		return el(C.TextareaControl, {
			label: p.label, help: p.help || __('One item per line.', 'vulopilot'), rows: p.rows || 4,
			value: p.value || '', onChange: p.onChange, __nextHasNoMarginBottom: true
		});
	}

	/** Icon picker (names from the icons the theme ships). */
	function Icon(p) {
		var opts = (DATA.icons || []).map(function (n) { return { value: n, label: n }; });
		return el('div', { style: { display: 'flex', gap: 8, alignItems: 'flex-end' } },
			el('div', { style: { flex: 1 } }, el(C.ComboboxControl, { label: p.label || __('Icon', 'vulopilot'), value: p.value, options: opts, onChange: function (v) { p.onChange(v || ''); }, __nextHasNoMarginBottom: true })));
	}

	/** Image from the media library (URL + id + alt). */
	function Image(p) {
		return el(BE.MediaUploadCheck, null, el(BE.MediaUpload, {
			allowedTypes: ['image'], value: p.id,
			onSelect: function (m) { p.onChange({ url: m.url, id: m.id, alt: m.alt || '' }); },
			render: function (o) {
				return el('div', { style: { margin: '8px 0 12px' } },
					el('div', { style: { fontSize: 11, fontWeight: 500, textTransform: 'uppercase', marginBottom: 6 } }, p.label || __('Image', 'vulopilot')),
					p.url ? el('img', { src: p.url, alt: '', style: { display: 'block', maxWidth: '100%', maxHeight: 120, marginBottom: 6, border: '1px solid #ddd' } }) : null,
					el(C.Button, { variant: 'secondary', onClick: o.open }, p.url ? __('Replace image', 'vulopilot') : __('Choose image', 'vulopilot')),
					p.url ? el(C.Button, { variant: 'link', isDestructive: true, style: { marginLeft: 8 }, onClick: function () { p.onChange({ url: '', id: 0, alt: '' }); } }, __('Remove', 'vulopilot')) : null,
					p.help ? el('p', { style: { fontSize: 12, color: '#757575', marginTop: 6 } }, p.help) : null);
			}
		}));
	}

	/** Pick any registered graphic (or none). */
	function Graphic(p) {
		var groups = {};
		Object.keys(DATA.registry || {}).forEach(function (k) {
			var e = DATA.registry[k];
			if (!e.ssr || e.alias || e.group === 'Template' || e.group === 'Screenshot' || e.group === 'Home section') return;
			(groups[e.group] = groups[e.group] || []).push({ value: k, label: e.label });
		});
		return el(C.SelectControl, { label: p.label || __('Graphic', 'vulopilot'), value: p.value || '', onChange: p.onChange, help: p.help, __nextHasNoMarginBottom: true },
			el('option', { value: '' }, __('— None —', 'vulopilot')),
			Object.keys(groups).map(function (g) {
				return el('optgroup', { key: g, label: g }, groups[g].map(function (o) { return el('option', { key: o.value, value: o.value }, o.label); }));
			}));
	}

	/** Product screenshot from Customize → VuloPilot screenshots (or none). */
	function Screen(p) {
		var opts = [{ value: '', label: __('— None —', 'vulopilot') }].concat((DATA.screens || []).map(function (s) { return { value: s.key, label: s.title }; }));
		return el(C.SelectControl, { label: p.label || __('Built-in screenshot', 'vulopilot'), value: p.value || '', options: opts, onChange: p.onChange, help: p.help, __nextHasNoMarginBottom: true });
	}

	/**
	 * Repeater: a list of items, each in its own collapsible panel with move / duplicate / remove.
	 * p.items, p.onChange(items), p.make() -> new item, p.title(item, i), p.render(item, set(patch), i)
	 */
	function Repeater(p) {
		var items = p.items || [];
		function upd(list) { p.onChange(list); }
		function set(i) { return function (patch) { var list = items.slice(); list[i] = Object.assign({}, list[i], patch); upd(list); }; }
		function move(i, d) { var list = items.slice(), j = i + d; if (j < 0 || j >= list.length) return; var t = list[i]; list[i] = list[j]; list[j] = t; upd(list); }
		return el(Fragment, null,
			items.map(function (it, i) {
				return el(C.PanelBody, { key: i, title: (i + 1) + '. ' + (p.title(it, i) || __('(untitled)', 'vulopilot')), initialOpen: false },
					p.render(it, set(i), i),
					el('div', { style: { display: 'flex', gap: 4, flexWrap: 'wrap', marginTop: 12 } },
						el(C.Button, { size: 'small', variant: 'secondary', disabled: i === 0, onClick: function () { move(i, -1); } }, '↑'),
						el(C.Button, { size: 'small', variant: 'secondary', disabled: i === items.length - 1, onClick: function () { move(i, 1); } }, '↓'),
						el(C.Button, { size: 'small', variant: 'secondary', onClick: function () { var list = items.slice(); list.splice(i + 1, 0, JSON.parse(JSON.stringify(it))); upd(list); } }, __('Duplicate', 'vulopilot')),
						el(C.Button, { size: 'small', variant: 'secondary', isDestructive: true, disabled: items.length <= (p.min || 1), onClick: function () { var list = items.slice(); list.splice(i, 1); upd(list); } }, __('Remove', 'vulopilot'))));
			}),
			el('div', { style: { padding: '12px 16px' } },
				el(C.Button, { variant: 'primary', disabled: p.max && items.length >= p.max, onClick: function () { upd(items.concat([p.make()])); } }, p.addLabel || __('Add item', 'vulopilot'))));
	}

	var COLORS = [
		['colorPrimary', __('Primary (blue)', 'vulopilot')],
		['colorPrimarySoft', __('Primary soft', 'vulopilot')],
		['colorAccent', __('Accent', 'vulopilot')],
		['colorText', __('Text', 'vulopilot')],
		['colorMuted', __('Muted text', 'vulopilot')],
		['colorSurface', __('Card background', 'vulopilot')],
		['colorBg', __('Soft background', 'vulopilot')],
		['colorBorder', __('Borders', 'vulopilot')]
	];

	/** Style tab: colours + corner radius, same as the other VuloPilot blocks. */
	function Style(p) {
		var a = p.attributes, set = p.setAttributes;
		return el(Fragment, null,
			el(BE.PanelColorSettings, { title: __('Colours', 'vulopilot'), initialOpen: false, colorSettings: COLORS.map(function (c) { return { label: c[1], value: a[c[0]] || undefined, onChange: function (v) { var o = {}; o[c[0]] = v || ''; set(o); } }; }) }),
			el(C.PanelBody, { title: __('Shape', 'vulopilot'), initialOpen: false },
				el(C.RangeControl, { label: __('Corner radius (px)', 'vulopilot'), value: a.radius, min: 0, max: 32, allowReset: true, resetFallbackValue: 4, onChange: function (v) { set({ radius: v == null ? 4 : v }); } })));
	}

	/** Server-rendered preview; clicks go to the sidebar, not the preview. */
	function Preview(p) {
		return el('div', { style: { pointerEvents: 'none' } }, el(wp.serverSideRender, { block: p.name, attributes: p.attributes }));
	}

	/** Ring colour presets. */
	var RING_COLORS = [
		{ name: 'Blue', color: '#4c6fd1' }, { name: 'Coral', color: '#ff5d50' }, { name: 'Navy', color: '#002991' },
		{ name: 'Red', color: '#d8362b' }, { name: 'Green', color: '#1f9d55' }, { name: 'Ink', color: '#0a1230' }
	];

	window.VPKit = { el: el, Fragment: Fragment, __: __, Lines: Lines, Icon: Icon, Image: Image, Graphic: Graphic, Screen: Screen, Repeater: Repeater, Style: Style, Preview: Preview, RING_COLORS: RING_COLORS, DATA: DATA };
})(window.wp);
