<?php
/**
 * Security › "What your site gives away": scans from the outside on its own (no click needed),
 * then lists what an attacker can see, most serious first, and fixes the top two.
 * Replaces the click-to-start React version. Graphic name: exposure-demo  ·  features/security-3
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$args = isset( $args ) && is_array( $args ) ? $args : array();
$t    = array_merge(
	array(
		'scanning' => 'Checking what is visible from outside…',
		'score'    => 'Security score',
		'note'     => 'Made-up example for illustration.',
		'hint'     => 'Each finding says what it means and fixes in one click.',
	),
	(array) ( $args['text'] ?? array() )
);
$from = (int) ( $args['from'] ?? 48 );
$to   = (int) ( $args['to'] ?? 76 );
// level, finding, detail, fix button, fixed in the replay.
$leaks = $args['leaks'] ?? array(
	array( 'High', 'Usernames are public', '/?author=1 shows /author/admin/, so attackers know half the login.', 'Hide author usernames', true ),
	array( 'High', 'A backup file is downloadable', '/backup-old-site.zip is readable by anyone.', 'Remove the file', true ),
	array( 'Medium', 'Debug mode is on', 'Error messages reveal file paths and plugin names.', 'Turn debug mode off', false ),
	array( 'Medium', 'Secure connection not forced', 'Some pages still load over plain http://.', 'Force HTTPS', false ),
	array( 'Low', 'Browser safety headers missing', 'Browsers are not told to block clickjacking.', 'Add security headers', false ),
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
				<?php foreach ( $leaks as $i => $l ) : $fix = ! empty( $l[4] ); ?>
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
						<?php if ( 0 === $i ) : ?>
							<div class="mt-2 flex flex-wrap items-center gap-3 text-xs text-muted" <?php vp_m( 'o:0 t:.3 d:.8' ); ?>>
								<span class="min-w-0 flex-1"><?php echo esc_html( $l[2] ); ?></span>
								<span class="rounded-sm bg-sage px-3 py-1 font-semibold text-white"><?php echo esc_html( $l[3] ); ?></span>
							</div>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
			<p class="mt-4 text-xs text-muted"><?php echo esc_html( $t['hint'] ); ?></p>
		</div>
	</div>
	<p class="mt-3 text-center text-xs text-muted"><?php echo esc_html( $t['note'] ); ?></p>
</div>
