<?php
/**
 * Businesses › graphic 2: changes waiting for approval.
 * Graphic name: businesses-2  ·  Shortcode: [vp_businesses_2]  ·  /for/businesses/
 * Scene: graphic/parts/scenes/approvals.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="businesses-2-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Needs a yes from you',
		'path' => '3 items',
		'rows' => array(
			array( 'heading', 'New title for the homepage', 'Family Dentist in Clifton, Bristol' ),
			array( 'refresh-cw', 'Update booking plugin', 'Backup taken first' ),
			array( 'message-circle-question', 'Add 5 FAQs to /treatments/', 'From real patient questions' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'approvals', $d );
