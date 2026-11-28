<?php
/**
 * Page graphics kit: helpers for the scenes.
 *
 * The 148 per-page graphics for solutions, use cases, problems and "for" pages
 * (graphic/parts/<page>-1 … -4) each hold their text and numbers in an array at the top,
 * then call one of the 40 scenes in graphic/parts/scenes/ through vpx_scene().
 *
 * Slot 1 is the hero graphic: it never repeats the page H1 and stretches to the height of
 * the column it sits in (class vpx-hero).
 *
 *   vpx_open() / vpx_close()   card header and footer shared by every scene
 *   vpx_col()                  colour for a state word (good, warn, bad …)
 *   vpx_data()                 page defaults + text passed in by a block or shortcode
 *   vpx_scene()                print one scene
 *   vpx_i() / vpx_path()       icon, and an SVG line path for a list of numbers
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

/** Card header shared by every scene. */
function vpx_open( $d, $icon = 'sparkles', $extra = '' ) {
	$hero = ! empty( $d['_hero'] );
	echo '<div class="card vpx' . ( $hero ? ' vpx-hero' : '' ) . ( $extra ? ' ' . esc_attr( $extra ) : '' ) . '"' . ( ! empty( $d['_loop'] ) ? ' data-vpg="loop" data-every="' . (int) $d['_loop'] . '"' : '' ) . '>';
	echo '<div class="vpx-head"><div class="vpx-title"><span class="vpx-ic">';
	vp_i( vp_safe_icon( $d['icon'] ?? $icon, $icon ), 15 );
	echo '</span>' . esc_html( $d['label'] ?? '' ) . '</div>';
	if ( ! empty( $d['path'] ) ) {
		echo '<span class="vpx-path">' . esc_html( $d['path'] ) . '</span>';
	}
	echo '</div><div class="vpx-body">';
}

/** Close a scene card, with an optional footer line. */
function vpx_close( $d ) {
	echo '</div>';
	if ( ! empty( $d['foot'] ) ) {
		echo '<div class="vpx-foot"><span class="vpx-foot-ic">';
		vp_i( vp_safe_icon( $d['foot_icon'] ?? 'sparkles' ), 13 );
		echo '</span><span>' . esc_html( $d['foot'] ) . '</span></div>';
	}
	echo '</div>';
}

/** Colour for a state word. */
function vpx_col( $state ) {
	$map = array(
		'good' => 'var(--sage)', 'ok' => 'var(--sage)', 'on' => 'var(--sage)', 'clean' => 'var(--sage)', '200' => 'var(--sage)',
		'warn' => 'var(--lilac)', 'medium' => 'var(--lilac)', 'slow' => 'var(--lilac)', 'changed' => 'var(--lilac)', '301' => 'var(--lilac)', 'outdated' => 'var(--lilac)',
		'bad' => 'var(--rose)', 'high' => 'var(--rose)', 'critical' => 'var(--rose)', 'down' => 'var(--rose)', 'flag' => 'var(--rose)', '404' => 'var(--rose)', 'vuln' => 'var(--rose)', 'noindex' => 'var(--rose)', 'off' => 'var(--rose)',
		'low' => 'var(--muted)', 'info' => 'var(--muted)',
	);
	$k = strtolower( (string) $state );
	return $map[ $k ] ?? 'var(--sage)';
}

/** Merge a page file's defaults with text passed in by a block or shortcode. */
function vpx_data( $defaults, $args, $hero = false ) {
	$args = is_array( $args ) ? $args : array();
	$d    = array_merge( $defaults, (array) ( $args['text'] ?? array() ), (array) ( $args['data'] ?? array() ) );
	if ( $hero ) {
		$d['_hero'] = true;
	}
	return $d;
}

/** Print one scene. */
function vpx_scene( $scene, $d ) {
	get_template_part( 'graphic/parts/scenes/' . sanitize_file_name( $scene ), null, array( 'd' => $d ) );
}

/** Icon from the shipped set (falls back to sparkles for an unknown name). */
function vpx_i( $name, $size = 14 ) {
	vp_i( vp_safe_icon( $name ), $size );
}

/** SVG path for a list of points, scaled into a w x h box. */
function vpx_path( $pts, $w = 300, $h = 90, $pad = 6 ) {
	$pts = array_values( array_map( 'floatval', (array) $pts ) );
	$n   = count( $pts );
	if ( $n < 2 ) {
		return '';
	}
	$max = max( $pts );
	$min = min( $pts );
	$d   = '';
	foreach ( $pts as $i => $v ) {
		$x  = round( $i / ( $n - 1 ) * $w, 2 );
		$y  = round( $h - $pad - ( $v - $min ) / ( ( $max - $min ) ?: 1 ) * ( $h - 2 * $pad ), 2 );
		$d .= ( $i ? 'L' : 'M' ) . $x . ',' . $y;
	}
	return $d;
}
