/**
 * Block editor script for vulopilot/graphic (built file: only used inside the editor).
 * The front-end output is in render.php next to this file.
 */
(() => {
	var v = (e, o) => () => {
		try {
			return o || e((o = { exports: {} }).exports, o), o.exports;
		} catch (l) {
			throw ((o = 0), l);
		}
	};
	var d = v((M, b) => {
		b.exports = {
			$schema: 'https://schemas.wp.org/trunk/block.json',
			apiVersion: 3,
			category: 'vulopilot',
			textdomain: 'vulopilot',
			editorScript: 'file:./index.js',
			name: 'vulopilot/graphic',
			title: 'Animated Graphic (VuloPilot)',
			icon: 'chart-area',
			description: 'Any animated graphic or section from the VuloPilot site (same as the [vp_graphic] shortcode).',
			keywords: ['animation', 'demo', 'graphic', 'shortcode', 'screenshot'],
			render: 'file:./render.php',
			supports: { html: !1, align: ['wide', 'full'], anchor: !0, spacing: { margin: !0 } },
			attributes: {
				name: { type: 'string', default: 'geo-demo' },
				maxWidth: { type: 'number', default: 0 },
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
	var { __: t } = wp.i18n,
		{ PanelColorSettings: h } = wp.blockEditor,
		{
			PanelBody: i,
			RangeControl: p,
			Button: P,
			ComboboxControl: B,
			TextControl: T,
			TextareaControl: I,
			ToggleControl: A,
			Flex: _,
			FlexItem: R,
		} = wp.components,
		c = window.VP_EDITOR || { registry: {}, icons: [], checklists: [] },
		y = [
			['colorPrimary', t('Primary (blue)', 'vulopilot'), '#002991'],
			['colorPrimarySoft', t('Primary soft', 'vulopilot'), '#dfe7fb'],
			['colorAccent', t('Accent (Paid / highlight)', 'vulopilot'), '#ff5d50'],
			['colorText', t('Text', 'vulopilot'), '#0a1230'],
			['colorMuted', t('Muted text', 'vulopilot'), '#5d6784'],
			['colorSurface', t('Card background', 'vulopilot'), '#ffffff'],
			['colorBg', t('Soft background', 'vulopilot'), '#f5f7fc'],
			['colorBorder', t('Borders', 'vulopilot'), '#e1e6f2'],
		];
	function u({ attributes: e, setAttributes: o, children: l }) {
		return wp.element.createElement(
			wp.element.Fragment,
			null,
			wp.element.createElement(h, {
				title: t('Colours', 'vulopilot'),
				initialOpen: !1,
				colorSettings: y.map(([r, n]) => ({ label: n, value: e[r] || void 0, onChange: (f) => o({ [r]: f || '' }) })),
			}),
			wp.element.createElement(
				i,
				{ title: t('Shape & size', 'vulopilot'), initialOpen: !1 },
				wp.element.createElement(p, {
					label: t('Corner radius (px)', 'vulopilot'),
					value: e.radius,
					min: 0,
					max: 32,
					onChange: (r) => o({ radius: r != null ? r : 4 }),
					allowReset: !0,
					resetFallbackValue: 4,
				}),
				l,
			),
		);
	}
	var { __: a } = wp.i18n,
		{ registerBlockType: x } = wp.blocks,
		{ InspectorControls: m, useBlockProps: C } = wp.blockEditor,
		{ SelectControl: w } = wp.components,
		S = wp.serverSideRender,
		g = d(),
		s = {};
	Object.entries(c.registry).forEach(([e, o]) => {
		!o.ssr ||
			o.group === 'Template' ||
			o.group === 'Screenshot' ||
			o.alias ||
			(s[o.group] = s[o.group] || []).push({
				value: e,
				label: o.label + (!o.num && o.page && o.page !== 'all' ? ` (${o.page === '/' ? 'home' : o.page})` : ''),
			});
	});
	x(g.name, {
		edit({ attributes: e, setAttributes: o }) {
			return wp.element.createElement(
				'div',
				{ ...C() },
				wp.element.createElement(
					m,
					null,
					wp.element.createElement(
						i,
						{ title: a('Graphic', 'vulopilot') },
						wp.element.createElement(
							w,
							{
								label: a('Graphic', 'vulopilot'),
								value: e.name,
								onChange: (l) => o({ name: l }),
								__nextHasNoMarginBottom: !0,
							},
							Object.entries(s).map(([l, r]) =>
								wp.element.createElement(
									'optgroup',
									{ key: l, label: l },
									r.map((n) => wp.element.createElement('option', { key: n.value, value: n.value }, n.label)),
								),
							),
						),
						wp.element.createElement(
							'p',
							{ style: { fontSize: 12, color: '#757575', marginTop: 8 } },
							a('Shortcode:', 'vulopilot'),
							' ',
							wp.element.createElement('code', null, '[vp_graphic name="', e.name, '"]'),
						),
						wp.element.createElement(p, {
							label: a('Max width (px, 0 = full)', 'vulopilot'),
							value: e.maxWidth,
							min: 0,
							max: 1400,
							step: 8,
							onChange: (l) => o({ maxWidth: l || 0 }),
						}),
					),
				),
				wp.element.createElement(
					m,
					{ group: 'styles' },
					wp.element.createElement(u, { attributes: e, setAttributes: o }),
				),
				wp.element.createElement(
					'div',
					{ style: { pointerEvents: 'none' } },
					wp.element.createElement(S, { block: g.name, attributes: e }),
				),
			);
		},
		save: () => null,
	});
})();
