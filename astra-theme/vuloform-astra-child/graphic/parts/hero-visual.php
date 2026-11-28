<?php
/**
 * Hero visual: the typing form card with the three floating notes (submission, webhook, spam).
 * Effects: vf-reveal-tilt, vf-exit-up (moves while the hero scrolls away), vf-follow (pointer), vf-float / vf-wiggle.
 * Put it next to the hero text, e.g. in a Kadence column: [vf_graphic name="hero-visual"].
 *
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="relative mx-auto w-full max-w-md [perspective:1200px] lg:max-w-none">
	<div class="vf-exit-up" style="--vf-move:60px">
		<div class="vf-reveal-tilt vf-slow" style="--vf-delay:.4s">
			<?php echo vf_graphic( 'hero-form' ); // phpcs:ignore ?>
		</div>
	</div>

	<div class="vf-exit-up absolute -top-8 -left-6 sm:-left-14" style="--vf-move:240px">
		<div class="vf-follow" style="--vf-fx:18px;--vf-fy:0px">
			<div class="vf-reveal-zoom" style="--vf-delay:1.4s">
				<div class="vf-float flex items-center gap-3 rounded-2xl border border-line bg-white px-4 py-3 shadow-lg" style="--vf-float:-8px;--vf-float-t:5s">
					<span class="grid h-9 w-9 place-items-center rounded-xl bg-mint-soft text-lg">📬</span>
					<div class="text-sm"><p class="font-semibold">Submission #128</p><p class="text-xs text-ink-soft">just now · New</p></div>
				</div>
			</div>
		</div>
	</div>

	<div class="vf-exit-up absolute top-1/2 -right-4 sm:-right-10" style="--vf-move:150px">
		<div class="vf-follow" style="--vf-fx:14px;--vf-fy:0px">
			<div class="vf-reveal-zoom" style="--vf-delay:1.7s">
				<div class="vf-float rounded-2xl border border-line bg-white px-4 py-3 font-mono text-xs shadow-lg" style="--vf-float:10px;--vf-float-t:6s">
					<span class="text-ink-soft">POST</span> /webhook <span class="ml-2 rounded-md bg-mint-soft px-1.5 py-0.5 font-bold text-[#0f7a53]">200</span>
				</div>
			</div>
		</div>
	</div>

	<div class="vf-exit-up absolute -bottom-10 left-6" style="--vf-move:240px">
		<div class="vf-reveal-zoom" style="--vf-delay:2s">
			<div class="vf-wiggle rounded-2xl bg-butter-soft px-4 py-3 text-sm font-semibold shadow-lg ring-1 ring-[#f0dc95]">🛡️ Spam blocked — no CAPTCHA</div>
		</div>
	</div>
</div>
