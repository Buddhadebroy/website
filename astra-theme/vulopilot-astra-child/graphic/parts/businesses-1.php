<?php
/**
 * Businesses › hero: KPI tiles with sparklines.
 * Graphic name: businesses-1  ·  Shortcode: [vp_businesses_1]  ·  /for/businesses/
 * Scene: graphic/parts/scenes/kpi.php  ·  Hero: no page title inside, fills the column height.
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="businesses-1-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Your website this month',
		'path' => 'Plain-English summary',
		'tiles' => array(
			array( 'Enquiries from the site', '46', '+12', 'good', array( 30, 31, 33, 34, 38, 41, 44, 46 ) ),
			array( 'Visitors from Google', '2.8k', '+18%', 'good', array( 2.1, 2.2, 2.2, 2.4, 2.5, 2.6, 2.7, 2.8 ) ),
			array( 'Mentioned by AI tools', '9 of 20', '+5', 'good', array( 2, 3, 3, 4, 5, 7, 8, 9 ) ),
			array( 'Site speed', '1.6 s', '-0.8 s', 'good', array( 2.4, 2.4, 2.3, 2, 1.8, 1.7, 1.6, 1.6 ) ),
		),
		'note' => 'Your contact page now loads twice as fast and shows in AI answers for "dentist in Clifton".',
		'foot' => 'The numbers that matter to a business, without the jargon.',
		'foot_icon' => 'lightbulb',
	),
	isset( $args ) ? $args : array(),
	true
);
vpx_scene( 'kpi', $d );
