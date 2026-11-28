<?php
/**
 * Businesses › graphic 4: site checkup tiles.
 * Graphic name: businesses-4  ·  Shortcode: [vp_businesses_4]  ·  /for/businesses/
 * Scene: graphic/parts/scenes/checkup.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="businesses-4-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Is everything OK?',
		'path' => 'At a glance',
		'tiles' => array(
			array( 'globe', 'Website', 'Online', 'good', '99.99% this month' ),
			array( 'mail', 'Booking form', 'Working', 'good', 'Tested today' ),
			array( 'shield-check', 'Security', 'Protected', 'good', '0 issues' ),
			array( 'database', 'Backup', 'Last night', 'good', 'Kept 30 days' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'checkup', $d );
