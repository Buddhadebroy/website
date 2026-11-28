<?php
/**
 * Low Search Traffic › graphic 4: changes waiting for approval.
 * Graphic name: low-search-traffic-4  ·  Shortcode: [vp_low_search_traffic_4]  ·  /problems/low-search-traffic/
 * Scene: graphic/parts/scenes/approvals.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="low-search-traffic-4-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Win it back',
		'path' => 'Fix plan',
		'rows' => array(
			array( 'heading', 'Restore a clear homepage title', 'Emergency Plumber in Bristol, 24/7' ),
			array( 'file-plus2', 'Expand "boiler repair cost"', 'Add prices, FAQ and a quick answer' ),
			array( 'zap', 'Speed up mobile', '4.8 s → 1.9 s' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'approvals', $d );
