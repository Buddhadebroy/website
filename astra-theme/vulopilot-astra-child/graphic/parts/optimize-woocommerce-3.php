<?php
/**
 * Optimize Woocommerce › graphic 3: product page check.
 * Graphic name: optimize-woocommerce-3  ·  Shortcode: [vp_optimize_woocommerce_3]  ·  /use-cases/optimize-woocommerce/
 * Scene: graphic/parts/scenes/product.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="optimize-woocommerce-3-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Product page',
		'path' => '/product/oak-planter/',
		'name' => 'Oak Planter, Large',
		'reviews' => '84 reviews',
		'price' => '£89.00',
		'img_icon' => 'package',
		'checks' => array(
			array( 'Image weight', '3.2 MB', 'bad' ),
			array( 'Variation dropdown', '11 options', 'warn' ),
			array( 'Shipping info above the fold', 'Missing', 'bad' ),
			array( 'Product schema', 'Valid', 'good' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'product', $d );
