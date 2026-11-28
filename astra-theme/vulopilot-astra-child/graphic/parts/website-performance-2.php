<?php
/**
 * Website Performance › graphic 2: request waterfall.
 * Graphic name: website-performance-2  ·  Shortcode: [vp_website_performance_2]  ·  /solutions/website-performance/
 * Scene: graphic/parts/scenes/waterfall.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="website-performance-2-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'What loads first',
		'path' => 'Homepage',
		'scale' => '0 · 1s · 2s · 3s · 4s',
		'rows' => array(
			array( '/ (HTML)', '1.4s', 0, 30, 'warn' ),
			array( 'style.css', '90KB', 30, 8, 'good' ),
			array( 'slider.js', '420KB', 32, 22, 'bad' ),
			array( 'hero.jpg', '2.4MB', 34, 52, 'bad' ),
			array( 'font.woff2', '48KB', 38, 6, 'good' ),
			array( 'chat-widget.js', '310KB', 55, 30, 'warn' ),
		),
		'stats' => array(
			array( 'Biggest file', 'hero.jpg', 'bad' ),
			array( 'Blocking', '2 scripts', 'warn' ),
			array( 'Cached', 'No', 'bad' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'waterfall', $d );
