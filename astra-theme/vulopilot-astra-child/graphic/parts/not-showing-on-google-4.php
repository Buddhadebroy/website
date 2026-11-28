<?php
/**
 * Not Showing On Google › graphic 4: index coverage and the reasons.
 * Graphic name: not-showing-on-google-4  ·  Shortcode: [vp_not_showing_on_google_4]  ·  /problems/not-showing-on-google/
 * Scene: graphic/parts/scenes/coverage.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="not-showing-on-google-4-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Pages in Google',
		'path' => '2 weeks later',
		'indexed' => 31,
		'total' => 34,
		'ok_label' => 'Indexed',
		'bad_label' => 'Waiting',
		'reasons' => array(
			array( 'Discovered, not crawled yet', 2, 'warn', 'Usually clears within days' ),
			array( 'Duplicate, Google chose another', 1, 'low', 'Expected' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'coverage', $d );
