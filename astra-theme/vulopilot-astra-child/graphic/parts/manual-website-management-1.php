<?php
/**
 * Manual Website Management › hero: hours by hand versus automated.
 * Graphic name: manual-website-management-1  ·  Shortcode: [vp_manual_website_management_1]  ·  /problems/manual-website-management/
 * Scene: graphic/parts/scenes/hours.php  ·  Hero: no page title inside, fills the column height.
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="manual-website-management-1-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Your month, by hand',
		'path' => 'Hours per month',
		'rows' => array(
			array( 'Logging in to check things', 5, 0.2 ),
			array( 'Updating plugins one by one', 3, 0.3 ),
			array( 'Fixing what updates broke', 4, 0.5 ),
			array( 'Copying numbers into reports', 3, 0.2 ),
			array( 'Checking backups worked', 1, 0 ),
		),
		'foot' => 'About 16 hours a month of checking, mostly finding nothing wrong.',
		'foot_icon' => 'hourglass',
	),
	isset( $args ) ? $args : array(),
	true
);
vpx_scene( 'hours', $d );
