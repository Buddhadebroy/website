<?php
/**
 * VuloPilot Astra Child – bootstrap.
 *
 *   inc/              site code: setup, header, footer, popup, blocks, admin screen
 *   inc/features/     one file per VuloPilot-specific feature: consult.php
 *   graphic/          everything for the animated graphics (see graphic/graphic.php)
 *   style.css         the site's custom CSS (table of contents at the top)
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

define( 'VP_VERSION', '2.7.0' );
define( 'VP_DIR', get_stylesheet_directory() );
define( 'VP_URI', get_stylesheet_directory_uri() );

// A missing file (for example after uploading only some changed files as a new theme) must not take the site down:
// load what is there and say in the admin what is missing.
$vp_missing = array();
$vp_files   = array(
	'inc/helpers.php',
	'inc/content-data.php',
	'inc/template-helpers.php',
	'inc/menu-data.php',
	'inc/customizer.php',
	'inc/setup.php',
	'inc/header.php',
	'inc/footer.php',
	'inc/shortcodes.php',
	'inc/blocks.php',
);
foreach ( (array) glob( VP_DIR . '/inc/features/*.php' ) as $vp_feature_file ) {
	$vp_files[] = 'inc/features/' . basename( $vp_feature_file );
}
$vp_files[] = 'inc/admin.php';
$vp_files[] = 'graphic/graphic.php';
foreach ( $vp_files as $vp_file ) {
	if ( is_readable( VP_DIR . '/' . $vp_file ) ) {
		require VP_DIR . '/' . $vp_file;
	} else {
		$vp_missing[] = $vp_file;
	}
}
if ( $vp_missing ) {
	add_action(
		'admin_notices',
		function () use ( $vp_missing ) {
			echo '<div class="notice notice-error"><p><b>VuloPilot theme:</b> these files are missing, so parts of the site may not work: <code>' . esc_html( implode( ', ', $vp_missing ) ) . '</code>. Upload the full theme zip again (Appearance → Themes → Add New → Upload Theme → "Replace current with uploaded").</p></div>';
		}
	);
}
