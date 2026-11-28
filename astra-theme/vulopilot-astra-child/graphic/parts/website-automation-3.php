<?php
/**
 * Website Automation › graphic 3: hours by hand versus automated.
 * Graphic name: website-automation-3  ·  Shortcode: [vp_website_automation_3]  ·  /solutions/website-automation/
 * Scene: graphic/parts/scenes/hours.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="website-automation-3-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Time back',
		'path' => 'Per month',
		'rows' => array(
			array( 'Checking for problems', 6, 0.2 ),
			array( 'Plugin and core updates', 3, 0.5 ),
			array( 'Backups and restores', 2, 0 ),
			array( 'Client or boss reports', 4, 0.3 ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'hours', $d );
