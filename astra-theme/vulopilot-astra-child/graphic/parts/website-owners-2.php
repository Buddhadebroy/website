<?php
/**
 * Website Owners › graphic 2: site checkup tiles.
 * Graphic name: website-owners-2  ·  Shortcode: [vp_website_owners_2]  ·  /for/website-owners/
 * Scene: graphic/parts/scenes/checkup.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="website-owners-2-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Your site at a glance',
		'path' => 'Dashboard',
		'tiles' => array(
			array( 'search', 'Google', 'Visible', 'good', '86 pages indexed' ),
			array( 'zap', 'Speed', 'Fast', 'good', '1.4 s on mobile' ),
			array( 'shield-check', 'Security', 'Safe', 'good', 'No issues' ),
			array( 'database', 'Backups', 'Daily', 'good', '30 kept' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'checkup', $d );
