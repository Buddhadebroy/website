<?php
/**
 * Notifima blocks (Inserter → category "Notifima").
 *
 *   graphic/block       Animated Graphic (Notifima)   every mockup and picture of the site
 *   blocks/site-logo    Site Logo (Notifima)          Site Identity logo or the wordmark
 *   blocks/site-menu    Site Menu (Notifima)          menu + mega menus, phone menu, burger, cart/account icons
 *
 * Patterns: synced patterns made from pattern-files/*.php (inc/patterns.php), category "Notifima".
 *
 * @package Notifima
 */

defined( 'ABSPATH' ) || exit;

add_filter(
	'block_categories_all',
	function ( $cats ) {
		array_unshift( $cats, array( 'slug' => 'notifima', 'title' => __( 'Notifima', 'notifima' ), 'icon' => null ) );
		return $cats;
	}
);
