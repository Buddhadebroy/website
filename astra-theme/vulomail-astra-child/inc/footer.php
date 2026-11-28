<?php
/**
 * Footer: the synced pattern "VuloMail Footer" (Kadence blocks), printed in place of Astra's footer.
 * No footer markup in PHP. Uses vm_render_layout_part() from inc/header.php (loaded first).
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

/** Swap Astra's footer for ours, unless switched off in Customize → VuloMail. */
add_action(
	'wp',
	function () {
		if ( vm_opt( 'use_footer' ) ) {
			remove_all_actions( 'astra_footer' );
			add_action(
				'astra_footer',
				function () {
					vm_render_layout_part( 'footer' );
				}
			);
		}
	},
	20
);
