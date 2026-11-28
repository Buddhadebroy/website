<?php
/**
 * Menus. The header and footer links come from WordPress menus (Appearance → Menus):
 *  - "VuloMail header menu": top-level items, and parents with children for the dropdowns
 *    (the item Description is the dropdown sub-line; CSS class vm-icon-<lucide-name> picks the icon).
 *  - "VuloMail footer: More from VuloLabs": the last footer column.
 * Both menus are created on activation with the original site's links.
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

/** Icon per path, as in the original header. */
function vm_nav_icon_for( $href ) {
	$map = array(
		'/features'    => 'sparkles',
		'/email'       => 'mail',
		'/sms'         => 'message-square-text',
		'/security'    => 'shield-check',
		'/get-started' => 'rocket',
		'/developers'  => 'code',
		'/faq'         => 'circle-help',
		'/blog'        => 'newspaper',
		'/about'       => 'globe-2',
		'/changelog'   => 'file-clock',
		'/contact'     => 'at-sign',
	);
	foreach ( $map as $p => $icon ) {
		if ( untrailingslashit( wp_parse_url( $href, PHP_URL_PATH ) ?: '' ) === $p ) {
			return $icon;
		}
	}
	return 0 === strpos( $href, 'http' ) ? 'book-open' : 'sparkles';
}

/**
 * The header menu as a tree:
 * [ ['label' => 'Product', 'items' => [ ['href','label','desc','external','icon'], … ] ], ['href','label'], … ]
 */
function vm_nav_tree() {
	$locations = get_nav_menu_locations();
	if ( ! empty( $locations['vm_primary'] ) ) {
		$items = wp_get_nav_menu_items( $locations['vm_primary'] );
		if ( $items ) {
			// Two passes: WordPress may return a child before its parent (menu_order), so place parents first.
			$nodes = array();
			foreach ( $items as $it ) {
				$node = array(
					'href'     => $it->url,
					'label'    => $it->title,
					'desc'     => $it->description,
					'external' => '_blank' === $it->target || ( 0 === strpos( $it->url, 'http' ) && false === strpos( $it->url, home_url() ) ),
					'icon'     => '',
				);
				foreach ( (array) $it->classes as $c ) {
					if ( 0 === strpos( $c, 'vm-icon-' ) ) {
						$node['icon'] = substr( $c, 8 );
					}
				}
				if ( ! $node['icon'] ) {
					$node['icon'] = vm_nav_icon_for( $it->url );
				}
				$nodes[ $it->ID ] = array( 'node' => $node, 'parent' => (int) $it->menu_item_parent, 'order' => (int) $it->menu_order );
			}
			uasort( $nodes, function ( $a, $b ) { return $a['order'] - $b['order']; } );
			$tree = array();
			$byid = array();
			foreach ( $nodes as $id => $n ) {
				if ( ! $n['parent'] || ! isset( $nodes[ $n['parent'] ] ) ) {
					$byid[ $id ] = count( $tree );
					$tree[]      = $n['node'];
				}
			}
			foreach ( $nodes as $id => $n ) {
				if ( $n['parent'] && isset( $byid[ $n['parent'] ] ) ) {
					$tree[ $byid[ $n['parent'] ] ]['items'][] = $n['node'];
				}
			}
			foreach ( $tree as $k => $t ) {
				if ( isset( $t['items'] ) && '#' === $t['href'] ) {
					$tree[ $k ] = array( 'label' => $t['label'], 'items' => $t['items'] );
				}
			}
			return $tree;
		}
	}
	// No menu assigned yet: the original site's navigation.
	$d    = vm_data( 'site' );
	$tree = array();
	foreach ( (array) $d['nav'] as $n ) {
		if ( isset( $n['items'] ) ) {
			$items = array();
			foreach ( $n['items'] as $i ) {
				$ext     = ! empty( $i['external'] );
				$items[] = array(
					'href'     => $ext ? $i['href'] : vm_url( $i['href'] ),
					'label'    => $i['label'],
					'desc'     => isset( $i['desc'] ) ? $i['desc'] : '',
					'external' => $ext,
					'icon'     => vm_nav_icon_for( $i['href'] ),
				);
			}
			$tree[] = array( 'label' => $n['label'], 'items' => $items );
		} else {
			$tree[] = array( 'href' => vm_url( $n['href'] ), 'label' => $n['label'], 'desc' => '', 'external' => false, 'icon' => '' );
		}
	}
	return $tree;
}

