<?php
/**
 * Low Search Traffic › graphic 2: keyword positions, before and after.
 * Graphic name: low-search-traffic-2  ·  Shortcode: [vp_low_search_traffic_2]  ·  /problems/low-search-traffic/
 * Scene: graphic/parts/scenes/rank.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="low-search-traffic-2-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Where the traffic went',
		'path' => 'Top pages',
		'col_was' => 'Last year',
		'col_now' => 'Today',
		'rows' => array(
			array( 'emergency plumber bristol', 3, 14, 'Homepage lost its title' ),
			array( 'boiler repair cost', 5, 11, 'Thin page, 180 words' ),
			array( 'blocked drain help', 2, 9, 'Competitor added FAQs' ),
			array( 'plumber near me', 8, 23, 'Slow on mobile' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'rank', $d );
