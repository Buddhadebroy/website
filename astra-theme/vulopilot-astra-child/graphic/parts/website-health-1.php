<?php
/**
 * Website Health › hero: site checkup tiles.
 * Graphic name: website-health-1  ·  Shortcode: [vp_website_health_1]  ·  /solutions/website-health/
 * Scene: graphic/parts/scenes/checkup.php  ·  Hero: no page title inside, fills the column height.
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="website-health-1-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Health checkup',
		'path' => 'Website Health',
		'tiles' => array(
			array( 'refresh-cw', 'WordPress', '6.6.2', 'good', 'Up to date' ),
			array( 'server', 'PHP', '7.4', 'bad', 'No security fixes' ),
			array( 'shield-check', 'SSL', 'Valid', 'good', 'Renews in 47 days' ),
			array( 'database', 'Backups', 'Daily', 'good', 'Last: 02:00 today' ),
			array( 'archive', 'Disk', '86% full', 'warn', '4.1 GB of 5 GB' ),
			array( 'clock', 'Cron', 'Late', 'warn', '3 jobs overdue' ),
		),
		'foot' => 'Green means nothing to do. Anything else says what to do next.',
		'foot_icon' => 'stethoscope',
	),
	isset( $args ) ? $args : array(),
	true
);
vpx_scene( 'checkup', $d );
