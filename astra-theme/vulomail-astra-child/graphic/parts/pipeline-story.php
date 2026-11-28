<?php
/**
 * Graphic: How a message travels (sticky scroll story)
 *
 * Five steps over 5 × 85vh of scroll; the left text swaps per step, the diagram lights up and the
 * envelope follows the scroll (spring 120/30). Steps: props.steps or the original copy.
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$steps = isset( $a['steps'] ) ? $a['steps'] : array(
	array( '01', 'Your site sends an email.', 'WordPress, WooCommerce, your forms, any plugin calling wp_mail(). Nothing in those plugins changes.' ),
	array( '02', 'VuloMail picks it up.', 'It reads every header shape WordPress accepts, applies your sender, guards against header injection and labels the plugin that sent it.' ),
	array( '03', 'Your primary tries first.', 'The message goes to the provider you chose. If it is down, rejects the key, or times out, VuloMail doesn\'t give up.' ),
	array( '04', 'The backup takes over.', 'Your second connection is tried straight away. If both fail, WordPress\'s own mailer can still try as a last resort.' ),
	array( '05', 'One entry in the log.', 'Every attempt is kept on a single row, so you can see exactly what happened. Optionally, a text tells you something failed.' ),
);
$node = function ( $key, $label, $sub, $icon ) {
	return '<div data-vm-node="' . $key . '" class="vm:relative vm:flex vm:items-center vm:gap-3 vm:rounded-2xl vm:border-2 vm:border-line vm:bg-surface vm:px-3 vm:py-2.5 vm:shadow-sm vm:transition-colors vm:duration-500 vm:sm:px-4 vm:sm:py-3.5" style="opacity:0.55">'
		. '<span class="vm:grid vm:h-8 vm:w-8 vm:shrink-0 vm:place-items-center vm:rounded-xl vm:bg-paper-deep vm:sm:h-10 vm:sm:w-10">' . $icon . '</span>'
		. '<span class="vm:min-w-0"><span class="vm:block vm:text-sm vm:font-semibold">' . esc_html( $label ) . '</span><span class="vm:block vm:truncate vm:font-mono vm:text-[0.7rem] vm:text-ink-mute">' . esc_html( $sub ) . '</span></span>'
		. '<span class="vm-node-fail vm:ml-auto" style="transform:scale(0)" hidden>' . vm_pill( vm_icon( 'circle-alert', 'vm:h-3 vm:w-3' ) . ' timed out', 'red' ) . '</span>'
		. '<span class="vm-node-ok vm:ml-auto" style="transform:scale(0)" hidden>' . vm_pill( vm_icon( 'check', 'vm:h-3 vm:w-3' ) . ' accepted', 'green' ) . '</span></div>';
};
?>
<div class="vm:relative" data-vm-pipeline style="height:<?php echo count( $steps ) * 85; ?>vh">
	<div class="vm:sticky vm:top-0 vm:flex vm:min-h-screen vm:items-center vm:overflow-hidden vm:py-16 vm:sm:py-24">
		<div class="vm:mx-auto vm:grid vm:w-full vm:max-w-7xl vm:items-center vm:gap-6 vm:px-4 vm:sm:gap-10 vm:sm:px-8 vm:lg:grid-cols-[1fr_1.15fr] vm:lg:gap-20">
			<div>
				<p class="vm:eyebrow vm:text-ink-mute">How a message travels</p>
				<div class="vm:relative vm:mt-4 vm:min-h-[190px] vm:sm:mt-6 vm:sm:min-h-[260px]">
					<?php foreach ( $steps as $i => $s ) : ?>
						<div data-vm-step="<?php echo (int) $i; ?>"<?php echo $i ? ' hidden' : ''; ?>>
							<span class="vm:display vm:text-5xl vm:text-red vm:sm:text-8xl"><?php echo esc_html( $s[0] ); ?></span>
							<h3 class="vm:display vm:mt-2 vm:text-3xl vm:sm:mt-3 vm:sm:text-5xl"><?php echo esc_html( $s[1] ); ?></h3>
							<p class="vm:mt-3 vm:max-w-md vm:leading-relaxed vm:text-ink-soft vm:sm:mt-4 vm:sm:text-lg"><?php echo esc_html( $s[2] ); ?></p>
						</div>
					<?php endforeach; ?>
				</div>
				<div class="vm:mt-5 vm:flex vm:items-center vm:gap-3 vm:sm:mt-8">
					<?php foreach ( $steps as $i => $s ) : ?>
						<span class="vm:relative vm:h-1 vm:flex-1 vm:overflow-hidden vm:rounded-full vm:bg-line"><span data-vm-bar="<?php echo (int) $i; ?>" class="vm:absolute vm:inset-0 vm:origin-left vm:rounded-full vm:bg-ink" style="transform:scaleX(<?php echo 0 === $i ? 1 : 0; ?>)"></span></span>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="vm:card vm:relative vm:overflow-hidden vm:p-5 vm:sm:p-8">
				<div class="vm:dot-grid vm:absolute vm:inset-0 vm:opacity-50"></div>
				<div class="vm:relative vm:grid vm:grid-cols-[28px_1fr] vm:gap-x-4 vm:sm:gap-x-6">
					<div class="vm:relative">
						<div class="vm:absolute vm:bottom-6 vm:left-1/2 vm:top-6 vm:w-px vm:-translate-x-1/2 vm:border-l-2 vm:border-dashed vm:border-line-strong"></div>
						<div data-vm-envelope class="vm:absolute vm:left-1/2 vm:z-10 vm:-translate-x-1/2" style="top:0%">
							<span class="vm:grid vm:h-7 vm:w-7 vm:place-items-center vm:rounded-full vm:bg-ink vm:text-paper vm:shadow-lg"><?php echo vm_icon( 'mail', 'vm:h-3.5 vm:w-3.5' ); // phpcs:ignore ?></span>
						</div>
					</div>
					<div class="vm:min-w-0 vm:space-y-2 vm:sm:space-y-4">
						<?php
						echo $node( 'wp', 'wp_mail()', 'woocommerce · Order #1042', vm_icon( 'code-2', 'vm:h-4 vm:w-4' ) ); // phpcs:ignore
						echo $node( 'vm', 'VuloMail', 'sender applied · source labelled', vm_icon( 'mail', 'vm:h-4 vm:w-4' ) ); // phpcs:ignore
						echo $node( 'primary', 'Primary · Mailgun', 'api.mailgun.net', '<span class="vm:text-xs vm:font-bold">1</span>' ); // phpcs:ignore
						echo $node( 'backup', 'Backup · SMTP', 'smtp.example.com:587', '<span class="vm:text-xs vm:font-bold">2</span>' ); // phpcs:ignore
						?>
						
					</div>
					<template data-vm-logentry-tpl><div data-vm-logentry class="vm:overflow-hidden">
							<div class="vm:rounded-2xl vm:border vm:border-line vm:bg-paper vm:p-4">
								<div class="vm:flex vm:items-center vm:justify-between"><span class="vm:text-sm vm:font-semibold">Order #1042 received</span><?php echo vm_pill( vm_icon( 'rotate-cw', 'vm:h-3 vm:w-3' ) . ' Sent via backup', 'blue' ); // phpcs:ignore ?></div>
								<ol class="vm:mt-3 vm:space-y-1.5 vm:font-mono vm:text-[0.7rem] vm:text-ink-soft">
									<li class="vm:flex vm:gap-2"><?php echo vm_icon( 'x', 'vm:h-3.5 vm:w-3.5 vm:text-red' ); // phpcs:ignore ?> mailgun · Connection timed out after 15s</li>
									<li class="vm:flex vm:gap-2"><?php echo vm_icon( 'check', 'vm:h-3.5 vm:w-3.5 vm:text-green' ); // phpcs:ignore ?> smtp · accepted</li>
								</ol>
							</div>
						</div></template>
				</div>
			</div>
		</div>
	</div>
</div>
