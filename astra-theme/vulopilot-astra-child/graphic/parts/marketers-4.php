<?php
/**
 * Marketers › graphic 4: report email.
 * Graphic name: marketers-4  ·  Shortcode: [vp_marketers_4]  ·  /for/marketers/
 * Scene: graphic/parts/scenes/report.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="marketers-4-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Weekly marketing report',
		'path' => 'Every Monday',
		'to' => 'marketing@kitecycles.example',
		'subject' => 'Week 42: +18% organic clicks, 3 new AI mentions',
		'kpis' => array(
			array( 'Organic clicks', '6.2k', '+18%', 'good' ),
			array( 'AI mentions', '27', '+3', 'good' ),
			array( 'Avg. position', '8.1', '-1.4', 'good' ),
		),
		'lines' => array(
			'Best page: Kids bike size chart, 1,240 clicks',
			'New: quoted by Perplexity for "gravel bike under £1,000"',
			'To do: 2 posts need a refresh',
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'report', $d );
