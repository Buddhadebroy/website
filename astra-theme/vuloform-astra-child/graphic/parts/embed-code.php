<?php
/**
 * Embed code window: dark card with Copy button and one code panel per way to publish (vf_embed_tabs()).
 * Tabs: the panels are .vf-tab; put this inside a .vf-tabs box that also holds a .vf-tab-list (Kadence: a column with one heading per tab).
 * On its own it shows the first panel.
 *
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;

$tabs = vf_embed_tabs();
?>
<div class="relative rounded-[28px] bg-ink p-2 shadow-[0_40px_80px_-40px_rgba(13,26,69,0.6)]">
	<div class="flex items-center justify-between px-4 py-3">
		<div class="flex gap-1.5"><span class="h-3 w-3 rounded-full bg-white/20"></span><span class="h-3 w-3 rounded-full bg-white/20"></span><span class="h-3 w-3 rounded-full bg-white/20"></span></div>
		<button type="button" class="vf-copy rounded-lg bg-white/10 px-3 py-1.5 text-xs font-semibold text-white transition-colors hover:bg-white/20" data-done="Copied ✓">Copy</button>
	</div>
	<div class="min-h-[200px] rounded-[22px] bg-[#13235a] p-6">
		<?php foreach ( $tabs as $t ) : ?>
		<div class="vf-tab">
			<pre class="vf-code m-0 overflow-x-auto bg-transparent p-0 font-mono text-sm leading-relaxed whitespace-pre-wrap text-[#9ff0c9]"><?php echo esc_html( $t[2] ); ?></pre>
			<p class="mt-6 border-t border-white/10 pt-4 text-sm text-white/60"><?php echo esc_html( $t[1] ); ?></p>
		</div>
		<?php endforeach; ?>
	</div>
</div>
