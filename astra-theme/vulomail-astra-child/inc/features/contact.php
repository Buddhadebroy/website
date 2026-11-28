<?php
/**
 * Contact form handler (graphic part "contact-form"): emails the message, then returns to the page.
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

function vm_handle_contact() {
	$back = wp_get_referer() ? wp_get_referer() : home_url( '/contact/' );
	$back = remove_query_arg( array( 'vm_sent' ), $back );
	$ok   = isset( $_POST['vm_contact_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['vm_contact_nonce'] ) ), 'vm_contact' );
	if ( $ok && empty( $_POST['vm_website'] ) && ! empty( $_POST['vm_consent'] ) ) {
		$name    = sanitize_text_field( wp_unslash( $_POST['vm_name'] ?? '' ) );
		$email   = sanitize_email( wp_unslash( $_POST['vm_email'] ?? '' ) );
		$site    = esc_url_raw( wp_unslash( $_POST['vm_site'] ?? '' ) );
		$topic   = sanitize_text_field( wp_unslash( $_POST['vm_topic'] ?? '' ) );
		$message = sanitize_textarea_field( wp_unslash( $_POST['vm_message'] ?? '' ) );
		if ( $name && is_email( $email ) && $message ) {
			$to   = vm_opt( 'contact_email' ) ? vm_opt( 'contact_email' ) : get_option( 'admin_email' );
			$body = "Name: $name\nEmail: $email\nSite: $site\nTopic: $topic\n\n$message\n";
			$ok   = wp_mail( $to, '[' . get_bloginfo( 'name' ) . '] ' . $topic . ' from ' . $name, $body, array( 'Reply-To: ' . $name . ' <' . $email . '>' ) );
		} else {
			$ok = false;
		}
	} else {
		$ok = false;
	}
	wp_safe_redirect( add_query_arg( 'vm_sent', $ok ? 'ok' : 'error', $back ) . '#contact' );
	exit;
}
add_action( 'admin_post_vm_contact', 'vm_handle_contact' );
add_action( 'admin_post_nopriv_vm_contact', 'vm_handle_contact' );
