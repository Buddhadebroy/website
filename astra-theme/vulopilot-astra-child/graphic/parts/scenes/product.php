<?php
/**
 * Scene: Product page check: what slows it down and what is missing for Google.
 * Data comes from the page graphic file that calls it (vpx_scene). Edit the text there.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;
$d = isset( $args['d'] ) ? (array) $args['d'] : array();
vpx_open( $d, 'shopping-bag' );
?>
<div class="vpx-prod">
	<div class="vpx-prod-img"><?php vpx_i( $d['img_icon'] ?? 'package', 34 ); ?></div>
	<div>
		<div style="font-weight:600;font-size:15px"><?php echo esc_html( $d['name'] ?? '' ); ?></div>
		<div class="vpx-row vpx-small" style="gap:8px;margin-top:4px;flex-wrap:wrap"><span class="vpx-stars">★★★★☆</span><span class="vpx-muted"><?php echo esc_html( $d['reviews'] ?? '' ); ?></span></div>
		<div style="font-weight:700;font-size:18px;margin-top:6px"><?php echo esc_html( $d['price'] ?? '' ); ?></div>
		<div class="vpx-row" style="gap:6px;margin-top:8px"><span class="vpx-btn"><?php vpx_i( 'shopping-cart', 11 ); ?><?php echo esc_html( $d['cta'] ?? 'Add to cart' ); ?></span></div>
	</div>
</div>
<ul class="vpx-list">
	<?php foreach ( (array) ( $d['checks'] ?? array() ) as $i => $c ) : $col = vpx_col( $c[2] ?? 'bad' ); ?>
	<li class="vpx-line vpx-row" <?php vp_m( 'o:0 y:6 t:.35 d:' . ( 0.3 + $i * 0.1 ) ); ?>><i class="vpx-dot" style="background:<?php echo esc_attr( $col ); ?>"></i><span class="vpx-grow"><?php echo esc_html( $c[0] ); ?></span><span class="vpx-small vpx-num" style="color:<?php echo esc_attr( $col ); ?>"><?php echo esc_html( $c[1] ); ?></span></li>
	<?php endforeach; ?>
</ul>
<?php vpx_close( $d );
