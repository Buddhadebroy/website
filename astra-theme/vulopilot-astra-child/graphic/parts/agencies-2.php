<?php
/**
 * Agencies › graphic 2: report email.
 * Graphic name: agencies-2  ·  Shortcode: [vp_agencies_2]  ·  /for/agencies/
 * Scene: graphic/parts/scenes/report.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="agencies-2-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Client report',
		'path' => 'White-label PDF + email',
		'to' => 'owner@harbourdental.example',
		'subject' => 'October: 14 fixes made on your website',
		'kpis' => array(
			array( 'SEO score', '55 → 78', '+23', 'good' ),
			array( 'Pages indexed', '+31', '+31', 'good' ),
			array( 'Load time', '1.8 s', '-1.6 s', 'good' ),
		),
		'lines' => array( 'Fixed noindex on 6 treatment pages', 'Converted 96 images to WebP', 'Blocked 2,140 login attacks' ),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'report', $d );
