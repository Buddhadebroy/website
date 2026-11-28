<?php
/**
 * Woocommerce Seo › graphic 4: product page check.
 * Graphic name: woocommerce-seo-4  ·  Shortcode: [vp_woocommerce_seo_4]  ·  /solutions/woocommerce-seo/
 * Scene: graphic/parts/scenes/product.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="woocommerce-seo-4-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Product page check',
		'path' => 'Lemon Drizzle Cake',
		'name' => 'Lemon Drizzle Cake',
		'reviews' => '126 reviews',
		'price' => '£34.00',
		'img_icon' => 'package',
		'checks' => array(
			array( 'Unique description', 'Supplier copy', 'bad' ),
			array( 'Main image size', '1.9 MB', 'bad' ),
			array( 'Product schema', 'Missing', 'bad' ),
			array( 'Title length', '52 chars', 'good' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'product', $d );
