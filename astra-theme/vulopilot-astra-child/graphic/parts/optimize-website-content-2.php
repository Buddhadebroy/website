<?php
/**
 * Optimize Website Content › graphic 2: content pipeline board.
 * Graphic name: optimize-website-content-2  ·  Shortcode: [vp_optimize_website_content_2]  ·  /use-cases/optimize-website-content/
 * Scene: graphic/parts/scenes/backlog.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="optimize-website-content-2-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Content plan',
		'path' => 'From real searches',
		'cols' => array(
			array(
				'Ideas',
				array(
					array( 'How deep to plant roses', '720 searches', 'good' ),
					array( 'Best roses for shade', '480 searches', 'good' ),
				),
			),
			array( 'Drafts', array( array( 'Rose pruning guide', 'Needs 2 headings', 'warn' ) ) ),
			array(
				'Live',
				array( array( 'When to plant roses', 'Ranking #4', 'good' ), array( 'Rose feeding', 'Refresh due', 'bad' ) ),
			),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'backlog', $d );
