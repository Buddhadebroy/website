<?php
/**
 * Improve Ai Visibility › graphic 4: the file or tag behind the problem, and the fix.
 * Graphic name: improve-ai-visibility-4  ·  Shortcode: [vp_improve_ai_visibility_4]  ·  /use-cases/improve-ai-visibility/
 * Scene: graphic/parts/scenes/code.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="improve-ai-visibility-4-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'llms.txt',
		'path' => 'Published at /llms.txt',
		'tabs' => array( 'llms.txt' ),
		'lines' => array(
			array( 'com', '# Northfield Garden Design' ),
			array( 'plain', '> Garden design and planting plans in Bristol and Bath.' ),
			array( 'plain', '' ),
			array( 'key', '## Services' ),
			array( 'add', '- [Garden design](https://northfield.example/design/)' ),
			array( 'add', '- [Planting plans](https://northfield.example/planting/)' ),
			array( 'add', '- [Prices](https://northfield.example/prices/)' ),
		),
		'notes' => array( array( 'Crawlers now find the right pages first', 'good' ) ),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'code', $d );
