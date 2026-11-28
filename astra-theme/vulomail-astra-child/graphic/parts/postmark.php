<?php
/**
 * Graphic: Postmark (rotating stamp)
 *
 * Props: size (px), label, parallax (speed, optional), rotate (deg over the scroll), light (bool),
 * class (wrapper classes; default positions it like the inner-page hero).
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$size  = isset( $a['size'] ) ? (int) $a['size'] : 180;
$label = isset( $a['label'] ) ? (string) $a['label'] : 'Delivered';
$light = ! empty( $a['light'] );
$pm    = vm_postmark( $size, $label, $light ? 'vm:[&_svg]:text-paper/80' : '' );
if ( isset( $a['parallax'] ) ) {
	$cls = isset( $a['class'] ) ? $a['class'] : ( $light ? 'vm:absolute vm:-right-10 vm:-top-10 vm:opacity-30' : 'vm:pointer-events-none vm:absolute vm:-right-24 vm:top-24 vm:hidden vm:md:block' );
	echo '<div class="' . esc_attr( $cls ) . '" data-vm-parallax="' . esc_attr( (string) $a['parallax'] ) . '" data-vm-rotate="' . esc_attr( isset( $a['rotate'] ) ? (string) $a['rotate'] : '0' ) . '">' . $pm . '</div>'; // phpcs:ignore
} else {
	echo $pm; // phpcs:ignore
}
