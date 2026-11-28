<?php
/**
 * Scene: Which search and AI crawlers can read the site, switched on one by one.
 * Data comes from the page graphic file that calls it (vpx_scene). Edit the text there.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;
$d = isset( $args['d'] ) ? (array) $args['d'] : array();
$rows = (array) ( $d['rows'] ?? array() );
$d['_loop'] = $d['_loop'] ?? 12000;
vpx_open( $d, 'bot' );
?>
<div data-fx="swap" data-after="1.8">
<ul class="vpx-list">
	<?php foreach ( $rows as $i => $r ) : $was = ! empty( $r[2] ); $now = ! empty( $r[3] ); ?>
	<li class="vpx-line vpx-row" <?php vp_m( 'o:0 x:-8 t:.35 d:' . ( $i * 0.08 ) ); ?>>
		<span class="vpx-ic" style="<?php echo esc_attr( vp_tint_style( 'var(--lilac)' ) ); ?>"><?php vpx_i( 'bot', 14 ); ?></span>
		<span class="vpx-grow"><span style="display:block;font-weight:600"><?php echo esc_html( $r[0] ); ?></span><span class="vpx-small vpx-muted"><?php echo esc_html( $r[1] ); ?></span></span>
		<span data-when="before" class="vpx-tag" style="<?php echo esc_attr( vp_tint_style( $was ? 'var(--sage)' : 'var(--rose)' ) ); ?>"><?php echo esc_html( $was ? ( $d['on'] ?? 'Allowed' ) : ( $d['off'] ?? 'Blocked' ) ); ?></span>
		<span data-when="after" hidden class="vpx-tag" style="<?php echo esc_attr( vp_tint_style( $now ? 'var(--sage)' : 'var(--rose)' ) ); ?>" <?php vp_m( 'o:0 s:.8 t:.4 e:spring d:' . ( $i * 0.12 ) ); ?>><?php echo esc_html( $now ? ( $d['on'] ?? 'Allowed' ) : ( $d['off'] ?? 'Blocked' ) ); ?></span>
		<span class="vpx-toggle<?php echo $was ? ' is-on' : ''; ?>" data-when="before"></span>
		<span class="vpx-toggle<?php echo $now ? ' is-on' : ''; ?>" data-when="after" hidden></span>
	</li>
	<?php endforeach; ?>
</ul>
</div>
<?php vpx_close( $d );
