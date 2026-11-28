/**
 * Editor side of the "Animated Graphic (Notifima)" block (no build step: plain wp.* globals).
 * Sidebar: pick a graphic + max width. The preview is the real PHP output (ServerSideRender).
 */
( function ( wp ) {
	var el = wp.element.createElement;
	var be = wp.blockEditor;
	var c = wp.components;
	var SSR = wp.serverSideRender;

	wp.blocks.registerBlockType( 'notifima/graphic', {
		edit: function ( props ) {
			var a = props.attributes;
			var list = window.NF_GRAPHICS || {};
			var options = Object.keys( list ).map( function ( k ) { return { value: k, label: k + ' — ' + list[ k ] }; } );
			return el( 'div', be.useBlockProps(),
				el( be.InspectorControls, null,
					el( c.PanelBody, { title: 'Graphic' },
						el( c.SelectControl, { label: 'Graphic', value: a.name, options: options, onChange: function ( v ) { props.setAttributes( { name: v } ); } } ),
						el( c.RangeControl, { label: 'Max width (px, 0 = full)', value: a.maxWidth, min: 0, max: 1400, step: 10, onChange: function ( v ) { props.setAttributes( { maxWidth: v || 0 } ); } } )
					)
				),
				el( 'div', { style: { pointerEvents: 'none' } }, el( SSR, { block: 'notifima/graphic', attributes: a } ) )
			);
		},
		save: function () { return null; }
	} );
} )( window.wp );
