<?php
/**
 * Website Security Issues › graphic 3: live firewall and login log.
 * Graphic name: website-security-issues-3  ·  Shortcode: [vp_website_security_issues_3]  ·  /problems/website-security-issues/
 * Scene: graphic/parts/scenes/firewall.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="website-security-issues-3-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Still trying',
		'path' => 'After cleanup',
		'stats' => array(
			array( 'Attacks blocked', 928, 'good' ),
			array( 'Backdoor hits', 211, 'bad' ),
			array( 'Got in', 0, 'good' ),
		),
		'rows' => array(
			array( '11:20', 'GET /wp-content/uploads/…/icon.php', 'backdoor request', 'bad', '404' ),
			array( '11:22', 'POST /xmlrpc.php', 'password spray', 'bad', 'Blocked' ),
			array( '11:31', '46.x.x.x', 'File Manager exploit', 'bad', 'Blocked' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'firewall', $d );
