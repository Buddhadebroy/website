<?php
/**
 * AI Search › "Become the answer": AEO checks for one page, a live engine test and the brand trust scores.
 * The missing FAQ schema gets fixed, then each AI engine is asked again. Plays again every few seconds.
 * Graphic name: aeo-demo  ·  Shortcode: [vp_aeo_demo]
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$args = isset( $args ) && is_array( $args ) ? $args : array();
$t    = array_merge(
	array(
		'page'     => '/custom-cakes/',
		'label'    => 'AEO check',
		'question' => 'How far ahead should I order a custom cake?',
		'checks'   => 'Answer readiness',
		'engines'  => 'Engine test',
		'testing'  => 'Asking…',
		'brand'    => 'Brand scores',
		'add'      => 'Add with AI',
		'added'    => 'Added',
	),
	(array) ( $args['text'] ?? array() )
);
// Check, passes from the start.
$checks  = $args['checks'] ?? array(
	array( 'Answers the question in the first two sentences', true ),
	array( 'The question is used as a heading', true ),
	array( 'FAQ schema on the page', false ),
	array( 'Author and last-updated date shown', true ),
);
// Engine, result after the fix, cited (true) or not yet (false), seconds before it answers.
$engines = $args['engines'] ?? array(
	array( 'ChatGPT', 'Cited as a source', true, 2.4 ),
	array( 'Perplexity', 'Cited as a source', true, 3.1 ),
	array( 'Gemini', 'Not yet, test again next week', false, 3.8 ),
);
$brand   = $args['brand'] ?? array( array( 'Trust', 82, 'var(--sage)' ), array( 'Authority', 67, 'var(--lilac)' ) );
?>
<div class="card overflow-hidden" data-vpg="loop" data-every="10000">
	<div class="flex flex-wrap items-center justify-between gap-2 border-b border-line px-5 py-3.5">
		<div class="flex items-center gap-2.5 text-sm font-semibold"><span class="grid h-7 w-7 place-items-center rounded-sm bg-lilac-soft text-lilac"><?php vp_i( 'message-circle-question', 15 ); ?></span><?php echo esc_html( $t['label'] ); ?></div>
		<span class="font-mono text-[11px] text-muted"><?php echo esc_html( $t['page'] ); ?></span>
	</div>
	<div class="p-5">
		<div class="ml-auto w-fit max-w-[85%] rounded-sm bg-ink px-4 py-2.5 text-sm text-bg" <?php vp_m( 'o:0 y:8 t:.4' ); ?>><?php echo esc_html( $t['question'] ); ?></div>

		<div class="mt-5 text-[11px] font-semibold uppercase tracking-widest text-muted"><?php echo esc_html( $t['checks'] ); ?></div>
		<ul class="mt-2 space-y-1.5">
			<?php foreach ( $checks as $i => $c ) : ?>
				<li class="flex items-center gap-2.5 text-sm" <?php vp_m( 'o:0 x:-8 t:.35 d:' . ( 0.15 + $i * 0.1 ) ); ?>>
					<?php if ( $c[1] ) : ?>
						<span class="vpg-ok"><?php vp_i( 'check', 12, '', 3 ); ?></span><span class="flex-1"><?php echo esc_html( $c[0] ); ?></span>
					<?php else : ?>
						<span class="flex flex-1 items-center gap-2.5" data-fx="swap" data-after="1.3">
							<span class="vpg-no" data-when="before"><?php vp_i( 'x', 12, '', 3 ); ?></span>
							<span class="vpg-ok" data-when="after" hidden <?php vp_m( 'o:0 s:.6 t:.3 e:spring' ); ?>><?php vp_i( 'check', 12, '', 3 ); ?></span>
							<span class="flex-1"><?php echo esc_html( $c[0] ); ?></span>
							<span class="vpg-chip is-ink" data-when="before"><?php vp_i( 'sparkles', 11 ); ?><?php echo esc_html( $t['add'] ); ?></span>
							<span class="vpg-chip is-good" data-when="after" hidden><?php echo esc_html( $t['added'] ); ?></span>
						</span>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>

		<div class="mt-5 text-[11px] font-semibold uppercase tracking-widest text-muted"><?php echo esc_html( $t['engines'] ); ?></div>
		<ul class="mt-2 divide-y divide-line rounded-sm border border-line">
			<?php foreach ( $engines as $e ) : ?>
				<li class="flex items-center gap-3 px-3.5 py-2.5 text-sm" data-fx="swap" data-after="<?php echo esc_attr( $e[3] ); ?>">
					<span class="grid h-6 w-6 shrink-0 place-items-center rounded-full bg-bg text-muted"><?php vp_i( 'bot', 13 ); ?></span>
					<span class="w-24 shrink-0 font-semibold"><?php echo esc_html( $e[0] ); ?></span>
					<span class="ml-auto inline-flex items-center gap-1.5 text-xs text-muted" data-when="before"><?php vp_i( 'loader-circle', 13, 'animate-spin' ); ?><?php echo esc_html( $t['testing'] ); ?></span>
					<span class="ml-auto inline-flex items-center gap-1.5 text-xs font-semibold" style="color:<?php echo $e[2] ? 'var(--sage)' : 'var(--muted)'; ?>" data-when="after" hidden <?php vp_m( 'o:0 x:8 t:.35' ); ?>><?php vp_i( $e[2] ? 'badge-check' : 'clock', 13 ); ?><?php echo esc_html( $e[1] ); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>

		<div class="mt-5 text-[11px] font-semibold uppercase tracking-widest text-muted"><?php echo esc_html( $t['brand'] ); ?></div>
		<div class="mt-2 grid grid-cols-2 gap-3">
			<?php foreach ( $brand as $i => $b ) : ?>
				<div class="rounded-sm border border-line bg-bg/40 p-3">
					<div class="flex items-baseline justify-between"><span class="text-sm font-semibold"><?php echo esc_html( $b[0] ); ?></span><span class="text-xl font-semibold tabular-nums" <?php vp_m( 'N:' . (int) $b[1] . ' t:1.2 d:' . ( 0.2 + $i * 0.1 ) ); ?>><?php echo (int) $b[1]; ?></span></div>
					<div class="mt-2 h-1.5 overflow-hidden rounded-full bg-line"><div class="h-full rounded-full" <?php vp_m( 'W:' . (int) $b[1] . '% t:1 e:ease d:' . ( 0.2 + $i * 0.1 ), 'background:' . $b[2] ); ?>></div></div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
