<?php
/**
 * Scene: Every client site in one table with the same scores.
 * Data comes from the page graphic file that calls it (vpx_scene). Edit the text there.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;
$d = isset( $args['d'] ) ? (array) $args['d'] : array();
$rows = (array) ( $d['rows'] ?? array() );
$cols = (array) ( $d['cols'] ?? array( 'Site', 'SEO', 'Speed', 'Security', '' ) );
$sc   = function ( $v ) { return $v >= 80 ? 'var(--sage)' : ( $v >= 60 ? 'var(--lilac)' : 'var(--rose)' ); };
vpx_open( $d, 'layout-dashboard' );
?>
<table class="vpx-table">
	<thead><tr><?php foreach ( $cols as $i => $c ) : ?><th class="<?php echo $i ? 'r' : ''; ?>"><?php echo esc_html( $c ); ?></th><?php endforeach; ?></tr></thead>
	<tbody>
	<?php foreach ( $rows as $i => $r ) : ?>
	<tr <?php vp_m( 'o:0 y:6 t:.35 d:' . ( $i * 0.08 ) ); ?>>
		<td><div class="vpx-row" style="gap:8px"><span class="vpx-fav" style="width:20px;height:20px;font-size:9px"><?php echo esc_html( strtoupper( substr( $r[0], 0, 1 ) ) ); ?></span><span class="vpx-trunc" style="max-width:150px;font-weight:600"><?php echo esc_html( $r[0] ); ?></span></div></td>
		<?php for ( $k = 1; $k <= 3; $k++ ) : ?><td class="r"><span class="vpx-num" style="color:<?php echo esc_attr( $sc( (int) $r[ $k ] ) ); ?>"><?php echo (int) $r[ $k ]; ?></span></td><?php endfor; ?>
		<td class="r"><?php if ( ! empty( $r[4] ) ) : ?><span class="vpx-tag" style="<?php echo esc_attr( vp_tint_style( 'var(--rose)' ) ); ?>"><?php echo esc_html( $r[4] ); ?></span><?php else : ?><span class="vpg-ok"><?php vpx_i( 'check', 10 ); ?></span><?php endif; ?></td>
	</tr>
	<?php endforeach; ?>
	</tbody>
</table>
<?php if ( ! empty( $d['note'] ) ) : ?><div class="vpx-soft vpx-row vpx-small"><span class="vpx-grow"><?php echo esc_html( $d['note'] ); ?></span><span class="vpx-btn"><?php vpx_i( 'file-down', 11 ); ?><?php echo esc_html( $d['btn'] ?? 'Client report' ); ?></span></div><?php endif; ?>
<?php vpx_close( $d );
