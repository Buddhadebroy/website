/**
 * Block editor script for vulopilot/product-tour (built file: only used inside the editor).
 * The front-end output is in render.php next to this file.
 */
/* Product Tour (VuloPilot) – editor. Front end: render.php + vp-site.js (tour widget). */
(function (wp, K) {
	var el = K.el,
		__ = K.__,
		C = wp.components,
		BE = wp.blockEditor,
		F = wp.element.Fragment;
	wp.blocks.registerBlockType('vulopilot/product-tour', {
		title: 'Product Tour (VuloPilot)',
		category: 'vulopilot',
		icon: 'images-alt2',
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
						{ title: __('Heading', 'vulopilot'), initialOpen: false },
						el(C.ToggleControl, {
							label: __('Show heading above the tour', 'vulopilot'),
							checked: !!a.showHeading,
							onChange: function (v) {
								set({ showHeading: v });
							},
						}),
						a.showHeading
							? el(
									F,
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
						{ title: __('Groups and screens', 'vulopilot'), initialOpen: true },
						el(
							'p',
							{ style: { fontSize: 12, color: '#757575' } },
							__(
								'Groups are the cards on the left. Each group has one or more screens. Each screen has an image, a title, a path line and up to six numbered hotspots. The first three hotspots also show as cards under the image.',
								'vulopilot',
							),
						),
					),
					el(K.Repeater, {
						items: a.groups,
						min: 1,
						max: 10,
						addLabel: __('Add group', 'vulopilot'),
						onChange: function (v) {
							set({ groups: v });
						},
						make: function () {
							return {
								icon: 'sparkles',
								title: 'New group',
								hint: '',
								screens: [
									{
										title: 'New screen',
										where: 'wp-admin / VuloPilot',
										screen: '',
										image: '',
										imageId: 0,
										alt: '',
										hotspots:
											'30, 40 | First point | Short text.\n70, 30 | Second point | Short text.\n40, 80 | Third point | Short text.',
									},
								],
							};
						},
						title: function (g) {
							return g.title;
						},
						render: function (g, up) {
							return el(
								F,
								null,
								el(K.Icon, {
									value: g.icon,
									onChange: function (v) {
										up({ icon: v });
									},
								}),
								el(C.TextControl, {
									label: __('Group title', 'vulopilot'),
									value: g.title || '',
									onChange: function (v) {
										up({ title: v });
									},
								}),
								el(C.TextControl, {
									label: __('Group text', 'vulopilot'),
									value: g.hint || '',
									onChange: function (v) {
										up({ hint: v });
									},
								}),
								el(
									'div',
									{ style: { fontSize: 11, fontWeight: 600, textTransform: 'uppercase', margin: '12px 0 4px' } },
									__('Screens in this group', 'vulopilot'),
								),
								el(K.Repeater, {
									items: g.screens || [],
									min: 1,
									max: 12,
									addLabel: __('Add screen', 'vulopilot'),
									onChange: function (v) {
										up({ screens: v });
									},
									make: function () {
										return {
											title: 'New screen',
											where: 'wp-admin / VuloPilot',
											screen: '',
											image: '',
											imageId: 0,
											alt: '',
											hotspots: '',
										};
									},
									title: function (s) {
										return s.title;
									},
									render: function (s, ups) {
										return el(
											F,
											null,
											el(C.TextControl, {
												label: __('Screen title (under the image)', 'vulopilot'),
												value: s.title || '',
												onChange: function (v) {
													ups({ title: v });
												},
											}),
											el(C.TextControl, {
												label: __('Path line (address bar and under the title)', 'vulopilot'),
												value: s.where || '',
												onChange: function (v) {
													ups({ where: v });
												},
											}),
											el(K.Image, {
												label: __('Screenshot image', 'vulopilot'),
												url: s.image,
												id: s.imageId,
												onChange: function (m) {
													ups({ image: m.url, imageId: m.id, alt: m.alt || s.alt });
												},
												help: __('Upload to Media and pick it here.', 'vulopilot'),
											}),
											s.image
												? null
												: el(K.Screen, {
														label: __('Or a screenshot from Customize → VuloPilot screenshots', 'vulopilot'),
														value: s.screen,
														onChange: function (v) {
															ups({ screen: v });
														},
													}),
											el(C.TextControl, {
												label: __('Image alt text', 'vulopilot'),
												value: s.alt || '',
												onChange: function (v) {
													ups({ alt: v });
												},
											}),
											el(C.TextareaControl, {
												label: __('Hotspots', 'vulopilot'),
												rows: 5,
												value: s.hotspots || '',
												onChange: function (v) {
													ups({ hotspots: v });
												},
												help: __(
													'One per line: left %, top % | Title | Text. Example: 34, 49 | Suggested prompts | One click to common tasks.',
													'vulopilot',
												),
											}),
										);
									},
								}),
							);
						},
					}),
					el(
						C.PanelBody,
						{ title: __('Playback & layout', 'vulopilot'), initialOpen: false },
						el(C.RangeControl, {
							label: __('Seconds per hotspot', 'vulopilot'),
							value: (a.interval || 4200) / 1000,
							min: 2,
							max: 15,
							step: 0.2,
							onChange: function (v) {
								set({ interval: Math.round((v || 4.2) * 1000) });
							},
						}),
						el(C.ToggleControl, {
							label: __('Full section (padding and page width)', 'vulopilot'),
							help: __('Turn off to place the tour inside your own Kadence row.', 'vulopilot'),
							checked: !!a.wrap,
							onChange: function (v) {
								set({ wrap: v });
							},
						}),
						txt('sectionId', __('Section ID (for #links)', 'vulopilot')),
					),
				),
				el(BE.InspectorControls, { group: 'styles' }, el(K.Style, props)),
				el(K.Preview, { name: 'vulopilot/product-tour', attributes: a }),
			);
		},
		save: function () {
			return null;
		},
	});
})(window.wp, window.VPKit);
