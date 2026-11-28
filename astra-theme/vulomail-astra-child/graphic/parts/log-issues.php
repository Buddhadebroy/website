<?php
/**
 * Graphic: The log is empty or missing things (FAQ)
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$items = array(
	array( 'Nothing at all', 'Keep a log is off under Settings → Logging & Privacy.' ),
	array( 'No message text', 'Store message content is off. It only applies to messages sent after you switch it on.' ),
	array( 'Recipients shown as j•••@example.com', 'Mask recipients is on.' ),
	array( 'Older entries are gone', 'They passed the Keep entries for period.' ),
	array( 'Resend is greyed out', 'The text wasn\'t stored, or recipients were masked.' ),
);
?>
<div data-vm-reveal data-delay="0.1" class="vm:mt-10 vm:space-y-4">
	<?php foreach ( $items as $i ) : ?>
		<div class="vm:rounded-2xl vm:border vm:border-line vm:bg-surface vm:p-5"><p class="vm:font-medium"><?php echo esc_html( $i[0] ); ?></p><p class="vm:mt-1 vm:text-sm vm:text-ink-soft"><?php echo esc_html( $i[1] ); ?></p></div>
	<?php endforeach; ?>
</div>
