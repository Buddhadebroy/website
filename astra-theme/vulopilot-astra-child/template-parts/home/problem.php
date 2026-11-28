<?php
/**
 * Home: the problem. Quotes, then a "noise to signal" slider that turns 20 warnings into 4 ranked steps.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$eyebrow = 'The problem';
$title   = 'Your website keeps changing. <span class="text-muted">Are you keeping up?</span>';
$quotes  = array(
	'I have three tools and a hundred warnings. What do I fix?',
	'Does ChatGPT even know my business exists?',
	'Is my website even okay? I can’t tell.',
	'I fixed it last week. Did it help?',
	'My visitors dropped. Why?',
);

// Warnings shown as noise: text, left %, top %, tilt in degrees.
$noise = array(
	array( 'SSL warning', 3, 6, -6 ),
	array( 'Missing alt text ×48', 30, 3, 4 ),
	array( 'Slow LCP', 60, 8, -3 ),
	array( 'Broken link /about', 82, 4, 7 ),
	array( 'Plugin outdated', 8, 24, -8 ),
	array( 'No meta desc', 38, 22, 3 ),
	array( 'Duplicate H1', 66, 26, -5 ),
	array( '404 ×12', 86, 22, 6 ),
	array( 'Large images', 2, 42, -2 ),
	array( 'Weak schema', 28, 40, 5 ),
	array( 'XML sitemap?', 54, 44, -7 ),
	array( 'Thin content', 80, 40, 2 ),
	array( 'Mixed content', 10, 60, 6 ),
	array( 'Core Web Vitals', 36, 62, -4 ),
	array( 'Orphan pages', 62, 58, 3 ),
	array( 'Cache off', 84, 60, -6 ),
	array( 'Old PHP', 22, 78, 5 ),
	array( 'Low contrast', 50, 80, -3 ),
	array( 'No llms.txt', 72, 76, 4 ),
	array( 'Debug mode on', 6, 88, -5 ),
);

// The clear next steps: icon, text, tag, colour.
$ranked = array(
	array( 'shield-alert', 'Update 2 vulnerable plugins', 'Critical', 'var(--rose)' ),
	array( 'gauge', 'Fix the slow homepage hero image', 'High', 'var(--apricot)' ),
	array( 'link-2', 'Repair 12 broken links', 'Medium', 'var(--lilac)' ),
	array( 'sparkles', 'Everything else can wait', 'Later', 'var(--sage)' ),
);

$texts = array(
	'noise'  => 'warnings, zero direction',
	'signal' => 'clear next steps',
	'left'   => 'Noise',
	'right'  => 'Signal',
	'hint'   => 'Drag the slider to let VuloPilot sort it out',
);
$closing = 'What if your website could answer these questions <span class="text-sage">and act on them?</span>';

// Optional overrides (blocks and the [vp_noise_signal] graphic pass these). 'graphic_only' prints just the slider card.
$args   = isset( $args ) && is_array( $args ) ? $args : array();
$only   = ! empty( $args['graphic_only'] );
$noise  = ! empty( $args['noise'] ) ? $args['noise'] : $noise;
$ranked = ! empty( $args['ranked'] ) ? $args['ranked'] : $ranked;
$texts  = array_merge( $texts, (array) ( $args['texts'] ?? array() ) );
if ( $only ) :
	?>
<div class="vp-graphic-only" data-graphic="noise-signal">
	<?php
endif;
if ( ! $only ) :
	?>
<section id="problem" class="relative px-5 py-16">
	<div class="mx-auto max-w-6xl">
		<div class="mx-auto max-w-3xl text-center" <?php vp_reveal(); ?>>
			<?php vp_eyebrow( $eyebrow ); ?>
			<h2 class="mt-5 text-4xl font-semibold leading-[1.08] tracking-tight sm:text-6xl"><?php echo wp_kses_post( $title ); ?></h2>
		</div>

		<div class="mt-9 flex flex-wrap justify-center gap-3">
			<?php foreach ( $quotes as $i => $q ) : ?>
				<div <?php vp_reveal( $i * 0.06 ); ?>>
					<div class="vp-hover card cursor-default px-5 py-3 text-sm sm:text-base" style="--hy:-4px;--hr:<?php echo $i % 2 ? '1' : '-1'; ?>deg">“<?php echo esc_html( $q ); ?>”</div>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="mt-12" <?php vp_reveal(); ?>>
<?php endif; // ! $only ?>
			<div class="card overflow-hidden" data-vp="noise" data-count="<?php echo count( $noise ); ?>" data-signal-count="<?php echo count( $ranked ); ?>">
				<div class="flex flex-wrap items-center gap-4 border-b border-line px-6 py-4">
					<div class="flex items-baseline gap-2">
						<span class="min-w-[3ch] text-4xl font-semibold tabular-nums tracking-tight" style="color:var(--rose)" data-n><?php echo 100; ?></span>
						<span class="text-sm text-muted" data-n-label data-noise="<?php echo esc_attr( $texts['noise'] ); ?>" data-signal="<?php echo esc_attr( $texts['signal'] ); ?>"><?php echo esc_html( $texts['noise'] ); ?></span>
					</div>
					<div class="ml-auto flex w-full items-center gap-3 sm:w-auto">
						<span class="text-xs font-semibold uppercase tracking-widest text-rose"><?php echo esc_html( $texts['left'] ); ?></span>
						<input aria-label="Noise to signal" type="range" min="0" max="100" value="0" data-range class="vp-range h-2 w-full min-w-[180px] cursor-pointer appearance-none rounded-full sm:w-64 [&::-moz-range-thumb]:h-5 [&::-moz-range-thumb]:w-5 [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:border-0 [&::-moz-range-thumb]:bg-ink [&::-webkit-slider-thumb]:h-5 [&::-webkit-slider-thumb]:w-5 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-ink [&::-webkit-slider-thumb]:shadow-lg" style="background:linear-gradient(90deg, var(--sage) 0%, var(--line) 0%)" />
						<span class="text-xs font-semibold uppercase tracking-widest text-sage"><?php echo esc_html( $texts['right'] ); ?></span>
						<button type="button" aria-label="Replay" class="grid h-9 w-9 shrink-0 place-items-center rounded-full border border-line transition hover:bg-bg" data-replay>
							<span data-ic-play><?php vp_i( 'play', 15 ); ?></span><span data-ic-replay hidden><?php vp_i( 'rotate-ccw', 15 ); ?></span>
						</button>
					</div>
				</div>
				<div class="relative h-[430px] bg-[radial-gradient(circle_at_50%_100%,var(--sage-soft),transparent_65%)] sm:h-[400px]">
					<?php foreach ( $noise as $n ) : ?>
						<span class="absolute whitespace-nowrap rounded-sm border border-rose/30 bg-rose/10 px-3 py-1.5 text-xs font-medium text-rose will-change-transform sm:text-sm" data-chip data-x="<?php echo (float) $n[1]; ?>" data-y="<?php echo (float) $n[2]; ?>" data-r="<?php echo (float) $n[3]; ?>" style="left:<?php echo (float) $n[1]; ?>%;top:<?php echo (float) $n[2]; ?>%;opacity:1;transform:rotate(<?php echo (float) $n[3]; ?>deg) scale(1);filter:blur(0px);pointer-events:none">⚠ <?php echo esc_html( $n[0] ); ?></span>
					<?php endforeach; ?>
					<div class="absolute inset-0 flex flex-col justify-center gap-2.5 px-5 sm:px-24" style="pointer-events:none" data-rows>
						<?php foreach ( $ranked as $r ) : ?>
							<div class="flex items-center justify-between rounded-sm border border-line bg-surface px-5 py-3.5 shadow-sm" style="opacity:0;transform:translateY(24px) scale(0.94)" data-row>
								<span class="flex items-center gap-4">
									<span class="grid h-9 w-9 place-items-center rounded-sm" style="<?php echo esc_attr( vp_tint_style( $r[3] ) ); ?>"><?php vp_i( $r[0], 17 ); ?></span>
									<span class="font-semibold"><?php echo esc_html( $r[1] ); ?></span>
								</span>
								<span class="hidden rounded-sm px-3 py-1 text-xs font-semibold sm:block" style="<?php echo esc_attr( vp_tint_style( $r[3] ) ); ?>"><?php echo esc_html( $r[2] ); ?></span>
							</div>
						<?php endforeach; ?>
					</div>
					<div class="pointer-events-none absolute inset-x-0 bottom-3 text-center text-sm font-semibold text-muted" style="opacity:1" data-hint><?php echo esc_html( $texts['hint'] ); ?></div>
				</div>
			</div>
<?php if ( $only ) : ?>
</div>
	<?php
	return;
endif;
?>
		</div>

		<div class="mt-10 text-center" <?php vp_reveal(); ?>>
			<p class="text-3xl font-semibold leading-tight tracking-tight sm:text-4xl"><?php echo wp_kses_post( $closing ); ?></p>
		</div>
	</div>
</section>
