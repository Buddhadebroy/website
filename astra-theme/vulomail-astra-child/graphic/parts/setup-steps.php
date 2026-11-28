<?php
/**
 * Graphic: Five setup steps with a timeline (Get started)
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$steps = array(
	array( 'Install and activate', array( 'In WordPress go to Plugins → Add New.', 'Search for VuloMail, or choose Upload Plugin and pick the zip file.', 'Click Install Now, then Activate. A VuloMail item appears in the menu.' ), 'Nothing changes yet. WordPress keeps sending email exactly as before.', null ),
	array( 'Add an email connection', array( 'Go to VuloMail → Settings. It opens on Connections.', 'In the Email section click Add email connection.', 'Choose your provider, fill in what it asks for, and click Save connection.' ), 'Saved keys are encrypted immediately and never shown again.', array( '/providers', 'What each provider needs' ) ),
	array( 'Set it as primary', array( 'Your connection is saved but not in use yet.', 'Click Set as primary on its row.', 'Optional: add a second connection and click Set as backup.' ), 'This is the moment VuloMail starts routing your email.', null ),
	array( 'Set your sender', array( 'Open the Email sub-tab.', 'Enter a Sender email your provider has verified, like hello@yourdomain.com.', 'Add a Sender name, such as your business name.' ), 'Changes save by themselves a moment after you make them.', null ),
	array( 'Send a test', array( 'Go to VuloMail → Tools.', 'In Send a test email, check the address and click Send test.', 'Look in that inbox, and the spam folder too.' ), 'If it fails, the message on screen says why. Diagnostics will tell you what to fix.', array( '/faq', 'Troubleshooting a failed test' ) ),
);
?>
<div class="vm:relative">
	<div class="vm:absolute vm:bottom-0 vm:left-[1.15rem] vm:top-0 vm:hidden vm:w-px vm:bg-gradient-to-b vm:from-line-strong vm:via-line-strong vm:to-transparent vm:sm:block"></div>
	<ol class="vm:space-y-8">
		<?php foreach ( $steps as $i => $s ) : ?>
			<li class="vm:relative vm:grid vm:gap-6 vm:sm:grid-cols-[70px_1fr]">
				<div data-vm-reveal><span class="vm:relative vm:z-10 vm:grid vm:h-14 vm:w-14 vm:place-items-center vm:rounded-full vm:border-2 vm:border-ink vm:bg-paper vm:font-mono vm:text-lg vm:font-semibold vm:transition-colors vm:hover:bg-ink vm:hover:text-paper"><?php echo (int) $i + 1; ?></span></div>
				<div data-vm-reveal data-delay="0.08" class="vm:card vm:p-7 vm:sm:p-9">
					<h2 class="vm:display vm:text-4xl"><?php echo esc_html( $s[0] ); ?></h2>
					<ol class="vm:mt-5 vm:space-y-2.5 vm:text-ink-soft">
						<?php foreach ( $s[1] as $k => $it ) : ?><li class="vm:flex vm:gap-3"><span class="vm:font-mono vm:text-sm vm:text-ink-mute"><?php echo esc_html( chr( 97 + $k ) ); ?>.</span><?php echo esc_html( $it ); ?></li><?php endforeach; ?>
					</ol>
					<p class="vm:mt-6 vm:rounded-xl vm:bg-paper vm:px-4 vm:py-3 vm:text-sm vm:text-ink-soft"><?php echo esc_html( $s[2] ); ?></p>
					<?php if ( $s[3] ) : ?><a href="<?php echo esc_url( vm_url( $s[3][0] ) ); ?>" class="vm:link-draw vm:mt-5 vm:inline-flex vm:items-center vm:gap-1 vm:text-sm vm:font-medium"><?php echo esc_html( $s[3][1] ); ?> <?php echo vm_icon( 'arrow-up-right', 'vm:h-4 vm:w-4' ); // phpcs:ignore ?></a><?php endif; ?>
				</div>
			</li>
		<?php endforeach; ?>
	</ol>
</div>
