<?php
/**
 * Generative Engine Optimization › graphic 2: audit result by category.
 * Graphic name: generative-engine-optimization-2  ·  Shortcode: [vp_generative_engine_optimization_2]  ·  /solutions/generative-engine-optimization/
 * Scene: graphic/parts/scenes/audit-grid.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="generative-engine-optimization-2-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'GEO score',
		'path' => 'SEO & Visibility → GEO',
		'total' => 9,
		'score' => 71,
		'total_label' => 'Things to improve',
		'tiles' => array(
			array( 'quote', 'AI summary', 1, 'warn' ),
			array( 'message-circle-question', 'Question coverage', 3, 'bad' ),
			array( 'file-text', 'Evidence & sources', 2, 'warn' ),
			array( 'building2', 'Entity clarity', 2, 'warn' ),
			array( 'code', 'Schema', 1, 'good' ),
			array( 'bot', 'Crawler access', 0, 'good' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'audit-grid', $d );
