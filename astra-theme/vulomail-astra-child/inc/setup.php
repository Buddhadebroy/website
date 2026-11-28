<?php
/**
 * Assets, fonts, design tokens, page templates and the blog template switch.
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'after_setup_theme',
	function () {
		load_child_theme_textdomain( 'vulomail', VM_DIR . 'languages' );
		add_theme_support( 'custom-logo', array( 'height' => 120, 'width' => 180, 'flex-width' => true, 'flex-height' => true ) );
		add_theme_support( 'title-tag' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'editor-styles' );
		register_nav_menus(
			array(
				'vm_primary' => __( 'VuloMail header menu', 'vulomail' ),
				'vm_more'    => __( 'VuloMail footer: More from VuloLabs', 'vulomail' ),
			)
		);
	},
	20
);

/** Google Fonts URL for Nunito + Geist Mono (the original loads both through next/font). */
function vm_fonts_url() {
	return 'https://fonts.googleapis.com/css2?family=Geist+Mono:wght@100..900&family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap';
}

/** Front-end assets. */
function vm_enqueue_assets() {
	$v = VM_VERSION;
	if ( 'google' === vm_opt( 'font_loading' ) ) {
		wp_enqueue_style( 'vm-fonts', vm_fonts_url(), array(), null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
	}
	$parent = wp_style_is( 'astra-theme-css', 'registered' ) ? array( 'astra-theme-css' ) : array();
	wp_enqueue_style( 'vm-tailwind', VM_URI . 'assets/css/vm-tailwind.css', $parent, $v );
	wp_enqueue_style( 'vm-site', VM_URI . 'assets/css/vm-site.css', array( 'vm-tailwind' ), $v );
	wp_enqueue_style( 'vm-effects', VM_URI . 'assets/css/vm-effects.css', array( 'vm-site' ), $v );
	wp_enqueue_style( 'vm-kadence', VM_URI . 'assets/css/vm-kadence.css', array( 'vm-effects' ), $v );
	wp_add_inline_style( 'vm-kadence', vm_root_vars_css() );

	if ( vm_opt( 'smooth_scroll' ) ) {
		wp_enqueue_script( 'vm-lenis', VM_URI . 'assets/js/vendor/lenis.min.js', array(), '1.3.26', true );
	}
	wp_enqueue_script( 'vm-effects', VM_URI . 'assets/js/vm-effects.js', array(), $v, true );
	wp_enqueue_script( 'vm-site', VM_URI . 'assets/js/vm-site.js', array( 'vm-effects' ), $v, true );
	wp_localize_script(
		'vm-effects',
		'vmConfig',
		array(
			'smooth'     => (bool) vm_opt( 'smooth_scroll' ),
			'progress'   => (bool) vm_opt( 'scroll_progress' ),
			'hideHeader' => (bool) vm_opt( 'header_hide' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'vm_enqueue_assets', 20 );

/** The same stylesheets inside the block editor, so patterns look like the front end while you edit. */
add_action(
	'enqueue_block_assets',
	function () {
		if ( ! is_admin() ) {
			return;
		}
		$v = VM_VERSION;
		if ( 'google' === vm_opt( 'font_loading' ) ) {
			wp_enqueue_style( 'vm-fonts', vm_fonts_url(), array(), null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
		}
		wp_enqueue_style( 'vm-tailwind', VM_URI . 'assets/css/vm-tailwind.css', array(), $v );
		wp_enqueue_style( 'vm-site', VM_URI . 'assets/css/vm-site.css', array( 'vm-tailwind' ), $v );
		wp_enqueue_style( 'vm-kadence', VM_URI . 'assets/css/vm-kadence.css', array( 'vm-site' ), $v );
		wp_enqueue_style( 'vm-kadence-editor', VM_URI . 'assets/css/vm-kadence-editor.css', array( 'vm-kadence' ), $v );
		wp_enqueue_style( 'vm-editor', VM_URI . 'assets/css/vm-editor.css', array( 'vm-kadence-editor' ), $v );
		wp_add_inline_style( 'vm-kadence', vm_root_vars_css() );
	}
);

/** vm-tw sets the site's background and font on the front end; vm-canvas marks full-width pattern pages. */
add_filter(
	'body_class',
	function ( $classes ) {
		$classes[] = 'vm-tw';
		if ( vm_is_canvas() ) {
			$classes[] = 'vm-canvas';
		}
		if ( vm_opt( 'use_header' ) ) {
			$classes[] = 'vm-has-header';
		}
		return $classes;
	}
);

/**
 * True on pages built from the VuloMail patterns (meta _vm_canvas, set by Appearance → VuloMail → Build pages,
 * or content that uses the theme's patterns / Kadence rows), on the 404 page and on the theme's blog views.
 * There are no page templates: these pages use Astra's own page template with a full-width, title-less layout.
 */
function vm_is_canvas() {
	if ( is_404() ) {
		return true;
	}
	if ( is_page() ) {
		$id = get_queried_object_id();
		if ( get_post_meta( $id, '_vm_canvas', true ) ) {
			return true;
		}
		$content = (string) get_post_field( 'post_content', $id );
		if ( false !== strpos( $content, 'wp:kadence/rowlayout' ) || false !== strpos( $content, ' vm-row' ) ) {
			return true;
		}
		if ( preg_match_all( '/wp:block \{"ref":(\d+)/', $content, $m ) ) {
			foreach ( $m[1] as $ref ) {
				if ( vm_is_theme_pattern( (int) $ref ) ) {
					return true;
				}
			}
		}
		return false;
	}
	return vm_blog_templates_active();
}

function vm_blog_templates_active() {
	return vm_opt( 'blog_templates' ) && ( is_singular( 'post' ) || is_home() || is_category() || is_tag() );
}

/** Pattern pages: no Astra title, no sidebar, no boxed container, no header offset. */
add_filter(
	'astra_page_layout',
	function ( $layout ) {
		return vm_is_canvas() ? 'no-sidebar' : $layout;
	}
);
add_filter(
	'astra_get_content_layout',
	function ( $layout ) {
		return vm_is_canvas() ? 'page-builder' : $layout;
	}
);
add_filter(
	'astra_the_title_enabled',
	function ( $on ) {
		return vm_is_canvas() ? false : $on;
	}
);
add_filter(
	'astra_featured_image_enabled',
	function ( $on ) {
		return vm_is_canvas() ? false : $on;
	}
);

/** Blog index and single post use the theme's own templates (Customize → VuloMail → Blog templates). */
add_filter(
	'template_include',
	function ( $template ) {
		if ( ! vm_opt( 'blog_templates' ) ) {
			return $template;
		}
		if ( is_singular( 'post' ) ) {
			return VM_DIR . 'template-parts/blog/single.php';
		}
		if ( is_home() || is_category() || is_tag() ) {
			return VM_DIR . 'template-parts/blog/index.php';
		}
		return $template;
	},
	20
);

/** Reading time like the original: words / 220, at least 1. */
function vm_read_minutes( $post = null ) {
	$post  = get_post( $post );
	$words = str_word_count( wp_strip_all_tags( (string) $post->post_content ) );
	return max( 1, (int) round( $words / 220 ) );
}

/** Marks JS early so reveal start states apply before first paint (same as the original's SSR styles). */
add_action(
	'wp_head',
	function () {
		echo "<script>document.documentElement.classList.add('vm-js');</script>\n";
	},
	1
);

/** The 404 page uses the full-width layout as well. */
add_filter(
	'body_class',
	function ( $c ) {
		if ( is_404() ) {
			$c[] = 'vm-canvas';
		}
		return $c;
	},
	30
);
