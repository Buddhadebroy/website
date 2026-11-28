<?php
/**
 * Website Too Slow › graphic 3: loading filmstrip.
 * Graphic name: website-too-slow-3  ·  Shortcode: [vp_website_too_slow_3]  ·  /problems/website-too-slow/
 * Scene: graphic/parts/scenes/filmstrip.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="website-too-slow-3-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'What visitors see',
		'path' => 'Mobile · 4G',
		'times' => array( '1s', '2s', '4s', '6s', '8s' ),
		'rows' => array(
			array( 'Today', array( 0, 0, 0, 1, 1 ), 'Still loading at 8 s' ),
			array( 'After', array( 1, 2, 2, 2, 2 ), 'Done at 2.1 s' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'filmstrip', $d );
