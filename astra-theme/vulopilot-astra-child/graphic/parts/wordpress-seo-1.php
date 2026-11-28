<?php
/**
 * Wordpress Seo › hero: Google result preview, before and after.
 * Graphic name: wordpress-seo-1  ·  Shortcode: [vp_wordpress_seo_1]  ·  /solutions/wordpress-seo/
 * Scene: graphic/parts/scenes/serp.php  ·  Hero: no page title inside, fills the column height.
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="wordpress-seo-1-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Google preview',
		'path' => 'Homepage',
		'query' => 'wedding cakes leeds',
		'site' => 'Rosie Bakes',
		'crumb' => 'rosiebakes.co.uk › wedding-cakes',
		'bad_title' => 'Home',
		'bad_desc' => 'Skip to content. Menu. Home. About. Shop. Contact. © 2024 All rights reserved.',
		'good_title' => 'Wedding Cakes in Leeds, Handmade to Order | Rosie Bakes',
		'good_desc' => 'Custom wedding cakes baked in Leeds. Tastings every Saturday, free delivery within 20 miles. See flavours and prices.',
		'checks' => array(
			array( 'Title says what the page is about', 'bad', 'good' ),
			array( 'Title fits in a search result (under 60 characters)', 'bad', 'good' ),
			array( 'Description invites the click (70 to 160 characters)', 'bad', 'good' ),
		),
		'foot' => 'Google rewrites weak titles. VuloPilot writes one per page for you to approve.',
		'foot_icon' => 'wand-sparkles',
	),
	isset( $args ) ? $args : array(),
	true
);
vpx_scene( 'serp', $d );