/** "More from VuloLabs" footer links. */
function vm_more_links() {
	$locations = get_nav_menu_locations();
	if ( ! empty( $locations['vm_more'] ) ) {
		$items = wp_get_nav_menu_items( $locations['vm_more'] );
		if ( $items ) {
			return array_map(
				function ( $it ) {
					return array( 'href' => $it->url, 'label' => $it->title, 'desc' => $it->description, 'external' => true );
				},
				$items
			);
		}
	}
	return array(
		array( 'href' => 'https://www.vulolabs.com/', 'label' => 'VuloLabs', 'desc' => 'Product engineering for modern business', 'external' => true ),
		array( 'href' => 'https://vulopilot.com/', 'label' => 'VuloPilot', 'desc' => 'The WordPress growth operating system', 'external' => true ),
		array( 'href' => 'https://vuloform.com/', 'label' => 'VuloForm', 'desc' => 'Build and review forms inside WordPress', 'external' => true ),
	);
}

/** Is this link the current page (or a parent of it)? Same rule as the original isActive(). */
function vm_is_current( $href ) {
	if ( empty( $href ) || 0 === strpos( $href, 'http' ) && false === strpos( $href, home_url() ) ) {
		return false;
	}
	$req  = isset( $_SERVER['REQUEST_URI'] ) ? wp_parse_url( esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ), PHP_URL_PATH ) : '/';
	$path = wp_parse_url( $href, PHP_URL_PATH );
	$req  = untrailingslashit( (string) $req );
	$path = untrailingslashit( (string) $path );
	return '' !== $path && ( $req === $path || 0 === strpos( $req, $path . '/' ) );
}

/** Creates both menus on activation (only if they don't exist yet). */
function vm_create_menus() {
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	if ( empty( $locations['vm_primary'] ) ) {
		$menu_id = wp_create_nav_menu( 'VuloMail header' );
		if ( ! is_wp_error( $menu_id ) ) {
			$d   = vm_data( 'site' );
			$pos = 0;
			foreach ( (array) $d['nav'] as $n ) {
				if ( isset( $n['items'] ) ) {
					$parent = wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-title' => $n['label'], 'menu-item-url' => '#', 'menu-item-status' => 'publish', 'menu-item-position' => ++$pos ) );
					foreach ( $n['items'] as $i ) {
						$ext = ! empty( $i['external'] );
						wp_update_nav_menu_item(
							$menu_id,
							0,
							array(
								'menu-item-title'       => $i['label'],
								'menu-item-url'         => $ext ? $i['href'] : vm_url( $i['href'] ),
								'menu-item-description' => isset( $i['desc'] ) ? $i['desc'] : '',
								'menu-item-classes'     => 'vm-icon-' . vm_nav_icon_for( $i['href'] ),
								'menu-item-target'      => $ext ? '_blank' : '',
								'menu-item-parent-id'   => $parent,
								'menu-item-status'      => 'publish',
								'menu-item-position'    => ++$pos,
							)
						);
					}
				} else {
					wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-title' => $n['label'], 'menu-item-url' => vm_url( $n['href'] ), 'menu-item-status' => 'publish', 'menu-item-position' => ++$pos ) );
				}
			}
			$locations['vm_primary'] = $menu_id;
		}
	}
	if ( empty( $locations['vm_more'] ) ) {
		$menu_id = wp_create_nav_menu( 'VuloMail footer: More from VuloLabs' );
		if ( ! is_wp_error( $menu_id ) ) {
			foreach ( vm_more_links() as $k => $l ) {
				wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-title' => $l['label'], 'menu-item-url' => $l['href'], 'menu-item-description' => $l['desc'], 'menu-item-target' => '_blank', 'menu-item-status' => 'publish', 'menu-item-position' => $k + 1 ) );
			}
			$locations['vm_more'] = $menu_id;
		}
	}
	set_theme_mod( 'nav_menu_locations', $locations );
}
add_action( 'after_switch_theme', 'vm_create_menus' );
