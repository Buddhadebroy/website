<?php
/**
 * Scene: Findings that sort themselves by impact, most important first.
 * Data comes from the page graphic file that calls it (vpx_scene). Edit the text there.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;
$d = isset( $args['d'] ) ? (array) $args['d'] : array();
$rows = (array) ( $d['rows'] ?? array() );
$d['_loop'] = $d['_loop'] ?? 12000;
vpx_open( $d, 'list-ordered' );
?>
<div data-fx="sort" data-after="1.6">
	<div class="vpx-row" style="justify-content:space-between;margin-bottom:10px"><span class="vpx-cap"><span data-when="before"><?php echo esc_html( $d['before'] ?? 'As found' ); ?></span><span data-when="after" hidden><?php echo esc_html( $d['after'] ?? 'Ranked by impact' ); ?></span></span><span class="vpx-small vpx-muted"><?php echo esc_html( $d['meta'] ?? '' ); ?></span></div>
	<ul class="vpx-list" style="transition:none">
		<?php foreach ( $rows as $i => $r ) : $c = vpx_col( $r[0] ); ?>
		<li data-rank="<?php echo (int) ( $r[3] ?? $i ); ?>" data-color="<?php echo esc_attr( $c ); ?>" class="vpx-line vpx-row" style="transition:transform .6s var(--vp-ease)">
			<span data-rank-badge class="vpx-pos" style="min-width:24px;height:24px;border-radius:999px">·</span>
			<span class="vpx-grow"><span style="display:block;font-weight:600" class="vpx-trunc"><?php echo esc_html( $r[1] ); ?></span><span class="vpx-small vpx-muted vpx-trunc" style="display:block"><?php echo esc_html( $r[2] ?? '' ); ?></span></span>
			<span class="vpx-tag" style="<?php echo esc_attr( vp_tint_style( $c ) ); ?>"><?php echo esc_html( ucfirst( $r[0] ) ); ?></span>
		</li>
		<?php endforeach; ?>
	</ul>
</div>
<?php vpx_close( $d );
