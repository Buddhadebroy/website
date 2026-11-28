<?php
/**
 * Hero form card: name and email type themselves, then a conditional field opens.
 * Behaviour: data-vf="hero-form" in assets/js/vf-site.js (starts when the card is on screen).
 *
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="vf-tilt relative rounded-[28px] border border-line bg-white p-6 shadow-[0_40px_80px_-40px_rgba(13,26,69,0.45)] sm:p-8" data-vf="hero-form">
	<div class="mb-6 flex items-center justify-between">
		<div><p class="text-xs font-semibold tracking-wider text-mint uppercase">Step 1 of 2</p><h3 class="mt-1 text-xl font-bold">Get in touch</h3></div>
		<div class="flex gap-1.5"><span class="h-1.5 w-8 rounded-full bg-navy"></span><span class="h-1.5 w-8 rounded-full bg-line"></span></div>
	</div>

	<span class="block text-sm font-medium text-ink-soft">Name</span>
	<div class="mt-1.5 mb-4 flex h-12 items-center rounded-xl border border-line bg-cream/60 px-4"><span data-type="Ada Lovelace" data-at="1300" data-speed="70"></span><span class="caret ml-px h-5 w-0.5 bg-navy"></span></div>

	<span class="block text-sm font-medium text-ink-soft">Email</span>
	<div class="vf-hf-email mt-1.5 mb-4 flex h-12 items-center rounded-xl border border-line bg-cream/60 px-4 transition-colors"><span data-type="ada@analytical.co" data-at="2400" data-speed="55"></span><span class="caret ml-px h-5 w-0.5 bg-navy" hidden></span><span class="vf-hf-ok ml-auto text-mint" hidden>✓</span></div>

	<span class="block text-sm font-medium text-ink-soft">What's this about?</span>
	<div class="mt-2 grid grid-cols-2 gap-2">
		<div class="rounded-xl border border-line px-3 py-2.5 text-sm">Sales question</div>
		<div class="vf-hf-topic rounded-xl border border-line px-3 py-2.5 text-sm transition-all duration-500">Problem with an order</div>
	</div>

	<div class="vf-collapse"><div>
		<div class="pt-4">
			<span class="flex items-center gap-2 text-sm font-medium text-ink-soft">Order number <span class="rounded-md bg-lilac-soft px-1.5 py-0.5 text-[10px] font-bold text-[#6b45d6] uppercase">Conditional</span></span>
			<div class="mt-1.5 h-12 rounded-xl border border-dashed border-[#6b45d6]/40 bg-lilac-soft/40"></div>
		</div>
	</div></div>

	<span class="mt-6 block w-full rounded-xl bg-navy py-3.5 text-center font-semibold text-white">Next →</span>
</div>
