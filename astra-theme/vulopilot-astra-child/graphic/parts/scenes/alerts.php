<?php
/**
 * Scene: Notifications arriving (email, Slack, dashboard) with what happened and what to do.
 * Data comes from the page graphic file that calls it (vpx_scene). Edit the text there.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;
$d = isset( $args['d'] ) ? (array) $args['d'] : array();
$rows = (array) ( $d['rows'] ?? array() );
$d['_loop'] = $d['_loop'] ?? 13000;
vpx_open( $d, 'bell-ring' );
?>
<div class="vpx-log" style="gap:8px">
	<?php foreach ( $rows as $i => $r ) : $c = vpx_col( $r[3] ?? 'bad' ); ?>
	<div class="vpx-line" style="border-left:3px solid <?php echo esc_attr( $c ); ?>" <?php vp_m( 'o:0 x:16 t:.45 e:spring d:' . ( 0.2 + $i * 0.45 ) ); ?>>
		<div class="vpx-row"><span style="color:<?php echo esc_attr( $c ); ?>;display:inline-flex"><?php vpx_i( $r[0], 14 ); ?></span><b class="vpx-grow vpx-trunc"><?php echo esc_html( $r[1] ); ?></b><span class="vpx-small vpx-muted"><?php echo esc_html( $r[4] ?? '' ); ?></span></div>
		<div class="vpx-small vpx-muted" style="margin-top:4px;padding-left:24px"><?php echo esc_html( $r[2] ); ?></div>
	</div>
	<?php endforeach; ?>
</div>
<?php vpx_close( $d );
