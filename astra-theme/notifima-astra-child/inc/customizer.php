<?php
/**
 * Customizer: Appearance → Customize → Notifima → General.
 *
 * @package Notifima
 */

defined( 'ABSPATH' ) || exit;

/** Defaults for every theme option. */
function nf_defaults() {
	return array(
		'nf_use_header'     => true,
		'nf_use_footer'     => true,
		'nf_load_font'      => true,
		'nf_content_offset' => 0,
		'nf_url_pricing'    => '/pricing/',
		'nf_url_contact'    => '/contact-us/',
		'nf_url_support'    => '#',
		'nf_url_demo'       => '#',
		'nf_url_docs'       => '#',
		'nf_url_cart'       => '#',
		'nf_url_account'    => '#',
		'nf_url_refund'     => '#',
		'nf_url_policy'     => '/support-policy/',
		'nf_contact_form'   => '',
		'nf_wordmark_color' => '#852aff',
		'nf_wordmark_fill'  => false,
		'nf_url_buy_individual' => '#',
		'nf_url_buy_business'   => '#',
		'nf_url_buy_agency'     => '#',
		'nf_url_facebook'   => '#',
		'nf_url_x'          => '#',
		'nf_url_youtube'    => '#',
		'nf_rel_wporg'      => 'woocommerce-product-stock-alert',
		'nf_rel_github'     => '',
	);
}

/** Read an option with its default. */
function nf_opt( $key ) {
	$d = nf_defaults();
	return get_theme_mod( $key, isset( $d[ $key ] ) ? $d[ $key ] : '' );
}

add_action(
	'customize_register',
	function ( $wp ) {
		$wp->add_panel( 'notifima', array( 'title' => __( 'Notifima', 'notifima' ), 'priority' => 25 ) );
		$wp->add_section(
			'notifima',
			array(
				'title'       => __( 'General', 'notifima' ),
				'panel'       => 'notifima',
				'priority'    => 10,
				'description' => __( 'The header and footer are the synced patterns "Site · Header" and "Site · Footer" (edit them with Kadence: Appearance → Notifima). Logo and favicon: Site Identity. Header links and the Resources mega menu: Appearance → Menus → "Notifima header". Colours, sizes and widths: Design tokens. The links below are used when the patterns are created and by the header menu.', 'notifima' ),
			)
		);
		$d      = nf_defaults();
		$fields = array(
			'nf_use_header'     => array( 'checkbox', __( 'Use the Notifima header (synced pattern "Site · Header"). Off = Astra\'s own header.', 'notifima' ) ),
			'nf_use_footer'     => array( 'checkbox', __( 'Use the Notifima footer (synced pattern "Site · Footer"). Off = Astra\'s own footer.', 'notifima' ) ),
			'nf_load_font'      => array( 'checkbox', __( 'Load the Outfit font from the theme (turn off if Astra already loads it)', 'notifima' ) ),
			'nf_content_offset' => array( 'number', __( 'Extra top space on normal Astra pages under the header (px)', 'notifima' ) ),
			'nf_url_pricing'    => array( 'text', __( 'Pricing / buy link', 'notifima' ) ),
			'nf_url_contact'    => array( 'text', __( 'Contact sales link', 'notifima' ) ),
			'nf_url_support'    => array( 'text', __( 'Support link', 'notifima' ) ),
			'nf_url_demo'       => array( 'text', __( 'Live demo link', 'notifima' ) ),
			'nf_url_docs'       => array( 'text', __( 'Documentation link', 'notifima' ) ),
			'nf_url_cart'       => array( 'text', __( 'Cart link (header icon)', 'notifima' ) ),
			'nf_url_account'    => array( 'text', __( 'My account link (header icon)', 'notifima' ) ),
			'nf_url_refund'     => array( 'text', __( 'Refund policy link', 'notifima' ) ),
			'nf_url_policy'     => array( 'text', __( 'Support policy link', 'notifima' ) ),
			'nf_contact_form'   => array( 'text', __( 'Contact Us page: form shortcode, e.g. [wpforms id="123"] (empty = the built-in form, sent by email)', 'notifima' ) ),
			'nf_url_buy_individual' => array( 'text', __( 'Pricing: "Get Individual" checkout link', 'notifima' ) ),
			'nf_url_buy_business'   => array( 'text', __( 'Pricing: "Get Business" checkout link', 'notifima' ) ),
			'nf_url_buy_agency'     => array( 'text', __( 'Pricing: "Get Agency" checkout link', 'notifima' ) ),
			'nf_url_facebook'   => array( 'text', __( 'Footer: Facebook page link', 'notifima' ) ),
			'nf_url_x'          => array( 'text', __( 'Footer: X (Twitter) profile link', 'notifima' ) ),
			'nf_url_youtube'    => array( 'text', __( 'Footer: YouTube channel link', 'notifima' ) ),
			'nf_wordmark_color' => array( 'color', __( 'Footer: big "Notifima" wordmark colour', 'notifima' ) ),
			'nf_wordmark_fill'  => array( 'checkbox', __( 'Footer: fill the wordmark (off = outline only)', 'notifima' ) ),
			'nf_rel_wporg'      => array( 'text', __( 'Release Notes: WordPress.org plugin slug (its readme Changelog is read automatically)', 'notifima' ) ),
			'nf_rel_github'     => array( 'text', __( 'Release Notes: GitHub repository (owner/repo, optional; its Releases are added)', 'notifima' ) ),
		);
		$sanitize = array(
			'checkbox' => 'wp_validate_boolean',
			'number'   => 'absint',
			'text'     => 'sanitize_text_field',
			'color'    => 'sanitize_hex_color',
		);
		foreach ( $fields as $id => $f ) {
			$wp->add_setting( $id, array( 'default' => $d[ $id ], 'sanitize_callback' => $sanitize[ $f[0] ] ) );
			if ( 'color' === $f[0] ) {
				$wp->add_control( new WP_Customize_Color_Control( $wp, $id, array( 'section' => 'notifima', 'label' => $f[1] ) ) );
			} else {
				$wp->add_control( $id, array( 'type' => $f[0], 'section' => 'notifima', 'label' => $f[1] ) );
			}
		}
	}
);

/** Footer wordmark colour (Customize → Notifima → General). */
add_action(
	'wp_enqueue_scripts',
	function () {
		$c = sanitize_hex_color( (string) nf_opt( 'nf_wordmark_color' ) );
		if ( ! $c ) {
			return;
		}
		$css = nf_opt( 'nf_wordmark_fill' )
			? 'color:' . $c . ';-webkit-text-stroke:0'
			: 'color:transparent;-webkit-text-stroke:1px ' . $c;
		wp_add_inline_style( 'nf-blocks', 'html body:not(#nf_):not(#nf2_) .wordmark{' . $css . '}' );
	},
	30
);
