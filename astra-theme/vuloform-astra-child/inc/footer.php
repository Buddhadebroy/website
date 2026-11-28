<?php
/**
 * Footer, built in WordPress with Kadence blocks.
 *
 *   Content   the synced pattern "Site · Footer" (inc/patterns.php makes it from patterns/site-footer.php).
 *             Edit it in Appearance → Design → Patterns, or Appearance → VuloForm → Edit footer (Kadence editor).
 *   Output    printed in place of Astra's footer (astra_footer hook) on every page.
 *             Customize → VuloForm → "Use the VuloForm footer" off = Astra's own footer.
 *
 * Uses vf_hf_render() and vf_opt() from inc/header.php (loaded first).
 *
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;

/** Print ours in place of Astra's footer. */
add_action(
	'wp',
	function () {
		if ( vf_opt( 'vf_use_footer' ) ) {
			remove_all_actions( 'astra_footer' );
			add_action( 'astra_footer', function () { echo vf_hf_render( 'footer' ); } ); // phpcs:ignore WordPress.Security.EscapeOutput
		}
	}
);
