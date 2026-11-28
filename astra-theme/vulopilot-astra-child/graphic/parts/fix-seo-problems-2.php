<?php
/**
 * Fix Seo Problems › graphic 2: the file or tag behind the problem, and the fix.
 * Graphic name: fix-seo-problems-2  ·  Shortcode: [vp_fix_seo_problems_2]  ·  /use-cases/fix-seo-problems/
 * Scene: graphic/parts/scenes/code.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="fix-seo-problems-2-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'One of the fixes',
		'path' => '/services/ · <head>',
		'tabs' => array( '<head>' ),
		'lines' => array(
			array( 'com', '<!-- set by an old SEO plugin -->' ),
			array( 'del', '<meta name="robots" content="noindex, nofollow">' ),
			array( 'add', '<meta name="robots" content="index, follow">' ),
			array( 'plain', '<title>Garden Design Services in Bristol</title>' ),
		),
		'notes' => array( array( 'Your most important page can show in Google again', 'good' ) ),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'code', $d );
