<?php
/**
 * Automate Website Management › graphic 2: When / If / Then automation.
 * Graphic name: automate-website-management-2  ·  Shortcode: [vp_automate_website_management_2]  ·  /use-cases/automate-website-management/
 * Scene: graphic/parts/scenes/workflow.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="automate-website-management-2-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Safe update rule',
		'path' => 'Automation',
		'nodes' => array(
			array( 'When', 'refresh-cw', 'A plugin update is available' ),
			array( 'Then', 'database', 'Take a backup first' ),
			array( 'Then', 'wrench', 'Apply the update' ),
			array( 'If', 'alert-triangle', 'The site breaks, roll back and email me' ),
		),
		'runs' => array(
			array( 'WooCommerce ✓', 'good' ),
			array( 'Yoast ✓', 'good' ),
			array( 'Slider Pro rolled back', 'warn' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'workflow', $d );
