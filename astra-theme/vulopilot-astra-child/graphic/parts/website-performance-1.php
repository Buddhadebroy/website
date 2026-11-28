<?php
/**
 * Website Performance › hero: speed score gauge.
 * Graphic name: website-performance-1  ·  Shortcode: [vp_website_performance_1]  ·  /solutions/website-performance/
 * Scene: graphic/parts/scenes/speed.php  ·  Hero: no page title inside, fills the column height.
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="website-performance-1-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Speed score',
		'path' => 'Performance → Overview',
		'from' => 38,
		'to' => 91,
		'before' => 'Today',
		'after' => 'After 3 fixes',
		'device' => 'Mobile',
		'stats' => array(
			array( 'Load time', '5.2 s', '1.6 s' ),
			array( 'Page weight', '6.8 MB', '1.1 MB' ),
			array( 'Requests', '148', '61' ),
			array( 'Server response', '1.4 s', '0.3 s' ),
		),
		'foot' => 'Fixes are ranked by how many seconds each one saves.',
		'foot_icon' => 'zap',
	),
	isset( $args ) ? $args : array(),
	true
);
vpx_scene( 'speed', $d );
