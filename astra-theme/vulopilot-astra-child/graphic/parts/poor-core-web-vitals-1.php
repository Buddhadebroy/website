<?php
/**
 * Poor Core Web Vitals › hero: loading filmstrip.
 * Graphic name: poor-core-web-vitals-1  ·  Shortcode: [vp_poor_core_web_vitals_1]  ·  /problems/poor-core-web-vitals/
 * Scene: graphic/parts/scenes/filmstrip.php  ·  Hero: no page title inside, fills the column height.
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="poor-core-web-vitals-1-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Your page loading',
		'path' => 'Mobile · 4G',
		'times' => array( '0.5s', '1.0s', '2.0s', '3.0s', '4.5s' ),
		'rows' => array(
			array( 'Today', array( 0, 0, 0, 1, 2 ), 'Main image appears at 4.4 s' ),
			array( 'After the fix', array( 1, 2, 2, 2, 2 ), 'Main image appears at 1.7 s' ),
		),
		'foot' => 'Visitors judge speed by when the main thing shows, not when loading ends.',
		'foot_icon' => 'timer',
	),
	isset( $args ) ? $args : array(),
	true
);
vpx_scene( 'filmstrip', $d );
