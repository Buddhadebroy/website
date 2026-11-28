<?php
/**
 * Website Health › graphic 2: 30 days of uptime.
 * Graphic name: website-health-2  ·  Shortcode: [vp_website_health_2]  ·  /solutions/website-health/
 * Scene: graphic/parts/scenes/uptime.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="website-health-2-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Uptime',
		'path' => 'Checked every 5 minutes',
		'uptime' => '99.94%',
		'rt' => '420 ms',
		'rt_label' => 'Avg response',
		'days' => array( 1, 1, 1, 1, 1, 1, 2, 1, 1, 1, 1, 1, 1, 3, 1, 1, 1, 1, 1, 1, 2, 1, 1, 1, 1, 1, 1, 1, 1, 1 ),
		'events' => array(
			array( 'Day 14', 'Down for 6 minutes, host restarted', 'bad' ),
			array( 'Day 7', 'Slow responses after plugin update', 'warn' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'uptime', $d );
