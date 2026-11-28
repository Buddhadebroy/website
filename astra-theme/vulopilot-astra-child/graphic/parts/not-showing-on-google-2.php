<?php
/**
 * Not Showing On Google › graphic 2: Google result preview, before and after.
 * Graphic name: not-showing-on-google-2  ·  Shortcode: [vp_not_showing_on_google_2]  ·  /problems/not-showing-on-google/
 * Scene: graphic/parts/scenes/serp.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="not-showing-on-google-2-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Google search',
		'path' => 'site: check',
		'query' => 'site:bristolflow.example',
		'site' => 'Bristol Flow Plumbing',
		'crumb' => 'bristolflow.example',
		'bad_title' => 'Your search did not match any documents',
		'bad_desc' => 'Suggestions: make sure the site is not blocking Google, and that it has a sitemap.',
		'good_title' => 'Emergency Plumber Bristol, 24/7 | Bristol Flow Plumbing',
		'good_desc' => 'Fixed-price call-outs across Bristol and Bath. On site in 60 minutes. Boilers, leaks and blocked drains.',
		'checks' => array(
			array( 'Google can crawl the site', 'bad', 'good' ),
			array( 'Sitemap submitted', 'bad', 'good' ),
			array( 'Homepage indexed', 'bad', 'good' ),
		),
		'before_tag' => 'Missing',
		'after_tag' => 'Indexed',
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'serp', $d );
