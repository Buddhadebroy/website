<?php
/**
 * Seo Audit › hero: audit result by category.
 * Graphic name: seo-audit-1  ·  Shortcode: [vp_seo_audit_1]  ·  /solutions/seo-audit/
 * Scene: graphic/parts/scenes/audit-grid.php  ·  Hero: no page title inside, fills the column height.
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="seo-audit-1-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Audit summary',
		'path' => 'Scan of 128 URLs · 4 min',
		'total' => 37,
		'score' => 64,
		'total_label' => 'Issues across 6 areas',
		'tiles' => array(
			array( 'network', 'Crawling', 4, 'warn' ),
			array( 'search-check', 'Indexing', 7, 'bad' ),
			array( 'heading', 'Titles & headings', 11, 'bad' ),
			array( 'code', 'Schema', 3, 'warn' ),
			array( 'link2', 'Links', 9, 'warn' ),
			array( 'image', 'Images', 3, 'good' ),
		),
		'foot' => 'Every number opens the exact pages behind it.',
		'foot_icon' => 'list-tree',
	),
	isset( $args ) ? $args : array(),
	true
);
vpx_scene( 'audit-grid', $d );
