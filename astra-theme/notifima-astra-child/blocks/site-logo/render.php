<?php
/**
 * Site Logo (Notifima): Customize → Site Identity → Logo (+ Astra's retina logo).
 * Without a logo image it prints the site title as the wordmark ("Notifima" + purple ring), like the original.
 *   width  logo image width in px (height follows the image)
 *   dot    show the purple ring after the wordmark
 *   link   link to the home page
 *
 * @var array $attributes
 * @package Notifima
 */

defined( 'ABSPATH' ) || exit;

$a     = wp_parse_args( $attributes, array( 'width' => 130, 'dot' => true, 'link' => true ) );
$name  = get_bloginfo( 'name' );
$logo  = (int) get_theme_mod( 'custom_logo' );
$inner = '';
if ( $logo ) {
	$src = wp_get_attachment_image_src( $logo, 'full' );
	if ( $src ) {
		$w      = max( 8, (int) $a['width'] );
		$h      = $src[1] ? (int) round( $src[2] * $w / $src[1] ) : $w;
		$retina = function_exists( 'astra_get_option' ) ? (string) astra_get_option( 'ast-header-retina-logo' ) : '';
		$srcset = $retina ? ' srcset="' . esc_url( $src[0] ) . ' 1x, ' . esc_url( $retina ) . ' 2x"' : '';
		$inner  = '<img class="nf-logo-img" src="' . esc_url( $src[0] ) . '"' . $srcset . ' width="' . $w . '" height="' . $h . '" style="width:' . $w . 'px;height:auto" alt="' . esc_attr( $name ) . '">';
	}
}
if ( '' === $inner ) {
	$inner = esc_html( $name ) . ( $a['dot'] ? '<span class="logo-dot"></span>' : '' );
}
$attrs = get_block_wrapper_attributes( array( 'class' => 'logo' ) );
if ( $a['link'] ) {
	/* translators: %s: site name */
	echo '<a ' . $attrs . ' href="' . esc_url( home_url( '/' ) ) . '" aria-label="' . esc_attr( sprintf( __( '%s home', 'notifima' ), $name ) ) . '" rel="home">' . $inner . '</a>'; // phpcs:ignore
} else {
	echo '<span ' . $attrs . '>' . $inner . '</span>'; // phpcs:ignore
}
