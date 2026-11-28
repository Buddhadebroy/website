<?php
/**
 * Home: hero (rotating words, buttons, scanned-website graphic with floating chips).
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$badge    = 'The WordPress Growth Operating System';
$title    = 'Stop collecting warnings.<br/>Your website should';
$words    = array( 'improve itself.', 'stay secure.', 'get found.', 'be understood by AI.' ); // Rotate every 2.6 s.
$intro    = 'VuloPilot looks across your entire WordPress website, finds what is holding it back, explains why it matters, and helps you improve it.';
$primary  = array( 'text' => 'Connect your WordPress site', 'url' => '/docs/' );
$second   = array( 'text' => 'See how it works', 'url' => '#how' );
$note     = 'Free to start · No credit card required';

// Floating chips around the graphic: icon, text, colour, left, top, float delay (s).
$chips = array(
	array( 'shield-check', '3 plugins outdated', 'var(--rose)', '-8%', '6%', 0 ),
	array( 'gauge', 'Homepage 2.1s slower', 'var(--apricot)', '78%', '-4%', 0.8 ),
	array( 'search', '14 pages missing titles', 'var(--lilac)', '84%', '62%', 1.6 ),
	array( 'file-text', 'Stale: /pricing', 'var(--sage)', '-10%', '70%', 2.2 ),
);
?>
<section id="top" class="relative overflow-hidden px-5 pb-20 pt-32" data-vp="tilt">
	<div class="pointer-events-none absolute inset-0 -z-10">
		<div class="absolute -left-32 top-10 h-[480px] w-[480px] rounded-full bg-sage-soft blur-3xl" data-tilt="x:-30:30"></div>
		<div class="absolute -right-24 top-40 h-[420px] w-[420px] rounded-full bg-apricot-soft blur-3xl" data-tilt="x:30:-30"></div>
		<div class="absolute left-1/2 top-0 h-[380px] w-[380px] -translate-x-1/2 rounded-full bg-lilac-soft/70 blur-3xl"></div>
		<div class="absolute inset-0 opacity-[.35] [background-image:radial-gradient(var(--line)_1px,transparent_1px)] [background-size:26px_26px] [mask-image:radial-gradient(ellipse_at_center,black,transparent_70%)]"></div>
	</div>

	<div class="mx-auto max-w-5xl text-center">
		<div class="mb-7 inline-flex items-center gap-2 rounded-sm border border-line bg-surface/80 px-4 py-1.5 text-sm text-muted backdrop-blur" <?php vp_m( 'o:0 y:12 on:load' ); ?>>
			<?php vp_i( 'sparkles', 14, 'text-apricot' ); ?> <?php echo esc_html( $badge ); ?>
		</div>

		<h1 class="font-display text-5xl leading-[1.04] tracking-tight sm:text-7xl" <?php vp_m( 'o:0 y:24 t:.9 on:load' ); ?>>
			<?php echo wp_kses_post( $title ); ?>
			<span class="grid justify-items-center text-sage" data-vp="rotator" data-interval="2600">
				<span class="sr-only"><?php echo esc_html( implode( ' ', $words ) ); ?></span>
				<?php foreach ( $words as $i => $w ) : ?>
					<span aria-hidden="true" class="col-start-1 row-start-1<?php echo 0 === $i ? ' is-on' : ''; ?>" data-rot-item><?php echo esc_html( $w ); ?></span>
				<?php endforeach; ?>
			</span>
		</h1>

		<p class="mx-auto mt-7 max-w-2xl text-lg text-muted" <?php vp_m( 'o:0 d:.3 t:.9 on:load' ); ?>><?php echo esc_html( $intro ); ?></p>

		<div class="mt-9 flex flex-wrap items-center justify-center gap-3" <?php vp_m( 'o:0 y:12 d:.45 on:load' ); ?>>
			<a href="<?php echo esc_url( vp_url( $primary['url'] ) ); ?>" class="group inline-flex items-center gap-2 rounded-sm bg-ink px-7 py-3.5 font-medium text-bg shadow-[0_14px_30px_-12px_rgba(22,26,34,.6)] transition hover:bg-sage"><?php echo esc_html( $primary['text'] ); ?> <?php vp_i( 'arrow-right', 16, 'transition group-hover:translate-x-1' ); ?></a>
			<a href="<?php echo esc_url( vp_url( $second['url'] ) ); ?>" class="rounded-sm border border-line bg-surface/70 px-7 py-3.5 font-medium transition hover:border-ink"><?php echo esc_html( $second['text'] ); ?></a>
		</div>
		<p class="mt-4 text-sm text-muted"><?php echo esc_html( $note ); ?></p>
	</div>

	<div class="relative mx-auto mt-14 max-w-4xl" <?php vp_m( 'o:0 y:60 d:.6 t:1 on:load' ); ?>>
		<div data-tilt="rx:6:-6 ry:-8:8" style="transform:perspective(1200px)">
			<?php foreach ( $chips as $c ) : ?>
				<div class="absolute z-10 hidden md:block" <?php vp_m( 'o:0 s:.6 e:spring on:load d:' . ( 1.2 + $c[5] * 0.3 ), 'left:' . $c[3] . ';top:' . $c[4] ); ?>>
					<div class="floaty flex items-center gap-2 rounded-sm border border-line bg-surface px-3.5 py-2 text-xs font-medium shadow-lg" style="animation-delay:<?php echo esc_attr( $c[5] ); ?>s">
						<?php echo vp_icon_style( $c[0], 14, 'color:' . $c[2] ); // phpcs:ignore ?>
						<?php echo esc_html( $c[1] ); ?>
					</div>
				</div>
			<?php endforeach; ?>

			<?php get_template_part( 'template-parts/shared/hero-visual' ); ?>
		</div>
	</div>
</section>
