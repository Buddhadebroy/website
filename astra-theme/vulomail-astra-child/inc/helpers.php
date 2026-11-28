<?php
/**
 * Small helpers shared by templates, the graphic block and the admin.
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

/** Theme option defaults (Customize → VuloMail). */
function vm_defaults() {
	return array(
		'use_header'      => true,
		'use_footer'      => true,
		'blog_templates'  => true,
		'smooth_scroll'   => true,
		'scroll_progress' => true,
		'header_hide'     => false,
		'font_loading'    => 'google',
		'use_astra_width' => false,
		'cta_url'         => '/get-started/',
		'docs_url'        => 'https://github.com/vulolabs/vulolabs/tree/main/plugins/vulomail/docs',
		'repo_url'        => 'https://github.com/vulolabs/vulolabs/tree/main/plugins/vulomail',
		'title_accent'    => 'Mail',
		'show_title'      => true,
		'contact_email'   => '',
	);
}

/** Reads one theme option. */
function vm_opt( $key ) {
	$d = vm_defaults();
	return get_theme_mod( 'vm_' . $key, isset( $d[ $key ] ) ? $d[ $key ] : null );
}

/** Lucide icon (same paths and attributes the original site renders). */
function vm_icon( $name, $class = 'vm:h-4 vm:w-4', $extra = '' ) {
	static $icons = null;
	if ( null === $icons ) {
		$icons = vm_data( 'icons' );
	}
	if ( empty( $icons[ $name ] ) ) {
		return '';
	}
	return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-' . esc_attr( $name ) . ' ' . esc_attr( $class ) . '" aria-hidden="true"' . $extra . '>' . $icons[ $name ] . '</svg>';
}

/** Reads data/<name>.json once per request. */
function vm_data( $name ) {
	static $cache = array();
	if ( ! isset( $cache[ $name ] ) ) {
		$file           = VM_DIR . 'data/' . $name . '.json';
		$cache[ $name ] = is_readable( $file ) ? json_decode( (string) file_get_contents( $file ), true ) : array(); // phpcs:ignore WordPress.WP.AlternativeFunctions
	}
	return $cache[ $name ];
}

/** Renders graphic/parts/<name>.php and returns the HTML. */
function vm_part( $name, $args = array() ) {
	$name = sanitize_key( str_replace( '/', '-', $name ) );
	$file = VM_DIR . 'graphic/parts/' . $name . '.php';
	if ( ! is_readable( $file ) ) {
		return '';
	}
	$a = is_array( $args ) ? $args : array();
	ob_start();
	include $file;
	return (string) ob_get_clean();
}

/** A site-relative link that follows the WordPress home URL. */
function vm_url( $path ) {
	if ( preg_match( '#^(https?:|mailto:|\#)#', $path ) ) {
		return $path;
	}
	$hash = '';
	if ( false !== strpos( $path, '#' ) ) {
		list( $path, $hash ) = explode( '#', $path, 2 );
		$hash                = '#' . $hash;
	}
	$path = '/' === $path ? '/' : trailingslashit( $path );
	return home_url( $path ) . $hash;
}

/** `[label](/path)`, `**bold**` and `code` inside a text string, like the original <Rich>. */
function vm_rich( $text ) {
	$parts = preg_split( '/(\[[^\]]+\]\([^)]+\)|\*\*[^*]+\*\*|`[^`]+`)/', (string) $text, -1, PREG_SPLIT_DELIM_CAPTURE );
	$out   = '';
	foreach ( $parts as $part ) {
		if ( preg_match( '/^\[([^\]]+)\]\(([^)]+)\)$/', $part, $m ) ) {
			$ext  = 0 === strpos( $m[2], 'http' );
			$out .= '<a href="' . esc_url( $ext ? $m[2] : vm_url( $m[2] ) ) . '"' . ( $ext ? ' target="_blank" rel="noreferrer"' : '' ) . ' class="vm:font-medium vm:text-blue vm:underline vm:decoration-blue/30 vm:underline-offset-4 vm:hover:decoration-blue">' . esc_html( $m[1] ) . '</a>';
		} elseif ( preg_match( '/^\*\*([^*]+)\*\*$/', $part, $m ) ) {
			$out .= '<strong class="vm:font-semibold vm:text-ink">' . esc_html( $m[1] ) . '</strong>';
		} elseif ( preg_match( '/^`([^`]+)`$/', $part, $m ) ) {
			$out .= '<code class="vm:rounded-md vm:bg-paper-deep vm:px-1.5 vm:py-0.5 vm:font-mono vm:text-[0.88em] vm:text-ink">' . esc_html( $m[1] ) . '</code>';
		} else {
			$out .= esc_html( $part );
		}
	}
	return $out;
}

/** 1200000 → 1.2M+, like the original formatInstalls(). */
function vm_format_installs( $n ) {
	$n = (float) $n;
	if ( $n >= 1000000 ) {
		return ( $n / 1000000 ) . 'M+';
	}
	if ( $n >= 1000 ) {
		return ( $n / 1000 ) . 'K+';
	}
	return 0.0 === $n ? 'New' : $n . '+';
}

/** WordPress.org rating 0–100 → "4.8". */
function vm_star_rating( $r ) {
	return number_format( $r / 20, 1 );
}

/** 2026-10-14 → 14 October 2026 (en-GB, like the original). */
function vm_fmt_date( $iso ) {
	$t = strtotime( $iso . ' 00:00:00 UTC' );
	return $t ? gmdate( 'j F Y', $t ) : $iso;
}

/** Same rules as the original slugify(). */
function vm_slugify( $s ) {
	$s = strtolower( preg_replace( '/[`*]/', '', (string) $s ) );
	$s = preg_replace( '/[^a-z0-9]+/', '-', $s );
	return trim( $s, '-' );
}

/** Classes joined, empties dropped. */
function vm_cx() {
	return implode( ' ', array_filter( array_map( 'trim', func_get_args() ) ) );
}
