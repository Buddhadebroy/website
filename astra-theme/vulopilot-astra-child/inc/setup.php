<?php
/**
 * Assets, Astra integration and the Home page template.
 *
 *   1. Template helpers   vp_template_map(), vp_starts_full_width(), vp_is_vp_template()
 *   2. Theme setup        text domain, editor styles, menu locations
 *   3. Front-end assets   fonts, Tailwind, site CSS/JS, motion, style.css
 *   4. Body classes and Astra layout
 *   5. Editor assets
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

/* -------------------------------------------------------------------------
 * 1. Template helpers
 * ---------------------------------------------------------------------- */

/** Version for a theme file: theme version + file time, so browsers reload it after every change. */
function vp_ver( $file ) {
	return VP_VERSION . '.' . filemtime( VP_DIR . '/' . $file );
}

/** Page templates that render a full-width VuloPilot page (only Home). */
function vp_template_map() {
	return array(
		'page-templates/template-home.php'    => 'home',
	);
}

/**
 * Does the current page start with a full-width section (e.g. a Kadence row or an alignfull group)?
 * Those sections carry their own top padding, like the sections on the original site.
 */
function vp_starts_full_width() {
	if ( ! is_singular() ) {
		return false;
	}
	$post = get_post();
	if ( ! $post || ! has_blocks( $post->post_content ) ) {
		return false;
	}
	foreach ( parse_blocks( $post->post_content ) as $b ) {
		if ( empty( $b['blockName'] ) ) {
			continue; // Whitespace between blocks.
		}
		$full = ( $b['attrs']['align'] ?? '' ) === 'full';
		return (bool) apply_filters( 'vp_starts_full_width', $full || 'kadence/rowlayout' === $b['blockName'], $b );
	}
	return false;
}

/** Is the current request one of our full-page templates? */
function vp_is_vp_template() {
	return is_page() && isset( vp_template_map()[ get_page_template_slug() ] );
}

/* -------------------------------------------------------------------------
 * 2. Theme setup
 * ---------------------------------------------------------------------- */
add_action(
	'after_setup_theme',
	function () {
		load_child_theme_textdomain( 'vulopilot', VP_DIR . '/languages' );
		add_theme_support( 'editor-styles' );
		register_nav_menus(
			array(
				'vp_header' => __( 'VuloPilot header (mega menu)', 'vulopilot' ),
				'vp_footer' => __( 'VuloPilot footer columns', 'vulopilot' ),
			)
		);
	}
);

/* -------------------------------------------------------------------------
 * 3. Front-end assets
 * ---------------------------------------------------------------------- */
add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_style( 'vp-tailwind', VP_URI . '/assets/css/vp-tailwind.css', array(), vp_ver( 'assets/css/vp-tailwind.css' ) );
		wp_enqueue_style( 'vp-site', VP_URI . '/assets/css/vp-site.css', array( 'vp-tailwind' ), vp_ver( 'assets/css/vp-site.css' ) );

		$offset = (int) vp_opt( 'vp_content_offset' );
		if ( $offset && vp_opt( 'vp_use_header' ) && ! vp_is_vp_template() && ! vp_starts_full_width() ) {
			wp_add_inline_style( 'vp-site', 'body.vp-header-on #content{padding-top:' . $offset . 'px}' );
		}

		wp_enqueue_style( 'vp-motion', VP_URI . '/assets/css/vp-motion.css', array( 'vp-site' ), vp_ver( 'assets/css/vp-motion.css' ) );
		wp_enqueue_script( 'vp-site', VP_URI . '/assets/js/vp-site.js', array(), vp_ver( 'assets/js/vp-site.js' ), true );
		// Animations for the PHP templates and graphics (replaces the React bundle on Home and Pricing).
		wp_enqueue_script( 'vp-motion', VP_URI . '/assets/js/vp-motion.js', array(), vp_ver( 'assets/js/vp-motion.js' ), true );
		wp_localize_script(
			'vp-site',
			'VP_SITE',
			array(
				'ajax'     => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'vp_consult' ),
				'exit'     => (bool) vp_opt( 'vp_consult_exit' ),
				'endpoint' => (string) vp_opt( 'vp_consult_endpoint' ),
			)
		);
	},
	20
);

/**
 * style.css (the site's own custom CSS, moved out of Customize → Additional CSS).
 * Printed at the same place WordPress prints Additional CSS (end of <head>, priority 101),
 * so it still wins over plugin and Kadence styles exactly like before.
 */
add_action(
	'wp_head',
	function () {
		wp_register_style( 'vp-child', get_stylesheet_uri(), array(), vp_ver( 'style.css' ) );
		wp_print_styles( 'vp-child' );
	},
	101
);

/** Mark JS as available as early as possible so reveal classes can hide content before paint (never in the editor). */
add_action(
	'wp_head',
	function () {
		echo "<script>document.documentElement.classList.add('vp-js');setTimeout(function(){if(!window.VP_REVEAL_READY)document.documentElement.classList.add('vp-noanim')},3000);</script>\n";
	},
	1
);


/* -------------------------------------------------------------------------
 * 4. Body classes and Astra layout
 * ---------------------------------------------------------------------- */
add_filter(
	'body_class',
	function ( $classes ) {
		if ( vp_opt( 'vp_site_style' ) ) {
			$classes[] = 'vp-site grain';
		}
		if ( vp_opt( 'vp_use_header' ) ) {
			$classes[] = 'vp-header-on';
		}
		if ( vp_is_vp_template() ) {
			$classes[] = 'vp-template';
			$classes[] = 'ast-page-builder-template';
		}
		return $classes;
	}
);

// Full-width, no sidebar, no title for the VuloPilot templates.
add_filter( 'astra_page_layout', function ( $l ) { return vp_is_vp_template() ? 'no-sidebar' : $l; } );
add_filter( 'astra_get_content_layout', function ( $l ) { return vp_is_vp_template() ? 'page-builder' : $l; } );
add_filter( 'astra_the_title_enabled', function ( $on ) { return vp_is_vp_template() ? false : $on; } );
add_filter( 'astra_featured_image_enabled', function ( $on ) { return vp_is_vp_template() ? false : $on; } );

/* -------------------------------------------------------------------------
 * 5. Editor: load the design tokens + Tailwind so blocks/shortcodes preview correctly.
 * ---------------------------------------------------------------------- */
add_action(
	'enqueue_block_assets',
	function () {
		if ( ! is_admin() ) {
			return;
		}
		wp_enqueue_style( 'vp-tailwind', VP_URI . '/assets/css/vp-tailwind.css', array(), vp_ver( 'assets/css/vp-tailwind.css' ) );
		wp_enqueue_style( 'vp-site', VP_URI . '/assets/css/vp-site.css', array( 'vp-tailwind' ), vp_ver( 'assets/css/vp-site.css' ) );
		wp_enqueue_style( 'vp-motion', VP_URI . '/assets/css/vp-motion.css', array( 'vp-site' ), vp_ver( 'assets/css/vp-motion.css' ) );
	}
);
