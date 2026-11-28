<?php
/**
 * Graphic folder – loader.
 *
 *   graphic/graphic.php   this file: vf_graphic(), the [vf_graphic] shortcode, the "Animated Graphic (VuloForm)" block
 *   graphic/parts/        one PHP file per graphic; the file name is the graphic name
 *   graphic/block/        the block (block name vuloform/graphic)
 *
 * Use a graphic anywhere:
 *   Block editor   add the block "Animated Graphic (VuloForm)" and pick one from the list
 *   Shortcode      [vf_graphic name="builder-app"]   optional: max_width="640" class="my-class"
 *   PHP            echo vf_graphic( 'builder-app' );
 *   Home sections  section-hero, section-templates, section-builder … (any file in template-parts/home/)
 *
 * Add your own by dropping a PHP file into graphic/parts/. Styles: assets/css/vf-site.css (section "Graphics"),
 * behaviour: assets/js/vf-site.js (data-vf="…" widgets).
 *
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;

/** Every graphic: name => label (from the first line of each file's doc comment). */
function vf_graphics() {
	static $list = null;
	if ( null === $list ) {
		$list = array();
		foreach ( glob( VF_DIR . '/graphic/parts/*.php' ) as $file ) {
			$head = file_get_contents( $file, false, null, 0, 400 ); // phpcs:ignore WordPress.WP.AlternativeFunctions
			$name = basename( $file, '.php' );
			$list[ $name ] = preg_match( '/\*\s+([^\n*][^\n]*)/', $head, $m ) ? trim( $m[1] ) : $name;
		}
		// Every Home section is a graphic too: section-hero, section-builder, section-privacy …
		foreach ( glob( VF_DIR . '/template-parts/home/*.php' ) as $file ) {
			$list[ 'section-' . basename( $file, '.php' ) ] = 'Home section: ' . basename( $file, '.php' );
		}
		$list = apply_filters( 'vf_graphics', $list );
	}
	return $list;
}

/**
 * HTML of one graphic, wrapped in .vf-scope (so Tailwind and effects apply anywhere).
 *
 * @param string $name Graphic name (file name in graphic/parts/).
 * @param array  $opt  class, style, args (passed to the part as $args).
 */
function vf_graphic( $name, $opt = array() ) {
	$name = sanitize_key( $name );
	$file = VF_DIR . '/graphic/parts/' . $name . '.php';
	if ( 0 === strpos( $name, 'section-' ) ) {
		$file = VF_DIR . '/template-parts/home/' . substr( $name, 8 ) . '.php';
	}
	if ( ! file_exists( $file ) ) {
		return current_user_can( 'edit_posts' ) ? '<p style="color:var(--red)">VuloForm: unknown graphic "' . esc_html( $name ) . '"</p>' : '';
	}
	ob_start();
	$args = $opt['args'] ?? array(); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals
	include $file;
	$html  = ob_get_clean();
	$class = trim( 'vf-scope vf-graphic vf-graphic-' . $name . ' ' . ( $opt['class'] ?? '' ) );
	$style = empty( $opt['style'] ) ? '' : ' style="' . esc_attr( $opt['style'] ) . '"';
	return '<div class="' . esc_attr( $class ) . '"' . $style . '>' . $html . '</div>';
}

// [vf_graphic name="…" max_width="" class=""]
add_shortcode(
	'vf_graphic',
	function ( $a ) {
		$a = shortcode_atts( array( 'name' => '', 'max_width' => '', 'class' => '' ), $a );
		$w = (int) $a['max_width'];
		return vf_graphic( $a['name'], array( 'class' => sanitize_text_field( $a['class'] ), 'style' => $w ? "max-width:{$w}px;margin-inline:auto" : '' ) );
	}
);

// Block "Animated Graphic (VuloForm)" (vuloform/graphic). The editor gets the list of graphics as VF_GRAPHICS.
add_action(
	'init',
	function () {
		register_block_type( VF_DIR . '/graphic/block' );
	}
);
add_action(
	'enqueue_block_editor_assets',
	function () {
		wp_add_inline_script( 'vuloform-graphic-editor-script', 'window.VF_GRAPHICS=' . wp_json_encode( vf_graphics() ) . ';', 'before' );
	}
);
