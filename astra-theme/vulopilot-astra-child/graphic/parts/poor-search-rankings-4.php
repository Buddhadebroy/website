<?php
/**
 * Poor Search Rankings › graphic 4: trend chart with the moment the fix shipped.
 * Graphic name: poor-search-rankings-4  ·  Shortcode: [vp_poor_search_rankings_4]  ·  /problems/poor-search-rankings/
 * Scene: graphic/parts/scenes/traffic.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="poor-search-rankings-4-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Average position',
		'path' => 'After fixes',
		'stat_label' => 'Avg. position, 4 keywords',
		'stat' => 5,
		'delta' => 'up 9 places',
		'delta_state' => 'good',
		'points' => array( 14, 14, 15, 14, 13, 11, 9, 8, 7, 6, 5, 5 ),
		'marker' => 4,
		'marker_label' => 'Fixes live',
		'axis' => array( 'Month 1', 'Month 2', 'Month 3' ),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'traffic', $d );
