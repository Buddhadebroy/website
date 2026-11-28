<?php
/**
 * Ai Search Optimization › graphic 4: the file or tag behind the problem, and the fix.
 * Graphic name: ai-search-optimization-4  ·  Shortcode: [vp_ai_search_optimization_4]  ·  /solutions/ai-search-optimization/
 * Scene: graphic/parts/scenes/code.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="ai-search-optimization-4-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'llms.txt',
		'path' => '/llms.txt',
		'tabs' => array( 'llms.txt', 'robots.txt' ),
		'lines' => array(
			array( 'com', '# Rosie Bakes' ),
			array( 'plain', '> Handmade wedding and celebration cakes in Leeds.' ),
			array( 'plain', '' ),
			array( 'key', '## Key pages' ),
			array( 'add', '- [Wedding cakes](https://rosiebakes.co.uk/wedding-cakes/)' ),
			array( 'add', '- [Vegan cakes](https://rosiebakes.co.uk/vegan/)' ),
			array( 'add', '- [Prices and delivery](https://rosiebakes.co.uk/prices/)' ),
		),
		'notes' => array(
			array( 'A plain-text map of your best pages for AI tools', 'good' ),
			array( 'Generated from your site and updated when pages change', 'good' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'code', $d );
