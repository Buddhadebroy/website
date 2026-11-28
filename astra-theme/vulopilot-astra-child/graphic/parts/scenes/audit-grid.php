<?php
/**
 * Scene: Audit result split into categories, each with its own issue count.
 * Data comes from the page graphic file that calls it (vpx_scene). Edit the text there.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;
$d = isset( $args['d'] ) ? (array) $args['d'] : array();
$tiles = (array) ( $d['tiles'] ?? array() );
vpx_open( $d, 'layout-grid' );
?>
<div class="vpx-row" style="justify-content:space-between">
	<div><div class="vpx-cap"><?php echo esc_html( $d['total_label'] ?? 'Issues found' ); ?></div><div class="vpx-big" style="margin-top:6px"><span <?php vp_m( 'N:' . (int) ( $d['total'] ?? 0 ) . ' t:1.2' ); ?>><?php echo (int) ( $d['total'] ?? 0 ); ?></span></div></div>
	<?php echo vp_ring( (int) ( $d['score'] ?? 60 ), 78, vpx_col( ( $d['score'] ?? 60 ) >= 80 ? 'good' : ( ( $d['score'] ?? 60 ) >= 60 ? 'warn' : 'bad' ) ) ); // phpcs:ignore ?>
</div>
<div class="vpx-grid3">
	<?php foreach ( $tiles as $i => $t ) : $c = vpx_col( $t[3] ?? 'good' ); ?>
	<div class="vpx-tile" <?php vp_m( 'o:0 s:.94 t:.4 e:spring d:' . ( 0.1 + $i * 0.07 ) ); ?>>
		<div class="vpx-tile-top"><span style="color:<?php echo esc_attr( $c ); ?>;display:inline-flex"><?php vpx_i( $t[0], 15 ); ?></span><i class="vpx-dot" style="background:<?php echo esc_attr( $c ); ?>"></i></div>
		<div class="vpx-tile-n"><?php echo esc_html( $t[2] ); ?></div>
		<div class="vpx-tile-l"><?php echo esc_html( $t[1] ); ?></div>
	</div>
	<?php endforeach; ?>
</div>
<?php vpx_close( $d );
