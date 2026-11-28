<?php
/**
 * Scene: Writing editor with readability and keyword highlights plus the heading outline.
 * Data comes from the page graphic file that calls it (vpx_scene). Edit the text there.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;
$d = isset( $args['d'] ) ? (array) $args['d'] : array();
$segs = (array) ( $d['segments'] ?? array() );
vpx_open( $d, 'pen-line' );
?>
<div class="vpx-row" style="gap:6px;flex-wrap:wrap">
	<?php foreach ( (array) ( $d['stats'] ?? array() ) as $s ) : ?><span class="vpx-tag" style="<?php echo esc_attr( vp_tint_style( vpx_col( $s[1] ?? 'good' ) ) ); ?>"><?php echo esc_html( $s[0] ); ?></span><?php endforeach; ?>
</div>
<div class="vpx-line">
	<div style="font-weight:700;font-size:16px;margin-bottom:6px"><?php echo esc_html( $d['title'] ?? '' ); ?></div>
	<div class="vpx-doc"><?php foreach ( $segs as $i => $s ) : ?><?php if ( ! empty( $s[1] ) ) : ?><mark class="is-<?php echo esc_attr( $s[1] ); ?>" <?php vp_m( 'o:.2 t:.4 d:' . ( 0.5 + $i * 0.15 ) ); ?>><?php echo esc_html( $s[0] ); ?></mark><?php else : ?><?php echo esc_html( $s[0] ); ?><?php endif; ?> <?php endforeach; ?></div>
</div>
<div class="vpx-grid2">
	<div class="vpx-soft"><div class="vpx-cap" style="margin-bottom:6px"><?php echo esc_html( $d['outline_l'] ?? 'Outline' ); ?></div><div class="vpx-outline"><?php foreach ( (array) ( $d['outline'] ?? array() ) as $o ) : ?><span style="padding-left:<?php echo 'H3' === $o[0] ? 14 : 0; ?>px"><b class="vpx-muted" style="font-size:10px"><?php echo esc_html( $o[0] ); ?></b><?php echo esc_html( $o[1] ); ?></span><?php endforeach; ?></div></div>
	<div class="vpx-soft"><div class="vpx-cap" style="margin-bottom:6px"><?php echo esc_html( $d['legend_l'] ?? 'Highlights' ); ?></div><div class="vpx-outline"><span><i class="vpx-dot" style="background:var(--flare)"></i><?php echo esc_html( $d['l_long'] ?? 'Long sentence' ); ?></span><span><i class="vpx-dot" style="background:var(--lilac)"></i><?php echo esc_html( $d['l_passive'] ?? 'Passive voice' ); ?></span><span><i class="vpx-dot" style="background:var(--sage)"></i><?php echo esc_html( $d['l_kw'] ?? 'Topic keyword' ); ?></span></div></div>
</div>
<?php vpx_close( $d );
