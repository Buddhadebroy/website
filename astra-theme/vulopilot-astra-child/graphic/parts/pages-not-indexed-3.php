<?php
/**
 * Pages Not Indexed › graphic 3: internal link map with the orphan page.
 * Graphic name: pages-not-indexed-3  ·  Shortcode: [vp_pages_not_indexed_3]  ·  /problems/pages-not-indexed/
 * Scene: graphic/parts/scenes/links.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="pages-not-indexed-3-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Pages nobody links to',
		'path' => 'Orphan pages',
		'nodes' => array( 'Home', 'Services', 'Areas', 'Blog', 'About', 'Contact', 'Keynsham' ),
		'orphan' => 6,
		'finding' => '12 area pages have no internal links, so Google rarely crawls them.',
		'btn' => 'Fix links',
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'links', $d );
