<?php
/**
 * Graphic: Icon (Lucide)
 *
 * Props: name (lucide icon name), class (icon classes), box (optional wrapper classes).
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$svg = vm_icon( isset( $a['name'] ) ? $a['name'] : 'mail', isset( $a['class'] ) ? $a['class'] : 'vm:h-5 vm:w-5' );
echo ! empty( $a['box'] ) ? '<span class="' . esc_attr( $a['box'] ) . '">' . $svg . '</span>' : $svg; // phpcs:ignore
