<?php
/**
 * Website Automation › hero: When / If / Then automation.
 * Graphic name: website-automation-1  ·  Shortcode: [vp_website_automation_1]  ·  /solutions/website-automation/
 * Scene: graphic/parts/scenes/workflow.php  ·  Hero: no page title inside, fills the column height.
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="website-automation-1-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Automation',
		'path' => 'Automations → New',
		'nodes' => array(
			array( 'When', 'calendar-clock', 'Every Monday at 06:00' ),
			array( 'If', 'filter', 'A new SEO or security problem is found' ),
			array( 'Then', 'wand-sparkles', 'Prepare the fix and ask me to approve' ),
			array( 'And', 'mail', 'Email the weekly report to the team' ),
		),
		'runs' => array(
			array( 'Run #52 ✓', 'good' ),
			array( '#51 ✓', 'good' ),
			array( '#50 retried', 'warn' ),
			array( '#49 ✓', 'good' ),
		),
		'foot' => 'Built from blocks, no code. Nothing changes without your approval.',
		'foot_icon' => 'hand',
	),
	isset( $args ) ? $args : array(),
	true
);
vpx_scene( 'workflow', $d );
