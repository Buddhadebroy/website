<?php
/**
 * Assets, Astra integration and templates.
 *
 *   1. Template helpers   vf_template_map(), vf_is_vf_template()
 *   2. Theme setup        text domain, editor styles, menu location
 *   3. Assets             registered once at init; front end, smooth scroll, style.css
 *   4. Body classes and Astra layout
 *   5. Blog templates     single post + blog index use template-parts/blog/
 *   6. Editor assets
 *
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;

/* -------------------------------------------------------------------------
 * 1. Template helpers
 * ---------------------------------------------------------------------- */

/** Page templates that render a full-width VuloForm page. */
function vf_template_map() {
	return array(
		'page-templates/template-home.php'           => 'home',
		'page-templates/template-compare.php'        => 'compare',
		'page-templates/template-compare-plugin.php' => 'compare-plugin',
		'page-templates/template-canvas.php'         => 'canvas',
	);
}

/** Is this one of our full-width pages (templates above, single posts, the blog index)? */
function vf_is_vf_template() {
	if ( is_page() && isset( vf_template_map()[ get_page_template_slug() ] ) ) {
		return true;
	}
	if ( is_singular() && false !== strpos( (string) get_post_field( 'post_content', get_queried_object_id() ), '"className":"vf-k ' ) ) {
		return true; // a page built from the Kadence patterns
	}
	if ( is_singular() && vf_uses_synced_pattern( (string) get_post_field( 'post_content', get_queried_object_id() ) ) ) {
		return true; // a page that shows our synced patterns
	}
	return is_singular( 'post' ) || is_home();
}

/* -------------------------------------------------------------------------
 * 2. Theme setup
 * ---------------------------------------------------------------------- */
add_action(
	'after_setup_theme',
	function () {
		load_child_theme_textdomain( 'vuloform', VF_DIR . '/languages' );
		add_theme_support( 'editor-styles' );
		register_nav_menus( array( 'vf_header' => __( 'VuloForm header', 'vuloform' ) ) );
	}
);

/* -------------------------------------------------------------------------
 * 3. Front-end assets
 * ---------------------------------------------------------------------- */
/** Register the theme CSS/JS once (blocks list these handles in block.json, so they load wherever a block is used). */
add_action(
	'init',
	function () {
		if ( vf_font_url() ) {
			wp_register_style( 'vf-font', vf_font_url(), array(), null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
		}
		wp_register_style( 'vf-tailwind', VF_URI . '/assets/css/vf-tailwind.css', vf_font_url() ? array( 'vf-font' ) : array(), vf_ver( 'assets/css/vf-tailwind.css' ) );
		wp_register_style( 'vf-site', VF_URI . '/assets/css/vf-site.css', array( 'vf-tailwind' ), vf_ver( 'assets/css/vf-site.css' ) );
		wp_register_style( 'vf-effects', VF_URI . '/assets/css/vf-effects.css', array( 'vf-site' ), vf_ver( 'assets/css/vf-effects.css' ) );
		wp_register_style( 'vf-kadence', VF_URI . '/assets/css/vf-kadence.css', array( 'vf-effects' ), vf_ver( 'assets/css/vf-kadence.css' ) );
		wp_register_script( 'vf-effects', VF_URI . '/assets/js/vf-effects.js', array(), vf_ver( 'assets/js/vf-effects.js' ), true );
		wp_register_script( 'vf-site', VF_URI . '/assets/js/vf-site.js', array( 'vf-effects' ), vf_ver( 'assets/js/vf-site.js' ), true );
	},
	5
);

add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_style( 'vf-kadence' ); // pulls in vf-font, vf-tailwind, vf-site, vf-effects
		wp_enqueue_script( 'vf-site' );   // pulls in vf-effects

		// 1rem = 16px like the original. Astra prints html{font-size:112.5%} for an 18px body font, which scales every rem size by 1.125.
		if ( vf_opt( 'vf_base_16' ) ) {
			wp_add_inline_style( 'vf-site', 'html:root{font-size:100%}' );
		}

		// Content width and every other CSS variable: inc/root-vars.php (Customize → VuloForm → Design tokens).

		// Space under the fixed header on normal Astra pages (our templates bring their own).
		$offset = (int) vf_opt( 'vf_content_offset' );
		if ( $offset && vf_opt( 'vf_use_header' ) && ! vf_is_vf_template() ) {
			wp_add_inline_style( 'vf-site', 'body.vf-header-on #content{padding-top:' . $offset . 'px}' );
		}

		// Smooth scrolling, same library and settings as the Next.js site.
		if ( vf_opt( 'vf_smooth_scroll' ) ) {
			wp_enqueue_script( 'lenis', 'https://cdn.jsdelivr.net/npm/lenis@1.3.19/dist/lenis.min.js', array(), '1.3.19', true );
			wp_add_inline_script( 'lenis', 'if(!matchMedia("(prefers-reduced-motion: reduce)").matches){window.vfLenis=new Lenis({autoRaf:true,lerp:.1,anchors:{offset:-80}});document.documentElement.classList.add("lenis")}' );
		}
	},
	20
);

