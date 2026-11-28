<?php
/**
 * Dark call-to-action band used at the bottom of inner pages.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$title  = 'You have the questions. <span class="text-flare">VuloPilot has the answers.</span>';
$note   = 'Free to start · No credit card required';
$button = array( 'SuperPower your Website', '/#pricing' );
?>
<div <?php vp_reveal(); ?>>
	<div class="relative overflow-hidden rounded-sm bg-ink p-10 text-center text-bg sm:p-16">
		<div class="absolute -right-10 -top-10 h-56 w-56 rounded-full bg-flare/30 blur-3xl"></div>
		<div class="absolute -left-10 bottom-0 h-56 w-56 rounded-full bg-sage/40 blur-3xl"></div>
		<div class="relative">
			<h2 class="font-display text-3xl sm:text-5xl"><?php echo wp_kses_post( $title ); ?></h2>
			<p class="mt-4 text-white/60"><?php echo esc_html( $note ); ?></p>
			<a href="<?php echo esc_url( vp_url( $button[1] ) ); ?>" class="mt-8 inline-flex items-center gap-2 rounded-sm bg-bg px-7 py-3.5 font-medium text-ink transition hover:bg-flare hover:text-white"><?php echo esc_html( $button[0] ); ?> <?php vp_i( 'arrow-right', 16 ); ?></a>
		</div>
	</div>
</div>
