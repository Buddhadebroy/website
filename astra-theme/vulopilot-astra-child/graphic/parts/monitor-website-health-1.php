<?php
/**
 * Monitor Website Health › hero: 30 days of uptime.
 * Graphic name: monitor-website-health-1  ·  Shortcode: [vp_monitor_website_health_1]  ·  /use-cases/monitor-website-health/
 * Scene: graphic/parts/scenes/uptime.php  ·  Hero: no page title inside, fills the column height.
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="monitor-website-health-1-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Uptime monitor',
		'path' => 'Every 5 minutes · 3 regions',
		'uptime' => '99.98%',
		'rt' => '310 ms',
		'rt_label' => 'Avg response',
		'days' => array( 1, 1, 1, 1, 1, 1, 1, 1, 1, 2, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 3, 1, 1, 1, 1, 1, 1, 1 ),
		'events' => array(
			array( 'Day 23', 'Down 4 min · database restarted', 'bad' ),
			array( 'Day 10', 'Slow for 1 hour · traffic spike', 'warn' ),
		),
		'foot' => 'You hear about it before your customers do.',
		'foot_icon' => 'bell',
	),
	isset( $args ) ? $args : array(),
	true
);
vpx_scene( 'uptime', $d );
