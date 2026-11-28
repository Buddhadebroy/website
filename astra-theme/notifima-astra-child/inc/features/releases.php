<?php
/**
 * Release Notes that update themselves.
 *
 * Sources (Customize → Notifima → General):
 *   - WordPress.org: the "Changelog" of the plugin's readme.txt (slug, default woocommerce-product-stock-alert),
 *     read from the WordPress.org plugins API.
 *   - GitHub (optional, owner/repo): the repository's Releases. Each release body is read line by line
 *     ("- New - …", "* Fixed: …"). A version found in both uses the GitHub notes.
 * The list is cached for an hour, so a new release shows up within the hour. To show it at once:
 *   - Appearance → Notifima → "Refresh release notes now", or
 *   - a GitHub webhook (repository → Settings → Webhooks): Payload URL = the "Webhook URL" shown on
 *     Appearance → Notifima, Content type = application/json, Secret = the secret shown there,
 *     events = Releases and Pushes.
 * If no source answers, the last good list is kept; on a new site the list from data/releases.php is shown.
 *
 * Output: the block "Release Notes (Notifima)" (blocks/release-notes) and the shortcodes
 * [nf_release_notes] and [nf_version] (the latest version number).
 *
 * @package Notifima
 */

defined( 'ABSPATH' ) || exit;

const NF_REL_CACHE = 'nf_releases_v2';

/** Labels of the change types, in filter order. */
function nf_rel_types() {
	return array(
		'new'     => __( 'New', 'notifima' ),
		'fixed'   => __( 'Fixed', 'notifima' ),
		'update'  => __( 'Update', 'notifima' ),
		'add'     => __( 'Add', 'notifima' ),
		'tweak'   => __( 'Tweak', 'notifima' ),
		'dev'     => __( 'Dev', 'notifima' ),
		'removed' => __( 'Removed', 'notifima' ),
	);
}

/** "Added - Something" / "Fix: something" / "[New] something" → [ type, text ]. */
function nf_rel_item( $line ) {
	$line = trim( wp_strip_all_tags( html_entity_decode( $line, ENT_QUOTES, 'UTF-8' ) ) );
	$line = preg_replace( '/^[\-\*\x{2022}]\s*/u', '', $line );
	$map  = array(
		'new' => 'new', 'feature' => 'new', 'features' => 'new', 'compatibility' => 'new',
		'add' => 'add', 'added' => 'new', 'adds' => 'new', 'enhanced' => 'update', 'enhance' => 'update', 'optimized' => 'update', 'security' => 'fixed',
		'update' => 'update', 'updated' => 'update', 'updates' => 'update', 'improve' => 'update', 'improved' => 'update', 'improvement' => 'update', 'enhancement' => 'update', 'change' => 'update', 'changed' => 'update',
		'tweak' => 'tweak', 'tweaked' => 'tweak',
		'fix' => 'fixed', 'fixed' => 'fixed', 'fixes' => 'fixed', 'bugfix' => 'fixed', 'bug' => 'fixed',
		'dev' => 'dev', 'developer' => 'dev',
		'remove' => 'removed', 'removed' => 'removed', 'deprecated' => 'removed',
	);
	if ( preg_match( '/^\[?([A-Za-z]+)\]?\s*(?:-|–|—|:|\])\s*(.+)$/u', $line, $m ) && isset( $map[ strtolower( $m[1] ) ] ) ) {
		return array( $map[ strtolower( $m[1] ) ], trim( $m[2] ) );
	}
	if ( preg_match( '/^(compatib|support for|works with)/i', $line ) ) {
		return array( 'new', $line );
	}
	return array( preg_match( '/\bfix/i', $line ) ? 'fixed' : 'update', $line );
}

