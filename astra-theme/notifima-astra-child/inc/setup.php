<?php
/**
 * Assets, Astra integration and templates.
 *
 *   1. Template helpers   nf_is_nf_template()
 *   2. Theme setup        text domain, editor styles, menu locations
 *   3. Assets             fonts, design CSS (nf-site), Kadence wrappers (nf-kadence), JS, icon sprite, style.css last
 *   4. Body classes and Astra layout (full width, no title, no sidebar)
 *   5. Blog templates     single post + blog index use template-parts/blog/
 *   6. Editor assets
 *
 * @package Notifima
 */

defined( 'ABSPATH' ) || exit;

/* 1. Template helpers ----------------------------------------------------- */

/** Is this one of our full-width pages? (Canvas template, a page built from our patterns, posts, blog index) */
function nf_is_nf_template() {
	static $is = null;
	if ( null !== $is ) {
		return $is;
	}
	$is = false;
	if ( is_page() && in_array( get_page_template_slug(), array( 'page-templates/template-canvas.php', 'page-templates/template-release-notes.php', 'page-templates/template-release-notes-pro.php' ), true ) ) {
		$is = true;
	} elseif ( function_exists( 'nf_is_relpro_page' ) && nf_is_relpro_page() ) {
		$is = true;
	} elseif ( function_exists( 'nf_is_docs_page' ) && nf_is_docs_page() ) {
		$is = true;
	} elseif ( is_singular() ) {
		$content = (string) get_post_field( 'post_content', get_queried_object_id() );
		$is      = false !== strpos( $content, '"className":"nf-k' ) || nf_uses_synced_pattern( $content ) || is_singular( 'post' );
	} elseif ( is_home() ) {
		$is = true;
	} elseif ( nf_shop_page_id() ) {
		$is = true;
	}
	return $is;
}

/**
 * The WooCommerce Shop page when it is one of our Kadence pages (e.g. Pricing set as Shop page), else 0.
 * On the shop URL we then show that page as built, not the product list.
 */
function nf_shop_page_id() {
	if ( ! function_exists( 'is_shop' ) || ! function_exists( 'wc_get_page_id' ) || ! is_shop() || is_search() ) {
		return 0;
	}
	$id = (int) wc_get_page_id( 'shop' );
	if ( $id <= 0 ) {
		return 0;
	}
	$content = (string) get_post_field( 'post_content', $id );
	$tpl     = get_page_template_slug( $id );
	$ours    = 'page-templates/template-canvas.php' === $tpl || false !== strpos( $content, 'nf-k' ) || false !== strpos( $content, 'wp:kadence/' ) || nf_uses_synced_pattern( $content );
	return $ours ? $id : 0;
}

/* 2. Theme setup ------------------------------------------------------------ */
add_action(
	'after_setup_theme',
	function () {
		load_child_theme_textdomain( 'notifima', NF_DIR . '/languages' );
		add_theme_support( 'editor-styles' );
		register_nav_menus( array( 'nf_header' => __( 'Notifima header', 'notifima' ) ) );
	}
);

/* 3. Assets ----------------------------------------------------------------- */
add_action(
	'init',
	function () {
		wp_register_style( 'nf-fonts', NF_URI . '/assets/css/nf-fonts.css', array(), nf_ver( 'assets/css/nf-fonts.css' ) );
		wp_register_style( 'nf-site', NF_URI . '/assets/css/nf-site.css', nf_opt( 'nf_load_font' ) ? array( 'nf-fonts' ) : array(), nf_ver( 'assets/css/nf-site.css' ) );
		wp_register_style( 'nf-kadence', NF_URI . '/assets/css/nf-kadence.css', array( 'nf-site' ), nf_ver( 'assets/css/nf-kadence.css' ) );
		wp_register_style( 'nf-blocks', NF_URI . '/assets/css/nf-blocks.css', array( 'nf-kadence' ), nf_ver( 'assets/css/nf-blocks.css' ) );
		wp_register_style( 'nf-blog', NF_URI . '/assets/css/nf-blog.css', array( 'nf-blocks' ), nf_ver( 'assets/css/nf-blog.css' ) );
		wp_register_style( 'nf-mega', NF_URI . '/assets/css/nf-mega.css', array( 'nf-blocks' ), nf_ver( 'assets/css/nf-mega.css' ) );
		wp_register_script( 'nf-site', NF_URI . '/assets/js/nf-site.js', array( 'jquery' ), nf_ver( 'assets/js/nf-site.js' ), true );
	},
	5
);

