<?php
/**
 * Scene: Index coverage: indexed versus not indexed, with the reasons.
 * Data comes from the page graphic file that calls it (vpx_scene). Edit the text there.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;
$d = isset( $args['d'] ) ? (array) $args['d'] : array();
$total = max( 1, (int) ( $d['total'] ?? 100 ) );
$ok    = (int) ( $d['indexed'] ?? 0 );
$rs    = (array) ( $d['reasons'] ?? array() );
vpx_open( $d, 'list-checks' );
?>
<div class="vpx-row" style="justify-content:space-between;align-items:flex-end">
	<div><div class="vpx-cap"><?php echo esc_html( $d['ok_label'] ?? 'Indexed' ); ?></div><div class="vpx-big" style="margin-top:6px"><span <?php vp_m( 'N:' . $ok . ' t:1.2' ); ?>><?php echo $ok; ?></span><span class="vpx-muted" style="font-size:14px"> / <?php echo $total; ?></span></div></div>
	<div style="text-align:right"><div class="vpx-cap"><?php echo esc_html( $d['bad_label'] ?? 'Not indexed' ); ?></div><div class="vpx-big" style="margin-top:6px;color:var(--rose)"><?php echo $total - $ok; ?></div></div>
</div>
<div class="vpx-stack"><i <?php vp_m( 'W:' . round( $ok / $total * 100, 1 ) . '% t:1 e:ease', 'background:var(--sage)' ); ?>></i><?php foreach ( $rs as $r ) : ?><i <?php vp_m( 'W:' . round( (int) $r[1] / $total * 100, 1 ) . '% t:1 e:ease d:.3', 'background:' . vpx_col( $r[2] ?? 'bad' ) ); ?>></i><?php endforeach; ?></div>
<ul class="vpx-list">
	<?php foreach ( $rs as $i => $r ) : ?>
	<li class="vpx-line vpx-row" <?php vp_m( 'o:0 y:6 t:.4 d:' . ( 0.4 + $i * 0.1 ) ); ?>>
		<i class="vpx-dot" style="background:<?php echo esc_attr( vpx_col( $r[2] ?? 'bad' ) ); ?>"></i>
		<span class="vpx-grow"><?php echo esc_html( $r[0] ); ?><?php if ( ! empty( $r[3] ) ) : ?><span class="vpx-small vpx-muted" style="display:block"><?php echo esc_html( $r[3] ); ?></span><?php endif; ?></span>
		<span class="vpx-num"><?php echo (int) $r[1]; ?></span>
	</li>
	<?php endforeach; ?>
</ul>
<?php vpx_close( $d );
