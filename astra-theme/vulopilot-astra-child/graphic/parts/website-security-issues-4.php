<?php
/**
 * Website Security Issues › graphic 4: security switches turning on.
 * Graphic name: website-security-issues-4  ·  Shortcode: [vp_website_security_issues_4]  ·  /problems/website-security-issues/
 * Scene: graphic/parts/scenes/hardening.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="website-security-issues-4-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'So it does not happen again',
		'path' => 'Security',
		'score' => 90,
		'rows' => array(
			array( 'key-round', 'New passwords for all admins', false, 'Old ones may be known' ),
			array( 'key-round', 'Two-factor login', false, '' ),
			array( 'file-x', 'Block PHP in uploads', false, 'Stops this backdoor type' ),
			array( 'refresh-cw', 'Auto-update security fixes', false, '' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'hardening', $d );
