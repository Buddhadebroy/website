<?php
/**
 * Scene: Content backlog board: ideas, drafts and published, fed by real search questions.
 * Data comes from the page graphic file that calls it (vpx_scene). Edit the text there.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;
$d = isset( $args['d'] ) ? (array) $args['d'] : array();
$cols = (array) ( $d['cols'] ?? array() );
vpx_open( $d, 'layout' );
?>
<div class="vpx-kan">
	<?php foreach ( $cols as $ci => $c ) : ?>
	<div class="vpx-kan-col">
		<div class="vpx-row" style="justify-content:space-between"><span class="vpx-cap"><?php echo esc_html( $c[0] ); ?></span><span class="vpx-small vpx-muted vpx-num"><?php echo count( (array) $c[1] ); ?></span></div>
		<?php foreach ( (array) $c[1] as $ki => $k ) : ?>
		<div class="vpx-kan-card" <?php vp_m( 'o:0 y:8 t:.4 e:spring d:' . ( $ci * 0.2 + $ki * 0.1 ) ); ?>><?php echo esc_html( is_array( $k ) ? $k[0] : $k ); ?><?php if ( is_array( $k ) && ! empty( $k[1] ) ) : ?><div class="vpx-small" style="margin-top:4px;color:<?php echo esc_attr( vpx_col( $k[2] ?? 'good' ) ); ?>;font-weight:600"><?php echo esc_html( $k[1] ); ?></div><?php endif; ?></div>
		<?php endforeach; ?>
	</div>
	<?php endforeach; ?>
</div>
<?php vpx_close( $d );
