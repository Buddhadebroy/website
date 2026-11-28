/**
 * Editor side of Site Logo (Notifima) and Site Menu (Notifima). Plain wp.* globals, no build step.
 * The preview is the real PHP output (ServerSideRender).
 */
( function ( wp ) {
	var el = wp.element.createElement;
	var be = wp.blockEditor;
	var c = wp.components;
	var SSR = wp.serverSideRender;
	var preview = function ( name, a ) { return el( 'div', { style: { pointerEvents: 'none' } }, el( SSR, { block: name, attributes: a } ) ); };

	wp.blocks.registerBlockType( 'notifima/site-logo', {
		edit: function ( props ) {
			var a = props.attributes, set = props.setAttributes;
			return el( 'div', be.useBlockProps(),
				el( be.InspectorControls, null,
					el( c.PanelBody, { title: 'Logo' },
						el( 'p', null, 'The image is Customize → Site Identity → Logo. Without one, the Site Title is shown as the Notifima wordmark.' ),
						el( c.RangeControl, { label: 'Logo image width (px)', value: a.width, min: 20, max: 400, onChange: function ( v ) { set( { width: v || 130 } ); } } ),
						el( c.ToggleControl, { label: 'Purple ring after the wordmark', checked: a.dot, onChange: function ( v ) { set( { dot: v } ); } } ),
						el( c.ToggleControl, { label: 'Link to the home page', checked: a.link, onChange: function ( v ) { set( { link: v } ); } } )
					)
				),
				preview( 'notifima/site-logo', a )
			);
		},
		save: function () { return null; }
	} );

	wp.blocks.registerBlockType( 'notifima/site-menu', {
		edit: function ( props ) {
			var a = props.attributes, set = props.setAttributes;
			return el( 'div', be.useBlockProps(),
				el( be.InspectorControls, null,
					el( c.PanelBody, { title: 'Menu' },
						el( 'p', null, 'Links come from Appearance → Menus → "Notifima header". An item with sub items becomes a mega menu (level 2 = column titles, level 3 = links; Description = small text; CSS class nf-icon-NAME = icon).' ),
						el( c.SelectControl, { label: 'Part', value: a.part, options: [ { value: 'links', label: 'Links (desktop menu)' }, { value: 'drawer', label: 'Phone menu panel' }, { value: 'toggle', label: 'Menu button (burger)' }, { value: 'icons', label: 'Cart + account icons' } ], onChange: function ( v ) { set( { part: v } ); } } ),
						a.part === 'drawer' && el( c.TextControl, { label: 'Button text at the end of the phone menu', value: a.ctaText, onChange: function ( v ) { set( { ctaText: v } ); } } )
					)
				),
				preview( 'notifima/site-menu', a )
			);
		},
		save: function () { return null; }
	} );
	wp.blocks.registerBlockType( 'notifima/release-notes', {
		edit: function () {
			return el( 'div', be.useBlockProps(),
				el( be.InspectorControls, null,
					el( c.PanelBody, { title: 'Release notes' },
						el( 'p', null, 'Read automatically from WordPress.org (and GitHub, if set) in Customize → Notifima → General. A new release shows up within an hour, or at once with Appearance → Notifima → Refresh release notes now.' )
					)
				),
				preview( 'notifima/release-notes', {} )
			);
		},
		save: function () { return null; }
	} );
} )( window.wp );
