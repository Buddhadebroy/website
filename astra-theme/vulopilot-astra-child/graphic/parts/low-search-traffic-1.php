<?php
/**
 * Low Search Traffic › hero: trend chart with the moment the fix shipped.
 * Graphic name: low-search-traffic-1  ·  Shortcode: [vp_low_search_traffic_1]  ·  /problems/low-search-traffic/
 * Scene: graphic/parts/scenes/traffic.php  ·  Hero: no page title inside, fills the column height.
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="low-search-traffic-1-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Organic visits',
		'path' => 'Last 12 months',
		'stat_label' => 'Visits from search, this month',
		'stat' => 1240,
		'delta' => '-46% vs last year',
		'delta_state' => 'bad',
		'points' => array( 2300, 2280, 2350, 2310, 2200, 1900, 1700, 1550, 1420, 1330, 1270, 1240 ),
		'marker' => 4,
		'marker_label' => 'Core update',
		'axis' => array( 'Nov', 'May', 'Oct' ),
		'legend' => array( array( 'Visits from Google and Bing', 'good' ) ),
		'foot' => 'VuloPilot matches the drop to the pages and changes behind it.',
		'foot_icon' => 'search',
	),
	isset( $args ) ? $args : array(),
	true
);
vpx_scene( 'traffic', $d );
