<?php
/**
 * Optimize Website Content › graphic 4: a short quotable answer block.
 * Graphic name: optimize-website-content-4  ·  Shortcode: [vp_optimize_website_content_4]  ·  /use-cases/optimize-website-content/
 * Scene: graphic/parts/scenes/answer-box.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="optimize-website-content-4-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Quick answer block',
		'path' => 'Added to the post',
		'question' => 'When is the best time to plant roses?',
		'answer' => 'Plant bare-root roses from November to March while they are dormant. Container roses can go in at any time of year, as long as the soil is not frozen. Water daily for two weeks after planting.',
		'words' => 41,
		'checks' => array(
			array( 'Directly answers the question', 'good' ),
			array( 'Reads well out loud', 'good' ),
			array( 'Marked up as FAQ', 'good' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'answer-box', $d );
