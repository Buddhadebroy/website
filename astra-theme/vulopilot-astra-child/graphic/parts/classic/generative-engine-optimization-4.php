<?php
/**
 * Generative Engine Optimization › result: scan, fix, verify, and the short answer.
 * Graphic name: generative-engine-optimization-4  ·  Shortcode: [vp_generative_engine_optimization_4]  ·  /solutions/generative-engine-optimization/
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$args = isset( $args ) && is_array( $args ) ? $args : array();
$t    = array_merge(
	array(
		'title' => 'Generative Engine Optimization',
		'q'     => 'What is Generative Engine Optimization (GEO)?',
		'a'     => 'Generative Engine Optimization (GEO) is making content easy for AI answer engines to find, use and cite. Early research found quotations, statistics and sources helped pages already chosen as sources. Later studies and Google\'s 2026 guidance agree the bigger levers are being indexed, relevant and verifiable. VuloPilot scores six GEO signals on every page.',
	),
	(array) ( $args['text'] ?? array() )
);
// kind (Scan|Fix|Verify), icon, detail.
$steps = $args['steps'] ?? array(
	array( 'Scan', 'scan-search', 'GEO checks AI summary, question coverage, evidence and citations, AI-readable structure, entity clarity and freshness.' ),
	array( 'Fix', 'wand-sparkles', 'Open A Closer Look, By Topic to see affected pages and follow the advice on each one.' ),
	array( 'Verify', 'check-circle2', 'Scan again and VuloPilot shows whether the problem is gone, with your score history as proof.' ),
);
$kind_col = array( 'Scan' => 'var(--sage)', 'Fix' => 'var(--lilac)', 'Verify' => 'var(--flare)' );
$sources = array(
	array( 'Aggarwal et al., “GEO: Generative Engine Optimization”, KDD 2024', 'https://arxiv.org/abs/2311.09735' ),
	array( 'Martinez, “Optimizing Visibility in Generative Engines: A Critical Survey of GEO (2023–2026)”', 'https://arxiv.org/abs/2607.14035' ),
	array( 'Google, “Optimizing your website for generative AI features on Google Search”', 'https://developers.google.com/search/docs/fundamentals/ai-optimization-guide' ),
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
