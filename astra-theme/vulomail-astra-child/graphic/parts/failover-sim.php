<?php
/**
 * Graphic: Failover simulator
 *
 * Four switches take connections down; the chain of attempts and the log entry update.
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$sw = function ( $key, $label ) {
	return '<label class="vm:flex vm:cursor-pointer vm:items-center vm:justify-between vm:gap-4 vm:rounded-2xl vm:border vm:border-line vm:bg-surface vm:px-4 vm:py-3 vm:text-sm">' . esc_html( $label )
		. '<button type="button" role="switch" aria-checked="true" data-vm-sim="' . $key . '" data-vm-toggle data-on="vm:bg-ink" class="vm:relative vm:h-6 vm:w-11 vm:shrink-0 vm:rounded-full vm:transition-colors vm:duration-300 vm:bg-ink"><span class="vm-knob vm:absolute vm:top-1 vm:h-4 vm:w-4 vm:rounded-full vm:bg-paper" style="left:24px"></span></button></label>';
};
?>
<div class="vm:grid vm:gap-6 vm:lg:grid-cols-[1fr_1.3fr]" data-vm-failsim>
	<div class="vm:space-y-3">
		<p class="vm:eyebrow vm:text-ink-mute">Break things on purpose</p>
		<?php echo $sw( 'primary', 'Primary connection is up' ) . $sw( 'has', 'A backup is set' ) . $sw( 'backup', 'Backup connection is up' ) . $sw( 'last', 'Fall back to WordPress default mailer' ); // phpcs:ignore ?>
	</div>
	<div class="vm:card vm:relative vm:overflow-hidden vm:p-6">
		<div class="vm:dot-grid vm:absolute vm:inset-0 vm:opacity-40"></div>
		<div class="vm:relative">
			<div class="vm:flex vm:items-center vm:gap-3 vm:rounded-2xl vm:border vm:border-line vm:bg-paper vm:px-4 vm:py-3"><?php echo vm_icon( 'mail', 'vm:h-4 vm:w-4' ); // phpcs:ignore ?><span class="vm:text-sm vm:font-medium">wp_mail( “Password reset” )</span></div>
			<div data-vm-chain>
				<div data-vm-chain-step>
					<div class="vm:flex vm:justify-center vm:py-1.5 vm:text-ink-mute"><?php echo vm_icon( 'arrow-down', 'vm:h-4 vm:w-4' ); // phpcs:ignore ?></div>
					<div class="vm:flex vm:items-center vm:justify-between vm:rounded-2xl vm:border-2 vm:px-4 vm:py-3 vm:border-green vm:bg-green-soft"><span class="vm:text-sm vm:font-medium">Primary · Mailgun</span><span class="vm:grid vm:h-6 vm:w-6 vm:place-items-center vm:rounded-full vm:bg-green vm:text-white"><?php echo vm_icon( 'check', 'vm:h-3.5 vm:w-3.5' ); // phpcs:ignore ?></span></div>
				</div>
			</div>
			<template data-vm-icons><?php echo vm_icon( 'arrow-down', 'vm:h-4 vm:w-4' ) . '|' . vm_icon( 'check', 'vm:h-3.5 vm:w-3.5' ) . '|' . vm_icon( 'x', 'vm:h-3.5 vm:w-3.5' ); // phpcs:ignore ?></template>
			<div data-vm-result class="vm:mt-5 vm:flex vm:items-center vm:justify-between vm:rounded-2xl vm:bg-ink vm:px-4 vm:py-3 vm:text-paper"><span class="vm:text-sm">Log entry</span><span data-vm-status class="vm:rounded-full vm:px-2.5 vm:py-1 vm:text-xs vm:font-medium vm:bg-green-soft vm:text-green">Sent</span></div>
			<p class="vm:mt-3 vm:text-xs vm:text-ink-mute" data-vm-attempts>1 attempt recorded on one row.</p>
		</div>
	</div>
</div>
