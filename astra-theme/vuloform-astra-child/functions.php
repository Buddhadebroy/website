<?php
/**
 * VuloForm Astra Child – bootstrap.
 *
 *   inc/              site code: setup, design tokens (root-vars), header + footer (Kadence synced patterns), shortcodes, blocks, admin screen
 *   graphic/          the animated graphics (builder app, demos, cost chart …) + the Animated Graphic block
 *   template-parts/   Home sections (home/), shared pieces (shared/), blog pieces (blog/)
 *   page-templates/   Home, Compare (all plugins) and Compare (one plugin)
 *   blocks/           the VuloForm blocks: sections, Site Logo, Site Menu (Inserter → VuloForm)
 *   pattern-files/    originals of the synced patterns (every section, page, header, footer) – see inc/patterns.php
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

// Helpers and data.
require VF_DIR . '/inc/helpers.php';
require VF_DIR . '/inc/content-data.php';
require VF_DIR . '/inc/template-helpers.php';

// Theme setup, options and assets.
require VF_DIR . '/inc/customizer.php';
require VF_DIR . '/inc/root-vars.php';
require VF_DIR . '/inc/setup.php';

// Site parts.
require VF_DIR . '/inc/patterns.php';
require VF_DIR . '/inc/header-footer.php';
require VF_DIR . '/inc/shortcodes.php';
require VF_DIR . '/inc/blocks.php';
require VF_DIR . '/inc/admin.php';

// Animated graphics.
require VF_DIR . '/graphic/graphic.php';
