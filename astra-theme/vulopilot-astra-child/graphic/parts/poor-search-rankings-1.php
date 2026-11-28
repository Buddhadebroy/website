<?php
/**
 * Poor Search Rankings › hero: keyword positions, before and after.
 * Graphic name: poor-search-rankings-1  ·  Shortcode: [vp_poor_search_rankings_1]  ·  /problems/poor-search-rankings/
 * Scene: graphic/parts/scenes/rank.php  ·  Hero: no page title inside, fills the column height.
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="poor-search-rankings-1-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Stuck on page 2',
		'path' => 'Your target keywords',
		'col_was' => '3 months ago',
		'col_now' => 'Today',
		'rows' => array(
			array( 'boiler repair bristol', 12, 13, 'Competitors have prices and reviews' ),
			array( 'emergency plumber bath', 15, 18, 'No page for Bath' ),
			array( 'blocked drain bristol', 11, 11, 'Page loads in 5 s' ),
			array( 'gas safety certificate', 19, 22, 'Thin page, 210 words' ),
		),
		'foot' => 'Positions 11 to 20 get under 1% of clicks. VuloPilot shows what holds each one back.',
		'foot_icon' => 'trending-down',
	),
	isset( $args ) ? $args : array(),
	true
);
vpx_scene( 'rank', $d );
