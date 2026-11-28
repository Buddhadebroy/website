<?php
/**
 * Home: role quotes + the big dark call to action.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$title   = 'Different roles. Different questions. <span class="text-sage">One VuloPilot.</span>';
$roles   = array( // quote, role
	array( 'What do I need to worry about?', 'Owners' ),
	array( 'What’s hurting my sales?', 'Stores' ),
	array( 'Where are we losing growth?', 'Marketers' ),
	array( 'What needs attention across every site?', 'Agencies' ),
);
$cta = array(
	'title'   => 'You have the questions. <span class="text-flare">VuloPilot has the answers.</span>',
	'text'    => 'Start free to see what is holding your website back. Upgrade when you want AI to fix, create and improve it for you.',
	'primary' => array( 'SuperPower your Website', '#pricing' ),
	'consult' => 'Request a free consultation',
	'note'    => 'Free to start · No credit card required',
);
?>
<section class="px-5 py-14">
	<div class="mx-auto max-w-6xl">
		<div class="mb-8 text-center" <?php vp_reveal(); ?>>
			<h2 class="text-4xl font-semibold leading-tight tracking-tight sm:text-5xl"><?php echo wp_kses_post( $title ); ?></h2>
		</div>
		<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
			<?php foreach ( $roles as $i => $r ) : ?>
				<div <?php vp_reveal( $i * 0.08 ); ?>>
					<div class="vp-hover card h-full p-6" style="--hr:<?php echo $i % 2 ? '1' : '-1'; ?>deg">
						<p class="text-2xl font-semibold leading-snug tracking-tight">“<?php echo esc_html( $r[0] ); ?>”</p>
						<p class="mt-5 text-xs font-semibold uppercase tracking-widest text-sage">— <?php echo esc_html( $r[1] ); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<section class="px-5 pb-16 pt-6">
	<div class="relative mx-auto max-w-5xl overflow-hidden rounded-sm bg-ink p-10 text-center text-bg sm:p-20">
		<div class="vp-drift absolute -left-10 -top-10 h-72 w-72 rounded-full bg-sage blur-3xl" style="--dx:40px;--dy:20px;--dd:10s"></div>
		<div class="vp-drift absolute -bottom-16 -right-10 h-72 w-72 rounded-full bg-flare/50 blur-3xl" style="--dx:-40px;--dy:-20px;--dd:12s"></div>
		<div class="relative">
			<h2 class="text-4xl font-semibold leading-tight tracking-tight sm:text-6xl"><?php echo wp_kses_post( $cta['title'] ); ?></h2>
			<p class="mx-auto mt-5 max-w-xl text-white/70"><?php echo esc_html( $cta['text'] ); ?></p>
			<div class="mt-9 flex flex-wrap items-center justify-center gap-3">
				<a href="<?php echo esc_url( vp_url( $cta['primary'][1] ) ); ?>" class="group inline-flex items-center gap-2 rounded-sm bg-bg px-7 py-3.5 font-semibold text-ink transition hover:bg-flare hover:text-white"><?php vp_i( 'zap', 16 ); ?><?php echo esc_html( $cta['primary'][0] ); ?> <?php vp_i( 'arrow-right', 16, 'transition group-hover:translate-x-1' ); ?></a>
				<button type="button" class="vp-open-consult inline-flex items-center gap-2 rounded-sm border border-white/25 px-7 py-3.5 font-semibold transition hover:bg-white/10"><?php vp_i( 'message-circle-question-mark', 16 ); ?><?php echo esc_html( $cta['consult'] ); ?></button>
			</div>
			<p class="mt-5 text-sm text-white/50"><?php echo esc_html( $cta['note'] ); ?></p>
		</div>
	</div>
</section>
