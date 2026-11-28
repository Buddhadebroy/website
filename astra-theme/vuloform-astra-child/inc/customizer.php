<?php
/**
 * Customizer: Appearance → Customize → VuloForm.
 *
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;

/** Defaults for every theme option. */
function vf_defaults() {
	return array(
		'vf_use_header'     => true,
		'vf_use_footer'     => true,
		'vf_site_style'     => true,
		'vf_google_font'    => true,
		'vf_smooth_scroll'  => true,
		'vf_content_offset' => 112,
		'vf_base_16'        => true,
		'vf_download_url'   => 'https://github.com/vulolabs/vulolabs/tree/main/plugins/vuloform',
	);
}

/** Read an option with its default. */
function vf_opt( $key ) {
	$d = vf_defaults();
	return get_theme_mod( $key, isset( $d[ $key ] ) ? $d[ $key ] : '' );
}

/** Google Fonts stylesheet for Nunito (the VuloForm font), or '' when turned off. */
function vf_font_url() {
	return vf_opt( 'vf_google_font' ) ? (string) apply_filters( 'vf_font_url', 'https://fonts.googleapis.com/css2?family=Nunito:wght@400..800&display=swap' ) : '';
}

add_action(
	'customize_register',
	function ( $wp ) {
		$wp->add_panel( 'vuloform', array( 'title' => __( 'VuloForm', 'vuloform' ), 'priority' => 25 ) );
		$wp->add_section(
			'vuloform',
			array(
				'title'       => __( 'General', 'vuloform' ),
				'panel'       => 'vuloform',
				'priority'    => 10,
				'description' => __( 'The header and footer are the synced patterns "VuloForm Header" / "VuloForm Footer" (edit them with Kadence: Appearance → VuloForm). Logo and favicon: Site Identity. Header links: Appearance → Menus → "VuloForm header". Colours, sizes and content width: Design tokens.', 'vuloform' ),
			)
		);
		$d      = vf_defaults();
		$fields = array(
			'vf_use_header'     => array( 'checkbox', __( 'Use the VuloForm header (synced pattern "VuloForm Header"). Off = Astra\'s own header.', 'vuloform' ) ),
			'vf_use_footer'     => array( 'checkbox', __( 'Use the VuloForm footer (synced pattern "VuloForm Footer"). Off = Astra\'s own footer.', 'vuloform' ) ),
			'vf_site_style'     => array( 'checkbox', __( 'Use the VuloForm cream background, font and grain on every page', 'vuloform' ) ),
			'vf_google_font'    => array( 'checkbox', __( 'Load Nunito from Google Fonts (turn off if Astra already loads it)', 'vuloform' ) ),
			'vf_smooth_scroll'  => array( 'checkbox', __( 'Smooth scrolling (Lenis, like the original site)', 'vuloform' ) ),
			'vf_content_offset' => array( 'number', __( 'Top space on normal pages under the fixed header (px)', 'vuloform' ) ),
			'vf_base_16'        => array( 'checkbox', __( 'Base size 1rem = 16px like the original site (Astra makes it 18px when the body font is 18px, which makes every VuloForm size 12.5% bigger)', 'vuloform' ) ),
			'vf_download_url'   => array( 'url', __( 'Download link for the patterns ("Download VuloForm" …). Docs = this link + /docs. The header and footer keep the link they were made with; change it there in the Kadence button.', 'vuloform' ) ),
		);
		$sanitize = array(
			'checkbox' => 'wp_validate_boolean',
			'number'   => 'absint',
			'url'      => 'esc_url_raw',
			'text'     => 'sanitize_text_field',
			'textarea' => 'sanitize_textarea_field',
		);
		foreach ( $fields as $id => $f ) {
			$wp->add_setting( $id, array( 'default' => $d[ $id ], 'sanitize_callback' => $sanitize[ $f[0] ] ) );
			$wp->add_control( $id, array( 'type' => $f[0], 'section' => 'vuloform', 'label' => $f[1] ) );
		}
	}
);
