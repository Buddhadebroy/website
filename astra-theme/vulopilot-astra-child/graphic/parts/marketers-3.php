<?php
/**
 * Marketers › graphic 3: how often AI engines mention you.
 * Graphic name: marketers-3  ·  Shortcode: [vp_marketers_3]  ·  /for/marketers/
 * Scene: graphic/parts/scenes/share.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="marketers-3-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Share of AI answers',
		'path' => 'vs competitors',
		'was' => 'Last quarter',
		'now' => 'This quarter',
		'rows' => array(
			array( 'Kite Cycles (you)', 9, 27 ),
			array( 'Competitor A', 24, 21 ),
			array( 'Competitor B', 18, 15 ),
		),
		'note' => 'How often each brand is named in AI answers to 50 buying questions.',
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'share', $d );
