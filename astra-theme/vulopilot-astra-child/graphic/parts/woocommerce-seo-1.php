<?php
/**
 * Woocommerce Seo › hero: structured data and the rich result it gives.
 * Graphic name: woocommerce-seo-1  ·  Shortcode: [vp_woocommerce_seo_1]  ·  /solutions/woocommerce-seo/
 * Scene: graphic/parts/scenes/schema.php  ·  Hero: no page title inside, fills the column height.
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="woocommerce-seo-1-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Product rich result',
		'path' => '/product/lemon-drizzle-cake/',
		'lines' => array(
			array( '{', '' ),
			array( '  "@type": "Product",', 'key' ),
			array( '  "name": "Lemon Drizzle Cake",', '' ),
			array( '  "offers": { "price": "34.00",', 'add' ),
			array( '    "availability": "InStock" },', 'add' ),
			array( '  "aggregateRating": { "ratingValue": 4.8,', 'add' ),
			array( '    "reviewCount": 126 }', 'add' ),
			array( '}', '' ),
		),
		'crumb' => 'rosiebakes.co.uk › shop › cakes',
		'title' => 'Lemon Drizzle Cake – Rosie Bakes',
		'rating' => '4.8 · 126 reviews',
		'price' => '£34.00',
		'stock' => 'In stock',
		'foot' => 'Price, stock and stars in search results get more clicks than a plain blue link.',
		'foot_icon' => 'star',
	),
	isset( $args ) ? $args : array(),
	true
);
vpx_scene( 'schema', $d );
