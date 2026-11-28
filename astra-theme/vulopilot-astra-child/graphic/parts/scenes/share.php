<?php
/**
 * Scene: How often each AI engine mentions the site, before and after.
 * Data comes from the page graphic file that calls it (vpx_scene). Edit the text there.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;
$d = isset( $args['d'] ) ? (array) $args['d'] : array();
$rows = (array) ( $d['rows'] ?? array() );
vpx_open( $d, 'pie-chart' );
?>
<div class="vpx-legend"><span><i class="vpx-dot" style="background:var(--line)"></i><?php echo esc_html( $d['was'] ?? 'Before' ); ?></span><span><i class="vpx-dot" style="background:var(--sage)"></i><?php echo esc_html( $d['now'] ?? 'After 30 days' ); ?></span></div>
<ul class="vpx-list" style="gap:12px">
	<?php foreach ( $rows as $i => $r ) : ?>
	<li>
		<div class="vpx-row" style="justify-content:space-between;margin-bottom:6px"><span style="font-weight:600"><?php echo esc_html( $r[0] ); ?></span><span class="vpx-small"><span class="vpx-muted"><?php echo (int) $r[1]; ?>%</span> → <b style="color:var(--sage)"><?php echo (int) $r[2]; ?>%</b></span></div>
		<div class="vpx-bar" style="height:8px"><i style="width:<?php echo (int) $r[1]; ?>%;background:color-mix(in srgb,var(--muted) 35%,white)"></i><i <?php vp_m( 'W:' . (int) $r[2] . '% t:1.1 e:ease d:' . ( 0.3 + $i * 0.12 ), 'background:var(--sage);opacity:.9' ); ?>></i></div>
	</li>
	<?php endforeach; ?>
</ul>
<?php if ( ! empty( $d['note'] ) ) : ?><p class="vpx-small vpx-muted" style="margin:0"><?php echo esc_html( $d['note'] ); ?></p><?php endif; ?>
<?php vpx_close( $d );
