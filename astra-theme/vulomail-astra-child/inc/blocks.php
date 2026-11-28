<?php
/**
 * The theme block "Animated Graphic (VuloMail)" and the pattern category.
 *
 * vulomail/graphic is a dynamic block: it stores only which part to show (and a few props) and renders
 * graphic/parts/<part>.php on the server. Every picture, demo and animation that can't be built from
 * Kadence text uses it, so all the copy around them stays editable in Kadence.
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

require_once VM_DIR . 'graphic/shared.php';

/** Part slug => label shown in the block's dropdown. */
function vm_graphic_parts() {
	$parts = array();
	foreach ( glob( VM_DIR . 'graphic/parts/*.php' ) as $file ) {
		$slug = basename( $file, '.php' );
		$head = (string) file_get_contents( $file, false, null, 0, 400 ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		$parts[ $slug ] = preg_match( '/Graphic:\s*(.+)/', $head, $m ) ? trim( $m[1] ) : $slug;
	}
	ksort( $parts );
	return $parts;
}

function vm_render_graphic( $attributes ) {
	$part  = isset( $attributes['part'] ) ? sanitize_key( $attributes['part'] ) : '';
	$props = isset( $attributes['props'] ) && is_array( $attributes['props'] ) ? $attributes['props'] : array();
	if ( ! $part ) {
		return '';
	}
	$class = 'vm-graphic vm-g-' . $part;
	if ( ! empty( $attributes['className'] ) ) {
		$class .= ' ' . $attributes['className'];
	}
	return '<div class="' . esc_attr( $class ) . '">' . vm_part( $part, $props ) . '</div>';
}

add_action(
	'init',
	function () {
		register_block_pattern_category( 'vulomail', array( 'label' => __( 'VuloMail', 'vulomail' ) ) );

		wp_register_script(
			'vm-graphic-editor',
			VM_URI . 'assets/js/vm-graphic-editor.js',
			array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-block-editor', 'wp-server-side-render', 'wp-i18n' ),
			VM_VERSION,
			true
		);
		wp_localize_script( 'vm-graphic-editor', 'vmGraphicParts', vm_graphic_parts() );

		register_block_type(
			'vulomail/graphic',
			array(
				'api_version'     => 3,
				'title'           => __( 'Animated Graphic (VuloMail)', 'vulomail' ),
				'category'        => 'design',
				'editor_script'   => 'vm-graphic-editor',
				'render_callback' => 'vm_render_graphic',
				'attributes'      => array(
					'part'      => array( 'type' => 'string', 'default' => '' ),
					'props'     => array( 'type' => 'object', 'default' => array() ),
					'className' => array( 'type' => 'string', 'default' => '' ),
				),
				'supports'        => array( 'html' => false, 'customClassName' => true ),
			)
		);
	}
);

function vm_render_site_logo( $a ) {
	return '<div class="' . esc_attr( vm_cx( 'vm-site-logo', $a['className'] ?? '' ) ) . '">' . vm_part( 'site-logo', array( 'link' => ! isset( $a['link'] ) || $a['link'] ) ) . '</div>';
}

function vm_render_site_menu( $a ) {
	$parts = array( 'header' => 'nav-desktop', 'phone' => 'menu-toggle', 'footer' => 'footer-links' );
	$v     = isset( $a['variant'], $parts[ $a['variant'] ] ) ? $a['variant'] : 'header';
	return '<div class="' . esc_attr( vm_cx( 'vm-site-menu vm-site-menu-' . $v, $a['className'] ?? '' ) ) . '">' . vm_part( $parts[ $v ] ) . '</div>';
}

/**
 * Site Logo (VuloMail) and Site Menu (VuloMail): the header and footer are built from these two blocks
 * plus Kadence blocks. The logo comes from Customize → Site Identity (a Kadence Image can replace it);
 * the links come from Appearance → Menus.
 */
add_action(
	'init',
	function () {
		register_block_type(
			'vulomail/site-logo',
			array(
				'api_version'     => 3,
				'title'           => __( 'Site Logo (VuloMail)', 'vulomail' ),
				'category'        => 'theme',
				'editor_script'   => 'vm-graphic-editor',
				'attributes'      => array(
					'link'      => array( 'type' => 'boolean', 'default' => true ),
					'className' => array( 'type' => 'string', 'default' => '' ),
				),
				'render_callback' => 'vm_render_site_logo',
			)
		);
		register_block_type(
			'vulomail/site-menu',
			array(
				'api_version'     => 3,
				'title'           => __( 'Site Menu (VuloMail)', 'vulomail' ),
				'category'        => 'theme',
				'editor_script'   => 'vm-graphic-editor',
				'attributes'      => array(
					'variant'   => array( 'type' => 'string', 'default' => 'header' ),
					'className' => array( 'type' => 'string', 'default' => '' ),
				),
				'render_callback' => 'vm_render_site_menu',
			)
		);
	}
);
