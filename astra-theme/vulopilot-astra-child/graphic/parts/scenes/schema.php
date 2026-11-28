<?php
/**
 * Scene: Structured data (JSON-LD) next to the rich result it produces.
 * Data comes from the page graphic file that calls it (vpx_scene). Edit the text there.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;
$d = isset( $args['d'] ) ? (array) $args['d'] : array();
$lines = (array) ( $d['lines'] ?? array() );
vpx_open( $d, 'code' );
?>
<div class="vpx-code" style="padding-top:10px">
	<?php foreach ( $lines as $i => $l ) : ?><div class="vpx-code-line <?php echo esc_attr( ! empty( $l[1] ) ? 'is-' . $l[1] : '' ); ?>" <?php vp_m( 'o:0 t:.25 d:' . ( $i * 0.06 ) ); ?>><b><?php echo (int) $i + 1; ?></b><span><?php echo esc_html( $l[0] ); ?></span></div><?php endforeach; ?>
</div>
<div class="vpx-line" <?php vp_m( 'o:0 y:10 t:.5 e:spring d:.9' ); ?>>
	<div class="vpx-serp-site"><span class="vpx-fav"><?php vpx_i( 'store', 11 ); ?></span><span><?php echo esc_html( $d['crumb'] ?? '' ); ?></span></div>
	<div class="vpx-serp-title"><?php echo esc_html( $d['title'] ?? '' ); ?></div>
	<div class="vpx-row vpx-small" style="margin-top:4px;flex-wrap:wrap;gap:8px"><span class="vpx-stars">★★★★★</span><span class="vpx-muted"><?php echo esc_html( $d['rating'] ?? '' ); ?></span><span style="font-weight:600"><?php echo esc_html( $d['price'] ?? '' ); ?></span><span style="color:var(--sage);font-weight:600"><?php echo esc_html( $d['stock'] ?? '' ); ?></span></div>
</div>
<?php vpx_close( $d );
