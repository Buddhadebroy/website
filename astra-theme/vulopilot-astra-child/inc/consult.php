<?php
/**
 * "Got a question?" consultation popup (port of components/ConsultPopup.tsx).
 * Open it from anything with class "vp-open-consult", a link to "#consult", or the [vp_consult_button] shortcode.
 * React components dispatch the same "vp:consult" event.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'wp_footer',
	function () {
		if ( ! vp_opt( 'vp_consult_enable' ) ) {
			return;
		}
		$field = 'mt-1.5 w-full rounded-sm border border-line bg-bg px-4 py-3 text-sm outline-none transition focus:border-sage focus:bg-surface focus:ring-4 focus:ring-sage/10';
		?>
		<div class="vp-scope"><div class="vp-consult fixed inset-0 z-[100] grid place-items-center overflow-y-auto p-4" data-vp-consult aria-hidden="true">
			<div class="absolute inset-0 bg-ink/50 backdrop-blur-sm" data-vp-close></div>
			<div role="dialog" aria-modal="true" aria-labelledby="consult-title" class="vp-consult-box relative grid w-full max-w-3xl overflow-hidden rounded-sm bg-surface shadow-2xl md:grid-cols-[0.9fr_1.1fr]">
				<button type="button" data-vp-close aria-label="<?php esc_attr_e( 'Close', 'vulopilot' ); ?>" class="absolute right-4 top-4 z-10 grid h-9 w-9 place-items-center rounded-full bg-bg/80 text-muted transition hover:text-ink"><?php echo vp_icon( 'x', 16 ); // phpcs:ignore ?></button>
				<div class="relative hidden overflow-hidden bg-ink p-8 text-bg md:block">
					<div class="vp-blob-c absolute -right-10 -top-10 h-52 w-52 rounded-full bg-sage blur-3xl"></div>
					<div class="vp-blob-d absolute -bottom-12 -left-10 h-52 w-52 rounded-full bg-flare/50 blur-3xl"></div>
					<div class="relative flex h-full flex-col justify-between">
						<div>
							<span class="vp-bob grid h-12 w-12 place-items-center rounded-sm bg-white/10 ring-1 ring-white/20"><?php echo vp_icon( 'message-circle-question', 22 ); // phpcs:ignore ?></span>
							<h2 id="consult-title" class="mt-6 text-3xl font-semibold leading-tight tracking-tight"><?php esc_html_e( 'Got a question?', 'vulopilot' ); ?></h2>
							<p class="mt-3 text-white/70"><?php esc_html_e( 'Before you go, ask us anything about your website. Request a free consultation and get a straight answer.', 'vulopilot' ); ?></p>
						</div>
						<ul class="mt-8 space-y-2.5 text-sm text-white/80">
							<?php foreach ( array( __( 'Free, no obligation', 'vulopilot' ), __( 'Plain-language advice', 'vulopilot' ), __( 'Reply within one business day', 'vulopilot' ) ) as $t ) : ?>
							<li class="flex gap-2.5"><?php echo vp_icon( 'check-circle2', 16, 'mt-0.5 shrink-0 text-flare' ); // phpcs:ignore ?><?php echo esc_html( $t ); ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
				<div class="p-7 sm:p-9">
					<div class="vp-consult-done hidden h-full min-h-[300px] flex-col items-center justify-center text-center">
						<span class="vp-pop-in grid h-16 w-16 place-items-center rounded-full bg-sage-soft text-sage"><?php echo vp_icon( 'check-circle2', 30 ); // phpcs:ignore ?></span>
						<h3 class="mt-5 text-2xl font-semibold"><?php esc_html_e( 'Thank you.', 'vulopilot' ); ?></h3>
						<p class="mt-2 max-w-xs text-muted"><?php esc_html_e( 'We have your question and will reply soon.', 'vulopilot' ); ?></p>
						<button type="button" data-vp-close class="mt-6 rounded-sm bg-ink px-6 py-2.5 text-sm font-semibold text-bg hover:bg-sage"><?php esc_html_e( 'Close', 'vulopilot' ); ?></button>
					</div>
					<form class="vp-consult-form space-y-3.5" novalidate>
						<div class="md:hidden"><h2 class="text-2xl font-semibold tracking-tight"><?php esc_html_e( 'Got a question?', 'vulopilot' ); ?></h2><p class="mt-1 text-sm text-muted"><?php esc_html_e( 'Request a free consultation.', 'vulopilot' ); ?></p></div>
						<div class="hidden text-sm font-semibold text-sage md:block"><?php esc_html_e( 'Request a free consultation', 'vulopilot' ); ?></div>
						<div class="grid gap-3.5 sm:grid-cols-2">
							<label class="block text-xs font-semibold"><?php esc_html_e( 'Your name', 'vulopilot' ); ?><input name="name" required autocomplete="name" class="<?php echo esc_attr( $field ); ?>" placeholder="Jane Doe" /></label>
							<label class="block text-xs font-semibold"><?php esc_html_e( 'Email', 'vulopilot' ); ?><input name="email" type="email" required autocomplete="email" class="<?php echo esc_attr( $field ); ?>" placeholder="jane@company.com" /></label>
						</div>
						<label class="block text-xs font-semibold"><?php esc_html_e( 'Website', 'vulopilot' ); ?> <span class="font-normal text-muted">(<?php esc_html_e( 'optional', 'vulopilot' ); ?>)</span><input name="site" autocomplete="url" class="<?php echo esc_attr( $field ); ?>" placeholder="yourwebsite.com" /></label>
						<label class="block text-xs font-semibold"><?php esc_html_e( 'Your question', 'vulopilot' ); ?><textarea name="question" required rows="4" class="<?php echo esc_attr( $field ); ?> resize-none" placeholder="<?php esc_attr_e( 'What would you like to know?', 'vulopilot' ); ?>"></textarea></label>
						<input type="text" name="company_url" tabindex="-1" autocomplete="off" class="hidden" aria-hidden="true" />
						<p role="alert" class="vp-consult-err hidden text-sm text-rose"></p>
						<button type="submit" class="group flex w-full items-center justify-center gap-2 rounded-sm bg-sage px-6 py-3.5 font-semibold text-white transition hover:bg-ink disabled:opacity-60"><span class="vp-consult-label"><?php esc_html_e( 'Request free consultation', 'vulopilot' ); ?></span><?php echo vp_icon( 'send', 15, 'transition group-hover:translate-x-0.5' ); // phpcs:ignore ?></button>
						<p class="text-center text-xs text-muted"><?php esc_html_e( 'We only use your details to reply to this request.', 'vulopilot' ); ?></p>
					</form>
				</div>
			</div>
		</div></div>
		<?php
	}
);

/** Email the request to the site owner. */
function vp_consult_ajax() {
	check_ajax_referer( 'vp_consult', 'nonce' );
	if ( ! empty( $_POST['company_url'] ) ) { // Honeypot.
		wp_send_json_success();
	}
	$name  = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
	$email = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$site  = sanitize_text_field( wp_unslash( $_POST['site'] ?? '' ) );
	$q     = sanitize_textarea_field( wp_unslash( $_POST['question'] ?? '' ) );
	if ( ! $name || ! is_email( $email ) || ! $q ) {
		wp_send_json_error( array( 'message' => __( 'Please fill in your name, a valid email and your question.', 'vulopilot' ) ), 400 );
	}
	$to   = vp_opt( 'vp_consult_email' ) ? vp_opt( 'vp_consult_email' ) : get_option( 'admin_email' );
	$body = sprintf( "Name: %s\nEmail: %s\nWebsite: %s\n\n%s", $name, $email, $site ? $site : '-', $q );
	$ok   = wp_mail( $to, __( 'Free consultation request', 'vulopilot' ), $body, array( 'Reply-To: ' . $name . ' <' . $email . '>' ) );
	do_action( 'vp_consult_request', compact( 'name', 'email', 'site', 'q' ) );
	$ok ? wp_send_json_success() : wp_send_json_error( array( 'message' => __( 'Something went wrong. Please try again, or email us directly.', 'vulopilot' ) ), 500 );
}
add_action( 'wp_ajax_vp_consult', 'vp_consult_ajax' );
add_action( 'wp_ajax_nopriv_vp_consult', 'vp_consult_ajax' );
