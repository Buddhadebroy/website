<?php
/**
 * Improve Website Performance › graphic 4: heavy images and their savings.
 * Graphic name: improve-website-performance-4  ·  Shortcode: [vp_improve_website_performance_4]  ·  /use-cases/improve-website-performance/
 * Scene: graphic/parts/scenes/images.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="improve-website-performance-4-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Images converted',
		'path' => 'WebP · resized',
		'total_label' => 'Saved across the site',
		'total' => '212 MB',
		'rows' => array(
			array( 'portfolio-garden-1.jpg', '3.1 MB', '240 KB', 8 ),
			array( 'team.png', '1.4 MB', '120 KB', 9 ),
			array( 'before-after.jpg', '2.2 MB', '190 KB', 9 ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'images', $d );
