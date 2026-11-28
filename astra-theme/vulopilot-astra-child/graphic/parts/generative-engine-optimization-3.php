<?php
/**
 * Generative Engine Optimization › graphic 3: content editor with highlights.
 * Graphic name: generative-engine-optimization-3  ·  Shortcode: [vp_generative_engine_optimization_3]  ·  /solutions/generative-engine-optimization/
 * Scene: graphic/parts/scenes/editor.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="generative-engine-optimization-3-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Make it quotable',
		'path' => '/vegan-wedding-cakes/',
		'title' => 'Vegan wedding cakes',
		'stats' => array(
			array( 'Has a one-line answer', 'good' ),
			array( '2 claims without a source', 'bad' ),
			array( 'Grade 7 reading level', 'good' ),
		),
		'segments' => array(
			array( 'Our vegan wedding cakes', null ),
			array( 'are made without eggs, dairy or honey', 'kw' ),
			array( 'and start at £320 for two tiers.', null ),
			array( 'It is believed by many customers that the taste is the same,', 'passive' ),
			array(
				'and in our blind tasting last spring with forty-two guests from three different weddings, most people could not tell which cake was which when asked.',
				'long',
			),
		),
		'outline' => array(
			array( 'H2', 'What makes it vegan' ),
			array( 'H2', 'Prices' ),
			array( 'H3', 'Two tiers' ),
			array( 'H3', 'Three tiers' ),
			array( 'H2', 'Questions' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'editor', $d );
