<?php
/**
 * Customize → VuloMail.
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'customize_register',
	function ( $wp_customize ) {
		$wp_customize->add_panel( 'vm_panel', array( 'title' => __( 'VuloMail', 'vulomail' ), 'priority' => 25 ) );
		$wp_customize->add_section( 'vm_general', array( 'title' => __( 'Header, footer & behaviour', 'vulomail' ), 'panel' => 'vm_panel', 'priority' => 10 ) );
		$wp_customize->add_section( 'vm_links', array( 'title' => __( 'Links', 'vulomail' ), 'panel' => 'vm_panel', 'priority' => 20 ) );

		$d    = vm_defaults();
		$bool = function ( $key, $label, $section = 'vm_general', $desc = '' ) use ( $wp_customize, $d ) {
			$wp_customize->add_setting( 'vm_' . $key, array( 'default' => $d[ $key ], 'sanitize_callback' => 'rest_sanitize_boolean' ) );
			$wp_customize->add_control( 'vm_' . $key, array( 'type' => 'checkbox', 'section' => $section, 'label' => $label, 'description' => $desc ) );
		};
		$text = function ( $key, $label, $section = 'vm_links', $desc = '' ) use ( $wp_customize, $d ) {
			$wp_customize->add_setting( 'vm_' . $key, array( 'default' => $d[ $key ], 'sanitize_callback' => 'sanitize_text_field' ) );
			$wp_customize->add_control( 'vm_' . $key, array( 'type' => 'text', 'section' => $section, 'label' => $label, 'description' => $desc ) );
		};

		$bool( 'use_header', __( 'Use the VuloMail header (Kadence, edited in Appearance → VuloMail)', 'vulomail' ), 'vm_general', __( 'Off: Astra\'s own header is shown.', 'vulomail' ) );
		$bool( 'use_footer', __( 'Use the VuloMail footer', 'vulomail' ), 'vm_general', __( 'Off: Astra\'s own footer is shown.', 'vulomail' ) );
		$bool( 'show_title', __( 'Show the site title next to the logo', 'vulomail' ) );
		$text( 'title_accent', __( 'Coloured part of the site title', 'vulomail' ), 'vm_general', __( 'Shown in blue, like "Mail" in VuloMail. Leave empty for none.', 'vulomail' ) );
		$bool( 'blog_templates', __( 'Use the VuloMail blog index and single post templates', 'vulomail' ) );
		$bool( 'smooth_scroll', __( 'Smooth scrolling (Lenis, like the original)', 'vulomail' ) );
		$bool( 'scroll_progress', __( 'Airmail scroll-progress bar at the top', 'vulomail' ) );
		$bool( 'header_hide', __( 'Hide the header while scrolling down (like the original site). Off: the header always stays at the top.', 'vulomail' ) );
		$bool( 'use_astra_width', __( 'Content width from Astra (Global → Container → Container Width)', 'vulomail' ), 'vm_general', __( 'Off: the Design token --vm-container-7xl (80rem, the original) is used for header, footer and every row.', 'vulomail' ) );

		$wp_customize->add_setting( 'vm_font_loading', array( 'default' => $d['font_loading'], 'sanitize_callback' => 'sanitize_key' ) );
		$wp_customize->add_control(
			'vm_font_loading',
			array(
				'type'    => 'radio',
				'section' => 'vm_general',
				'label'   => __( 'Fonts (Nunito + Geist Mono)', 'vulomail' ),
				'choices' => array(
					'google' => __( 'Load from Google Fonts', 'vulomail' ),
					'none'   => __( 'Don\'t load (a plugin or the host provides them)', 'vulomail' ),
				),
			)
		);

		$text( 'cta_url', __( 'Get started / download link', 'vulomail' ) );
		$text( 'docs_url', __( 'Documentation link', 'vulomail' ) );
		$text( 'repo_url', __( 'Plugin on GitHub link', 'vulomail' ) );
		$text( 'contact_email', __( 'Contact form email', 'vulomail' ), 'vm_links', __( 'Where contact form messages go. Empty: the site admin email.', 'vulomail' ) );
	}
);
