<?php
/**
 * Small helpers used by the PHP section files in /template-parts.
 *
 * Animation attributes are read by assets/js/vp-motion.js. Nothing here needs React.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

/**
 * Print animation attributes for an element.
 *
 * The first argument lists where the element starts and how it moves, separated by spaces:
 *   o:0      start opacity            x:-8 / y:24   start offset in px
 *   s:.94    start scale              r:-90         start rotation in degrees
 *   W:45%    grow width from 0 to 45% H:30px        grow height from 0 to 30px
 *   P:1      draw an SVG line         N:78          count a number up to 78
 *   d:.2     delay in seconds         t:.8          duration in seconds
 *   e:spring ease: out (default), spring, linear, inout
 *   on:load  when to play: view (default, when scrolled into view) or load (on page load)
 *
 * Example: <div <?php vp_m( 'o:0 y:24 d:.1' ); ?> class="…">
 *
 * @param string $spec  Animation spec (see above).
 * @param string $style Extra inline CSS for the same element.
 * @param bool   $echo  Echo (default) or return.
 * @return string
 */
function vp_m( $spec = 'o:0 y:24', $style = '', $echo = true ) {
	$css   = array();
	$data  = array();
	$on    = 'view';
	$eases = array(
		'out'    => 'var(--vp-ease)',
		'spring' => 'var(--vp-spring2)',
		'linear' => 'linear',
		'inout'  => 'cubic-bezier(.65,0,.35,1)',
		'ease'   => 'ease-out',
	);
	foreach ( preg_split( '/\s+/', trim( $spec ) ) as $pair ) {
		if ( false === strpos( $pair, ':' ) ) {
			continue;
		}
		list( $k, $v ) = explode( ':', $pair, 2 );
		switch ( $k ) {
			case 'o':
				$css[] = '--fo:' . (float) $v;
				break;
			case 'x':
			case 'y':
				$css[] = '--f' . $k . ':' . (float) $v . 'px';
				break;
			case 's':
				$css[] = '--fs:' . (float) $v;
				break;
			case 'r':
				$css[] = '--fr:' . (float) $v . 'deg';
				break;
			case 'd':
				$css[] = '--md:' . (float) $v . 's';
				break;
			case 't':
				$css[] = '--mt:' . (float) $v . 's';
				break;
			case 'e':
				$css[] = '--me:' . ( $eases[ $v ] ?? $eases['out'] );
				if ( 'spring' === $v && false === strpos( $spec, 't:' ) ) {
					$css[] = '--mt:.6s';
				}
				break;
			case 'W':
				$css[]          = 'width:0';
				$data['m-w']    = $v;
				break;
			case 'H':
				$css[]          = 'height:0';
				$data['m-h']    = $v;
				break;
			case 'P':
				$data['m-path'] = '1';
				break;
			case 'N':
				$data['m-n'] = $v;
				break;
			case 'on':
				$on = sanitize_key( $v );
				break;
		}
	}
	$out = ' data-m="' . esc_attr( $on ) . '"';
	foreach ( $data as $k => $v ) {
		$out .= ' data-' . $k . '="' . esc_attr( $v ) . '"';
	}
	$all = trim( implode( ';', $css ) . ( $css && $style ? ';' : '' ) . $style );
	if ( '' !== $all ) {
		$out .= ' style="' . esc_attr( $all ) . '"';
	}
	if ( $echo ) {
		echo $out; // phpcs:ignore WordPress.Security.EscapeOutput
	}
	return $out;
}

/** Return version of vp_m() for use inside strings. */
function vp_m_attr( $spec = 'o:0 y:24', $style = '' ) {
	return vp_m( $spec, $style, false );
}

/** "Fade up when scrolled into view" wrapper attributes (the site's standard section reveal). */
function vp_reveal( $delay = 0, $style = '' ) {
	vp_m( 'o:0 y:24 t:.8' . ( $delay ? ' d:' . $delay : '' ), $style );
}

/** Tinted background: color-mix of a colour with white. */
function vp_tint( $color, $pct = 14 ) {
	return 'color-mix(in srgb, ' . $color . ' ' . (int) $pct . '%, white)';
}

