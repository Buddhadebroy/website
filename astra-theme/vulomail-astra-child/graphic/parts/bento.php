<?php
/**
 * Graphic: What's inside (bento grid with live demos)
 *
 * Failover ticker, diagnostics, encrypted-key scramble, privacy toggles, source labels and the live log.
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$checks = array( array( 'Email routing', 'good' ), array( 'wp_mail() delivery', 'good' ), array( 'Sender domain DNS', 'warning' ), array( 'Other email plugins', 'good' ), array( 'Scheduled tasks', 'info' ) );
$tone   = array( 'good' => 'vm:text-green vm:bg-green-soft', 'warning' => 'vm:text-amber-ink vm:bg-amber-soft', 'info' => 'vm:text-blue vm:bg-blue-soft' );
$text   = array( 'good' => 'Passed', 'warning' => 'Needs attention', 'info' => 'Info' );
?>
<div class="vm:mt-16 vm:grid vm:auto-rows-[minmax(250px,auto)] vm:gap-5 vm:md:grid-cols-6" data-vm-stagger>
	<div class="vm:md:col-span-4" data-vm-st>
		<div data-vm-tilt="3" class="vm:card vm:h-full vm:overflow-hidden vm:p-7">
			<?php echo vm_pill( 'Failover', 'green' ); // phpcs:ignore ?>
			<h3 class="vm:display vm:mt-4 vm:text-4xl">A backup that steps in by itself.</h3>
			<p class="vm:mt-2 vm:max-w-md vm:text-ink-soft">Primary, then backup, then, if you allow it, WordPress’s own mailer as a last resort. Every attempt is recorded.</p>
			<div class="vm:mt-8 vm:flex vm:items-center vm:gap-2 vm:sm:gap-3" data-vm-failover>
				<?php foreach ( array( 'Primary', 'Backup', 'WordPress' ) as $i => $n ) : ?>
					<div class="vm:flex vm:flex-1 vm:items-center vm:gap-2 vm:sm:gap-3">
						<div data-vm-fo="<?php echo (int) $i; ?>" class="vm:flex-1 vm:rounded-xl vm:border-2 vm:px-2 vm:py-3 vm:text-center vm:text-xs vm:font-medium vm:sm:text-sm" style="background-color:<?php echo 0 === $i ? 'var(--green-soft)' : 'var(--surface)'; ?>;border-color:<?php echo 0 === $i ? 'var(--green)' : 'var(--line)'; ?>;transform:translateY(<?php echo 0 === $i ? '-4px' : '0px'; ?>)">
							<?php echo esc_html( $n ); ?>
							<span class="vm:mt-1 vm:block vm:text-[0.65rem] vm:text-ink-mute"><?php echo 0 === $i ? 'delivered' : 'standby'; ?></span>
						</div>
						<?php if ( $i < 2 ) : ?><span class="vm:text-ink-mute">→</span><?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<div class="vm:md:col-span-2" data-vm-st>
		<div data-vm-tilt="4" class="vm:card vm:h-full vm:p-7">
			<?php echo vm_pill( 'Diagnostics', 'blue' ); // phpcs:ignore ?>
			<h3 class="vm:display vm:mt-4 vm:text-3xl">Finds the usual suspects.</h3>
			<ul class="vm:mt-6 vm:space-y-2" data-vm-diag>
				<?php foreach ( $checks as $i => $c ) : ?>
					<li class="vm:flex vm:items-center vm:justify-between vm:rounded-xl vm:bg-paper vm:px-3 vm:py-2 vm:text-sm" data-vm-diag-i="<?php echo (int) $i; ?>" style="opacity:0;transform:translateX(-12px)">
						<?php echo esc_html( $c[0] ); ?>
						<span class="vm:rounded-full vm:px-2 vm:py-0.5 vm:text-[0.68rem] vm:font-medium <?php echo esc_attr( $tone[ $c[1] ] ); ?>"><?php echo esc_html( $text[ $c[1] ] ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<div class="vm:md:col-span-2" data-vm-st>
		<div data-vm-tilt="4" class="vm:card vm:h-full vm:p-7">
			<?php echo vm_pill( 'Encrypted', 'red' ); // phpcs:ignore ?>
			<h3 class="vm:display vm:mt-4 vm:text-3xl">Keys locked at rest.</h3>
			<div class="vm:mt-6 vm:rounded-xl vm:border vm:border-line vm:bg-paper vm:p-4" data-vm-secret>
				<p class="vm:eyebrow vm:text-ink-mute" data-vm-secret-label>What the database holds</p>
				<p class="vm:mt-2 vm:truncate vm:font-mono vm:text-base" data-vm-secret-text>vm1:Qm9nYXNzZWNyZXQ…</p>
				<p class="vm:mt-2 vm:text-xs vm:text-ink-mute">Hover to swap views</p>
			</div>
		</div>
	</div>
	<div class="vm:md:col-span-2" data-vm-st>
		<div data-vm-tilt="4" class="vm:card vm:h-full vm:p-7">
			<?php echo vm_pill( 'Privacy', 'amber' ); // phpcs:ignore ?>
			<h3 class="vm:display vm:mt-4 vm:text-3xl">You decide what’s kept.</h3>
			<ul class="vm:mt-6 vm:space-y-2.5">
				<?php foreach ( array( array( 'Keep a log', true ), array( 'Store message content', false ), array( 'Mask recipients', true ) ) as $t ) : ?>
					<li class="vm:flex vm:items-center vm:justify-between vm:text-sm"><?php echo esc_html( $t[0] ); ?><?php echo vm_toggle( $t[1], 'Toggle ' . $t[0] ); // phpcs:ignore ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<div class="vm:md:col-span-2" data-vm-st>
		<div data-vm-tilt="4" class="vm:card vm:h-full vm:p-7">
			<?php echo vm_pill( 'Source labels' ); // phpcs:ignore ?>
			<h3 class="vm:display vm:mt-4 vm:text-3xl">Know who sent it.</h3>
			<p class="vm:mt-2 vm:text-sm vm:text-ink-soft">Each message is labelled with the plugin or theme that called wp_mail().</p>
			<div class="vm:mt-6 vm:flex vm:items-center vm:gap-2 vm:font-mono vm:text-sm">
				<span class="vm:text-ink-mute">source:</span>
				<span class="vm:relative vm:inline-flex vm:h-8 vm:min-w-[9rem] vm:items-center vm:overflow-hidden vm:rounded-lg vm:bg-paper vm:px-3" data-vm-sources='["core","woocommerce","my-theme","my-forms","vulomail-test"]'><span>core</span></span>
			</div>
		</div>
	</div>
	<div class="vm:md:col-span-6" data-vm-st>
		<div class="vm:card vm:grid vm:h-full vm:items-center vm:gap-8 vm:overflow-hidden vm:p-7 vm:md:grid-cols-[1fr_1.2fr]">
			<div>
				<?php echo vm_pill( 'Delivery log', 'green' ); // phpcs:ignore ?>
				<h3 class="vm:display vm:mt-4 vm:text-4xl vm:sm:text-5xl">Every message, one row.</h3>
				<p class="vm:mt-3 vm:max-w-md vm:text-ink-soft">Filter by channel, status, date and search. Open any entry to see each attempt, the provider’s own message ID and the reason a message failed, then resend it.</p>
				<a href="<?php echo esc_url( vm_url( '/features#logs' ) ); ?>" class="vm:link-draw vm:mt-6 vm:inline-flex vm:items-center vm:gap-1 vm:text-sm vm:font-medium">Explore the log <?php echo vm_icon( 'arrow-up-right', 'vm:h-4 vm:w-4' ); // phpcs:ignore ?></a>
			</div>
			<?php echo vm_live_log( 4 ); // phpcs:ignore ?>
		</div>
	</div>
</div>
