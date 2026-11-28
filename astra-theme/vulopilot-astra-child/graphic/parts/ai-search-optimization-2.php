<?php
/**
 * Ai Search Optimization › graphic 2: which crawlers may read the site.
 * Graphic name: ai-search-optimization-2  ·  Shortcode: [vp_ai_search_optimization_2]  ·  /solutions/ai-search-optimization/
 * Scene: graphic/parts/scenes/crawlers.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="ai-search-optimization-2-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'AI crawler access',
		'path' => 'robots.txt',
		'rows' => array(
			array( 'GPTBot', 'OpenAI · ChatGPT', false, true ),
			array( 'ClaudeBot', 'Anthropic · Claude', false, true ),
			array( 'PerplexityBot', 'Perplexity', true, true ),
			array( 'Google-Extended', 'Gemini', false, true ),
			array( 'Bingbot', 'Bing · Copilot', true, true ),
		),
		'foot' => 'Blocked crawlers cannot quote you. VuloPilot shows which ones and opens them with one switch.',
		'foot_icon' => 'unlock',
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'crawlers', $d );
