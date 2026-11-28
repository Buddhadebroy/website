<?php
/**
 * Low Search Traffic › graphic 3: how often AI engines mention you.
 * Graphic name: low-search-traffic-3  ·  Shortcode: [vp_low_search_traffic_3]  ·  /problems/low-search-traffic/
 * Scene: graphic/parts/scenes/share.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="low-search-traffic-3-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Clicks going to AI answers',
		'path' => 'Same questions',
		'was' => 'Last year',
		'now' => 'Now',
		'rows' => array(
			array( 'Searches that show an AI answer', 12, 47 ),
			array( 'Your clicks from those searches', 30, 9 ),
		),
		'note' => 'More questions are answered by AI before anyone clicks. Being quoted there is the new click.',
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'share', $d );
