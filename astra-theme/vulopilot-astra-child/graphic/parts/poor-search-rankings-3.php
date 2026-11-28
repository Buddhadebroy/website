<?php
/**
 * Poor Search Rankings › graphic 3: internal link map with the orphan page.
 * Graphic name: poor-search-rankings-3  ·  Shortcode: [vp_poor_search_rankings_3]  ·  /problems/poor-search-rankings/
 * Scene: graphic/parts/scenes/links.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="poor-search-rankings-3-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Link support',
		'path' => 'Internal links',
		'nodes' => array( 'Home', 'Services', 'Blog', 'Prices', 'Reviews', 'Contact', 'Boiler repair' ),
		'orphan' => 6,
		'finding' => '"Boiler repair" gets 1 internal link. Your top competitor page gets 14.',
		'btn' => 'Suggest links',
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'links', $d );
