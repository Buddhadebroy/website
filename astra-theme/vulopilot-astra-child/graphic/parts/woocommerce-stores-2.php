<?php
/**
 * Woocommerce Stores › graphic 2: store funnel.
 * Graphic name: woocommerce-stores-2  ·  Shortcode: [vp_woocommerce_stores_2]  ·  /for/woocommerce-stores/
 * Scene: graphic/parts/scenes/funnel.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="woocommerce-stores-2-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Store funnel',
		'path' => 'Last 30 days',
		'steps' => array(
			array( 'Sessions', 42000, '' ),
			array( 'Product views', 17600, '' ),
			array( 'Add to cart', 2900, '' ),
			array( 'Checkout', 1700, '' ),
			array( 'Orders', 890, '-48% at checkout' ),
		),
		'note' => 'Checkout loads in 5.4 s on mobile. The fix is ready for your approval.',
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'funnel', $d );
