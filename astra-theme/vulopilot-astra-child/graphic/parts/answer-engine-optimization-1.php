<?php
/**
 * Answer Engine Optimization › hero: a short quotable answer block.
 * Graphic name: answer-engine-optimization-1  ·  Shortcode: [vp_answer_engine_optimization_1]  ·  /solutions/answer-engine-optimization/
 * Scene: graphic/parts/scenes/answer-box.php  ·  Hero: no page title inside, fills the column height.
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="answer-engine-optimization-1-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Answer block',
		'path' => '/prices/',
		'kicker' => 'People also ask',
		'question' => 'How much does a wedding cake cost in Leeds?',
		'answer' => 'A two-tier handmade wedding cake in Leeds costs £320 to £450. Three tiers cost £480 to £700, depending on flavours and decoration. Delivery within 20 miles and set-up are included.',
		'words' => 36,
		'len_unit' => 'words · target 40–60',
		'checks' => array(
			array( 'Answers in the first sentence', 'good' ),
			array( 'Uses the words people search for', 'good' ),
			array( 'FAQPage schema on the page', 'bad' ),
			array( 'Under 60 words', 'good' ),
		),
		'foot' => 'Short, direct answers are what featured snippets and voice assistants read out.',
		'foot_icon' => 'volume2',
	),
	isset( $args ) ? $args : array(),
	true
);
vpx_scene( 'answer-box', $d );
