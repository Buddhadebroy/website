<?php
/**
 * Graphic: Soft colour blob (parallax)
 *
 * Props: class (size, position, colour, blur), speed (parallax speed).
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$cls = isset( $a['class'] ) ? $a['class'] : 'vm:pointer-events-none vm:absolute vm:right-0 vm:top-0 vm:h-[480px] vm:w-[480px] vm:rounded-full vm:bg-blue/10 vm:blur-3xl';
echo '<div class="' . esc_attr( $cls ) . '" data-vm-parallax="' . esc_attr( isset( $a['speed'] ) ? (string) $a['speed'] : '0.2' ) . '"></div>';
