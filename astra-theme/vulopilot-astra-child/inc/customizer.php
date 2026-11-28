<?php
/**
 * Customizer: Appearance → Customize → VuloPilot.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

/** Defaults for every theme option. */
function vp_defaults() {
	return array(
		'vp_use_header'      => true,
		'vp_use_footer'      => true,
		'vp_site_style'      => true,
		'vp_content_offset'  => 110,
		'vp_cta_text'        => 'SuperPower',
		'vp_cta_text_long'   => ' your Website',
		'vp_cta_url'         => '/#pricing',
		'vp_footer_about'    => 'The WordPress Growth Operating System. Find what holds your site back, fix it with your approval, and prove it worked.',
		'vp_footer_consult'  => 'Request a free consultation',
		'vp_footer_left'     => '© 2026 VuloPilot. Your WordPress site, on autopilot.',
		'vp_footer_right'    => 'Product names belong to their owners and are used only to describe how tools differ.',
		'vp_consult_enable'  => true,
		'vp_consult_exit'    => true,
		'vp_consult_email'   => '',
		'vp_consult_endpoint' => '',
		'vp_google_font'     => false,
	);
}

/** Read an option with its default. */
function vp_opt( $key ) {
	$d = vp_defaults();
	return get_theme_mod( $key, isset( $d[ $key ] ) ? $d[ $key ] : '' );
}

add_action(
	'customize_register',
	function ( $wp ) {
		$wp->add_section(
			'vulopilot',
			array(
				'title'       => __( 'VuloPilot', 'vulopilot' ),
				'priority'    => 25,
				'description' => __( 'Header, footer and consultation popup from the VuloPilot design. Menus are edited under Appearance → Menus.', 'vulopilot' ),
			)
		);
		$d      = vp_defaults();
		$fields = array(
			'vp_use_header'       => array( 'checkbox', __( 'Use the VuloPilot mega-menu header (replaces the Astra header)', 'vulopilot' ) ),
			'vp_use_footer'       => array( 'checkbox', __( 'Use the VuloPilot footer (replaces the Astra footer)', 'vulopilot' ) ),
			'vp_site_style'       => array( 'checkbox', __( 'Use VuloPilot background, font and grain on every page', 'vulopilot' ) ),
			'vp_content_offset'   => array( 'number', __( 'Space above content (px) so the floating header does not cover it. Applied to posts, archives and pages that do not start with a full-width section (full-width rows set their own top padding). 0 = never.', 'vulopilot' ) ),
			'vp_cta_text'         => array( 'text', __( 'Header button text', 'vulopilot' ) ),
			'vp_cta_text_long'    => array( 'text', __( 'Header button extra text (wide screens)', 'vulopilot' ) ),
			'vp_cta_url'          => array( 'text', __( 'Header button link', 'vulopilot' ) ),
			'vp_footer_about'     => array( 'textarea', __( 'Footer about text', 'vulopilot' ) ),
			'vp_footer_consult'   => array( 'text', __( 'Footer consultation button text (empty = hide)', 'vulopilot' ) ),
			'vp_footer_left'      => array( 'text', __( 'Footer bottom left text', 'vulopilot' ) ),
			'vp_footer_right'     => array( 'text', __( 'Footer bottom right text', 'vulopilot' ) ),
			'vp_consult_enable'   => array( 'checkbox', __( 'Enable the “Got a question?” consultation popup', 'vulopilot' ) ),
			'vp_consult_exit'     => array( 'checkbox', __( 'Open the popup automatically on exit intent (max once a week)', 'vulopilot' ) ),
			'vp_consult_email'    => array( 'email', __( 'Send consultation requests to (empty = site admin email)', 'vulopilot' ) ),
			'vp_consult_endpoint' => array( 'url', __( 'Optional: POST requests as JSON to this URL instead of email (e.g. a form service)', 'vulopilot' ) ),
			'vp_google_font'      => array( 'checkbox', __( 'Load Plus Jakarta Sans from Google Fonts (turn off if Astra or Kadence already loads it)', 'vulopilot' ) ),
		);
		foreach ( $fields as $id => $f ) {
			$type     = $f[0];
			$sanitize = 'checkbox' === $type ? 'wp_validate_boolean' : ( 'number' === $type ? 'absint' : ( 'email' === $type ? 'sanitize_email' : ( 'url' === $type ? 'esc_url_raw' : ( 'textarea' === $type ? 'sanitize_textarea_field' : 'sanitize_text_field' ) ) ) );
			$wp->add_setting( $id, array( 'default' => $d[ $id ], 'sanitize_callback' => $sanitize ) );
			$wp->add_control( $id, array( 'section' => 'vulopilot', 'label' => $f[1], 'type' => $type ) );
		}

		// Product screenshots: pick each one from the Media Library (the theme no longer ships image files).
		$wp->add_section(
			'vulopilot_screens',
			array(
				'title'       => __( 'VuloPilot screenshots', 'vulopilot' ),
				'priority'    => 26,
				'description' => __( 'Upload each product screenshot to Media and pick it here. Graphics, the product tour and [vp_shot] use these links. Empty = a grey placeholder.', 'vulopilot' ),
			)
		);
		foreach ( vp_screen_rows() as $key => $r ) {
			$wp->add_setting( 'vp_screen_' . $key, array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
			$wp->add_control( new WP_Customize_Image_Control( $wp, 'vp_screen_' . $key, array( 'section' => 'vulopilot_screens', 'label' => $r[2] . ' (' . $key . ')' ) ) );
		}
	}
);
