<?php
/**
 * Marketers › hero: content pipeline board.
 * Graphic name: marketers-1  ·  Shortcode: [vp_marketers_1]  ·  /for/marketers/
 * Scene: graphic/parts/scenes/backlog.php  ·  Hero: no page title inside, fills the column height.
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="marketers-1-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Content pipeline',
		'path' => 'Ideas from real searches',
		'cols' => array(
			array(
				'Ideas',
				array(
					array( 'Kids bike size chart', '2.4k searches', 'good' ),
					array( 'Best gravel bike under £1,000', '1.1k searches', 'good' ),
					array( 'How to fit a bike rack', '880 searches', 'good' ),
				),
			),
			array(
				'In progress',
				array(
					array( 'E-bike buying guide', 'Needs a quick answer', 'warn' ),
					array( 'Commuter bikes 2025', 'Refresh due', 'bad' ),
				),
			),
			array(
				'Published',
				array(
					array( 'Bike service checklist', '#3 · quoted by AI', 'good' ),
					array( 'Road vs gravel', '#6', 'good' ),
				),
			),
		),
		'foot' => 'Ideas ranked by search demand and by questions AI tools already get.',
		'foot_icon' => 'layout',
	),
	isset( $args ) ? $args : array(),
	true
);
vpx_scene( 'backlog', $d );
