<?php
/**
 * Scene: Automation built from When / If / Then blocks, with its last runs.
 * Data comes from the page graphic file that calls it (vpx_scene). Edit the text there.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;
$d = isset( $args['d'] ) ? (array) $args['d'] : array();
$nodes = (array) ( $d['nodes'] ?? array() );
$kc    = array( 'When' => 'var(--sage)', 'If' => 'var(--lilac)', 'Then' => 'var(--flare)', 'And' => 'var(--flare)' );
vpx_open( $d, 'workflow' );
?>
<div class="vpx-flow">
	<?php foreach ( $nodes as $i => $n ) : $c = $kc[ $n[0] ] ?? 'var(--sage)'; ?>
	<?php if ( $i ) : ?><span class="vpx-link"></span><?php endif; ?>
	<div class="vpx-node" <?php vp_m( 'o:0 y:10 t:.45 e:spring d:' . ( $i * 0.3 ) ); ?>>
		<span class="vpx-ic" style="<?php echo esc_attr( vp_tint_style( $c ) ); ?>;width:32px;height:32px"><?php vpx_i( $n[1], 15 ); ?></span>
		<span class="vpx-grow"><span class="vpx-node-k" style="color:<?php echo esc_attr( $c ); ?>"><?php echo esc_html( $n[0] ); ?></span><span style="display:block;font-weight:600"><?php echo esc_html( $n[2] ); ?></span></span>
	</div>
	<?php endforeach; ?>
</div>
<?php if ( ! empty( $d['runs'] ) ) : ?>
<div class="vpx-row" style="gap:6px;flex-wrap:wrap"><span class="vpx-cap" style="margin-right:4px"><?php echo esc_html( $d['runs_label'] ?? 'Last runs' ); ?></span><?php foreach ( (array) $d['runs'] as $i => $r ) : ?><span class="vpx-tag" style="<?php echo esc_attr( vp_tint_style( vpx_col( $r[1] ?? 'good' ) ) ); ?>" <?php vp_m( 'o:0 s:.8 t:.3 d:' . ( 1 + $i * 0.1 ) ); ?>><?php echo esc_html( $r[0] ); ?></span><?php endforeach; ?></div>
<?php endif; ?>
<?php vpx_close( $d );
