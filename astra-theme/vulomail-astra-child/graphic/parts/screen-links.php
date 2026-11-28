<?php
/**
 * Graphic: Five screens link cards (Get started)
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$items = array(
	array( 'Dashboard', 'Totals, channel status, recent failures', '/features#dashboard' ),
	array( 'Logs', 'Every email and text message', '/features#logs' ),
	array( 'Tools', 'Test email, test SMS, diagnostics', '/features#tools' ),
	array( 'SMS Alerts', 'Texts for site, store and customer events', '/sms' ),
	array( 'Settings', 'Connections, Email, SMS, Logging & Privacy, Data', '/features#privacy' ),
);
?>
<div class="vm:mt-12 vm:grid vm:gap-4 vm:sm:grid-cols-2 vm:lg:grid-cols-5" data-vm-stagger>
	<?php foreach ( $items as $i ) : ?>
		<div data-vm-st><a href="<?php echo esc_url( vm_url( $i[2] ) ); ?>" class="vm:card vm:group vm:block vm:h-full vm:p-6 vm:transition-transform vm:duration-500 vm:hover:-translate-y-1">
			<span class="vm:flex vm:items-center vm:justify-between"><span class="vm:font-medium"><?php echo esc_html( $i[0] ); ?></span><?php echo vm_icon( 'arrow-up-right', 'vm:h-4 vm:w-4 vm:text-ink-mute vm:transition-all vm:duration-300 vm:group-hover:rotate-45 vm:group-hover:text-ink' ); // phpcs:ignore ?></span>
			<span class="vm:mt-3 vm:block vm:text-sm vm:text-ink-soft"><?php echo esc_html( $i[1] ); ?></span>
		</a></div>
	<?php endforeach; ?>
</div>
