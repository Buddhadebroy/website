<?php
/**
 * Slow Woocommerce Store › graphic 4: store funnel.
 * Graphic name: slow-woocommerce-store-4  ·  Shortcode: [vp_slow_woocommerce_store_4]  ·  /problems/slow-woocommerce-store/
 * Scene: graphic/parts/scenes/funnel.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="slow-woocommerce-store-4-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Where shoppers give up',
		'path' => 'Last 30 days',
		'steps' => array(
			array( 'Visited shop', 18400, '' ),
			array( 'Viewed product', 9200, '' ),
			array( 'Reached checkout', 1450, '' ),
			array( 'Paid', 380, '-74% at checkout' ),
		),
		'note' => 'Checkout drop-off fell to 52% once it loaded in 2 seconds.',
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'funnel', $d );
