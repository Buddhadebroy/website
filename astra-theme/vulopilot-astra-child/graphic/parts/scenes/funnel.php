<?php
/**
 * Scene: Store funnel from product view to order, with the biggest drop called out.
 * Data comes from the page graphic file that calls it (vpx_scene). Edit the text there.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;
$d = isset( $args['d'] ) ? (array) $args['d'] : array();
$steps = (array) ( $d['steps'] ?? array() );
$max   = max( 1, (int) ( $steps[0][1] ?? 1 ) );
vpx_open( $d, 'shopping-cart' );
?>
<ul class="vpx-list" style="gap:10px">
	<?php foreach ( $steps as $i => $s ) : $w = max( 14, round( (int) $s[1] / $max * 100 ) ); $bad = ! empty( $s[2] ); ?>
	<li>
		<div class="vpx-row" style="justify-content:space-between;margin-bottom:4px"><span class="vpx-small" style="font-weight:600"><?php echo esc_html( $s[0] ); ?></span><?php if ( $bad ) : ?><span class="vpx-tag" style="<?php echo esc_attr( vp_tint_style( 'var(--rose)' ) ); ?>"><?php vpx_i( 'trending-down', 11 ); ?><?php echo esc_html( $s[2] ); ?></span><?php endif; ?></div>
		<div class="vpx-funnel-bar" <?php vp_m( 'W:' . $w . '% t:.8 e:ease d:' . ( $i * 0.15 ), 'background:' . ( $bad ? 'var(--rose)' : 'color-mix(in srgb, var(--sage) ' . ( 100 - $i * 14 ) . '%, var(--lilac))' ) ); ?>><?php echo esc_html( number_format_i18n( (int) $s[1] ) ); ?></div>
	</li>
	<?php endforeach; ?>
</ul>
<?php if ( ! empty( $d['note'] ) ) : ?><div class="vpx-soft vpx-row"><span class="vpg-no"><?php vpx_i( 'alert-triangle', 11 ); ?></span><span class="vpx-grow"><?php echo esc_html( $d['note'] ); ?></span></div><?php endif; ?>
<?php vpx_close( $d );
