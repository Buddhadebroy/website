<?php
/**
 * Answer Engine Optimization › result: scan, fix, verify, and the short answer.
 * Graphic name: answer-engine-optimization-4  ·  Shortcode: [vp_answer_engine_optimization_4]  ·  /solutions/answer-engine-optimization/
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$args = isset( $args ) && is_array( $args ) ? $args : array();
$t    = array_merge(
	array(
		'title' => 'Answer Engine Optimization',
		'q'     => 'What is Answer Engine Optimization (AEO)?',
		'a'     => 'Answer Engine Optimization (AEO) is writing pages so an AI assistant or search feature picks them as the answer to a specific question. Use the question as a heading, answer it directly in one or two sentences, add FAQ schema and keep the page current. VuloPilot checks answer-readiness and tests pages against AI engines.',
	),
	(array) ( $args['text'] ?? array() )
);
// kind (Scan|Fix|Verify), icon, detail.
$steps = $args['steps'] ?? array(
	array( 'Scan', 'scan-search', 'AEO checks whether pages answer questions clearly and lets you test a page you just fixed.' ),
	array( 'Fix', 'wand-sparkles', 'Add FAQ blocks to pages that answer common questions, and keep key pages fresh.' ),
	array( 'Verify', 'check-circle2', 'Scan again and VuloPilot shows whether the problem is gone, with your score history as proof.' ),
);
$kind_col = array( 'Scan' => 'var(--sage)', 'Fix' => 'var(--lilac)', 'Verify' => 'var(--flare)' );
$sources = array(
);
?>
<div class="card overflow-hidden">
	<div class="flex flex-wrap items-center justify-between gap-2 border-b border-line px-5 py-3.5">
		<div class="flex items-center gap-2.5 text-sm font-semibold"><span class="grid h-7 w-7 place-items-center rounded-sm bg-sage-soft text-sage"><?php vp_i( 'workflow', 15 ); ?></span><?php echo esc_html( $t['title'] ); ?></div>
	</div>
	<div class="p-5">
		<ol class="vpg-flow">
			<?php foreach ( $steps as $i => $s ) : $c = $kind_col[ $s[0] ] ?? 'var(--sage)'; ?>
				<li class="vpg-flow-step" <?php vp_m( 'o:0 x:-12 t:.45 e:spring d:' . ( 0.15 + $i * 0.4 ) ); ?>>
					<span class="vpg-flow-kind" style="<?php echo esc_attr( vp_tint_style( $c ) ); ?>"><?php echo esc_html( $s[0] ); ?></span>
					<span class="flex min-w-0 flex-1 items-center gap-3 rounded-sm border border-line bg-surface px-3.5 py-2.5">
						<span class="grid h-8 w-8 shrink-0 place-items-center rounded-sm" style="<?php echo esc_attr( vp_tint_style( $c ) ); ?>"><?php vp_i( $s[1], 15 ); ?></span>
						<span class="min-w-0 text-xs text-muted"><?php echo esc_html( $s[2] ); ?></span>
					</span>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
	<div class="border-t border-line bg-bg px-5 py-4">
		<div class="text-[11px] font-semibold uppercase tracking-widest text-muted"><?php echo esc_html( $t['q'] ); ?></div>
		<p class="mt-2 text-sm leading-relaxed"><?php echo esc_html( $t['a'] ); ?></p>
		<?php if ( $sources ) : ?>
			<p class="mt-3 text-xs text-muted">
				<?php foreach ( $sources as $i => $src ) : ?><?php echo $i ? ' · ' : ''; ?><?php echo esc_html( $src[0] ); ?><?php endforeach; ?>
			</p>
		<?php endif; ?>
	</div>
</div>
