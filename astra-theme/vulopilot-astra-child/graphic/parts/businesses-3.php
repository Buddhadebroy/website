<?php
/**
 * Businesses › graphic 3: alerts with what to do.
 * Graphic name: businesses-3  ·  Shortcode: [vp_businesses_3]  ·  /for/businesses/
 * Scene: graphic/parts/scenes/alerts.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="businesses-3-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Only important emails',
		'path' => 'Your inbox',
		'rows' => array(
			array( 'circle-x', 'Booking form stopped working', 'Fixed automatically 6 minutes later.', 'good', 'Tue' ),
			array(
				'shield-alert',
				'Login attack blocked',
				'2,000 attempts from one network. Nothing got in.',
				'warn',
				'Thu',
			),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'alerts', $d );
