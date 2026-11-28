<?php
/**
 * Wordpress Seo › graphic 4: changes waiting for approval.
 * Graphic name: wordpress-seo-4  ·  Shortcode: [vp_wordpress_seo_4]  ·  /solutions/wordpress-seo/
 * Scene: graphic/parts/scenes/approvals.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="wordpress-seo-4-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Ready to apply',
		'path' => 'Waiting for you',
		'rows' => array(
			array( 'heading', 'New title for /wedding-cakes/', 'Home → Wedding Cakes in Leeds…' ),
			array( 'file-text', 'Description for /prices/', '148 characters, written from the page' ),
			array( 'link2', 'Redirect /cakes-2/ to /cakes/', 'Fixes the duplicate title' ),
			array( 'image', 'Alt text for 9 gallery images', '"Three-tier lemon cake with roses"…' ),
		),
		'foot' => 'Nothing changes on your site until you approve it, and every change can be undone.',
		'foot_icon' => 'undo2',
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'approvals', $d );
