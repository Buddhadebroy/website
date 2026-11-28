<?php
/**
 * Manual Website Management › graphic 4: site checkup tiles.
 * Graphic name: manual-website-management-4  ·  Shortcode: [vp_manual_website_management_4]  ·  /problems/manual-website-management/
 * Scene: graphic/parts/scenes/checkup.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="manual-website-management-4-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Everything in one place',
		'path' => 'Dashboard',
		'tiles' => array(
			array( 'mail', 'Contact form', 'Sending', 'good', 'Test sent 06:00' ),
			array( 'database', 'Backup', 'Last night', 'good', 'Verified' ),
			array( 'refresh-cw', 'Updates', '2 waiting', 'warn', 'Approve in one click' ),
			array( 'shield-check', 'Security', 'No issues', 'good', 'Scanned 06:10' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'checkup', $d );
