<?php
/**
 * Improve Search Visibility › hero: URLs with their status codes.
 * Graphic name: improve-search-visibility-1  ·  Shortcode: [vp_improve_search_visibility_1]  ·  /use-cases/improve-search-visibility/
 * Scene: graphic/parts/scenes/sitemap.php  ·  Hero: no page title inside, fills the column height.
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="improve-search-visibility-1-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Pages Google can find',
		'path' => 'Sitemap + IndexNow',
		'rows' => array(
			array( '/garden-design-bristol/', '200', 'In sitemap, indexed' ),
			array( '/planting-plans/', '200', 'Added to sitemap today' ),
			array( '/portfolio/', '301', 'Moved to /projects/' ),
			array( '/old-offers/', '404', 'Removed from sitemap' ),
			array( '/projects/?page=2', 'noindex', 'Kept out on purpose' ),
		),
		'ping' => 'New and changed pages sent to Bing and Yandex in seconds',
		'ping_tag' => 'IndexNow',
		'foot' => 'A clean sitemap means crawlers spend their time on pages that can rank.',
		'foot_icon' => 'list-tree',
	),
	isset( $args ) ? $args : array(),
	true
);
vpx_scene( 'sitemap', $d );
