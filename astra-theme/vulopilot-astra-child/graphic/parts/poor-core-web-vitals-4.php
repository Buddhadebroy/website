<?php
/**
 * Poor Core Web Vitals › graphic 4: the file or tag behind the problem, and the fix.
 * Graphic name: poor-core-web-vitals-4  ·  Shortcode: [vp_poor_core_web_vitals_4]  ·  /problems/poor-core-web-vitals/
 * Scene: graphic/parts/scenes/code.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="poor-core-web-vitals-4-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'The layout shift',
		'path' => 'Cookie banner',
		'tabs' => array( 'style.css' ),
		'lines' => array(
			array( 'com', '/* banner pushes the page down after load */' ),
			array( 'del', '.cookie-bar { position: relative; }' ),
			array( 'add', '.cookie-bar { position: fixed; bottom: 0; }' ),
			array( 'add', 'img { aspect-ratio: attr(width) / attr(height); }' ),
		),
		'notes' => array( array( 'CLS 0.22 → 0.03', 'good' ) ),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'code', $d );
