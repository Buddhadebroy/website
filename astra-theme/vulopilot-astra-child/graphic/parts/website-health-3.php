<?php
/**
 * Website Health › graphic 3: backup timeline.
 * Graphic name: website-health-3  ·  Shortcode: [vp_website_health_3]  ·  /solutions/website-health/
 * Scene: graphic/parts/scenes/backups.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="website-health-3-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Backups',
		'path' => 'Restore points',
		'rows' => array(
			array( 'Today 02:00', 'Full site · database + files', '1.8 GB', true ),
			array( 'Yesterday 02:00', 'Full site', '1.8 GB', false ),
			array( 'Before update · 2 days ago', 'Automatic, before WooCommerce 9.3', '1.7 GB', false ),
			array( '7 days ago', 'Full site', '1.7 GB', false ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'backups', $d );
