<?php
/**
 * Improve Search Visibility › graphic 4: internal link map with the orphan page.
 * Graphic name: improve-search-visibility-4  ·  Shortcode: [vp_improve_search_visibility_4]  ·  /use-cases/improve-search-visibility/
 * Scene: graphic/parts/scenes/links.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="improve-search-visibility-4-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Internal links',
		'path' => 'Link map',
		'nodes' => array( 'Home', 'Design', 'Projects', 'Prices', 'Blog', 'Contact', 'Planting plans' ),
		'orphan' => 6,
		'finding' => 'Planting plans gets no links from your blog, so it struggles to rank.',
		'btn' => 'Add 3 links',
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'links', $d );
