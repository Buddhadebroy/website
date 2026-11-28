<?php
/**
 * Seo Audit › graphic 4: report email.
 * Graphic name: seo-audit-4  ·  Shortcode: [vp_seo_audit_4]  ·  /solutions/seo-audit/
 * Scene: graphic/parts/scenes/report.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="seo-audit-4-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Audit email',
		'path' => 'Every Monday',
		'to' => 'you@rosiebakes.co.uk',
		'subject' => 'SEO audit: 6 issues fixed, 3 new ones found',
		'kpis' => array(
			array( 'Audit score', '88', '+5', 'good' ),
			array( 'Open issues', '14', '-6', 'good' ),
			array( 'New issues', '3', '+3', 'bad' ),
		),
		'lines' => array(
			'Fixed: duplicate titles on /cakes/ and /cakes-2/',
			'New: 3 images uploaded without alt text',
			'Next: 2 pages blocked by noindex by mistake',
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'report', $d );
