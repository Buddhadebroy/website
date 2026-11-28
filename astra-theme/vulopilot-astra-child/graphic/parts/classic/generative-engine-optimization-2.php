<?php
/**
 * Generative Engine Optimization › diagnostic: problems ranked and scored, with a score ring.
 * Graphic name: generative-engine-optimization-2  ·  Shortcode: [vp_generative_engine_optimization_2]  ·  /solutions/generative-engine-optimization/
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$args = isset( $args ) && is_array( $args ) ? $args : array();
$t    = array_merge(
	array(
		'label' => 'Generative Engine Optimization check',
		'page'  => '/solutions/generative-engine-optimization/',
		'score' => 'Page score',
		'first' => 'Fix first',
		'fix'   => 'Open A Closer Look, By Topic to see affected pages and follow the advice on each…',
		'btn'   => 'Fix with VuloPilot',
	),
	(array) ( $args['text'] ?? array() )
);
// icon, finding, score 0-100.
$signals = $args['signals'] ?? array(
		array( 'alert-triangle', 'Pages bury the answer under long introductions.', 45 ),
		array( 'filter', 'Claims have no source an AI can verify.', 57 ),
		array( 'list-tree', 'The brand or product is named differently on every page.', 67 ),
	);
$total = 56;
$col   = function ( $v ) {
	return $v >= 80 ? 'var(--sage)' : ( $v >= 60 ? 'var(--lilac)' : 'var(--rose)' );
};
?>
<div class="card overflow-hidden">
	<div class="flex flex-wrap items-center justify-between gap-2 border-b border-line px-5 py-3.5">
		<div class="flex items-center gap-2.5 text-sm font-semibold"><span class="grid h-7 w-7 place-items-center rounded-sm bg-sage-soft text-sage"><?php vp_i( 'scan-search', 15 ); ?></span><?php echo esc_html( $t['label'] ); ?></div>
		<span class="font-mono text-[11px] text-muted"><?php echo esc_html( $t['page'] ); ?></span>
	</div>
	<div class="vpg-signals p-5">
		<ul class="space-y-3">
			<?php foreach ( $signals as $i => $s ) : ?>
				<li <?php vp_m( 'o:0 x:-8 t:.4 d:' . ( $i * 0.08 ) ); ?>>
					<div class="flex items-center gap-2.5">
						<span class="grid h-7 w-7 shrink-0 place-items-center rounded-sm" style="<?php echo esc_attr( vp_tint_style( $col( $s[2] ) ) ); ?>"><?php vp_i( $s[0], 14 ); ?></span>
						<span class="min-w-0 flex-1 truncate text-xs text-muted"><?php echo esc_html( $s[1] ); ?></span>
						<span class="text-sm font-semibold tabular-nums" style="color:<?php echo esc_attr( $col( $s[2] ) ); ?>"><?php echo (int) $s[2]; ?></span>
					</div>
					<div class="vpg-indent mt-1.5 h-1.5 overflow-hidden rounded-full bg-line"><div class="h-full rounded-full" <?php vp_m( 'W:' . (int) $s[2] . '% t:.9 e:ease d:' . ( 0.1 + $i * 0.08 ), 'background:' . $col( $s[2] ) ); ?>></div></div>
				</li>
			<?php endforeach; ?>
		</ul>
		<div class="vpg-signals-score flex flex-col items-center gap-2">
			<?php echo vp_ring( $total, 112, $col( $total ) ); // phpcs:ignore ?>
			<span class="text-xs text-muted"><?php echo esc_html( $t['score'] ); ?></span>
		</div>
	</div>
	<div class="flex flex-wrap items-center gap-3 border-t border-line bg-bg px-5 py-3 text-sm" <?php vp_m( 'o:0 y:8 t:.4 d:.6' ); ?>>
		<span class="vpg-chip is-bad"><?php echo esc_html( $t['first'] ); ?></span>
		<span class="min-w-0 flex-1 font-semibold"><?php echo esc_html( $t['fix'] ); ?></span>
		<span class="inline-flex items-center gap-1.5 rounded-sm bg-ink px-3 py-1.5 text-xs font-semibold text-bg"><?php vp_i( 'sparkles', 12 ); ?><?php echo esc_html( $t['btn'] ); ?></span>
	</div>
</div>
