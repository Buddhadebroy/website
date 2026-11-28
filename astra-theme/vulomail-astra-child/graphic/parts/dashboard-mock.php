<?php
/**
 * Graphic: Dashboard mock (7 / 30 / 90 days)
 *
 * Bars grow in when seen; the period switch redraws them. Same pseudo-random bars as the original.
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="vm:card vm:overflow-hidden vm:p-6" data-vm-dash>
	<div class="vm:flex vm:flex-wrap vm:items-center vm:justify-between vm:gap-3">
		<div><p class="vm:eyebrow vm:text-ink-mute">Dashboard</p><p class="vm:display vm:mt-1 vm:text-3xl">Delivery</p></div>
		<div class="vm:inline-flex vm:rounded-full vm:border vm:border-line vm:bg-paper vm:p-1">
			<?php foreach ( array( 7, 30, 90 ) as $d ) : ?>
				<button type="button" data-vm-days="<?php echo (int) $d; ?>" class="vm:relative vm:rounded-full vm:px-3 vm:py-1 vm:text-xs <?php echo 7 === $d ? 'vm:bg-ink vm:text-paper' : 'vm:text-ink-soft'; ?>"><span class="vm:relative"><?php echo (int) $d; ?>D</span></button>
			<?php endforeach; ?>
		</div>
	</div>
	<div class="vm:mt-6 vm:grid vm:grid-cols-4 vm:gap-3 vm:text-center">
		<?php foreach ( array( array( 'Emails sent', 'vm:bg-green' ), array( 'Emails failed', 'vm:bg-red' ), array( 'SMS sent', 'vm:bg-green' ), array( 'SMS failed', 'vm:bg-red' ) ) as $s ) : ?>
			<div class="vm:rounded-xl vm:bg-paper vm:p-3"><p class="vm:h-2 vm:w-2 vm:rounded-full <?php echo esc_attr( $s[1] ); ?> vm:mx-auto"></p><p class="vm:mt-2 vm:text-[0.68rem] vm:text-ink-mute"><?php echo esc_html( $s[0] ); ?></p></div>
		<?php endforeach; ?>
	</div>
	<div class="vm:mt-6 vm:flex vm:h-40 vm:items-end vm:gap-1.5" data-vm-bars></div>
	<p class="vm:mt-4 vm:text-xs vm:text-ink-mute">Illustration. Your dashboard shows your own site’s totals for the period.</p>
</div>
