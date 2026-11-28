/**
 * Editor side of the VuloForm section blocks (no build step: plain wp.* globals).
 * Every block gets a sidebar made from the "vfFields" list in its block.json
 * (text → text box, textarea → multi-line box, toggle → switch) and a live preview of the real PHP output.
 */
( function ( wp ) {
	var el = wp.element.createElement;
	var be = wp.blockEditor;
	var c = wp.components;
	var SSR = wp.serverSideRender;
	var defs = window.VF_BLOCK_FIELDS || {};

	Object.keys( defs ).forEach( function ( name ) {
		wp.blocks.registerBlockType( name, {
			edit: function ( props ) {
				var a = props.attributes;
				var controls = defs[ name ].map( function ( f ) {
					var set = function ( v ) { var o = {}; o[ f.key ] = v; props.setAttributes( o ); };
					if ( f.type === 'toggle' ) {
						return el( c.ToggleControl, { key: f.key, label: f.label, checked: !! a[ f.key ], onChange: set, __nextHasNoMarginBottom: true } );
					}
					return el( f.type === 'textarea' ? c.TextareaControl : c.TextControl, { key: f.key, label: f.label, value: a[ f.key ] || '', onChange: set, rows: 6, __nextHasNoMarginBottom: true } );
				} );
				return el( 'div', be.useBlockProps(),
					el( be.InspectorControls, null, el( c.PanelBody, { title: 'Text' }, controls ) ),
					el( 'div', { style: { pointerEvents: 'none' } }, el( SSR, { block: name, attributes: a } ) )
				);
			},
			save: function () { return null; }
		} );
	} );
} )( window.wp );
