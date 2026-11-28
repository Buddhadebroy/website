<?php
/**
 * Automate Website Management › hero: week of scheduled jobs.
 * Graphic name: automate-website-management-1  ·  Shortcode: [vp_automate_website_management_1]  ·  /use-cases/automate-website-management/
 * Scene: graphic/parts/scenes/calendar.php  ·  Hero: no page title inside, fills the column height.
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="automate-website-management-1-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Maintenance calendar',
		'path' => 'Runs on its own',
		'today' => 1,
		'jobs' => array(
			array( 0, 'Backup', 'good' ),
			array( 0, 'SEO scan', 'good' ),
			array( 1, 'Backup', 'good' ),
			array( 1, 'Plugin updates', 'warn' ),
			array( 2, 'Backup', 'good' ),
			array( 3, 'Backup', 'good' ),
			array( 3, 'Speed test', 'good' ),
			array( 4, 'Backup', 'good' ),
			array( 4, 'Security scan', 'bad' ),
			array( 5, 'Backup', 'good' ),
			array( 6, 'Backup', 'good' ),
			array( 6, 'Weekly report', 'warn' ),
		),
		'next' => 'Plugin updates: 3 waiting for your approval',
		'next_time' => 'Today',
		'foot' => 'Set it once. Backups, scans, updates and reports run on schedule.',
		'foot_icon' => 'calendar-check',
	),
	isset( $args ) ? $args : array(),
	true
);
vpx_scene( 'calendar', $d );
