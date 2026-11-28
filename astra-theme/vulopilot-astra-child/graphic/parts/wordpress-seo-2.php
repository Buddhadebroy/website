<?php
/**
 * Wordpress Seo › graphic 2: findings sorted by impact.
 * Graphic name: wordpress-seo-2  ·  Shortcode: [vp_wordpress_seo_2]  ·  /solutions/wordpress-seo/
 * Scene: graphic/parts/scenes/issues.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="wordpress-seo-2-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'SEO problems, ranked',
		'path' => '42 pages scanned',
		'meta' => 'Sorted by traffic at risk',
		'rows' => array(
			array( 'medium', '2 pages share the same title', '/cakes/ and /cakes-2/', 2 ),
			array( 'high', 'Sitemap not set up', 'Search engines have to find pages on their own', 0 ),
			array( 'low', '9 images have no alt text', 'Gallery, menu, about', 3 ),
			array( 'high', '12 pages have no description', 'Google writes its own, usually badly', 1 ),
			array( 'medium', '4 links point at missing pages', 'Visitors and crawlers hit a 404', 4 ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'issues', $d );
