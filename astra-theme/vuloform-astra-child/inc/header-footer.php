<?php
/**
 * Header and footer, built in WordPress with Kadence blocks.
 *
 *   Content   the synced patterns "Site · Header" and "Site · Footer" (inc/patterns.php makes them from
 *             pattern-files/site-header.php and site-footer.php). Edit them in Appearance → Design → Patterns,
 *             or Appearance → VuloForm → Edit header / Edit footer (Kadence editor).
 *   Output    printed in place of Astra's header and footer (astra_header / astra_footer hooks) on every page.
 *             Customize → VuloForm → "Use the VuloForm header/footer" off = Astra's own header/footer.
 *   Logo      the Site Logo (VuloForm) block = Customize → Site Identity (logo, Astra retina logo); favicon = Site Icon.
 *   Menu      the Site Menu (VuloForm) block = Appearance → Menus → "VuloForm header".
 *
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;

/** Header / footer → their pattern slugs (the synced patterns are made in inc/patterns.php). */
function vf_hf_slug( $part ) {
	return 'header' === $part ? 'site-header' : 'site-footer';
}

/** Edit link of the header or footer. */
function vf_hf_edit_url( $part ) {
	return vf_pattern_edit_url( vf_hf_slug( $part ) );
}

/** HTML of the header or footer: the synced pattern, or the pattern file when it doesn't exist (yet). */
function vf_hf_render( $part ) {
	$post    = vf_synced_post( vf_hf_slug( $part ) );
	$content = $post && 'publish' === $post->post_status ? $post->post_content : vf_pattern_markup( vf_hf_slug( $part ) );
	return do_shortcode( do_blocks( $content ) );
}

/** Theme activated: make every synced pattern (only the missing ones) and fill Site Identity when it is empty. */
add_action(
	'after_switch_theme',
	function () {
		vf_sync_all();
		vf_import_identity();
	}
);

/**
 * Logo and favicon: Customize → Site Identity. On activation, when no logo / site icon is set yet,
 * the VuloForm icon is added to the Media Library and used for both (change them any time in Site Identity).
 */
function vf_import_identity() {
	if ( ! function_exists( 'media_handle_sideload' ) ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';
	}
	$add = function ( $file, $title ) {
		$tmp = wp_tempnam( $file );
		copy( VF_DIR . '/assets/img/' . $file, $tmp );
		$id = media_handle_sideload( array( 'name' => $file, 'tmp_name' => $tmp ), 0, $title );
		if ( is_wp_error( $id ) ) {
			@unlink( $tmp ); // phpcs:ignore WordPress.PHP.NoSilencedErrors
			return 0;
		}
		return (int) $id;
	};
	if ( ! get_theme_mod( 'custom_logo' ) ) {
		$id = $add( 'brand-icon.png', 'VuloForm logo' );
		if ( $id ) {
			set_theme_mod( 'custom_logo', $id );
		}
	}
	if ( ! get_option( 'site_icon' ) ) {
		$id = $add( 'site-icon.png', 'VuloForm site icon' );
		if ( $id ) {
			update_option( 'site_icon', $id );
		}
	}
}

/** Print ours in place of Astra's header / footer. */
add_action(
	'wp',
	function () {
		if ( vf_opt( 'vf_use_header' ) ) {
			remove_all_actions( 'astra_header' );
			add_action( 'astra_header', function () { echo vf_hf_render( 'header' ); } ); // phpcs:ignore WordPress.Security.EscapeOutput
		}
		if ( vf_opt( 'vf_use_footer' ) ) {
			remove_all_actions( 'astra_footer' );
			add_action( 'astra_footer', function () { echo vf_hf_render( 'footer' ); } ); // phpcs:ignore WordPress.Security.EscapeOutput
		}
	}
);

/** Header links: the menu assigned to a location (top level items), else vf_nav_links(). Returns [ [href, label] ]. */
function vf_menu_links( $location = 'vf_header' ) {
	$loc = get_nav_menu_locations();
	if ( ! empty( $loc[ $location ] ) ) {
		$items = wp_get_nav_menu_items( $loc[ $location ] );
		if ( $items ) {
			$out = array();
			foreach ( $items as $it ) {
				if ( ! $it->menu_item_parent ) {
					$out[] = array( $it->url, $it->title );
				}
			}
			return $out;
		}
	}
	return vf_nav_links();
}

/** Register the two blocks used by the header and footer. */
add_action(
	'init',
	function () {
		wp_register_script( 'vf-site-blocks-editor', VF_URI . '/blocks/site-editor.js', array( 'wp-blocks', 'wp-block-editor', 'wp-components', 'wp-element', 'wp-server-side-render' ), vf_ver( 'blocks/site-editor.js' ), true );
		register_block_type( VF_DIR . '/blocks/site-logo' );
		register_block_type( VF_DIR . '/blocks/site-menu' );
	},
	20
);
