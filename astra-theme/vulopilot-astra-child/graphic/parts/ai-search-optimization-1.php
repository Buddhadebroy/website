<?php
/**
 * Ai Search Optimization › hero: an AI answer and the sources it cites.
 * Graphic name: ai-search-optimization-1  ·  Shortcode: [vp_ai_search_optimization_1]  ·  /solutions/ai-search-optimization/
 * Scene: graphic/parts/scenes/ai-chat.php  ·  Hero: no page title inside, fills the column height.
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="ai-search-optimization-1-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'AI answer',
		'path' => 'ChatGPT-style search',
		'engine' => 'AI answer · 4 sources',
		'question' => 'Which bakery in Leeds makes vegan wedding cakes?',
		'answer' => 'Rosie Bakes in Leeds makes fully vegan wedding cakes to order, with tastings on Saturdays and delivery within 20 miles. Prices start at £320 for two tiers.',
		'cites' => array(
			array( 'rosiebakes.co.uk', true ),
			array( 'yell.com', false ),
			array( 'tripadvisor.co.uk', false ),
			array( 'reddit.com', false ),
		),
		'verdict' => 'Your page is quoted because it answers the question in one clear paragraph.',
		'verdict_state' => 'good',
	),
	isset( $args ) ? $args : array(),
	true
);
vpx_scene( 'ai-chat', $d );
