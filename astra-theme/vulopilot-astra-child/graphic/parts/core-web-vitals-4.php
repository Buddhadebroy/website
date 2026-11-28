<?php
/**
 * Core Web Vitals › graphic 4: trend chart with the moment the fix shipped.
 * Graphic name: core-web-vitals-4  ·  Shortcode: [vp_core_web_vitals_4]  ·  /solutions/core-web-vitals/
 * Scene: graphic/parts/scenes/traffic.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="core-web-vitals-4-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'LCP over 28 days',
		'path' => 'Field data',
		'stat_label' => 'LCP (75th percentile)',
		'stat' => 1.8,
		'stat_suffix' => ' s',
		'delta' => '-2.1 s',
		'delta_state' => 'good',
		'points' => array( 3.9, 3.9, 3.8, 3.9, 3.6, 2.9, 2.4, 2.1, 1.9, 1.8, 1.8, 1.8 ),
		'marker' => 4,
		'marker_label' => 'Fix applied',
		'axis' => array( 'Day 1', 'Day 14', 'Day 28' ),
		'legend' => array( array( 'Under 2.5 s counts as good', 'good' ) ),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'traffic', $d );
