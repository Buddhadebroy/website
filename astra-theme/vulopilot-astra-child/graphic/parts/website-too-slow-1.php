<?php
/**
 * Website Too Slow › hero: heavy images and their savings.
 * Graphic name: website-too-slow-1  ·  Shortcode: [vp_website_too_slow_1]  ·  /problems/website-too-slow/
 * Scene: graphic/parts/scenes/images.php  ·  Hero: no page title inside, fills the column height.
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="website-too-slow-1-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'The heaviest files',
		'path' => 'Homepage',
		'total_label' => 'Homepage weight',
		'total' => '9.4 MB → 1.3 MB',
		'rows' => array(
			array( 'hero-slider-1.jpg', '3.8 MB', '260 KB', 7 ),
			array( 'hero-slider-2.jpg', '3.1 MB', '230 KB', 7 ),
			array( 'about-team.png', '1.2 MB', '110 KB', 9 ),
			array( 'icon-set.png', '640 KB', '24 KB', 4 ),
		),
		'foot' => 'Images are usually most of the weight. Converting them is the biggest single win.',
		'foot_icon' => 'image',
	),
	isset( $args ) ? $args : array(),
	true
);
vpx_scene( 'images', $d );
