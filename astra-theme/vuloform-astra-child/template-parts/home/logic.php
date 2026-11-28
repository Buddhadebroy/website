<?php
/**
 * Home › Smart forms (port of components/Logic.tsx). Two live demos: graphics "conditional-demo" and "calc-demo".
 *
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;
?>
<section id="logic" class="relative isolate py-28 sm:py-36">
	<div class="absolute inset-x-0 top-24 -z-10 mx-auto h-[600px] max-w-5xl rounded-full bg-lilac-soft/70 blur-3xl"></div>
	<div class="mx-auto max-w-6xl px-4 sm:px-6">
		<div class="vf-reveal mx-auto max-w-2xl text-center">
			<?php echo vf_eyebrow( 'Smart forms' ); // phpcs:ignore ?>
			<h2 class="mt-5 text-4xl leading-tight font-bold tracking-tight sm:text-5xl">Forms that think <span class="text-mint">before</span> they ask.</h2>
			<p class="mt-5 text-lg text-ink-soft">Show, hide or require fields based on other answers. Split long forms into steps. Work out totals live — then recompute them on your server so nobody can fake the numbers.</p>
		</div>
		<div class="mt-14 grid gap-6 lg:grid-cols-2">
			<div class="vf-reveal" style="--vf-delay:.05s">
				<h3 class="mb-4 pl-2 text-lg font-bold">Conditional logic</h3>
				<?php echo vf_graphic( 'conditional-demo' ); // phpcs:ignore ?>
			</div>
			<div class="vf-reveal" style="--vf-delay:.15s">
				<h3 class="mb-4 pl-2 text-lg font-bold">Multi-step + calculations</h3>
				<?php echo vf_graphic( 'calc-demo' ); // phpcs:ignore ?>
			</div>
		</div>
	</div>
</section>
