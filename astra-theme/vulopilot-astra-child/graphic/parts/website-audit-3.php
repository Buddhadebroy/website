<?php
/**
 * Website Audit › graphic 3: file and malware scan.
 * Graphic name: website-audit-3  ·  Shortcode: [vp_website_audit_3]  ·  /solutions/website-audit/
 * Scene: graphic/parts/scenes/malware.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="website-audit-3-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Security pass',
		'path' => 'wp-content',
		'progress' => 'Checking files against known-good copies',
		'files' => 5214,
		'rows' => array(
			array( 'wp-content/plugins/contact-form-x/', 'clean', 'Clean' ),
			array( 'wp-content/themes/astra-child/functions.php', 'changed', 'Changed 2 days ago' ),
			array( 'wp-content/uploads/2024/11/cache.php', 'flag', 'PHP in uploads' ),
			array( 'wp-includes/', 'clean', 'Matches core' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'malware', $d );
