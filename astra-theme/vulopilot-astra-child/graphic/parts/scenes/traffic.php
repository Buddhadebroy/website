<?php
/**
 * Scene: Line chart over time with a marker where the fix shipped.
 * Data comes from the page graphic file that calls it (vpx_scene). Edit the text there.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;
$d = isset( $args['d'] ) ? (array) $args['d'] : array();
$pts = (array) ( $d['points'] ?? array( 5, 6, 5, 4, 4, 3, 3, 5, 6, 7, 8, 9 ) );
$n   = count( $pts );
$mk  = min( max( 0, (int) ( $d['marker'] ?? 6 ) ), $n - 1 );
$x   = round( $mk / max( 1, $n - 1 ) * 300, 2 );
$path = vpx_path( $pts, 300, 100 );
vpx_open( $d, 'line-chart' );
?>
<div class="vpx-row" style="justify-content:space-between;align-items:flex-end">
	<div><div class="vpx-cap"><?php echo esc_html( $d['stat_label'] ?? '' ); ?></div><div class="vpx-big" style="margin-top:6px"><span <?php if ( (float) ( $d['stat'] ?? 0 ) === (float) (int) ( $d['stat'] ?? 0 ) ) { vp_m( 'N:' . (int) $d['stat'] . ' t:1.4' ); } ?>><?php echo esc_html( $d['stat'] ?? '' ); ?></span><?php echo esc_html( $d['stat_suffix'] ?? '' ); ?></div></div>
	<span class="vpx-tag" style="<?php echo esc_attr( vp_tint_style( vpx_col( $d['delta_state'] ?? 'good' ) ) ); ?>"><?php vpx_i( 'good' === ( $d['delta_state'] ?? 'good' ) ? 'trending-up' : 'trending-down', 12 ); ?><?php echo esc_html( $d['delta'] ?? '' ); ?></span>
</div>
<svg class="vpx-chart" viewBox="0 -4 300 122" aria-hidden="true">
	<?php for ( $g = 0; $g < 4; $g++ ) : ?><line x1="0" x2="300" y1="<?php echo 6 + $g * 30; ?>" y2="<?php echo 6 + $g * 30; ?>" stroke="var(--line)" stroke-width="1"></line><?php endfor; ?>
	<line x1="<?php echo esc_attr( $x ); ?>" x2="<?php echo esc_attr( $x ); ?>" y1="0" y2="100" stroke="var(--flare)" stroke-dasharray="3 3"></line>
	<path d="<?php echo esc_attr( $path . 'L300,100L0,100Z' ); ?>" fill="var(--sage)" opacity=".07"></path>
	<path d="<?php echo esc_attr( $path ); ?>" fill="none" stroke="var(--sage)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" <?php vp_m( 'P:1 t:1.6 e:ease' ); ?>></path>
	<text x="<?php echo esc_attr( min( 230, $x + 5 ) ); ?>" y="10" style="fill:var(--flare);font-weight:600"><?php echo esc_html( $d['marker_label'] ?? '' ); ?></text>
	<?php foreach ( (array) ( $d['axis'] ?? array() ) as $i => $a ) : $ax = count( (array) $d['axis'] ) > 1 ? $i / ( count( (array) $d['axis'] ) - 1 ) * 300 : 0; ?><text x="<?php echo esc_attr( $ax ); ?>" y="116" text-anchor="<?php echo 0 === $i ? 'start' : ( $ax >= 300 ? 'end' : 'middle' ); ?>"><?php echo esc_html( $a ); ?></text><?php endforeach; ?>
</svg>
<?php if ( ! empty( $d['legend'] ) ) : ?><div class="vpx-legend"><?php foreach ( (array) $d['legend'] as $l ) : ?><span><i class="vpx-dot" style="background:<?php echo esc_attr( vpx_col( $l[1] ) ); ?>"></i><?php echo esc_html( $l[0] ); ?></span><?php endforeach; ?></div><?php endif; ?>
<?php vpx_close( $d );
