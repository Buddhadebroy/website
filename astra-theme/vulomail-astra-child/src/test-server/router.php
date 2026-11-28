<?php
/**
 * Test server: renders the theme's patterns like WordPress + Kadence would, for side-by-side measurement.
 *   php -S 127.0.0.1:8090 src/test-server/router.php
 * /            → page-home     /p/<page>  → page-<page>     /s/<pattern> → one pattern
 * /theme/...   → theme files   ?hostile=0 → without the Astra-like CSS
 */
$uri  = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
$root = dirname( __DIR__, 2 );
if ( 0 === strpos( $uri, '/theme/' ) ) {
	$f = realpath( $root . substr( $uri, 6 ) );
	if ( $f && 0 === strpos( $f, $root ) && is_file( $f ) ) {
		$types = array( 'css' => 'text/css', 'js' => 'application/javascript', 'svg' => 'image/svg+xml', 'png' => 'image/png', 'json' => 'application/json' );
		header( 'Content-Type: ' . ( $types[ pathinfo( $f, PATHINFO_EXTENSION ) ] ?? 'application/octet-stream' ) );
		readfile( $f );
		return true;
	}
	http_response_code( 404 );
	return true;
}
if ( 0 === strpos( $uri, '/raw-css/' ) ) {
	header( 'Content-Type: text/css' );
	readfile( dirname( __DIR__ ) . '/.raw-css/' . basename( $uri ) );
	return true;
}
if ( 0 === strpos( $uri, '/emu/' ) ) {
	header( 'Content-Type: text/css' );
	readfile( __DIR__ . '/' . basename( $uri ) );
	return true;
}
require __DIR__ . '/wp-shim.php';
define( 'VM_VERSION', 'test' );
define( 'VM_DIR', $root . '/' );
define( 'VM_URI', '/theme/' );
require VM_DIR . 'inc/helpers.php';
require VM_DIR . 'inc/content-data.php';
require VM_DIR . 'inc/menus.php';
require VM_DIR . 'graphic/shared.php';
function vm_render_graphic( $attributes ) {
	$part  = isset( $attributes['part'] ) ? sanitize_key( $attributes['part'] ) : '';
	$props = isset( $attributes['props'] ) && is_array( $attributes['props'] ) ? $attributes['props'] : array();
	$class = 'vm-graphic vm-g-' . $part . ( ! empty( $attributes['className'] ) ? ' ' . $attributes['className'] : '' );
	return '<div class="' . esc_attr( $class ) . '">' . vm_part( $part, $props ) . '</div>';
}
require __DIR__ . '/blocks-render.php';
function vm_render_site_logo( $a ) { return '<div class="' . esc_attr( vm_cx( 'vm-site-logo', $a['className'] ?? '' ) ) . '">' . vm_part( 'site-logo', array( 'link' => ! isset( $a['link'] ) || $a['link'] ) ) . '</div>'; }
function vm_render_site_menu( $a ) { $parts = array( 'header' => 'nav-desktop', 'phone' => 'menu-toggle', 'footer' => 'footer-links' ); $v = isset( $a['variant'], $parts[ $a['variant'] ] ) ? $a['variant'] : 'header'; return '<div class="' . esc_attr( vm_cx( 'vm-site-menu vm-site-menu-' . $v, $a['className'] ?? '' ) ) . '">' . vm_part( $parts[ $v ] ) . '</div>'; }
require VM_DIR . 'inc/root-vars.php';

function vmt_pattern( $slug ) {
	$f = VM_DIR . 'pattern-files/' . $slug . '.html';
	return is_file( $f ) ? file_get_contents( $f ) : '';
}

$page = 'home';
if ( preg_match( '#^/p/([a-z0-9-]+)#', $uri, $m ) ) {
	$page = $m[1];
}
$single = preg_match( '#^/s/([a-z0-9-]+)#', $uri, $m ) ? $m[1] : '';
$body   = $single ? vmt_pattern( $single ) : vmt_pattern( 'page-' . $page );
if ( 0 === strpos( $uri, '/blog' ) ) {
	$body = '';
	require VM_DIR . 'inc/blog.php';
	require __DIR__ . '/blog.php'; // renders through the same functions as the theme templates
}
$main   = vmt_do_blocks( $body );
$header = vmt_do_blocks( vmt_pattern( 'header' ) );
$footer = vmt_do_blocks( vmt_pattern( 'footer' ) );
if ( isset( $GLOBALS['vmt_template'] ) ) {
	$main = $GLOBALS['vmt_template'];
}
$hostile = ! isset( $_GET['hostile'] ) || '0' !== $_GET['hostile'];
$vmt_css_base = isset( $_GET['css'] ) && 'raw' === $_GET['css'] ? '/raw-css/' : '/theme/assets/css/';
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script>document.documentElement.classList.add("vm-js");</script>
<title>VuloMail theme test · <?php echo esc_html( $single ? $single : $page ); ?></title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Geist+Mono:wght@100..900&family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap">
<?php if ( $hostile ) : ?><link rel="stylesheet" href="/emu/astra-emu.css"><?php endif; ?>
<link rel="stylesheet" href="<?php echo $vmt_css_base; ?>vm-tailwind.css">
<link rel="stylesheet" href="<?php echo $vmt_css_base; ?>vm-site.css">
<link rel="stylesheet" href="<?php echo $vmt_css_base; ?>vm-effects.css">
<link rel="stylesheet" href="<?php echo $vmt_css_base; ?>vm-kadence.css">
<style><?php echo vm_root_vars_css(); ?></style>
<link rel="stylesheet" href="/emu/kadence-emu.css">
<style><?php echo implode( "\n", $GLOBALS['vmt_css'] ); ?></style>
</head>
<body class="vm-tw vm-canvas vm-has-header page">
<div aria-hidden="true" class="vm:airmail vm-progress"></div>
<div id="page" class="hfeed site">
<div class="vm-layout vm-layout-header"><?php echo $header; ?></div>
<div id="content" class="site-content"><div class="ast-container"><div id="primary" class="content-area primary"><?php if ( isset( $GLOBALS['vmt_template'] ) ) : ?><main id="main" class="site-main vm-main"><?php echo $main; ?></main><?php else : ?><main id="main" class="site-main"><article class="page type-page ast-article-single"><div class="vm:entry-content clear" ast-blocks-layout="true" itemprop="text"><?php echo $main; ?></div></article></main><?php endif; ?></div></div></div>
<div class="vm-layout vm-layout-footer"><?php echo $footer; ?></div>
</div>
<script>window.vmConfig={smooth:true,progress:true};</script>
<script src="/theme/assets/js/vendor/lenis.min.js"></script>
<script src="/theme/assets/js/vm-effects.js"></script>
<script src="/theme/assets/js/vm-site.js"></script>
</body>
</html>
