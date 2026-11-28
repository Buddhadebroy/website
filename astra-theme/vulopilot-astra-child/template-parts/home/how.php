<?php
/**
 * Home: how it works – five auto-playing steps (Find, Understand, Prioritize, Fix, Verify).
 * Each step's graphic lives in template-parts/shared/how-*.php.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$eyebrow  = 'How VuloPilot works';
$title    = '“What’s wrong?” becomes <span class="text-sage">“We’ve got this.”</span>';
$intro    = 'Every problem goes from found to fixed, and proven.';
$interval = 5600; // ms per step.
$status   = array( 'play' => 'Auto-playing', 'pause' => 'Paused' );

// icon, title, description, points, graphic file
$steps = array(
	array( 'scan-search', 'Find', 'One scan looks at your whole website.', array( 'Pages, plugins, speed, search, content and AI visibility', 'Runs from your WordPress dashboard', 'Re-run any time, or on a schedule' ), 'how-find' ),
	array( 'eye', 'Understand', 'See the exact page with the problem, what is wrong, and why it matters.', array( 'The exact page and element', 'A plain-language explanation', 'Severity, so you can judge the impact' ), 'how-understand' ),
	array( 'list-ordered', 'Prioritize', 'Know what to fix first and what can wait.', array( 'Ranked from critical to low', 'Quick wins surfaced first', 'Skip what can safely wait' ), 'how-prioritize' ),
	array( 'wand-sparkles', 'Fix', 'One click, an AI draft you approve, or a clear note for your developer.', array( 'One-click AI fix', 'Preview before and after, then approve', 'Or send a note to your developer' ), 'how-fix' ),
	array( 'badge-check', 'Verify', 'See the result for yourself, with proof it worked.', array( 'Scan again after the fix', 'Scores and history show the change', 'Undo any applied change' ), 'how-verify' ),
);

// Optional overrides (the "How it works stepper" block and [vp_how_stepper] pass these).
// A step may also carry: [5] image URL (used instead of the graphic), [6] image alt text.
$args         = isset( $args ) && is_array( $args ) ? $args : array();
$only         = ! empty( $args['graphic_only'] );
$show_heading = isset( $args['show_heading'] ) ? (bool) $args['show_heading'] : ! $only;
$eyebrow      = isset( $args['eyebrow'] ) ? $args['eyebrow'] : $eyebrow;
$title        = isset( $args['title'] ) ? $args['title'] : $title;
$intro        = isset( $args['intro'] ) ? $args['intro'] : $intro;
$interval     = ! empty( $args['interval'] ) ? (int) $args['interval'] : $interval;
$status       = array_merge( $status, (array) ( $args['status'] ?? array() ) );
$steps        = ! empty( $args['steps'] ) ? array_values( $args['steps'] ) : $steps;
$n            = count( $steps ) - 1;
$section_id   = isset( $args['id'] ) ? $args['id'] : 'how';
?>
<?php if ( ! $only ) : ?>
<section<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?> class="px-5 py-16">
	<div class="mx-auto max-w-6xl">
<?php endif; ?>
		<?php if ( $show_heading ) : ?>
		<div class="mx-auto max-w-3xl text-center" <?php vp_reveal(); ?>>
			<?php if ( '' !== (string) $eyebrow ) { vp_eyebrow( $eyebrow ); } ?>
			<h2 class="mt-5 text-4xl font-semibold leading-[1.08] tracking-tight sm:text-6xl"><?php echo wp_kses_post( $title ); ?></h2>
			<?php if ( '' !== (string) $intro ) : ?><p class="mt-4 text-lg text-muted"><?php echo esc_html( $intro ); ?></p><?php endif; ?>
		</div>
		<?php endif; ?>

		<div class="<?php echo $show_heading ? 'mt-10' : ''; ?>" data-vp="stepper" data-interval="<?php echo (int) $interval; ?>">
			<div class="relative grid grid-cols-<?php echo count( $steps ); ?>" style="grid-template-columns:repeat(<?php echo (int) count( $steps ); ?>,minmax(0,1fr))">
				<div class="absolute top-6 h-1 rounded-full bg-line" style="left:<?php echo esc_attr( round( 50 / count( $steps ), 3 ) ); ?>%;right:<?php echo esc_attr( round( 50 / count( $steps ), 3 ) ); ?>%">
					<div class="vp-step-bar absolute inset-y-0 left-0 rounded-sm bg-sage" style="width:0%" data-bar></div>
					<div class="vp-step-next absolute inset-y-0 rounded-sm bg-sage/40" style="left:0%;--to:<?php echo esc_attr( 100 / max( 1, $n ) ); ?>%;--step:<?php echo esc_attr( $interval / 1000 ); ?>s" data-next></div>
				</div>
				<?php foreach ( $steps as $i => $s ) : ?>
					<button type="button" class="group relative flex flex-col items-center gap-2.5 text-center" aria-current="<?php echo 0 === $i ? 'true' : 'false'; ?>" data-step-btn>
						<span class="vp-step-ic relative grid h-12 w-12 place-items-center rounded-sm border-2 transition-colors <?php echo 0 === $i ? 'border-sage bg-sage text-white shadow-[0_10px_30px_-8px_rgba(0,41,145,.6)]' : 'border-line bg-surface text-muted group-hover:border-sage/50'; ?>" data-step-ic>
							<span data-ic-step><?php vp_i( $s[0], 20 ); ?></span>
							<span data-ic-done hidden><?php vp_i( 'check', 20 ); ?></span>
							<span class="absolute -inset-1.5 rounded-sm border border-sage/30" data-halo<?php echo 0 === $i ? '' : ' hidden'; ?>></span>
						</span>
						<span class="font-mono text-[10px] <?php echo 0 === $i ? 'text-sage' : 'text-muted'; ?>" data-step-num>0<?php echo (int) $i + 1; ?></span>
						<span class="-mt-1.5 text-sm font-semibold sm:text-base <?php echo 0 === $i ? 'text-ink' : 'text-muted'; ?>" data-step-label><?php echo esc_html( $s[1] ); ?></span>
					</button>
				<?php endforeach; ?>
			</div>

			<div class="relative mt-8 overflow-hidden rounded-sm border border-line bg-surface shadow-[0_40px_90px_-50px_rgba(0,41,145,.4)]">
				<div class="grid lg:grid-cols-[0.85fr_1.15fr]">
					<div class="relative p-8 sm:p-10">
						<?php foreach ( $steps as $i => $s ) : ?>
							<div data-panel="<?php echo (int) $i; ?>"<?php echo 0 === $i ? '' : ' hidden'; ?> <?php vp_m( 'o:0 y:16 t:.3' ); ?>>
								<div class="select-none text-8xl font-semibold leading-none tracking-tighter text-transparent [-webkit-text-stroke:1.5px_var(--line)]">0<?php echo (int) $i + 1; ?></div>
								<h3 class="mt-4 text-4xl font-semibold tracking-tight"><?php echo esc_html( $s[1] ); ?></h3>
								<p class="mt-3 text-lg text-muted"><?php echo esc_html( $s[2] ); ?></p>
								<ul class="mt-6 space-y-2.5">
									<?php foreach ( $s[3] as $l => $pt ) : ?>
										<li class="flex gap-3 text-[15px]" <?php vp_m( 'o:0 x:-10 d:' . ( 0.15 + $l * 0.08 ) ); ?>><span class="mt-0.5 grid h-5 w-5 shrink-0 place-items-center rounded-full bg-sage-soft text-sage"><?php vp_i( 'check', 12 ); ?></span><?php echo esc_html( $pt ); ?></li>
									<?php endforeach; ?>
								</ul>
							</div>
						<?php endforeach; ?>
						<div class="mt-8 flex items-center gap-2">
							<button type="button" aria-label="Previous step" class="grid h-10 w-10 place-items-center rounded-full border border-line transition hover:border-ink" data-prev><?php vp_i( 'arrow-left', 16 ); ?></button>
							<button type="button" aria-label="Next step" class="grid h-10 w-10 place-items-center rounded-full bg-ink text-bg transition hover:bg-sage" data-next-btn><?php vp_i( 'arrow-right', 16 ); ?></button>
							<span class="ml-2 text-xs text-muted" data-status data-play="<?php echo esc_attr( $status['play'] ); ?>" data-pause="<?php echo esc_attr( $status['pause'] ); ?>"><?php echo esc_html( $status['play'] ); ?></span>
						</div>
					</div>
					<div class="relative overflow-hidden bg-gradient-to-br from-sage-soft/70 via-bg to-apricot-soft/60 p-6 sm:p-9">
						<div class="absolute inset-0 opacity-40 [background-image:radial-gradient(var(--line)_1px,transparent_1px)] [background-size:22px_22px]"></div>
						<div class="relative min-h-[340px]">
							<?php foreach ( $steps as $i => $s ) : ?>
								<div data-panel="<?php echo (int) $i; ?>"<?php echo 0 === $i ? '' : ' hidden'; ?> <?php vp_m( 'o:0 y:24 s:.97 t:.35' ); ?>>
									<?php
									if ( ! empty( $s[5] ) ) {
										// Your own image instead of a graphic.
										echo '<img src="' . esc_url( $s[5] ) . '" alt="' . esc_attr( $s[6] ?? '' ) . '" loading="lazy" decoding="async" class="block h-auto w-full rounded-sm border border-line shadow-[0_24px_60px_-34px_rgba(0,41,145,.45)]" />';
									} elseif ( ! empty( $s[4] ) && locate_template( 'template-parts/shared/' . $s[4] . '.php' ) ) {
										get_template_part( 'template-parts/shared/' . $s[4] );
									} elseif ( ! empty( $s[4] ) ) {
										echo vp_mount( sanitize_key( $s[4] ) ); // phpcs:ignore -- any registered graphic.
									}
									?>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
<?php if ( ! $only ) : ?>
	</div>
</section>
<?php endif; ?>
