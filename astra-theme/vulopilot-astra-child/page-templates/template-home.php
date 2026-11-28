<?php
/**
 * Template Name: VuloPilot Home
 * Template Post Type: page
 *
 * The Home page, built from plain PHP section files (no React).
 * Each section is a file in template-parts/home/ – open it to change text, links, colours or classes.
 * Remove a line from $sections to hide a section, or reorder the lines to move one.
 * Anything typed in the page editor is shown below the sections.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$sections = apply_filters(
	'vp_home_sections',
	array(
		'hero',          // template-parts/home/hero.php
		'problem',       // template-parts/home/problem.php
		'answers',       // template-parts/home/answers.php
		'how',           // template-parts/home/how.php
		'automation',    // template-parts/home/automation.php
		'ai',            // template-parts/home/ai.php
		'dashboard',     // template-parts/home/dashboard.php
		'product-tour',  // template-parts/home/product-tour.php
		'features',      // template-parts/home/features.php
		'wp-compare',    // template-parts/home/wp-compare.php
		'pricing',       // template-parts/home/pricing.php
		'blog-preview',  // template-parts/home/blog-preview.php
		'faq',           // template-parts/home/faq.php
		'start',         // template-parts/home/start.php
		'finale',        // template-parts/home/finale.php
	)
);

get_header();
?>
<main id="vp-main" class="vp-template-main">
	<div class="vp-scope vp-page vp-page-home">
		<?php
		vp_jsonld( vp_software_schema() );
		foreach ( $sections as $section ) {
			vp_section( 'home', $section );
		}
		?>
	</div>
	<?php
	while ( have_posts() ) :
		the_post();
		if ( '' !== trim( get_the_content() ) ) :
			?>
			<div class="vp-template-extra entry-content"><?php the_content(); ?></div>
			<?php
		endif;
	endwhile;
	?>
</main>
<?php
get_footer();
