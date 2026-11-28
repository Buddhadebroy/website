<?php
/**
 * Block parser + Kadence render emulation for the test server.
 * Kadence Row Layout is dynamic in Kadence 3.x: this prints the same wrapper markup and the CSS Kadence
 * generates from the padding attributes (desktop / ≤1024px / ≤767px).
 */

function vmt_parse_blocks( $html ) {
	$re  = '/<!--\s+(\/)?wp:([a-z][a-z0-9_-]*\/)?([a-z][a-z0-9_-]*)\s+(\{(?:(?!-->).)*?\}\s+)?(\/)?-->/s';
	$pos = 0;
	$root = array( 'blockName' => null, 'attrs' => array(), 'innerBlocks' => array(), 'innerContent' => array() );
	$stack = array( &$root );
	while ( preg_match( $re, $html, $m, PREG_OFFSET_CAPTURE, $pos ) ) {
		$start = $m[0][1];
		$len   = strlen( $m[0][0] );
		$top   = &$stack[ count( $stack ) - 1 ];
		$text  = substr( $html, $pos, $start - $pos );
		if ( '' !== $text ) {
			$top['innerContent'][] = $text;
		}
		$closer = '' !== $m[1][0];
		$name   = ( $m[2][0] ? $m[2][0] : 'core/' ) . $m[3][0];
		$attrs  = isset( $m[4] ) && '' !== $m[4][0] ? json_decode( trim( $m[4][0] ), true ) : array();
		$void   = isset( $m[5] ) && '' !== $m[5][0];
		if ( $closer ) {
			array_pop( $stack );
		} else {
			$block = array( 'blockName' => $name, 'attrs' => $attrs ?: array(), 'innerBlocks' => array(), 'innerContent' => array() );
			$top['innerBlocks'][]  = $block;
			$top['innerContent'][] = null;
			if ( ! $void ) {
				$stack[] = &$top['innerBlocks'][ count( $top['innerBlocks'] ) - 1 ];
			}
		}
		unset( $top );
		$pos = $start + $len;
	}
	$rest = substr( $html, $pos );
	if ( '' !== trim( $rest ) ) {
		$root['innerContent'][] = $rest;
	}
	return $root['innerBlocks'];
}

$GLOBALS['vmt_css'] = array();

function vmt_pad_css( $sel, $arr ) {
	if ( ! is_array( $arr ) ) {
		return '';
	}
	$sides = array( 'top', 'right', 'bottom', 'left' );
	$out   = '';
	foreach ( $sides as $i => $s ) {
		if ( isset( $arr[ $i ] ) && '' !== $arr[ $i ] && null !== $arr[ $i ] ) {
			$out .= 'padding-' . $s . ':' . $arr[ $i ] . 'px;';
		}
	}
	return $out ? $sel . '{' . $out . '}' : '';
}

function vmt_render_block( $b ) {
	$inner = '';
	$k     = 0;
	foreach ( $b['innerContent'] as $chunk ) {
		if ( null === $chunk ) {
			$inner .= vmt_render_block( $b['innerBlocks'][ $k++ ] );
		} else {
			$inner .= $chunk;
		}
	}
	$a = $b['attrs'];
	switch ( $b['blockName'] ) {
		case 'kadence/rowlayout':
			$id   = $a['uniqueID'];
			$cols = isset( $a['columns'] ) ? (int) $a['columns'] : 1;
			$tag  = isset( $a['htmlTag'] ) ? $a['htmlTag'] : 'div';
			$sel  = '.kb-row-layout-id' . $id . ' > .kt-row-column-wrap';
			$css  = $sel . '{align-content:start;}';
			$css .= $sel . '{grid-template-columns:' . implode( ' ', array_fill( 0, $cols, 'minmax(0, 1fr)' ) ) . ';}';
			$css .= vmt_pad_css( $sel, isset( $a['padding'] ) ? $a['padding'] : null );
			if ( ! empty( $a['maxWidth'] ) ) { // Kadence: Structure → Content max width.
				$css .= $sel . '{max-width:' . $a['maxWidth'] . ( ! empty( $a['maxWidthUnit'] ) ? $a['maxWidthUnit'] : 'px' ) . ';margin-left:auto;margin-right:auto;}';
			}
			$css .= '@media all and (max-width: 1024px){' . vmt_pad_css( $sel, isset( $a['tabletPadding'] ) ? $a['tabletPadding'] : null ) . '}';
			$css .= '@media all and (max-width: 767px){' . $sel . '{grid-template-columns:minmax(0, 1fr);}' . vmt_pad_css( $sel, isset( $a['mobilePadding'] ) ? $a['mobilePadding'] : null ) . '}';
			$GLOBALS['vmt_css'][] = $css;
			$anchor = ! empty( $a['anchor'] ) ? ' id="' . esc_attr( $a['anchor'] ) . '"' : '';
			$cls    = 'kb-row-layout-wrap kb-row-layout-id' . $id . ' alignnone' . ( ! empty( $a['className'] ) ? ' ' . $a['className'] : '' ) . ' wp-block-kadence-rowlayout';
			return '<' . $tag . $anchor . ' class="' . esc_attr( $cls ) . '"><div class="kt-row-column-wrap kt-has-' . $cols . '-columns kt-row-layout-equal kt-tab-layout-inherit kt-mobile-layout-row kt-row-valign-top kb-theme-content-width">' . $inner . '</div></' . $tag . '>';
		case 'vulomail/graphic':
			return vm_render_graphic( $a );
		case 'kadence/singlebtn':
			// Same output as Kadence 3.8 Kadence_Blocks_Singlebtn_Block::build_html() + get_block_wrapper_attributes().
			$id  = $a['uniqueID'];
			$cls = 'wp-block-kadence-singlebtn' . ( ! empty( $a['className'] ) ? ' ' . $a['className'] : '' ) . ' kb-button kt-button button kb-btn' . $id . ' kt-btn-size-standard kt-btn-width-type-auto kb-btn-global-fill kt-btn-has-text-true kt-btn-has-svg-false';
			$tgt = ( ! empty( $a['target'] ) && '_blank' === $a['target'] ) ? ' target="_blank" rel="noreferrer noopener"' : '';
			$tag = ! empty( $a['link'] ) ? 'a' : 'span';
			return '<' . $tag . ' class="' . esc_attr( $cls ) . '"' . ( ! empty( $a['link'] ) ? ' href="' . esc_attr( $a['link'] ) . '"' . $tgt : '' ) . '><span class="kt-btn-inner-text">' . $a['text'] . '</span></' . $tag . '>';
		case 'vulomail/site-logo':
			return vm_render_site_logo( $a );
		case 'vulomail/site-menu':
			return vm_render_site_menu( $a );
		default:
			return $inner;
	}
}

function vmt_do_blocks( $html ) {
	$out = '';
	foreach ( vmt_parse_blocks( $html ) as $b ) {
		$out .= vmt_render_block( $b );
	}
	return $out;
}
