<?php
/**
 * Optimize Website Content › graphic 3: keyword positions, before and after.
 * Graphic name: optimize-website-content-3  ·  Shortcode: [vp_optimize_website_content_3]  ·  /use-cases/optimize-website-content/
 * Scene: graphic/parts/scenes/rank.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="optimize-website-content-3-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Updated posts',
		'path' => 'Before and after refresh',
		'rows' => array(
			array( 'when to plant roses', 17, 4, 'Refreshed 3 weeks ago' ),
			array( 'rose feeding schedule', 24, 9, 'Added FAQ block' ),
			array( 'climbing roses for walls', 12, 6, 'New images + alt text' ),
			array( 'rose black spot', 8, 3, 'Merged 2 thin posts' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'rank', $d );
