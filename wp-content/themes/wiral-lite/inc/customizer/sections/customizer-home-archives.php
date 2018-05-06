<?php
/**
 * Archive Settings
 *
 * Register Home & Archive Settings section, settings and controls for Theme Customizer
 *
 * @package wiral
 */


/**
 * Adds post settings in the Customizer
 *
 * @param object $wp_customize / Customizer Object
 */
function wiral_lite_customize_register_archive_options( $wp_customize ) {

	// Add Sections for Post Settings
	$wp_customize->add_section( 'wiral_lite_section_home_archive', array(
        'title'    => esc_html__( 'Home & Archive Settings', 'wiral-lite' ),
        'priority' => 20,
		'panel' => 'wiral_lite_options_panel' 
		)
	);
	
	// Add Settings and Controls for Post length on home & archives
	$wp_customize->add_setting( 'wiral_lite_theme_options[post_content]', array(
        'default'           => 'excerpt',
        'type'           	=> 'option',
        'transport'         => 'refresh',
        'sanitize_callback' => 'wiral_lite_sanitize_select'
		)
	);

	// Add Setting and Control for Excerpt Length
	$wp_customize->add_setting( 'wiral_lite_theme_options[excerpt_length]', array(
        'default'           => 20,
		'type'           	=> 'option',
		'transport'         => 'refresh',
        'sanitize_callback' => 'absint'
		)
	);
    $wp_customize->add_control( 'wiral_lite_theme_options[excerpt_length]', array(
        'label'    => esc_html__( 'Excerpt Length', 'wiral-lite' ),
        'section'  => 'wiral_lite_section_home_archive',
        'settings' => 'wiral_lite_theme_options[excerpt_length]',
        'type'     => 'text',
		'active_callback' => 'wiral_lite_control_post_content_callback',
		'priority' => 2
		)
	);
	

	// Add Setting and Control for Excerpt More Text
	$wp_customize->add_setting( 'wiral_lite_theme_options[excerpt_more]', array(
        'default'           => '[...]',
		'type'           	=> 'option',
		'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_text_field'
		)
	);

    $wp_customize->add_control( 'wiral_lite_theme_options[excerpt_more]', array(
        'label'    => esc_html__( 'Excerpt More Text', 'wiral-lite' ),
        'section'  => 'wiral_lite_section_home_archive',
        'settings' => 'wiral_lite_theme_options[excerpt_more]',
        'type'     => 'text',
        'active_callback' => 'wiral_lite_control_post_content_callback',
		'priority' => 3
		)
	);
	
	

    // Add Settings and Controls for Pagination
    $wp_customize->add_setting( 'wiral_lite_theme_options[paging]', array(
        'default'           => 'pageing-default',
        'type'              => 'option',
        'transport'         => 'refresh',
        'sanitize_callback' => 'wiral_lite_sanitize_select'
        )
    );
    $wp_customize->add_control( 'wiral_lite_theme_options[paging]', array(
        'label'    => esc_html__( 'Pagination Type', 'wiral-lite' ),
        'section'  => 'wiral_lite_section_home_archive',
        'settings' => 'wiral_lite_theme_options[paging]',
        'type'     => 'radio',
        'priority' => 4,
        'choices'  => array(
            'pageing-default' => esc_html__( ' Default (Older Posts/Newer Posts)', 'wiral-lite' ),
            'pageing-numberal' => esc_html__( 'Numberal (1 2 3 ..)', 'wiral-lite' )
            )
        )
    );
  
	
	
}
add_action( 'customize_register', 'wiral_lite_customize_register_archive_options' );