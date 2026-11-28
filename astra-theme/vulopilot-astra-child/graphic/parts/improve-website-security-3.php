<?php
/**
 * Improve Website Security › graphic 3: trend chart with the moment the fix shipped.
 * Graphic name: improve-website-security-3  ·  Shortcode: [vp_improve_website_security_3]  ·  /use-cases/improve-website-security/
 * Scene: graphic/parts/scenes/traffic.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="improve-website-security-3-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Security score',
		'path' => 'Last 8 weeks',
		'stat_label' => 'Security score',
		'stat' => 94,
		'stat_suffix' => '/100',
		'delta' => '+39',
		'delta_state' => 'good',
		'points' => array( 55, 55, 56, 58, 72, 80, 86, 90, 92, 93, 94, 94 ),
		'marker' => 3,
		'marker_label' => 'Hardening on',
		'axis' => array( 'Week 1', 'Week 4', 'Week 8' ),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'traffic', $d );
