<?php
/**
 * Graphic: Phone with messages
 *
 * Props: title, messages ([[from, text], …]), parallax (speed), wrap (classes around it).
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$msgs  = isset( $a['messages'] ) ? $a['messages'] : array( array( 'New order', 'New order #1042 from Ava: $86.00.' ) );
$phone = vm_phone( $msgs, isset( $a['title'] ) ? $a['title'] : 'Messages' );
if ( isset( $a['parallax'] ) ) {
	$phone = '<div data-vm-parallax="' . esc_attr( (string) $a['parallax'] ) . '">' . $phone . '</div>';
}
if ( ! empty( $a['wrap'] ) ) {
	$phone = '<div class="' . esc_attr( $a['wrap'] ) . '">' . $phone . '</div>';
}
echo $phone; // phpcs:ignore
