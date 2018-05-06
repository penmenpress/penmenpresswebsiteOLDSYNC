<?php
/**
 * General Settings
 *
 * Register General section, settings and controls for Theme Customizer
 *
 * @package wiral
 */
/**
 * Adds all general settings to the Customizer
 *
 * @param object $wp_customize / Customizer Object
 */
function wiral_lite_customize_register_general_options( $wp_customize ) {
// Add Section for Theme Options
	$wp_customize->add_section( 'wiral_lite_section_general', array(
        'title'    => esc_html__( 'General Settings', 'wiral-lite' ),
        'priority' => 10,
		'panel' => 'wiral_lite_options_panel' 
		)
	);
	
	//Add Settings and Controls for Layout
	$wp_customize->add_setting( 'wiral_lite_theme_options[layout]', array(
        'default'           => 'right-sidebar',
		'type'           	=> 'option',
        'transport'         => 'refresh',
        'sanitize_callback' => 'wiral_lite_sanitize_select'
		)
	);
    $wp_customize->add_control( 'wiral_lite_theme_options[layout]', array(
        'label'    => esc_html__( 'Theme Layout', 'wiral-lite' ),
        'section'  => 'wiral_lite_section_general',
        'settings' => 'wiral_lite_theme_options[layout]',
        'type'     => 'radio',
		'priority' => 1,
        'choices'  => array(
            'left-sidebar' => esc_html__( 'Left Sidebar', 'wiral-lite' ),
            'right-sidebar' => esc_html__( 'Right Sidebar', 'wiral-lite' )
			)
		)
	);

    //Add Sticky Header
    $wp_customize->add_setting( 'wiral_lite_theme_options[sticky_header_title]', array(
        'default'           => '',
        'type'              => 'option',
        'transport'         => 'refresh',
        'sanitize_callback' => 'wiral_lite_sanitize_checkbox'
        )
    );
    $wp_customize->add_control( new wiral_lite_Customize_Header_Control(
        $wp_customize, 'wiral_lite_theme_options[sticky_header_title]', array(
            'label' => esc_html__( 'Sticky Header', 'wiral-lite' ),
            'section' => 'wiral_lite_section_general',
            'settings' => 'wiral_lite_theme_options[sticky_header_title]',
            'priority' => 2
            )
        )
    );
    
     $wp_customize->add_setting( 'wiral_lite_theme_options[sticky_header]', array(
        'default'           => false,
        'type'              => 'option',
        'transport'         => 'refresh',
        'sanitize_callback' => 'wiral_lite_sanitize_checkbox'
        )
    );

    
    $wp_customize->add_control( 'wiral_lite_theme_options[sticky_header]', array(
        'label'    => esc_html__( 'Sticky Header', 'wiral-lite' ),
        'section'  => 'wiral_lite_section_general',
        'settings' => 'wiral_lite_theme_options[sticky_header]',
        'type'     => 'checkbox',
        'priority' => 3
        )
    );

}
add_action( 'customize_register', 'wiral_lite_customize_register_general_options' );