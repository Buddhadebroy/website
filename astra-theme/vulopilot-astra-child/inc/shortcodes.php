<?php
/**
 * Site shortcodes (the graphic shortcodes are in graphic/render.php).
 *
 *   [vp_consult_button text="Request a free consultation"]
 *   [vp_mega_menu]   the header mega menu (e.g. inside a page builder header)
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

/** Common wrapper options from shortcode attributes: max_width="768" and class="my-class". */
function vp_wrap_opts( $a ) {
	$style = '';
	if ( ! empty( $a['max_width'] ) ) {
		$style = 'max-width:' . (int) $a['max_width'] . 'px;margin-left:auto;margin-right:auto;';
	}
	return array( 'class' => sanitize_html_class_list( $a['class'] ?? '' ), 'style' => $style );
}

/** sanitize_html_class for a space separated list. */
function sanitize_html_class_list( $list ) {
	return implode( ' ', array_filter( array_map( 'sanitize_html_class', preg_split( '/\s+/', (string) $list ) ) ) );
}

add_action(
	'init',
	function () {
		// [vp_consult_button text="…" class="…"]
		add_shortcode(
			'vp_consult_button',
			function ( $a ) {
				$a   = shortcode_atts( array( 'text' => __( 'Request a free consultation', 'vulopilot' ), 'class' => '' ), $a );
				$cls = $a['class'] ? sanitize_html_class_list( $a['class'] ) : 'rounded-sm border border-line bg-surface px-4 py-2 text-sm font-semibold text-sage transition hover:border-sage';
				return '<span class="vp-scope"><button type="button" class="vp-open-consult ' . esc_attr( $cls ) . '">' . esc_html( $a['text'] ) . '</button></span>';
			}
		);

		// [vp_mega_menu menu="" fixed="no" cta="yes"]
		add_shortcode(
			'vp_mega_menu',
			function ( $a ) {
				$a = shortcode_atts( array( 'menu' => '', 'fixed' => 'no', 'cta' => 'yes' ), $a );
				return vp_render_header( array( 'menu' => $a['menu'] ? $a['menu'] : null, 'fixed' => 'yes' === $a['fixed'], 'cta' => 'yes' === $a['cta'] ) );
			}
		);
	}
);
