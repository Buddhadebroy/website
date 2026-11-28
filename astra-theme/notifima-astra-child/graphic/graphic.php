<?php
/**
 * Graphic folder – loader.
 *
 *   graphic/parts/   one PHP file per graphic (every mockup, demo, card picture and form of the site);
 *                    the file name is the graphic name. Made by src/build-patterns.py from the original HTML.
 *   graphic/block/   the block "Animated Graphic (Notifima)" (notifima/graphic)
 *
 * Use a graphic anywhere: the block (pick one from the list), the shortcode [nf_graphic name="home-stage"],
 * or PHP: echo nf_graphic( 'home-stage' );
 * Add your own by dropping a PHP file into graphic/parts/ (first doc comment line = its label).
 *
 * @package Notifima
 */

defined( 'ABSPATH' ) || exit;

/** Every graphic: name => label. */
function nf_graphics() {
	static $list = null;
	if ( null === $list ) {
		$list = array();
		foreach ( (array) glob( NF_DIR . '/graphic/parts/*.php' ) as $file ) {
			$head = file_get_contents( $file, false, null, 0, 300 ); // phpcs:ignore
			$name = basename( $file, '.php' );
			$list[ $name ] = preg_match( '/\*\s+([^\n*][^\n]*)/', $head, $m ) ? trim( $m[1] ) : $name;
		}
		$list = apply_filters( 'nf_graphics', $list );
	}
	return $list;
}

/**
 * HTML of one graphic. It is printed as it is (no wrapper), so it sits in the layout exactly like the original
 * element; $opt['class'] / $opt['style'] add a wrapper with display:contents-free styling when given.
 */
function nf_graphic( $name, $opt = array() ) {
	$name = sanitize_key( $name );
	$file = NF_DIR . '/graphic/parts/' . $name . '.php';
	if ( ! file_exists( $file ) ) {
		return current_user_can( 'edit_posts' ) ? '<p style="color:#e11d48">Notifima: unknown graphic "' . esc_html( $name ) . '"</p>' : '';
	}
	ob_start();
	include $file;
	$html = trim( ob_get_clean() );
	if ( ! empty( $opt['class'] ) || ! empty( $opt['style'] ) ) {
		$html = '<div class="' . esc_attr( trim( 'nf-graphic ' . ( $opt['class'] ?? '' ) ) ) . '"' . ( empty( $opt['style'] ) ? '' : ' style="' . esc_attr( $opt['style'] ) . '"' ) . '>' . $html . '</div>';
	}
	return $html;
}

add_shortcode(
	'nf_graphic',
	function ( $a ) {
		$a = shortcode_atts( array( 'name' => '', 'max_width' => '', 'class' => '' ), $a );
		$w = (int) $a['max_width'];
		return nf_graphic( $a['name'], array( 'class' => sanitize_text_field( $a['class'] ), 'style' => $w ? "max-width:{$w}px;margin-inline:auto" : '' ) );
	}
);

add_action(
	'init',
	function () {
		register_block_type( NF_DIR . '/graphic/block' );
	}
);
add_action(
	'enqueue_block_editor_assets',
	function () {
		wp_add_inline_script( 'notifima-graphic-editor-script', 'window.NF_GRAPHICS=' . wp_json_encode( nf_graphics() ) . ';', 'before' );
	}
);
