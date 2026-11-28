<?php
/**
 * Site Logo (VuloForm): Customize → Site Identity → Logo (+ Astra's retina logo) and the site title.
 *   showTitle  print the site title next to the logo (turn off when your logo image already has the name)
 *   accent     the end of the title shown in brand blue ("Vulo" + "Form")
 *   width      logo width in px (the original: 34; the height follows the image)
 *   link       link to the home page
 *
 * @var array $attributes
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;

$a      = wp_parse_args( $attributes, array( 'showTitle' => true, 'accent' => 'Form', 'width' => 34, 'link' => true ) );
$name   = get_bloginfo( 'name' );
$logo   = (int) get_theme_mod( 'custom_logo' );
$img    = '';
if ( $logo ) {
	$src = wp_get_attachment_image_src( $logo, 'full' );
	if ( $src ) {
		$w      = max( 8, (int) $a['width'] );
		$h      = $src[1] ? (int) round( $src[2] * $w / $src[1] ) : $w;
		$retina = function_exists( 'astra_get_option' ) ? (string) astra_get_option( 'ast-header-retina-logo' ) : '';
		$srcset = $retina ? ' srcset="' . esc_url( $src[0] ) . ' 1x, ' . esc_url( $retina ) . ' 2x"' : '';
		$img    = '<img class="vf-logo-img" src="' . esc_url( $src[0] ) . '"' . $srcset . ' width="' . $w . '" height="' . $h . '" style="width:' . $w . 'px" alt="' . ( $a['showTitle'] ? '' : esc_attr( $name ) ) . '"' . ( $a['showTitle'] ? ' aria-hidden="true"' : '' ) . '>';
	}
}
$title = '';
if ( $a['showTitle'] || ! $img ) {
	$acc   = (string) $a['accent'];
	$title = esc_html( $name );
	if ( '' !== $acc && strlen( $name ) > strlen( $acc ) && substr( $name, -strlen( $acc ) ) === $acc ) {
		$title = esc_html( substr( $name, 0, -strlen( $acc ) ) ) . '<span class="vf-logo-accent">' . esc_html( $acc ) . '</span>';
	}
	$title = '<span class="vf-logo-title">' . $title . '</span>';
}
$inner = '<span class="vf-logo-inner">' . $img . $title . '</span>';
$attrs = get_block_wrapper_attributes( array( 'class' => 'vf-logo' ) );
if ( $a['link'] ) {
	/* translators: %s: site name */
	echo '<a ' . $attrs . ' href="' . esc_url( home_url( '/' ) ) . '" aria-label="' . esc_attr( sprintf( __( '%s home', 'vuloform' ), $name ) ) . '" rel="home">' . $inner . '</a>'; // phpcs:ignore
} else {
	echo '<span ' . $attrs . '>' . $inner . '</span>'; // phpcs:ignore
}
