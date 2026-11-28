<?php
/**
 * Home › Privacy & ownership (port of components/Privacy.tsx). Screenshot 4.
 * Also the block "Privacy Scrolling Words (VuloForm)". Text: vf_section_defaults( 'privacy' ).
 * Effects: vf-drift-left / vf-drift-right (the giant faded words slide sideways while you scroll), vf-reveal, vf-stagger.
 *
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;

$a = vf_section_args( 'privacy', $args ?? array() );
?>
<section id="privacy" class="vf-fx-root relative isolate overflow-hidden py-28 sm:py-36">
	<!-- parallax word bands -->
	<div aria-hidden="true" class="pointer-events-none absolute inset-x-0 top-10 -z-10 space-y-2 select-none">
		<p class="vf-band vf-drift-left display text-[clamp(5rem,14vw,12rem)] leading-none font-bold whitespace-nowrap text-sky-soft"><?php echo esc_html( $a['band1'] ); ?></p>
		<p class="vf-band vf-drift-right display text-[clamp(5rem,14vw,12rem)] leading-none font-bold whitespace-nowrap text-mint-soft"><?php echo esc_html( $a['band2'] ); ?></p>
	</div>

	<div class="mx-auto max-w-6xl px-4 pt-32 sm:px-6">
		<div class="vf-reveal max-w-2xl">
			<?php echo vf_eyebrow( $a['eyebrow'] ); // phpcs:ignore ?>
			<h2 class="mt-5 text-4xl leading-tight font-bold tracking-tight sm:text-5xl"><?php echo esc_html( $a['title'] ); ?> <span class="text-navy"><?php echo esc_html( $a['highlight'] ); ?></span> <?php echo esc_html( $a['titleAfter'] ); ?></h2>
			<p class="mt-5 text-lg text-ink-soft"><?php echo esc_html( $a['intro'] ); ?></p>
		</div>

		<div class="vf-stagger mt-14 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
			<?php foreach ( vf_lines( $a['promises'], true ) as $p ) : ?>
			<div class="h-full rounded-[24px] border border-line bg-white/85 p-6 backdrop-blur">
				<span class="grid h-11 w-11 place-items-center rounded-full bg-mint text-lg font-bold text-white">✓</span>
				<h3 class="mt-5 text-xl font-bold"><?php echo esc_html( $p[0] ); ?></h3>
				<p class="mt-2 text-sm leading-relaxed text-ink-soft"><?php echo esc_html( $p[1] ); ?></p>
			</div>
			<?php endforeach; ?>
		</div>

		<?php if ( vf_lines( $a['extras'] ) ) : ?>
		<div class="vf-reveal mt-6 grid gap-5 md:grid-cols-3">
			<?php foreach ( vf_lines( $a['extras'], true ) as $e ) : ?>
			<div class="rounded-[24px] bg-sky-soft/70 p-6"><h3 class="font-bold"><?php echo esc_html( $e[0] ); ?></h3><p class="mt-1.5 text-sm text-ink-soft"><?php echo esc_html( $e[1] ); ?></p></div>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
	</div>
</section>
