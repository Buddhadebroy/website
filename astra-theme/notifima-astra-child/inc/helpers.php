<?php
/**
 * Small helpers used everywhere.
 *
 * @package Notifima
 */

defined( 'ABSPATH' ) || exit;

/** Version for a theme file: theme version + file time, so browsers reload it after every change. */
function nf_ver( $file ) {
	return NF_VERSION . '.' . filemtime( NF_DIR . '/' . $file );
}

/** A JSON file from /data as an array (cached per request). */
function nf_data( $name ) {
	static $cache = array();
	if ( ! isset( $cache[ $name ] ) ) {
		$base = NF_DIR . '/data/' . preg_replace( '/[^a-z0-9_-]/', '', strtolower( $name ) ); // not sanitize_file_name(): it turns "pages" into "unnamed-file.pages"
		$data = file_exists( $base . '.php' ) ? include $base . '.php' : null; // PHP copy: always survives theme uploads
		if ( ! is_array( $data ) && file_exists( $base . '.json' ) ) {
			$data = json_decode( (string) file_get_contents( $base . '.json' ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		}
		$cache[ $name ] = is_array( $data ) ? $data : array();
	}
	return $cache[ $name ];
}

/** Site-relative paths ("/pricing/", "/#how") become full URLs; external links and "#" stay as they are. */
function nf_url( $href ) {
	if ( 0 === strpos( (string) $href, '/' ) && 0 !== strpos( (string) $href, '//' ) ) {
		return home_url( $href );
	}
	return (string) $href;
}

/** A link from Customize → Notifima → General (contact, demo, docs, support …). */
function nf_link( $key ) {
	return nf_url( (string) nf_opt( 'nf_url_' . $key ) );
}

/** Inline SVG icon from the sprite (assets/img/icons.svg is printed once per page). */
function nf_icon( $name ) {
	return '<svg class="i" aria-hidden="true"><use href="#' . esc_attr( $name ) . '"/></svg>';
}

/** Rough reading time of a post. */
function nf_read_time( $post = null ) {
	$words = str_word_count( wp_strip_all_tags( (string) get_post_field( 'post_content', $post ) ) );
	return max( 1, (int) round( $words / 220 ) ) . ' min';
}

/* ---------------------------------------------------------------------------
 * Link tokens. Built pages keep "#nf-KEY" links so a link set later in
 * Customize → Notifima → General shows everywhere without rebuilding pages.
 *   #nf-docs, #nf-contact … → nf_link( KEY )
 *   #nf-post-N              → the post with the card's title, else the sample post, else the Blog page
 *   #nf-pg-1/2/next         → Blog pager (hidden while there is no second page)
 * ------------------------------------------------------------------------ */

/** Permalink of a published post titled $title (card text), else the sample post, else the Blog page. */
function nf_card_url( $title ) {
	static $cache = array();
	$key = strtolower( trim( wp_strip_all_tags( html_entity_decode( (string) $title, ENT_QUOTES ) ) ) );
	if ( isset( $cache[ $key ] ) ) {
		return $cache[ $key ];
	}
	$id = 0;
	if ( '' !== $key ) {
		$q  = get_posts( array( 'post_type' => 'post', 'post_status' => 'publish', 'title' => $title, 'numberposts' => 1, 'fields' => 'ids' ) );
		$id = $q ? (int) $q[0] : 0;
	}
	if ( ! $id ) {
		$q  = get_posts( array( 'post_type' => 'post', 'post_status' => 'publish', 'meta_key' => '_nf_sample', 'meta_value' => '1', 'numberposts' => 1, 'fields' => 'ids' ) ); // phpcs:ignore
		$id = $q ? (int) $q[0] : 0;
	}
	$cache[ $key ] = $id ? get_permalink( $id ) : home_url( '/blog/' );
	return $cache[ $key ];
}

/** Second Blog page, or '' when there is none. */
function nf_blog_page2() {
	$posts_page = (int) get_option( 'page_for_posts' );
	$count      = (int) wp_count_posts()->publish;
	return ( $posts_page && $count > (int) get_option( 'posts_per_page', 10 ) ) ? add_query_arg( 'paged', 2, get_permalink( $posts_page ) ) : '';
}

/** Replace the link tokens in rendered HTML. */
function nf_resolve_links( $html ) {
	if ( false === strpos( (string) $html, '#nf-' ) ) {
		return $html;
	}
	// Blog pager: drop it when there is no page 2.
	if ( false !== strpos( $html, '#nf-pg-' ) && '' === nf_blog_page2() ) {
		$html = preg_replace( '#<(div|nav)\b[^>]*\bpager\b[^>]*>.*?</\1>#s', '', $html );
	}
	// Blog cards: link to the post with the same title.
	$html = preg_replace_callback(
		'#href="\#nf-post-\d+"(.*?)<h3[^>]*>(.*?)</h3>#s',
		function ( $m ) {
			return 'href="' . esc_url( nf_card_url( $m[2] ) ) . '"' . $m[1] . '<h3>' . $m[2] . '</h3>';
		},
		$html
	);
	return preg_replace_callback(
		'/(href=["\'])\#nf-([a-z0-9_-]+)(["\'])/',
		function ( $m ) {
			$k = $m[2];
			if ( 'latest' === $k ) {
				$u = nf_latest_post_field( 'url' );
			} elseif ( 0 === strpos( $k, 'post-' ) ) {
				$u = home_url( '/blog/' );
			} elseif ( 'pg-1' === $k ) {
				$u = home_url( '/blog/' );
			} elseif ( 0 === strpos( $k, 'pg-' ) ) {
				$u = nf_blog_page2();
			} else {
				$u = nf_link( $k );
			}
			return $m[1] . esc_url( '' !== $u ? $u : '#' ) . $m[3];
		},
		$html
	);
}
add_filter( 'render_block', 'nf_resolve_links', 20 );

/** The newest published post: title, short (first words), read (reading time), url. Falls back to data/menu.json → feature. */
function nf_latest_post_field( $field ) {
	static $p = false;
	if ( false === $p ) {
		$q = get_posts( array( 'numberposts' => 1, 'post_status' => 'publish' ) );
		$p = $q ? $q[0] : null;
	}
	$f = function_exists( 'nf_data' ) ? ( nf_data( 'menu' )['feature'] ?? array() ) : array();
	switch ( $field ) {
		case 'title':
			return $p ? get_the_title( $p ) : ( $f['title'] ?? '' );
		case 'short':
			return $p ? wp_trim_words( get_the_title( $p ), 5 ) : ( $f['row1'] ?? '' );
		case 'read':
			/* translators: %s: reading time */
			return $p ? sprintf( __( '%s read', 'notifima' ), nf_read_time( $p ) ) : ( $f['row2'] ?? '' );
		case 'url':
			return $p ? get_permalink( $p ) : home_url( '/blog/' );
	}
	return '';
}

/** [nf_latest field="title|short|read"] — used by the mega menu pattern. */
add_shortcode(
	'nf_latest',
	function ( $atts ) {
		$atts = shortcode_atts( array( 'field' => 'title' ), $atts );
		return esc_html( nf_latest_post_field( $atts['field'] ) );
	}
);

/** Contact Us page: a form plugin's shortcode (Customize → Notifima → General) in place of the built-in email form. */
add_filter(
	'render_block',
	function ( $html, $block ) {
		if ( 'notifima/graphic' !== ( $block['blockName'] ?? '' ) || false === strpos( (string) $html, 'cform' ) ) {
			return $html;
		}
		$code = trim( (string) nf_opt( 'nf_contact_form' ) );
		if ( '' === $code ) {
			return $html;
		}
		return preg_replace( '#<form\b[^>]*\bcform\b.*?</form>#s', '<div class="card cform cform-plugin">' . str_replace( '$', '\\$', do_shortcode( $code ) ) . '</div>', $html, 1 );
	},
	10,
	2
);
