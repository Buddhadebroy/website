<?php
/**
 * Woocommerce Seo › graphic 3: URLs with their status codes.
 * Graphic name: woocommerce-seo-3  ·  Shortcode: [vp_woocommerce_seo_3]  ·  /solutions/woocommerce-seo/
 * Scene: graphic/parts/scenes/sitemap.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="woocommerce-seo-3-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Product URLs',
		'path' => 'Product sitemap',
		'rows' => array(
			array( '/product/lemon-drizzle-cake/', '200', 'Indexed' ),
			array( '/product/red-velvet/', '404', 'Deleted when it sold out' ),
			array( '/product/carrot-cake/?color=', 'noindex', 'Filter page, kept out on purpose' ),
			array( '/product/vegan-chocolate/', '301', 'Redirected to new URL' ),
		),
		'ping' => 'Sold-out products stay live with "back soon" instead of a 404',
		'ping_tag' => 'Fixed',
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'sitemap', $d );
