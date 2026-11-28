<?php
/**
 * Patterns.
 *
 * The original designs live in patterns/*.html (plain block markup, no PHP). data/patterns.json gives each a kind:
 *   - "layout" (VuloMail Header, VuloMail Footer) and "shared" (sections with the same look and content wherever they
 *     appear: the call to action band, the compare call to action, the blog header and banner, the 404 section)
 *     become synced patterns. Edit one and every page that shows it changes.
 *   - "section" and "page" are plain patterns (Patterns in the block inserter → VuloMail). Inserting one copies its
 *     blocks into the page, so editing a page never changes another page.
 * Pages built from Appearance → VuloMail hold their sections as blocks, with references only to the shared patterns.
 * "Put the original design back" restores a synced pattern from its file.
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

/** [ slug => [ 'title' =>, 'kind' => section|layout|page, 'page' => … ] ] from data/patterns.json. */
function vm_pattern_list() {
	$list = vm_data( 'patterns' );
	return is_array( $list ) ? $list : array();
}

/** The original design of a pattern (patterns/<slug>.html). */
function vm_pattern_content( $slug ) {
	$slug = sanitize_key( $slug );
	$file = VM_DIR . 'patterns/' . $slug . '.html';
	return is_readable( $file ) ? trim( (string) file_get_contents( $file ) ) : ''; // phpcs:ignore WordPress.WP.AlternativeFunctions
}

/** ID of the synced pattern made from <slug>, or 0. */
function vm_pattern_id( $slug ) {
	static $cache = array();
	if ( isset( $cache[ $slug ] ) && 'wp_block' === get_post_type( $cache[ $slug ] ) ) {
		return $cache[ $slug ];
	}
	$q = get_posts(
		array(
			'post_type'   => 'wp_block',
			'post_status' => array( 'publish', 'draft' ),
			'numberposts' => 1,
			'meta_key'    => '_vm_pattern', // phpcs:ignore WordPress.DB.SlowDBQuery
			'meta_value'  => $slug, // phpcs:ignore WordPress.DB.SlowDBQuery
			'fields'      => 'ids',
		)
	);
	$cache[ $slug ] = $q ? (int) $q[0] : 0;
	// 1.0.0 stored the header and footer patterns in an option: adopt them instead of creating duplicates.
	if ( ! $cache[ $slug ] && in_array( $slug, array( 'header', 'footer' ), true ) ) {
		$old = (int) get_option( 'vm_' . $slug . '_block_id' );
		if ( $old && 'wp_block' === get_post_type( $old ) && 'trash' !== get_post_status( $old ) ) {
			update_post_meta( $old, '_vm_pattern', $slug );
			$cache[ $slug ] = $old;
		}
	}
	return $cache[ $slug ];
}

/** "VuloMail" pattern category (WordPress 6.5+ shows it in the inserter for synced patterns too). */
function vm_pattern_category_id() {
	if ( ! taxonomy_exists( 'wp_pattern_category' ) ) {
		return 0;
	}
	$t = term_exists( 'vulomail', 'wp_pattern_category' );
	if ( ! $t ) {
		$t = wp_insert_term( 'VuloMail', 'wp_pattern_category', array( 'slug' => 'vulomail' ) );
	}
	return is_array( $t ) ? (int) $t['term_id'] : 0;
}

/** Is this pattern synced (one copy shared by every page that shows it)? */
function vm_pattern_is_synced( $slug ) {
	$list = vm_pattern_list();
	return isset( $list[ $slug ] ) && in_array( $list[ $slug ]['kind'], array( 'layout', 'shared' ), true );
}

/**
 * Content of a page: the blocks of each section, and a reference for each shared section
 * (its blocks when the synced pattern doesn't exist yet).
 */
function vm_page_pattern_content( $page ) {
	$pages = vm_data( 'pages' );
	if ( empty( $pages[ $page ] ) ) {
		return '';
	}
	$out = array();
	foreach ( $pages[ $page ]['sections'] as $slug ) {
		$id = vm_pattern_is_synced( $slug ) ? vm_pattern_id( $slug ) : 0;
		$out[] = $id ? '<!-- wp:block {"ref":' . $id . '} /-->' : vm_pattern_content( $slug );
	}
	return implode( "\n\n", array_filter( $out ) );
}

