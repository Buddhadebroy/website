<?php
/**
 * Home › Hero (port of components/Hero.tsx).
 * Effects: vf-words (headline rises word by word), vf-exit-* (parallax while scrolling away), vf-tilt (follows the pointer),
 * vf-float (loops). The typing demo is data-vf="hero-form" in assets/js/vf-site.js.
 *
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;

$stats = array( array( '21', 'field types' ), array( '10', 'ready-made forms' ), array( '0', 'trackers or telemetry' ) );
?>
<section id="top" class="vf-hero relative isolate overflow-hidden pt-32 pb-24 sm:pt-40 lg:pb-36" data-tilt-area>
	<!-- background blobs (move down slower than the page) -->
	<div class="vf-exit-down pointer-events-none absolute inset-0 -z-10" style="--vf-move:120px" aria-hidden="true">
		<div class="dotted absolute inset-0 [mask-image:radial-gradient(ellipse_at_center,black_30%,transparent_75%)]"></div>
		<div class="absolute -top-32 -left-24 h-[520px] w-[520px] rounded-full bg-sky-soft blur-3xl"></div>
		<div class="absolute top-20 right-[-120px] h-[460px] w-[460px] rounded-full bg-mint-soft blur-3xl"></div>
		<div class="absolute bottom-[-160px] left-1/3 h-[420px] w-[420px] rounded-full bg-peach-soft blur-3xl"></div>
	</div>

	<div class="mx-auto grid max-w-6xl items-center gap-16 px-4 sm:px-6 lg:grid-cols-[1.05fr_1fr] lg:gap-10">
		<div class="vf-exit-down vf-exit-fade" style="--vf-move:140px">
			<a href="#privacy" class="vf-reveal vf-fast group inline-flex items-center gap-2 rounded-full border border-line bg-white/70 py-1.5 pr-4 pl-1.5 text-sm text-ink-soft backdrop-blur">
				<span class="rounded-full bg-mint px-2.5 py-0.5 text-xs font-bold text-white">Free</span>
				No account · No subscription · No limits
				<span class="transition-transform group-hover:translate-x-0.5">→</span>
			</a>

			<h1 class="mt-7 text-[clamp(2.5rem,5.6vw,4.6rem)] leading-[1.02] font-bold tracking-[-0.02em] text-ink">
				<span class="vf-words" style="--vf-delay:.15s">Every form your website needs.</span>
				<span class="vf-words block text-navy" style="--vf-delay:.6s">One <span class="relative inline-block">simple<svg viewBox="0 0 300 20" class="absolute -bottom-1 left-0 h-3 w-full" preserveAspectRatio="none" aria-hidden="true"><path class="vf-draw" style="--vf-delay:1.2s" d="M2 14 C 60 4, 140 4, 298 12" fill="none" style="stroke:var(--mint)" stroke-width="6" stroke-linecap="round" vector-effect="non-scaling-stroke"/></svg></span> builder.</span>
			</h1>

			<p class="vf-reveal mt-7 max-w-xl text-lg leading-relaxed text-ink-soft" style="--vf-delay:.9s">VuloForm is a drag-and-drop form builder for WordPress. Build a form, publish it, drop it on a page — and read what people send, all inside your own site.</p>

			<div class="vf-reveal mt-9 flex flex-wrap items-center gap-3" style="--vf-delay:1.05s">
				<a href="<?php echo esc_url( vf_download_url() ); ?>" class="vf-shine group relative inline-flex items-center gap-2 overflow-hidden rounded-full bg-navy px-7 py-4 font-semibold text-white shadow-[0_18px_40px_-16px_rgba(0,75,242,0.7)] transition-all hover:-translate-y-0.5 hover:bg-navy-light">Download VuloForm <span aria-hidden="true">↓</span></a>
				<a href="#builder" class="inline-flex items-center gap-2 rounded-full border border-line bg-white px-6 py-4 font-semibold text-ink transition-colors hover:border-navy/30">See how it works</a>
			</div>

			<dl class="vf-reveal-fade mt-12 flex flex-wrap gap-x-10 gap-y-4 text-sm text-ink-soft" style="--vf-delay:1.3s">
				<?php foreach ( $stats as $s ) : ?>
				<div><dt class="display text-3xl font-bold text-ink"><?php echo esc_html( $s[0] ); ?></dt><dd><?php echo esc_html( $s[1] ); ?></dd></div>
				<?php endforeach; ?>
			</dl>
		</div>

		<!-- visual: the form card + floating notes (graphic/parts/hero-visual.php) -->
		<?php echo vf_graphic( 'hero-visual' ); // phpcs:ignore ?>
	</div>
</section>
