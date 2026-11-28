<?php
/**
 * Graphic: Privacy defaults (Security)
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$items = array(
	array( 'Message content', 'Off by default', 'Bodies can contain password-reset links and personal details. Store them only if you need to read or resend messages.' ),
	array( 'Masked recipients', 'Optional', 'Store j•••@example.com and •••••••••123 instead of full addresses and numbers. Masked entries can\'t be resent.' ),
	array( 'Retention', '30 days', 'Older entries are pruned daily. Set 0 to keep everything, or up to 3,650 days.' ),
	array( 'Logging', 'On', 'Turn it off entirely if you must not keep any record. The dashboard totals then stay at zero.' ),
);
?>
<div class="vm:mt-14 vm:grid vm:gap-5 vm:md:grid-cols-2" data-vm-stagger>
	<?php foreach ( $items as $i ) : ?>
		<div data-vm-st class="vm:flex vm:gap-6 vm:border-t vm:border-line vm:pt-6">
			<span class="vm:w-32 vm:shrink-0"><span class="vm:block vm:font-medium"><?php echo esc_html( $i[0] ); ?></span><span class="vm:mt-1 vm:block vm:font-mono vm:text-xs vm:text-ink-mute"><?php echo esc_html( $i[1] ); ?></span></span>
			<p class="vm:leading-relaxed vm:text-ink-soft"><?php echo esc_html( $i[2] ); ?></p>
		</div>
	<?php endforeach; ?>
</div>
