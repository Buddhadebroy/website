<?php
/**
 * Scene: Request waterfall: what loads when, with the slow file flagged.
 * Data comes from the page graphic file that calls it (vpx_scene). Edit the text there.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;
$d = isset( $args['d'] ) ? (array) $args['d'] : array();
$rows = (array) ( $d['rows'] ?? array() );
vpx_open( $d, 'bar-chart3' );
?>
<div class="vpx-row" style="justify-content:space-between"><span class="vpx-cap"><?php echo esc_html( $d['col'] ?? 'Request' ); ?></span><span class="vpx-small vpx-muted"><?php echo esc_html( $d['scale'] ?? '0 · 1s · 2s · 3s · 4s' ); ?></span></div>
<div class="vpx-wf">
	<?php foreach ( $rows as $i => $r ) : $c = vpx_col( $r[4] ?? 'good' ); ?>
	<span class="vpx-row" style="gap:6px"><?php if ( 'good' !== ( $r[4] ?? 'good' ) ) : ?><i class="vpx-dot" style="background:<?php echo esc_attr( $c ); ?>"></i><?php endif; ?><span class="vpx-mono vpx-trunc" style="font-size:11px"><?php echo esc_html( $r[0] ); ?></span><span class="vpx-muted" style="font-size:10px;margin-left:auto;flex:none"><?php echo esc_html( $r[1] ); ?></span></span>
	<span class="vpx-wf-track"><i <?php vp_m( 'W:' . (float) $r[3] . '% t:.6 e:ease d:' . ( 0.1 + (float) $r[2] / 100 * 1.2 ), 'left:' . (float) $r[2] . '%;background:' . $c ); ?>></i></span>
	<?php endforeach; ?>
</div>
<div class="vpx-grid3">
	<?php foreach ( (array) ( $d['stats'] ?? array() ) as $s ) : ?><div class="vpx-soft"><div class="vpx-cap"><?php echo esc_html( $s[0] ); ?></div><div class="vpx-num" style="font-size:18px;margin-top:4px;color:<?php echo esc_attr( vpx_col( $s[2] ?? 'good' ) ); ?>"><?php echo esc_html( $s[1] ); ?></div></div><?php endforeach; ?>
</div>
<?php vpx_close( $d );
