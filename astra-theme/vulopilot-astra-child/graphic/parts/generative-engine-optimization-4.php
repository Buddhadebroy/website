<?php
/**
 * Generative Engine Optimization › graphic 4: how often AI engines mention you.
 * Graphic name: generative-engine-optimization-4  ·  Shortcode: [vp_generative_engine_optimization_4]  ·  /solutions/generative-engine-optimization/
 * Scene: graphic/parts/scenes/share.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="generative-engine-optimization-4-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Brand mentions in AI answers',
		'path' => 'Tracked questions',
		'was' => 'Month 1',
		'now' => 'Month 3',
		'rows' => array(
			array( 'Best vegan cake Leeds', 0, 64 ),
			array( 'Wedding cake delivery Leeds', 22, 58 ),
			array( 'Egg-free birthday cake', 10, 41 ),
		),
		'note' => 'How often each answer names Rosie Bakes, across ChatGPT, Perplexity and Gemini.',
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'share', $d );
