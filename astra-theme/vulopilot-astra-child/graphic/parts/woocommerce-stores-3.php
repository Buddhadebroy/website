<?php
/**
 * Woocommerce Stores › graphic 3: structured data and the rich result it gives.
 * Graphic name: woocommerce-stores-3  ·  Shortcode: [vp_woocommerce_stores_3]  ·  /for/woocommerce-stores/
 * Scene: graphic/parts/scenes/schema.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="woocommerce-stores-3-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Products in Google',
		'path' => 'Rich results',
		'lines' => array(
			array( '{', '' ),
			array( '  "@type": "Product",', 'key' ),
			array( '  "name": "Kids Balance Bike",', '' ),
			array( '  "offers": { "price": "119.00",', 'add' ),
			array( '    "availability": "InStock" },', 'add' ),
			array( '  "aggregateRating": { "ratingValue": 4.7 }', 'add' ),
			array( '}', '' ),
		),
		'crumb' => 'kitecycles.example › kids › balance-bikes',
		'title' => 'Kids Balance Bike, Blue – Ages 2 to 5',
		'rating' => '4.7 · 212 reviews',
		'price' => '£119.00',
		'stock' => 'In stock · free delivery',
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'schema', $d );