/**
 * Creates every missing synced pattern (header, footer and the shared sections). $reset: slugs whose content goes
 * back to the original design (WordPress keeps the edited version as a revision).
 */
function vm_sync_patterns( $reset = array() ) {
	$cat     = vm_pattern_category_id();
	$created = 0;
	$restore = 0;
	foreach ( vm_pattern_list() as $slug => $p ) {
		if ( ! vm_pattern_is_synced( $slug ) ) {
			continue;
		}
		$content = vm_pattern_content( $slug );
		if ( '' === $content ) {
			continue;
		}
		$id = vm_pattern_id( $slug );
		if ( ! $id ) {
			$id = wp_insert_post(
				array(
					'post_type'    => 'wp_block',
					'post_status'  => 'publish',
					'post_title'   => $p['title'],
					'post_content' => wp_slash( $content ),
				)
			);
			if ( $id && ! is_wp_error( $id ) ) {
				update_post_meta( $id, '_vm_pattern', $slug );
				$created++;
			}
		} elseif ( in_array( $slug, (array) $reset, true ) ) {
			wp_update_post( array( 'ID' => $id, 'post_title' => $p['title'], 'post_content' => wp_slash( $content ) ) );
			$restore++;
		}
		if ( $id && ! is_wp_error( $id ) && $cat ) {
			wp_set_object_terms( $id, array( $cat ), 'wp_pattern_category', false );
		}
	}
	return array( $created, $restore );
}
add_action( 'after_switch_theme', 'vm_sync_patterns' );

/** Renders a pattern: its synced version when it exists, otherwise the original design. */
function vm_render_pattern( $slug ) {
	$id = vm_pattern_id( $slug );
	if ( $id ) {
		return do_blocks( '<!-- wp:block {"ref":' . $id . '} /-->' );
	}
	return do_blocks( vm_pattern_content( $slug ) );
}

/** Is this one of the theme's synced patterns? */
function vm_is_theme_pattern( $post_id ) {
	return '' !== (string) get_post_meta( $post_id, '_vm_pattern', true );
}

/**
 * Plain patterns: every section, and every page as a whole, in the block inserter (Patterns → VuloMail and
 * VuloMail pages). Inserting one copies its blocks; a page's shared sections come in as synced references.
 */
function vm_register_plain_patterns() {
	static $done = false;
	if ( $done || ! function_exists( 'register_block_pattern' ) ) {
		return;
	}
	$done = true;
	register_block_pattern_category( 'vulomail', array( 'label' => 'VuloMail' ) );
	register_block_pattern_category( 'vulomail-pages', array( 'label' => 'VuloMail pages' ) );
	$lazy = version_compare( get_bloginfo( 'version' ), '6.5', '>=' ); // filePath: the file is read only when the pattern is used.
	foreach ( vm_pattern_list() as $slug => $p ) {
		if ( 'section' !== $p['kind'] ) {
			continue;
		}
		$args = array( 'title' => $p['title'], 'categories' => array( 'vulomail' ), 'viewportWidth' => 1440 );
		if ( $lazy ) {
			$args['filePath'] = VM_DIR . 'patterns/' . sanitize_key( $slug ) . '.html';
		} else {
			$args['content'] = vm_pattern_content( $slug );
		}
		register_block_pattern( 'vulomail/' . $slug, $args );
	}
}
add_action( 'init', 'vm_register_plain_patterns', 20 );

