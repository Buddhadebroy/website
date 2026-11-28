<?php
/**
 * Security › hero: one security score and the findings ranked most serious first.
 * Fixes tick off one by one and the score climbs. Plays again every few seconds.
 * Graphic name: security-score-demo  ·  Shortcode: [vp_security_score_demo]  ·  features/security-1
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$args = isset( $args ) && is_array( $args ) ? $args : array();
$t    = array_merge(
	array(
		'title' => 'Security scan',
		'site'  => 'sweetlayers.example',
		'score' => 'Security score',
		'after' => 'After 3 fixes',
		'note'  => 'Most serious first, so your first fix is the one that matters.',
		'btn'   => 'Fix',
		'done'  => 'Fixed',
	),
	(array) ( $args['text'] ?? array() )
);
$from = (int) ( $args['from'] ?? 48 );
$to   = (int) ( $args['to'] ?? 84 );
// level, finding, detail, fixed in the replay (true/false).
$rows = $args['rows'] ?? array(
	array( 'Critical', 'Login has no limit on password guesses', 'Bots can try thousands of passwords a night.', true ),
	array( 'High', 'Old backup file anyone can download', '/backup-2024.zip is public.', true ),
	array( 'High', 'Plugin with a known vulnerability', 'Slider Plugin 6.6.12, fixed in 6.6.20.', true ),
	array( 'Medium', 'Author usernames are public', '/?author=1 shows “admin”.', false ),
	array( 'Low', 'Security headers missing', 'Add them in one click.', false ),
);
$sev = array(
	'Critical' => 'background:var(--rose);color:#fff',
	'High'     => vp_tint_style( 'var(--rose)', 12 ),
	'Medium'   => vp_tint_style( 'var(--flare)', 16 ),
	'Low'      => 'background:var(--sage-soft);color:var(--sage)',
);
$fixes   = 0;
$n_fix   = count( array_filter( array_column( $rows, 3 ) ) );
$done_at = 0.9 + $n_fix * 0.55 + 0.3; // seconds: after the last fix.
?>
<div class="card overflow-hidden" data-vpg="loop" data-every="10000">
	<div class="flex flex-wrap items-center justify-between gap-2 border-b border-line px-5 py-3.5">
		<div class="flex items-center gap-2.5 text-sm font-semibold"><span class="grid h-7 w-7 place-items-center rounded-sm bg-sage-soft text-sage"><?php vp_i( 'shield-check', 15 ); ?></span><?php echo esc_html( $t['title'] ); ?></div>
		<span class="font-mono text-[11px] text-muted"><?php echo esc_html( $t['site'] ); ?></span>
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
		<ul class="mt-5 space-y-2">
			<?php foreach ( $rows as $i => $r ) : $fix = ! empty( $r[3] ); $d = 0.9 + $fixes * 0.55; if ( $fix ) { $fixes++; } ?>
				<li class="vpg-finding flex items-center gap-3 rounded-sm border border-line bg-surface px-3.5 py-2.5" <?php vp_m( 'o:0 y:10 t:.4 d:' . ( 0.15 + $i * 0.1 ) ); ?>>
					<span class="vpg-sev shrink-0 rounded-sm px-2 py-0.5 text-[11px] font-semibold" style="<?php echo esc_attr( $sev[ $r[0] ] ?? $sev['Low'] ); ?>"><?php echo esc_html( $r[0] ); ?></span>
					<span class="min-w-0 flex-1">
						<span class="block text-sm font-semibold leading-snug"><?php echo esc_html( $r[1] ); ?></span>
						<span class="block truncate text-xs text-muted"><?php echo esc_html( $r[2] ); ?></span>
					</span>
					<?php if ( $fix ) : ?>
						<span class="shrink-0" data-fx="swap" data-after="<?php echo esc_attr( $d ); ?>">
							<span data-when="before" class="inline-block rounded-sm border border-line px-2.5 py-1 text-[11px] font-semibold"><?php echo esc_html( $t['btn'] ); ?></span>
							<span data-when="after" hidden class="inline-flex items-center gap-1 rounded-sm bg-sage-soft px-2.5 py-1 text-[11px] font-semibold text-sage" <?php vp_m( 'o:0 s:.6 e:spring' ); ?>><?php vp_i( 'check', 12 ); ?><?php echo esc_html( $t['done'] ); ?></span>
						</span>
					<?php else : ?>
						<span class="shrink-0 rounded-sm border border-line px-2.5 py-1 text-[11px] font-semibold"><?php echo esc_html( $t['btn'] ); ?></span>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
		<p class="mt-4 text-xs text-muted"><?php echo esc_html( $t['note'] ); ?></p>
	</div>
</div>
