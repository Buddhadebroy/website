<?php
/**
 * VuloMail Astra child theme.
 *
 *   inc/              site code: helpers, content-data, root-vars, setup, customizer, blocks, patterns, header, footer
 *   inc/features/     one file per VuloMail-specific feature: menus.php, blog.php, contact.php
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

define( 'VM_VERSION', '1.3.0' );
define( 'VM_DIR', trailingslashit( get_stylesheet_directory() ) );
define( 'VM_URI', trailingslashit( get_stylesheet_directory_uri() ) );

// A missing file (for example after uploading only some changed files as a new theme) must not take the site down:
// load what is there and say in the admin what is missing.
$vm_missing = array();
$vm_files   = array(
	'inc/helpers.php',
	'inc/content-data.php',
	'inc/root-vars.php',
	'inc/customizer.php',
	'inc/setup.php',
	'inc/header.php',
	'inc/footer.php',
	'inc/blocks.php',
	'inc/patterns.php',
);
foreach ( (array) glob( VM_DIR . 'inc/features/*.php' ) as $vm_feature_file ) {
	$vm_files[] = 'inc/features/' . basename( $vm_feature_file );
}
foreach ( $vm_files as $vm_file ) {
	if ( is_readable( VM_DIR . $vm_file ) ) {
		require VM_DIR . $vm_file;
	} else {
		$vm_missing[] = $vm_file;
	}
}
if ( is_admin() ) {
	if ( is_readable( VM_DIR . 'inc/admin.php' ) ) {
		require VM_DIR . 'inc/admin.php';
	} else {
		$vm_missing[] = 'inc/admin.php';
	}
}
if ( $vm_missing ) {
	add_action(
		'admin_notices',
		function () use ( $vm_missing ) {
			echo '<div class="notice notice-error"><p><b>VuloMail theme:</b> these files are missing, so parts of the site may not work: <code>' . esc_html( implode( ', ', $vm_missing ) ) . '</code>. Upload the full theme zip again (Appearance → Themes → Add New → Upload Theme → "Replace current with uploaded").</p></div>';
		}
	);
}
