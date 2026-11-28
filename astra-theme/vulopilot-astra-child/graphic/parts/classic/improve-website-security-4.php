<?php
/**
 * Improve Website Security › an automated fix workflow with a run log.
 * Graphic name: improve-website-security-4  ·  Shortcode: [vp_improve_website_security_4]  ·  /use-cases/improve-website-security/
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$args = isset( $args ) && is_array( $args ) ? $args : array();
$t    = array_merge(
	array(
		'title' => 'Fix workflow',
		'name'  => 'Improve Website Security guard',
		'pro'   => 'Paid plans',
		'log'   => 'Run log',
	),
	(array) ( $args['text'] ?? array() )
);
// kind (When|If|Then), icon, title, detail.
$steps = $args['steps'] ?? array(
	array( 'When', 'calendar-clock', 'Run a security scan and read the severity-ranked list.', 'Scheduled trigger' ),
	array( 'If', 'filter', 'Follow the suggested setup: login protection, attack filter and alerts.', 'Condition' ),
	array( 'Then', 'wand-sparkles', 'Scan again and VuloPilot shows whether the problem is gone, with your score history as proof.', 'Waits for your approval' ),
);
$kind_col = array( 'When' => 'var(--sage)', 'If' => 'var(--lilac)', 'Then' => 'var(--flare)' );
// status (ok|retry), run, detail, time.
$runs = $args['runs'] ?? array(
	array( 'ok', 'Run #128', 'Debug mode left on.', '2m' ),
	array( 'ok', 'Run #127', 'Repeated login guessing.', '4m' ),
	array( 'ok', 'Run #126', 'Outdated plugins.', '6m' ),
);
$run_ic   = array( 'ok' => array( 'check', 'var(--sage)' ), 'retry' => array( 'rotate-ccw', 'var(--flare)' ), 'fail' => array( 'x', 'var(--rose)' ) );
?>
<div class="card overflow-hidden" data-vpg="loop" data-every="11000">
	<div class="flex flex-wrap items-center justify-between gap-2 border-b border-line px-5 py-3.5">
		<div class="flex items-center gap-2.5 text-sm font-semibold"><span class="grid h-7 w-7 place-items-center rounded-sm bg-sage-soft text-sage"><?php vp_i( 'workflow', 15 ); ?></span><?php echo esc_html( $t['title'] ); ?> <span class="font-normal text-muted">· <?php echo esc_html( $t['name'] ); ?></span></div>
		<span class="vpg-chip is-bad"><?php vp_i( 'sparkles', 11 ); ?><?php echo esc_html( $t['pro'] ); ?></span>
	</div>
	<div class="p-5">
		<ol class="vpg-flow">
			<?php foreach ( $steps as $i => $s ) : $c = $kind_col[ $s[0] ] ?? 'var(--sage)'; ?>
				<li class="vpg-flow-step" <?php vp_m( 'o:0 x:-12 t:.45 e:spring d:' . ( 0.15 + $i * 0.4 ) ); ?>>
					<span class="vpg-flow-kind" style="<?php echo esc_attr( vp_tint_style( $c ) ); ?>"><?php echo esc_html( $s[0] ); ?></span>
					<span class="flex min-w-0 flex-1 items-center gap-3 rounded-sm border border-line bg-surface px-3.5 py-2.5">
						<span class="grid h-8 w-8 shrink-0 place-items-center rounded-sm" style="<?php echo esc_attr( vp_tint_style( $c ) ); ?>"><?php vp_i( $s[1], 15 ); ?></span>
						<span class="min-w-0">
							<span class="block text-sm font-semibold leading-snug"><?php echo esc_html( $s[2] ); ?></span>
							<span class="block text-xs text-muted"><?php echo esc_html( $s[3] ); ?></span>
						</span>
					</span>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
	<div class="border-t border-line bg-bg px-5 py-4">
		<div class="text-[11px] font-semibold uppercase tracking-widest text-muted"><?php echo esc_html( $t['log'] ); ?></div>
		<ul class="mt-3 space-y-1.5">
			<?php foreach ( $runs as $i => $r ) : $ic = $run_ic[ $r[0] ] ?? $run_ic['ok']; ?>
				<li class="flex items-center gap-3 text-sm" <?php vp_m( 'o:0 y:6 t:.35 d:' . ( 1.9 + $i * 0.2 ) ); ?>>
					<span class="grid h-6 w-6 shrink-0 place-items-center rounded-full" style="<?php echo esc_attr( vp_tint_style( $ic[1] ) ); ?>"><?php vp_i( $ic[0], 12 ); ?></span>
					<span class="shrink-0 font-semibold"><?php echo esc_html( $r[1] ); ?></span>
					<span class="min-w-0 flex-1 truncate text-xs text-muted"><?php echo esc_html( $r[2] ); ?></span>
					<span class="shrink-0 font-mono text-[11px] text-muted"><?php echo esc_html( $r[3] ); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