/** Badge + colour style used by icon tiles: background tint and text colour. */
function vp_tint_style( $color, $pct = 14 ) {
	return 'background:' . vp_tint( $color, $pct ) . ';color:' . $color;
}

/** Echo an icon (shortcut for echo vp_icon()). */
function vp_i( $name, $size = 16, $class = '', $stroke = 2 ) {
	echo vp_icon( $name, $size, $class, $stroke ); // phpcs:ignore WordPress.Security.EscapeOutput
}

/** Icon with inline style (e.g. a colour): vp_icon_style( 'gauge', 14, 'color:var(--rose)' ). */
function vp_icon_style( $name, $size, $style, $class = '', $stroke = 2 ) {
	return str_replace( '<svg ', '<svg style="' . esc_attr( $style ) . '" ', vp_icon( $name, $size, $class, $stroke ) );
}

/** Small eyebrow label above section headings. */
function vp_eyebrow( $text ) {
	echo '<div class="inline-flex items-center gap-2 rounded-sm border border-line bg-surface/70 px-3 py-1 font-mono text-[11px] uppercase tracking-[0.14em] text-sage"><span class="h-1.5 w-1.5 rounded-full bg-apricot"></span>' . esc_html( $text ) . '</div>';
}

/**
 * Opening markup of a browser-window frame. Close it with echo '</div>'.
 *
 * @param string $url   Text in the address bar.
 * @param string $size  'lg' (hero / screenshots) or 'sm' (how-it-works graphics).
 * @param string $class Extra classes.
 */
function vp_frame_open( $url, $size = 'lg', $class = '' ) {
	if ( 'sm' === $size ) {
		echo '<div class="overflow-hidden rounded-sm border border-line bg-surface shadow-[0_24px_60px_-34px_rgba(0,41,145,.45)] ' . esc_attr( $class ) . '">'
			. '<div class="flex items-center gap-1.5 border-b border-line bg-bg/70 px-4 py-2.5"><span class="h-2 w-2 rounded-full bg-rose/70"></span><span class="h-2 w-2 rounded-full bg-flare/60"></span><span class="h-2 w-2 rounded-full bg-sage/70"></span><span class="ml-2 truncate font-mono text-[10px] text-muted">' . esc_html( $url ) . '</span></div>';
		return;
	}
	echo '<div class="overflow-hidden rounded-sm border border-line bg-surface shadow-[0_30px_70px_-36px_rgba(0,41,145,.5)] ' . esc_attr( $class ) . '">'
		. '<div class="flex items-center gap-1.5 border-b border-line bg-bg/80 px-4 py-2.5"><span class="h-2.5 w-2.5 rounded-full bg-rose/70"></span><span class="h-2.5 w-2.5 rounded-full bg-flare/60"></span><span class="h-2.5 w-2.5 rounded-full bg-sage/70"></span><span class="ml-3 truncate rounded-sm bg-surface px-3 py-0.5 font-mono text-[10px] text-muted ring-1 ring-line">' . esc_html( $url ) . '</span></div>';
}

/**
 * Animated score ring (counts up and draws when scrolled into view).
 *
 * @param int    $value  0–100.
 * @param int    $size   Pixel size.
 * @param string $color  CSS colour.
 * @param string $label  Optional small label under the number.
 * @param string $attrs  Extra attributes for the wrapper.
 * @return string
 */
