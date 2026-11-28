<?php
/**
 * Optimize Woocommerce › graphic 2: checkout timings.
 * Graphic name: optimize-woocommerce-2  ·  Shortcode: [vp_optimize_woocommerce_2]  ·  /use-cases/optimize-woocommerce/
 * Scene: graphic/parts/scenes/checkout.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="optimize-woocommerce-2-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Checkout speed',
		'path' => 'Mobile',
		'was' => 'Before',
		'now' => 'After',
		'rows' => array(
			array( 'Cart', 3.4, 1.2 ),
			array( 'Checkout', 6.2, 1.9 ),
			array( 'Payment', 2.8, 1.4 ),
			array( 'Thank-you page', 2.1, 0.8 ),
		),
		'note' => 'Cart fragments script disabled on non-shop pages, checkout excluded from heavy plugins.',
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'checkout', $d );
