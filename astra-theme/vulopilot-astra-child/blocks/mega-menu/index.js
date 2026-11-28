/**
 * Block editor script for vulopilot/mega-menu (built file: only used inside the editor).
 * The front-end output is in render.php next to this file.
 */
(() => {
	var f = (t, o) => () => {
		try {
			return o || t((o = { exports: {} }).exports, o), o.exports;
		} catch (i) {
			throw ((o = 0), i);
		}
	};
	var u = f((_, b) => {
		b.exports = {
			$schema: 'https://schemas.wp.org/trunk/block.json',
			apiVersion: 3,
			category: 'vulopilot',
			textdomain: 'vulopilot',
			editorScript: 'file:./index.js',
			name: 'vulopilot/mega-menu',
			title: 'Mega Menu (VuloPilot)',
			icon: 'menu-alt',
			description:
				'The VuloPilot header with animated mega menus. Uses the menu you pick (or the \u2018VuloPilot header\u2019 location).',
			keywords: ['menu', 'navigation', 'header', 'mega'],
			render: 'file:./render.php',
			supports: { html: !1, align: ['wide', 'full'], multiple: !0 },
			attributes: {
				menu: { type: 'number', default: 0 },
				fixed: { type: 'boolean', default: !1 },
				showCta: { type: 'boolean', default: !0 },
				colorPrimary: { type: 'string', default: '' },
				colorPrimarySoft: { type: 'string', default: '' },
				colorAccent: { type: 'string', default: '' },
				colorBg: { type: 'string', default: '' },
				colorSurface: { type: 'string', default: '' },
				colorText: { type: 'string', default: '' },
				colorMuted: { type: 'string', default: '' },
				colorBorder: { type: 'string', default: '' },
				radius: { type: 'number', default: 4 },
			},
		};
	});
	var { __: l } = wp.i18n,
		{ PanelColorSettings: v } = wp.blockEditor,
		{
			PanelBody: r,
			RangeControl: g,
			Button: B,
			ComboboxControl: M,
			TextControl: I,
			TextareaControl: T,
			ToggleControl: a,
			Flex: A,
			FlexItem: R,
		} = wp.components,
		F = window.VP_EDITOR || { registry: {}, icons: [], checklists: [] },
		h = [
			['colorPrimary', l('Primary (blue)', 'vulopilot'), '#002991'],
			['colorPrimarySoft', l('Primary soft', 'vulopilot'), '#dfe7fb'],
			['colorAccent', l('Accent (Paid / highlight)', 'vulopilot'), '#ff5d50'],
			['colorText', l('Text', 'vulopilot'), '#0a1230'],
			['colorMuted', l('Muted text', 'vulopilot'), '#5d6784'],
			['colorSurface', l('Card background', 'vulopilot'), '#ffffff'],
			['colorBg', l('Soft background', 'vulopilot'), '#f5f7fc'],
			['colorBorder', l('Borders', 'vulopilot'), '#e1e6f2'],
		];
	function s({ attributes: t, setAttributes: o, children: i }) {
		return wp.element.createElement(
			wp.element.Fragment,
			null,
			wp.element.createElement(v, {
				title: l('Colours', 'vulopilot'),
				initialOpen: !1,
				colorSettings: h.map(([e, d]) => ({ label: d, value: t[e] || void 0, onChange: (m) => o({ [e]: m || '' }) })),
			}),
			wp.element.createElement(
				r,
				{ title: l('Shape & size', 'vulopilot'), initialOpen: !1 },
				wp.element.createElement(g, {
					label: l('Corner radius (px)', 'vulopilot'),
					value: t.radius,
					min: 0,
					max: 32,
					onChange: (e) => o({ radius: e != null ? e : 4 }),
					allowReset: !0,
					resetFallbackValue: 4,
				}),
				i,
			),
		);
	}
	var { __: n } = wp.i18n,
		{ registerBlockType: y } = wp.blocks,
		{ InspectorControls: c, useBlockProps: x } = wp.blockEditor,
		{ SelectControl: C, Notice: w } = wp.components,
		{ useSelect: k } = wp.data,
		S = wp.serverSideRender,
		p = u();
	y(p.name, {
		edit({ attributes: t, setAttributes: o }) {
			let i = k((e) => (e('core').getMenus ? e('core').getMenus({ per_page: -1 }) : []), []) || [];
			return wp.element.createElement(
				'div',
				{ ...x() },
				wp.element.createElement(
					c,
					null,
					wp.element.createElement(
						r,
						{ title: n('Menu', 'vulopilot') },
						wp.element.createElement(C, {
							label: n('Menu', 'vulopilot'),
							value: t.menu,
							onChange: (e) => o({ menu: parseInt(e, 10) || 0 }),
							options: [
								{ value: 0, label: n('\u201CVuloPilot header\u201D location / built-in links', 'vulopilot') },
								...i.map((e) => ({ value: e.id, label: e.name })),
							],
						}),
						wp.element.createElement(
							'p',
							{ style: { fontSize: 12, color: '#757575' } },
							n(
								'Edit the links in Appearance \u2192 Menus. Level 1 = top items, level 2 = categories, level 3 = links. CSS class vp-promo makes the promo card, vp-foot the bottom link, vp-icon-NAME sets an icon.',
								'vulopilot',
							),
						),
						wp.element.createElement(a, {
							label: n('Stick to the top of the screen', 'vulopilot'),
							checked: t.fixed,
							onChange: (e) => o({ fixed: e }),
						}),
						wp.element.createElement(a, {
							label: n('Show the button on the right', 'vulopilot'),
							checked: t.showCta,
							onChange: (e) => o({ showCta: e }),
						}),
					),
				),
				wp.element.createElement(
					c,
					{ group: 'styles' },
					wp.element.createElement(s, { attributes: t, setAttributes: o }),
				),
				t.fixed &&
					wp.element.createElement(
						w,
						{ status: 'info', isDismissible: !1 },
						n('Sticky header: shown in place here, fixed on the live site.', 'vulopilot'),
					),
				wp.element.createElement(
					'div',
					{ style: { padding: '8px 0 340px' } },
					wp.element.createElement(S, { block: p.name, attributes: { ...t, fixed: !1 } }),
				),
			);
		},
		save: () => null,
	});
})();
