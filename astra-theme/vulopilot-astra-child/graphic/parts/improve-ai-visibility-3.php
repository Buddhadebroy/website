<?php
/**
 * Improve Ai Visibility › graphic 3: business facts AI engines read.
 * Graphic name: improve-ai-visibility-3  ·  Shortcode: [vp_improve_ai_visibility_3]  ·  /use-cases/improve-ai-visibility/
 * Scene: graphic/parts/scenes/entity.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="improve-ai-visibility-3-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Business information',
		'path' => 'Fill once, used everywhere',
		'name' => 'Northfield Garden Design',
		'kind' => 'Garden designer · Bristol',
		'score' => 88,
		'facts' => array(
			array( 'What you do', 'Garden design and planting plans', 'good' ),
			array( 'Area', 'Bristol and Bath', 'good' ),
			array( 'Old listing', '"Landscaping supplies"', 'bad', 'Outdated' ),
			array( 'Prices', 'From £450 per plan', 'good' ),
			array( 'Reviews', '4.9 on Google, 61 reviews', 'good' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'entity', $d );
