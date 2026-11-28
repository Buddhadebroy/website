<?php
/**
 * Template Name: Notifima Canvas (Kadence)
 * Template Post Type: page, post
 *
 * Full-width empty page with the Notifima header and footer: only what you build in the editor
 * (e.g. the synced pattern "Page · Home"). No Astra title, sidebar or boxed container.
 *
 * @package Notifima
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="nf-main" class="nf-main nf-canvas">
	<?php
	while ( have_posts() ) :
		the_post();
		the_content();
	endwhile;
	?>
</main>
<?php
get_footer();
