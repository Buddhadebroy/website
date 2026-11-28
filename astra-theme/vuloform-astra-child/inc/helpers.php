<?php
/**
 * Small helpers used everywhere.
 *
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;

/** Version for a theme file: theme version + file time, so browsers reload it after every change. */
function vf_ver( $file ) {
	return VF_VERSION . '.' . filemtime( VF_DIR . '/' . $file );
}

/** A JSON file from /data as an array (cached per request). */
function vf_data( $name ) {
	static $cache = array();
	if ( ! isset( $cache[ $name ] ) ) {
		$file           = VF_DIR . '/data/' . sanitize_file_name( $name ) . '.json';
		$cache[ $name ] = file_exists( $file ) ? (array) json_decode( file_get_contents( $file ), true ) : array(); // phpcs:ignore WordPress.WP.AlternativeFunctions
	}
	return $cache[ $name ];
}

/** Site-relative paths ("/compare", "/#faq") become full URLs; external links stay as they are. */
function vf_url( $href ) {
	if ( 0 === strpos( $href, '/' ) && 0 !== strpos( $href, '//' ) ) {
		return home_url( $href );
	}
	return $href;
}

/** Download / docs links (Customize → VuloForm). */
function vf_download_url() {
	return (string) vf_opt( 'vf_download_url' );
}
function vf_docs_url( $path = '' ) {
	return rtrim( (string) vf_opt( 'vf_download_url' ), '/' ) . '/docs' . $path;
}

/** Load a section file: vf_section( 'home', 'hero' ) → template-parts/home/hero.php. */
function vf_section( $group, $name, $args = array() ) {
	get_template_part( 'template-parts/' . $group . '/' . $name, null, $args );
}

/** Small pill above a heading. $tone: mint | navy. */
function vf_eyebrow( $text, $tone = 'mint' ) {
	$box = 'mint' === $tone ? 'border-mint/30 bg-mint-soft text-[#0f7a53]' : 'border-navy/15 bg-sky-soft text-navy';
	$dot = 'mint' === $tone ? 'bg-mint' : 'bg-navy';
	return '<span class="inline-flex items-center gap-2 rounded-full border px-3.5 py-1.5 text-xs font-semibold tracking-wide uppercase ' . $box . '">'
		. '<span class="h-1.5 w-1.5 rounded-full ' . $dot . '"></span>' . esc_html( $text ) . '</span>';
}

/** "24 October 2026". */
function vf_date( $iso ) {
	return wp_date( 'j F Y', strtotime( $iso . ' 12:00:00 UTC' ), new DateTimeZone( 'UTC' ) );
}

/** Print a JSON-LD script. */
function vf_jsonld( $data ) {
	echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_HEX_TAG ) . "</script>\n"; // phpcs:ignore
}

/** Heading text → anchor id ("Plan the steps" → "plan-the-steps"). */
function vf_slug( $text ) {
	return trim( preg_replace( '/[^a-z0-9]+/', '-', strtolower( wp_strip_all_tags( $text ) ) ), '-' );
}
