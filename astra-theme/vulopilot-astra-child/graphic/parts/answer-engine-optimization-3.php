<?php
/**
 * Answer Engine Optimization › graphic 3: the file or tag behind the problem, and the fix.
 * Graphic name: answer-engine-optimization-3  ·  Shortcode: [vp_answer_engine_optimization_3]  ·  /solutions/answer-engine-optimization/
 * Scene: graphic/parts/scenes/code.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="answer-engine-optimization-3-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'FAQ schema',
		'path' => 'Added to /faq/',
		'tabs' => array( 'JSON-LD', 'HTML' ),
		'lines' => array(
			array( 'plain', '{' ),
			array( 'key', '  "@type": "FAQPage",' ),
			array( 'key', '  "mainEntity": [{' ),
			array( 'plain', '    "@type": "Question",' ),
			array( 'add', '    "name": "How long does a wedding cake last?",' ),
			array( 'add', '    "acceptedAnswer": { "text": "3 to 5 days…" }' ),
			array( 'plain', '  }]' ),
			array( 'plain', '}' ),
		),
		'notes' => array(
			array( 'Valid in Google Rich Results Test', 'good' ),
			array( 'Written from the questions already on the page', 'good' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'code', $d );
