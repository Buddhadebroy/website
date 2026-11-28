<?php
/**
 * Optimize Woocommerce › graphic 4: KPI tiles with sparklines.
 * Graphic name: optimize-woocommerce-4  ·  Shortcode: [vp_optimize_woocommerce_4]  ·  /use-cases/optimize-woocommerce/
 * Scene: graphic/parts/scenes/kpi.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="optimize-woocommerce-4-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Store numbers',
		'path' => 'After 30 days',
		'tiles' => array(
			array( 'Conversion rate', '2.5%', '+0.9 pts', 'good', array( 1.6, 1.6, 1.7, 1.9, 2.1, 2.3, 2.4, 2.5 ) ),
			array( 'Cart abandonment', '58%', '-11 pts', 'good', array( 69, 69, 68, 66, 63, 61, 59, 58 ) ),
			array( 'Revenue', '£18.4k', '+31%', 'good', array( 12, 12.5, 13, 14, 15.5, 16.5, 17.6, 18.4 ) ),
			array( 'Checkout load', '1.9 s', '-4.3 s', 'good', array( 6.2, 6, 5.8, 3, 2.2, 2, 1.9, 1.9 ) ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'kpi', $d );
