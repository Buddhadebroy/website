<?php
/**
 * Graphic: $0 / forever card + what you will pay (Pricing)
 *
 * Props: included (list of strings).
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$included = isset( $a['included'] ) ? $a['included'] : array();
?>
<div class="vm:grid vm:items-stretch vm:gap-6 vm:lg:grid-cols-[1.3fr_1fr]">
	<div data-vm-reveal>
		<div data-vm-tilt="3" class="vm:relative vm:h-full vm:overflow-hidden vm:rounded-[2rem] vm:bg-ink vm:p-2 vm:text-paper">
			<div class="vm:airmail vm:absolute vm:inset-0"></div>
			<div class="vm:relative vm:h-full vm:rounded-[1.6rem] vm:bg-ink vm:p-8 vm:sm:p-12">
				<div class="vm:absolute vm:-right-8 vm:-top-8 vm:opacity-30" data-vm-parallax="0.2" data-vm-rotate="10"><?php echo vm_postmark( 200, 'Paid in full', 'vm:[&_svg]:text-paper/80' ); // phpcs:ignore ?></div>
				<p class="vm:eyebrow vm:text-paper/50">VuloMail</p>
				<p class="vm:display vm:mt-4 vm:text-[7rem] vm:leading-none vm:sm:text-[9rem]">$0<span class="vm:text-3xl vm:text-paper/50"> / forever</span></p>
				<p class="vm:mt-4 vm:max-w-md vm:text-paper/70">Everything, for every site you run. Install from your WordPress dashboard or upload the zip.</p>
				<div class="vm:mt-10 vm:grid vm:gap-3 vm:sm:grid-cols-2" data-vm-stagger>
					<?php foreach ( $included as $i ) : ?>
						<div data-vm-st class="vm:flex vm:gap-3 vm:text-sm"><span class="vm:mt-0.5 vm:grid vm:h-5 vm:w-5 vm:shrink-0 vm:place-items-center vm:rounded-full vm:bg-green vm:text-paper"><?php echo vm_icon( 'check', 'vm:h-3 vm:w-3' ); // phpcs:ignore ?></span><?php echo esc_html( $i ); ?></div>
					<?php endforeach; ?>
				</div>
				<div class="vm:mt-10"><a href="<?php echo esc_url( vm_url( (string) vm_opt( 'cta_url' ) ) ); ?>" data-vm-magnetic="0.25" class="vm-btn-link vm:group vm:relative vm:inline-flex vm:h-12 vm:items-center vm:gap-2 vm:overflow-hidden vm:rounded-full vm:bg-paper vm:px-6 vm:text-[0.95rem] vm:font-medium vm:text-ink vm:transition-colors vm:duration-300 vm:hover:bg-white"><span class="vm:relative">Install VuloMail</span><span class="vm:relative vm:grid vm:h-5 vm:w-5 vm:place-items-center vm:overflow-hidden"><?php echo vm_icon( 'arrow-right', 'vm:h-4 vm:w-4 vm:transition-transform vm:duration-500 vm:ease-[var(--ease-out)] vm:group-hover:translate-x-5' ) . vm_icon( 'arrow-right', 'vm:absolute vm:h-4 vm:w-4 vm:-translate-x-5 vm:transition-transform vm:duration-500 vm:ease-[var(--ease-out)] vm:group-hover:translate-x-0' ); // phpcs:ignore ?></span></a></div>
			</div>
		</div>
	</div>
	<div data-vm-reveal data-delay="0.1" class="vm:flex vm:flex-col vm:gap-6">
		<div class="vm:card vm:flex-1 vm:p-8">
			<p class="vm:eyebrow vm:text-ink-mute">What you will pay</p>
			<h2 class="vm:display vm:mt-3 vm:text-4xl">Your providers, directly.</h2>
			<p class="vm:mt-4 vm:leading-relaxed vm:text-ink-soft">Email services and SMS gateways bill you on their own terms. VuloMail adds nothing on top and never sits between you and them.</p>
		</div>
		<div class="vm:card vm:flex-1 vm:p-8">
			<p class="vm:eyebrow vm:text-ink-mute">Keep SMS costs predictable</p>
			<ul class="vm:mt-4 vm:space-y-3 vm:text-ink-soft"><li>Every alert is off until you switch it on.</li><li>A text over 160 plain characters, or 70 with emoji, is billed as more than one.</li><li>The email-failed alert sends at most one text every 15 minutes.</li></ul>
		</div>
	</div>
</div>
