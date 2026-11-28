<?php
/**
 * Scene: The weekly report email, with the numbers that changed.
 * Data comes from the page graphic file that calls it (vpx_scene). Edit the text there.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;
$d = isset( $args['d'] ) ? (array) $args['d'] : array();
$kpis = (array) ( $d['kpis'] ?? array() );
vpx_open( $d, 'mail' );
?>
<div class="vpx-mail-h">
	<b><?php echo esc_html( $d['from_l'] ?? 'From' ); ?></b><span><?php echo esc_html( $d['from'] ?? 'VuloPilot' ); ?></span>
	<b><?php echo esc_html( $d['to_l'] ?? 'To' ); ?></b><span><?php echo esc_html( $d['to'] ?? '' ); ?></span>
	<b><?php echo esc_html( $d['subj_l'] ?? 'Subject' ); ?></b><span style="font-weight:600"><?php echo esc_html( $d['subject'] ?? '' ); ?></span>
</div>
<div class="vpx-sep"></div>
<div class="vpx-grid3">
	<?php foreach ( $kpis as $i => $k ) : $up = 'bad' !== ( $k[3] ?? 'good' ); $neg = 0 === strpos( ltrim( (string) $k[2] ), '-' ); ?>
	<div class="vpx-tile" <?php vp_m( 'o:0 y:8 t:.4 d:' . ( $i * 0.1 ) ); ?>><div class="vpx-small vpx-muted vpx-trunc"><?php echo esc_html( $k[0] ); ?></div><div class="vpx-num" style="font-size:20px;margin-top:4px"><?php echo esc_html( $k[1] ); ?></div><div class="vpx-small <?php echo $up ? 'vpx-up' : 'vpx-down'; ?>"><?php vpx_i( $neg ? 'arrow-down' : 'arrow-up', 11 ); ?><?php echo esc_html( $k[2] ); ?></div></div>
	<?php endforeach; ?>
</div>
<ul class="vpx-list">
	<?php foreach ( (array) ( $d['lines'] ?? array() ) as $i => $l ) : ?><li class="vpx-row vpx-small" <?php vp_m( 'o:0 x:-6 t:.35 d:' . ( 0.5 + $i * 0.1 ) ); ?>><span class="vpg-ok"><?php vpx_i( 'check', 10 ); ?></span><span class="vpx-muted"><?php echo esc_html( $l ); ?></span></li><?php endforeach; ?>
</ul>
<?php vpx_close( $d );
