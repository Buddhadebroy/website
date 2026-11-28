<?php
/**
 * VuloForm Astra Child – bootstrap.
 *
 *   inc/              site code: setup, design tokens (root-vars), header + footer (Kadence synced patterns), shortcodes, blocks, admin screen
 *   inc/features/     brand-specific one-off features, auto-loaded; empty for now
 *   graphic/          the animated graphics (builder app, demos, cost chart …) + the Animated Graphic block
 *   template-parts/   Home sections (home/), shared pieces (shared/), blog pieces (blog/)
 *   page-templates/   Home, Compare (all plugins) and Compare (one plugin)
 *   blocks/           the VuloForm blocks: sections, Site Logo, Site Menu (Inserter → VuloForm)
 *   patterns/    originals of the synced patterns (every section, page, header, footer) – see inc/patterns.php
 *   assets/           CSS + JS (Tailwind, site, effects), logo
 *   data/             compare.json (comparison facts), posts.json (blog posts), tokens.json (design tokens)
 *   style.css         theme header + your own custom CSS
 *
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;

define( 'VF_VERSION', '2.1.0' );
define( 'VF_DIR', get_stylesheet_directory() );
define( 'VF_URI', get_stylesheet_directory_uri() );

// A missing file (for example after uploading only some changed files as a new theme) must not take the site down:
// load what is there and say in the admin what is missing.
$vf_missing = array();
$vf_files   = array(
	'inc/helpers.php',
	'inc/content-data.php',
	'inc/template-helpers.php',
	'inc/root-vars.php',
	'inc/customizer.php',
	'inc/setup.php',
	'inc/header.php',
	'inc/footer.php',
	'inc/blocks.php',
	'inc/patterns.php',
	'inc/shortcodes.php',
);
foreach ( (array) glob( VF_DIR . '/inc/features/*.php' ) as $vf_feature_file ) {
	$vf_files[] = 'inc/features/' . basename( $vf_feature_file );
}
$vf_files[] = 'inc/admin.php';
$vf_files[] = 'graphic/graphic.php';
foreach ( $vf_files as $vf_file ) {
	if ( is_readable( VF_DIR . '/' . $vf_file ) ) {
		require VF_DIR . '/' . $vf_file;
	} else {
		$vf_missing[] = $vf_file;
	}
}
if ( $vf_missing ) {
	add_action(
		'admin_notices',
		function () use ( $vf_missing ) {
			echo '<div class="notice notice-error"><p><b>VuloForm theme:</b> these files are missing, so parts of the site may not work: <code>' . esc_html( implode( ', ', $vf_missing ) ) . '</code>. Upload the full theme zip again (Appearance → Themes → Add New → Upload Theme → "Replace current with uploaded").</p></div>';
		}
	);
}
