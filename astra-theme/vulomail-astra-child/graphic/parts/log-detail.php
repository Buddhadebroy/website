<?php
/**
 * Graphic: Live log + entry detail (Features · delivery log)
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="vm:grid vm:gap-6 vm:md:grid-cols-2">
	<?php echo vm_live_log( 5 ); // phpcs:ignore ?>
	<div class="vm:card vm:p-6">
		<p class="vm:eyebrow vm:text-ink-mute">Entry detail</p>
		<p class="vm:display vm:mt-2 vm:text-3xl">Order #1042 received</p>
		<div class="vm:mt-3 vm:flex vm:gap-2"><?php echo vm_pill( 'Sent via backup', 'blue' ) . vm_pill( 'woocommerce' ); // phpcs:ignore ?></div>
		<dl class="vm:mt-5 vm:grid vm:grid-cols-[auto_1fr] vm:gap-x-6 vm:gap-y-2 vm:text-sm">
			<dt class="vm:text-ink-mute">To</dt><dd>ava@example.com</dd>
			<dt class="vm:text-ink-mute">Provider</dt><dd>SMTP</dd>
			<dt class="vm:text-ink-mute">Attempts</dt><dd class="vm:font-mono vm:text-xs vm:leading-6">1. mailgun: failed, timed out<br>2. smtp: accepted</dd>
		</dl>
		<div class="vm:mt-6 vm:flex vm:gap-2"><span class="vm:rounded-full vm:bg-ink vm:px-4 vm:py-2 vm:text-xs vm:text-paper">Resend</span><span class="vm:rounded-full vm:border vm:border-line vm:px-4 vm:py-2 vm:text-xs">Delete entry</span></div>
	</div>
</div>
