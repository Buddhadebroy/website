<?php
/**
 * Website Health Issues › graphic 3: 30 days of uptime.
 * Graphic name: website-health-issues-3  ·  Shortcode: [vp_website_health_issues_3]  ·  /problems/website-health-issues/
 * Scene: graphic/parts/scenes/uptime.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="website-health-issues-3-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Errors over 30 days',
		'path' => 'Uptime + PHP errors',
		'uptime' => '98.7%',
		'rt' => '1.9 s',
		'rt_label' => 'Avg response',
		'up_label' => 'Uptime, 30 days',
		'days' => array( 1, 1, 2, 1, 1, 2, 2, 1, 1, 3, 1, 1, 2, 1, 1, 1, 2, 2, 3, 1, 1, 1, 2, 1, 1, 2, 3, 2, 2, 2 ),
		'events' => array(
			array( 'Day 27', 'Down 41 min · memory limit', 'bad' ),
			array( 'Day 19', 'Down 12 min · plugin conflict', 'bad' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'uptime', $d );
