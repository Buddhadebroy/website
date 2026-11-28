<?php
/**
 * Improve Website Performance › graphic 2: speed score gauge.
 * Graphic name: improve-website-performance-2  ·  Shortcode: [vp_improve_website_performance_2]  ·  /use-cases/improve-website-performance/
 * Scene: graphic/parts/scenes/speed.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="improve-website-performance-2-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Speed score',
		'path' => 'Homepage',
		'from' => 29,
		'to' => 84,
		'before' => 'Before',
		'after' => 'After page cache + images',
		'device' => 'Mobile',
		'stats' => array( array( 'Page cache', 'Off', 'On' ), array( 'Hero video', '8.2 MB', 'Poster image' ) ),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'speed', $d );
