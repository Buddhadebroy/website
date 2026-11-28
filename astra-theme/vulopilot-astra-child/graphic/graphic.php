
<?php
/**
 * Graphic folder – loader.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

define( 'VP_GRAPHIC_DIR', VP_DIR . '/graphic' );
define( 'VP_GRAPHIC_URI', VP_URI . '/graphic' );

require VP_GRAPHIC_DIR . '/graphics.php';
require VP_GRAPHIC_DIR . '/page-graphics.php';
require VP_GRAPHIC_DIR . '/render.php';

/**
 * Version string for a file in this folder.
 */
function vp_graphic_ver( $file ) {
	$path = VP_GRAPHIC_DIR . '/' . $file;

	return file_exists( $path )
		? VP_VERSION . '.' . filemtime( $path )
		: VP_VERSION;
}

/**
 * Check whether the current page needs graphic assets.
 *
 * Includes:
 * - Homepage.
 * - VuloPilot page templates.
 * - Animated Graphic Gutenberg block.
 * - Graphic shortcodes.
 */
function vp_page_needs_graphics() {

	// Homepage and VuloPilot PHP templates.
	if (
		is_front_page() ||
		( function_exists( 'vp_is_vp_template' ) && vp_is_vp_template() )
	) {
		return true;
	}

	if ( ! is_singular() ) {
		return false;
	}

	global $post;

	if ( ! $post instanceof WP_Post ) {
		return false;
	}

	$content = $post->post_content;

	// Animated Graphic Gutenberg block.
	if ( has_block( 'vulopilot/graphic', $content ) ) {
		return true;
	}

	// Graphic shortcodes.
	if ( preg_match( '/\[(?:vp_graphic|vp_[a-z0-9_-]+)\b/i', $content ) ) {
		return true;
	}

	return false;
}

/* -------------------------------------------------------------------------
 * Front end: Load graphic assets only when needed.
 * ---------------------------------------------------------------------- */
add_action(
	'wp_enqueue_scripts',
	function () {

		if ( ! vp_page_needs_graphics() ) {
			return;
		}

		// Graphic styles.
		wp_enqueue_style(
			'vp-graphics',
			VP_GRAPHIC_URI . '/css/vp-graphics.css',
			array( 'vp-motion' ),
			vp_graphic_ver( 'css/vp-graphics.css' )
		);

		wp_enqueue_style(
			'vp-page-graphics',
			VP_GRAPHIC_URI . '/css/vp-page-graphics.css',
			array( 'vp-graphics' ),
			vp_graphic_ver( 'css/vp-page-graphics.css' )
		);

		// Small graphic widgets.
		wp_enqueue_script(
			'vp-graphics',
			VP_GRAPHIC_URI . '/js/vp-graphics.js',
			array( 'vp-motion' ),
			vp_graphic_ver( 'js/vp-graphics.js' ),
			true
		);

		// Register React bundle.
		// It is enqueued only when vp_mount() needs a React graphic.
		wp_register_script(
			'vp-app',
			VP_GRAPHIC_URI . '/js/vp-app.js',
			array(),
			vp_graphic_ver( 'js/vp-app.js' ),
			true
		);

		wp_add_inline_script(
			'vp-app',
			'window.VP_DATA=' . wp_json_encode(
				array(
					'assets'  => vp_assets_url(),
					'screens' => wp_list_pluck( vp_screens(), 'src' ),
				)
			) . ';',
			'before'
		);
	},
	21
);

/* -------------------------------------------------------------------------
 * Block editor: Graphic styles.
 * ---------------------------------------------------------------------- */
add_action(
	'enqueue_block_assets',
	function () {

		if ( ! is_admin() ) {
			return;
		}

		wp_enqueue_style(
			'vp-graphics',
			VP_GRAPHIC_URI . '/css/vp-graphics.css',
			array(),
			vp_graphic_ver( 'css/vp-graphics.css' )
		);

		wp_enqueue_style(
			'vp-page-graphics',
			VP_GRAPHIC_URI . '/css/vp-page-graphics.css',
			array(),
			vp_graphic_ver( 'css/vp-page-graphics.css' )
		);
	}
);

/* -------------------------------------------------------------------------
 * Animated Graphic Gutenberg block.
 * ---------------------------------------------------------------------- */
add_action(
	'init',
	function () {
		register_block_type( VP_GRAPHIC_DIR . '/block' );
	},
	11
);
