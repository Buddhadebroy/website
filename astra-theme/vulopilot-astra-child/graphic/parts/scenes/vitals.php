<?php
/**
 * Scene: The three Core Web Vitals as gauges, with Google's good / needs work / poor bands.
 * Data comes from the page graphic file that calls it (vpx_scene). Edit the text there.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;
$d = isset( $args['d'] ) ? (array) $args['d'] : array();
$ms = (array) ( $d['metrics'] ?? array() );
vpx_open( $d, 'gauge' );
?>
<div class="vpx-grid3">
	<?php foreach ( $ms as $i => $m ) : $c = vpx_col( $m[3] ?? 'good' ); $p = max( 2, min( 100, (int) ( $m[4] ?? 50 ) ) ); ?>
	<div class="vpx-tile" style="text-align:center" <?php vp_m( 'o:0 y:10 t:.45 d:' . ( $i * 0.12 ) ); ?>>
		<div class="vpx-gauge">
			<svg viewBox="0 0 120 68" aria-hidden="true"><path d="M10,62 A50,50 0 0 1 110,62" fill="none" stroke="var(--line)" stroke-width="10" stroke-linecap="round"></path><path d="M10,62 A50,50 0 0 1 110,62" fill="none" stroke="<?php echo esc_attr( $c ); ?>" stroke-width="10" stroke-linecap="round" pathLength="100" stroke-dasharray="<?php echo $p; ?> 100" <?php vp_m( 'P:1 t:1.2 e:ease d:' . ( 0.2 + $i * 0.12 ) ); ?>></path></svg>
			<div class="vpx-gauge-v"><?php echo esc_html( $m[1] ); ?><small><?php echo esc_html( $m[2] ?? '' ); ?></small></div>
		</div>
		<div style="font-weight:600;margin-top:8px"><?php echo esc_html( $m[0] ); ?></div>
		<div class="vpx-small" style="color:<?php echo esc_attr( $c ); ?>;font-weight:600"><?php echo esc_html( $m[5] ?? '' ); ?></div>
	</div>
	<?php endforeach; ?>
</div>
<div>
	<div class="vpx-thresh"><i style="width:50%;background:var(--sage)"></i><i style="width:25%;background:var(--lilac)"></i><i style="width:25%;background:var(--rose)"></i></div>
	<div class="vpx-legend" style="margin-top:8px"><span><i class="vpx-dot" style="background:var(--sage)"></i><?php echo esc_html( $d['good'] ?? 'Good' ); ?></span><span><i class="vpx-dot" style="background:var(--lilac)"></i><?php echo esc_html( $d['mid'] ?? 'Needs work' ); ?></span><span><i class="vpx-dot" style="background:var(--rose)"></i><?php echo esc_html( $d['poor'] ?? 'Poor' ); ?></span></div>
</div>
<?php if ( ! empty( $d['note'] ) ) : ?><div class="vpx-soft vpx-small"><?php echo esc_html( $d['note'] ); ?></div><?php endif; ?>
<?php vpx_close( $d );
