<?php
/**
 * Not Appearing In Ai Search › graphic 2: an AI answer and the sources it cites.
 * Graphic name: not-appearing-in-ai-search-2  ·  Shortcode: [vp_not_appearing_in_ai_search_2]  ·  /problems/not-appearing-in-ai-search/
 * Scene: graphic/parts/scenes/ai-chat.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="not-appearing-in-ai-search-2-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Ask an AI about your field',
		'path' => 'ChatGPT-style answer',
		'engine' => 'AI answer · 3 sources',
		'question' => 'Who is the best emergency plumber in Bristol?',
		'answer' => 'Popular options in Bristol include RapidFlow Plumbing and Avon Heating, both with strong reviews and clear 24/7 pricing on their websites.',
		'cites' => array(
			array( 'rapidflow.example', false ),
			array( 'avonheating.example', false ),
			array( 'checkatrade.com', false ),
		),
		'verdict' => 'Your business is not mentioned. The AI never saw your pages.',
		'verdict_state' => 'bad',
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'ai-chat', $d );
