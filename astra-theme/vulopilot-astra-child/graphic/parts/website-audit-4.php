<?php
/**
 * Website Audit › graphic 4: speed score gauge.
 * Graphic name: website-audit-4  ·  Shortcode: [vp_website_audit_4]  ·  /solutions/website-audit/
 * Scene: graphic/parts/scenes/speed.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="website-audit-4-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Speed pass',
		'path' => 'Homepage · mobile',
		'from' => 41,
		'to' => 86,
		'before' => 'As audited',
		'after' => 'After quick fixes',
		'device' => 'Mobile',
		'stats' => array( array( 'Largest image', '2.4 MB', '210 KB' ), array( 'Load time', '4.1 s', '1.7 s' ) ),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'speed', $d );
