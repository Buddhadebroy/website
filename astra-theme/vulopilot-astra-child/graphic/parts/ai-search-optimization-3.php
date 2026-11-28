<?php
/**
 * Ai Search Optimization › graphic 3: how often AI engines mention you.
 * Graphic name: ai-search-optimization-3  ·  Shortcode: [vp_ai_search_optimization_3]  ·  /solutions/ai-search-optimization/
 * Scene: graphic/parts/scenes/share.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="ai-search-optimization-3-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'AI mentions',
		'path' => 'Share of answers',
		'was' => 'Before',
		'now' => 'After 30 days',
		'rows' => array(
			array( 'ChatGPT', 8, 31 ),
			array( 'Perplexity', 12, 38 ),
			array( 'Google AI Overviews', 5, 22 ),
			array( 'Gemini', 3, 17 ),
		),
		'note' => 'Share of 40 tracked questions where the answer names your business.',
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'share', $d );
