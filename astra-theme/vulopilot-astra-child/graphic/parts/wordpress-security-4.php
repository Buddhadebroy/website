<?php
/**
 * Wordpress Security › graphic 4: file and malware scan.
 * Graphic name: wordpress-security-4  ·  Shortcode: [vp_wordpress_security_4]  ·  /solutions/wordpress-security/
 * Scene: graphic/parts/scenes/malware.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="wordpress-security-4-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'File integrity',
		'path' => 'Daily scan',
		'progress' => 'Comparing core files with wordpress.org',
		'files' => 3906,
		'rows' => array(
			array( 'wp-admin/', 'clean', 'Matches core' ),
			array( 'wp-includes/', 'clean', 'Matches core' ),
			array( 'wp-content/uploads/2024/10/x.php', 'flag', 'Removed' ),
			array( '.htaccess', 'changed', 'Changed 03:20' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'malware', $d );
