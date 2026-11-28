<?php
/**
 * WooCommerce › "Every sales leak in one list": ask the AI sales assistant, get the store's
 * leaks ranked by what they are most likely to cost, then draft fixes for review.
 * Graphic name: store-leaks-demo  ·  Shortcode: [vp_store_leaks_demo]  ·  features/woocommerce-2
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$args = isset( $args ) && is_array( $args ) ? $args : array();
$t    = array_merge(
	array(
		'title'    => 'AI sales assistant',
		'count'    => '7 open',
		'question' => 'Why were sales quieter this week?',
		'answer'   => 'Three things are most likely costing you orders. Costliest first:',
		'btn'      => 'Draft the fixes for review',
		'note'     => 'Nothing is saved until you approve it.',
	),
	(array) ( $args['text'] ?? array() )
);
// icon, level, leak, cost or effect.
$leaks = $args['leaks'] ?? array(
	array( 'credit-card', 'High', '4 card payments failed at checkout', '$212 not collected' ),
	array( 'image', 'Medium', '3 bestsellers have no main image', 'Fewer clicks from search' ),
	array( 'file-text', 'Medium', '6 products have no description', 'Shoppers leave to compare' ),
);
$sev = array(
	'Critical' => 'background:var(--rose);color:#fff',
	'High'     => vp_tint_style( 'var(--rose)', 12 ),
	'Medium'   => vp_tint_style( 'var(--flare)', 16 ),
	'Low'      => 'background:var(--sage-soft);color:var(--sage)',
);
$base = 1.9;
?>
<div class="card overflow-hidden" data-vpg="loop" data-every="12000">
	<div class="flex flex-wrap items-center justify-between gap-2 border-b border-line px-5 py-3.5">
		<div class="flex items-center gap-2.5 text-sm font-semibold"><span class="grid h-7 w-7 place-items-center rounded-sm bg-sage-soft text-sage"><?php vp_i( 'bot', 15 ); ?></span><?php echo esc_html( $t['title'] ); ?></div>
		<span class="vpg-chip is-bad"><?php echo esc_html( $t['count'] ); ?></span>
	</div>
	<div class="space-y-3 p-5">
		<div class="flex justify-end" <?php vp_m( 'o:0 y:8 t:.4' ); ?>>
			<span class="max-w-[85%] rounded-sm bg-ink px-3.5 py-2 text-sm text-bg"><?php echo esc_html( $t['question'] ); ?></span>
		</div>
		<div class="flex gap-2.5" <?php vp_m( 'o:0 y:8 t:.4 d:.6' ); ?>>
			<span class="grid h-7 w-7 shrink-0 place-items-center rounded-full bg-sage text-white"><?php vp_i( 'sparkles', 13 ); ?></span>
			<div class="min-w-0 flex-1 rounded-sm bg-bg px-3.5 py-2.5 text-sm">
				<p data-fx="type" data-ms="22"><?php echo esc_html( $t['answer'] ); ?></p>
				<ol class="mt-3 space-y-2">
					<?php foreach ( $leaks as $i => $l ) : ?>
						<li class="flex items-center gap-3 rounded-sm border border-line bg-surface px-3 py-2.5" <?php vp_m( 'o:0 x:14 t:.45 e:spring d:' . ( $base + $i * 0.45 ) ); ?>>
							<span class="grid h-6 w-6 shrink-0 place-items-center rounded-full bg-sage text-[11px] font-bold text-white"><?php echo (int) $i + 1; ?></span>
							<span class="grid h-7 w-7 shrink-0 place-items-center rounded-sm bg-sage-soft text-sage"><?php vp_i( $l[0], 14 ); ?></span>
							<span class="min-w-0 flex-1">
								<span class="block text-sm font-semibold leading-snug"><?php echo esc_html( $l[2] ); ?></span>
								<span class="block text-xs text-muted"><?php echo esc_html( $l[3] ); ?></span>
							</span>
							<span class="shrink-0 rounded-sm px-2 py-0.5 text-[11px] font-semibold" style="<?php echo esc_attr( $sev[ $l[1] ] ?? $sev['Low'] ); ?>"><?php echo esc_html( $l[1] ); ?></span>
						</li>
					<?php endforeach; ?>
				</ol>
			</div>
		</div>
		<div class="flex flex-wrap items-center gap-3" style="padding-left:38px" <?php vp_m( 'o:0 y:8 t:.4 d:' . ( $base + count( $leaks ) * 0.45 + 0.2 ) ); ?>>
			<span class="inline-flex items-center gap-1.5 rounded-sm bg-ink px-3.5 py-2 text-xs font-semibold text-bg"><?php vp_i( 'wand-sparkles', 13 ); ?><?php echo esc_html( $t['btn'] ); ?></span>
			<span class="text-xs text-muted"><?php echo esc_html( $t['note'] ); ?></span>
		</div>
	</div>
</div>
