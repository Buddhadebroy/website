<?php
/**
 * Generative Engine Optimization › hero: business facts AI engines read.
 * Graphic name: generative-engine-optimization-1  ·  Shortcode: [vp_generative_engine_optimization_1]  ·  /solutions/generative-engine-optimization/
 * Scene: graphic/parts/scenes/entity.php  ·  Hero: no page title inside, fills the column height.
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="generative-engine-optimization-1-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'How AI sees your business',
		'path' => 'Business information',
		'name' => 'Rosie Bakes',
		'kind' => 'Bakery · Leeds, UK',
		'score' => 72,
		'facts' => array(
			array( 'Name', 'Rosie Bakes', 'good' ),
			array( 'Address', '14 Kirkgate, Leeds LS1', 'bad', '2 versions found' ),
			array( 'Opening hours', 'Tue–Sat 9:00–17:00', 'good' ),
			array( 'Phone', '0113 496 0000', 'good' ),
			array( 'Founded', '2016', 'warn', 'Missing on site' ),
			array( 'Speciality', 'Vegan wedding cakes', 'good' ),
		),
		'foot' => 'AI tools trust facts that match everywhere. Fix the two that do not.',
		'foot_icon' => 'badge-check',
	),
	isset( $args ) ? $args : array(),
	true
);
vpx_scene( 'entity', $d );
