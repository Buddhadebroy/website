<?php
/**
 * Scene: Heavy images found and how much each one shrinks.
 * Data comes from the page graphic file that calls it (vpx_scene). Edit the text there.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;
$d = isset( $args['d'] ) ? (array) $args['d'] : array();
$rows = (array) ( $d['rows'] ?? array() );
vpx_open( $d, 'image' );
?>
<ul class="vpx-list">
	<?php foreach ( $rows as $i => $r ) : $pct = max( 3, min( 100, (int) ( $r[3] ?? 10 ) ) ); ?>
	<li class="vpx-line" <?php vp_m( 'o:0 y:6 t:.35 d:' . ( $i * 0.1 ) ); ?>>
		<div class="vpx-row"><span class="vpx-ic" style="<?php echo esc_attr( vp_tint_style( 'var(--lilac)' ) ); ?>"><?php vpx_i( 'image', 14 ); ?></span><span class="vpx-grow vpx-mono vpx-trunc"><?php echo esc_html( $r[0] ); ?></span><span class="vpx-small"><span class="vpx-muted" style="text-decoration:line-through"><?php echo esc_html( $r[1] ); ?></span> <b style="color:var(--sage)"><?php echo esc_html( $r[2] ); ?></b></span></div>
		<div class="vpx-bar" style="margin-top:8px"><i style="width:100%;background:color-mix(in srgb,var(--rose) 30%,white)"></i><i <?php vp_m( 'W:' . $pct . '% t:.9 e:ease d:' . ( 0.5 + $i * 0.1 ), 'background:var(--sage)' ); ?>></i></div>
	</li>
	<?php endforeach; ?>
</ul>
<div class="vpx-soft vpx-row"><span class="vpx-grow"><?php echo esc_html( $d['total_label'] ?? 'Page weight' ); ?></span><b class="vpx-num" style="color:var(--sage)"><?php echo esc_html( $d['total'] ?? '' ); ?></b></div>
<?php vpx_close( $d );
