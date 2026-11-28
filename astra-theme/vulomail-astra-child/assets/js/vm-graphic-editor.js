/* Editor UI for "Animated Graphic (VuloMail)": pick a part, preview it with the server render. */
(function (wp) {
	var el = wp.element.createElement;
	var parts = window.vmGraphicParts || {};
	var options = Object.keys(parts).map(function (k) { return { value: k, label: parts[k] }; });
	options.unshift({ value: '', label: '— Choose a graphic —' });
	wp.blocks.registerBlockType('vulomail/graphic', {
		apiVersion: 3,
		title: 'Animated Graphic (VuloMail)',
		icon: 'format-image',
		category: 'design',
		attributes: { part: { type: 'string', default: '' }, props: { type: 'object', default: {} }, className: { type: 'string', default: '' } },
		edit: function (props) {
			var blockProps = wp.blockEditor.useBlockProps();
			return el('div', blockProps,
				el(wp.blockEditor.InspectorControls, null,
					el(wp.components.PanelBody, { title: 'Graphic' },
						el(wp.components.SelectControl, {
							label: 'Part', value: props.attributes.part, options: options,
							onChange: function (v) { props.setAttributes({ part: v }); }
						}),
						el(wp.components.TextareaControl, {
							label: 'Props (JSON)', help: 'Optional settings for this part, e.g. {"label":"Delivered","size":180}',
							value: JSON.stringify(props.attributes.props || {}),
							onChange: function (v) { try { props.setAttributes({ props: JSON.parse(v || '{}') }); } catch (e) {} }
						})
					)
				),
				props.attributes.part
					? el(wp.serverSideRender, { block: 'vulomail/graphic', attributes: props.attributes })
					: el(wp.components.Placeholder, { label: 'Animated Graphic (VuloMail)', instructions: 'Choose a graphic in the block settings.' })
			);
		},
		save: function () { return null; }
	});
})(window.wp);

/* Site Logo (VuloMail) and Site Menu (VuloMail) */
(function (wp) {
	var el = wp.element.createElement;
	function block(name, title, attrs, controls) {
		wp.blocks.registerBlockType(name, {
			apiVersion: 3, title: title, icon: name === 'vulomail/site-logo' ? 'admin-site' : 'menu', category: 'theme', attributes: attrs,
			edit: function (props) {
				return el('div', wp.blockEditor.useBlockProps(),
					el(wp.blockEditor.InspectorControls, null, el(wp.components.PanelBody, { title: title }, controls(props))),
					el(wp.serverSideRender, { block: name, attributes: props.attributes }));
			},
			save: function () { return null; }
		});
	}
	block('vulomail/site-logo', 'Site Logo (VuloMail)', { link: { type: 'boolean', default: true }, className: { type: 'string', default: '' } }, function (p) {
		return [el('p', { key: 'h' }, 'Logo and title come from Customize → Site Identity.'), el(wp.components.ToggleControl, { key: 't', label: 'Link to the home page', checked: p.attributes.link, onChange: function (v) { p.setAttributes({ link: v }); } })];
	});
	block('vulomail/site-menu', 'Site Menu (VuloMail)', { variant: { type: 'string', default: 'header' }, className: { type: 'string', default: '' } }, function (p) {
		return [el('p', { key: 'h' }, 'Links come from Appearance → Menus.'), el(wp.components.SelectControl, { key: 's', label: 'Show', value: p.attributes.variant, options: [{ value: 'header', label: 'Header links (desktop)' }, { value: 'phone', label: 'Phone menu button + full-screen menu' }, { value: 'footer', label: 'Footer link columns' }], onChange: function (v) { p.setAttributes({ variant: v }); } })];
	});
})(window.wp);
