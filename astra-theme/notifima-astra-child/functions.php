<?php
/**
 * Notifima Astra Child – bootstrap.
 *
 *   inc/              setup, options, design tokens (root-vars), synced patterns, header + footer, blocks, admin screen
 *   graphic/          the Animated Graphic (Notifima) block + parts/ (every mockup, demo and picture of the site)
 *   blocks/           Site Logo (Notifima) and Site Menu (Notifima) for the header and footer
 *   pattern-files/    originals of the synced patterns (every section, every page, header, footer) – made by src/build-patterns.py
 *   page-templates/   Notifima Canvas (Kadence): empty full-width page with the Notifima header and footer
 *   template-parts/   blog/ (single post + blog index)
 *   assets/           CSS (nf-site.css = the design, nf-kadence.css = Kadence wrappers), JS, fonts, images
 *   data/             pages.json (which sections make each page), tokens.json (design tokens), menu.json (default menu)
 *   src/              build-patterns.py (site HTML → Kadence patterns + CSS), site/ (the original Notifima site)
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
foreach ( array( 'inc/helpers.php', 'inc/customizer.php', 'inc/root-vars.php', 'inc/setup.php', 'inc/patterns.php', 'inc/header-footer.php', 'inc/blocks.php', 'inc/releases.php', 'inc/woocommerce.php', 'inc/docs.php', 'inc/release-pro.php', 'inc/admin.php', 'graphic/graphic.php' ) as $nf_file ) {
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
