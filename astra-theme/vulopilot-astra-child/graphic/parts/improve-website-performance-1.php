<?php
/**
 * Improve Website Performance › hero: request waterfall.
 * Graphic name: improve-website-performance-1  ·  Shortcode: [vp_improve_website_performance_1]  ·  /use-cases/improve-website-performance/
 * Scene: graphic/parts/scenes/waterfall.php  ·  Hero: no page title inside, fills the column height.
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="improve-website-performance-1-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Request waterfall',
		'path' => 'Homepage · mobile',
		'scale' => '0 · 1.5s · 3s · 4.5s · 6s',
		'rows' => array(
			array( '/ (HTML)', '1.9s', 0, 32, 'bad' ),
			array( 'jquery.min.js', '88KB', 32, 10, 'warn' ),
			array( 'page-builder.css', '640KB', 33, 20, 'bad' ),
			array( 'fonts (4 files)', '380KB', 40, 18, 'warn' ),
			array( 'hero-video.mp4', '8.2MB', 42, 58, 'bad' ),
			array( 'analytics.js', '45KB', 60, 8, 'good' ),
		),
		'stats' => array(
			array( 'Server', '1.9 s', 'bad' ),
			array( 'Unused CSS', '71%', 'bad' ),
			array( 'Total', '11.4 MB', 'bad' ),
		),
		'foot' => 'Each bar is a file. The long red ones are what VuloPilot fixes first.',
		'foot_icon' => 'zap',
	),
	isset( $args ) ? $args : array(),
	true
);
vpx_scene( 'waterfall', $d );
