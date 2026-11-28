<?php
/**
 * Slow Woocommerce Store › hero: checkout timings.
 * Graphic name: slow-woocommerce-store-1  ·  Shortcode: [vp_slow_woocommerce_store_1]  ·  /problems/slow-woocommerce-store/
 * Scene: graphic/parts/scenes/checkout.php  ·  Hero: no page title inside, fills the column height.
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="slow-woocommerce-store-1-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Shop page timings',
		'path' => 'Mobile',
		'was' => 'Today',
		'now' => 'After fixes',
		'rows' => array(
			array( 'Shop page', 5.8, 1.7 ),
			array( 'Product page', 4.9, 1.5 ),
			array( 'Add to cart', 2.6, 0.6 ),
			array( 'Checkout', 7.1, 2.0 ),
		),
		'note' => 'The checkout is the slowest page on the site, and the one that makes the money.',
		'foot' => 'Every extra second at checkout costs orders.',
		'foot_icon' => 'credit-card',
	),
	isset( $args ) ? $args : array(),
	true
);
vpx_scene( 'checkout', $d );
