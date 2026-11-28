<?php
/**
 * Improve Website Security › alerts arrive one by one, with an incident log.
 * Graphic name: improve-website-security-2  ·  Shortcode: [vp_improve_website_security_2]  ·  /use-cases/improve-website-security/
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$args = isset( $args ) && is_array( $args ) ? $args : array();
$t    = array_merge(
	array(
		'title'   => 'Alerts',
		'live'    => 'Watching · checked every 6 hours',
		'email'   => 'Alert emailed to you@yoursite.com',
		'email_s' => 'Also shown on your dashboard',
		'log'     => 'Incident log',
		'free'    => 'Free',
		'paid'    => 'Paid',
	),
	(array) ( $args['text'] ?? array() )
);
// icon, colour, title, detail, level, plan (free|paid).
$alerts = $args['alerts'] ?? array(
	array( 'alert-triangle', 'var(--rose)', 'Debug mode left on.', 'Flagged by the scan.', 'High', 'paid' ),
	array( 'filter', 'var(--flare)', 'Repeated login guessing.', 'Flagged by the scan.', 'Medium', 'paid' ),
	array( 'list-tree', 'var(--lilac)', 'Outdated plugins.', 'Flagged by the scan.', 'Low', 'free' ),
);
// time, event, done.
$log = $args['log'] ?? array(
	array( '03:12', 'Run a security scan and read the severity-ranked list.', false ),
	array( '03:14', 'Follow the suggested setup: login protection, attack filter ', false ),
	array( '09:40', 'Scan again and VuloPilot shows whether the problem is gone, ', true ),
);
?>
<div class="card overflow-hidden" data-vpg="loop" data-every="11000">
	<div class="flex flex-wrap items-center justify-between gap-2 border-b border-line px-5 py-3.5">
		<div class="flex items-center gap-2.5 text-sm font-semibold"><span class="vpg-bell grid h-7 w-7 place-items-center rounded-sm" style="<?php echo esc_attr( vp_tint_style( 'var(--rose)' ) ); ?>"><?php vp_i( 'bell-ring', 15 ); ?></span><?php echo esc_html( $t['title'] ); ?></div>
		<span class="inline-flex items-center gap-2 text-[11px] text-muted"><span class="vpg-live"></span><?php echo esc_html( $t['live'] ); ?></span>
	</div>
	<div class="p-5">
		<ul class="space-y-2.5">
			<?php foreach ( $alerts as $i => $a ) : ?>
				<li class="vpg-alert flex items-start gap-3 rounded-sm border border-line bg-surface p-3.5" style="--c:<?php echo esc_attr( $a[1] ); ?>" <?php vp_m( 'o:0 y:-14 s:.97 t:.5 e:spring d:' . ( 0.2 + $i * 0.55 ) ); ?>>
					<span class="grid h-8 w-8 shrink-0 place-items-center rounded-sm" style="<?php echo esc_attr( vp_tint_style( $a[1] ) ); ?>"><?php vp_i( $a[0], 16 ); ?></span>
					<span class="min-w-0 flex-1">
						<span class="block text-sm font-semibold leading-snug"><?php echo esc_html( $a[2] ); ?></span>
						<span class="block text-xs text-muted"><?php echo esc_html( $a[3] ); ?></span>
					</span>
					<span class="flex shrink-0 flex-col items-end gap-1">
						<span class="rounded-sm px-2 py-0.5 text-[11px] font-semibold" style="<?php echo esc_attr( vp_tint_style( $a[1] ) ); ?>"><?php echo esc_html( $a[4] ); ?></span>
						<span class="vpg-plan <?php echo 'paid' === $a[5] ? 'is-paid' : ''; ?>"><?php echo esc_html( 'paid' === $a[5] ? $t['paid'] : $t['free'] ); ?></span>
					</span>
				</li>
			<?php endforeach; ?>
		</ul>
		<div class="mt-3 flex items-center gap-3 rounded-sm bg-ink px-4 py-3 text-bg" <?php vp_m( 'o:0 x:24 t:.5 d:' . ( 0.4 + count( $alerts ) * 0.55 ) ); ?>>
			<?php vp_i( 'mail', 16 ); ?>
			<span class="min-w-0 flex-1 text-sm"><span class="block font-semibold"><?php echo esc_html( $t['email'] ); ?></span><span class="block text-xs" style="opacity:.65"><?php echo esc_html( $t['email_s'] ); ?></span></span>
			<?php vp_i( 'check', 16 ); ?>
		</div>
		<div class="mt-5 text-[11px] font-semibold uppercase tracking-widest text-muted"><?php echo esc_html( $t['log'] ); ?></div>
		<ol class="vpg-log mt-2">
			<?php foreach ( $log as $i => $l ) : ?>
				<li class="<?php echo $l[2] ? 'is-done' : ''; ?>" <?php vp_m( 'o:0 t:.4 d:' . ( 0.8 + count( $alerts ) * 0.55 + $i * 0.25 ) ); ?>>
					<span class="font-mono text-xs text-muted"><?php echo esc_html( $l[0] ); ?></span>
					<span class="text-sm"><?php echo esc_html( $l[1] ); ?></span>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</div>
