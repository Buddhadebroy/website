<?php
/**
 * Scene: Internal link map: pages as nodes, the orphan page highlighted.
 * Data comes from the page graphic file that calls it (vpx_scene). Edit the text there.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;
$d = isset( $args['d'] ) ? (array) $args['d'] : array();
$nodes = (array) ( $d['nodes'] ?? array() );
$pos   = array( array( 150, 70 ), array( 55, 30 ), array( 245, 30 ), array( 50, 120 ), array( 250, 120 ), array( 150, 150 ), array( 268, 168 ) );
$orph  = (int) ( $d['orphan'] ?? 6 );
vpx_open( $d, 'network' );
?>
<svg class="vpx-chart vpx-graph" viewBox="0 0 300 190" aria-hidden="true">
	<?php foreach ( $nodes as $i => $n ) : if ( 0 === $i || $i === $orph || ! isset( $pos[ $i ] ) ) { continue; } ?>
	<line x1="150" y1="70" x2="<?php echo (int) $pos[ $i ][0]; ?>" y2="<?php echo (int) $pos[ $i ][1]; ?>" stroke="var(--lilac)" stroke-width="1.5" <?php vp_m( 'P:1 t:.8 d:' . ( $i * 0.1 ) ); ?>></line>
	<?php endforeach; ?>
	<?php if ( isset( $pos[ $orph ] ) ) : ?><line x1="150" y1="150" x2="<?php echo (int) $pos[ $orph ][0]; ?>" y2="<?php echo (int) $pos[ $orph ][1]; ?>" stroke="var(--sage)" stroke-width="2" stroke-dasharray="4 3" <?php vp_m( 'o:0 t:.5 d:1.4' ); ?>></line><?php endif; ?>
	<?php foreach ( $nodes as $i => $n ) : if ( ! isset( $pos[ $i ] ) ) { continue; } $o = $i === $orph; $r = 0 === $i ? 20 : 13; ?>
	<g <?php vp_m( 'o:0 s:.6 t:.45 e:spring d:' . ( $i * 0.08 ) ); ?>>
		<circle cx="<?php echo (int) $pos[ $i ][0]; ?>" cy="<?php echo (int) $pos[ $i ][1]; ?>" r="<?php echo $r; ?>" fill="<?php echo $o ? 'var(--apricot-soft)' : ( 0 === $i ? 'var(--sage)' : 'var(--sage-soft)' ); ?>" stroke="<?php echo $o ? 'var(--rose)' : 'var(--sage)'; ?>" stroke-width="1.5"></circle>
		<text x="<?php echo (int) $pos[ $i ][0]; ?>" y="<?php echo 0 === $i ? (int) $pos[ $i ][1] + 3 : (int) $pos[ $i ][1] + $r + 11; ?>" text-anchor="middle"<?php echo 0 === $i ? ' style="fill:#fff"' : ''; ?>><?php echo esc_html( $n ); ?></text>
	</g>
	<?php endforeach; ?>
</svg>
<div class="vpx-soft vpx-row" <?php vp_m( 'o:0 y:6 t:.4 d:1.2' ); ?>><span class="vpg-no"><?php vpx_i( 'link2', 11 ); ?></span><span class="vpx-grow"><?php echo esc_html( $d['finding'] ?? '' ); ?></span><span class="vpx-btn"><?php echo esc_html( $d['btn'] ?? 'Add link' ); ?></span></div>
<?php vpx_close( $d );
