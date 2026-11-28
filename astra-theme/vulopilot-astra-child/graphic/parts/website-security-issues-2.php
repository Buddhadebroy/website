<?php
/**
 * Website Security Issues › graphic 2: plugins with known vulnerabilities.
 * Graphic name: website-security-issues-2  ·  Shortcode: [vp_website_security_issues_2]  ·  /problems/website-security-issues/
 * Scene: graphic/parts/scenes/plugins.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="website-security-issues-2-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'How they got in',
		'path' => 'Plugins',
		'rows' => array(
			array( 'File Manager', '6.0', '7.2', 'vuln', 'Known exploit, actively attacked' ),
			array( 'Old Gallery', '1.4', '—', 'vuln', 'Abandoned, remove it' ),
			array( 'Elementor', '3.18', '3.25', 'outdated', '' ),
		),
		'done' => 'Fixed',
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'plugins', $d );
