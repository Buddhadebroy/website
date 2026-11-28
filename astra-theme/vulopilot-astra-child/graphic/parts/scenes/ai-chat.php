<?php
/**
 * Scene: AI assistant answer, typed out, with the sources it cites (yours highlighted).
 * Data comes from the page graphic file that calls it (vpx_scene). Edit the text there.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;
$d = isset( $args['d'] ) ? (array) $args['d'] : array();
$d['_loop'] = $d['_loop'] ?? 14000;
$cites = (array) ( $d['cites'] ?? array() );
vpx_open( $d, 'message-square-text' );
?>
<div class="vpx-q" <?php vp_m( 'o:0 y:8 t:.4' ); ?>><?php echo esc_html( $d['question'] ?? '' ); ?></div>
<div class="vpx-a">
	<span class="vpx-a-av"><?php vpx_i( 'sparkles', 14 ); ?></span>
	<div class="vpx-grow">
		<div class="vpx-small vpx-muted" style="margin-bottom:4px"><?php echo esc_html( $d['engine'] ?? 'AI answer' ); ?></div>
		<div class="vpx-a-txt" data-fx="type" data-ms="28" data-step="2"><?php echo esc_html( $d['answer'] ?? '' ); ?></div>
	</div>
</div>
<div>
	<div class="vpx-cap" style="margin-bottom:8px"><?php echo esc_html( $d['cites_label'] ?? 'Sources' ); ?></div>
	<div class="vpx-row" style="flex-wrap:wrap;gap:6px">
		<?php foreach ( $cites as $i => $c ) : ?><span class="vpx-cite<?php echo ! empty( $c[1] ) ? ' is-you' : ''; ?>" <?php vp_m( 'o:0 s:.8 t:.4 e:spring d:' . ( 1.8 + $i * 0.15 ) ); ?>><i><?php echo (int) $i + 1; ?></i><?php echo esc_html( $c[0] ); ?></span><?php endforeach; ?>
	</div>
</div>
<?php if ( ! empty( $d['verdict'] ) ) : ?><div class="vpx-soft vpx-row" <?php vp_m( 'o:0 y:6 t:.4 d:2.4' ); ?>><span class="<?php echo 'bad' === ( $d['verdict_state'] ?? 'good' ) ? 'vpg-no' : 'vpg-ok'; ?>"><?php vpx_i( 'bad' === ( $d['verdict_state'] ?? 'good' ) ? 'x' : 'check', 11 ); ?></span><span class="vpx-grow"><?php echo esc_html( $d['verdict'] ); ?></span></div><?php endif; ?>
<?php vpx_close( $d );