/** Whole pages hold references to the synced patterns, so they're registered only where the editor asks for them. */
function vm_register_page_patterns() {
	static $done = false;
	if ( $done || ! function_exists( 'register_block_pattern' ) ) {
		return;
	}
	$done = true;
	foreach ( vm_pattern_list() as $slug => $p ) {
		if ( 'page' !== $p['kind'] || empty( $p['page'] ) || in_array( (string) $p['page'], array( 'blog', '404' ), true ) ) {
			continue;
		}
		register_block_pattern(
			'vulomail/' . $slug,
			array(
				'title'         => $p['title'],
				'categories'    => array( 'vulomail-pages' ),
				'blockTypes'    => array( 'core/post-content' ),
				'postTypes'     => array( 'page' ),
				'viewportWidth' => 1440,
				'content'       => vm_page_pattern_content( $p['page'] ),
			)
		);
	}
}
add_action( 'rest_api_init', 'vm_register_page_patterns' );
add_action( 'admin_init', 'vm_register_page_patterns' );

/* ---------------------------------------------------------------- update from 1.1 */

/**
 * Class names that 1.1 used without the vm: prefix inside block markup. Graphic props class, box and wrap hold
 * utilities only, so every token there is prefixed.
 */
function vm_legacy_prefix( $html ) {
	$known  = array( 'bg-paper-deep', 'font-mono', 'font-semibold', 'group', 'px-1', 'rounded', 'text-base', 'text-sm' );
	$tokens = function ( $value, $all ) use ( $known ) {
		$out = array();
		foreach ( preg_split( '/\s+/', trim( $value ) ) as $t ) {
			if ( '' === $t ) {
				continue;
			}
			$plain = ! preg_match( '/^(vm-|vm:|kt-|kb-|kadence-|wp-|has-|is-|lucide)/', $t );
			$out[] = $plain && ( $all || in_array( $t, $known, true ) ) ? 'vm:' . $t : $t;
		}
		return implode( ' ', $out );
	};
	$html = preg_replace_callback(
		'/(<!-- wp:vulomail\/graphic )(\{.*?\})( \/-->)/',
		function ( $m ) use ( $tokens ) {
			return $m[1] . preg_replace_callback( '/("(?:class|box|wrap)":")([^"]*)(")/', function ( $n ) use ( $tokens ) { return $n[1] . $tokens( $n[2], true ) . $n[3]; }, $m[2] ) . $m[3];
		},
		$html
	);
	$html = preg_replace_callback( '/("className":")([^"]*)(")/', function ( $m ) use ( $tokens ) { return $m[1] . $tokens( $m[2], false ) . $m[3]; }, $html );
	return preg_replace_callback( '/(\bclass=")([^"]*)(")/', function ( $m ) use ( $tokens ) { return $m[1] . $tokens( $m[2], false ) . $m[3]; }, $html );
}

/**
 * 1.2.0 narrowed some Row Layouts with the classes vm-w-5xl and vm-w-6xl. 1.2.1 uses Kadence's own setting instead
 * (Row Layout → Structure → Content max width), so the class becomes the maxWidth attribute.
 */
function vm_legacy_widths( $html ) {
	return preg_replace_callback(
		'/(<!-- wp:kadence\/rowlayout )(\{.*?\})( -->)/',
		function ( $m ) {
			$a = json_decode( $m[2], true );
			if ( ! is_array( $a ) || empty( $a['className'] ) || ! preg_match( '/(^|\s)vm-w-(5|6)xl(\s|$)/', $a['className'], $w ) ) {
				return $m[0];
			}
			$a['className'] = trim( preg_replace( '/\s*\bvm-w-[56]xl\b/', '', $a['className'] ) );
			if ( '' === $a['className'] ) {
				unset( $a['className'] );
			}
			if ( empty( $a['maxWidth'] ) ) {
				$a['maxWidth']     = '5' === $w[2] ? 1024 : 1152;
				$a['maxWidthUnit'] = 'px';
			}
			return $m[1] . serialize_block_attributes( $a ) . $m[3];
		},
		$html
	);
}

/** Is there anything left for "Update my pages" to do? */
function vm_needs_update() {
	if ( get_option( 'vm_migrated_121' ) ) {
		return false;
	}
	global $wpdb;
	$old = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_status NOT IN ('trash','auto-draft','inherit') AND post_content LIKE '%vm-w-%xl%'" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
	if ( ! $old && ! vm_legacy_pattern_ids() ) {
		update_option( 'vm_migrated_121', 1 );
		return false;
	}
	return true;
}

