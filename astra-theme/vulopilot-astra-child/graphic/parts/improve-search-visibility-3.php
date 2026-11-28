<?php
/**
 * Improve Search Visibility › graphic 3: Google result preview, before and after.
 * Graphic name: improve-search-visibility-3  ·  Shortcode: [vp_improve_search_visibility_3]  ·  /use-cases/improve-search-visibility/
 * Scene: graphic/parts/scenes/serp.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="improve-search-visibility-3-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'How you show up',
		'path' => '/planting-plans/',
		'query' => 'planting plan designer bristol',
		'site' => 'Northfield Garden Design',
		'crumb' => 'northfield.example › planting-plans',
		'bad_title' => 'Planting Plans – Northfield',
		'bad_desc' => 'We offer a range of services for your garden. Please get in touch for more information about…',
		'good_title' => 'Planting Plans in Bristol, from £450 | Northfield Garden Design',
		'good_desc' => 'A planting plan made for your soil, light and budget. Delivered in 2 weeks with a shopping list. See examples from 60+ Bristol gardens.',
		'checks' => array(
			array( 'Location in the title', 'bad', 'good' ),
			array( 'Price or proof in the description', 'bad', 'good' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'serp', $d );
