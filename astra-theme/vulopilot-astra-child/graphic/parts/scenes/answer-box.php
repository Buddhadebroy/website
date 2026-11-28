<?php
/**
 * Scene: A question with a short, quotable answer block and the checks that make it quotable.
 * Data comes from the page graphic file that calls it (vpx_scene). Edit the text there.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;
$d = isset( $args['d'] ) ? (array) $args['d'] : array();
$words = (int) ( $d['words'] ?? 48 );
vpx_open( $d, 'message-circle-question' );
?>
<div class="vpx-line" style="border-left:3px solid var(--sage)">
	<div class="vpx-cap" style="color:var(--sage)"><?php echo esc_html( $d['kicker'] ?? 'Question' ); ?></div>
	<div style="font-weight:600;font-size:15px;margin-top:4px"><?php echo esc_html( $d['question'] ?? '' ); ?></div>
	<p class="vpx-muted" style="margin:8px 0 0;line-height:1.6"><?php echo esc_html( $d['answer'] ?? '' ); ?></p>
</div>
<div>
	<div class="vpx-row" style="justify-content:space-between;margin-bottom:6px"><span class="vpx-cap"><?php echo esc_html( $d['len_label'] ?? 'Answer length' ); ?></span><span class="vpx-small"><b><?php echo $words; ?></b> <?php echo esc_html( $d['len_unit'] ?? 'words · target 40–60' ); ?></span></div>
	<div class="vpx-bar" style="height:8px"><i style="left:40%;width:20%;background:var(--sage-soft)"></i><i <?php vp_m( 'W:' . min( 100, $words ) . '% t:1 e:ease', 'background:var(--sage)' ); ?>></i></div>
</div>
<ul class="vpx-list">
	<?php foreach ( (array) ( $d['checks'] ?? array() ) as $i => $c ) : ?>
	<li class="vpx-row" <?php vp_m( 'o:0 x:-6 t:.35 d:' . ( 0.5 + $i * 0.1 ) ); ?>><span class="<?php echo 'bad' === ( $c[1] ?? 'good' ) ? 'vpg-no' : 'vpg-ok'; ?>"><?php vpx_i( 'bad' === ( $c[1] ?? 'good' ) ? 'x' : 'check', 11 ); ?></span><span class="vpx-muted"><?php echo esc_html( $c[0] ); ?></span></li>
	<?php endforeach; ?>
</ul>
<?php vpx_close( $d );
