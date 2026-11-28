<?php
/**
 * Home › How it compares (port of components/Compare.tsx). Screenshot 3.
 * Also the block "Comparison Table with Tabs (VuloForm)". Text: vf_section_defaults( 'compare' ). Facts: data/compare.json.
 * Tabs: .vf-tabs > .vf-tab (each with a .vf-tab-label); the JS builds the pill buttons.
 *
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;

$data = vf_compare();
$a    = vf_section_args( 'compare', $args ?? array() );
?>
<section id="compare" class="relative isolate py-28 sm:py-36">
	<div class="absolute inset-x-0 top-40 -z-10 mx-auto h-[500px] max-w-5xl rounded-full bg-sky-soft/60 blur-3xl"></div>
	<div class="mx-auto max-w-6xl px-4 sm:px-6">
		<?php if ( $a['showHeading'] ) : ?>
		<div class="vf-reveal max-w-3xl">
			<?php echo vf_eyebrow( $a['eyebrow'], 'navy' ); // phpcs:ignore ?>
			<h2 class="mt-5 text-4xl leading-tight font-bold tracking-tight sm:text-5xl"><?php echo esc_html( $a['title'] ); ?> <span class="text-navy"><?php echo esc_html( $a['highlight'] ); ?></span></h2>
			<p class="mt-5 text-lg text-ink-soft"><?php echo esc_html( $a['intro'] ); ?></p>
		</div>
		<?php endif; ?>

		<div class="vf-tabs <?php echo $a['showHeading'] ? 'mt-10' : ''; ?>">
			<div class="vf-tab">
				<p class="vf-tab-label"><?php echo esc_html( $a['tab1'] ); ?></p>
				<?php echo vf_feature_table(); // phpcs:ignore ?>
			</div>
			<div class="vf-tab">
				<p class="vf-tab-label"><?php echo esc_html( $a['tab2'] ); ?></p>
				<?php echo vf_cost_chart(); // phpcs:ignore ?>
			</div>
		</div>

		<?php if ( $a['showCards'] ) : ?>
		<!-- honest take -->
		<div class="mt-8 grid gap-5 md:grid-cols-2">
			<div class="vf-reveal rounded-[28px] bg-mint-soft p-7">
				<h3 class="text-xl font-bold">Where VuloForm stands out</h3>
				<ul class="mt-4 space-y-3 text-[15px] leading-relaxed text-ink">
					<li>✓ Every feature is in the free plugin — there is no paid tier to upsell you to.</li>
					<li>✓ Conditional logic and calculations are enforced again on your server, not just in the browser.</li>
					<li>✓ Webhooks are signed, retried twice and logged on each submission — free.</li>
					<li>✓ Spam protection needs no CAPTCHA and sends no visitor data to another company.</li>
					<li>✓ No account, no telemetry, and IP addresses aren't stored unless you switch it on.</li>
				</ul>
			</div>
			<div class="vf-reveal rounded-[28px] border border-line bg-white p-7" style="--vf-delay:.08s">
				<h3 class="text-xl font-bold">Where others are stronger</h3>
				<ul class="mt-4 space-y-3 text-[15px] leading-relaxed text-ink-soft">
					<li>• <b class="text-ink">Payments.</b> VuloForm doesn't take payments. WPForms, Formidable, Fluent Forms and Forminator include Stripe for free.</li>
					<li>• <b class="text-ink">Integrations.</b> The paid tiers of WPForms, Gravity Forms and others connect directly to CRMs, email-marketing tools and Zapier. VuloForm offers webhooks.</li>
					<li>• <b class="text-ink">Track record.</b> VuloForm is at version 1.0. The others have years of releases and large communities behind them.</li>
					<li>• <b class="text-ink">Specialist extras.</b> Things like quizzes, e-signatures, PDF generation and user registration are available elsewhere.</li>
				</ul>
			</div>
		</div>

		<?php endif; ?>

		<?php if ( $a['showLinks'] ) : ?>
		<!-- detailed head-to-head pages -->
		<div class="vf-reveal mt-10 rounded-[28px] border border-line bg-white p-7 sm:p-8">
			<div class="flex flex-wrap items-end justify-between gap-4">
				<div>
					<h3 class="text-xl font-bold">Detailed head-to-head comparisons</h3>
					<p class="mt-1 text-[15px] text-ink-soft">One page per plugin: features, pricing, who each suits, and honest trade-offs.</p>
				</div>
				<a href="<?php echo esc_url( home_url( '/compare/' ) ); ?>" class="rounded-full bg-navy px-5 py-2.5 text-sm font-semibold text-white transition-all hover:-translate-y-0.5 hover:bg-navy-light">See the full comparison →</a>
			</div>
			<ul class="mt-6 grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
				<?php foreach ( $data['profiles'] as $p ) : ?>
				<li><a href="<?php echo esc_url( home_url( '/compare/' . $p['slug'] . '/' ) ); ?>" class="flex items-center justify-between rounded-2xl border border-line px-4 py-3 text-sm font-semibold text-ink transition-colors hover:border-navy/30 hover:bg-sky-soft">VuloForm vs <?php echo esc_html( $p['name'] ); ?><span aria-hidden="true" class="text-navy">→</span></a></li>
				<?php endforeach; ?>
			</ul>
		</div>

		<?php endif; ?>

		<?php if ( $a['showSources'] ) { vf_section( 'shared', 'sources', array( 'intro' => true ) ); } ?>
	</div>
</section>
