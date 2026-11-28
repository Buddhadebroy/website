<?php
/**
 * Scene: Speed score gauge that climbs after the fix, mobile and desktop.
 * Data comes from the page graphic file that calls it (vpx_scene). Edit the text there.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;
$d = isset( $args['d'] ) ? (array) $args['d'] : array();
$from = (int) ( $d['from'] ?? 38 );
$to   = (int) ( $d['to'] ?? 91 );
$d['_loop'] = $d['_loop'] ?? 10000;
vpx_open( $d, 'zap' );
?>
<div data-fx="swap" data-after="1.4" style="text-align:center">
	<div class="vpx-gauge" style="max-width:240px">
		<svg viewBox="0 0 120 68" aria-hidden="true"><path d="M10,62 A50,50 0 0 1 110,62" fill="none" stroke="var(--line)" stroke-width="9" stroke-linecap="round"></path>
			<path data-when="before" d="M10,62 A50,50 0 0 1 110,62" fill="none" stroke="var(--rose)" stroke-width="9" stroke-linecap="round" pathLength="100" stroke-dasharray="<?php echo $from; ?> 100" <?php vp_m( 'P:1 t:.9 e:ease' ); ?>></path>
			<path data-when="after" hidden d="M10,62 A50,50 0 0 1 110,62" fill="none" stroke="var(--sage)" stroke-width="9" stroke-linecap="round" pathLength="100" stroke-dasharray="<?php echo $to; ?> 100" <?php vp_m( 'P:1 t:1.1 e:ease' ); ?>></path></svg>
		<div class="vpx-gauge-v" style="font-size:34px"><span data-when="before"><?php echo $from; ?></span><span data-when="after" hidden <?php vp_m( 'N:' . $to . ' t:1' ); ?>><?php echo $to; ?></span></div>
	</div>
	<div class="vpx-small" style="margin-top:8px"><span data-when="before" style="color:var(--rose);font-weight:600"><?php echo esc_html( $d['before'] ?? 'Before' ); ?></span><span data-when="after" hidden style="color:var(--sage);font-weight:600"><?php echo esc_html( $d['after'] ?? 'After the fix' ); ?></span> · <?php echo esc_html( $d['device'] ?? 'Mobile' ); ?></div>
</div>
<div class="vpx-grid2">
	<?php foreach ( (array) ( $d['stats'] ?? array() ) as $i => $s ) : ?>
	<div class="vpx-line"><div class="vpx-small vpx-muted"><?php echo esc_html( $s[0] ); ?></div><div class="vpx-row" style="margin-top:3px;gap:6px"><span class="vpx-muted" style="text-decoration:line-through"><?php echo esc_html( $s[1] ); ?></span><?php vpx_i( 'arrow-right', 12 ); ?><b style="color:var(--sage)"><?php echo esc_html( $s[2] ); ?></b></div></div>
	<?php endforeach; ?>
</div>
<?php vpx_close( $d );
