<?php
/**
 * Header, built in WordPress with Kadence blocks. Shared with inc/footer.php: nf_hf_slug(), nf_hf_edit_url(),
 * nf_hf_render() work for either part by parameter; this file also handles the site icon import, the header
 * menu (mega menu data, tree, creation) and registers the Site Logo / Site Menu blocks used by both parts.
 *
 *   Content   the synced pattern "Site · Header" (inc/patterns.php makes it from patterns/site-header.php).
 *             Edit it in Appearance → Design → Patterns, or Appearance → Notifima → Edit header (Kadence editor).
 *   Output    printed in place of Astra's header (astra_header) on every page.
 *             Customize → Notifima → General: turn it off to get Astra's own back.
 *   Logo      Site Logo (Notifima) block = Customize → Site Identity → Logo (else the "Notifima" wordmark); favicon = Site Icon.
 *   Menu      Site Menu (Notifima) block = Appearance → Menus → "Notifima header". A top item with sub items
 *             becomes a mega menu: level 2 = column titles, level 3 = links (Description = small text,
 *             CSS class "nf-icon-NAME" = icon). Without a menu the default from data/menu.json is shown.
 *
 * @package Notifima
 */

defined( 'ABSPATH' ) || exit;

function nf_hf_slug( $part ) {
	return 'header' === $part ? 'site-header' : 'site-footer';
}

function nf_hf_edit_url( $part ) {
	return nf_pattern_edit_url( nf_hf_slug( $part ) );
}

/** HTML of the header or footer: the synced pattern, or the pattern file when it doesn't exist (yet). */
function nf_hf_render( $part ) {
	$post    = nf_synced_post( nf_hf_slug( $part ) );
	$content = $post && 'publish' === $post->post_status ? $post->post_content : nf_pattern_markup( nf_hf_slug( $part ) );
	return do_shortcode( do_blocks( $content ) );
}

/** Theme activated: synced patterns (only missing ones), the header menu, the site icon. */
add_action(
	'after_switch_theme',
	function () {
		nf_sync_all();
		nf_make_menu();
		nf_import_identity();
	}
);

/** Site icon (favicon) from assets/img/site-icon.png when none is set. The logo stays the "Notifima" wordmark unless you pick one. */
function nf_import_identity() {
	if ( get_option( 'site_icon' ) ) {
		return;
	}
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';
	$tmp = wp_tempnam( 'site-icon.png' );
	copy( NF_DIR . '/assets/img/site-icon.png', $tmp );
	$id = media_handle_sideload( array( 'name' => 'notifima-site-icon.png', 'tmp_name' => $tmp ), 0, 'Notifima site icon' );
	if ( is_wp_error( $id ) ) {
		@unlink( $tmp ); // phpcs:ignore
		return;
	}
	update_option( 'site_icon', (int) $id );
}

/** Print ours in place of Astra's header. */
add_action(
	'wp',
	function () {
		if ( nf_opt( 'nf_use_header' ) ) {
			remove_all_actions( 'astra_header' );
			add_action( 'astra_header', function () { echo nf_hf_render( 'header' ); } ); // phpcs:ignore
		}
	}
);

/* ---------------------------------------------------------------------------
 * Menu data
 * ------------------------------------------------------------------------ */

/** "@docs" or "#nf-docs" → Customize link, "/pricing/" → home_url, anything else as is. */
function nf_menu_url( $u ) {
	$u = (string) $u;
	if ( 0 === strpos( $u, '@' ) ) {
		return nf_link( substr( $u, 1 ) );
	}
	if ( preg_match( '/^#nf-([a-z_]+)$/', $u, $m ) ) {
		return nf_link( $m[1] );
	}
	return nf_url( $u );
}

/** menu.json title => Customize key, for the items that use a Customize link ("@docs"). */
function nf_menu_link_titles( $items = null, &$out = array() ) {
	foreach ( null === $items ? ( nf_data( 'menu' )['items'] ?? array() ) : $items as $it ) {
		if ( isset( $it['url'] ) && 0 === strpos( $it['url'], '@' ) ) {
			$out[ strtolower( $it['title'] ) ] = substr( $it['url'], 1 );
		}
		nf_menu_link_titles( $it['children'] ?? array(), $out );
	}
	return $out;
}

/** Once: menu items made by older versions saved "#" for Documentation, Support … Point them at the Customize link instead. */
add_action(
	'admin_init',
	function () {
		if ( get_option( 'nf_menu_tokens' ) || ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}
		update_option( 'nf_menu_tokens', 1 );
		$loc = get_nav_menu_locations();
		if ( empty( $loc['nf_header'] ) ) {
			return;
		}
		$map = nf_menu_link_titles();
		foreach ( (array) wp_get_nav_menu_items( $loc['nf_header'] ) as $it ) {
			$key = $map[ strtolower( $it->title ) ] ?? '';
			if ( $key && in_array( $it->url, array( '#', '' ), true ) ) {
				update_post_meta( $it->ID, '_menu_item_url', '#nf-' . $key );
			}
		}
	}
);

