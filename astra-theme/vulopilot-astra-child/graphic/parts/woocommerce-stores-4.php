<?php
/**
 * Woocommerce Stores › graphic 4: KPI tiles with sparklines.
 * Graphic name: woocommerce-stores-4  ·  Shortcode: [vp_woocommerce_stores_4]  ·  /for/woocommerce-stores/
 * Scene: graphic/parts/scenes/kpi.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="woocommerce-stores-4-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Store results',
		'path' => 'After 60 days',
		'tiles' => array(
			array( 'Revenue', '£54k', '+22%', 'good', array( 40, 41, 43, 44, 47, 50, 52, 54 ) ),
			array( 'Conversion', '2.1%', '+0.6 pts', 'good', array( 1.5, 1.5, 1.6, 1.7, 1.8, 1.9, 2, 2.1 ) ),
			array(
				'Product clicks from Google',
				'8.9k',
				'+35%',
				'good',
				array( 6.4, 6.5, 6.9, 7.3, 7.8, 8.2, 8.6, 8.9 ),
			),
			array( 'Checkout load', '1.8 s', '-3.6 s', 'good', array( 5.4, 5.3, 5.2, 2.6, 2, 1.9, 1.8, 1.8 ) ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'kpi', $d );
