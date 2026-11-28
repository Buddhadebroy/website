<?php
/**
 * Core Web Vitals › graphic 3: the file or tag behind the problem, and the fix.
 * Graphic name: core-web-vitals-3  ·  Shortcode: [vp_core_web_vitals_3]  ·  /solutions/core-web-vitals/
 * Scene: graphic/parts/scenes/code.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="core-web-vitals-3-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'The fix for LCP',
		'path' => 'header.php',
		'tabs' => array( 'header.php' ),
		'lines' => array(
			array( 'com', '<!-- hero image -->' ),
			array( 'del', '<img src="hero.jpg" loading="lazy">' ),
			array( 'add', '<link rel="preload" as="image" href="hero.webp">' ),
			array( 'add', '<img src="hero.webp" fetchpriority="high" width="1200" height="640">' ),
		),
		'notes' => array(
			array( 'The hero image no longer waits to be lazy-loaded', 'good' ),
			array( 'Width and height stop the layout jumping (CLS)', 'good' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'code', $d );
