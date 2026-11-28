<?php
/**
 * Scene: KPI tiles with sparklines: the numbers a marketer or owner reports on.
 * Data comes from the page graphic file that calls it (vpx_scene). Edit the text there.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;
$d = isset( $args['d'] ) ? (array) $args['d'] : array();
$tiles = (array) ( $d['tiles'] ?? array() );
vpx_open( $d, 'bar-chart3' );
?>
<div class="vpx-grid2">
	<?php foreach ( $tiles as $i => $t ) : $up = 'bad' !== ( $t[3] ?? 'good' ); $c = $up ? 'var(--sage)' : 'var(--rose)'; $neg = 0 === strpos( ltrim( (string) $t[2] ), '-' ); ?>
	<div class="vpx-tile" <?php vp_m( 'o:0 y:8 t:.4 d:' . ( $i * 0.1 ) ); ?>>
		<div class="vpx-small vpx-muted"><?php echo esc_html( $t[0] ); ?></div>
		<div class="vpx-row" style="justify-content:space-between;margin-top:4px"><span class="vpx-num" style="font-size:20px"><?php echo esc_html( $t[1] ); ?></span><span class="vpx-small <?php echo $up ? 'vpx-up' : 'vpx-down'; ?>"><?php vpx_i( $neg ? 'arrow-down' : 'arrow-up', 11 ); ?><?php echo esc_html( $t[2] ); ?></span></div>
		<svg viewBox="0 0 160 40" class="vpx-chart" style="margin-top:6px" aria-hidden="true"><path d="<?php echo esc_attr( vpx_path( (array) ( $t[4] ?? array( 3, 4, 3, 5, 6, 7 ) ), 160, 40, 4 ) ); ?>" fill="none" stroke="<?php echo esc_attr( $c ); ?>" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" <?php vp_m( 'P:1 t:1.3 e:ease d:' . ( 0.2 + $i * 0.1 ) ); ?>></path></svg>
	</div>
	<?php endforeach; ?>
</div>
<?php if ( ! empty( $d['note'] ) ) : ?><div class="vpx-soft vpx-small vpx-row"><span class="vpx-foot-ic"><?php vpx_i( 'lightbulb', 13 ); ?></span><span><?php echo esc_html( $d['note'] ); ?></span></div><?php endif; ?>
<?php vpx_close( $d );
