<?php
/**
 * WooCommerce: your store rules (moved from the old Astra child theme).
 *   - No cart page: cart → checkout (or shop when empty), one product in the cart at a time, no "View cart" in the mini cart.
 *   - Payment gateways by billing country: India = bank transfer only, everywhere else = Stripe / PayPal only.
 *   - My Account: profile + support box above the account menu (styled by assets/css/nf-woo.css).
 * Runs only when WooCommerce is active.
 *
 * @package Notifima
 */

defined( 'ABSPATH' ) || exit;

/* Styles for My Account and BetterDocs. */
add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_style( 'nf-woo', NF_URI . '/assets/css/nf-woo.css', array(), nf_ver( 'assets/css/nf-woo.css' ) );
		wp_enqueue_style( 'nf-docs', NF_URI . '/assets/css/nf-docs.css', array(), nf_ver( 'assets/css/nf-docs.css' ) );
	},
	20
);

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

/* Remove the cart page: straight to checkout, or to the shop when the cart is empty. */
add_action(
	'template_redirect',
	function () {
		if ( ! function_exists( 'is_cart' ) || ! is_cart() || ! WC()->cart ) {
			return;
		}
		wp_safe_redirect( WC()->cart->is_empty() ? wc_get_page_permalink( 'shop' ) : wc_get_checkout_url() );
		exit;
	}
);

/* Empty the cart before adding a product (one product per order). */
add_filter(
	'woocommerce_add_to_cart_validation',
	function ( $passed ) {
		if ( WC()->cart && ! WC()->cart->is_empty() ) {
			WC()->cart->empty_cart();
		}
		return $passed;
	},
	20
);

/* Mini cart: no "View cart" button, no quantity. */
add_action(
	'init',
	function () {
		remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart', 10 );
	}
);
add_filter( 'woocommerce_widget_cart_item_quantity', '__return_empty_string' );

/* Payment gateways by country. 1. The checkout starts on United States. */
add_filter( 'default_checkout_billing_country', function () { return 'US'; } );

/* Checkout Block: keep the chosen billing country on the customer (Store API). */
add_action(
	'woocommerce_store_api_cart_update_customer_from_request',
	function ( $customer, $request ) {
		$address = $request->get_param( 'billing_address' );
		if ( is_array( $address ) && isset( $address['country'] ) && is_string( $address['country'] ) ) {
			$country = strtoupper( sanitize_text_field( $address['country'] ) );
			if ( preg_match( '/^[A-Z]{2}$/', $country ) ) {
				$customer->set_billing_country( $country );
			}
		}
	},
	10,
	2
);

/* 2. India: bank transfer only. Everyone else: Stripe / PayPal, no bank transfer. */
add_filter(
	'woocommerce_available_payment_gateways',
	function ( $gateways ) {
		if ( is_admin() || ! WC()->customer ) {
			return $gateways;
		}
		$country = WC()->customer->get_billing_country();
		if ( empty( $country ) ) {
			$country = 'US';
		}
		if ( 'IN' === $country ) {
			unset( $gateways['ppcp-gateway'], $gateways['paypal'], $gateways['stripe'] );
		} else {
			unset( $gateways['bacs'] );
		}
		return $gateways;
	},
	100
);

/* My Account: profile and support box above the account menu. */
add_action(
	'woocommerce_before_account_navigation',
	function () {
		$user    = wp_get_current_user();
		$pricing = get_page_by_path( 'pricing' );
		$support = nf_link( 'support' );
		$support = ( '' === $support || '#' === $support ) ? 'https://notifima.com/support/' : $support;
		?>
		<div class="enable-customer-support">
			<div class="support-profile">
				<?php echo get_avatar( $user->user_email, 160 ); ?>
				<div class="shape-text">
					<h3><span><?php esc_html_e( 'Hello', 'notifima' ); ?></span> <?php echo esc_html( $user->user_firstname ); ?></h3>
					<p><?php esc_html_e( 'Not', 'notifima' ); ?> <?php echo esc_html( $user->display_name ); ?>?</p>
					<a class="sign-out" href="<?php echo esc_url( wp_logout_url( $pricing ? get_permalink( $pricing ) : home_url( '/' ) ) ); ?>"> ... <?php esc_html_e( 'Sign Out', 'notifima' ); ?></a>
				</div>
			</div>
			<div class="customer-support-img">
				<div class="support-team">
					<p><?php esc_html_e( 'If you need help', 'notifima' ); ?></p>
					<a href="<?php echo esc_url( $support ); ?>" class="ticket"><?php esc_html_e( 'Create Support ticket', 'notifima' ); ?> <span class="arrow-account"></span></a>
					<section>
						<div class="icon-container"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true"><path d="M20,4H4C2.895,4,2,4.895,2,6v12c0,1.105,0.895,2,2,2h16c1.105,0,2-0.895,2-2V6C22,4.895,21.105,4,20,4z M20,8.236l-8,4.882 L4,8.236V6h16V8.236z"></path></svg></div>
						<a href="<?php echo esc_url( 'mailto:contact@notifima.com?Subject=' . rawurlencode( 'Support Request: - ' . $user->display_name . ' - ' ) ); ?>" target="_top"> contact@notifima.com</a>
					</section>
				</div>
			</div>
		</div>
		<?php
	}
);

/* Header mini cart: WooCommerce's scripts keep it fresh (remove item without reload, count badge). */
add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_script( 'wc-cart-fragments' );
		wp_enqueue_script( 'wc-add-to-cart' );
	},
	30
);
add_filter(
	'woocommerce_add_to_cart_fragments',
	function ( $fragments ) {
		$fragments['span.nf-cart-count'] = '<span class="cnt nf-cart-count">' . (int) WC()->cart->get_cart_contents_count() . '</span>';
		return $fragments;
	}
);
