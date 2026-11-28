<?php
/**
 * Website Owners › hero: report email.
 * Graphic name: website-owners-1  ·  Shortcode: [vp_website_owners_1]  ·  /for/website-owners/
 * Scene: graphic/parts/scenes/report.php  ·  Hero: no page title inside, fills the column height.
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="website-owners-1-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Your weekly email',
		'path' => 'Monday 07:00',
		'to' => 'you@oakandleaf.example',
		'subject' => 'All good this week, 2 small things to approve',
		'kpis' => array(
			array( 'Website score', '91', '+3', 'good' ),
			array( 'Visitors', '1,420', '+9%', 'good' ),
			array( 'Problems', '2', '-4', 'good' ),
		),
		'lines' => array(
			'Backups ran every night and were checked',
			'3 plugins updated safely',
			'Blocked 312 login attempts',
			'2 page titles ready for your approval',
		),
		'foot' => 'Read it in a minute. Click once to approve. That is the whole job.',
		'foot_icon' => 'mail',
	),
	isset( $args ) ? $args : array(),
	true
);
vpx_scene( 'report', $d );
