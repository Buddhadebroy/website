<?php
/**
 * Agencies › hero: all client sites in one table.
 * Graphic name: agencies-1  ·  Shortcode: [vp_agencies_1]  ·  /for/agencies/
 * Scene: graphic/parts/scenes/sites.php  ·  Hero: no page title inside, fills the column height.
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="agencies-1-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'All client sites',
		'path' => '12 sites · same 6 scores',
		'cols' => array( 'Site', 'SEO', 'Speed', 'Security', '' ),
		'rows' => array(
			array( 'northfield.example', 91, 84, 96, '' ),
			array( 'rosiebakes.example', 72, 58, 88, 'Slow' ),
			array( 'bristolflow.example', 64, 79, 41, 'Plugin hole' ),
			array( 'oakandleaf.example', 88, 90, 92, '' ),
			array( 'harbourdental.example', 55, 62, 80, 'Not indexed' ),
			array( 'kitecycles.example', 83, 71, 94, '' ),
		),
		'note' => 'Needs your attention: 3 sites, 4 issues.',
		'btn' => 'Client report',
		'foot' => 'One scan method for every client, so nothing is checked differently.',
		'foot_icon' => 'layout-dashboard',
	),
	isset( $args ) ? $args : array(),
	true
);
vpx_scene( 'sites', $d );
