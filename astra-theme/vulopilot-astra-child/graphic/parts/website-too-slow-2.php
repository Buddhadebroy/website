<?php
/**
 * Website Too Slow › graphic 2: speed score gauge.
 * Graphic name: website-too-slow-2  ·  Shortcode: [vp_website_too_slow_2]  ·  /problems/website-too-slow/
 * Scene: graphic/parts/scenes/speed.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="website-too-slow-2-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Speed score',
		'path' => 'Homepage',
		'from' => 22,
		'to' => 88,
		'before' => 'Today',
		'after' => 'After images + cache',
		'device' => 'Mobile',
		'stats' => array( array( 'First response', '2.2 s', '0.3 s' ), array( 'Fully loaded', '9.8 s', '2.1 s' ) ),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'speed', $d );
