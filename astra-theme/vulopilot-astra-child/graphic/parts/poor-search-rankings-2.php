<?php
/**
 * Poor Search Rankings › graphic 2: findings sorted by impact.
 * Graphic name: poor-search-rankings-2  ·  Shortcode: [vp_poor_search_rankings_2]  ·  /problems/poor-search-rankings/
 * Scene: graphic/parts/scenes/issues.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="poor-search-rankings-2-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'What holds them back',
		'path' => 'Per page',
		'meta' => 'Biggest gain first',
		'rows' => array(
			array( 'low', 'Missing alt text', '/boiler-repair/', 3 ),
			array( 'high', 'Thin content, no prices', '/gas-safety/', 0 ),
			array( 'high', 'Slow on mobile (5.1 s)', '/blocked-drains/', 1 ),
			array( 'medium', 'No internal links from blog', '/boiler-repair/', 2 ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'issues', $d );