add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_style( 'nf-blocks' ); // + nf-fonts, nf-site, nf-kadence
		wp_enqueue_style( 'nf-mega' );   // the mega menu pattern
		if ( is_singular( 'post' ) ) {
			wp_enqueue_style( 'nf-blog' ); // the single post design and its components
		}
		wp_enqueue_script( 'nf-site' );
		$offset = (int) nf_opt( 'nf_content_offset' );
		if ( $offset && ! nf_is_nf_template() ) {
			wp_add_inline_style( 'nf-kadence', 'body.nf-header-on #content{padding-top:' . $offset . 'px}' );
		}
	},
	20
);

/** style.css (your own CSS) is printed last in <head>, so it wins over plugin, Astra and theme styles. */
add_action(
	'wp_head',
	function () {
		wp_register_style( 'nf-child', get_stylesheet_uri(), array(), nf_ver( 'style.css' ) );
		wp_print_styles( 'nf-child' );
	},
	101
);

/** JS flag before paint (reveal animations hide content until it scrolls in; everything shows if JS fails) + font preload. */
add_action(
	'wp_head',
	function () {
		echo "<script>document.documentElement.classList.add('js');setTimeout(function(){if(!window.NF_READY)document.documentElement.classList.remove('js')},3000);</script>\n";
		if ( nf_opt( 'nf_load_font' ) ) {
			echo '<link rel="preload" href="' . esc_url( NF_URI . '/assets/fonts/outfit-latin-wght-normal.woff2' ) . '" as="font" type="font/woff2" crossorigin>' . "\n";
		}
	},
	1
);

/** The icon sprite (every {icon} of the site), once per page. */
add_action(
	'wp_body_open',
	function () {
		echo file_get_contents( NF_DIR . '/assets/img/icons.svg' ); // phpcs:ignore
	}
);

/* 4. Body classes and Astra layout ------------------------------------------- */
add_filter(
	'body_class',
	function ( $classes ) {
		if ( nf_opt( 'nf_use_header' ) ) {
			$classes[] = 'nf-header-on';
		}
		if ( nf_is_nf_template() ) {
			$classes[] = 'nf-template';
			$classes[] = 'ast-page-builder-template';
		}
		return $classes;
	}
);
add_filter( 'astra_page_layout', function ( $l ) { return nf_is_nf_template() ? 'no-sidebar' : $l; } );
add_filter( 'astra_get_content_layout', function ( $l ) { return nf_is_nf_template() ? 'page-builder' : $l; } );
add_filter( 'astra_the_title_enabled', function ( $on ) { return nf_is_nf_template() ? false : $on; } );
add_filter( 'astra_featured_image_enabled', function ( $on ) { return nf_is_nf_template() ? false : $on; } );

/* 5. Blog: single posts and the blog index use our layout ---------------------- */
add_filter(
	'template_include',
	function ( $template ) {
		if ( is_singular( 'post' ) ) {
			return NF_DIR . '/template-parts/blog/single.php';
		}
		if ( is_home() ) {
			return NF_DIR . '/template-parts/blog/index.php';
		}
		if ( nf_shop_page_id() ) {
			return NF_DIR . '/template-parts/shop-page.php';
		}
		return $template;
	},
	9999
);

/* 6. Editor: the same CSS, so patterns look right while you edit them ---------- */
add_action(
	'enqueue_block_assets',
	function () {
		if ( is_admin() ) {
			wp_enqueue_style( 'nf-blocks' );
			wp_enqueue_style( 'nf-blog' );
			wp_enqueue_style( 'nf-mega' );
		}
	}
);
add_action(
	'enqueue_block_editor_assets',
	function () {
		// The sprite in the editor too, so icons inside patterns show.
		wp_add_inline_script( 'wp-blocks', 'document.addEventListener("DOMContentLoaded",function(){var d=document.createElement("div");d.style.display="none";d.innerHTML=' . wp_json_encode( file_get_contents( NF_DIR . '/assets/img/icons.svg' ) ) . ';document.body.appendChild(d)});' ); // phpcs:ignore
	}
);

/*
 * Content width for Kadence "Inherit Max Width from Theme" (every Notifima section uses it).
 * Kadence sizes those rows with the global $content_width. Astra sets it to 700 (its narrow text column), so it is
 * set here to Astra's Container width (Customize → Global → Container → Container Width, default 1200) plus Astra's
 * 2 × 20px container padding: 1240px, the width of the original design. Change the Astra setting and every
 * section follows.
 */
function nf_kadence_content_width() {
	$w = function_exists( 'astra_get_option' ) ? (int) astra_get_option( 'site-content-width', 1200 ) : 1200;
	return ( $w > 0 ? $w : 1200 ) + 40;
}
add_filter( 'astra_content_width', 'nf_kadence_content_width', 99 );
add_action(
	'after_setup_theme',
	function () {
		$GLOBALS['content_width'] = nf_kadence_content_width();
	},
	99
);
add_action(
	'template_redirect',
	function () {
		$GLOBALS['content_width'] = nf_kadence_content_width();
	},
	99
);
