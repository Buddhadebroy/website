<?php
/**
 * Website Owners › graphic 4: plugins with known vulnerabilities.
 * Graphic name: website-owners-4  ·  Shortcode: [vp_website_owners_4]  ·  /for/website-owners/
 * Scene: graphic/parts/scenes/plugins.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="website-owners-4-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Updates handled',
		'path' => 'Plugins · Themes',
		'done' => 'Updated',
		'rows' => array(
			array( 'WooCommerce', '9.2.1', '9.3.3', 'outdated', 'Backup taken first' ),
			array( 'Contact Form X', '5.1.2', '5.1.4', 'vuln', 'Security fix' ),
			array( 'Astra', '4.7.0', '4.8.0', 'outdated', '' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'plugins', $d );
