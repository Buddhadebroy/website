<?php
/**
 * Improve Website Security › graphic 2: security switches turning on.
 * Graphic name: improve-website-security-2  ·  Shortcode: [vp_improve_website_security_2]  ·  /use-cases/improve-website-security/
 * Scene: graphic/parts/scenes/hardening.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="improve-website-security-2-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Quick hardening',
		'path' => '6 switches',
		'score' => 94,
		'rows' => array(
			array( 'key-round', 'Two-factor login', false, 'For every admin account' ),
			array( 'user', 'Rename default "admin" user', false, 'Most attacks guess "admin"' ),
			array( 'lock', 'Force HTTPS everywhere', true, '' ),
			array( 'file-x', 'Disable file editing in wp-admin', false, '' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'hardening', $d );
