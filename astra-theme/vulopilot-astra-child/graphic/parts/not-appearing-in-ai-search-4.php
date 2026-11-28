<?php
/**
 * Not Appearing In Ai Search › graphic 4: business facts AI engines read.
 * Graphic name: not-appearing-in-ai-search-4  ·  Shortcode: [vp_not_appearing_in_ai_search_4]  ·  /problems/not-appearing-in-ai-search/
 * Scene: graphic/parts/scenes/entity.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="not-appearing-in-ai-search-4-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'What AI needs to know',
		'path' => 'Business information',
		'name' => 'Bristol Flow Plumbing',
		'kind' => 'Plumber · Bristol',
		'score' => 58,
		'facts' => array(
			array( 'Services', 'Emergency, boilers, drains', 'good' ),
			array( 'Hours', '24/7', 'warn', 'Only in an image' ),
			array( 'Prices', 'Not on the site', 'bad', 'Missing' ),
			array( 'Area', 'Bristol, Bath, Keynsham', 'good' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'entity', $d );
