<?php
/**
 * SelfScan Theme Customizer
 *
 * @package SelfScan
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function selfscan_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'selfscan_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'selfscan_customize_partial_blogdescription',
			)
		);
	}

	// Remove unused default sections
	$wp_customize->remove_section('colors');
	$wp_customize->remove_section('header_image');
	$wp_customize->remove_section('background_image');
	$wp_customize->remove_section('widgets');

	// Logo Settings Section
	$wp_customize->add_section(
		'selfscan_logo_section',
		array(
			'title'      => __( 'Logo Settings', 'selfscan' ),
			'priority'   => 30,
		)
	);

	// Dark Logo (for header)
	$wp_customize->add_setting(
		'selfscan_logo_dark',
		array(
			'default'           => get_template_directory_uri() . '/img/main/logo-black.svg',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'selfscan_logo_dark',
			array(
				'label'    => __( 'Dark Logo (Header)', 'selfscan' ),
				'section'  => 'selfscan_logo_section',
				'settings' => 'selfscan_logo_dark',
				'description' => __( 'Upload a dark version of your logo for use in the header.', 'selfscan' ),
			)
		)
	);

	// Light Logo (for footer)
	$wp_customize->add_setting(
		'selfscan_logo_light',
		array(
			'default'           => get_template_directory_uri() . '/img/main/logo-white.svg',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'selfscan_logo_light',
			array(
				'label'    => __( 'Light Logo (Footer)', 'selfscan' ),
				'section'  => 'selfscan_logo_section',
				'settings' => 'selfscan_logo_light',
				'description' => __( 'Upload a light version of your logo for use in the footer.', 'selfscan' ),
			)
		)
	);

	// Footer Settings Section
	$wp_customize->add_section(
		'selfscan_footer_section',
		array(
			'title'      => __( 'Footer Settings', 'selfscan' ),
			'priority'   => 120,
		)
	);

	// Footer Text
	$wp_customize->add_setting(
		'footer_text',
		array(
			'default'           => 'SelfScan.ca is the easiest, quickest, and most affordable way for Canadians to obtain their Name-Based RCMP Criminal Record Check. Whether it\'s required for employment, school, or volunteering, don\'t waste your time standing in line and filling out paperwork at the police station or post office, obtain your official signed police certificate within the comfort of your home.',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'footer_text',
		array(
			'label'    => __( 'Footer Text', 'selfscan' ),
			'section'  => 'selfscan_footer_section',
			'settings' => 'footer_text',
			'type'     => 'textarea',
		)
	);

	// Copyright Text
	$wp_customize->add_setting(
		'copyright_text',
		array(
			'default'           => '© ' . get_bloginfo('name') . ' ' . date('Y'),
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'copyright_text',
		array(
			'label'    => __( 'Copyright Text', 'selfscan' ),
			'section'  => 'selfscan_footer_section',
			'settings' => 'copyright_text',
			'type'     => 'text',
		)
	);

	// Footer Partners Images
	$wp_customize->add_setting(
		'footer_partner_1',
		array(
			'default'           => '',
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'footer_partner_1',
			array(
				'label'     => __( 'Footer Partner 1', 'selfscan' ),
				'section'   => 'selfscan_footer_section',
				'settings'  => 'footer_partner_1',
				'mime_type' => 'image',
			)
		)
	);

	$wp_customize->add_setting(
		'footer_partner_2',
		array(
			'default'           => '',
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'footer_partner_2',
			array(
				'label'     => __( 'Footer Partner 2', 'selfscan' ),
				'section'   => 'selfscan_footer_section',
				'settings'  => 'footer_partner_2',
				'mime_type' => 'image',
			)
		)
	);
}
add_action( 'customize_register', 'selfscan_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function selfscan_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function selfscan_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function selfscan_customize_preview_js() {
	wp_enqueue_script( 'selfscan-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), _S_VERSION, true );
}
add_action( 'customize_preview_init', 'selfscan_customize_preview_js' );
