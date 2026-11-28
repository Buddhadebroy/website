<?php
/**
 * Monitor Website Health › every check rolled into one health score, with trend bars.
 * Graphic name: monitor-website-health-2  ·  Shortcode: [vp_monitor_website_health_2]  ·  /use-cases/monitor-website-health/
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$args = isset( $args ) && is_array( $args ) ? $args : array();
$t    = array_merge(
	array(
		'title' => 'Health score',
		'range' => 'Last 8 weeks',
		'score' => 'Today',
		'trend' => '+54 since you started',
		'areas' => 'By area',
	),
	(array) ( $args['text'] ?? array() )
);
$weeks = $args['weeks'] ?? array( 28, 36, 43, 51, 59, 67, 74, 82 );
// icon, area, score.
$areas = $args['areas'] ?? array(
	array( 'alert-triangle', 'Problems appear between checks.', 45 ),
	array( 'filter', 'Backups are forgotten.', 67 ),
	array( 'list-tree', 'No audit trail.', 78 ),
);
$now = (int) end( $weeks );
$min = max( 0, min( $weeks ) - 20 );
$pct = function ( $v ) use ( $min ) {
	return (int) round( 12 + ( $v - $min ) / max( 1, 100 - $min ) * 88 );
};
$col = function ( $v ) {
	return $v >= 80 ? 'var(--sage)' : ( $v >= 60 ? 'var(--flare)' : 'var(--rose)' );
};
?>
<div class="card overflow-hidden" data-vpg="loop" data-every="11000">
	<div class="flex flex-wrap items-center justify-between gap-2 border-b border-line px-5 py-3.5">
		<div class="flex items-center gap-2.5 text-sm font-semibold"><span class="grid h-7 w-7 place-items-center rounded-sm bg-sage-soft text-sage"><?php vp_i( 'heart-pulse', 15 ); ?></span><?php echo esc_html( $t['title'] ); ?></div>
		<span class="font-mono text-[11px] text-muted"><?php echo esc_html( $t['range'] ); ?></span>
	</div>
	<div class="vpg-health p-5">
		<div class="flex flex-col items-center justify-center gap-2 text-center">
			<?php echo vp_ring( $now, 116, $col( $now ) ); // phpcs:ignore ?>
			<span class="text-xs text-muted"><?php echo esc_html( $t['score'] ); ?></span>
			<span class="vpg-chip is-good" <?php vp_m( 'o:0 y:6 t:.4 d:1.4' ); ?>><?php vp_i( 'trending-up', 12 ); ?><?php echo esc_html( $t['trend'] ); ?></span>
		</div>
		<div class="vpg-bars" aria-hidden="true">
			<?php foreach ( $weeks as $i => $w ) : ?>
				<span class="vpg-bar-col"><span class="vpg-bar" <?php vp_m( 'H:' . $pct( $w ) . '% t:.7 e:ease d:' . ( 0.1 + $i * 0.09 ), 'background:' . ( $i === count( $weeks ) - 1 ? 'var(--sage)' : 'var(--sage-soft)' ) ); ?>></span></span>
			<?php endforeach; ?>
		</div>
	</div>
	<div class="border-t border-line px-5 py-4">
		<div class="text-[11px] font-semibold uppercase tracking-widest text-muted"><?php echo esc_html( $t['areas'] ); ?></div>
		<ul class="vpg-areas mt-3">
			<?php foreach ( $areas as $i => $a ) : ?>
				<li class="flex items-center gap-2.5 rounded-sm border border-line bg-surface px-3 py-2" <?php vp_m( 'o:0 y:8 t:.4 d:' . ( 0.6 + $i * 0.08 ) ); ?>>
					<span class="grid h-7 w-7 shrink-0 place-items-center rounded-sm" style="<?php echo esc_attr( vp_tint_style( $col( $a[2] ) ) ); ?>"><?php vp_i( $a[0], 14 ); ?></span>
					<span class="min-w-0 flex-1 truncate text-sm font-semibold"><?php echo esc_html( $a[1] ); ?></span>
					<span class="text-sm font-semibold tabular-nums" style="color:<?php echo esc_attr( $col( $a[2] ) ); ?>"><?php echo (int) $a[2]; ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
