<?php
/**
 * Notifima Astra Child – bootstrap.
 *
 *   inc/              setup, options, design tokens (root-vars), synced patterns, header + footer, blocks, admin screen
 *   inc/features/     one file per Notifima-specific feature: woocommerce.php, docs.php, releases.php, release-pro.php
 *   graphic/          the Animated Graphic (Notifima) block + parts/ (every mockup, demo and picture of the site)
 *   blocks/           Site Logo (Notifima) and Site Menu (Notifima) for the header and footer
 *   patterns/    originals of the synced patterns (every section, every page, header, footer) – now maintained directly (the original generator, src/build-patterns.py, has been removed)
 *   page-templates/   Notifima Canvas (Kadence): empty full-width page with the Notifima header and footer
 *   template-parts/   blog/ (single post + blog index)
 *   assets/           CSS (nf-site.css = the design, nf-kadence.css = Kadence wrappers), JS, fonts, images
 *   data/             pages.json (which sections make each page), tokens.json (design tokens), menu.json (default menu)
 *   style.css         theme header + your own custom CSS
 *
 * @package Notifima
 */

defined( 'ABSPATH' ) || exit;

define( 'NF_VERSION', '2.3.7' );
define( 'NF_DIR', get_stylesheet_directory() );
define( 'NF_URI', get_stylesheet_directory_uri() );

// A missing file (for example after uploading only some changed files as a new theme) must not take the site down:
// load what is there and say in the admin what is missing.
$nf_missing = array();
$nf_files   = array( 'inc/helpers.php', 'inc/customizer.php', 'inc/root-vars.php', 'inc/setup.php', 'inc/patterns.php', 'inc/header.php', 'inc/footer.php', 'inc/blocks.php' );
foreach ( (array) glob( NF_DIR . '/inc/features/*.php' ) as $nf_feature_file ) {
	$nf_files[] = 'inc/features/' . basename( $nf_feature_file );
}
$nf_files[] = 'inc/admin.php';
$nf_files[] = 'graphic/graphic.php';
foreach ( $nf_files as $nf_file ) {
	if ( is_readable( NF_DIR . '/' . $nf_file ) ) {
		require NF_DIR . '/' . $nf_file;
	} else {
		$nf_missing[] = $nf_file;
	}
}
if ( $nf_missing ) {
	add_action(
		'admin_notices',
		function () use ( $nf_missing ) {
			echo '<div class="notice notice-error"><p><b>Notifima theme:</b> these files are missing, so parts of the site may not work: <code>' . esc_html( implode( ', ', $nf_missing ) ) . '</code>. Upload the full theme zip again (Appearance → Themes → Add New → Upload Theme → "Replace current with uploaded").</p></div>';
		}
	);
}
