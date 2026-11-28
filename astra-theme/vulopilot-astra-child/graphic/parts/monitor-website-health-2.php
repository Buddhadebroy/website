<?php
/**
 * Monitor Website Health › graphic 2: alerts with what to do.
 * Graphic name: monitor-website-health-2  ·  Shortcode: [vp_monitor_website_health_2]  ·  /use-cases/monitor-website-health/
 * Scene: graphic/parts/scenes/alerts.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="monitor-website-health-2-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Alerts',
		'path' => 'Email · Slack',
		'rows' => array(
			array(
				'circle-x',
				'Site is down',
				'Checked from London and Frankfurt. Host notified at 14:02.',
				'bad',
				'14:01',
			),
			array( 'check-circle2', 'Back up after 4 minutes', 'Response time back to 290 ms.', 'good', '14:05' ),
			array( 'clock', 'SSL expires in 14 days', 'Auto-renew failed. Renew in hosting panel.', 'warn', '09:00' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'alerts', $d );
