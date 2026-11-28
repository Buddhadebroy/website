<?php
/**
 * Graphic: Glossary (Get started)
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$g = array(
	array( 'Connection', 'One account with an email service or SMS provider that you\'ve added' ),
	array( 'Primary', 'The connection used for every message' ),
	array( 'Backup', 'The connection tried when the primary fails' ),
	array( 'WordPress default mailer', 'How WordPress sends email on its own, through your web server' ),
	array( 'Last resort', 'Handing an email to the WordPress mailer when primary and backup both fail' ),
	array( 'Sent', 'A provider accepted the message. It doesn\'t prove the message reached the inbox' ),
	array( 'Failed', 'No connection could send the message' ),
	array( 'Sent via backup', 'The primary failed and something else delivered the message' ),
);
?>
<div class="vm:mt-12 vm:grid vm:gap-x-12 vm:sm:grid-cols-2" data-vm-stagger>
	<?php foreach ( $g as $i ) : ?>
		<div data-vm-st class="vm:flex vm:gap-6 vm:border-t vm:border-line vm:py-5"><span class="vm:w-44 vm:shrink-0 vm:font-medium"><?php echo esc_html( $i[0] ); ?></span><span class="vm:text-ink-soft"><?php echo esc_html( $i[1] ); ?></span></div>
	<?php endforeach; ?>
</div>
