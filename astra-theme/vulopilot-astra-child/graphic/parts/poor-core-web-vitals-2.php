<?php
/**
 * Poor Core Web Vitals › graphic 2: Core Web Vitals gauges.
 * Graphic name: poor-core-web-vitals-2  ·  Shortcode: [vp_poor_core_web_vitals_2]  ·  /problems/poor-core-web-vitals/
 * Scene: graphic/parts/scenes/vitals.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="poor-core-web-vitals-2-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Search Console report',
		'path' => 'Mobile · 28 days',
		'metrics' => array(
			array( 'LCP', '4.4', 's', 'bad', 85, 'Poor' ),
			array( 'INP', '380', 'ms', 'bad', 80, 'Poor' ),
			array( 'CLS', '0.22', '', 'warn', 62, 'Needs work' ),
		),
		'note' => 'Field data from real visitors on Chrome. All three must be good to pass.',
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'vitals', $d );
