<?php
/**
 * Wordpress Seo › graphic 3: internal link map with the orphan page.
 * Graphic name: wordpress-seo-3  ·  Shortcode: [vp_wordpress_seo_3]  ·  /solutions/wordpress-seo/
 * Scene: graphic/parts/scenes/links.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="wordpress-seo-3-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Internal links',
		'path' => 'Link map',
		'nodes' => array( 'Home', 'Cakes', 'Prices', 'About', 'Blog', 'Contact', 'Vegan cakes' ),
		'orphan' => 6,
		'finding' => '"Vegan cakes" has no links pointing to it, so Google treats it as unimportant.',
		'btn' => 'Link it',
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'links', $d );