/** style.css (your own custom CSS) is printed last in <head>, so it wins over plugin and Astra styles. */
add_action(
	'wp_head',
	function () {
		wp_register_style( 'vf-child', get_stylesheet_uri(), array(), vf_ver( 'style.css' ) );
		wp_print_styles( 'vf-child' );
	},
	101
);

/** Mark JS as available early so reveal classes can hide content before paint. Shows everything if JS fails. */
add_action(
	'wp_head',
	function () {
		echo "<script>document.documentElement.classList.add('vf-js');setTimeout(function(){if(!window.VF_FX)document.documentElement.classList.add('vf-noanim')},3000);</script>\n";
		if ( vf_font_url() ) {
			echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n" . '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
		}
	},
	1
);

/* -------------------------------------------------------------------------
 * 4. Body classes and Astra layout
 * ---------------------------------------------------------------------- */
add_filter(
	'body_class',
	function ( $classes ) {
		if ( vf_opt( 'vf_site_style' ) ) {
			$classes[] = 'vf-site grain';
		}
		if ( vf_opt( 'vf_use_header' ) ) {
			$classes[] = 'vf-header-on';
		}
		if ( vf_is_vf_template() ) {
			$classes[] = 'vf-template';
			$classes[] = 'ast-page-builder-template';
		}
		return $classes;
	}
);

// Full width, no sidebar, no Astra title for our templates.
add_filter( 'astra_page_layout', function ( $l ) { return vf_is_vf_template() ? 'no-sidebar' : $l; } );
add_filter( 'astra_get_content_layout', function ( $l ) { return vf_is_vf_template() ? 'page-builder' : $l; } );
add_filter( 'astra_the_title_enabled', function ( $on ) { return vf_is_vf_template() ? false : $on; } );
add_filter( 'astra_featured_image_enabled', function ( $on ) { return vf_is_vf_template() ? false : $on; } );

/* -------------------------------------------------------------------------
 * 5. Blog: single posts and the blog index use our layout (template-parts/blog/)
 * ---------------------------------------------------------------------- */
add_filter(
	'template_include',
	function ( $template ) {
		if ( is_singular( 'post' ) ) {
			return VF_DIR . '/template-parts/blog/single.php';
		}
		if ( is_home() ) {
			return VF_DIR . '/template-parts/blog/index.php';
		}
		return $template;
	},
	99
);

/* -------------------------------------------------------------------------
 * 6. Editor: tokens, Tailwind, effects and Kadence pattern styles so blocks and patterns preview correctly.
 * ---------------------------------------------------------------------- */
add_action(
	'enqueue_block_assets',
	function () {
		if ( is_admin() ) {
			wp_enqueue_style( 'vf-kadence' ); // + vf-font, vf-tailwind, vf-site, vf-effects
		}
	}
);