function vp_ring( $value, $size = 120, $color = 'var(--sage)', $label = '', $attrs = '' ) {
	$value = max( 0, min( 100, (float) $value ) );
	$r     = $size / 2 - 8;
	$c     = $size / 2;
	$html  = '<div class="vp-ring relative" style="width:' . (int) $size . 'px;height:' . (int) $size . 'px"' . $attrs . '>';
	$html .= '<svg width="' . (int) $size . '" height="' . (int) $size . '" class="-rotate-90" aria-hidden="true">';
	$html .= '<circle cx="' . $c . '" cy="' . $c . '" r="' . $r . '" fill="none" stroke="var(--line)" stroke-width="8"></circle>';
	$html .= '<circle class="vp-ring-arc" cx="' . $c . '" cy="' . $c . '" r="' . $r . '" fill="none" stroke="' . esc_attr( $color ) . '" stroke-width="8" stroke-linecap="round" pathLength="100" stroke-dasharray="100" data-value="' . esc_attr( $value ) . '" style="stroke-dashoffset:' . ( 100 - $value ) . '"></circle>';
	$html .= '</svg><div class="absolute inset-0 grid place-items-center text-center"><div>';
	$html .= '<div class="font-display text-3xl leading-none"><span class="vp-ring-num" data-n="' . esc_attr( round( $value ) ) . '">' . esc_html( round( $value ) ) . '</span></div>';
	if ( $label ) {
		$html .= '<div class="mt-1 text-[10px] uppercase tracking-widest text-muted">' . esc_html( $label ) . '</div>';
	}
	$html .= '</div></div></div>';
	return $html;
}

/**
 * Framed product screenshot that opens a lightbox on click.
 *
 * @param string $name    Key from vp_screens().
 * @param bool   $caption Show "Title: where" under it.
 * @param string $class   Classes for the <figure>.
 * @param bool   $eager   Load eagerly.
 * @return string
 */
function vp_shot( $name, $caption = false, $class = '', $eager = false ) {
	$s = vp_screens()[ $name ] ?? null;
	if ( ! $s ) {
		return '';
	}
	ob_start();
	echo '<figure class="' . esc_attr( $class ) . '">';
	vp_frame_open( 'wp-admin / VuloPilot / ' . $s['where'] );
	echo '<button type="button" class="group relative block w-full cursor-zoom-in text-left" data-vp-lightbox aria-label="' . esc_attr( 'Enlarge screenshot: ' . $s['title'] ) . '">';
	echo '<img src="' . esc_url( $s['src'] ) . '" width="' . (int) $s['w'] . '" height="' . (int) $s['h'] . '" alt="' . esc_attr( $s['alt'] ) . '" loading="' . ( $eager ? 'eager' : 'lazy' ) . '" decoding="async" class="block h-auto w-full" />';
	echo '<span class="absolute right-3 top-3 grid h-8 w-8 place-items-center rounded-full bg-ink/80 text-bg opacity-0 backdrop-blur transition group-hover:opacity-100">' . vp_icon( 'maximize-2', 14 ) . '</span>'; // phpcs:ignore
	echo '</button></div>';
	if ( $caption ) {
		echo '<figcaption class="mt-3 text-center text-sm text-muted">' . esc_html( $s['title'] . ': ' . $s['where'] ) . '</figcaption>';
	}
	echo '</figure>';
	return ob_get_clean();
}

/** Load a section file: template-parts/<page>/<name>.php. Child-theme overridable via get_template_part(). */
function vp_section( $page, $name, $args = array() ) {
	get_template_part( 'template-parts/' . $page . '/' . $name, null, $args );
}

/** Print JSON-LD. */
function vp_jsonld( $graph ) {
	$data = array(
		'@context' => 'https://schema.org',
		'@graph'   => isset( $graph['@type'] ) ? array( $graph ) : $graph,
	);
	echo '<script type="application/ld+json">' . str_replace( '<', '<', wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) ) . '</script>'; // phpcs:ignore
}

/**
 * FAQ accordion (first item open) with FAQPage schema.
 *
 * @param array $items List of [question, answer].
 * @param int   $open  Index open on load, or -1.
 */
