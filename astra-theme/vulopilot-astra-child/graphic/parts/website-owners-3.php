<?php
/**
 * Website Owners › graphic 3: changes waiting for approval.
 * Graphic name: website-owners-3  ·  Shortcode: [vp_website_owners_3]  ·  /for/website-owners/
 * Scene: graphic/parts/scenes/approvals.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="website-owners-3-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Two things for you',
		'path' => 'Takes 30 seconds',
		'rows' => array(
			array( 'heading', 'Better title for "Shop"', 'Handmade Oak Furniture | Oak & Leaf' ),
			array( 'image', 'Add alt text to 6 photos', 'Written from the photo content' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'approvals', $d );
