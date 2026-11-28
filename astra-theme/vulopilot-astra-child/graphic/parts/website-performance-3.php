<?php
/**
 * Website Performance › graphic 3: heavy images and their savings.
 * Graphic name: website-performance-3  ·  Shortcode: [vp_website_performance_3]  ·  /solutions/website-performance/
 * Scene: graphic/parts/scenes/images.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="website-performance-3-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Image optimisation',
		'path' => 'Media library',
		'total_label' => 'Homepage weight',
		'total' => '6.8 MB → 1.1 MB',
		'rows' => array(
			array( 'hero-wedding.jpg', '2.4 MB', '210 KB', 9 ),
			array( 'gallery-01.png', '1.6 MB', '140 KB', 9 ),
			array( 'team-photo.jpg', '980 KB', '96 KB', 10 ),
			array( 'logo-large.png', '420 KB', '18 KB', 5 ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'images', $d );
