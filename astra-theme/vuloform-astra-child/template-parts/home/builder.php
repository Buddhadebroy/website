<?php
/**
 * Home › The builder: sticky scroll story (port of components/Builder.tsx). Screenshot 2.
 * Also the block "Builder Sticky Steps (VuloForm)". Text: vf_section_defaults( 'builder' ).
 * Effects: vf-sticky-steps (section is 340vh tall, .vf-sticky stays on screen; the JS sets data-step while you scroll),
 * vf-steps (each <li><strong>Title</strong><br>Text</li> lights up on its step), vf-step-bar (fills with scroll).
 * The mock app is the graphic "builder-app" (graphic/parts/builder-app.php); it reacts to data-step.
 *
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;

$a     = vf_section_args( 'builder', $args ?? array() );
$steps = vf_lines( $a['steps'], true );
?>
<section id="builder" class="vf-sticky-steps relative" style="--vf-steps-h:<?php echo (int) ( 85 * max( 1, count( $steps ) ) ); ?>vh" data-step="0">
	<div class="vf-sticky flex items-center overflow-hidden">
		<div class="mx-auto grid w-full max-w-6xl items-center gap-10 px-4 sm:px-6 lg:grid-cols-[0.8fr_1.2fr]">
			<div>
				<?php echo vf_eyebrow( $a['eyebrow'], 'navy' ); // phpcs:ignore ?>
				<h2 class="mt-5 text-4xl leading-tight font-bold tracking-tight sm:text-5xl"><?php echo esc_html( $a['title'] ); ?><br><span class="text-ink-soft"><?php echo esc_html( $a['subtitle'] ); ?></span></h2>
				<div class="vf-step-bar mt-8 hidden sm:block"></div>
				<ol class="vf-steps">
					<?php foreach ( $steps as $s ) : ?>
					<li><strong><?php echo esc_html( $s[0] ); ?></strong><br><?php echo esc_html( $s[1] ); ?></li>
					<?php endforeach; ?>
				</ol>
			</div>
			<?php echo vf_graphic( 'builder-app' ); // phpcs:ignore ?>
		</div>
	</div>
</section>
