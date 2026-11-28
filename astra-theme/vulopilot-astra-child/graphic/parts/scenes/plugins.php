<?php
/**
 * Scene: Plugins and themes with known vulnerabilities, then updated.
 * Data comes from the page graphic file that calls it (vpx_scene). Edit the text there.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;
$d = isset( $args['d'] ) ? (array) $args['d'] : array();
$rows = (array) ( $d['rows'] ?? array() );
$d['_loop'] = $d['_loop'] ?? 11000;
vpx_open( $d, 'puzzle' );
?>
<div data-fx="swap" data-after="2">
<ul class="vpx-list">
	<?php foreach ( $rows as $i => $r ) : $st = $r[3] ?? 'ok'; ?>
	<li class="vpx-line vpx-row" <?php vp_m( 'o:0 x:-8 t:.35 d:' . ( $i * 0.09 ) ); ?>>
		<span class="vpx-ic" style="<?php echo esc_attr( vp_tint_style( 'var(--lilac)' ) ); ?>"><?php vpx_i( 'puzzle', 14 ); ?></span>
		<span class="vpx-grow"><span style="display:block;font-weight:600" class="vpx-trunc"><?php echo esc_html( $r[0] ); ?></span><span class="vpx-small vpx-muted vpx-mono"><span data-when="before">v<?php echo esc_html( $r[1] ); ?></span><span data-when="after" hidden>v<?php echo esc_html( $r[2] ); ?></span><?php echo ! empty( $r[4] ) ? ' · ' . esc_html( $r[4] ) : ''; ?></span></span>
		<span data-when="before" class="vpx-tag" style="<?php echo esc_attr( vp_tint_style( vpx_col( $st ) ) ); ?>"><?php echo esc_html( 'vuln' === $st ? ( $d['vuln'] ?? 'Vulnerable' ) : ( 'outdated' === $st ? ( $d['old'] ?? 'Update' ) : ( $d['ok'] ?? 'Up to date' ) ) ); ?></span>
		<span data-when="after" hidden class="vpx-tag" style="<?php echo esc_attr( vp_tint_style( 'var(--sage)' ) ); ?>" <?php vp_m( 'o:0 s:.8 t:.4 e:spring d:' . ( $i * 0.15 ) ); ?>><?php vpx_i( 'check', 11 ); ?><?php echo esc_html( $d['done'] ?? 'Updated' ); ?></span>
	</li>
	<?php endforeach; ?>
</ul>
</div>
<?php vpx_close( $d );
