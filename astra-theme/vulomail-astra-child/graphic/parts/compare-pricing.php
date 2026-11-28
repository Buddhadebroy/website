<?php
/**
 * Graphic: $0 vs their plans (comparison page)
 *
 * Props: slug.
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$c = vm_competitor( $a['slug'] ?? '' );
if ( ! $c ) {
	return;
}
?>
<div class="vm:mt-12 vm:grid vm:gap-6 vm:lg:grid-cols-[1fr_1.6fr]">
	<div data-vm-reveal class="vm:relative vm:overflow-hidden vm:rounded-[2rem] vm:bg-ink vm:p-8 vm:text-paper vm:sm:p-10">
		<div class="vm:airmail vm:absolute vm:inset-x-0 vm:top-0 vm:h-1.5"></div>
		<p class="vm:eyebrow vm:text-paper/50">VuloMail</p>
		<p class="vm:display vm:mt-3 vm:text-7xl vm:leading-none">$0</p>
		<p class="vm:mt-4 vm:text-paper/70">No paid plan, no account, no limits set by VuloMail. Every feature on this page is in the free plugin.</p>
	</div>
	<div data-vm-reveal data-delay="0.1" class="vm:card vm:p-8 vm:sm:p-10">
		<p class="vm:eyebrow vm:text-ink-mute"><?php echo esc_html( $c['name'] ); ?></p>
		<p class="vm:mt-3 vm:text-ink-soft"><span class="vm:font-semibold vm:text-ink">Free version: </span><?php echo esc_html( $c['freeTier'] ); ?></p>
		<?php if ( ! empty( $c['plans'] ) ) : ?>
			<div class="vm:mt-6 vm:divide-y vm:divide-line/70 vm:border-y vm:border-line/70">
				<?php foreach ( $c['plans'] as $p ) : ?>
					<div class="vm:grid vm:grid-cols-[1fr_auto] vm:gap-x-4 vm:gap-y-1 vm:py-3.5 vm:sm:grid-cols-[1fr_auto_auto]"><p class="vm:font-medium"><?php echo esc_html( $p['name'] ); ?></p><p class="vm:text-right vm:font-semibold vm:sm:order-last"><?php echo esc_html( $p['price'] ); ?></p><p class="vm:text-sm vm:text-ink-mute vm:sm:text-right"><?php echo esc_html( $p['sites'] ); ?></p><?php if ( ! empty( $p['note'] ) ) : ?><p class="vm:col-span-2 vm:text-sm vm:text-ink-mute vm:sm:col-span-3"><?php echo esc_html( $p['note'] ); ?></p><?php endif; ?></div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<?php if ( ! empty( $c['pricingNote'] ) ) : ?><p class="vm:mt-5 vm:text-sm vm:text-ink-mute"><?php echo esc_html( $c['pricingNote'] ); ?></p><?php endif; ?>
	</div>
</div>
