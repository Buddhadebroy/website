<?php
/**
 * Small helpers: icons, data files, prerendered markup.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

/**
 * Lucide icon as inline SVG (same icon set as the Next.js site).
 *
 * @param string     $name   Kebab-case lucide name, e.g. "arrow-right".
 * @param int        $size   Pixel size.
 * @param string     $class  Extra classes.
 * @param int|string $stroke Stroke width.
 */
function vp_icon( $name, $size = 16, $class = '', $stroke = 2 ) {
	static $icons = null;
	if ( null === $icons ) {
		$icons = require VP_DIR . '/inc/icons-data.php';
	}
	$name  = sanitize_key( $name );
	$inner = isset( $icons[ $name ] ) ? $icons[ $name ] : $icons['sparkles'];
	return sprintf(
		'<svg xmlns="http://www.w3.org/2000/svg" width="%1$d" height="%1$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="%2$s" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-%3$s %4$s" aria-hidden="true">%5$s</svg>',
		(int) $size,
		esc_attr( $stroke ),
		esc_attr( $name ),
		esc_attr( $class ),
		$inner // Generated, trusted SVG paths.
	);
}

/** All icon names available to vp_icon(). */
function vp_icon_names() {
	$icons = require VP_DIR . '/inc/icons-data.php';
	return array_keys( $icons );
}

/** One icon name from the shipped set, or a fallback. */
function vp_safe_icon( $name, $fallback = 'sparkles' ) {
	static $names = null;
	if ( null === $names ) {
		$names = array_flip( vp_icon_names() );
	}
	$name = sanitize_key( (string) $name );
	return isset( $names[ $name ] ) ? $name : $fallback;
}

/** Decoded JSON from /data (cached per request). */
function vp_data( $file ) {
	static $cache = array();
	if ( ! isset( $cache[ $file ] ) ) {
		$path            = VP_DIR . '/data/' . $file . '.json';
		$cache[ $file ] = file_exists( $path ) ? json_decode( file_get_contents( $path ), true ) : array(); // phpcs:ignore WordPress.WP.AlternativeFunctions
	}
	return $cache[ $file ];
}

/** Base URL for theme assets; must match window.VP_DATA.assets for hydration. */
function vp_assets_url() {
	return untrailingslashit( VP_URI ) . '/assets';
}

/** Theme logo URL: custom logo from the Customizer, else the bundled VuloPilot logo. */
function vp_logo_html( $class = 'h-9 w-auto' ) {
	$id  = get_theme_mod( 'custom_logo' );
	$src = $id ? wp_get_attachment_image_url( $id, 'full' ) : '';
	$src = $src ? $src : 'https://vulopilot.com/wp-content/uploads/2026/10/brand-logo.png';
	return sprintf(
		'<a href="%1$s" aria-label="%2$s home" class="group flex items-center transition-transform duration-300 hover:scale-[1.03]"><img src="%3$s" width="129" height="36" alt="%2$s" class="%4$s" /></a>',
		esc_url( home_url( '/' ) ),
		esc_attr( get_bloginfo( 'name' ) ),
		esc_url( $src ),
		esc_attr( $class )
	);
}

/** Last path segment of a URL, used to pick menu icons. */
function vp_slug_of( $href ) {
	$path  = (string) wp_parse_url( $href, PHP_URL_PATH );
	$parts = array_values( array_filter( explode( '/', $path ) ) );
	return $parts ? end( $parts ) : '';
}

/** Icon name for a menu link: explicit class "vp-icon-NAME" wins, then the site's slug map. */
function vp_icon_for( $href, $classes = array(), $fallback = 'sparkles' ) {
	foreach ( (array) $classes as $c ) {
		if ( 0 === strpos( $c, 'vp-icon-' ) ) {
			return substr( $c, 8 );
		}
	}
	$map  = vp_data( 'site' )['iconFor'] ?? array();
	$slug = vp_slug_of( $href );
	return isset( $map[ $slug ] ) ? $map[ $slug ] : $fallback;
}

/**
 * Make a root-relative link ("/pricing/") work when WordPress lives in a sub-folder.
 */
function vp_url( $href ) {
	if ( is_string( $href ) && 0 === strpos( $href, '/' ) && 0 !== strpos( $href, '//' ) ) {
		return home_url( $href );
	}
	// Links copied from the Vercel preview (e.g. https://vulopilotcom.vercel.app/compare/...) or saved
	// on an old staging address point to the wrong site. Send them to the same path on this site.
	if ( is_string( $href ) && preg_match( '#^https?://([^/:]+)(?::\d+)?(/.*)?$#i', $href, $m ) ) {
		$host    = strtolower( $m[1] );
		$foreign = apply_filters( 'vp_foreign_hosts', array( 'vulopilotcom.vercel.app', 'vulopilot.vercel.app', 'localhost', '127.0.0.1' ) );
		$own     = strtolower( (string) wp_parse_url( home_url( '/' ), PHP_URL_HOST ) );
		if ( $host !== $own && ( in_array( $host, $foreign, true ) || '.vercel.app' === substr( $host, -11 ) ) ) {
			return home_url( isset( $m[2] ) && '' !== $m[2] ? $m[2] : '/' );
		}
	}
	return $href;
}

/**
 * Page content pasted from the Vercel preview can hold absolute links such as
 * https://vulopilotcom.vercel.app/compare/vulopilot-vs-yoast/. Point them at this site.
 */
function vp_fix_foreign_links( $html ) {
	if ( ! is_string( $html ) || false === stripos( $html, '.vercel.app' ) ) {
		return $html;
	}
	return preg_replace_callback(
		'#(href=["\'])(https?://[a-z0-9.-]+\.vercel\.app(?:/[^"\']*)?)#i',
		function ( $m ) {
			return $m[1] . vp_url( $m[2] );
		},
		$html
	);
}
add_filter( 'the_content', 'vp_fix_foreign_links', 20 );
add_filter( 'widget_block_content', 'vp_fix_foreign_links', 20 );
