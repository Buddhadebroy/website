<?php
/**
 * Optimize Woocommerce › hero: store funnel.
 * Graphic name: optimize-woocommerce-1  ·  Shortcode: [vp_optimize_woocommerce_1]  ·  /use-cases/optimize-woocommerce/
 * Scene: graphic/parts/scenes/funnel.php  ·  Hero: no page title inside, fills the column height.
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="optimize-woocommerce-1-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Store funnel',
		'path' => 'Last 30 days',
		'steps' => array(
			array( 'Product views', 24800, '' ),
			array( 'Added to cart', 3120, '' ),
			array( 'Started checkout', 1980, '' ),
			array( 'Orders', 610, '-69% at checkout' ),
		),
		'note' => 'Most shoppers leave on the checkout page. It takes 6.2 s to load on mobile.',
		'foot' => 'VuloPilot finds where sales leak, then what is causing it.',
		'foot_icon' => 'shopping-cart',
	),
	isset( $args ) ? $args : array(),
	true
);
vpx_scene( 'funnel', $d );
