<?php
/**
 * Automate Website Management › graphic 4: changes waiting for approval.
 * Graphic name: automate-website-management-4  ·  Shortcode: [vp_automate_website_management_4]  ·  /use-cases/automate-website-management/
 * Scene: graphic/parts/scenes/approvals.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="automate-website-management-4-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Waiting for you',
		'path' => 'Inbox',
		'rows' => array(
			array( 'refresh-cw', 'Update Elementor 3.24 → 3.25', 'Backup taken · tested on staging' ),
			array( 'message-square', 'Delete 2,400 spam comments', 'Older than 30 days' ),
			array( 'database', 'Optimise database tables', 'Frees 180 MB' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'approvals', $d );
