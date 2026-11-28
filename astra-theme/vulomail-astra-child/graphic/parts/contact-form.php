<?php
/**
 * Graphic: Contact form
 *
 * Sends the message by email (to Customize → VuloMail → Links → Contact form email, or the site admin email).
 * ?plan=personal|business|agency preselects "Licence & billing" and names the plan.
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$sent  = isset( $_GET['vm_sent'] ) ? sanitize_key( wp_unslash( $_GET['vm_sent'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
$plan  = isset( $_GET['plan'] ) ? sanitize_key( wp_unslash( $_GET['plan'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
$plans = array( 'personal' => 'Personal (1 site)', 'business' => 'Business (3 sites)', 'agency' => 'Agency (10 sites)' );
$field = 'vm:h-12 vm:w-full vm:rounded-2xl vm:border vm:border-line vm:bg-paper vm:px-4 vm:text-[0.95rem] vm:outline-none vm:transition-colors vm:placeholder:text-ink-mute vm:focus:border-ink';
$label = 'vm:mb-2 vm:block vm:text-sm vm:font-medium';
?>
<div class="vm:card vm:p-6 vm:sm:p-10">
	<p class="vm:eyebrow vm:text-ink-mute">Send a message</p>
	<h2 class="vm:display vm:mt-3 vm:text-4xl">How can we help?</h2>
	<?php if ( 'ok' === $sent ) : ?>
		<p class="vm:mt-6 vm:rounded-2xl vm:bg-green-soft vm:px-4 vm:py-3 vm:text-sm vm:text-green" role="status">Thanks, your message is on its way. We'll reply by email within one working day.</p>
	<?php elseif ( 'error' === $sent ) : ?>
		<p class="vm:mt-6 vm:rounded-2xl vm:bg-red-soft vm:px-4 vm:py-3 vm:text-sm vm:text-red" role="alert">Your message couldn't be sent. Please check the fields, or email us directly.</p>
	<?php endif; ?>
	<form class="vm:mt-8 vm:grid vm:gap-5 vm:sm:grid-cols-2" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<input type="hidden" name="action" value="vm_contact">
		<?php wp_nonce_field( 'vm_contact', 'vm_contact_nonce' ); ?>
		<input type="text" name="vm_website" value="" tabindex="-1" autocomplete="off" aria-hidden="true" style="position:absolute;left:-9999px">
		<label class="vm:block"><span class="<?php echo esc_attr( $label ); ?>">Your name</span><input class="<?php echo esc_attr( $field ); ?>" name="vm_name" required autocomplete="name"></label>
		<label class="vm:block"><span class="<?php echo esc_attr( $label ); ?>">Email</span><input class="<?php echo esc_attr( $field ); ?>" type="email" name="vm_email" required autocomplete="email"></label>
		<label class="vm:block"><span class="<?php echo esc_attr( $label ); ?>">Site address</span><input class="<?php echo esc_attr( $field ); ?>" type="url" name="vm_site" placeholder="https://"></label>
		<label class="vm:block"><span class="<?php echo esc_attr( $label ); ?>">Topic</span>
			<select class="<?php echo esc_attr( $field ); ?>" name="vm_topic">
				<?php foreach ( array( 'General question', 'Technical help', 'Feature request', 'Partnership' ) as $t ) : ?>
					<option<?php selected( $plan && 'Licence & billing' === $t ); ?>><?php echo esc_html( $t ); ?></option>
				<?php endforeach; ?>
			</select>
		</label>
		<label class="vm:block vm:sm:col-span-2"><span class="<?php echo esc_attr( $label ); ?>">Message</span>
			<textarea class="<?php echo esc_attr( str_replace( 'vm:h-12', 'vm:min-h-40 vm:py-3', $field ) ); ?>" name="vm_message" rows="6" required><?php echo isset( $plans[ $plan ] ) ? esc_textarea( 'I\'d like the ' . $plans[ $plan ] . ' licence.' ) : ''; ?></textarea>
		</label>
		<label class="vm:flex vm:items-start vm:gap-3 vm:text-sm vm:text-ink-soft vm:sm:col-span-2"><input type="checkbox" name="vm_consent" value="1" required class="vm:mt-1 vm:h-4 vm:w-4 vm:accent-[var(--ink)]"> I agree that VuloLabs uses these details to answer my message.</label>
		<div class="vm:sm:col-span-2">
			<button type="submit" data-vm-magnetic="0.25" class="vm:group vm:relative vm:inline-flex vm:h-12 vm:items-center vm:gap-2 vm:overflow-hidden vm:rounded-full vm:bg-blue vm:px-6 vm:text-[0.95rem] vm:font-medium vm:text-white vm:shadow-[0_10px_30px_-12px_rgba(0,59,205,0.6)] vm:transition-colors vm:duration-300 vm:hover:bg-blue-light"><span class="vm:relative">Send message</span><span class="vm:relative vm:grid vm:h-5 vm:w-5 vm:place-items-center vm:overflow-hidden"><?php echo vm_icon( 'arrow-right', 'vm:h-4 vm:w-4 vm:transition-transform vm:duration-500 vm:ease-[var(--ease-out)] vm:group-hover:translate-x-5' ) . vm_icon( 'arrow-right', 'vm:absolute vm:h-4 vm:w-4 vm:-translate-x-5 vm:transition-transform vm:duration-500 vm:ease-[var(--ease-out)] vm:group-hover:translate-x-0' ); // phpcs:ignore ?></span></button>
		</div>
	</form>
</div>
