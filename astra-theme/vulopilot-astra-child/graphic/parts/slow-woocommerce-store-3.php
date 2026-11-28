<?php
/**
 * Slow Woocommerce Store › graphic 3: product page check.
 * Graphic name: slow-woocommerce-store-3  ·  Shortcode: [vp_slow_woocommerce_store_3]  ·  /problems/slow-woocommerce-store/
 * Scene: graphic/parts/scenes/product.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="slow-woocommerce-store-3-classic"].
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
			array( 'Gallery images', '14.6 MB', 'bad' ),
			array( 'Related products query', '1.4 s', 'bad' ),
			array( 'Reviews loaded on scroll', 'Fixed', 'good' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'product', $d );
