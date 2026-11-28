<?php
/**
 * Improve Search Visibility › graphic 2: KPI tiles with sparklines.
 * Graphic name: improve-search-visibility-2  ·  Shortcode: [vp_improve_search_visibility_2]  ·  /use-cases/improve-search-visibility/
 * Scene: graphic/parts/scenes/kpi.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="improve-search-visibility-2-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Search Console',
		'path' => 'Last 28 days',
		'tiles' => array(
			array( 'Impressions', '48.2k', '+71%', 'good', array( 20, 22, 21, 25, 29, 34, 40, 48 ) ),
			array( 'Clicks', '1.9k', '+44%', 'good', array( 1.1, 1.2, 1.1, 1.3, 1.4, 1.6, 1.8, 1.9 ) ),
			array( 'Avg. position', '11.4', '-6.2', 'good', array( 18, 17.5, 17, 15, 14, 13, 12, 11.4 ) ),
			array( 'Pages indexed', '86', '+22', 'good', array( 64, 64, 66, 70, 74, 79, 83, 86 ) ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'kpi', $d );
