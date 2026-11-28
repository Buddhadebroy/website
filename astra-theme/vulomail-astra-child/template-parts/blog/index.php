<?php
/**
 * Blog index (posts page, categories, tags), like the original /blog.
 * The hero and the call to action are the "Blog · Hero" and "Home · Call to action" patterns.
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

get_header();
$vm_posts = array();
while ( have_posts() ) {
	the_post();
	$vm_posts[] = vm_post_array( get_the_ID() );
}
echo '<main id="content" class="vm-main vm:relative">';
echo vm_blog_index_html( $vm_posts, vm_render_pattern( 'blog-hero' ), vm_render_pattern( 'shared-cta' ) ); // phpcs:ignore
the_posts_pagination();
echo '</main>';
get_footer();
