<?php
/**
 * Home: pricing section (compact plan cards + guarantees).
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$f       = vp_site_facts();
$eyebrow = 'Plans for your next step';
$title   = 'Start with insight. <span class="text-sage">Upgrade to action.</span>';
$intro   = 'Start free to discover what’s holding your website back. Upgrade when you’re ready to let AI fix, create and improve it automatically.';
$trust   = array( // icon, title, text
	array( 'rotate-ccw', $f['refund_days'] . '-day money-back guarantee', 'Risk-free, with a simple refund' ),
	array( 'credit-card', 'Secure payments', $f['payments'] ),
);
$link    = array( 'text' => 'Full pricing and comparison', 'url' => '/pricing/' );
?>
<section id="pricing" class="px-5 py-16">
	<div class="mx-auto max-w-6xl">
		<div class="mx-auto max-w-3xl text-center" <?php vp_reveal(); ?>>
			<?php vp_eyebrow( $eyebrow ); ?>
			<h2 class="mt-5 text-4xl font-semibold leading-[1.08] tracking-tight sm:text-6xl"><?php echo wp_kses_post( $title ); ?></h2>
			<p class="mt-4 text-lg text-muted"><?php echo esc_html( $intro ); ?></p>
		</div>
		<div class="mt-9" <?php vp_reveal(); ?>><?php get_template_part( 'template-parts/shared/plans', null, array( 'compact' => true ) ); ?></div>
		<div class="mt-8" <?php vp_reveal(); ?>>
			<div class="mx-auto grid max-w-3xl gap-3 rounded-sm border border-line bg-surface/70 p-3 sm:grid-cols-2">
				<?php foreach ( $trust as $x ) : ?>
					<div class="flex items-start gap-3 rounded-sm p-3">
						<span class="grid h-10 w-10 shrink-0 place-items-center rounded-sm bg-sage-soft text-sage"><?php vp_i( $x[0], 18 ); ?></span>
						<div><div class="text-sm font-semibold"><?php echo esc_html( $x[1] ); ?></div><div class="text-xs text-muted"><?php echo esc_html( $x[2] ); ?></div></div>
					</div>
				<?php endforeach; ?>
			</div>
			<p class="mt-5 text-center text-sm text-muted"><a href="<?php echo esc_url( vp_url( $link['url'] ) ); ?>" class="font-semibold text-sage hover:underline"><?php echo esc_html( $link['text'] ); ?></a></p>
		</div>
	</div>
</section>