function vp_faq( $items, $open = 0 ) {
	vp_jsonld(
		array(
			'@type'      => 'FAQPage',
			'mainEntity' => array_map(
				function ( $qa ) {
					return array(
						'@type'          => 'Question',
						'name'           => $qa[0],
						'acceptedAnswer' => array( '@type' => 'Answer', 'text' => wp_strip_all_tags( $qa[1] ) ),
					);
				},
				$items
			),
		)
	);
	echo '<div class="divide-y divide-line rounded-sm border border-line bg-surface" data-vp-accordion>';
	foreach ( $items as $i => $qa ) {
		$is = $i === $open;
		echo '<div><h3 class="text-base"><button type="button" aria-expanded="' . ( $is ? 'true' : 'false' ) . '" class="flex w-full items-center justify-between gap-4 px-6 py-5 text-left font-medium" data-vp-acc-btn>' . esc_html( $qa[0] ) . vp_icon( 'plus', 18, 'shrink-0 transition' . ( $is ? ' rotate-45' : '' ) ) . '</button></h3>'; // phpcs:ignore
		echo '<div class="overflow-hidden" data-vp-acc-body' . ( $is ? '' : ' style="height:0;opacity:0" hidden' ) . '><p class="px-6 pb-5 text-muted">' . wp_kses_post( $qa[1] ) . '</p></div></div>';
	}
	echo '</div>';
}

/**
 * Section with eyebrow + heading (the inner-page "Section" layout).
 *
 * @param string   $eyebrow Eyebrow text (optional).
 * @param string   $title   Heading HTML.
 * @param callable $body    Prints the body.
 * @param bool     $wide    max-w-6xl instead of max-w-5xl.
 */
function vp_titled_section( $eyebrow, $title, $body, $wide = false ) {
	echo '<section class="px-5 py-10"><div class="mx-auto ' . ( $wide ? 'max-w-6xl' : 'max-w-5xl' ) . '"><div' . vp_m_attr( 'o:0 y:24 t:.8' ) . '>';
	if ( $eyebrow ) {
		vp_eyebrow( $eyebrow );
	}
	echo '<h2 class="mt-4 font-display text-3xl leading-tight sm:text-4xl">' . wp_kses_post( $title ) . '</h2></div><div class="mt-8">';
	call_user_func( $body );
	echo '</div></div></section>';
}

/**
 * Line chart used by the dashboard demo (the script rebuilds it the same way when a tab changes).
 *
 * @param int[]  $pts Values 20–100.
 * @param string $key Unique id for the gradient.
 * @return string
 */
function vp_dash_chart( $pts, $key ) {
	$key = preg_replace( '/[^A-Za-z0-9]/', '', $key );
	$n   = count( $pts );
	$xy  = array();
	foreach ( array_values( $pts ) as $i => $v ) {
		$xy[] = array( $n > 1 ? $i / ( $n - 1 ) * 520 : 0, 150 - ( $v - 20 ) / 80 * 134 - 8 );
	}
	$d = '';
	foreach ( $xy as $i => $p ) {
		$d .= ( $i ? 'L' : 'M' ) . number_format( $p[0], 1, '.', '' ) . ',' . number_format( $p[1], 1, '.', '' ) . ' ';
	}
	$d    = trim( $d );
	$html = '<svg viewBox="0 0 520 150" class="h-40 w-full" preserveAspectRatio="none" aria-hidden="true"><defs><linearGradient id="g-' . $key . '" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="var(--sage)" stop-opacity=".28"></stop><stop offset="1" stop-color="var(--sage)" stop-opacity="0"></stop></linearGradient></defs>';
	for ( $l = 0; $l < 4; $l++ ) {
		$y     = 150 / 4 * $l + 8;
		$html .= '<line x1="0" x2="520" y1="' . $y . '" y2="' . $y . '" stroke="var(--line)" stroke-dasharray="3 5"></line>';
	}
	$html .= '<path d="' . $d . ' L520,150 L0,150 Z" fill="url(#g-' . $key . ')"' . vp_m_attr( 'o:0 d:.5 t:.8' ) . '></path>';
	$html .= '<path d="' . $d . '" fill="none" stroke="var(--sage)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"' . vp_m_attr( 'P:1 t:1.2 e:ease' ) . '></path>';
	foreach ( $xy as $i => $p ) {
		$html .= '<circle cx="' . $p[0] . '" cy="' . $p[1] . '" r="' . ( $i === $n - 1 ? 5 : 3 ) . '" fill="var(--surface)" stroke="var(--sage)" stroke-width="2"' . vp_m_attr( 's:0 d:' . ( 0.3 + $i * 0.1 ) ) . '></circle>';
	}
	return $html . '</svg>';
}
