<?php
/**
 * Website Performance › graphic 4: KPI tiles with sparklines.
 * Graphic name: website-performance-4  ·  Shortcode: [vp_website_performance_4]  ·  /solutions/website-performance/
 * Scene: graphic/parts/scenes/kpi.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="website-performance-4-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'After the fixes',
		'path' => '28 days',
		'tiles' => array(
			array( 'Bounce rate', '41%', '-12 pts', 'good', array( 55, 54, 56, 53, 49, 45, 42, 41 ) ),
			array( 'Pages per visit', '3.2', '+0.9', 'good', array( 2.2, 2.3, 2.2, 2.6, 2.8, 3, 3.1, 3.2 ) ),
			array( 'Mobile load', '1.6 s', '-3.6 s', 'good', array( 5.2, 5, 4.8, 3, 2.1, 1.8, 1.7, 1.6 ) ),
			array( 'Orders', '+18%', 'vs last month', 'good', array( 10, 11, 10, 12, 13, 14, 15, 16 ) ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'kpi', $d );
