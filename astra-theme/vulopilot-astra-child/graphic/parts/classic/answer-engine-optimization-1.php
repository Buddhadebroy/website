<?php
/**
 * Answer Engine Optimization › scans on its own, then reveals the score and findings.
 * Graphic name: answer-engine-optimization-1  ·  Shortcode: [vp_answer_engine_optimization_1]  ·  /solutions/answer-engine-optimization/
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$args = isset( $args ) && is_array( $args ) ? $args : array();
$t    = array_merge(
	array(
		'scanning' => 'Scanning /solutions/answer-engine-optimization/…',
		'score'    => 'Score',
		'note'     => 'Made-up example for illustration.',
		'hint'     => 'Each finding says what it means and fixes in one click.',
	),
	(array) ( $args['text'] ?? array() )
);
$from = 29;
$to = 91;
// level, finding, fixed.
$leaks = $args['leaks'] ?? array(
	array( 'Critical', 'Questions your customers ask have no clear answer on your site.', true ),
	array( 'Medium', 'You cannot see whether AI answers mention you.', true ),
	array( 'Low', 'Pages go stale and AI prefers current answers.', false ),
);
$sev = array(
	'Critical' => 'background:var(--rose);color:#fff',
	'High'     => vp_tint_style( 'var(--rose)', 12 ),
	'Medium'   => vp_tint_style( 'var(--flare)', 16 ),
	'Low'      => 'background:var(--sage-soft);color:var(--sage)',
);
$fix_at = 1.4 + count( $leaks ) * 0.1 + 0.6;
?>
<div class="card p-6" data-vpg="loop" data-every="12000">
	<div data-fx="swap" data-after="1.6">
		<div data-when="before" class="vpg-exposure-wait grid place-items-center text-sm text-muted">
			<span class="flex items-center gap-2"><span class="vpg-spin text-sage"><?php vp_i( 'loader-circle', 16 ); ?></span><?php echo esc_html( $t['scanning'] ); ?></span>
		</div>
		<div data-when="after" hidden>
			<div class="flex items-end justify-between">
				<span class="text-[11px] font-semibold uppercase tracking-widest text-muted"><?php echo esc_html( $t['score'] ); ?></span>
				<span class="text-3xl font-semibold tabular-nums"><span data-fx="swap" data-after="<?php echo esc_attr( $fix_at + 0.5 ); ?>"><span data-when="before" <?php vp_m( 'N:' . $from . ' t:.8' ); ?>><?php echo (int) $from; ?></span><span data-when="after" hidden <?php vp_m( 'N:' . $to . ' t:.8' ); ?>><?php echo (int) $to; ?></span></span><span class="text-sm text-muted"> / 100</span></span>
			</div>
			<div class="vpg-meter mt-3">
				<div class="vpg-meter-fill" <?php vp_m( 'W:' . $from . '% t:.8 e:ease', 'background:var(--rose)' ); ?>></div>
				<div class="vpg-meter-fill" <?php vp_m( 'W:' . $to . '% t:.9 e:ease d:' . ( $fix_at + 0.5 ), 'background:var(--flare)' ); ?>></div>
			</div>
			<ul class="mt-5 space-y-2">
				<?php foreach ( $leaks as $i => $l ) : $fix = ! empty( $l[2] ); ?>
					<li class="rounded-sm border border-line bg-bg px-4 py-3" <?php vp_m( 'o:0 y:8 t:.35 d:' . ( 0.2 + $i * 0.1 ) ); ?>>
						<div class="flex items-center gap-3">
							<span class="vpg-sev shrink-0 rounded-sm px-2 py-0.5 text-[11px] font-semibold" style="<?php echo esc_attr( $sev[ $l[0] ] ?? $sev['Low'] ); ?>"><?php echo esc_html( $l[0] ); ?></span>
							<?php if ( $fix ) : ?>
								<span class="min-w-0 flex-1 text-sm font-semibold" data-fx="swap" data-after="<?php echo esc_attr( $fix_at + ( $i * 0.5 ) ); ?>"><span data-when="before"><?php echo esc_html( $l[1] ); ?></span><span data-when="after" hidden class="text-muted line-through"><?php echo esc_html( $l[1] ); ?></span></span>
								<span class="shrink-0 text-sage" <?php vp_m( 'o:0 s:.5 e:spring d:' . ( $fix_at + $i * 0.5 ) ); ?>><?php vp_i( 'check', 16 ); ?></span>
							<?php else : ?>
								<span class="min-w-0 flex-1 text-sm font-semibold"><?php echo esc_html( $l[1] ); ?></span>
							<?php endif; ?>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
			<p class="mt-4 text-xs text-muted"><?php echo esc_html( $t['hint'] ); ?></p>
		</div>
	</div>
	<p class="mt-3 text-center text-xs text-muted"><?php echo esc_html( $t['note'] ); ?></p>
</div>
