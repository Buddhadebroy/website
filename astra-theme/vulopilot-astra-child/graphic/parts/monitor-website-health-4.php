<?php
/**
 * Monitor Website Health › graphic 4: report email.
 * Graphic name: monitor-website-health-4  ·  Shortcode: [vp_monitor_website_health_4]  ·  /use-cases/monitor-website-health/
 * Scene: graphic/parts/scenes/report.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="monitor-website-health-4-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Monthly health report',
		'path' => '1st of each month',
		'to' => 'owner@northfield.example',
		'subject' => 'Your site in October: 99.98% up, 0 security issues',
		'kpis' => array(
			array( 'Uptime', '99.98%', '+0.1%', 'good' ),
			array( 'Avg speed', '1.4 s', '-0.3 s', 'good' ),
			array( 'Issues fixed', '11', '+11', 'good' ),
		),
		'lines' => array(
			'31 backups made, all verified',
			'12 updates applied safely',
			'1 short outage, resolved by your host',
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'report', $d );
