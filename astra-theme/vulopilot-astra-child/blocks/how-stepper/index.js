/**
 * Block editor script for vulopilot/how-stepper (built file: only used inside the editor).
 * The front-end output is in render.php next to this file.
 */
/* How It Works Stepper (VuloPilot) – editor. Front end: render.php + vp-motion.js (stepper widget). */
(function (wp, K) {
	var el = K.el,
		__ = K.__,
		C = wp.components,
		BE = wp.blockEditor;
	wp.blocks.registerBlockType('vulopilot/how-stepper', {
		title: 'How It Works Stepper (VuloPilot)',
		category: 'vulopilot',
		icon: 'controls-forward',
		edit: function (props) {
			var a = props.attributes,
				set = props.setAttributes;
			function txt(key, label, help) {
				return el(C.TextControl, {
					label: label,
					help: help,
					value: a[key] || '',
					onChange: function (v) {
						var o = {};
						o[key] = v;
						set(o);
					},
					__nextHasNoMarginBottom: true,
				});
			}
			return el(
				'div',
				BE.useBlockProps(),
				el(
					BE.InspectorControls,
					null,
					el(
						C.PanelBody,
						{ title: __('Heading', 'vulopilot'), initialOpen: true },
						el(C.ToggleControl, {
							label: __('Show heading', 'vulopilot'),
							checked: !!a.showHeading,
							onChange: function (v) {
								set({ showHeading: v });
							},
						}),
						a.showHeading
							? el(
									wp.element.Fragment,
									null,
									txt('eyebrow', __('Small label', 'vulopilot')),
									txt('title', __('Title', 'vulopilot')),
									txt('titleAccent', __('Title, blue part', 'vulopilot')),
									el(C.TextareaControl, {
										label: __('Intro', 'vulopilot'),
										value: a.intro || '',
										onChange: function (v) {
											set({ intro: v });
										},
									}),
								)
							: null,
					),
					el(
						C.PanelBody,
						{ title: __('Steps', 'vulopilot'), initialOpen: true },
						el(
							'p',
							{ style: { fontSize: 12, color: '#757575' } },
							__(
								'Each step has a button on top, text on the left and a graphic or your own image on the right.',
								'vulopilot',
							),
						),
					),
					el(K.Repeater, {
						items: a.steps,
						min: 2,
						max: 8,
						addLabel: __('Add step', 'vulopilot'),
						onChange: function (v) {
							set({ steps: v });
						},
						make: function () {
							return {
								icon: 'sparkles',
								title: 'New step',
								desc: '',
								points: '',
								graphic: '',
								image: '',
								imageId: 0,
								imageAlt: '',
							};
						},
						title: function (s) {
							return s.title;
						},
						render: function (s, up) {
							return el(
								wp.element.Fragment,
								null,
								el(K.Icon, {
									value: s.icon,
									onChange: function (v) {
										up({ icon: v });
									},
								}),
								el(C.TextControl, {
									label: __('Title', 'vulopilot'),
									value: s.title || '',
									onChange: function (v) {
										up({ title: v });
									},
								}),
								el(C.TextareaControl, {
									label: __('Text', 'vulopilot'),
									rows: 2,
									value: s.desc || '',
									onChange: function (v) {
										up({ desc: v });
									},
								}),
								el(K.Lines, {
									label: __('Points (with ticks)', 'vulopilot'),
									value: s.points,
									onChange: function (v) {
										up({ points: v });
									},
								}),
								el(K.Graphic, {
									label: __('Graphic on the right', 'vulopilot'),
									value: s.graphic,
									onChange: function (v) {
										up({ graphic: v });
									},
									help: __('Any VuloPilot graphic. An image below replaces it.', 'vulopilot'),
								}),
								el(K.Image, {
									label: __('Or your own image', 'vulopilot'),
									url: s.image,
									id: s.imageId,
									onChange: function (m) {
										up({ image: m.url, imageId: m.id, imageAlt: m.alt });
									},
								}),
								s.image
									? el(C.TextControl, {
											label: __('Image alt text', 'vulopilot'),
											value: s.imageAlt || '',
											onChange: function (v) {
												up({ imageAlt: v });
											},
										})
									: null,
							);
						},
					}),
					el(
						C.PanelBody,
						{ title: __('Playback & layout', 'vulopilot'), initialOpen: false },
						el(C.RangeControl, {
							label: __('Seconds per step', 'vulopilot'),
							value: (a.interval || 5600) / 1000,
							min: 2,
							max: 15,
							step: 0.2,
							onChange: function (v) {
								set({ interval: Math.round((v || 5.6) * 1000) });
							},
						}),
						txt('playLabel', __('“Auto-playing” text', 'vulopilot')),
						txt('pauseLabel', __('“Paused” text', 'vulopilot')),
						el(C.ToggleControl, {
							label: __('Full section (padding and page width)', 'vulopilot'),
							help: __('Turn off to place the stepper inside your own row or column.', 'vulopilot'),
							checked: !!a.wrap,
							onChange: function (v) {
								set({ wrap: v });
							},
						}),
						txt('sectionId', __('Section ID (for #links)', 'vulopilot')),
					),
				),
				el(BE.InspectorControls, { group: 'styles' }, el(K.Style, props)),
				el(K.Preview, { name: 'vulopilot/how-stepper', attributes: a }),
			);
		},
		save: function () {
			return null;
		},
	});
})(window.wp, window.VPKit);
