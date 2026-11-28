<?php
/**
 * Template Name: VuloForm Compare (all plugins)
 * Template Post Type: page
 *
 * Port of app/compare/page.tsx: full table, cost chart, links to every head-to-head page.
 * Facts come from data/compare.json. Anything typed in the page editor is shown under the links.
 *
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;

$data = vf_compare();
get_header();
?>
<main id="vf-main" class="vf-template-main">
	<div class="vf-scope vf-page pt-28 sm:pt-36">
		<div class="mx-auto max-w-6xl px-4 sm:px-6">
			<div class="vf-reveal max-w-3xl">
				<?php echo vf_eyebrow( 'Comparison', 'navy' ); // phpcs:ignore ?>
				<h1 class="mt-5 text-4xl leading-tight font-bold tracking-tight sm:text-5xl">VuloForm vs the other WordPress form plugins</h1>
				<p class="mt-5 text-lg text-ink-soft">Seven widely used plugins, compared using each maker's own pricing page and WordPress.org listing (checked <?php echo esc_html( $data['checked_on'] ); ?>). Columns describe the free version; where a feature is paid we name the cheapest plan.</p>
			</div>

			<h2 class="mt-14 mb-5 text-2xl font-bold">Feature by feature</h2>
			<div class="vf-reveal"><?php echo vf_feature_table(); // phpcs:ignore ?></div>

			<h2 class="mt-14 mb-5 text-2xl font-bold">What it costs</h2>
			<div class="vf-reveal"><?php echo vf_cost_chart(); // phpcs:ignore ?></div>

			<h2 class="mt-14 text-2xl font-bold">Pick a head-to-head</h2>
			<p class="mt-2 text-ink-soft">Each page covers features, pricing, who the plugin suits, and where it beats VuloForm.</p>
			<ul class="vf-stagger mt-6 grid gap-4 md:grid-cols-2">
				<?php foreach ( $data['profiles'] as $p ) : ?>
				<li>
					<a href="<?php echo esc_url( home_url( '/compare/' . $p['slug'] . '/' ) ); ?>" class="group block h-full rounded-[24px] border border-line bg-white p-6 text-ink transition-all hover:-translate-y-0.5 hover:border-navy/30 hover:shadow-lg">
						<span class="flex items-center justify-between text-lg font-bold">VuloForm vs <?php echo esc_html( $p['name'] ); ?><span aria-hidden="true" class="text-navy transition-transform group-hover:translate-x-1">→</span></span>
						<span class="mt-2 block text-[15px] leading-relaxed text-ink-soft"><?php echo esc_html( $p['tagline'] ); ?></span>
					</a>
				</li>
				<?php endforeach; ?>
			</ul>

			<?php vf_section( 'shared', 'sources' ); ?>
		</div>
		<?php
		while ( have_posts() ) :
			the_post();
			if ( '' !== trim( get_the_content() ) ) {
				echo '<div class="vf-template-extra entry-content mx-auto mt-14 max-w-6xl px-4 sm:px-6">';
				the_content();
				echo '</div>';
			}
		endwhile;
		echo vf_get_it_banner(); // phpcs:ignore
		?>
	</div>
</main>
<?php
get_footer();
