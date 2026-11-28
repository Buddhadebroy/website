<?php
/**
 * Monitor Website Health › the score moving: before/after meter.
 * Graphic name: monitor-website-health-3  ·  Shortcode: [vp_monitor_website_health_3]  ·  /use-cases/monitor-website-health/
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$args = isset( $args ) && is_array( $args ) ? $args : array();
$t    = array_merge(
	array(
		'title' => 'Monitor Website Health score',
		'page'  => '/use-cases/monitor-website-health/',
		'score' => 'Score',
		'note'  => 'without checking daily.',
	),
	(array) ( $args['text'] ?? array() )
);
$from = 28;
$to = 82;
$done_at = 1.1;
?>
<div class="card overflow-hidden" data-vpg="loop" data-every="10000">
	<div class="flex flex-wrap items-center justify-between gap-2 border-b border-line px-5 py-3.5">
		<div class="flex items-center gap-2.5 text-sm font-semibold"><span class="grid h-7 w-7 place-items-center rounded-sm bg-sage-soft text-sage"><?php vp_i( 'gauge', 15 ); ?></span><?php echo esc_html( $t['title'] ); ?></div>
		<span class="font-mono text-[11px] text-muted"><?php echo esc_html( $t['page'] ); ?></span>
	</div>
	<div class="p-5">
		<div class="flex items-end justify-between gap-3">
			<span class="text-[11px] font-semibold uppercase tracking-widest text-muted"><?php echo esc_html( $t['score'] ); ?></span>
			<span class="text-3xl font-semibold tabular-nums"><span data-fx="swap" data-after="<?php echo esc_attr( $done_at ); ?>"><span data-when="before" <?php vp_m( 'N:' . (int) $from . ' t:.9' ); ?>><?php echo (int) $from; ?></span><span data-when="after" hidden <?php vp_m( 'N:' . (int) $to . ' t:1' ); ?>><?php echo (int) $to; ?></span></span><span class="text-sm text-muted"> / 100</span></span>
		</div>
		<div class="vpg-meter mt-3">
			<div class="vpg-meter-fill" <?php vp_m( 'W:' . (int) $from . '% t:.9 e:ease', 'background:var(--rose)' ); ?>></div>
			<div class="vpg-meter-fill" <?php vp_m( 'W:' . (int) $to . '% t:1 e:ease d:' . $done_at, 'background:var(--sage)' ); ?>></div>
		</div>
		<p class="mt-4 text-xs text-muted"><?php echo esc_html( $t['note'] ); ?></p>
	</div>
</div>
