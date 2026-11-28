<?php
/**
 * Blocks: Plan comparison, Checklist, Mega menu, How it works stepper, Product tour.
 * Each block lives in /blocks/<name>/ with block.json, render.php, index.js (editor) and view.js (front end).
 * The Animated Graphic block is in graphic/block/ (see graphic/graphic.php).
 *
 *   1. Editor data + block registration
 *   2. VuloPilot block category
 *   3. Helpers: vp_block_wrapper(), vp_lines()
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

/* -------------------------------------------------------------------------
 * 1. Editor data + block registration
 * ---------------------------------------------------------------------- */
add_action(
	'init',
	function () {
		// Data the block editors need (graphic list, icons, checklist presets, menus).
		wp_register_script( 'vp-editor-data', false, array(), VP_VERSION, false );
		$site = vp_data( 'site' );
		wp_add_inline_script(
			'vp-editor-data',
			'window.VP_EDITOR=' . wp_json_encode(
				array(
					'registry'   => vp_registry(),
					'icons'      => vp_icon_names(),
					'checklists' => $site['resources'] ?? array(),
				)
			) . ';'
		);
		foreach ( array( 'compare-matrix', 'checklist', 'mega-menu' ) as $b ) {
			register_block_type( VP_DIR . '/blocks/' . $b );
		}
	}
);

// Editable blocks with shared editor controls (repeater, icon, image, colours) from vp-block-kit.js.
add_action(
	'init',
	function () {
		wp_register_script( 'vp-block-kit', VP_URI . '/assets/js/vp-block-kit.js', array( 'wp-element', 'wp-components', 'wp-block-editor', 'wp-i18n', 'wp-server-side-render', 'vp-editor-data' ), VP_VERSION . '.' . filemtime( VP_DIR . '/assets/js/vp-block-kit.js' ), false );
		$screens = array();
		foreach ( vp_screens() as $k => $sc ) {
			$screens[] = array( 'key' => $k, 'title' => $sc['title'] );
		}
		wp_add_inline_script( 'vp-block-kit', 'window.VP_EDITOR=window.VP_EDITOR||{};window.VP_EDITOR.screens=' . wp_json_encode( $screens ) . ';', 'before' );

		foreach ( array( 'how-stepper', 'product-tour' ) as $b ) {
			register_block_type( VP_DIR . '/blocks/' . $b );
		}
	},
	11
);

/* -------------------------------------------------------------------------
 * 2. VuloPilot block category
 * ---------------------------------------------------------------------- */

add_filter(
	'block_categories_all',
	function ( $cats ) {
		array_unshift( $cats, array( 'slug' => 'vulopilot', 'title' => 'VuloPilot', 'icon' => null ) );
		return $cats;
	}
);

/* -------------------------------------------------------------------------
 * 3. Helpers
 * ---------------------------------------------------------------------- */

/**
 * Wrapper attributes for a VuloPilot block: design tokens become CSS variables,
 * so every Tailwind colour / radius inside the block follows the block's Style settings.
 */
function vp_block_wrapper( $a, $extra_class = '' ) {
	$map  = array(
		'colorPrimary'     => '--sage',
		'colorPrimarySoft' => '--sage-soft',
		'colorAccent'      => '--flare',
		'colorBg'          => '--bg',
		'colorSurface'     => '--surface',
		'colorText'        => '--ink',
		'colorMuted'       => '--muted',
		'colorBorder'      => '--line',
	);
	$css = '';
	foreach ( $map as $k => $var ) {
		if ( ! empty( $a[ $k ] ) ) {
			$css .= $var . ':' . $a[ $k ] . ';';
		}
	}
	if ( ! empty( $a['colorPrimary'] ) && empty( $a['colorPrimarySoft'] ) ) {
		$css .= '--sage-soft:color-mix(in oklab,' . $a['colorPrimary'] . ' 14%,#fff);';
	}
	if ( isset( $a['radius'] ) && '' !== $a['radius'] && null !== $a['radius'] ) {
		$css .= '--radius-sm:' . (int) $a['radius'] . 'px;';
	}
	if ( ! empty( $a['fontSize'] ) ) {
		$css .= 'font-size:' . (int) $a['fontSize'] . 'px;';
	}
	return get_block_wrapper_attributes( array( 'class' => trim( 'vp-scope ' . $extra_class ), 'style' => $css ) );
}

/** Split a textarea value into trimmed non-empty lines. */
function vp_lines( $text ) {
	if ( is_array( $text ) ) {
		return array_values( array_filter( array_map( 'trim', $text ), 'strlen' ) );
	}
	return array_values( array_filter( array_map( 'trim', preg_split( "/\r\n|\n|\r/", (string) $text ) ), 'strlen' ) );
}
