<?php
/**
 * Answer Engine Optimization › graphic 4: keyword positions, before and after.
 * Graphic name: answer-engine-optimization-4  ·  Shortcode: [vp_answer_engine_optimization_4]  ·  /solutions/answer-engine-optimization/
 * Scene: graphic/parts/scenes/rank.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="answer-engine-optimization-4-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Questions you answer',
		'path' => 'Answer position',
		'col_was' => 'Before',
		'col_now' => 'Now',
		'rows' => array(
			array( 'how much is a wedding cake uk', 14, 3, 'Featured snippet' ),
			array( 'how long does a wedding cake last', 9, 1, 'Featured snippet' ),
			array( 'vegan wedding cake price', 21, 6, 'People also ask' ),
			array( 'cake tasting near me', 18, 8, '' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'rank', $d );
