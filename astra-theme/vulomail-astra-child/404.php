<?php
/**
 * 404: the "404 · Address unknown" pattern.
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

get_header();
echo '<main id="content" class="vm-main vm:relative">' . vm_render_pattern( '404-main' ) . '</main>'; // phpcs:ignore
get_footer();
