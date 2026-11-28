<?php
/**
 * Improve Website Performance › graphic 3: checkout timings.
 * Graphic name: improve-website-performance-3  ·  Shortcode: [vp_improve_website_performance_3]  ·  /use-cases/improve-website-performance/
 * Scene: graphic/parts/scenes/checkout.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="improve-website-performance-3-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Key pages, load time',
		'path' => 'Seconds',
		'was' => 'Before',
		'now' => 'After',
		'rows' => array(
			array( 'Homepage', 6.1, 1.8 ),
			array( 'Services', 4.4, 1.5 ),
			array( 'Contact form', 3.9, 1.2 ),
			array( 'Blog post', 3.2, 1.1 ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'checkout', $d );
