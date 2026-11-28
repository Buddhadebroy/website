<?php
/**
 * Website Security Issues › hero: file and malware scan.
 * Graphic name: website-security-issues-1  ·  Shortcode: [vp_website_security_issues_1]  ·  /problems/website-security-issues/
 * Scene: graphic/parts/scenes/malware.php  ·  Hero: no page title inside, fills the column height.
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="website-security-issues-1-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Malware scan',
		'path' => 'Full site',
		'progress' => 'Scanning files and database',
		'files' => 6120,
		'rows' => array(
			array( 'wp-content/uploads/2024/09/icon.php', 'flag', 'Backdoor' ),
			array( 'wp-includes/load.php', 'changed', 'Modified core' ),
			array( 'wp_options: siteurl', 'flag', 'Spam redirect' ),
			array( 'wp-content/themes/', 'clean', 'Clean' ),
		),
		'foot' => 'Found, explained in plain English, and cleaned with your approval.',
		'foot_icon' => 'shield-alert',
	),
	isset( $args ) ? $args : array(),
	true
);
vpx_scene( 'malware', $d );
