<?php
/**
 * Woocommerce Seo › graphic 2: findings sorted by impact.
 * Graphic name: woocommerce-seo-2  ·  Shortcode: [vp_woocommerce_seo_2]  ·  /solutions/woocommerce-seo/
 * Scene: graphic/parts/scenes/issues.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="woocommerce-seo-2-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Store SEO issues',
		'path' => 'Commerce',
		'meta' => 'Most lost sales first',
		'rows' => array(
			array( 'medium', '38 products share one description', 'Copied from the supplier', 2 ),
			array( 'critical', 'Out-of-stock products return 404', 'Rankings lost overnight', 0 ),
			array( 'high', 'No product schema', 'No price or stars in Google', 1 ),
			array( 'low', 'Category pages have no intro text', 'Thin pages', 3 ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'issues', $d );
