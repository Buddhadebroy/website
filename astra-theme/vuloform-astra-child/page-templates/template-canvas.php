<?php
/**
 * Template Name: VuloForm Canvas (Kadence)
 * Template Post Type: page, post
 *
 * Full-width empty page with the VuloForm header and footer: only what you build in the editor.
 * Use it with the Kadence patterns (Inserter → Patterns → VuloForm), e.g. "Page · Home (all sections)".
 * Full-width rows reach the screen edges; no Astra title, sidebar or boxed container.
 *
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="vf-main" class="vf-template-main vf-canvas">
	<?php
	while ( have_posts() ) :
		the_post();
		the_content();
	endwhile;
	?>
</main>
<?php
get_footer();
