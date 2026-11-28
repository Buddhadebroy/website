<?php
/**
 * Website Automation › graphic 4: alerts with what to do.
 * Graphic name: website-automation-4  ·  Shortcode: [vp_website_automation_4]  ·  /solutions/website-automation/
 * Scene: graphic/parts/scenes/alerts.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="website-automation-4-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Only when it matters',
		'path' => 'Notifications',
		'rows' => array(
			array(
				'shield-alert',
				'New vulnerability in Slider Pro',
				'Update prepared. Approve to apply.',
				'bad',
				'06:02',
			),
			array( 'check-circle2', 'Weekly scan finished', 'No new problems. Score 91.', 'good', '06:05' ),
			array( 'trending-down', 'Homepage got slower', 'Load time 1.6 s → 2.9 s after a new image.', 'warn', 'Tue' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'alerts', $d );
