<?php
/**
 * VuloForm blocks (Inserter → category "VuloForm").
 *
 *   blocks/templates-marquee   Ready-made Forms Marquee (VuloForm)      screenshot 1
 *   blocks/builder-steps       Builder Sticky Steps (VuloForm)          screenshot 2
 *   blocks/compare-tabs        Comparison Table with Tabs (VuloForm)    screenshot 3
 *   blocks/privacy-words       Privacy Scrolling Words (VuloForm)       screenshot 4
 *   graphic/block              Animated Graphic (VuloForm)              any graphic or Home section
 *   blocks/site-logo           Site Logo (VuloForm)                     Site Identity logo + title (header, footer)
 *   blocks/site-menu           Site Menu (VuloForm)                     header links, phone menu, burger
 *   (the last two are registered in inc/header-footer.php)
 *
 * Patterns: synced patterns made from pattern-files/*.php (inc/patterns.php), category "VuloForm", editable in Appearance → Design → Patterns.
 *
 * Each section block renders the same PHP file as the Home page (template-parts/home/…), so it looks exactly the same.
 * Text is edited in the block sidebar; the fields start with the default text (vf_section_defaults()).
 * The blocks load their own CSS/JS (block.json "style" / "viewScript"), so they work on any page.
 *
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;

/** "VuloForm" category at the top of the inserter. */
add_filter(
	'block_categories_all',
	function ( $cats ) {
		array_unshift( $cats, array( 'slug' => 'vuloform', 'title' => __( 'VuloForm', 'vuloform' ), 'icon' => null ) );
		return $cats;
	}
);

/* Patterns: synced patterns in category "VuloForm" (inc/patterns.php), not theme patterns – those would be locked. */

/** The section block folders. */
function vf_section_blocks() {
	return array( 'templates-marquee', 'builder-steps', 'compare-tabs', 'privacy-words' );
}

add_action(
	'init',
	function () {
		wp_register_script( 'vf-blocks-editor', VF_URI . '/blocks/editor.js', array( 'wp-blocks', 'wp-block-editor', 'wp-components', 'wp-element', 'wp-server-side-render' ), vf_ver( 'blocks/editor.js' ), true );
		$fields = array();
		foreach ( vf_section_blocks() as $slug ) {
			$meta  = json_decode( file_get_contents( VF_DIR . '/blocks/' . $slug . '/block.json' ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions
			$attrs = $meta['attributes'];
			// Start every text field with the real default text, so the sidebar shows what is on the page.
			foreach ( vf_section_defaults( $meta['vfSection'] ) as $k => $v ) {
				if ( isset( $attrs[ $k ] ) ) {
					$attrs[ $k ]['default'] = $v;
				}
			}
			register_block_type( VF_DIR . '/blocks/' . $slug, array( 'attributes' => $attrs ) );
			$fields[ $meta['name'] ] = $meta['vfFields'];
		}
		wp_add_inline_script( 'vf-blocks-editor', 'window.VF_BLOCK_FIELDS=' . wp_json_encode( $fields ) . ';', 'before' );
	},
	20
);
