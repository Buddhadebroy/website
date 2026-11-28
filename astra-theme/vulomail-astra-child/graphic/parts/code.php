<?php
/**
 * Graphic: Code card
 *
 * Props: code, title, parallax (speed, optional), reveal (delay in s, optional).
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$html = vm_code( isset( $a['code'] ) ? (string) $a['code'] : '', isset( $a['title'] ) ? (string) $a['title'] : '' );
if ( isset( $a['reveal'] ) ) {
	$html = '<div data-vm-reveal data-delay="' . esc_attr( (string) $a['reveal'] ) . '">' . $html . '</div>';
}
if ( isset( $a['parallax'] ) ) {
	$html = '<div data-vm-parallax="' . esc_attr( (string) $a['parallax'] ) . '">' . $html . '</div>';
}
echo $html; // phpcs:ignore
