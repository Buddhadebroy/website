<?php
/**
 * Scene: URL list with HTTP status codes and the IndexNow ping.
 * Data comes from the page graphic file that calls it (vpx_scene). Edit the text there.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;
$d = isset( $args['d'] ) ? (array) $args['d'] : array();
$rows = (array) ( $d['rows'] ?? array() );
vpx_open( $d, 'list-tree' );
?>
<ul class="vpx-list">
	<?php foreach ( $rows as $i => $r ) : $c = vpx_col( $r[1] ); ?>
	<li class="vpx-line vpx-row" <?php vp_m( 'o:0 x:-8 t:.35 d:' . ( $i * 0.09 ) ); ?>>
		<span class="vpx-tag vpx-mono" style="<?php echo esc_attr( vp_tint_style( $c ) ); ?>;min-width:62px;justify-content:center"><?php echo esc_html( $r[1] ); ?></span>
		<span class="vpx-grow"><span class="vpx-mono vpx-trunc" style="display:block"><?php echo esc_html( $r[0] ); ?></span><?php if ( ! empty( $r[2] ) ) : ?><span class="vpx-small vpx-muted"><?php echo esc_html( $r[2] ); ?></span><?php endif; ?></span>
	</li>
	<?php endforeach; ?>
</ul>
<?php if ( ! empty( $d['ping'] ) ) : ?>
<div class="vpx-soft vpx-row" <?php vp_m( 'o:0 y:8 t:.45 d:.8' ); ?>><span class="vpg-live"></span><span class="vpx-grow"><?php echo esc_html( $d['ping'] ); ?></span><span class="vpx-tag" style="<?php echo esc_attr( vp_tint_style( 'var(--sage)' ) ); ?>"><?php echo esc_html( $d['ping_tag'] ?? 'Sent' ); ?></span></div>
<?php endif; ?>
<?php vpx_close( $d );
