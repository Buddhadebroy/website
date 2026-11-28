<?php
/**
 * Website Health Issues › graphic 2: site checkup tiles.
 * Graphic name: website-health-issues-2  ·  Shortcode: [vp_website_health_issues_2]  ·  /problems/website-health-issues/
 * Scene: graphic/parts/scenes/checkup.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="website-health-issues-2-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Health check',
		'path' => 'Site Health',
		'tiles' => array(
			array( 'server', 'PHP', '7.4', 'bad', 'End of life' ),
			array( 'cpu', 'Memory limit', '128 MB', 'warn', '256 MB advised' ),
			array( 'mail', 'Email sending', 'Failing', 'bad', 'Host blocks mail()' ),
			array( 'archive', 'Disk', '97%', 'bad', 'Almost full' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'checkup', $d );
