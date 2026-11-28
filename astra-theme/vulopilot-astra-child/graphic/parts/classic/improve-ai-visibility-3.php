<?php
/**
 * Improve Ai Visibility › the same page before and after the fix, side by side.
 * Graphic name: improve-ai-visibility-3  ·  Shortcode: [vp_improve_ai_visibility_3]  ·  /use-cases/improve-ai-visibility/
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$args = isset( $args ) && is_array( $args ) ? $args : array();
$t    = array_merge(
	array(
		'label'  => 'Before / after',
		'page'   => '/use-cases/improve-ai-visibility/',
		'before' => 'Before',
		'after'  => 'After',
		'desc_b' => 'AI describes your business wrongly or not at all.',
		'desc_a' => 'Fill in Business Information and run a scan.',
		'from'   => 47,
		'to'     => 93,
	),
	(array) ( $args['text'] ?? array() )
);
?>
<div class="card overflow-hidden" data-vpg="loop" data-every="9000">
	<div data-fx="swap" data-after="2.6">
		<div class="flex flex-wrap items-center justify-between gap-2 border-b border-line px-5 py-3.5">
			<div class="flex items-center gap-2.5 text-sm font-semibold"><span class="grid h-7 w-7 place-items-center rounded-sm bg-sage-soft text-sage"><?php vp_i( 'search', 15 ); ?></span><?php echo esc_html( $t['label'] ); ?></div>
			<div class="vpg-seg"><span class="is-b"><?php echo esc_html( $t['before'] ); ?></span><span class="is-a"><?php echo esc_html( $t['after'] ); ?></span></div>
		</div>
		<div class="p-5">
			<div class="vpg-serp rounded-sm border border-line bg-surface p-4">
				<div class="flex items-center justify-between gap-3">
					<span class="text-[11px] font-semibold uppercase tracking-widest text-muted"><?php echo esc_html( $t['page'] ); ?></span>
					<span class="text-xl font-semibold tabular-nums" data-fx="swap" data-after="2.6"><span data-when="before" style="color:var(--rose)"><?php echo (int) $t['from']; ?></span><span data-when="after" hidden style="color:var(--sage)" <?php vp_m( 'o:0 s:.7 e:spring' ); ?>><?php echo (int) $t['to']; ?></span><span class="text-xs text-muted"> / 100</span></span>
				</div>
				<div data-when="before">
					<p class="mt-2 text-[13px] leading-relaxed text-muted"><?php echo esc_html( $t['desc_b'] ); ?></p>
				</div>
				<div data-when="after" hidden>
					<p class="mt-2 text-[13px] leading-relaxed" <?php vp_m( 'o:0 y:6 t:.4' ); ?>><?php echo esc_html( $t['desc_a'] ); ?></p>
				</div>
			</div>
			<div class="mt-3 grid gap-2 sm:grid-cols-2">
				<div class="rounded-sm border border-line bg-bg/40 px-3 py-2">
					<div class="text-[10px] font-semibold uppercase tracking-widest text-muted">Status</div>
					<div class="mt-0.5 flex items-center gap-1.5 text-xs font-semibold" style="color:var(--rose)" data-when="before"><?php vp_i( 'x', 12, '', 3 ); ?>Not fixed</div>
					<div class="mt-0.5 flex items-center gap-1.5 text-xs font-semibold text-sage" data-when="after" hidden <?php vp_m( 'o:0 s:.9 t:.3' ); ?>><?php vp_i( 'check', 12, '', 3 ); ?>Fixed</div>
				</div>
				<div class="rounded-sm border border-line bg-bg/40 px-3 py-2">
					<div class="text-[10px] font-semibold uppercase tracking-widest text-muted">Confidence</div>
					<div class="mt-0.5 flex items-center gap-1.5 text-xs font-semibold text-muted" data-when="before">Unverified</div>
					<div class="mt-0.5 flex items-center gap-1.5 text-xs font-semibold text-sage" data-when="after" hidden <?php vp_m( 'o:0 s:.9 t:.3 d:.1' ); ?>>Re-scanned, confirmed</div>
				</div>
			</div>
		</div>
	</div>
</div>
