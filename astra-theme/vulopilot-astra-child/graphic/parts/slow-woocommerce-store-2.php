<?php
/**
 * Slow Woocommerce Store › graphic 2: request waterfall.
 * Graphic name: slow-woocommerce-store-2  ·  Shortcode: [vp_slow_woocommerce_store_2]  ·  /problems/slow-woocommerce-store/
 * Scene: graphic/parts/scenes/waterfall.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="slow-woocommerce-store-2-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Why checkout is slow',
		'path' => '/checkout/',
		'scale' => '0 · 2s · 4s · 6s · 8s',
		'rows' => array(
			array( '/checkout/ (HTML)', '2.9s', 0, 36, 'bad' ),
			array( 'wc-cart-fragments', '1.1s', 36, 14, 'bad' ),
			array( '12 plugin scripts', '780KB', 38, 30, 'warn' ),
			array( 'payment.js', '140KB', 50, 10, 'good' ),
			array( 'upsell-popup.js', '420KB', 60, 24, 'bad' ),
		),
		'stats' => array(
			array( 'Uncached queries', '186', 'bad' ),
			array( 'Plugin scripts', '12', 'warn' ),
			array( 'Server', '2.9 s', 'bad' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'waterfall', $d );
