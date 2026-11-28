<?php
/**
 * Optimize Website Content › hero: content editor with highlights.
 * Graphic name: optimize-website-content-1  ·  Shortcode: [vp_optimize_website_content_1]  ·  /use-cases/optimize-website-content/
 * Scene: graphic/parts/scenes/editor.php  ·  Hero: no page title inside, fills the column height.
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="optimize-website-content-1-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Content editor',
		'path' => '/blog/when-to-plant-roses/',
		'title' => 'When to plant roses in the UK',
		'stats' => array(
			array( 'Readability: good', 'good' ),
			array( '1 long sentence', 'warn' ),
			array( 'Keyword in first 100 words', 'good' ),
		),
		'segments' => array(
			array( 'Plant bare-root roses', 'kw' ),
			array( 'between November and March, while they are dormant.', null ),
			array( 'Container roses can be planted at any time of year', null ),
			array(
				'as long as the ground is not frozen or waterlogged, but if you plant them in summer you will need to water them every day for the first few weeks, which many people forget.',
				'long',
			),
			array( 'The hole should be dug twice as wide as the roots.', 'passive' ),
		),
		'outline' => array(
			array( 'H2', 'Bare-root vs container' ),
			array( 'H2', 'Month by month' ),
			array( 'H3', 'Autumn' ),
			array( 'H3', 'Spring' ),
			array( 'H2', 'Aftercare' ),
		),
		'foot' => 'Highlights show what to change. Nothing is rewritten unless you ask.',
		'foot_icon' => 'pen-line',
	),
	isset( $args ) ? $args : array(),
	true
);
vpx_scene( 'editor', $d );
