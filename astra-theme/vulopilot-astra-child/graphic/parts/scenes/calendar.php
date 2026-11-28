<?php
/**
 * Scene: A week of scheduled jobs: scans, backups, reports.
 * Data comes from the page graphic file that calls it (vpx_scene). Edit the text there.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;
$d = isset( $args['d'] ) ? (array) $args['d'] : array();
$days  = (array) ( $d['days'] ?? array( 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun' ) );
$jobs  = (array) ( $d['jobs'] ?? array() );
$today = (int) ( $d['today'] ?? 2 );
vpx_open( $d, 'calendar-clock' );
?>
<div class="vpx-cal">
	<?php foreach ( $days as $di => $day ) : $has = false; foreach ( $jobs as $j ) { if ( (int) $j[0] === $di ) { $has = true; } } ?>
	<div class="vpx-cal-day<?php echo $di === $today ? ' is-today' : ''; ?><?php echo $has ? '' : ' is-empty'; ?>">
		<span class="vpx-cal-d"><?php echo esc_html( $day ); ?></span>
		<?php foreach ( $jobs as $ji => $j ) : if ( (int) $j[0] !== $di ) { continue; } $c = vpx_col( $j[2] ?? 'good' ); ?>
		<span class="vpx-job" style="<?php echo esc_attr( vp_tint_style( $c ) ); ?>" <?php vp_m( 'o:0 s:.8 t:.35 e:spring d:' . ( 0.1 + $ji * 0.08 ) ); ?>><?php echo esc_html( $j[1] ); ?></span>
		<?php endforeach; ?>
	</div>
	<?php endforeach; ?>
</div>
<?php if ( ! empty( $d['next'] ) ) : ?><div class="vpx-soft vpx-row"><span class="vpg-live"></span><span class="vpx-grow"><?php echo esc_html( $d['next'] ); ?></span><span class="vpx-small vpx-muted"><?php echo esc_html( $d['next_time'] ?? '' ); ?></span></div><?php endif; ?>
<?php vpx_close( $d );