/** The menu as a tree: [ [ title, url, desc, icon, children[] ] ]. From the "Notifima header" location, else data/menu.json. */
function nf_menu_tree() {
	static $tree = null;
	if ( null !== $tree ) {
		return $tree;
	}
	$loc = get_nav_menu_locations();
	if ( ! empty( $loc['nf_header'] ) ) {
		$items = wp_get_nav_menu_items( $loc['nf_header'] );
		if ( $items ) {
			$by = array();
			foreach ( $items as $it ) {
				$icon = '';
				foreach ( (array) $it->classes as $c ) {
					if ( 0 === strpos( $c, 'nf-icon-' ) ) {
						$icon = substr( $c, 8 );
					}
				}
				$by[ $it->ID ] = array( 'title' => $it->title, 'url' => nf_menu_url( $it->url ), 'desc' => $it->description, 'icon' => $icon, 'parent' => (int) $it->menu_item_parent, 'children' => array() );
			}
			$tree = nf_menu_rebuild( $by );
			return $tree;
		}
	}
	$tree = nf_menu_from_json( nf_data( 'menu' )['items'] ?? array() );
	return $tree;
}

/** Ordered tree from the flat list of menu items. */
function nf_menu_rebuild( $by ) {
	$kids = array();
	foreach ( $by as $id => $n ) {
		$kids[ $n['parent'] ][] = $id;
	}
	$build = function ( $parent ) use ( &$build, $by, $kids ) {
		$out = array();
		foreach ( $kids[ $parent ] ?? array() as $id ) {
			$n             = $by[ $id ];
			$n['children'] = $build( $id );
			$out[]         = $n;
		}
		return $out;
	};
	return $build( 0 );
}

function nf_menu_from_json( $items ) {
	$out = array();
	foreach ( $items as $it ) {
		$out[] = array(
			'title'    => $it['title'],
			'url'      => nf_menu_url( $it['url'] ?? '#' ),
			'desc'     => $it['desc'] ?? '',
			'icon'     => $it['icon'] ?? '',
			'children' => nf_menu_from_json( $it['children'] ?? array() ),
		);
	}
	return $out;
}

/** Create the "Notifima header" menu from data/menu.json once (only when that location is empty). */
function nf_make_menu() {
	$loc = get_nav_menu_locations();
	if ( ! empty( $loc['nf_header'] ) ) {
		return;
	}
	$menu = wp_get_nav_menu_object( 'Notifima header' );
	$id   = $menu ? $menu->term_id : wp_create_nav_menu( 'Notifima header' );
	if ( is_wp_error( $id ) ) {
		return;
	}
	if ( ! $menu ) {
		$add = function ( $items, $parent ) use ( &$add, $id ) {
			foreach ( $items as $it ) {
				$item = wp_update_nav_menu_item(
					$id,
					0,
					array(
						'menu-item-title'       => $it['title'],
						'menu-item-url'         => isset( $it['url'] ) ? ( 0 === strpos( $it['url'], '@' ) ? '#nf-' . substr( $it['url'], 1 ) : nf_menu_url( $it['url'] ) ) : '#',
						'menu-item-description' => $it['desc'] ?? '',
						'menu-item-classes'     => empty( $it['icon'] ) ? '' : 'nf-icon-' . $it['icon'],
						'menu-item-parent-id'   => $parent,
						'menu-item-status'      => 'publish',
						'menu-item-type'        => 'custom',
					)
				);
				if ( ! is_wp_error( $item ) && ! empty( $it['children'] ) ) {
					$add( $it['children'], $item );
				}
			}
		};
		$add( nf_data( 'menu' )['items'] ?? array(), 0 );
	}
	$loc['nf_header'] = $id;
	set_theme_mod( 'nav_menu_locations', $loc );
}

/** Is this menu URL the current page? */
function nf_menu_is_current( $url, $children = array() ) {
	$here = untrailingslashit( (string) wp_parse_url( home_url( add_query_arg( array() ) ), PHP_URL_PATH ) );
	$path = untrailingslashit( (string) wp_parse_url( $url, PHP_URL_PATH ) );
	if ( '#' !== $url && $path === $here && ( '' !== $path || is_front_page() ) ) {
		return true;
	}
	foreach ( $children as $c ) {
		if ( nf_menu_is_current( $c['url'], $c['children'] ) ) {
			return true;
		}
	}
	return false;
}

/** The featured card of a mega menu: the latest blog post, else data/menu.json → feature. */
function nf_mega_feature() {
	$f    = nf_data( 'menu' )['feature'] ?? array();
	$post = get_posts( array( 'numberposts' => 1, 'post_status' => 'publish' ) );
	if ( $post ) {
		$f = array( 'title' => get_the_title( $post[0] ), 'text' => __( 'Our latest guide.', 'notifima' ), 'link_text' => __( 'Read the post', 'notifima' ), 'url' => get_permalink( $post[0] ), 'row1' => wp_trim_words( get_the_title( $post[0] ), 5 ), 'row2' => nf_read_time( $post[0] ) . ' read' );
	} else {
		$f['url'] = nf_menu_url( $f['url'] ?? '/blog/' );
	}
	return $f;
}

/* ---------------------------------------------------------------------------
 * Blocks used by the header and footer
 * ------------------------------------------------------------------------ */
add_action(
	'init',
	function () {
		wp_register_script( 'nf-site-blocks-editor', NF_URI . '/blocks/site-editor.js', array( 'wp-blocks', 'wp-block-editor', 'wp-components', 'wp-element', 'wp-server-side-render' ), nf_ver( 'blocks/site-editor.js' ), true );
		register_block_type( NF_DIR . '/blocks/site-logo' );
		register_block_type( NF_DIR . '/blocks/site-menu' );
	},
	20
);
