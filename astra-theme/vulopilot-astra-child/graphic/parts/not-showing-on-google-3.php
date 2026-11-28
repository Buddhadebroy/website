<?php
/**
 * Not Showing On Google › graphic 3: URLs with their status codes.
 * Graphic name: not-showing-on-google-3  ·  Shortcode: [vp_not_showing_on_google_3]  ·  /problems/not-showing-on-google/
 * Scene: graphic/parts/scenes/sitemap.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="not-showing-on-google-3-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Sitemap',
		'path' => '/sitemap_index.xml',
		'rows' => array(
			array( '/', '200', 'Submitted' ),
			array( '/emergency-plumber/', '200', 'Submitted' ),
			array( '/boiler-repair/', '200', 'Submitted' ),
			array( '/sample-page/', '404', 'Removed' ),
		),
		'ping' => 'Sitemap sent to Google Search Console and IndexNow',
		'ping_tag' => 'Sent',
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'sitemap', $d );
