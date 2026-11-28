<?php
/**
 * Multi-step + calculation demo: pick a package and quantity, the total counts to the new value.
 * Behaviour: data-vf="calc" in assets/js/vf-site.js.
 *
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="flex h-full flex-col rounded-[28px] border border-line bg-white p-6 sm:p-8" data-vf="calc" data-step="0">
	<div class="mb-6 flex items-center justify-between">
		<p class="text-xs font-semibold tracking-wider text-mint uppercase">Step <span class="vf-calc-n">1</span> of 2</p>
		<div class="flex gap-1.5"><span class="vf-calc-dot is-on h-1.5 rounded-full"></span><span class="vf-calc-dot h-1.5 rounded-full"></span></div>
	</div>

	<div class="vf-calc-step">
		<p class="text-sm font-medium text-ink-soft">Package</p>
		<div class="mt-2 grid grid-cols-2 gap-2">
			<button type="button" class="vf-plan is-on rounded-xl border border-line p-3 text-left text-ink transition-all hover:border-mint/50" data-price="24"><span class="block font-semibold">Basic</span><span class="block text-sm text-ink-soft">$24 each</span></button>
			<button type="button" class="vf-plan rounded-xl border border-line p-3 text-left text-ink transition-all hover:border-mint/50" data-price="49"><span class="block font-semibold">Deluxe</span><span class="block text-sm text-ink-soft">$49 each</span></button>
		</div>
		<p class="mt-5 text-sm font-medium text-ink-soft">Quantity</p>
		<div class="mt-2 flex items-center gap-3">
			<button type="button" class="vf-qty grid h-11 w-11 place-items-center rounded-xl border border-line bg-white text-xl text-ink hover:bg-cream" data-d="-1" aria-label="Decrease">−</button>
			<span class="vf-qty-n w-10 text-center text-2xl font-bold">3</span>
			<button type="button" class="vf-qty grid h-11 w-11 place-items-center rounded-xl border border-line bg-white text-xl text-ink hover:bg-cream" data-d="1" aria-label="Increase">+</button>
		</div>
	</div>
	<div class="vf-calc-step space-y-4" hidden>
		<p class="text-sm font-medium text-ink-soft">Delivery address</p>
		<div class="h-12 rounded-xl border border-line bg-cream/60"></div>
		<div class="grid grid-cols-2 gap-2"><div class="h-12 rounded-xl border border-line bg-cream/60"></div><div class="h-12 rounded-xl border border-line bg-cream/60"></div></div>
	</div>

	<div class="mt-6 flex items-center justify-between rounded-2xl bg-butter-soft px-5 py-4">
		<span class="text-sm font-semibold">Total <span class="font-normal text-ink-soft">= quantity × price</span></span>
		<span class="vf-calc-total text-2xl font-bold tabular-nums">$72.00</span>
	</div>
	<div class="mt-auto flex gap-2 pt-6">
		<button type="button" class="vf-calc-back rounded-xl border border-line bg-white px-5 py-3 font-semibold text-ink" hidden>← Back</button>
		<button type="button" class="vf-calc-next flex-1 rounded-xl bg-navy py-3 font-semibold text-white">Next →</button>
	</div>
</div>
