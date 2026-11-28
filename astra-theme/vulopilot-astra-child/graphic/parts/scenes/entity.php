<?php
/**
 * Scene: Business facts AI engines read about you, with each one checked for consistency.
 * Data comes from the page graphic file that calls it (vpx_scene). Edit the text there.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;
$d = isset( $args['d'] ) ? (array) $args['d'] : array();
$facts = (array) ( $d['facts'] ?? array() );
vpx_open( $d, 'building2' );
?>
<div class="vpx-row" style="gap:12px">
	<span class="vpx-fav" style="width:46px;height:46px;font-size:18px"><?php echo esc_html( strtoupper( substr( (string) ( $d['name'] ?? 'B' ), 0, 1 ) ) ); ?></span>
	<div class="vpx-grow"><div style="font-weight:600;font-size:16px"><?php echo esc_html( $d['name'] ?? '' ); ?></div><div class="vpx-small vpx-muted"><?php echo esc_html( $d['kind'] ?? '' ); ?></div></div>
	<?php echo vp_ring( (int) ( $d['score'] ?? 70 ), 64, 'var(--sage)' ); // phpcs:ignore ?>
</div>
<ul class="vpx-list">
	<?php foreach ( $facts as $i => $f ) : $c = vpx_col( $f[2] ?? 'good' ); ?>
	<li class="vpx-line vpx-entity-row" <?php vp_m( 'o:0 y:6 t:.35 d:' . ( $i * 0.09 ) ); ?>>
		<span class="vpx-small vpx-muted"><?php echo esc_html( $f[0] ); ?></span>
		<span class="vpx-trunc" style="font-weight:600"><?php echo esc_html( $f[1] ); ?></span>
		<span class="vpx-tag" style="<?php echo esc_attr( vp_tint_style( $c ) ); ?>"><?php echo esc_html( $f[3] ?? ( 'good' === ( $f[2] ?? 'good' ) ? 'Consistent' : 'Mismatch' ) ); ?></span>
	</li>
	<?php endforeach; ?>
</ul>
<?php vpx_close( $d );
