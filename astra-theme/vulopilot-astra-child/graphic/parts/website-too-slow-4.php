<?php
/**
 * Website Too Slow › graphic 4: request waterfall.
 * Graphic name: website-too-slow-4  ·  Shortcode: [vp_website_too_slow_4]  ·  /problems/website-too-slow/
 * Scene: graphic/parts/scenes/waterfall.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="website-too-slow-4-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Slow server response',
		'path' => 'Before cache',
		'scale' => '0 · 0.5s · 1s · 1.5s · 2s',
		'col' => 'Step',
		'rows' => array(
			array( 'DNS + connect', '0.1s', 0, 6, 'good' ),
			array( 'PHP + 214 queries', '1.6s', 6, 78, 'bad' ),
			array( 'First byte', '', 84, 4, 'warn' ),
			array( 'HTML download', '0.2s', 88, 10, 'good' ),
		),
		'stats' => array(
			array( 'TTFB', '2.2 s', 'bad' ),
			array( 'With page cache', '0.3 s', 'good' ),
			array( 'Queries', '214', 'bad' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'waterfall', $d );