/** "2026-11-03", "03/11/2026", "3rd Nov 2026" … → timestamp (0 if none). */
function nf_rel_time( $s ) {
	$s = trim( preg_replace( '/(\d)(st|nd|rd|th)\b/i', '$1', (string) $s ) );
	$s = trim( preg_replace( '/\s*,\s*/', ' ', $s ) ); // "16 July, 2025" would read 2025 as a time
	if ( '' === $s ) {
		return 0;
	}
	if ( preg_match( '#^(\d{1,2})[/.](\d{1,2})[/.](\d{4})$#', $s, $m ) ) { // day/month/year
		$s = $m[3] . '-' . $m[2] . '-' . $m[1];
	}
	$t = strtotime( $s );
	return $t ? $t : 0;
}

/** WordPress.org changelog → releases. */
function nf_rel_from_wporg( $slug ) {
	$url = add_query_arg(
		array(
			'action'                    => 'plugin_information',
			'request[slug]'             => $slug,
			'request[fields][sections]' => 1,
		),
		'https://api.wordpress.org/plugins/info/1.2/'
	);
	$res = wp_remote_get( $url, array( 'timeout' => 12 ) );
	if ( is_wp_error( $res ) || 200 !== wp_remote_retrieve_response_code( $res ) ) {
		return null;
	}
	$d = json_decode( wp_remote_retrieve_body( $res ), true );
	if ( empty( $d['sections']['changelog'] ) ) {
		return null;
	}
	$out   = array();
	$parts = preg_split( '#<h4>#i', $d['sections']['changelog'] );
	foreach ( $parts as $part ) {
		if ( ! preg_match( '#^(.*?)</h4>(.*)$#is', $part, $m ) ) {
			continue;
		}
		$head = trim( html_entity_decode( wp_strip_all_tags( $m[1] ), ENT_QUOTES, 'UTF-8' ) );
		if ( ! preg_match( '/^\s*v?(?:ersion\s*)?(\d+(?:\.\d+)+)\s*(?:[-–—(:,]\s*([^)]*))?/iu', $head, $h ) ) {
			continue;
		}
		$items = array();
		if ( preg_match_all( '#<li>(.*?)</li>#is', $m[2], $li ) ) {
			foreach ( $li[1] as $l ) {
				$items[] = nf_rel_item( $l );
			}
		} else {
			foreach ( preg_split( '/<br\s*\/?>|\n/', $m[2] ) as $l ) {
				if ( trim( wp_strip_all_tags( $l ) ) ) {
					$items[] = nf_rel_item( $l );
				}
			}
		}
		$out[ $h[1] ] = array( 'version' => $h[1], 'time' => nf_rel_time( isset( $h[2] ) ? $h[2] : '' ), 'items' => $items );
	}
	return $out;
}

/** GitHub Releases → releases. */
function nf_rel_from_github( $repo ) {
	$res = wp_remote_get(
		'https://api.github.com/repos/' . $repo . '/releases?per_page=100',
		array( 'timeout' => 12, 'headers' => array( 'Accept' => 'application/vnd.github+json', 'User-Agent' => 'notifima-theme' ) )
	);
	if ( is_wp_error( $res ) || 200 !== wp_remote_retrieve_response_code( $res ) ) {
		return null;
	}
	$out = array();
	foreach ( (array) json_decode( wp_remote_retrieve_body( $res ), true ) as $r ) {
		if ( ! empty( $r['draft'] ) || ! empty( $r['prerelease'] ) || ! preg_match( '/(\d+(?:\.\d+)+)/', (string) $r['tag_name'], $v ) ) {
			continue;
		}
		$items = array();
		foreach ( preg_split( '/\r?\n/', (string) $r['body'] ) as $l ) {
			if ( preg_match( '/^\s*[\-\*]\s+\S/', $l ) ) {
				$items[] = nf_rel_item( $l );
			}
		}
		$out[ $v[1] ] = array( 'version' => $v[1], 'time' => nf_rel_time( substr( (string) $r['published_at'], 0, 10 ) ), 'items' => $items );
	}
	return $out;
}

