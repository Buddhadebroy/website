<?php
/**
 * Site Menu (VuloForm).
 *   part "links"   desktop pill links (md and up) + the phone menu panel (opened by the burger)
 *   part "toggle"  the burger button (phones)
 * Links: the menu assigned to Appearance → Menus → "VuloForm header" (top level items), else vf_nav_links().
 * The last item of the phone menu is a copy of the header button (the Kadence button with class vf-nav-cta),
 * added by assets/js/vf-site.js, so you change the button in one place.
 *
 * @var array $attributes
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;

$part = ( $attributes['part'] ?? 'links' ) === 'toggle' ? 'toggle' : 'links';
if ( 'toggle' === $part ) {
	echo '<button type="button" ' . get_block_wrapper_attributes( array( 'class' => 'vf-burger' ) ) . ' aria-label="' . esc_attr__( 'Menu', 'vuloform' ) . '" aria-expanded="false" aria-controls="vf-mobile-menu"><span class="vf-burger-box"><span class="vf-burger-a"></span><span class="vf-burger-b"></span></span></button>'; // phpcs:ignore
	return;
}

$links = vf_menu_links( $attributes['location'] ?? 'vf_header' );
$items = '';
foreach ( $links as $l ) {
	$items .= '<li><a href="' . esc_url( vf_url( $l[0] ) ) . '">' . esc_html( $l[1] ) . '</a></li>';
}
echo '<div ' . get_block_wrapper_attributes( array( 'class' => 'vf-menu' ) ) . '>' // phpcs:ignore
	. '<nav aria-label="' . esc_attr__( 'Main', 'vuloform' ) . '"><ul class="vf-nav-links">' . $items . '</ul></nav>' // phpcs:ignore
	. '<ul id="vf-mobile-menu" class="vf-mobile" hidden>' . $items . '</ul>' // phpcs:ignore
	. '</div>';
