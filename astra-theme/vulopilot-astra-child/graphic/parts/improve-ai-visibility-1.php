<?php
/**
 * Improve Ai Visibility › hero: how often AI engines mention you.
 * Graphic name: improve-ai-visibility-1  ·  Shortcode: [vp_improve_ai_visibility_1]  ·  /use-cases/improve-ai-visibility/
 * Scene: graphic/parts/scenes/share.php  ·  Hero: no page title inside, fills the column height.
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="improve-ai-visibility-1-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Where AI mentions you',
		'path' => '40 tracked questions',
		'was' => 'Before',
		'now' => 'After 6 weeks',
		'rows' => array(
			array( 'ChatGPT', 5, 34 ),
			array( 'Perplexity', 10, 41 ),
			array( 'Google AI Overviews', 0, 19 ),
			array( 'Gemini', 2, 23 ),
			array( 'Copilot', 4, 26 ),
		),
		'note' => 'Share of answers that name your business or link to your site.',
		'foot' => 'Tracked weekly, so you see which changes AI tools picked up.',
		'foot_icon' => 'line-chart',
	),
	isset( $args ) ? $args : array(),
	true
);
vpx_scene( 'share', $d );