/** Fetch from the sources now. Null when none answered. */
function nf_rel_fetch() {
	$slug = trim( (string) nf_opt( 'nf_rel_wporg' ) );
	$repo = trim( (string) nf_opt( 'nf_rel_github' ), " /\t" );
	$repo = preg_replace( '#^https?://github\.com/#', '', $repo );
	$all  = null;
	if ( $slug ) {
		$all = nf_rel_from_wporg( $slug );
	}
	if ( $repo && preg_match( '#^[\w.-]+/[\w.-]+$#', $repo ) ) {
		$gh = nf_rel_from_github( $repo );
		if ( null !== $gh ) {
			$all = array_merge( (array) $all, array_filter( $gh, function ( $r ) { return ! empty( $r['items'] ); } ) );
		}
	}
	if ( ! $all ) {
		return null;
	}
	uksort( $all, function ( $a, $b ) { return version_compare( $b, $a ); } );
	return array_values( $all );
}

/** The releases, newest first (cached for an hour). */
function nf_releases( $refresh = false ) {
	$list = $refresh ? false : get_transient( NF_REL_CACHE );
	if ( false === $list ) {
		$list = nf_rel_fetch();
		if ( $list ) {
			update_option( 'nf_releases_last', $list, false );
			update_option( 'nf_releases_checked', time(), false );
		} else {
			$list = get_option( 'nf_releases_last' );
			if ( ! $list && is_readable( NF_DIR . '/data/releases.php' ) ) {
				$list = include NF_DIR . '/data/releases.php';
			}
		}
		set_transient( NF_REL_CACHE, (array) $list, $list ? HOUR_IN_SECONDS : 10 * MINUTE_IN_SECONDS );
	}
	return (array) $list;
}

function nf_rel_refresh() {
	delete_transient( NF_REL_CACHE );
	return nf_releases( true );
}

/** Latest version number. */
function nf_latest_version() {
	$r = nf_releases();
	return $r ? $r[0]['version'] : '';
}

/** The Release Notes list in the design of the original page (filters, version index, cards). */
function nf_release_notes_html( $rel = null, $pre = 'v' ) {
	$rel = is_array( $rel ) ? $rel : nf_releases();
	$pre = is_string( $pre ) && '' !== $pre ? $pre : 'v'; // shortcode passes attributes here
	if ( ! $rel ) {
		return '<p>' . esc_html__( 'Release notes are not available right now.', 'notifima' ) . '</p>';
	}
	$labels = nf_rel_types();
	$used   = array();
	foreach ( $rel as $r ) {
		foreach ( $r['items'] as $it ) {
			$used[ $it[0] ] = true;
		}
	}
	$o  = '<div class="cl nf-rel-live"><aside class="cl-side"><h6>' . esc_html__( 'Filter by type', 'notifima' ) . '</h6><div class="cl-filter">';
	$o .= '<button class="on" type="button" data-t="all">' . esc_html__( 'All', 'notifima' ) . '</button>';
	foreach ( $labels as $k => $l ) {
		if ( isset( $used[ $k ] ) ) {
			$o .= '<button type="button" data-t="' . esc_attr( $k ) . '">' . esc_html( $l ) . '</button>';
		}
	}
	$o .= '</div><h6>' . esc_html__( 'Versions', 'notifima' ) . '</h6><nav class="cl-index">';
	foreach ( $rel as $r ) {
		$o .= '<a href="#' . esc_attr( $pre ) . esc_attr( str_replace( '.', '-', $r['version'] ) ) . '">' . esc_html( $r['version'] ) . ( $r['time'] ? '<small>' . esc_html( gmdate( 'Y', $r['time'] ) ) . '</small>' : '' ) . '</a>';
	}
	$o .= '</nav></aside><div class="cl-list">';
	foreach ( $rel as $i => $r ) {
		$o .= '<article class="rel" id="' . esc_attr( $pre ) . esc_attr( str_replace( '.', '-', $r['version'] ) ) . '"><div class="rel-card"><div class="rel-h"><h2>' . esc_html( sprintf( __( 'Version %s', 'notifima' ), $r['version'] ) ) . '</h2>';
		if ( $r['time'] ) {
			$o .= '<time datetime="' . esc_attr( gmdate( 'Y-m-d', $r['time'] ) ) . '">' . esc_html( gmdate( 'jS M Y', $r['time'] ) ) . '</time>';
		}
		if ( 0 === $i ) {
			$o .= '<span class="latest">' . esc_html__( 'Latest', 'notifima' ) . '</span>';
		}
		$o .= '</div><ul>';
		foreach ( $r['items'] as $it ) {
			$t  = isset( $labels[ $it[0] ] ) ? $it[0] : 'update';
			$o .= '<li data-t="' . esc_attr( $t ) . '" class="nf-t-' . esc_attr( $t ) . '"><span class="tg tg-' . esc_attr( $t ) . '">' . esc_html( $labels[ $t ] ) . '</span><span>' . esc_html( $it[1] ) . '</span></li>';
		}
		$o .= '</ul></div></article>';
	}
	return $o . '</div></div>';
}

