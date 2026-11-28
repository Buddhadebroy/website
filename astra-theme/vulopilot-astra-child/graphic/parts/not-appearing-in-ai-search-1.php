<?php
/**
 * Not Appearing In Ai Search › hero: which crawlers may read the site.
 * Graphic name: not-appearing-in-ai-search-1  ·  Shortcode: [vp_not_appearing_in_ai_search_1]  ·  /problems/not-appearing-in-ai-search/
 * Scene: graphic/parts/scenes/crawlers.php  ·  Hero: no page title inside, fills the column height.
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="not-appearing-in-ai-search-1-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Who can read your site',
		'path' => 'robots.txt + firewall',
		'rows' => array(
			array( 'GPTBot', 'OpenAI · ChatGPT', false, true ),
			array( 'OAI-SearchBot', 'ChatGPT search', false, true ),
			array( 'ClaudeBot', 'Anthropic · Claude', false, true ),
			array( 'PerplexityBot', 'Perplexity', false, true ),
			array( 'Google-Extended', 'Gemini', false, true ),
		),
		'foot' => 'A security plugin was blocking every AI crawler. That is why you were invisible.',
		'foot_icon' => 'bot',
	),
	isset( $args ) ? $args : array(),
	true
);
vpx_scene( 'crawlers', $d );
