<?php
/**
 * Graphic: Switching steps (comparison page)
 *
 * Props: slug.
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$c = vm_competitor( $a['slug'] ?? '' );
if ( ! $c ) {
	return;
}
if ( 'wsms' === $c['slug'] ) {
	$steps = array(
		array( 'Keep both if you like', 'WSMS doesn\'t deliver email, so it doesn\'t compete with VuloMail for your site\'s messages.' ),
		array( 'Avoid double texts', 'If both plugins send the same alert, such as new-order texts, switch it off in one of them.' ),
		array( 'Add your email provider', 'Connect SMTP or an email service in VuloMail, set it as primary and send a test.' ),
	);
} else {
	$hosted = 'Hosted service' === $c['model'];
	$steps  = array(
		array( $hosted ? 'Pick an email provider' : 'Add the same account', $hosted ? 'Create an account with an email service, such as Brevo, Postmark or Amazon SES, and verify your sending domain.' : 'Add the provider you use in ' . $c['name'] . ' as a VuloMail connection. Nothing changes until you set it as primary.' ),
		array( 'Set it as primary', 'Optionally add a backup connection, so a second provider takes over if the first fails.' ),
		array( 'Send a test', 'Tools → Send a test email goes through live routing. Diagnostics confirms your SPF and DMARC records.' ),
		array( 'Deactivate ' . $c['name'], 'Two plugins handling the same email compete for it. VuloMail\'s Diagnostics flags any it recognises.' ),
	);
}
echo '<div class="vm:mt-12 vm:grid vm:gap-5 ' . ( 4 === count( $steps ) ? 'vm:md:grid-cols-2 vm:lg:grid-cols-4' : 'vm:md:grid-cols-3' ) . '" data-vm-stagger>';
foreach ( $steps as $i => $s ) {
	echo '<div data-vm-st class="vm:card vm:p-7"><span class="vm:font-mono vm:text-sm vm:text-blue">0' . ( (int) $i + 1 ) . '</span><h3 class="vm:display vm:mt-3 vm:text-2xl">' . esc_html( $s[0] ) . '</h3><p class="vm:mt-3 vm:text-sm vm:leading-relaxed vm:text-ink-soft">' . esc_html( $s[1] ) . '</p></div>';
}
echo '</div>';
