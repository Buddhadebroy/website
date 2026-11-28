<?php
/**
 * Website Audit › graphic 2: site checkup tiles.
 * Graphic name: website-audit-2  ·  Shortcode: [vp_website_audit_2]  ·  /solutions/website-audit/
 * Scene: graphic/parts/scenes/checkup.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="website-audit-2-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Site checkup',
		'path' => 'Health area',
		'tiles' => array(
			array( 'server', 'PHP version', '8.0', 'warn', 'Update to 8.2 or newer' ),
			array( 'shield-check', 'SSL certificate', 'Valid', 'good', 'Renews in 61 days' ),
			array( 'database', 'Last backup', '9 days ago', 'bad', 'Daily backups are off' ),
			array( 'refresh-cw', 'WordPress core', '6.6.2', 'good', 'Up to date' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'checkup', $d );
