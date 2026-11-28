/**
 * Block editor script for vulopilot/checklist (built file: only used inside the editor).
 * The front-end output is in render.php next to this file.
 */
(() => {
	var __getOwnPropNames = Object.getOwnPropertyNames;
	var __commonJS = (cb, mod) =>
		function __require() {
			try {
				return mod || (0, cb[__getOwnPropNames(cb)[0]])((mod = { exports: {} }).exports, mod), mod.exports;
			} catch (e) {
				throw ((mod = 0), e);
			}
		};

	// theme2/astra-child/blocks/checklist/block.json
	var require_block = __commonJS({
		'theme2/astra-child/blocks/checklist/block.json'(exports, module) {
			module.exports = {
				$schema: 'https://schemas.wp.org/trunk/block.json',
				apiVersion: 3,
				category: 'vulopilot',
				textdomain: 'vulopilot',
				editorScript: 'file:./index.js',
				name: 'vulopilot/checklist',
				title: 'Interactive Checklist (VuloPilot)',
				icon: 'yes-alt',
				description: "Tick-off checklist with a progress ring. Progress is saved in the visitor's browser.",
				keywords: ['checklist', 'todo', 'progress', 'resources'],
				viewScript: 'file:./view.js',
				render: 'file:./render.php',
				supports: {
					html: false,
					align: ['wide', 'full'],
					anchor: true,
					spacing: {
						margin: true,
						padding: true,
					},
				},
				attributes: {
					heading: {
						type: 'string',
						default: 'Work through it',
					},
					storageId: {
						type: 'string',
						default: 'seo-checklist',
					},
					groups: {
						type: 'array',
						default: [
							{
								title: 'Foundations',
								items:
									'Every page has a unique title\nEvery page has a meta description\nXML sitemap is published and submitted\nrobots.txt does not block important pages\nSite uses HTTPS everywhere',
							},
							{
								title: 'Pages',
								items:
									'One H1 per page\nImages have alt text\nInternal links connect related pages\nNo duplicate titles or descriptions',
							},
							{
								title: 'Fix and monitor',
								items:
									'Moved pages are redirected\n404s are reviewed\nSearch Console is connected\nScores are re-checked after fixes',
							},
						],
					},
					ringLabel: {
						type: 'string',
						default: 'Complete',
					},
					countText: {
						type: 'string',
						default: '{done} of {total} done',
					},
					progressText: {
						type: 'string',
						default: 'Tick items as you go.',
					},
					completeText: {
						type: 'string',
						default: 'All done. Nice work.',
					},
					resetText: {
						type: 'string',
						default: 'Reset',
					},
					downloadText: {
						type: 'string',
						default: 'Or download the checklist',
					},
					ctaText: {
						type: 'string',
						default: 'Get VuloPilot free',
					},
					ctaUrl: {
						type: 'string',
						default: 'https://wordpress.org/plugins/vulopilot/',
					},
					ctaKicker: {
						type: 'string',
						default: 'Skip the manual work',
					},
					ctaTitle: {
						type: 'string',
						default: 'VuloPilot checks this list for you',
					},
					ctaBody: {
						type: 'string',
						default:
							'Install the WordPress plugin and it scans every page, flags what is missing and shows what to fix first.',
					},
					ctaNote: {
						type: 'string',
						default: 'Free plan. Works in WordPress.',
					},
					sticky: {
						type: 'boolean',
						default: true,
					},
					ringSize: {
						type: 'number',
						default: 140,
					},
					maxWidth: {
						type: 'number',
						default: 1024,
					},
					howToSchema: {
						type: 'boolean',
						default: true,
					},
					colorPrimary: {
						type: 'string',
						default: '',
					},
					colorPrimarySoft: {
						type: 'string',
						default: '',
					},
					colorAccent: {
						type: 'string',
						default: '',
					},
					colorBg: {
						type: 'string',
						default: '',
					},
					colorSurface: {
						type: 'string',
						default: '',
					},
					colorText: {
						type: 'string',
						default: '',
					},
					colorMuted: {
						type: 'string',
						default: '',
					},
					colorBorder: {
						type: 'string',
						default: '',
					},
					radius: {
						type: 'number',
						default: 4,
					},
				},
			};
		},
	});

	// blocks-src/_shared.jsx
	var { __ } = wp.i18n;
	var { PanelColorSettings } = wp.blockEditor;
	var {
		PanelBody,
		RangeControl,
		Button,
		ComboboxControl,
		TextControl,
		TextareaControl,
		ToggleControl,
		Flex,
		FlexItem,
	} = wp.components;
	var DATA = window.VP_EDITOR || { registry: {}, icons: [], checklists: [] };
	var COLORS = [
		['colorPrimary', __('Primary (blue)', 'vulopilot'), '#002991'],
		['colorPrimarySoft', __('Primary soft', 'vulopilot'), '#dfe7fb'],
		['colorAccent', __('Accent (Paid / highlight)', 'vulopilot'), '#ff5d50'],
		['colorText', __('Text', 'vulopilot'), '#0a1230'],
		['colorMuted', __('Muted text', 'vulopilot'), '#5d6784'],
		['colorSurface', __('Card background', 'vulopilot'), '#ffffff'],
		['colorBg', __('Soft background', 'vulopilot'), '#f5f7fc'],
		['colorBorder', __('Borders', 'vulopilot'), '#e1e6f2'],
	];
	function StylePanel({ attributes, setAttributes, children }) {
		return /* @__PURE__ */ wp.element.createElement(
			wp.element.Fragment,
			null,
			/* @__PURE__ */ wp.element.createElement(PanelColorSettings, {
				title: __('Colours', 'vulopilot'),
				initialOpen: false,
				colorSettings: COLORS.map(([key, label]) => ({
					label,
					value: attributes[key] || void 0,
					onChange: (v) => setAttributes({ [key]: v || '' }),
				})),
			}),
			/* @__PURE__ */ wp.element.createElement(
				PanelBody,
				{ title: __('Shape & size', 'vulopilot'), initialOpen: false },
				/* @__PURE__ */ wp.element.createElement(RangeControl, {
					label: __('Corner radius (px)', 'vulopilot'),
					value: attributes.radius,
					min: 0,
					max: 32,
					onChange: (v) => setAttributes({ radius: v != null ? v : 4 }),
					allowReset: true,
					resetFallbackValue: 4,
				}),
				children,
			),
		);
	}
	function ItemTools({ list, index, onChange, label }) {
		const move = (d) => {
			const n = [...list];
			const [x] = n.splice(index, 1);
			n.splice(index + d, 0, x);
			onChange(n);
		};
		return /* @__PURE__ */ wp.element.createElement(
			Flex,
			{ justify: 'flex-end', gap: 1, style: { marginBottom: 12 } },
			/* @__PURE__ */ wp.element.createElement(
				FlexItem,
				null,
				/* @__PURE__ */ wp.element.createElement(Button, {
					size: 'small',
					variant: 'secondary',
					disabled: index === 0,
					onClick: () => move(-1),
					label: __('Move up', 'vulopilot'),
					icon: 'arrow-up-alt2',
				}),
			),
			/* @__PURE__ */ wp.element.createElement(
				FlexItem,
				null,
				/* @__PURE__ */ wp.element.createElement(Button, {
					size: 'small',
					variant: 'secondary',
					disabled: index === list.length - 1,
					onClick: () => move(1),
					label: __('Move down', 'vulopilot'),
					icon: 'arrow-down-alt2',
				}),
			),
			/* @__PURE__ */ wp.element.createElement(
				FlexItem,
				null,
				/* @__PURE__ */ wp.element.createElement(Button, {
					size: 'small',
					variant: 'secondary',
					isDestructive: true,
					onClick: () => {
						if (window.confirm(__('Remove this ', 'vulopilot') + (label || __('item', 'vulopilot')) + '?'))
							onChange(list.filter((_, i) => i !== index));
					},
					icon: 'trash',
					label: __('Remove', 'vulopilot'),
				}),
			),
		);
	}
	var update = (list, i, patch) => list.map((x, k) => (k === i ? { ...x, ...patch } : x));

	// blocks-src/checklist.jsx
	var { __: __2 } = wp.i18n;
	var { registerBlockType } = wp.blocks;
	var { InspectorControls, useBlockProps } = wp.blockEditor;
	var { SelectControl } = wp.components;
	var ServerSideRender = wp.serverSideRender;
	var meta = require_block();
	registerBlockType(meta.name, {
		edit({ attributes: a, setAttributes: set }) {
			const groups = a.groups || [];
			const load = (slug) => {
				const r = DATA.checklists.find((x) => x.slug === slug);
				if (r && window.confirm(__2('Replace the current items with this checklist?', 'vulopilot')))
					set({ storageId: r.slug, groups: r.groups.map(([title, items]) => ({ title, items: items.join('\n') })) });
			};
			return /* @__PURE__ */ wp.element.createElement(
				'div',
				{ ...useBlockProps() },
				/* @__PURE__ */ wp.element.createElement(
					InspectorControls,
					null,
					/* @__PURE__ */ wp.element.createElement(
						PanelBody,
						{ title: __2('Checklist', 'vulopilot') },
						/* @__PURE__ */ wp.element.createElement(TextControl, {
							label: __2('Heading (empty = none)', 'vulopilot'),
							value: a.heading,
							onChange: (v) => set({ heading: v }),
						}),
						/* @__PURE__ */ wp.element.createElement(TextControl, {
							label: __2('Save key', 'vulopilot'),
							help: __2(
								'Progress is saved in the visitor\u2019s browser under this name. Use a different key for each checklist.',
								'vulopilot',
							),
							value: a.storageId,
							onChange: (v) => set({ storageId: v }),
						}),
						/* @__PURE__ */ wp.element.createElement(SelectControl, {
							label: __2('Load a ready-made checklist', 'vulopilot'),
							value: '',
							options: [
								{ value: '', label: __2('\u2014 choose \u2014', 'vulopilot') },
								...DATA.checklists.map((r) => ({ value: r.slug, label: r.title })),
							],
							onChange: load,
						}),
					),
					groups.map((g, i) =>
						/* @__PURE__ */ wp.element.createElement(
							PanelBody,
							{ key: i, title: `${i + 1}. ${g.title || __2('Group', 'vulopilot')}`, initialOpen: i === 0 },
							/* @__PURE__ */ wp.element.createElement(TextControl, {
								label: __2('Group title', 'vulopilot'),
								value: g.title,
								onChange: (v) => set({ groups: update(groups, i, { title: v }) }),
							}),
							/* @__PURE__ */ wp.element.createElement(TextareaControl, {
								label: __2('Items (one per line)', 'vulopilot'),
								rows: 6,
								value: g.items,
								onChange: (v) => set({ groups: update(groups, i, { items: v }) }),
							}),
							/* @__PURE__ */ wp.element.createElement(ItemTools, {
								list: groups,
								index: i,
								onChange: (n) => set({ groups: n }),
								label: __2('group', 'vulopilot'),
							}),
						),
					),
					/* @__PURE__ */ wp.element.createElement(
						PanelBody,
						{ title: __2('Add a group', 'vulopilot'), initialOpen: false },
						/* @__PURE__ */ wp.element.createElement(
							Button,
							{
								variant: 'primary',
								onClick: () => set({ groups: [...groups, { title: 'New group', items: 'First item\nSecond item' }] }),
							},
							__2('Add group', 'vulopilot'),
						),
					),
					/* @__PURE__ */ wp.element.createElement(
						PanelBody,
						{ title: __2('Progress card text', 'vulopilot'), initialOpen: false },
						/* @__PURE__ */ wp.element.createElement(TextControl, {
							label: __2('Ring label', 'vulopilot'),
							value: a.ringLabel,
							onChange: (v) => set({ ringLabel: v }),
						}),
						/* @__PURE__ */ wp.element.createElement(TextControl, {
							label: __2('Count text', 'vulopilot'),
							help: '{done} {total}',
							value: a.countText,
							onChange: (v) => set({ countText: v }),
						}),
						/* @__PURE__ */ wp.element.createElement(TextControl, {
							label: __2('While in progress', 'vulopilot'),
							value: a.progressText,
							onChange: (v) => set({ progressText: v }),
						}),
						/* @__PURE__ */ wp.element.createElement(TextControl, {
							label: __2('When complete', 'vulopilot'),
							value: a.completeText,
							onChange: (v) => set({ completeText: v }),
						}),
						/* @__PURE__ */ wp.element.createElement(TextControl, {
							label: __2('Reset button (empty = hide)', 'vulopilot'),
							value: a.resetText,
							onChange: (v) => set({ resetText: v }),
						}),
						/* @__PURE__ */ wp.element.createElement(TextControl, {
							label: __2('Promo kicker', 'vulopilot'),
							value: a.ctaKicker,
							onChange: (v) => set({ ctaKicker: v }),
						}),
						/* @__PURE__ */ wp.element.createElement(TextControl, {
							label: __2('Promo title (empty = hide box)', 'vulopilot'),
							value: a.ctaTitle,
							onChange: (v) => set({ ctaTitle: v }),
						}),
						/* @__PURE__ */ wp.element.createElement(TextareaControl, {
							label: __2('Promo text', 'vulopilot'),
							rows: 3,
							value: a.ctaBody,
							onChange: (v) => set({ ctaBody: v }),
						}),
						/* @__PURE__ */ wp.element.createElement(TextControl, {
							label: __2('Install button text (empty = hide)', 'vulopilot'),
							value: a.ctaText,
							onChange: (v) => set({ ctaText: v }),
						}),
						/* @__PURE__ */ wp.element.createElement(TextControl, {
							label: __2('Install button link', 'vulopilot'),
							value: a.ctaUrl,
							onChange: (v) => set({ ctaUrl: v }),
						}),
						/* @__PURE__ */ wp.element.createElement(TextControl, {
							label: __2('Small note under the button', 'vulopilot'),
							value: a.ctaNote,
							onChange: (v) => set({ ctaNote: v }),
						}),
						/* @__PURE__ */ wp.element.createElement(TextControl, {
							label: __2('Download link text (empty = hide)', 'vulopilot'),
							value: a.downloadText,
							onChange: (v) => set({ downloadText: v }),
						}),
						/* @__PURE__ */ wp.element.createElement(ToggleControl, {
							label: __2('Sticky progress card', 'vulopilot'),
							checked: a.sticky,
							onChange: (v) => set({ sticky: v }),
						}),
						/* @__PURE__ */ wp.element.createElement(ToggleControl, {
							label: __2('Add HowTo structured data', 'vulopilot'),
							checked: a.howToSchema,
							onChange: (v) => set({ howToSchema: v }),
						}),
					),
				),
				/* @__PURE__ */ wp.element.createElement(
					InspectorControls,
					{ group: 'styles' },
					/* @__PURE__ */ wp.element.createElement(
						StylePanel,
						{ attributes: a, setAttributes: set },
						/* @__PURE__ */ wp.element.createElement(RangeControl, {
							label: __2('Ring size (px)', 'vulopilot'),
							value: a.ringSize,
							min: 80,
							max: 240,
							onChange: (v) => set({ ringSize: v || 140 }),
						}),
						/* @__PURE__ */ wp.element.createElement(RangeControl, {
							label: __2('Max width (px)', 'vulopilot'),
							value: a.maxWidth,
							min: 480,
							max: 1400,
							step: 8,
							onChange: (v) => set({ maxWidth: v || 1024 }),
						}),
					),
				),
				/* @__PURE__ */ wp.element.createElement(ServerSideRender, {
					block: meta.name,
					attributes: a,
					httpMethod: 'POST',
				}),
			);
		},
		save: () => null,
	});
})();
