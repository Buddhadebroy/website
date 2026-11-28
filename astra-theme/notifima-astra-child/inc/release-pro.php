<?php
/**
 * Notifima Pro Release Notes: written by hand in the Kadence editor, shown exactly like the free Release Notes page.
 *
 * In the page editor each version is a Kadence Section with the class "rel" (inside the Section "nf-relpro"):
 *   - Advanced Text  "1.1.7"               (class rel-ver)  → "Version 1.1.7"
 *   - Advanced Text  "3rd November, 2026"  (class rel-date)
 *   - List, one change per line: "Added - …", "Fixed - …", "Update - …", "Enhanced - …" (class rel-items)
 * To add a version: Block inserter → Patterns → Notifima → "Pro release · one version", drop it at the top of the
 * list, and change the number, the date and the lines.
 *
 * On the site, any page holding the "nf-relpro" Section uses page-templates/template-release-notes-pro.php: the same
 * hero and list as the free Release Notes page (same filters, labels, colours, date format, version index), filled
 * from the versions typed in the editor.
 *
 * @package Notifima
 */

defined( 'ABSPATH' ) || exit;

/** Text of the first element with this class inside $node. */
function nf_relpro_text( DOMXPath $xp, DOMNode $node, $class ) {
	$n = $xp->query( './/*[contains(concat(" ", normalize-space(@class), " "), " ' . $class . ' ")]', $node );
	return $n->length ? trim( preg_replace( '/\s+/u', ' ', $n->item( 0 )->textContent ) ) : '';
}

/** Markup of the "nf-relpro" Section → releases in the same shape as nf_releases(). */
function nf_relpro_parse( $html ) {
	if ( ! class_exists( 'DOMDocument' ) || '' === trim( (string) $html ) ) {
		return array();
	}
	$doc = new DOMDocument();
	libxml_use_internal_errors( true );
	$doc->loadHTML( '<?xml encoding="utf-8"?><div id="nfrp">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
	libxml_clear_errors();
	$xp  = new DOMXPath( $doc );
	$out = array();
	foreach ( $xp->query( '//*[contains(concat(" ", normalize-space(@class), " "), " rel ")]' ) as $rel ) {
		$ver = preg_replace( '/^\s*(version|v)\s*/i', '', nf_relpro_text( $xp, $rel, 'rel-ver' ) );
		if ( '' === $ver ) {
			continue;
		}
		$items = array();
		foreach ( $xp->query( './/li', $rel ) as $li ) {
			$line = trim( preg_replace( '/\s+/u', ' ', $li->textContent ) );
			if ( '' !== $line ) {
				$items[] = nf_rel_item( $line ); // same types and labels as the free page
			}
		}
		$out[] = array(
			'version' => $ver,
			'time'    => nf_rel_time( nf_relpro_text( $xp, $rel, 'rel-date' ) ),
			'items'   => $items,
		);
	}
	return $out;
}

/** The "nf-relpro" block of a page's content (searched through nested blocks), or null. */
function nf_relpro_find_block( $blocks ) {
	foreach ( (array) $blocks as $b ) {
		$cls = isset( $b['attrs']['className'] ) ? ' ' . $b['attrs']['className'] . ' ' : '';
		if ( false !== strpos( $cls, ' nf-relpro ' ) ) {
			return $b;
		}
		if ( ! empty( $b['innerBlocks'] ) ) {
			$f = nf_relpro_find_block( $b['innerBlocks'] );
			if ( $f ) {
				return $f;
			}
		}
	}
	return null;
}

/** Releases typed on a page (newest first, as in the editor). */
function nf_relpro_releases( $post_id = 0 ) {
	$content = (string) get_post_field( 'post_content', $post_id ? $post_id : get_queried_object_id() );
	$b       = false !== strpos( $content, 'nf-relpro' ) ? nf_relpro_find_block( parse_blocks( $content ) ) : null;
	return $b ? nf_relpro_parse( serialize_block( $b ) ) : array();
}

/** Does this page hold the Pro release list? */
function nf_is_relpro_page() {
	if ( ! is_page() ) {
		return false;
	}
	$content = (string) get_post_field( 'post_content', get_queried_object_id() );
	return false !== strpos( $content, '"nf-relpro"' ) || false !== strpos( $content, ' nf-relpro"' ) || false !== strpos( $content, '"nf-relpro ' );
}

/* Show the page in the free Release Notes design, whatever template is picked in the editor. */
add_filter(
	'template_include',
	function ( $template ) {
		if ( nf_is_relpro_page() ) {
			$t = NF_DIR . '/page-templates/template-release-notes-pro.php';
			if ( file_exists( $t ) ) {
				return $t;
			}
		}
		return $template;
	},
	10000
);

/* Fallback when the content is shown some other way (e.g. a shortcode or a preview): the list in the same design. */
add_filter(
	'render_block',
	function ( $html, $block ) {
		if ( 'kadence/column' !== $block['blockName'] || is_admin() || wp_is_json_request() ) {
			return $html;
		}
		$cls = isset( $block['attrs']['className'] ) ? ' ' . $block['attrs']['className'] . ' ' : '';
		if ( false === strpos( $cls, ' nf-relpro ' ) ) {
			return $html;
		}
		$rel = nf_relpro_parse( $html );
		return $rel ? '<div class="nf-rp-wrap nf-release-notes">' . nf_release_notes_html( $rel, 'pro-v' ) . '</div>' : $html;
	},
	20,
	2
);

/* "Pro release · one version" pattern, to add a new version. */
add_action(
	'init',
	function () {
		$file = NF_DIR . '/data/page-extra/release-pro-version.html';
		if ( function_exists( 'register_block_pattern' ) && file_exists( $file ) ) {
			register_block_pattern(
				'notifima/release-pro-version',
				array(
					'title'       => __( 'Pro release · one version', 'notifima' ),
					'description' => __( 'One version of the Notifima Pro Release Notes. Put it at the top of the list and change the number, date and lines ("Added - …", "Fixed - …", "Update - …").', 'notifima' ),
					'categories'  => array( 'notifima' ),
					'keywords'    => array( 'release', 'changelog', 'version', 'pro' ),
					'content'     => (string) file_get_contents( $file ), // phpcs:ignore
				)
			);
		}
	}
);
