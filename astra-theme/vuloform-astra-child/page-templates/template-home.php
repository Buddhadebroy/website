<?php
/**
 * Template Name: VuloForm Home
 * Template Post Type: page
 *
 * The Home page, built from plain PHP section files (no React).
 * Each section is a file in template-parts/home/ – open it to change text, links, colours or classes.
 * Remove a line from $sections to hide a section, or reorder the lines to move one.
 * Anything typed in the page editor is shown below the sections (above the final call to action).
 *
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;

$sections = apply_filters(
	'vf_home_sections',
	array(
		'hero',       // template-parts/home/hero.php
		'templates',  // template-parts/home/templates.php   (screenshot 1: chip marquee)
		'builder',    // template-parts/home/builder.php     (screenshot 2: three panes, sticky steps)
		'logic',      // template-parts/home/logic.php
		'features',   // template-parts/home/features.php
		'compare',    // template-parts/home/compare.php     (screenshot 3: table with tabs)
		'embed',      // template-parts/home/embed.php
		'privacy',    // template-parts/home/privacy.php     (screenshot 4: giant scrolling words)
		'faq',        // template-parts/home/faq.php
	)
);

get_header();
?>
<main id="vf-main" class="vf-template-main">
	<div class="vf-scope vf-page vf-page-home">
		<?php
		vf_jsonld(
			array(
				'@context'            => 'https://schema.org',
				'@type'               => 'SoftwareApplication',
				'name'                => 'VuloForm',
				'applicationCategory' => 'BusinessApplication',
				'operatingSystem'     => 'WordPress',
				'description'         => 'VuloForm is a free drag-and-drop form builder for WordPress. Conditional logic, multi-step forms, calculations, webhooks and embeds — no account, no subscription, no limits.',
				'offers'              => array( '@type' => 'Offer', 'price' => '0', 'priceCurrency' => 'USD' ),
			)
		);
		foreach ( $sections as $section ) {
			vf_section( 'home', $section );
		}
		?>
	</div>
	<?php
	while ( have_posts() ) :
		the_post();
		if ( '' !== trim( get_the_content() ) ) :
			?>
			<div class="vf-template-extra entry-content"><?php the_content(); ?></div>
			<?php
		endif;
	endwhile;
	?>
	<div class="vf-scope"><?php vf_section( 'home', 'cta' ); ?></div>
</main>
<?php
get_footer();
