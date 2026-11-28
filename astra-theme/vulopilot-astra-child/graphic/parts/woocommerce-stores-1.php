<?php
/**
 * Woocommerce Stores › hero: product page check.
 * Graphic name: woocommerce-stores-1  ·  Shortcode: [vp_woocommerce_stores_1]  ·  /for/woocommerce-stores/
 * Scene: graphic/parts/scenes/product.php  ·  Hero: no page title inside, fills the column height.
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="woocommerce-stores-1-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Product page health',
		'path' => '/product/kids-balance-bike/',
		'name' => 'Kids Balance Bike, Blue',
		'reviews' => '212 reviews',
		'price' => '£119.00',
		'img_icon' => 'package',
		'checks' => array(
			array( 'Load time on mobile', '4.3 s', 'bad' ),
			array( 'Product schema (price, stock)', 'Missing', 'bad' ),
			array( 'Description', 'Copied from supplier', 'warn' ),
			array( 'Images', 'WebP, 380 KB', 'good' ),
			array( 'Reviews shown in Google', 'Yes', 'good' ),
		),
		'foot' => 'Every product checked for speed, search and sales problems.',
		'foot_icon' => 'shopping-bag',
	),
	isset( $args ) ? $args : array(),
	true
);
vpx_scene( 'product', $d );
