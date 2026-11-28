<?php
/**
 * Graphic: Tools → Diagnostics list
 *
 * Rows slide in each time the list comes into view; "Run diagnostics again" replays it.
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$checks = array(
	array( 'Email routing', 'good', 'Mailgun is primary; SMTP is the backup.' ),
	array( 'wp_mail() delivery', 'good', 'VuloMail handles wp_mail() on this site.' ),
	array( 'Recent email delivery', 'good', 'Email has been going out, according to the log.' ),
	array( 'Sender address', 'good', 'A sender email is configured.' ),
	array( 'Sender domain DNS', 'warning', 'SPF found. No DMARC record published.' ),
	array( 'Other email plugins', 'good', 'No other delivery plugin is active.' ),
	array( 'SMS routing', 'good', 'Twilio is the primary gateway.' ),
	array( 'Connections', 'error', '“Old SMTP” has a password that can no longer be read.' ),
	array( 'Server', 'info', 'PHP, WordPress and encryption support.' ),
	array( 'Delivery log', 'good', 'Table present; entries kept for 30 days.' ),
	array( 'Scheduled tasks', 'info', 'WordPress\'s scheduler is running.' ),
);
$tone = array( 'good' => array( 'Passed', 'vm:bg-green-soft vm:text-green' ), 'warning' => array( 'Needs attention', 'vm:bg-amber-soft vm:text-amber-ink' ), 'error' => array( 'Problem', 'vm:bg-red-soft vm:text-red' ), 'info' => array( 'Info', 'vm:bg-blue-soft vm:text-blue' ) );
?>
<div class="vm:card vm:overflow-hidden" data-vm-diaglist>
	<div class="vm:flex vm:items-center vm:justify-between vm:border-b vm:border-line vm:px-5 vm:py-4">
		<span class="vm:text-sm vm:font-medium">Tools → Diagnostics</span>
		<button type="button" data-vm-rerun class="vm:rounded-full vm:border vm:border-line vm:px-3 vm:py-1 vm:text-xs vm:transition-colors vm:hover:border-ink">Run diagnostics again</button>
	</div>
	<ul>
		<?php foreach ( $checks as $c ) : ?>
			<li class="vm:flex vm:items-start vm:justify-between vm:gap-4 vm:border-b vm:border-line/70 vm:px-5 vm:py-3 vm:last:border-0" style="opacity:0;transform:translateX(-10px)">
				<span><span class="vm:block vm:text-sm vm:font-medium"><?php echo esc_html( $c[0] ); ?></span><span class="vm:block vm:text-xs vm:text-ink-mute"><?php echo esc_html( $c[2] ); ?></span></span>
				<span class="vm:shrink-0 vm:rounded-full vm:px-2.5 vm:py-1 vm:text-[0.68rem] vm:font-medium <?php echo esc_attr( $tone[ $c[1] ][1] ); ?>"><?php echo esc_html( $tone[ $c[1] ][0] ); ?></span>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
