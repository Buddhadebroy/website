<?php
/**
 * Wordpress Security › graphic 3: plugins with known vulnerabilities.
 * Graphic name: wordpress-security-3  ·  Shortcode: [vp_wordpress_security_3]  ·  /solutions/wordpress-security/
 * Scene: graphic/parts/scenes/plugins.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="wordpress-security-3-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Plugins with known holes',
		'path' => 'Plugins · Themes',
		'rows' => array(
			array( 'Contact Form X', '5.1.2', '5.1.4', 'vuln', 'CVE · unauthenticated upload' ),
			array( 'Slider Pro', '3.2.0', '3.4.1', 'vuln', 'XSS in shortcode' ),
			array( 'Astra', '4.6.1', '4.8.0', 'outdated', '' ),
			array( 'WooCommerce', '9.3.3', '9.3.3', 'ok', '' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'plugins', $d );
