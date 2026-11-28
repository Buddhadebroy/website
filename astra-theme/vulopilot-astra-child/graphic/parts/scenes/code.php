<?php
/**
 * Scene: Code or file editor (robots.txt, llms.txt, .htaccess, head tags) with the bad line and the fix.
 * Data comes from the page graphic file that calls it (vpx_scene). Edit the text there.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;
$d = isset( $args['d'] ) ? (array) $args['d'] : array();
$lines = (array) ( $d['lines'] ?? array() );
vpx_open( $d, 'code2' );
?>
<div class="vpx-code">
	<div class="vpx-code-tabs"><?php foreach ( (array) ( $d['tabs'] ?? array( $d['file'] ?? 'robots.txt' ) ) as $i => $tb ) : ?><span class="<?php echo 0 === $i ? 'is-on' : ''; ?>"><?php echo esc_html( $tb ); ?></span><?php endforeach; ?></div>
	<?php foreach ( $lines as $i => $l ) : $k = $l[0] ?? 'plain'; ?>
	<div class="vpx-code-line is-<?php echo esc_attr( $k ); ?>" <?php vp_m( 'o:0 x:-6 t:.3 d:' . ( $i * 0.08 + ( in_array( $k, array( 'add', 'del' ), true ) ? 0.8 : 0 ) ) ); ?>><b><?php echo (int) $i + 1; ?></b><span><?php echo esc_html( $l[1] ?? '' ); ?></span></div>
	<?php endforeach; ?>
</div>
<?php if ( ! empty( $d['notes'] ) ) : ?>
<ul class="vpx-list">
	<?php foreach ( (array) $d['notes'] as $i => $n ) : ?>
	<li class="vpx-row" <?php vp_m( 'o:0 y:6 t:.4 d:' . ( 1 + $i * 0.12 ) ); ?>><span class="<?php echo 'good' === ( $n[1] ?? 'good' ) ? 'vpg-ok' : 'vpg-no'; ?>"><?php vpx_i( 'good' === ( $n[1] ?? 'good' ) ? 'check' : 'x', 11 ); ?></span><span class="vpx-muted"><?php echo esc_html( $n[0] ); ?></span></li>
	<?php endforeach; ?>
</ul>
<?php endif; ?>
<?php vpx_close( $d );
