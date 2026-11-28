<?php
/**
 * Rendering graphics + graphic shortcodes.
 *
 *   [vp_graphic name="geo-demo"]        any registered graphic (Appearance → VuloPilot lists them all)
 *   [vp_geo_demo] [vp_crawler_demo] …   one shortcode per graphic (dashes become underscores)
 *   [vp_shot name="geo" caption="yes"]  framed product screenshot that zooms on click
 *   [vp_ring value="76" label="Score" size="140"]   [vp_counter to="96" suffix="%"]   [vp_spark points="3,5,4,8,9"]
 *
 * Optional on every graphic: max_width="768" (centres it with that max width), class="my-class".
 *
 *   1. Registry and prerendered HTML
 *   2. vp_mount()          wrapper used by shortcodes and blocks
 *   3. vp_php_graphic()    PHP version of a graphic (no React)
 *   4. Shortcodes
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

/* -------------------------------------------------------------------------
 * 1. Registry and prerendered HTML
 * ---------------------------------------------------------------------- */

/** Registry written by the build (name => group, label, page, ssr). */
function vp_registry() {
	return vp_data( 'registry' );
}

/** Prerendered HTML for a registry entry (graphic/prerender/<name>.html), with asset URLs filled in. */
function vp_prerender_html( $name ) {
	$file = VP_GRAPHIC_DIR . '/prerender/' . sanitize_file_name( $name ) . '.html';
	if ( ! file_exists( $file ) ) {
		return '';
	}
	return str_replace( '__VP_ASSETS__', vp_assets_url(), file_get_contents( $file ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions
}

/* -------------------------------------------------------------------------
 * 2. vp_mount(): PHP version when there is one, else the React bundle
 * ---------------------------------------------------------------------- */

/**
 * Output for a graphic.
 *
 * @param string $name  Registry name (aliases are resolved).
 * @param array  $props Props for attribute-driven widgets.
 * @param array  $opt   tag, class, style.
 */
function vp_mount( $name, $props = array(), $opt = array() ) {
	$name  = vp_graphic_alias( $name );
	$tag   = $opt['tag'] ?? 'div';
	$style = ! empty( $opt['style'] ) ? ' style="' . esc_attr( $opt['style'] ) . '"' : '';

	// PHP graphic.
	$php = vp_php_graphic( $name, $props );
	if ( null !== $php ) {
		$class = trim( 'vp-scope vp-graphic vp-graphic-' . $name . ' ' . ( $opt['class'] ?? '' ) );
		return '<' . $tag . ' class="' . esc_attr( $class ) . '"' . $style . '>' . $php . '</' . $tag . '>';
	}

	// React graphic.
	$reg = vp_registry();
	if ( ! isset( $reg[ $name ] ) ) {
		return current_user_can( 'edit_posts' ) ? '<p style="color:#d8362b">VuloPilot: unknown graphic "' . esc_html( $name ) . '"</p>' : '';
	}
	wp_enqueue_script( 'vp-app' );
	$class = trim( 'vp-scope vp-mount vp-mount-' . $name . ' ' . ( $opt['class'] ?? '' ) );
	$attrs = sprintf( ' class="%s" data-vp-mount="%s"', esc_attr( $class ), esc_attr( $name ) );
	if ( $props ) {
		$attrs .= " data-vp-props='" . esc_attr( wp_json_encode( $props ) ) . "'";
	}
	$inner = $reg[ $name ]['ssr'] ? vp_prerender_html( $name ) : '';
	return '<' . $tag . $attrs . $style . '>' . $inner . '</' . $tag . '>';
}

/* -------------------------------------------------------------------------
 * 3. vp_php_graphic()
 * ---------------------------------------------------------------------- */

/** Theme-relative template path for a part: 'graphics/x' => graphic/parts/x, anything else => template-parts/…. */
function vp_graphic_template( $part ) {
	return 0 === strpos( $part, 'graphics/' )
		? 'graphic/parts/' . substr( $part, 9 )
		: 'template-parts/' . $part;
}

/**
 * PHP version of a graphic or section. Returns null for graphics that still use the React bundle.
 *
 * @param string $name  Registry name.
 * @param array  $props Widget props (ring, counter, spark).
 * @return string|null
 */
function vp_php_graphic( $name, $props = array() ) {
	// Home sections and shared pieces. graphic/graphics.php adds the files in graphic/parts/.
	$parts = apply_filters(
		'vp_graphic_parts',
		array(
			'home'           => 'page:home',
			'hero'           => 'home/hero',
			'problem'        => 'home/problem',
			'answers'        => 'home/answers',
			'how'            => 'home/how',
			'automation'     => 'home/automation',
			'ai'             => 'home/ai',
			'dashboard'      => 'home/dashboard',
			'product-tour'   => 'home/product-tour',
			'features'       => 'home/features',
			'wp-compare'     => 'home/wp-compare',
			'pricing'        => 'home/pricing',
			'blog-preview'   => 'home/blog-preview',
			'home-faq'       => 'home/faq',
			'start'          => 'home/start',
			'finale'         => 'home/finale',
			'cta-band'       => 'shared/cta-band',
			'plans'          => 'shared/plans',
			'compare-matrix' => 'shared/compare-matrix',
			'hero-visual'    => 'shared/hero-visual',
			'how-find'       => 'shared/how-find',
			'how-understand' => 'shared/how-understand',
			'how-prioritize' => 'shared/how-prioritize',
			'how-fix'        => 'shared/how-fix',
			'how-verify'     => 'shared/how-verify',
		)
	);

	// Screenshots: shot-<key> and shot-<key>-caption.
	if ( 0 === strpos( $name, 'shot-' ) ) {
		$key = preg_replace( '/^shot-|-caption$/', '', $name );
		return vp_shot( $key, '-caption' === substr( $name, -8 ) );
	}

	// Small widgets.
	switch ( $name ) {
		case 'ring':
			return vp_ring( $props['value'] ?? 76, $props['size'] ?? 120, $props['color'] ?? 'var(--sage)', $props['label'] ?? '' );
		case 'counter':
			$to = (float) ( $props['to'] ?? 100 );
			return '<span' . vp_m_attr( 'N:' . $to . ' t:' . (float) ( $props['duration'] ?? 1.6 ) ) . '>' . esc_html( $to ) . '</span>' . esc_html( $props['suffix'] ?? '' );
		case 'spark':
			$pts = array_values( array_map( 'floatval', (array) ( $props['points'] ?? array( 3, 5, 4, 8, 6, 9 ) ) ) );
			if ( count( $pts ) < 2 ) {
				return '';
			}
			$max = max( $pts );
			$min = min( $pts );
			$d   = '';
			foreach ( $pts as $i => $v ) {
				$d .= ( $i ? 'L' : 'M' ) . round( $i / ( count( $pts ) - 1 ) * 160, 2 ) . ',' . round( 48 - ( $v - $min ) / ( ( $max - $min ) ?: 1 ) * 40 - 4, 2 );
			}
			return '<svg viewBox="0 0 160 48" class="h-12 w-full" aria-hidden="true"><path d="' . esc_attr( $d ) . '" fill="none" stroke="' . esc_attr( $props['color'] ?? 'var(--sage)' ) . '" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"' . vp_m_attr( 'P:1 t:1.5 e:ease' ) . '></path></svg>';
	}

	if ( ! isset( $parts[ $name ] ) ) {
		return null;
	}

	// Template files.
	$part = $parts[ $name ];
	ob_start();
	if ( 'page:home' === $part ) {
		foreach ( array( 'hero', 'problem', 'answers', 'how', 'automation', 'ai', 'dashboard', 'product-tour', 'features', 'wp-compare', 'pricing', 'blog-preview', 'faq', 'start', 'finale' ) as $section ) {
			get_template_part( 'template-parts/home/' . $section );
		}
	} elseif ( is_array( $part ) ) {
		get_template_part( vp_graphic_template( $part[0] ), null, array_merge( (array) ( $part[1] ?? array() ), (array) $props ) );
	} else {
		get_template_part( vp_graphic_template( $part ), null, (array) $props );
	}
	return ob_get_clean();
}

/* -------------------------------------------------------------------------
 * 4. Shortcodes
 * ---------------------------------------------------------------------- */
add_action(
	'init',
	function () {
		// [vp_graphic name="…"]
		add_shortcode(
			'vp_graphic',
			function ( $a ) {
				$a = shortcode_atts( array( 'name' => '', 'max_width' => '', 'class' => '' ), $a );
				return vp_mount( sanitize_key( $a['name'] ), array(), vp_wrap_opts( $a ) );
			}
		);

		// [vp_<name>] for every prerendered graphic.
		foreach ( vp_registry() as $name => $e ) {
			if ( ! $e['ssr'] || 0 === strpos( $name, 'shot-' ) ) {
				continue;
			}
			add_shortcode(
				'vp_' . str_replace( '-', '_', $name ),
				function ( $a ) use ( $name ) {
					$a = shortcode_atts( array( 'max_width' => '', 'class' => '' ), $a );
					return vp_mount( $name, array(), vp_wrap_opts( $a ) );
				}
			);
		}

		// [vp_shot name="geo" caption="yes"]
		add_shortcode(
			'vp_shot',
			function ( $a ) {
				$a    = shortcode_atts( array( 'name' => 'overview', 'caption' => 'no', 'max_width' => '', 'class' => '' ), $a );
				$name = 'shot-' . sanitize_key( $a['name'] ) . ( in_array( strtolower( $a['caption'] ), array( 'yes', '1', 'true' ), true ) ? '-caption' : '' );
				return vp_mount( $name, array(), vp_wrap_opts( $a ) );
			}
		);

		// [vp_ring value="76" size="120" label="" color=""]
		add_shortcode(
			'vp_ring',
			function ( $a ) {
				$a = shortcode_atts( array( 'value' => 76, 'size' => 120, 'label' => '', 'color' => 'var(--sage)', 'class' => '' ), $a );
				$s = (int) $a['size'];
				return vp_mount( 'ring', array( 'value' => (float) $a['value'], 'size' => $s, 'label' => $a['label'], 'color' => $a['color'] ), array( 'class' => sanitize_html_class_list( $a['class'] ), 'style' => "display:inline-block;width:{$s}px;height:{$s}px" ) );
			}
		);

		// [vp_counter to="96" suffix="%" duration="1.6"]
		add_shortcode(
			'vp_counter',
			function ( $a ) {
				$a = shortcode_atts( array( 'to' => 100, 'suffix' => '', 'duration' => 1.6 ), $a );
				return vp_mount( 'counter', array( 'to' => (float) $a['to'], 'suffix' => $a['suffix'], 'duration' => (float) $a['duration'] ), array( 'tag' => 'span', 'style' => 'display:inline' ) );
			}
		);

		// [vp_spark points="3,5,4,8,6,9" color=""]
		add_shortcode(
			'vp_spark',
			function ( $a ) {
				$a   = shortcode_atts( array( 'points' => '3,5,4,8,6,9', 'color' => 'var(--sage)' ), $a );
				$pts = array_map( 'floatval', array_filter( array_map( 'trim', explode( ',', $a['points'] ) ), 'strlen' ) );
				return vp_mount( 'spark', array( 'points' => $pts, 'color' => $a['color'] ), array() );
			}
		);
	}
);
