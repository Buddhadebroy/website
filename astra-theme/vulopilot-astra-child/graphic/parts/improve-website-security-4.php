<?php
/**
 * Improve Website Security › graphic 4: backup timeline.
 * Graphic name: improve-website-security-4  ·  Shortcode: [vp_improve_website_security_4]  ·  /use-cases/improve-website-security/
 * Scene: graphic/parts/scenes/backups.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="improve-website-security-4-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Safety net',
		'path' => 'Off-site backups',
		'restore' => 'Restore',
		'rows' => array(
			array( 'Today 03:00', 'Stored off-site, encrypted', '2.1 GB', true ),
			array( 'Yesterday 03:00', 'Stored off-site', '2.1 GB', false ),
			array( 'Before hardening', 'Automatic snapshot', '2.0 GB', false ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'backups', $d );
