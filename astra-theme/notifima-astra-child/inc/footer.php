<?php
/**
 * Footer, built in WordPress with Kadence blocks.
 *
 *   Content   the synced pattern "Site · Footer" (inc/patterns.php makes it from patterns/site-footer.php).
 *             Edit it in Appearance → Design → Patterns, or Appearance → Notifima → Edit footer (Kadence editor).
 *   Output    printed in place of Astra's footer (astra_footer) on every page.
 *             Customize → Notifima → General: turn it off to get Astra's own back.
 *
 * Uses nf_hf_render() and nf_opt() from inc/header.php (loaded first).
 *
 * @package Notifima
 */

defined( 'ABSPATH' ) || exit;

/** Print ours in place of Astra's footer. */
add_action(
	'wp',
	function () {
		if ( nf_opt( 'nf_use_footer' ) ) {
			remove_all_actions( 'astra_footer' );
			add_action( 'astra_footer', function () { echo nf_hf_render( 'footer' ); } ); // phpcs:ignore
		}
	}
);
