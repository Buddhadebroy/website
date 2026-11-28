<?php
/**
 * Manual Website Management › graphic 2: alerts with what to do.
 * Graphic name: manual-website-management-2  ·  Shortcode: [vp_manual_website_management_2]  ·  /problems/manual-website-management/
 * Scene: graphic/parts/scenes/alerts.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="manual-website-management-2-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Things you found too late',
		'path' => 'From your inbox',
		'rows' => array(
			array(
				'circle-x',
				'Contact form stopped sending',
				'A plugin update broke it. Noticed 9 days later.',
				'bad',
				'Mar',
			),
			array( 'database', 'Backup failed for 3 weeks', 'Storage was full.', 'bad', 'Apr' ),
			array( 'shield-alert', 'Site flagged as unsafe', 'Old plugin, known exploit.', 'bad', 'Jun' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'alerts', $d );
