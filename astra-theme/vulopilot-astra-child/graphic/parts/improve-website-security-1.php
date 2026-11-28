<?php
/**
 * Improve Website Security › hero: live firewall and login log.
 * Graphic name: improve-website-security-1  ·  Shortcode: [vp_improve_website_security_1]  ·  /use-cases/improve-website-security/
 * Scene: graphic/parts/scenes/firewall.php  ·  Hero: no page title inside, fills the column height.
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="improve-website-security-1-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Firewall',
		'path' => 'Live',
		'stats' => array(
			array( 'Attacks today', 342, 'bad' ),
			array( 'Blocked', 342, 'good' ),
			array( 'Bad bots', 57, 'warn' ),
		),
		'rows' => array(
			array( '10:02', 'SQL injection', 'on /?s= search', 'bad', 'Blocked' ),
			array( '10:04', 'Fake Googlebot', 'crawling /wp-admin/', 'bad', 'Blocked' ),
			array( '10:11', '185.191.x.x', 'brute force on wp-login', 'bad', 'Banned 24h' ),
			array( '10:15', 'Uptime monitor', 'allowed list', 'good', 'Allowed' ),
		),
		'foot' => 'Attacks are stopped before they reach WordPress.',
		'foot_icon' => 'shield-check',
	),
	isset( $args ) ? $args : array(),
	true
);
vpx_scene( 'firewall', $d );
