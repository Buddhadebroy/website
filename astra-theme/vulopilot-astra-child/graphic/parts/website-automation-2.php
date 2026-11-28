<?php
/**
 * Website Automation › graphic 2: week of scheduled jobs.
 * Graphic name: website-automation-2  ·  Shortcode: [vp_website_automation_2]  ·  /solutions/website-automation/
 * Scene: graphic/parts/scenes/calendar.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="website-automation-2-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Scheduled jobs',
		'path' => 'This week',
		'today' => 3,
		'jobs' => array(
			array( 0, 'SEO scan', 'good' ),
			array( 0, 'Report email', 'warn' ),
			array( 1, 'Backup', 'good' ),
			array( 2, 'Backup', 'good' ),
			array( 3, 'Speed test', 'good' ),
			array( 3, 'Backup', 'good' ),
			array( 4, 'Security scan', 'bad' ),
			array( 5, 'Backup', 'good' ),
			array( 6, 'Broken links', 'warn' ),
		),
		'next' => 'Speed test running now',
		'next_time' => '06:00',
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'calendar', $d );
