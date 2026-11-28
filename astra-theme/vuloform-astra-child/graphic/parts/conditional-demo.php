<?php
/**
 * Conditional logic demo: pick "Problem with an order" and the Order number field opens.
 * Behaviour: data-vf="conditional" in assets/js/vf-site.js.
 *
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;

$topics = array( 'Sales question', 'Problem with an order', 'Something else' );
?>
<div class="flex h-full flex-col rounded-[28px] border border-line bg-white p-6 sm:p-8" data-vf="conditional" data-show="Problem with an order">
	<div class="mb-6 flex items-center gap-2 text-xs font-semibold text-[#6b45d6]"><span class="rounded-md bg-lilac-soft px-2 py-1">Rule</span> Show <b>Order number</b> when <b>Topic</b> is “Problem with an order”</div>
	<p class="text-sm font-medium text-ink-soft">Topic</p>
	<div class="mt-2 flex flex-wrap gap-2" role="radiogroup" aria-label="Topic">
		<?php foreach ( $topics as $i => $t ) : ?>
		<button type="button" role="radio" aria-checked="<?php echo 0 === $i ? 'true' : 'false'; ?>" class="vf-choice rounded-full border border-line px-4 py-2 text-sm text-ink transition-all hover:border-navy/40"><?php echo esc_html( $t ); ?></button>
		<?php endforeach; ?>
	</div>
	<div class="vf-collapse"><div>
		<label class="mt-5 block text-sm font-medium text-ink-soft">Order number <span class="text-[#d24b4b]">*</span>
			<input placeholder="e.g. 10482" class="mt-1.5 block h-12 w-full rounded-xl border border-[#6b45d6]/40 bg-lilac-soft/40 px-4 text-ink outline-none focus:ring-4 focus:ring-[#6b45d6]/15">
		</label>
	</div></div>
	<span class="mt-5 block text-sm font-medium text-ink-soft">Message</span>
	<div class="mt-1.5 h-20 rounded-xl border border-line bg-cream/60"></div>
	<p class="mt-auto pt-6 text-xs text-ink-soft">↑ Try it. Rules run in the browser as visitors type — and are enforced again on your server.</p>
</div>
