<?php
/**
 * Scene: Site checkup tiles: core, PHP, SSL, backups, disk, cron.
 * Data comes from the page graphic file that calls it (vpx_scene). Edit the text there.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;
$d = isset( $args['d'] ) ? (array) $args['d'] : array();
$tiles = (array) ( $d['tiles'] ?? array() );
vpx_open( $d, 'stethoscope' );
?>
<div class="vpx-grid2">
	<?php foreach ( $tiles as $i => $t ) : $c = vpx_col( $t[3] ?? 'good' ); ?>
	<div class="vpx-tile" <?php vp_m( 'o:0 s:.94 t:.4 e:spring d:' . ( $i * 0.08 ) ); ?>>
		<div class="vpx-tile-top"><span class="vpx-row" style="gap:8px"><span style="color:<?php echo esc_attr( $c ); ?>;display:inline-flex"><?php vpx_i( $t[0], 15 ); ?></span><span class="vpx-small vpx-muted"><?php echo esc_html( $t[1] ); ?></span></span><i class="vpx-dot" style="background:<?php echo esc_attr( $c ); ?>"></i></div>
		<div style="font-weight:600;font-size:15px;margin-top:8px" class="vpx-trunc"><?php echo esc_html( $t[2] ); ?></div>
		<?php if ( ! empty( $t[4] ) ) : ?><div class="vpx-small" style="color:<?php echo esc_attr( $c ); ?>;margin-top:2px"><?php echo esc_html( $t[4] ); ?></div><?php endif; ?>
	</div>
	<?php endforeach; ?>
</div>
<?php vpx_close( $d );
