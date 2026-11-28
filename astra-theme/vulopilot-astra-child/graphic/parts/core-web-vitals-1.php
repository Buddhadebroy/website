<?php
/**
 * Core Web Vitals › hero: Core Web Vitals gauges.
 * Graphic name: core-web-vitals-1  ·  Shortcode: [vp_core_web_vitals_1]  ·  /solutions/core-web-vitals/
 * Scene: graphic/parts/scenes/vitals.php  ·  Hero: no page title inside, fills the column height.
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="core-web-vitals-1-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Core Web Vitals',
		'path' => 'Mobile · field data',
		'metrics' => array(
			array( 'LCP', '3.9', 's', 'bad', 78, 'Poor' ),
			array( 'INP', '240', 'ms', 'warn', 60, 'Needs work' ),
			array( 'CLS', '0.04', '', 'good', 20, 'Good' ),
		),
		'note' => 'LCP: how fast the main content shows. INP: how fast the page reacts to a tap. CLS: how much the layout jumps.',
		'foot' => 'Google uses these three numbers as a ranking signal.',
		'foot_icon' => 'gauge',
	),
	isset( $args ) ? $args : array(),
	true
);
vpx_scene( 'vitals', $d );
