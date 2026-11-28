<?php
/**
 * Not Appearing In Ai Search › graphic 3: the file or tag behind the problem, and the fix.
 * Graphic name: not-appearing-in-ai-search-3  ·  Shortcode: [vp_not_appearing_in_ai_search_3]  ·  /problems/not-appearing-in-ai-search/
 * Scene: graphic/parts/scenes/code.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="not-appearing-in-ai-search-3-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'The blocking rule',
		'path' => 'robots.txt',
		'tabs' => array( 'robots.txt' ),
		'lines' => array(
			array( 'plain', 'User-agent: *' ),
			array( 'plain', 'Disallow: /wp-admin/' ),
			array( 'plain', '' ),
			array( 'del', 'User-agent: GPTBot' ),
			array( 'del', 'Disallow: /' ),
			array( 'add', 'User-agent: GPTBot' ),
			array( 'add', 'Allow: /' ),
		),
		'notes' => array(
			array( 'Added by a plugin in 2023, never reviewed', 'bad' ),
			array( 'Fixed with one switch, logged and reversible', 'good' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'code', $d );
