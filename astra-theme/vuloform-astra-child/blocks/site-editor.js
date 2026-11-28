/**
 * Editor side of Site Logo (VuloForm) and Site Menu (VuloForm). Plain wp.* globals, no build step.
 * The preview is the real PHP output (ServerSideRender).
 */
( function ( wp ) {
	var el = wp.element.createElement;
	var be = wp.blockEditor;
	var c = wp.components;
	var SSR = wp.serverSideRender;
	var preview = function ( name, a ) { return el( 'div', { style: { pointerEvents: 'none' } }, el( SSR, { block: name, attributes: a } ) ); };

	wp.blocks.registerBlockType( 'vuloform/site-logo', {
		edit: function ( props ) {
			var a = props.attributes, set = props.setAttributes;
			return el( 'div', be.useBlockProps(),
				el( be.InspectorControls, null,
					el( c.PanelBody, { title: 'Logo' },
						el( 'p', null, 'The image is Customize → Site Identity → Logo (and Astra\'s Retina logo). The title is Settings → General → Site Title.' ),
						el( c.ToggleControl, { label: 'Show the site title', checked: a.showTitle, onChange: function ( v ) { set( { showTitle: v } ); } } ),
						el( c.TextControl, { label: 'End of the title in brand blue', value: a.accent, onChange: function ( v ) { set( { accent: v } ); } } ),
						el( c.RangeControl, { label: 'Logo width (px)', value: a.width, min: 12, max: 400, onChange: function ( v ) { set( { width: v || 34 } ); } } ),
						el( c.ToggleControl, { label: 'Link to the home page', checked: a.link, onChange: function ( v ) { set( { link: v } ); } } )
					)
				),
				preview( 'vuloform/site-logo', a )
			);
		},
		save: function () { return null; }
	} );

	wp.blocks.registerBlockType( 'vuloform/site-menu', {
		edit: function ( props ) {
			var a = props.attributes, set = props.setAttributes;
			return el( 'div', be.useBlockProps(),
				el( be.InspectorControls, null,
					el( c.PanelBody, { title: 'Menu' },
						el( 'p', null, 'Links come from Appearance → Menus → "VuloForm header".' ),
						el( c.SelectControl, { label: 'Part', value: a.part, options: [ { value: 'links', label: 'Links (+ phone menu)' }, { value: 'toggle', label: 'Menu button (phones)' } ], onChange: function ( v ) { set( { part: v } ); } } )
					)
				),
				preview( 'vuloform/site-menu', a )
			);
		},
		save: function () { return null; }
	} );
} )( window.wp );
