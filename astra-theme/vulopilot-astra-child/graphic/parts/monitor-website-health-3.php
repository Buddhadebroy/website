<?php
/**
 * Monitor Website Health › graphic 3: site checkup tiles.
 * Graphic name: monitor-website-health-3  ·  Shortcode: [vp_monitor_website_health_3]  ·  /use-cases/monitor-website-health/
 * Scene: graphic/parts/scenes/checkup.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="monitor-website-health-3-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Daily checks',
		'path' => 'Today 06:00',
		'tiles' => array(
			array( 'shield-check', 'SSL', 'Valid', 'good', '89 days left' ),
			array( 'database', 'Backup', '02:00', 'good', 'Verified restorable' ),
			array( 'refresh-cw', 'Updates', '3 waiting', 'warn', '2 plugins, 1 theme' ),
			array( 'server', 'PHP errors', '0', 'good', 'Last 24 hours' ),
		),
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'checkup', $d );
