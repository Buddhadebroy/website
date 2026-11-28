<?php
/**
 * Agencies › graphic 4: week of scheduled jobs.
 * Graphic name: agencies-4  ·  Shortcode: [vp_agencies_4]  ·  /for/agencies/
 * Scene: graphic/parts/scenes/calendar.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="agencies-4-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Agency schedule',
		'path' => 'All sites',
		'today' => 0,
		'jobs' => array(
			array( 0, '12 SEO scans', 'good' ),
			array( 0, 'Reports out', 'warn' ),
			array( 1, 'Updates: 4 sites', 'warn' ),
			array( 2, 'Speed tests', 'good' ),
			array( 3, 'Updates: 4 sites', 'warn' ),
			array( 4, 'Security scans', 'bad' ),
			array( 5, 'Updates: 4 sites', 'warn' ),
			array( 6, 'Backups verified', 'good' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'calendar', $d );
