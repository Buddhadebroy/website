<?php
/**
 * Pages Not Indexed › hero: index coverage and the reasons.
 * Graphic name: pages-not-indexed-1  ·  Shortcode: [vp_pages_not_indexed_1]  ·  /problems/pages-not-indexed/
 * Scene: graphic/parts/scenes/coverage.php  ·  Hero: no page title inside, fills the column height.
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="pages-not-indexed-1-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Index coverage',
		'path' => 'Search Console',
		'indexed' => 58,
		'total' => 142,
		'ok_label' => 'Indexed',
		'bad_label' => 'Not indexed',
		'reasons' => array(
			array( 'Crawled, currently not indexed', 38, 'bad', 'Thin or duplicate content' ),
			array( 'Discovered, not crawled', 21, 'warn', 'Not linked, not in the sitemap' ),
			array( 'Excluded by noindex', 17, 'bad', 'Tag pages and 3 real pages' ),
			array( 'Page with redirect', 8, 'low', 'Expected' ),
		),
		'foot' => 'VuloPilot turns each reason into a fix you can approve.',
		'foot_icon' => 'list-checks',
	),
	isset( $args ) ? $args : array(),
	true
);
vpx_scene( 'coverage', $d );
