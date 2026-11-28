<?php
/**
 * Core Web Vitals › graphic 2: loading filmstrip.
 * Graphic name: core-web-vitals-2  ·  Shortcode: [vp_core_web_vitals_2]  ·  /solutions/core-web-vitals/
 * Scene: graphic/parts/scenes/filmstrip.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="core-web-vitals-2-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'What the visitor sees',
		'path' => 'LCP on mobile',
		'times' => array( '0.5s', '1.0s', '2.0s', '3.0s', '4.0s' ),
		'rows' => array(
			array( 'Before', array( 0, 0, 1, 1, 2 ), 'LCP 3.9 s' ),
			array( 'After', array( 1, 2, 2, 2, 2 ), 'LCP 1.8 s' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'filmstrip', $d );
