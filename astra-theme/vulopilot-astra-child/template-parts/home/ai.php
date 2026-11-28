<?php
/**
 * Home: AI search section (typed AI answer + three score rings).
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$eyebrow  = 'Looking ahead';
$title    = 'People now ask AI, not just Google. <span class="text-sage">Be the answer.</span>';
$intro    = 'VuloPilot helps your website explain itself clearly, so tools like ChatGPT and Gemini understand what you do.';
$tags     = array( 'GPTBot', 'ClaudeBot', 'PerplexityBot', 'llms.txt', 'FAQ schema', 'Knowledge graph' );
$steps    = array(
	'Make your business easier to understand.',
	'Strengthen information and entities that explain your business.',
	'Help AI and search tools find the right information.',
	'Monitor the signals you can improve.',
	'Track what’s working and where to make your website stronger.',
);
$question = 'Best bakery near me for a custom birthday cake?';
$answer   = 'For a family-run bakery in Austin, try Rise & Grind Bakery — known for sourdough, same-day custom cakes and gluten-free options.';
$rings    = array( // value, colour, label
	array( 78, 'var(--lilac)', 'AI Search' ),
	array( 64, 'var(--apricot)', 'Entities' ),
	array( 89, 'var(--sage)', 'Clarity' ),
);
$ring_size = 92;
$typing    = true;
$glow      = true;

// Optional overrides (the "AI Answer" block and the [vp_ai_answer] graphic pass these).
// 'graphic_only' prints just the chat card with the score rings.
$args      = isset( $args ) && is_array( $args ) ? $args : array();
$only      = ! empty( $args['graphic_only'] );
$question  = isset( $args['question'] ) ? $args['question'] : $question;
$answer    = isset( $args['answer'] ) ? $args['answer'] : $answer;
$rings     = isset( $args['rings'] ) && is_array( $args['rings'] ) ? $args['rings'] : $rings;
$ring_size = ! empty( $args['ring_size'] ) ? (int) $args['ring_size'] : $ring_size;
$typing    = isset( $args['typing'] ) ? (bool) $args['typing'] : $typing;
$glow      = isset( $args['glow'] ) ? (bool) $args['glow'] : $glow;
if ( ! $only ) :
	?>
<section id="ai" class="px-5 py-16">
	<div class="mx-auto grid max-w-6xl items-center gap-10 lg:grid-cols-2">
		<div <?php vp_reveal(); ?>>
			<?php vp_eyebrow( $eyebrow ); ?>
			<h2 class="mt-5 font-display text-4xl leading-tight sm:text-5xl"><?php echo wp_kses_post( $title ); ?></h2>
			<p class="mt-4 text-lg text-muted"><?php echo esc_html( $intro ); ?></p>
			<div class="mt-6 flex flex-wrap gap-2">
				<?php foreach ( $tags as $t ) : ?>
					<span class="rounded-sm border border-line bg-surface px-3 py-1 font-mono text-xs"><?php echo esc_html( $t ); ?></span>
				<?php endforeach; ?>
			</div>
			<ol class="mt-8 space-y-3">
				<?php foreach ( $steps as $i => $s ) : ?>
					<li class="flex gap-4" <?php vp_m( 'o:0 x:-20 d:' . ( $i * 0.1 ) ); ?>><span class="grid h-7 w-7 shrink-0 place-items-center rounded-full bg-lilac-soft font-mono text-xs text-lilac"><?php echo (int) $i + 1; ?></span><span><?php echo esc_html( $s ); ?></span></li>
				<?php endforeach; ?>
			</ol>
		</div>

<?php endif; // ! $only ?>
		<div class="relative vp-ai-answer">
			<?php if ( $glow ) : ?><div class="absolute -inset-6 -z-10 rounded-sm bg-gradient-to-br from-lilac-soft via-apricot-soft to-sage-soft opacity-80 blur-2xl"></div><?php endif; ?>
			<div class="card p-6">
				<?php if ( '' !== trim( (string) $question ) ) : ?><div class="ml-auto w-fit max-w-[85%] rounded-sm rounded-br-sm bg-ink px-4 py-3 text-sm text-bg"><?php echo esc_html( $question ); ?></div><?php endif; ?>
				<?php if ( '' !== trim( (string) $answer ) ) : ?><div class="mt-4 max-w-[92%] rounded-sm rounded-bl-sm border border-line bg-bg/70 px-4 py-3 text-sm leading-relaxed"><?php if ( $typing ) : ?><span data-fx="type" data-step="2" data-ms="28" data-margin="-100px"><?php echo esc_html( $answer ); ?></span><?php else : echo esc_html( $answer ); endif; ?></div><?php endif; ?>
				<?php if ( $rings ) : ?>
				<div class="mt-6 grid gap-3 border-t border-line pt-6" style="grid-template-columns:repeat(<?php echo (int) min( 6, max( 1, count( $rings ) ) ); ?>,minmax(0,1fr))">
					<?php foreach ( $rings as $r ) : ?>
						<div class="flex flex-col items-center gap-2"><?php echo vp_ring( $r[0], $ring_size, $r[1] ); // phpcs:ignore ?><span class="text-xs text-muted"><?php echo esc_html( $r[2] ); ?></span></div>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
			</div>
		</div>
<?php if ( ! $only ) : ?>
	</div>
</section>
<?php endif; ?>
