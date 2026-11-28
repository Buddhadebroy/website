<?php
/**
 * Home › Publish anywhere (port of components/Embed.tsx). Tabs with your own button row (.vf-tab-list) + copy button.
 *
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;

$tabs = vf_embed_tabs();
?>
<section class="vf-tabs vf-tabs-blur py-28 sm:py-36">
	<div class="mx-auto grid max-w-6xl items-center gap-12 px-4 sm:px-6 lg:grid-cols-2">
		<div class="vf-reveal">
			<?php echo vf_eyebrow( 'Publish anywhere', 'navy' ); // phpcs:ignore ?>
			<h2 class="mt-5 text-4xl leading-tight font-bold tracking-tight sm:text-5xl">One form.<br>Every place you need it.</h2>
			<p class="mt-5 text-lg text-ink-soft">Drop it on a WordPress page, or paste a tiny snippet into a site that doesn't run WordPress at all. Submissions always land back home.</p>
			<div class="vf-tab-list vf-tab-list-plain mt-8" role="tablist">
				<?php foreach ( $tabs as $t ) : ?>
				<button type="button" role="tab"><?php echo esc_html( $t[0] ); ?></button>
				<?php endforeach; ?>
			</div>
		</div>

		<div class="vf-reveal" style="--vf-delay:.1s"><?php echo vf_graphic( 'embed-code' ); // phpcs:ignore ?></div>
	</div>
</section>
