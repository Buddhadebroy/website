<?php
/**
 * Site Menu (Notifima).
 *   part "links"   desktop menu: plain links + mega menus (top items that have sub items)
 *   part "drawer"  phone menu panel (opened by the burger), ends with the call-to-action button
 *   part "toggle"  the burger button (phones and tablets)
 *   part "icons"   cart + my account icon buttons (Customize → Notifima → General links; WooCommerce cart count when active)
 * Links: Appearance → Menus → "Notifima header" (see inc/header-footer.php), else data/menu.json.
 * Markup and classes are the original site's (.nav, .nav-item, .has-mega, .mega, .drawer …), styled by assets/css/nf-site.css.
 *
 * @var array $attributes
 * @package Notifima
 */

defined( 'ABSPATH' ) || exit;

$part = $attributes['part'] ?? 'links';
$data = nf_data( 'menu' );

if ( 'toggle' === $part ) {
	echo '<button ' . get_block_wrapper_attributes( array( 'class' => 'ico-btn burger' ) ) . ' type="button" aria-label="' . esc_attr__( 'Open menu', 'notifima' ) . '" aria-expanded="false">' . nf_icon( 'menu' ) . '</button>'; // phpcs:ignore
	return;
}

if ( 'icons' === $part ) {
	$count = ( function_exists( 'WC' ) && WC()->cart ) ? (int) WC()->cart->get_cart_contents_count() : 0;
	$cart  = function_exists( 'wc_get_cart_url' ) && '#' === nf_opt( 'nf_url_cart' ) ? wc_get_cart_url() : nf_link( 'cart' );
	$acct  = function_exists( 'wc_get_page_permalink' ) && '#' === nf_opt( 'nf_url_account' ) ? wc_get_page_permalink( 'myaccount' ) : nf_link( 'account' );
	// WooCommerce: the cart icon opens a mini cart (remove items, subtotal, checkout), kept up to date by WooCommerce's cart fragments.
	$mini = '';
	if ( function_exists( 'woocommerce_mini_cart' ) && WC()->cart ) {
		ob_start();
		woocommerce_mini_cart();
		$mini = '<div class="nf-mini" role="dialog" aria-label="' . esc_attr__( 'Cart', 'notifima' ) . '"><div class="nf-mini-h">' . esc_html__( 'Your cart', 'notifima' ) . '</div><div class="widget_shopping_cart"><div class="widget_shopping_cart_content">' . ob_get_clean() . '</div></div></div>';
	}
	echo '<div ' . get_block_wrapper_attributes( array( 'class' => 'nf-icons' ) ) . '>' // phpcs:ignore
		. ( $mini ? '<div class="nf-cart">' : '' )
		. '<a class="ico-btn' . ( $mini ? ' nf-cart-btn' : '' ) . '" href="' . esc_url( $cart ) . '" aria-label="' . esc_attr__( 'Cart', 'notifima' ) . '"' . ( $mini ? ' aria-haspopup="dialog" aria-expanded="false"' : '' ) . '>' . nf_icon( 'cart' ) . '<span class="cnt nf-cart-count">' . $count . '</span></a>' // phpcs:ignore
		. ( $mini ? $mini . '</div>' : '' ) // phpcs:ignore
		. '<a class="ico-btn" href="' . esc_url( $acct ) . '" aria-label="' . esc_attr__( 'My account', 'notifima' ) . '">' . nf_icon( 'user' ) . '</a></div>'; // phpcs:ignore
	return;
}

$tree = nf_menu_tree();

