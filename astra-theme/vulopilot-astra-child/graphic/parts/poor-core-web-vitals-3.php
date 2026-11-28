<?php
/**
 * Poor Core Web Vitals › graphic 3: request waterfall.
 * Graphic name: poor-core-web-vitals-3  ·  Shortcode: [vp_poor_core_web_vitals_3]  ·  /problems/poor-core-web-vitals/
 * Scene: graphic/parts/scenes/waterfall.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="poor-core-web-vitals-3-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Why INP is slow',
		'path' => 'Main thread on a tap',
		'col' => 'Task',
		'scale' => '0 · 100 · 200 · 300 · 400 ms',
		'rows' => array(
			array( 'Tap on "Book now"', '', 0, 6, 'good' ),
			array( 'page-builder.js', '190ms', 6, 48, 'bad' ),
			array( 'chat-widget.js', '110ms', 54, 28, 'bad' ),
			array( 'analytics', '40ms', 82, 10, 'warn' ),
			array( 'Next paint', '', 92, 6, 'good' ),
		),
		'stats' => array(
			array( 'INP', '380 ms', 'bad' ),
			array( 'Target', '< 200 ms', 'good' ),
			array( 'Main cause', 'Builder JS', 'bad' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'waterfall', $d );
