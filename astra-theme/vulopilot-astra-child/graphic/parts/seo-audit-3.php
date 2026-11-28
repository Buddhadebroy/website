<?php
/**
 * Seo Audit › graphic 3: week of scheduled jobs.
 * Graphic name: seo-audit-3  ·  Shortcode: [vp_seo_audit_3]  ·  /solutions/seo-audit/
 * Scene: graphic/parts/scenes/calendar.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="seo-audit-3-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Audit schedule',
		'path' => 'This week',
		'today' => 0,
		'jobs' => array(
			array( 0, 'Full SEO audit', 'good' ),
			array( 0, 'Email summary', 'warn' ),
			array( 2, 'Re-scan fixed pages', 'good' ),
			array( 4, 'Broken link check', 'warn' ),
			array( 6, 'Weekly score snapshot', 'good' ),
		),
		'next' => 'Next full audit starts Monday 06:00',
		'next_time' => 'in 2 days',
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'calendar', $d );
