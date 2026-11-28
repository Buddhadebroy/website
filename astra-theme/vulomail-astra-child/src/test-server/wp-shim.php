<?php
/**
 * Minimal WordPress function shims so the theme's PHP (parts, helpers, menus) runs outside WordPress.
 * Test tooling only: not loaded by the theme.
 */
define( 'ABSPATH', __DIR__ . '/' );
$GLOBALS['vm_theme_dir'] = dirname( __DIR__, 2 ) . '/';
$GLOBALS['vm_home']      = 'http://127.0.0.1:8090';
$GLOBALS['vm_mods']      = array( 'custom_logo' => 1 );
function trailingslashit( $s ) { return rtrim( $s, '/\\' ) . '/'; }
function untrailingslashit( $s ) { return rtrim( (string) $s, '/\\' ); }
function get_stylesheet_directory() { return rtrim( $GLOBALS['vm_theme_dir'], '/' ); }
function get_stylesheet_directory_uri() { return '/theme'; }
function home_url( $p = '' ) { return $GLOBALS['vm_home'] . ( $p ? '/' . ltrim( $p, '/' ) : '' ); }
function esc_html( $s ) { return htmlspecialchars( (string) $s, ENT_QUOTES, 'UTF-8' ); }
function esc_attr( $s ) { return htmlspecialchars( (string) $s, ENT_QUOTES, 'UTF-8' ); }
function esc_url( $s ) { return htmlspecialchars( (string) $s, ENT_QUOTES, 'UTF-8' ); }
function esc_url_raw( $s ) { return (string) $s; }
function wp_unslash( $s ) { return $s; }
function __( $s ) { return $s; }
function esc_html__( $s ) { return esc_html( $s ); }
function esc_attr__( $s ) { return esc_attr( $s ); }
function sanitize_key( $s ) { return preg_replace( '/[^a-z0-9_\-]/', '', strtolower( (string) $s ) ); }
function wp_parse_url( $u, $c = -1 ) { return parse_url( $u, $c ); }
function add_query_arg( $a = array() ) { return $_SERVER['REQUEST_URI'] ?? '/'; }
function get_bloginfo( $k ) { return 'name' === $k ? 'VuloMail' : ''; }
function get_theme_mod( $k, $d = false ) { return array_key_exists( $k, $GLOBALS['vm_mods'] ) ? $GLOBALS['vm_mods'][ $k ] : $d; }
function get_nav_menu_locations() { return array(); }
function wp_get_attachment_image( $id, $size = '', $icon = false, $attr = array() ) { $h = ''; foreach ( $attr as $k => $v ) { $h .= ' ' . $k . '="' . esc_attr( $v ) . '"'; } return '<img src="/theme/assets/img/brand-icon.svg" width="1402" height="940"' . $h . '>'; }
function wp_json_encode( $d, $f = 0 ) { return json_encode( $d, $f ); }
function wp_list_pluck( $l, $f ) { return array_map( function ( $x ) use ( $f ) { return $x[ $f ]; }, $l ); }
function wp_strip_all_tags( $s ) { return strip_tags( (string) $s ); }
function is_wp_error( $x ) { return false; }
function add_action() {}
function add_filter() {}
function register_nav_menus() {}
function wp_nonce_field() { echo '<input type="hidden" name="vm_contact_nonce" value="test">'; }
function admin_url( $p = '' ) { return home_url( 'wp-admin/' . $p ); }
function selected( $a, $b = true ) { if ( $a === $b ) { echo ' selected="selected"'; } }
function esc_textarea( $s ) { return esc_html( $s ); }
