<?php
/**
 * Scene: Checkout steps timed, before and after.
 * Data comes from the page graphic file that calls it (vpx_scene). Edit the text there.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;
$d = isset( $args['d'] ) ? (array) $args['d'] : array();
$rows = (array) ( $d['rows'] ?? array() );
$max  = 0.1;
foreach ( $rows as $r ) { $max = max( $max, (float) $r[1] ); }
vpx_open( $d, 'credit-card' );
?>
<div class="vpx-legend"><span><i class="vpx-dot" style="background:var(--rose)"></i><?php echo esc_html( $d['was'] ?? 'Before' ); ?></span><span><i class="vpx-dot" style="background:var(--sage)"></i><?php echo esc_html( $d['now'] ?? 'After' ); ?></span></div>
<ul class="vpx-list" style="gap:12px">
	<?php foreach ( $rows as $i => $r ) : ?>
	<li>
		<div class="vpx-row" style="justify-content:space-between;margin-bottom:5px"><span class="vpx-row" style="gap:8px"><span class="vpx-pos" style="height:20px;min-width:20px;font-size:10px"><?php echo (int) $i + 1; ?></span><b><?php echo esc_html( $r[0] ); ?></b></span><span class="vpx-small vpx-num"><span class="vpx-muted"><?php echo esc_html( $r[1] ); ?>s</span> → <span style="color:var(--sage)"><?php echo esc_html( $r[2] ); ?>s</span></span></div>
		<div class="vpx-bar" style="height:5px;margin-bottom:3px"><i <?php vp_m( 'W:' . round( (float) $r[1] / $max * 100 ) . '% t:.8 e:ease d:' . ( $i * 0.1 ), 'background:var(--rose)' ); ?>></i></div>
		<div class="vpx-bar" style="height:5px"><i <?php vp_m( 'W:' . round( (float) $r[2] / $max * 100 ) . '% t:.8 e:ease d:' . ( 0.5 + $i * 0.1 ), 'background:var(--sage)' ); ?>></i></div>
	</li>
	<?php endforeach; ?>
</ul>
<?php if ( ! empty( $d['note'] ) ) : ?><div class="vpx-soft vpx-small"><?php echo esc_html( $d['note'] ); ?></div><?php endif; ?>
<?php vpx_close( $d );
