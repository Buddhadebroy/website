<?php
/**
 * Animated Graphic (Notifima) block – same output as [nf_graphic]. The graphic is printed without a wrapper
 * so it takes the same place in the layout as in the original site. Additional CSS class / max width add one.
 *
 * @var array $attributes
 * @package Notifima
 */

defined( 'ABSPATH' ) || exit;

$w = (int) ( $attributes['maxWidth'] ?? 0 );
echo nf_graphic( $attributes['name'] ?? 'home-stage', array( 'class' => $attributes['className'] ?? '', 'style' => $w ? "max-width:{$w}px;margin-inline:auto" : '' ) ); // phpcs:ignore