add_shortcode( 'nf_release_notes', function () { return nf_release_notes_html(); } );
add_shortcode( 'nf_version', function () { return esc_html( nf_latest_version() ); } );

/* Block "Release Notes (Notifima)" */
add_action(
	'init',
	function () {
		register_block_type( NF_DIR . '/blocks/release-notes' );
	},
	21
);

/* GitHub webhook: POST /wp-json/notifima/v1/releases/refresh (signed with the secret). */
function nf_rel_secret() {
	$s = get_option( 'nf_rel_secret' );
	if ( ! $s ) {
		$s = wp_generate_password( 32, false );
		update_option( 'nf_rel_secret', $s, false );
	}
	return $s;
}

add_action(
	'rest_api_init',
	function () {
		register_rest_route(
			'notifima/v1',
			'/releases/refresh',
			array(
				'methods'             => 'POST',
				'permission_callback' => function ( WP_REST_Request $req ) {
					$sig = (string) $req->get_header( 'x-hub-signature-256' );
					$key = (string) $req->get_param( 'key' );
					return ( $sig && hash_equals( 'sha256=' . hash_hmac( 'sha256', $req->get_body(), nf_rel_secret() ), $sig ) )
						|| ( $key && hash_equals( nf_rel_secret(), $key ) );
				},
				'callback'            => function () {
					$r = nf_rel_refresh();
					return array( 'ok' => true, 'latest' => $r ? $r[0]['version'] : null, 'releases' => count( $r ) );
				},
			)
		);
	}
);

/* WordPress.org has no webhook: also check twice a day in the background. */
add_action( 'nf_rel_cron', 'nf_rel_refresh' );
add_action(
	'init',
	function () {
		if ( ! wp_next_scheduled( 'nf_rel_cron' ) ) {
			wp_schedule_event( time() + HOUR_IN_SECONDS, 'twicedaily', 'nf_rel_cron' );
		}
	}
);
add_action( 'switch_theme', function () { wp_clear_scheduled_hook( 'nf_rel_cron' ); } );

/* Once, after updating the theme: the existing Release Notes page switches to the automatic template, so its old
   hand-made list is replaced by the live one without rebuilding the page. */
add_action(
	'init',
	function () {
		if ( get_option( 'nf_rel_template_set' ) ) {
			return;
		}
		update_option( 'nf_rel_template_set', 1, false );
		$page = get_page_by_path( 'release-notes' );
		if ( $page && 'page-templates/template-canvas.php' === get_page_template_slug( $page ) || ( $page && ! get_page_template_slug( $page ) ) ) {
			update_post_meta( $page->ID, '_wp_page_template', 'page-templates/template-release-notes.php' );
		}
	},
	30
);
