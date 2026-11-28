<?php
/**
 * Fix Seo Problems › graphic 4: audit result by category.
 * Graphic name: fix-seo-problems-4  ·  Shortcode: [vp_fix_seo_problems_4]  ·  /use-cases/fix-seo-problems/
 * Scene: graphic/parts/scenes/audit-grid.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="fix-seo-problems-4-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Still open',
		'path' => 'After this round',
		'total' => 6,
		'score' => 87,
		'total_label' => 'Problems left',
		'tiles' => array(
			array( 'heading', 'Titles', 0, 'good' ),
			array( 'file-text', 'Descriptions', 0, 'good' ),
			array( 'link2', 'Links', 2, 'warn' ),
			array( 'image', 'Alt text', 4, 'warn' ),
			array( 'code', 'Schema', 0, 'good' ),
			array( 'search-check', 'Indexing', 0, 'good' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'audit-grid', $d );
