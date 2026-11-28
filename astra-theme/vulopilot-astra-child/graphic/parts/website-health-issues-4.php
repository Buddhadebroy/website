<?php
/**
 * Website Health Issues › graphic 4: backup timeline.
 * Graphic name: website-health-issues-4  ·  Shortcode: [vp_website_health_issues_4]  ·  /problems/website-health-issues/
 * Scene: graphic/parts/scenes/backups.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="website-health-issues-4-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Last good backup',
		'path' => 'Restore points',
		'rows' => array(
			array( '6 days ago', 'Failed: storage full', '0 B', false ),
			array( '7 days ago', 'Full site · verified', '2.4 GB', true ),
			array( '8 days ago', 'Full site', '2.4 GB', false ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'backups', $d );
