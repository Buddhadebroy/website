<?php
/**
 * Pages Not Indexed › graphic 2: URLs with their status codes.
 * Graphic name: pages-not-indexed-2  ·  Shortcode: [vp_pages_not_indexed_2]  ·  /problems/pages-not-indexed/
 * Scene: graphic/parts/scenes/sitemap.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="pages-not-indexed-2-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'What the sitemap says',
		'path' => '/sitemap.xml',
		'rows' => array(
			array( '/services/gas-safety/', 'noindex', 'Real page, tagged noindex by mistake' ),
			array( '/blog/page/7/', '200', 'Pagination, should not be in the sitemap' ),
			array( '/old-services/', '301', 'Redirect, remove from sitemap' ),
			array( '/areas/keynsham/', '200', 'Missing from sitemap' ),
		),
		'ping' => 'Updated sitemap and changed URLs sent via IndexNow',
		'ping_tag' => 'Sent',
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'sitemap', $d );
