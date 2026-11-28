<?php
/**
 * Scene: Live login and firewall log: attacks arriving and being blocked.
 * Data comes from the page graphic file that calls it (vpx_scene). Edit the text there.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;
$d = isset( $args['d'] ) ? (array) $args['d'] : array();
$rows = (array) ( $d['rows'] ?? array() );
$d['_loop'] = $d['_loop'] ?? 12000;
vpx_open( $d, 'shield' );
?>
<div class="vpx-grid3">
	<?php foreach ( (array) ( $d['stats'] ?? array() ) as $i => $s ) : ?><div class="vpx-soft"><div class="vpx-cap"><?php echo esc_html( $s[0] ); ?></div><div class="vpx-num" style="font-size:20px;margin-top:4px;color:<?php echo esc_attr( vpx_col( $s[2] ?? 'good' ) ); ?>" <?php vp_m( 'N:' . (int) $s[1] . ' t:1.4' ); ?>><?php echo (int) $s[1]; ?></div></div><?php endforeach; ?>
</div>
<div class="vpx-row" style="justify-content:space-between"><span class="vpx-cap"><?php echo esc_html( $d['log'] ?? 'Live log' ); ?></span><span class="vpx-row vpx-small vpx-muted" style="gap:6px"><span class="vpg-live"></span><?php echo esc_html( $d['live'] ?? 'Watching' ); ?></span></div>
<div class="vpx-log">
	<?php foreach ( $rows as $i => $r ) : $c = vpx_col( $r[3] ?? 'bad' ); ?>
	<div class="vpx-log-row" <?php vp_m( 'o:0 y:-10 t:.4 e:spring d:' . ( 0.3 + $i * 0.35 ) ); ?>>
		<span class="vpx-mono vpx-small vpx-muted"><?php echo esc_html( $r[0] ); ?></span>
		<span class="vpx-trunc"><b><?php echo esc_html( $r[1] ); ?></b> <span class="vpx-muted"><?php echo esc_html( $r[2] ); ?></span></span>
		<span class="vpx-tag" style="<?php echo esc_attr( vp_tint_style( $c ) ); ?>"><?php echo esc_html( $r[4] ?? 'Blocked' ); ?></span>
	</div>
	<?php endforeach; ?>
</div>
<?php vpx_close( $d );
