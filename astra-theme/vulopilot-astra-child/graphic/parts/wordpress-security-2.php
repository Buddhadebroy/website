<?php
/**
 * Wordpress Security › graphic 2: live firewall and login log.
 * Graphic name: wordpress-security-2  ·  Shortcode: [vp_wordpress_security_2]  ·  /solutions/wordpress-security/
 * Scene: graphic/parts/scenes/firewall.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="wordpress-security-2-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Login protection',
		'path' => 'Last 24 hours',
		'stats' => array( array( 'Attempts', 1284, 'bad' ), array( 'Blocked', 1284, 'good' ), array( 'Got in', 0, 'good' ) ),
		'rows' => array(
			array( '03:12', '185.220.x.x', 'tried "admin" 41 times', 'bad', 'Blocked' ),
			array( '03:14', '45.95.x.x', 'xmlrpc.php flood', 'bad', 'Blocked' ),
			array( '04:02', '91.240.x.x', 'tried "rosie" 12 times', 'bad', 'Locked out' ),
			array( '07:45', 'Your office', 'logged in with 2FA', 'good', 'Allowed' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'firewall', $d );
