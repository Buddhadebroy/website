<?php
/**
 * Website Health Issues › hero: alerts with what to do.
 * Graphic name: website-health-issues-1  ·  Shortcode: [vp_website_health_issues_1]  ·  /problems/website-health-issues/
 * Scene: graphic/parts/scenes/alerts.php  ·  Hero: no page title inside, fills the column height.
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="website-health-issues-1-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'What is wrong right now',
		'path' => 'Health',
		'rows' => array(
			array(
				'circle-x',
				'Contact form is not sending',
				'Mail is rejected by your host. Switch to SMTP to fix.',
				'bad',
				'Now',
			),
			array( 'database', 'Backups failing for 6 days', 'Storage full. 3 old backups can go.', 'bad', '6 days' ),
			array(
				'alert-triangle',
				'White screen on /shop/ for some visitors',
				'PHP memory limit hit. Raise to 256 MB.',
				'warn',
				'2 days',
			),
			array( 'clock', 'Scheduled posts not publishing', 'WP-Cron is not running.', 'warn', '1 day' ),
		),
		'foot' => 'Each alert says what broke, why, and the fix.',
		'foot_icon' => 'stethoscope',
	),
	isset( $args ) ? $args : array(),
	true
);
vpx_scene( 'alerts', $d );