/** Old section slugs that are now one shared pattern. */
function vm_merged_slug( $slug ) {
	if ( in_array( $slug, array( 'home-cta', 'features-cta', 'pricing-cta', 'contact-cta' ), true ) ) {
		return 'shared-cta';
	}
	if ( 'compare-cta' !== $slug && preg_match( '/^compare-[a-z0-9-]+-cta$/', $slug ) ) {
		return 'shared-compare-cta';
	}
	return '';
}

/** Theme synced patterns from 1.1 that are plain patterns now: [ id => slug ]. */
function vm_legacy_pattern_ids() {
	$out = array();
	$ids = get_posts( array( 'post_type' => 'wp_block', 'post_status' => array( 'publish', 'draft' ), 'numberposts' => -1, 'meta_key' => '_vm_pattern', 'fields' => 'ids' ) ); // phpcs:ignore WordPress.DB.SlowDBQuery
	foreach ( $ids as $id ) {
		$slug = (string) get_post_meta( $id, '_vm_pattern', true );
		if ( ! vm_pattern_is_synced( $slug ) ) {
			$out[ (int) $id ] = $slug;
		}
	}
	return $out;
}

/**
 * Updates a site built with 1.1 or 1.2.0: on every page, post and synced pattern, references to section patterns
 * become the section's blocks (with your edits), the four identical call to action bands and the ten compare ones
 * become the shared patterns, 1.1 class names get the vm: prefix, and vm-w-5xl / vm-w-6xl become Kadence's
 * Content max width. The old section and page patterns go to the trash.
 * WordPress keeps the previous version of each page as a revision. Returns a list of messages.
 */
function vm_migrate_from_11() {
	vm_sync_patterns();
	$legacy = vm_legacy_pattern_ids();
	$inline = function ( $content, $depth = 0 ) use ( &$inline, $legacy ) {
		return preg_replace_callback(
			'/<!-- wp:block (\{[^}]*\}) \/-->/',
			function ( $m ) use ( &$inline, $legacy, $depth ) {
				$a  = json_decode( $m[1], true );
				$id = isset( $a['ref'] ) ? (int) $a['ref'] : 0;
				if ( ! isset( $legacy[ $id ] ) ) {
					return $m[0];
				}
				$merged = vm_merged_slug( $legacy[ $id ] );
				$new    = $merged ? vm_pattern_id( $merged ) : 0;
				if ( $new ) {
					return '<!-- wp:block {"ref":' . $new . '} /-->';
				}
				$post = get_post( $id );
				return $post && $depth < 3 ? $inline( trim( $post->post_content ), $depth + 1 ) : '';
			},
			$content
		);
	};
	$changed = 0;
	$ids     = get_posts( array( 'post_type' => 'any', 'post_status' => array( 'publish', 'draft', 'pending', 'private', 'future' ), 'numberposts' => -1, 'fields' => 'ids' ) );
	$ids     = array_merge( $ids, get_posts( array( 'post_type' => 'wp_block', 'post_status' => array( 'publish', 'draft' ), 'numberposts' => -1, 'fields' => 'ids' ) ) );
	foreach ( array_unique( $ids ) as $id ) {
		if ( isset( $legacy[ $id ] ) ) {
			continue;
		}
		$post = get_post( $id );
		$new  = vm_legacy_widths( vm_legacy_prefix( $inline( $post->post_content ) ) );
		if ( $new !== $post->post_content ) {
			wp_update_post( array( 'ID' => $id, 'post_content' => wp_slash( $new ) ) );
			$changed++;
		}
	}
	foreach ( array_keys( $legacy ) as $id ) {
		wp_trash_post( $id );
	}
	update_option( 'vm_migrated_121', 1 );
	return array(
		sprintf( '%d pages, posts and patterns updated (WordPress keeps the previous version as a revision).', $changed ),
		sprintf( '%d old section and page patterns moved to the trash. Sections are now plain patterns in the inserter.', count( $legacy ) ),
	);
}
