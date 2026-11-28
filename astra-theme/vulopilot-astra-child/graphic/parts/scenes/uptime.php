<?php
/**
 * Scene: 30 days of uptime with slow and down days, plus response time.
 * Data comes from the page graphic file that calls it (vpx_scene). Edit the text there.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;
$d = isset( $args['d'] ) ? (array) $args['d'] : array();
$days = (array) ( $d['days'] ?? array() );
vpx_open( $d, 'activity' );
?>
<div class="vpx-row" style="justify-content:space-between;align-items:flex-end">
	<div><div class="vpx-cap"><?php echo esc_html( $d['up_label'] ?? 'Uptime, 30 days' ); ?></div><div class="vpx-big" style="margin-top:6px"><?php echo esc_html( $d['uptime'] ?? '99.9%' ); ?></div></div>
	<div style="text-align:right"><div class="vpx-cap"><?php echo esc_html( $d['rt_label'] ?? 'Response' ); ?></div><div class="vpx-num" style="font-size:18px;margin-top:6px"><?php echo esc_html( $d['rt'] ?? '' ); ?></div></div>
</div>
<div class="vpx-up-bars">
	<?php for ( $i = 0; $i < 30; $i++ ) : $s = (int) ( $days[ $i ] ?? 1 ); ?><i class="<?php echo 3 === $s ? 'is-down' : ( 2 === $s ? 'is-slow' : '' ); ?>" <?php vp_m( 'H:34px t:.4 d:' . round( $i * 0.03, 2 ) ); ?>></i><?php endfor; ?>
</div>
<div class="vpx-row vpx-small vpx-muted" style="justify-content:space-between"><span><?php echo esc_html( $d['from'] ?? '30 days ago' ); ?></span><span><?php echo esc_html( $d['to'] ?? 'Today' ); ?></span></div>
<ul class="vpx-list">
	<?php foreach ( (array) ( $d['events'] ?? array() ) as $i => $e ) : ?>
	<li class="vpx-line vpx-row" <?php vp_m( 'o:0 y:6 t:.4 d:' . ( 0.9 + $i * 0.12 ) ); ?>><i class="vpx-dot" style="background:<?php echo esc_attr( vpx_col( $e[2] ?? 'bad' ) ); ?>"></i><span class="vpx-grow"><?php echo esc_html( $e[1] ); ?></span><span class="vpx-small vpx-muted"><?php echo esc_html( $e[0] ); ?></span></li>
	<?php endforeach; ?>
</ul>
<?php vpx_close( $d );
