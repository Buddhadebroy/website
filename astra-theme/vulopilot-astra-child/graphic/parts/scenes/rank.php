<?php
/**
 * Scene: Keyword rank tracker: position before and after, with the change.
 * Data comes from the page graphic file that calls it (vpx_scene). Edit the text there.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;
$d = isset( $args['d'] ) ? (array) $args['d'] : array();
$rows = (array) ( $d['rows'] ?? array() );
vpx_open( $d, 'trending-up' );
?>
<table class="vpx-table">
	<thead><tr><th><?php echo esc_html( $d['col_kw'] ?? 'Keyword' ); ?></th><th class="r"><?php echo esc_html( $d['col_was'] ?? 'Was' ); ?></th><th class="r"><?php echo esc_html( $d['col_now'] ?? 'Now' ); ?></th><th class="r"><?php echo esc_html( $d['col_chg'] ?? 'Change' ); ?></th></tr></thead>
	<tbody>
	<?php foreach ( $rows as $i => $r ) : $chg = (int) $r[1] - (int) $r[2]; ?>
		<tr <?php vp_m( 'o:0 y:6 t:.4 d:' . ( $i * 0.1 ) ); ?>>
			<td><div class="vpx-trunc" style="max-width:220px;font-weight:600"><?php echo esc_html( $r[0] ); ?></div><?php if ( ! empty( $r[3] ) ) : ?><div class="vpx-small vpx-muted"><?php echo esc_html( $r[3] ); ?></div><?php endif; ?></td>
			<td class="r"><span class="vpx-pos vpx-muted"><?php echo (int) $r[1]; ?></span></td>
			<td class="r"><span class="vpx-pos" style="<?php echo esc_attr( vp_tint_style( $chg > 0 ? 'var(--sage)' : 'var(--rose)' ) ); ?>" <?php vp_m( 'N:' . (int) $r[2] . ' t:1 d:' . ( 0.3 + $i * 0.1 ) ); ?>><?php echo (int) $r[2]; ?></span></td>
			<td class="r"><span class="<?php echo $chg >= 0 ? 'vpx-up' : 'vpx-down'; ?>"><?php vpx_i( $chg >= 0 ? 'arrow-up' : 'arrow-down', 12 ); ?><?php echo abs( $chg ); ?></span></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
<?php if ( ! empty( $d['note'] ) ) : ?><p class="vpx-small vpx-muted" style="margin:0"><?php echo esc_html( $d['note'] ); ?></p><?php endif; ?>
<?php vpx_close( $d );
