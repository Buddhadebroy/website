<?php
/**
 * Website Health › graphic 4: alerts with what to do.
 * Graphic name: website-health-4  ·  Shortcode: [vp_website_health_4]  ·  /solutions/website-health/
 * Scene: graphic/parts/scenes/alerts.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="website-health-4-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Health alerts',
		'path' => 'Email · Dashboard',
		'rows' => array(
			array(
				'archive',
				'Disk is 86% full',
				'Old backups and logs take 1.2 GB. Clean up with one click.',
				'warn',
				'09:14',
			),
			array(
				'server',
				'PHP 7.4 has no security fixes',
				'Ask your host for PHP 8.2. Everything was checked for compatibility.',
				'bad',
				'Yesterday',
			),
			array( 'shield-check', 'SSL renewed', 'Your certificate is valid until March.', 'good', 'Mon' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'alerts', $d );
