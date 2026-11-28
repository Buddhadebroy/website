<?php
/**
 * Scene: Changes waiting for approval, each with approve and undo.
 * Data comes from the page graphic file that calls it (vpx_scene). Edit the text there.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;
$d = isset( $args['d'] ) ? (array) $args['d'] : array();
$rows = (array) ( $d['rows'] ?? array() );
$d['_loop'] = $d['_loop'] ?? 12000;
vpx_open( $d, 'clipboard-check' );
?>
<div data-fx="swap" data-after="2.2">
<ul class="vpx-list">
	<?php foreach ( $rows as $i => $r ) : ?>
	<li class="vpx-line" <?php vp_m( 'o:0 y:6 t:.35 d:' . ( $i * 0.1 ) ); ?>>
		<div class="vpx-row"><span class="vpx-ic"><?php vpx_i( $r[0], 14 ); ?></span><span class="vpx-grow"><b style="display:block" class="vpx-trunc"><?php echo esc_html( $r[1] ); ?></b><span class="vpx-small vpx-muted vpx-trunc" style="display:block"><?php echo esc_html( $r[2] ); ?></span></span>
			<span data-when="before" class="vpx-row" style="gap:6px"><span class="vpx-btn is-ghost"><?php echo esc_html( $d['skip'] ?? 'Skip' ); ?></span><span class="vpx-btn"><?php echo esc_html( $d['approve'] ?? 'Approve' ); ?></span></span>
			<span data-when="after" hidden class="vpx-row" style="gap:6px" <?php vp_m( 'o:0 s:.85 t:.35 e:spring d:' . ( $i * 0.2 ) ); ?>><span class="vpx-tag" style="<?php echo esc_attr( vp_tint_style( 'var(--sage)' ) ); ?>"><?php vpx_i( 'check', 11 ); ?><?php echo esc_html( $d['applied'] ?? 'Applied' ); ?></span><span class="vpx-small vpx-muted vpx-row" style="gap:3px"><?php vpx_i( 'undo2', 11 ); ?><?php echo esc_html( $d['undo'] ?? 'Undo' ); ?></span></span>
		</div>
	</li>
	<?php endforeach; ?>
</ul>
</div>
<?php vpx_close( $d );
