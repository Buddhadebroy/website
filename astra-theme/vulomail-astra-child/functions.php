<?php
/**
 * VuloMail Astra child theme.
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

define( 'VM_VERSION', '1.3.0' );
define( 'VM_DIR', trailingslashit( get_stylesheet_directory() ) );
define( 'VM_URI', trailingslashit( get_stylesheet_directory_uri() ) );

require VM_DIR . 'inc/helpers.php';
require VM_DIR . 'inc/content-data.php';
require VM_DIR . 'inc/root-vars.php';
require VM_DIR . 'inc/setup.php';
require VM_DIR . 'inc/customizer.php';
require VM_DIR . 'inc/blocks.php';
require VM_DIR . 'inc/patterns.php';
require VM_DIR . 'inc/header-footer.php';
require VM_DIR . 'inc/menus.php';
require VM_DIR . 'inc/blog.php';
require VM_DIR . 'inc/contact.php';

if ( is_admin() ) {
	require VM_DIR . 'inc/admin.php';
}
