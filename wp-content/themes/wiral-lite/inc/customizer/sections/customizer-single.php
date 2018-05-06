<?php
/**
 * Single Settings
 *
 * Register Single Settings section, settings and controls for Theme Customizer
 *
 * @package wiral
 */


/**
 * Adds post settings in the Customizer
 *
 * @param object $wp_customize / Customizer Object
 */
function wiral_lite_customize_register_single_options( $wp_customize ) {
		// Add Section for Theme Options
		$wp_customize->add_section( 'wiral_lite_section_single', array(
	        'title'    => esc_html__( 'Single Post Settings', 'wiral-lite' ),
	        'priority' => 40,
			'panel' => 'wiral_lite_options_panel' 
			)
		);

		// Add Settings and Controls for Post length on home & archives
		$wp_customize->add_setting( 'wiral_lite_theme_options[related_posts]', array(
	        'default'           => 'cat',
	        'type'           	=> 'option',
	        'transport'         => 'refresh',
	        'sanitize_callback' => 'wiral_lite_sanitize_select'
			)
		);
	    $wp_customize->add_control( 'wiral_lite_theme_options[related_posts]', array(
	        'label'    => esc_html__( 'Related posts', 'wiral-lite' ),
	        'section'  => 'wiral_lite_section_single',
	        'settings' => 'wiral_lite_theme_options[related_posts]',
	        'type'     => 'radio',
			'priority' => 1,
	        'choices'  => array(
	            'cat' => esc_html__( 'Categories', 'wiral-lite' ),
	            'tag' => esc_html__( 'Tags', 'wiral-lite' )
				)
			)
		);
	}
add_action( 'customize_register', 'wiral_lite_customize_register_single_options' );