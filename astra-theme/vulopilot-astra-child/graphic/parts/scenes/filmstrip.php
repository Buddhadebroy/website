<?php
/**
 * Scene: Loading filmstrip: what a visitor sees second by second, before and after.
 * Data comes from the page graphic file that calls it (vpx_scene). Edit the text there.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;
$d = isset( $args['d'] ) ? (array) $args['d'] : array();
$times = (array) ( $d['times'] ?? array( '0.5s', '1.0s', '1.5s', '2.0s', '2.5s' ) );
$rows  = (array) ( $d['rows'] ?? array( array( 'Before', array( 0, 0, 1, 1, 2 ) ), array( 'After', array( 1, 2, 2, 2, 2 ) ) ) );
vpx_open( $d, 'timer' );
?>
<?php foreach ( $rows as $ri => $r ) : ?>
<div <?php vp_m( 'o:0 y:8 t:.45 d:' . ( $ri * 0.3 ) ); ?>>
	<div class="vpx-row" style="justify-content:space-between;margin-bottom:6px"><span class="vpx-cap" style="color:<?php echo esc_attr( $ri ? 'var(--sage)' : 'var(--rose)' ); ?>"><?php echo esc_html( $r[0] ); ?></span><span class="vpx-small vpx-muted"><?php echo esc_html( $r[2] ?? '' ); ?></span></div>
	<div class="vpx-film">
		<?php foreach ( $times as $ti => $tm ) : $st = (int) ( $r[1][ $ti ] ?? 2 ); ?>
		<div><div class="vpx-frame <?php echo 0 === $st ? 'is-blank' : ( 1 === $st ? 'is-part' : '' ); ?>"><i style="height:8px;width:60%;background:var(--sage-soft)"></i><i style="height:5px;width:90%"></i><i style="flex:1;background:linear-gradient(135deg,var(--sage-soft),var(--lilac-soft))"></i><i style="height:5px;width:80%"></i></div><div class="vpx-small vpx-muted" style="text-align:center;margin-top:3px"><?php echo esc_html( $tm ); ?></div></div>
		<?php endforeach; ?>
	</div>
</div>
<?php endforeach; ?>
<?php vpx_close( $d );
