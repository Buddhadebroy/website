<?php
/**
 * Scene: Monthly hours spent on routine work, by hand versus automated.
 * Data comes from the page graphic file that calls it (vpx_scene). Edit the text there.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;
$d = isset( $args['d'] ) ? (array) $args['d'] : array();
$rows = (array) ( $d['rows'] ?? array() );
$max  = 1;
$sum_a = 0; $sum_b = 0;
foreach ( $rows as $r ) { $max = max( $max, (float) $r[1] ); $sum_a += (float) $r[1]; $sum_b += (float) $r[2]; }
vpx_open( $d, 'hourglass' );
?>
<div class="vpx-grid2">
	<div class="vpx-soft"><div class="vpx-cap"><?php echo esc_html( $d['a_label'] ?? 'By hand' ); ?></div><div class="vpx-big" style="margin-top:6px;color:var(--rose)"><?php echo esc_html( round( $sum_a, 1 ) ); ?><span style="font-size:13px" class="vpx-muted"> <?php echo esc_html( $d['unit'] ?? 'h / month' ); ?></span></div></div>
	<div class="vpx-soft"><div class="vpx-cap"><?php echo esc_html( $d['b_label'] ?? 'With VuloPilot' ); ?></div><div class="vpx-big" style="margin-top:6px;color:var(--sage)"><?php echo esc_html( round( $sum_b, 1 ) ); ?><span style="font-size:13px" class="vpx-muted"> <?php echo esc_html( $d['unit'] ?? 'h / month' ); ?></span></div></div>
</div>
<ul class="vpx-list" style="gap:10px">
	<?php foreach ( $rows as $i => $r ) : ?>
	<li>
		<div class="vpx-row" style="justify-content:space-between;margin-bottom:5px"><span class="vpx-small" style="font-weight:600"><?php echo esc_html( $r[0] ); ?></span><span class="vpx-small vpx-muted"><?php echo esc_html( $r[1] ); ?>h → <b style="color:var(--sage)"><?php echo esc_html( $r[2] ); ?>h</b></span></div>
		<div class="vpx-bar" style="height:8px"><i <?php vp_m( 'W:' . round( (float) $r[1] / $max * 100 ) . '% t:.8 e:ease d:' . ( $i * 0.1 ), 'background:color-mix(in srgb,var(--rose) 35%,white)' ); ?>></i><i <?php vp_m( 'W:' . max( 2, round( (float) $r[2] / $max * 100 ) ) . '% t:.8 e:ease d:' . ( 0.6 + $i * 0.1 ), 'background:var(--sage)' ); ?>></i></div>
	</li>
	<?php endforeach; ?>
</ul>
<?php vpx_close( $d );
