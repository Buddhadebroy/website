<?php
/**
 * Home › Final call to action (port of components/Cta.tsx).
 * Effects: vf-scroll-zoom (the card grows from 88% and its corners tighten as it scrolls in), vf-enter-up on the two notes.
 *
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="px-4 pb-10 sm:px-6">
	<div class="vf-scroll-zoom relative mx-auto max-w-6xl overflow-hidden bg-gradient-to-br from-sky-soft via-white to-mint-soft px-6 py-24 text-center ring-1 ring-line sm:py-32">
		<div class="vf-enter-up absolute top-10 left-[8%] hidden sm:block" style="--vf-from:120px;--vf-to:-20px"><div class="rotate-[-8deg] rounded-2xl bg-white px-4 py-3 text-sm font-semibold shadow-lg">✉️ Confirmation sent</div></div>
		<div class="vf-enter-up absolute right-[8%] bottom-14 hidden sm:block" style="--vf-from:200px;--vf-to:10px"><div class="rotate-[6deg] rounded-2xl bg-white px-4 py-3 text-sm font-semibold shadow-lg">📈 Exported to CSV</div></div>
		<h2 class="mx-auto max-w-3xl text-[clamp(2.4rem,5.5vw,4.5rem)] leading-[1.05] font-bold tracking-tight">Your next form is <span class="text-navy">two minutes</span> away.</h2>
		<p class="mx-auto mt-6 max-w-lg text-lg text-ink-soft">Install from Plugins › Add New, pick a template, hit Publish. That's the whole tutorial.</p>
		<div class="mt-10 flex flex-wrap justify-center gap-3">
			<a href="<?php echo esc_url( vf_download_url() ); ?>" class="rounded-full bg-navy px-8 py-4 font-semibold text-white shadow-[0_18px_40px_-16px_rgba(0,75,242,0.7)] transition-all hover:-translate-y-0.5 hover:bg-navy-light">Download VuloForm — free</a>
			<a href="<?php echo esc_url( vf_docs_url() ); ?>" class="rounded-full border border-line bg-white px-8 py-4 font-semibold text-ink transition-colors hover:border-navy/30">Read the docs</a>
		</div>
		<p class="mt-6 text-sm text-ink-soft">WordPress 6.4+ · PHP 7.4+ · GPLv2 or later</p>
	</div>
</section>
