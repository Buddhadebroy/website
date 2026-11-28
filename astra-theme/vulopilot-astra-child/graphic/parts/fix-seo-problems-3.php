<?php
/**
 * Fix Seo Problems › graphic 3: trend chart with the moment the fix shipped.
 * Graphic name: fix-seo-problems-3  ·  Shortcode: [vp_fix_seo_problems_3]  ·  /use-cases/fix-seo-problems/
 * Scene: graphic/parts/scenes/traffic.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="fix-seo-problems-3-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Clicks from Google',
		'path' => 'Search Console',
		'stat_label' => 'Clicks, last 28 days',
		'stat' => 1940,
		'delta' => '+62%',
		'delta_state' => 'good',
		'points' => array( 900, 940, 880, 920, 960, 1010, 1180, 1350, 1520, 1690, 1830, 1940 ),
		'marker' => 5,
		'marker_label' => 'Fixes applied',
		'axis' => array( 'Jan', 'Feb', 'Mar' ),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'traffic', $d );
