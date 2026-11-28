<?php
/**
 * Seo Audit › graphic 2: trend chart with the moment the fix shipped.
 * Graphic name: seo-audit-2  ·  Shortcode: [vp_seo_audit_2]  ·  /solutions/seo-audit/
 * Scene: graphic/parts/scenes/traffic.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="seo-audit-2-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Audit score history',
		'path' => 'Last 12 weeks',
		'stat_label' => 'Audit score',
		'stat' => 88,
		'stat_suffix' => '/100',
		'delta' => '+24 since week 1',
		'delta_state' => 'good',
		'points' => array( 64, 63, 65, 64, 66, 71, 74, 78, 80, 83, 86, 88 ),
		'marker' => 4,
		'marker_label' => 'Fixes started',
		'axis' => array( 'Week 1', 'Week 6', 'Week 12' ),
		'legend' => array( array( 'Score after each weekly scan', 'good' ) ),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'traffic', $d );
