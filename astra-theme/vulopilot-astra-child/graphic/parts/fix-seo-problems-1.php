<?php
/**
 * Fix Seo Problems › hero: changes waiting for approval.
 * Graphic name: fix-seo-problems-1  ·  Shortcode: [vp_fix_seo_problems_1]  ·  /use-cases/fix-seo-problems/
 * Scene: graphic/parts/scenes/approvals.php  ·  Hero: no page title inside, fills the column height.
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="fix-seo-problems-1-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Fixes ready for you',
		'path' => 'Fix these first',
		'rows' => array(
			array( 'heading', 'Rewrite 12 missing descriptions', '/services/, /about/, /prices/ …' ),
			array( 'link2', 'Repair 4 broken internal links', '/blog/old-post/ → /blog/new-post/' ),
			array( 'file-x', 'Remove noindex from /services/', 'Set by mistake in 2023' ),
			array( 'list-tree', 'Submit the updated sitemap', 'Bing and Yandex via IndexNow' ),
		),
		'foot' => 'Approve one, approve all, or skip. Every change is logged and can be undone.',
		'foot_icon' => 'undo2',
	),
	isset( $args ) ? $args : array(),
	true
);
vpx_scene( 'approvals', $d );
