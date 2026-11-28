<?php
/**
 * Not Showing On Google › hero: the file or tag behind the problem, and the fix.
 * Graphic name: not-showing-on-google-1  ·  Shortcode: [vp_not_showing_on_google_1]  ·  /problems/not-showing-on-google/
 * Scene: graphic/parts/scenes/code.php  ·  Hero: no page title inside, fills the column height.
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="not-showing-on-google-1-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Why Google skips you',
		'path' => 'Settings → Reading',
		'tabs' => array( '<head>', 'robots.txt' ),
		'lines' => array(
			array( 'com', '<!-- every page -->' ),
			array( 'bad', '<meta name="robots" content="noindex, nofollow">' ),
			array( 'plain', '<title>Bristol Flow Plumbing</title>' ),
			array( 'plain', '' ),
			array( 'com', '# "Discourage search engines" is ticked' ),
			array( 'add', '<meta name="robots" content="index, follow">' ),
		),
		'notes' => array(
			array( '"Discourage search engines" was left on after launch', 'bad' ),
			array( 'Turned off and every page re-submitted', 'good' ),
		),
		'foot' => 'One tick box can hide a whole site. VuloPilot checks it on every scan.',
		'foot_icon' => 'search-x',
	),
	isset( $args ) ? $args : array(),
	true
);
vpx_scene( 'code', $d );
