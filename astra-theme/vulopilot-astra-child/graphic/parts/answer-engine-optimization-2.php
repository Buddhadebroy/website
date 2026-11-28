<?php
/**
 * Answer Engine Optimization › graphic 2: Google result preview, before and after.
 * Graphic name: answer-engine-optimization-2  ·  Shortcode: [vp_answer_engine_optimization_2]  ·  /solutions/answer-engine-optimization/
 * Scene: graphic/parts/scenes/serp.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="answer-engine-optimization-2-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Featured snippet',
		'path' => 'Position 0',
		'query' => 'how long does a wedding cake last',
		'site' => 'Rosie Bakes',
		'crumb' => 'rosiebakes.co.uk › faq',
		'bad_title' => 'FAQ – Rosie Bakes',
		'bad_desc' => 'Frequently asked questions. Click a question below to see the answer…',
		'good_title' => 'How long does a wedding cake last? Up to 5 days',
		'good_desc' => 'Buttercream cakes keep for 3 to 5 days at room temperature. Fruit cakes last for weeks when wrapped. Freeze the top tier for up to a year.',
		'checks' => array(
			array( 'Question used as a heading', 'bad', 'good' ),
			array( 'Answer directly under the heading', 'bad', 'good' ),
		),
		'before_tag' => 'Hidden',
		'after_tag' => 'Quoted',
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'serp', $d );
