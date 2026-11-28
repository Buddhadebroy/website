<?php
/**
 * Pages Not Indexed › graphic 4: trend chart with the moment the fix shipped.
 * Graphic name: pages-not-indexed-4  ·  Shortcode: [vp_pages_not_indexed_4]  ·  /problems/pages-not-indexed/
 * Scene: graphic/parts/scenes/traffic.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="pages-not-indexed-4-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Indexed pages',
		'path' => 'Last 8 weeks',
		'stat_label' => 'Pages indexed',
		'stat' => 124,
		'stat_suffix' => ' / 142',
		'delta' => '+66',
		'delta_state' => 'good',
		'points' => array( 58, 58, 59, 60, 74, 88, 99, 108, 115, 119, 122, 124 ),
		'marker' => 3,
		'marker_label' => 'Fixes applied',
		'axis' => array( 'Week 1', 'Week 4', 'Week 8' ),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'traffic', $d );
