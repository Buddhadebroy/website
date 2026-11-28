<?php
/**
 * Template Name: VuloForm Compare (one plugin)
 * Template Post Type: page
 *
 * Port of app/compare/[slug]/page.tsx. Which plugin? The page slug (e.g. "wpforms" → /compare/wpforms/),
 * or a custom field "vf_plugin" with one of: contact-form-7, wpforms, gravity-forms, ninja-forms,
 * formidable-forms, fluent-forms, forminator. Text lives in data/compare.json → profiles.
 *
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;

$slug = get_post_meta( get_the_ID(), 'vf_plugin', true );
$p    = vf_profile( $slug ? $slug : get_post_field( 'post_name' ) );
$data = vf_compare();

get_header();
?>
<main id="vf-main" class="vf-template-main">
	<div class="vf-scope vf-page pt-28 sm:pt-36">
	<?php if ( ! $p ) : ?>
		<div class="mx-auto max-w-3xl px-4 py-20 text-center">
			<h1 class="text-3xl font-bold">Pick a plugin for this page</h1>
			<p class="mt-4 text-ink-soft">Give the page one of these slugs, or a custom field <code>vf_plugin</code>: <?php echo esc_html( implode( ', ', wp_list_pluck( $data['profiles'], 'slug' ) ) ); ?>.</p>
		</div>
	<?php else :
		$cost = current( array_filter( $data['costs'], function ( $c ) use ( $p ) { return $c['id'] === $p['id']; } ) );
		vf_jsonld(
			array(
				'@context'   => 'https://schema.org',
				'@type'      => 'FAQPage',
				'mainEntity' => array_map( function ( $f ) { return array( '@type' => 'Question', 'name' => $f[0], 'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $f[1] ) ); }, $p['faqs'] ),
			)
		);
		?>
		<div class="mx-auto max-w-6xl px-4 sm:px-6">
			<nav aria-label="Breadcrumb" class="text-sm text-ink-soft"><a href="<?php echo esc_url( home_url( '/compare/' ) ); ?>" class="text-ink-soft hover:text-ink">Compare</a> <span aria-hidden="true">/</span> VuloForm vs <?php echo esc_html( $p['name'] ); ?></nav>

			<div class="vf-reveal mt-6 max-w-3xl">
				<?php echo vf_eyebrow( 'Head-to-head', 'navy' ); // phpcs:ignore ?>
				<h1 class="mt-5 text-4xl leading-tight font-bold tracking-tight sm:text-5xl">VuloForm vs <?php echo esc_html( $p['name'] ); ?></h1>
				<p class="mt-5 text-lg text-ink-soft"><?php echo esc_html( $p['verdict'] ); ?></p>
			</div>

			<div class="mt-10 grid gap-5 md:grid-cols-2">
				<div class="vf-reveal rounded-[28px] bg-mint-soft p-7">
					<h2 class="text-xl font-bold">Choose VuloForm if…</h2>
					<ul class="mt-4 space-y-3 text-[15px] leading-relaxed"><?php foreach ( $p['chooseVulo'] as $t ) { echo '<li>✓ ' . esc_html( $t ) . '</li>'; } ?></ul>
				</div>
				<div class="vf-reveal rounded-[28px] border border-line bg-white p-7" style="--vf-delay:.08s">
					<h2 class="text-xl font-bold">Choose <?php echo esc_html( $p['name'] ); ?> if…</h2>
					<ul class="mt-4 space-y-3 text-[15px] leading-relaxed text-ink-soft"><?php foreach ( $p['chooseThem'] as $t ) { echo '<li>• ' . esc_html( $t ) . '</li>'; } ?></ul>
				</div>
			</div>

			<h2 class="mt-16 mb-5 text-2xl font-bold">Feature comparison</h2>
			<div class="vf-reveal"><?php echo vf_feature_table( array( 'vulo', $p['id'] ) ); // phpcs:ignore ?></div>

			<div class="vf-reveal mt-10 rounded-[28px] bg-butter-soft p-7">
				<h2 class="text-xl font-bold">Price</h2>
				<p class="mt-2 text-[15px] leading-relaxed"><?php echo esc_html( $p['pricing'] ); ?></p>
				<?php if ( $cost ) : ?><p class="mt-2 text-sm text-ink-soft"><?php echo esc_html( $cost['name'] . ' · ' . $cost['plan'] . ': ' . $cost['detail'] ); ?></p><?php endif; ?>
			</div>

			<div class="mt-14 max-w-3xl space-y-12">
				<?php foreach ( $p['sections'] as $s ) : ?>
				<section class="vf-reveal">
					<h2 class="text-2xl font-bold"><?php echo esc_html( $s['h'] ); ?></h2>
					<?php foreach ( $s['p'] as $para ) : ?><p class="mt-4 text-[17px] leading-relaxed text-ink-soft"><?php echo esc_html( $para ); ?></p><?php endforeach; ?>
				</section>
				<?php endforeach; ?>
			</div>

			<h2 class="mt-16 mb-5 text-2xl font-bold">How the cost compares across all plugins</h2>
			<?php echo vf_cost_chart(); // phpcs:ignore ?>

			<section class="mt-16 max-w-3xl">
				<h2 class="text-2xl font-bold">Frequently asked questions</h2>
				<dl class="mt-6 space-y-6">
					<?php foreach ( $p['faqs'] as $f ) : ?>
					<div><dt class="font-bold"><?php echo esc_html( $f[0] ); ?></dt><dd class="mt-1.5 ml-0 text-ink-soft"><?php echo esc_html( $f[1] ); ?></dd></div>
					<?php endforeach; ?>
				</dl>
			</section>

			<section class="mt-16">
				<h2 class="text-2xl font-bold">Compare with other plugins</h2>
				<ul class="mt-4 flex flex-wrap gap-2">
					<?php foreach ( $data['profiles'] as $o ) : if ( $o['slug'] === $p['slug'] ) { continue; } ?>
					<li><a href="<?php echo esc_url( home_url( '/compare/' . $o['slug'] . '/' ) ); ?>" class="inline-block rounded-full border border-line bg-white px-4 py-2 text-sm font-semibold text-ink transition-colors hover:border-navy/30 hover:bg-sky-soft">vs <?php echo esc_html( $o['name'] ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</section>

			<p class="mt-12 text-xs leading-relaxed text-ink-soft">
				Facts checked <?php echo esc_html( $data['checked_on'] ); ?> against each vendor's own pricing page and WordPress.org listing; columns describe the free version. Plans and prices change, so check before you buy. Product names are trademarks of their owners; this page isn't affiliated with or endorsed by them.
				<?php
				$src = $data['sources'][ $p['id'] ] ?? array();
				if ( $src ) {
					$links = array_map( function ( $s ) { return '<a href="' . esc_url( $s[1] ) . '" target="_blank" rel="noopener noreferrer" class="text-navy underline underline-offset-2">' . esc_html( $s[0] ) . '</a>'; }, $src );
					echo ' Sources: ' . implode( ', ', $links ) . '.'; // phpcs:ignore
				}
				?>
			</p>
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
		echo vf_get_it_banner( 'Ready to try VuloForm instead of ' . $p['name'] . '?' ); // phpcs:ignore
	endif;
	?>
	</div>
</main>
<?php
get_footer();
