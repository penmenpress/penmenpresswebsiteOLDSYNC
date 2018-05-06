<?php
/**
 * Slideshow
 *
 * Register Slideshow, settings and controls for Theme Customizer
 *
 * @package wiral
 */
/**
 * Adds Slideshow settings to the Customizer
 *
 * @param object $wp_customize / Customizer Object
 */
function wiral_lite_customize_register_slider_options( $wp_customize ) {
// Add Section for Theme Options
	$wp_customize->add_section( 'wiral_lite_section_slider', array(
        'title'    => esc_html__( 'Slider Setting', 'wiral-lite' ),
        'priority' => 30,
		'panel' => 'wiral_lite_options_panel' 
		)
	);

	//Option Disable & Enable Slide Show
	$wp_customize->add_setting( 'wiral_lite_theme_options[slider]', array(
		'default'           => false,
		'type'              => 'option',
		'transport'         => 'refresh',
		'sanitize_callback' => 'wiral_lite_sanitize_checkbox'
		)
	);
	$wp_customize->add_control( 'wiral_lite_theme_options[slider]', array(
		'label'    => esc_html__( 'Feature Slider', 'wiral-lite' ),
		'section'  => 'wiral_lite_section_slider',
		'settings' => 'wiral_lite_theme_options[slider]',
		'type'     => 'checkbox',
		'priority' => 1
		)
	);
	
	
}

add_action( 'customize_register', 'wiral_lite_customize_register_slider_options' );