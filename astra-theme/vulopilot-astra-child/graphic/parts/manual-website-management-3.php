<?php
/**
 * Manual Website Management › graphic 3: When / If / Then automation.
 * Graphic name: manual-website-management-3  ·  Shortcode: [vp_manual_website_management_3]  ·  /problems/manual-website-management/
 * Scene: graphic/parts/scenes/workflow.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="manual-website-management-3-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Same work, automated',
		'path' => 'One rule',
		'nodes' => array(
			array( 'When', 'calendar-clock', 'Every night' ),
			array( 'Then', 'database', 'Back up and check the backup' ),
			array( 'Then', 'scan-search', 'Scan for broken forms, links and problems' ),
			array( 'If', 'bell', 'Something is wrong, tell me' ),
		),
		'runs' => array(
			array( 'Mon ✓', 'good' ),
			array( 'Tue ✓', 'good' ),
			array( 'Wed form alert', 'bad' ),
			array( 'Thu ✓', 'good' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'workflow', $d );
