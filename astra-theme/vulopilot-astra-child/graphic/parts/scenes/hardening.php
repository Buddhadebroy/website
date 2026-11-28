<?php
/**
 * Scene: Security settings switching on one after another.
 * Data comes from the page graphic file that calls it (vpx_scene). Edit the text there.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;
$d = isset( $args['d'] ) ? (array) $args['d'] : array();
$rows = (array) ( $d['rows'] ?? array() );
$d['_loop'] = $d['_loop'] ?? 12000;
vpx_open( $d, 'lock' );
?>
<div data-fx="swap" data-after="1.2">
<ul class="vpx-list">
	<?php foreach ( $rows as $i => $r ) : $was = ! empty( $r[2] ); ?>
	<li class="vpx-line vpx-row">
		<span class="vpx-ic"><?php vpx_i( $r[0], 14 ); ?></span>
		<span class="vpx-grow"><span style="display:block;font-weight:600"><?php echo esc_html( $r[1] ); ?></span><?php if ( ! empty( $r[3] ) ) : ?><span class="vpx-small vpx-muted"><?php echo esc_html( $r[3] ); ?></span><?php endif; ?></span>
		<span class="vpx-toggle<?php echo $was ? ' is-on' : ''; ?>" data-when="before"></span>
		<span class="vpx-toggle is-on" data-when="after" hidden <?php vp_m( 'o:.3 t:.3 d:' . ( $i * 0.3 ) ); ?>></span>
	</li>
	<?php endforeach; ?>
</ul>
</div>
<?php if ( ! empty( $d['score'] ) ) : ?><div class="vpx-soft vpx-row"><span class="vpx-grow"><?php echo esc_html( $d['score_label'] ?? 'Security score' ); ?></span><b class="vpx-num" style="color:var(--sage)" <?php vp_m( 'N:' . (int) $d['score'] . ' t:2' ); ?>><?php echo (int) $d['score']; ?></b><span class="vpx-muted">/100</span></div><?php endif; ?>
<?php vpx_close( $d );