/** The mega panel of one top item: its synced pattern "Site · Mega menu · TITLE" (edit it in Kadence), else built from the menu. */
$mega = function ( $item ) use ( $data ) {
	$slug = 'mega-' . sanitize_title( $item['title'] );
	if ( function_exists( 'nf_synced_post' ) && isset( nf_pattern_files()[ $slug ] ) ) {
		$post = nf_synced_post( $slug );
		$html = $post && 'publish' === $post->post_status ? $post->post_content : nf_pattern_markup( $slug );
		if ( '' !== trim( $html ) ) {
			return '<div class="mega mega-k">' . do_shortcode( do_blocks( $html ) ) . '</div>';
		}
	}
	$f     = nf_mega_feature();
	$feat  = '<div class="mg-feat"><div class="mg-mini" aria-hidden="true"><div class="row">' . nf_icon( 'file' ) . ' ' . esc_html( $f['row1'] ?? '' ) . '</div><div class="row">' . nf_icon( 'clock' ) . ' ' . esc_html( $f['row2'] ?? '' ) . '</div></div>'
		. '<b>' . esc_html( $f['title'] ?? '' ) . '</b><p>' . esc_html( $f['text'] ?? '' ) . '</p><a class="link" href="' . esc_url( $f['url'] ?? '#' ) . '">' . esc_html( $f['link_text'] ?? '' ) . ' ' . nf_icon( 'arrow' ) . '</a></div>';
	$cols  = '';
	foreach ( $item['children'] as $group ) {
		$links = '';
		$list  = $group['children'] ? $group['children'] : array( $group );
		foreach ( $list as $l ) {
			$links .= '<a class="mm" href="' . esc_url( $l['url'] ) . '"><span class="mi">' . nf_icon( $l['icon'] ? $l['icon'] : 'arrow' ) . '</span><span><b>' . esc_html( $l['title'] ) . '</b>' . ( $l['desc'] ? '<small>' . esc_html( $l['desc'] ) . '</small>' : '' ) . '</span></a>';
		}
		$cols .= '<div class="mg-group">' . ( $group['children'] ? '<h6>' . esc_html( $group['title'] ) . '</h6>' : '' ) . $links . '</div>';
	}
	$foot = '';
	if ( ! empty( $data['foot'] ) ) {
		$foot = '<div class="mg-foot"><span>' . wp_kses( $data['foot']['text'], array( 'b' => array() ) ) . '</span><a class="link" href="' . esc_url( nf_menu_url( $data['foot']['url'] ) ) . '">' . esc_html( $data['foot']['link_text'] ) . ' ' . nf_icon( 'arrow' ) . '</a></div>';
	}
	$three = count( $item['children'] ) >= 3 ? ' three' : '';
	return '<div class="mega"><div class="mega-in">' . $feat . '<div class="mg-cols' . $three . '">' . $cols . $foot . '</div></div></div>';
};

if ( 'drawer' === $part ) {
	$out = '';
	foreach ( $tree as $item ) {
		if ( $item['children'] ) {
			$links = '';
			foreach ( $item['children'] as $group ) {
				foreach ( ( $group['children'] ? $group['children'] : array( $group ) ) as $l ) {
					$links .= '<a href="' . esc_url( $l['url'] ) . '">' . esc_html( $l['title'] ) . '</a>';
				}
			}
			if ( ! empty( $data['foot'] ) ) {
				$links .= '<a href="' . esc_url( nf_menu_url( $data['foot']['url'] ) ) . '">' . esc_html__( 'Live Demo', 'notifima' ) . '</a>';
			}
			$out .= '<div class="dr-sec"><button class="dr-h" type="button">' . esc_html( $item['title'] ) . ' ' . nf_icon( 'chev' ) . '</button><div class="dr-b">' . $links . '</div></div>';
		} else {
			$out .= '<a class="dr-l" href="' . esc_url( $item['url'] ) . '">' . esc_html( $item['title'] ) . '</a>';
		}
	}
	$cta  = $data['cta'] ?? array( 'text' => 'Contact Sales', 'url' => '@contact' );
	$text = '' !== ( $attributes['ctaText'] ?? '' ) ? $attributes['ctaText'] : $cta['text'];
	$out .= '<a class="btn btn-p" href="' . esc_url( nf_menu_url( $cta['url'] ) ) . '">' . esc_html( $text ) . '</a>';
	echo '<div ' . get_block_wrapper_attributes( array( 'class' => 'drawer' ) ) . ' aria-hidden="true">' . $out . '</div>'; // phpcs:ignore
	return;
}

// links
$out = '';
foreach ( $tree as $item ) {
	$on = nf_menu_is_current( $item['url'], $item['children'] ) ? ' on' : '';
	if ( $item['children'] ) {
		$out .= '<div class="nav-item has-mega"><button class="nav-link' . $on . '" type="button" aria-expanded="false">' . esc_html( $item['title'] ) . ' ' . nf_icon( 'chev' ) . '</button>' . $mega( $item ) . '</div>';
	} else {
		$out .= '<div class="nav-item"><a class="nav-link' . $on . '" href="' . esc_url( $item['url'] ) . '">' . esc_html( $item['title'] ) . '</a></div>';
	}
}
echo '<nav ' . get_block_wrapper_attributes( array( 'class' => 'nav' ) ) . ' aria-label="' . esc_attr__( 'Main', 'notifima' ) . '">' . $out . '</nav>'; // phpcs:ignore
