<?php
/**
 * Marketers › graphic 2: keyword positions, before and after.
 * Graphic name: marketers-2  ·  Shortcode: [vp_marketers_2]  ·  /for/marketers/
 * Scene: graphic/parts/scenes/rank.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="marketers-2-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Campaign keywords',
		'path' => 'Last 30 days',
		'rows' => array(
			array( 'kids bike size chart', 18, 3, 'New guide' ),
			array( 'gravel bike under 1000', 27, 8, 'Comparison table added' ),
			array( 'bike service near me', 9, 4, 'Local page' ),
			array( 'e-bike buying guide', 31, 12, 'Draft published' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'rank', $d );
