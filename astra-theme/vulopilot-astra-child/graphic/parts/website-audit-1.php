<?php
/**
 * Website Audit › hero: findings sorted by impact.
 * Graphic name: website-audit-1  ·  Shortcode: [vp_website_audit_1]  ·  /solutions/website-audit/
 * Scene: graphic/parts/scenes/issues.php  ·  Hero: no page title inside, fills the column height.
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="website-audit-1-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Whole-site audit',
		'path' => 'SEO · speed · security · health',
		'meta' => 'One list, not four reports',
		'before' => 'As found',
		'after' => 'Fix in this order',
		'rows' => array(
			array( 'low', 'Image alt text missing on 14 images', 'SEO', 4 ),
			array( 'critical', 'Outdated plugin with a known vulnerability', 'Security', 0 ),
			array( 'medium', 'Homepage loads in 4.1 s on mobile', 'Speed', 2 ),
			array( 'high', 'No backup in the last 9 days', 'Health', 1 ),
			array( 'medium', '12 pages without a meta description', 'SEO', 3 ),
			array( 'low', 'PHP 8.0 reaches end of life', 'Health', 5 ),
		),
		'foot' => 'Security and data loss come first, cosmetic SEO last.',
		'foot_icon' => 'list-ordered',
	),
	isset( $args ) ? $args : array(),
	true
);
vpx_scene( 'issues', $d );
