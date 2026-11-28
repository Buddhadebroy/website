<?php
/**
 * Agencies › graphic 3: hours by hand versus automated.
 * Graphic name: agencies-3  ·  Shortcode: [vp_agencies_3]  ·  /for/agencies/
 * Scene: graphic/parts/scenes/hours.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="agencies-3-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Unbilled time, per month',
		'path' => '12 sites',
		'unit' => 'h / month',
		'rows' => array(
			array( 'Checking each site', 18, 1 ),
			array( 'Updates and testing', 14, 2 ),
			array( 'Writing client reports', 10, 1 ),
			array( 'Chasing problems', 8, 2 ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'hours', $d );
