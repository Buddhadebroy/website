<?php
/**
 * Automate Website Management › graphic 3: hours by hand versus automated.
 * Graphic name: automate-website-management-3  ·  Shortcode: [vp_automate_website_management_3]  ·  /use-cases/automate-website-management/
 * Scene: graphic/parts/scenes/hours.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="automate-website-management-3-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Hours saved',
		'path' => 'Per month, 1 site',
		'rows' => array(
			array( 'Updates and testing', 4, 0.3 ),
			array( 'Backups', 1.5, 0 ),
			array( 'Security checks', 2, 0.1 ),
			array( 'Speed checks', 1.5, 0.1 ),
			array( 'Reporting', 2, 0.2 ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'hours', $d );
