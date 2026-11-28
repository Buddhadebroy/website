<?php
/**
 * Wordpress Security › hero: security switches turning on.
 * Graphic name: wordpress-security-1  ·  Shortcode: [vp_wordpress_security_1]  ·  /solutions/wordpress-security/
 * Scene: graphic/parts/scenes/hardening.php  ·  Hero: no page title inside, fills the column height.
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="wordpress-security-1-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Security settings',
		'path' => 'Security',
		'score' => 92,
		'score_label' => 'Security score',
		'rows' => array(
			array( 'key-round', 'Two-factor login for admins', false, 'Stops stolen passwords working' ),
			array( 'ban', 'Limit login attempts', false, 'Locks out after 5 tries' ),
			array( 'shield', 'Firewall', true, 'Blocks known attack patterns' ),
			array( 'eye-off', 'Hide WordPress version', false, 'Gives attackers less to go on' ),
			array( 'file-x', 'Block PHP in uploads', false, 'Stops uploaded backdoors running' ),
		),
		'foot' => 'Each switch says in plain English what it protects you from.',
		'foot_icon' => 'info',
	),
	isset( $args ) ? $args : array(),
	true
);
vpx_scene( 'hardening', $d );
