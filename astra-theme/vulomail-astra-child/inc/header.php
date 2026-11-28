<?php
/**
 * Header: the synced pattern "VuloMail Header" (Kadence blocks + the theme's Site Logo and Site Menu blocks),
 * printed in place of Astra's header. No header markup in PHP.
 *
 * Shared with inc/footer.php: vm_layout_block_id(), vm_render_layout_part() render either layout part by slug.
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

/** Kept for older calls: ID of the header/footer synced pattern. */
function vm_layout_block_id( $part ) {
	return vm_pattern_id( $part );
}

function vm_render_layout_part( $part ) {
	if ( ! vm_pattern_id( $part ) ) {
		vm_sync_patterns();
	}
	echo '<div class="vm-layout vm-layout-' . esc_attr( $part ) . '">' . vm_render_pattern( $part ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput
}

/** Swap Astra's header for ours, unless switched off in Customize → VuloMail. */
add_action(
	'wp',
	function () {
		if ( vm_opt( 'use_header' ) ) {
			remove_all_actions( 'astra_header' );
			remove_all_actions( 'astra_mobile_header' );
			add_action(
				'astra_header',
				function () {
					vm_render_layout_part( 'header' );
				}
			);
		}
	},
	20
);

/** Scroll-progress bar, like the original layout. */
add_action(
	'wp_body_open',
	function () {
		if ( vm_opt( 'scroll_progress' ) ) {
			echo '<div aria-hidden="true" class="vm:airmail vm-progress"></div>';
		}
	},
	5
);
