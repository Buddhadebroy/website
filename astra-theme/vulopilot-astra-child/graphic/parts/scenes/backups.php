<?php
/**
 * Scene: Backup timeline with restore points and one-click restore.
 * Data comes from the page graphic file that calls it (vpx_scene). Edit the text there.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;
$d = isset( $args['d'] ) ? (array) $args['d'] : array();
$rows = (array) ( $d['rows'] ?? array() );
vpx_open( $d, 'database' );
?>
<div style="position:relative;padding-left:18px">
	<span style="position:absolute;left:5px;top:6px;bottom:6px;width:2px;background:var(--line)"></span>
	<ul class="vpx-list" style="gap:10px">
		<?php foreach ( $rows as $i => $r ) : $hi = ! empty( $r[3] ); ?>
		<li style="position:relative" <?php vp_m( 'o:0 x:-8 t:.35 d:' . ( $i * 0.1 ) ); ?>>
			<span style="position:absolute;left:-18px;top:12px;width:12px;height:12px;border-radius:999px;background:<?php echo $hi ? 'var(--sage)' : 'var(--surface)'; ?>;border:2px solid var(--sage)"></span>
			<div class="vpx-line vpx-row"<?php echo $hi ? ' style="border-color:var(--sage)"' : ''; ?>><span class="vpx-grow"><b style="display:block"><?php echo esc_html( $r[0] ); ?></b><span class="vpx-small vpx-muted"><?php echo esc_html( $r[1] ); ?></span></span><span class="vpx-small vpx-num vpx-muted"><?php echo esc_html( $r[2] ); ?></span><?php if ( $hi ) : ?><span class="vpx-btn"><?php vpx_i( 'rotate-ccw', 11 ); ?><?php echo esc_html( $d['restore'] ?? 'Restore' ); ?></span><?php endif; ?></div>
		</li>
		<?php endforeach; ?>
	</ul>
</div>
<?php vpx_close( $d );
