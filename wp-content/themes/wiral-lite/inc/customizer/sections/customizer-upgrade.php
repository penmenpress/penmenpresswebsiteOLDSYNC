<?php
/**
 * Pro Version Upgrade Section
 *
 * Registers Upgrade Section for the Pro Version of the theme
 *
 * @package wiral
 */

/**
 * Adds pro version description
 *
 * @param object $wp_customize / Customizer Object
 */
function wiral_lite_customize_register_upgrade_options( $wp_customize ) {

	// Add Upgrade / More Features Section
	$wp_customize->add_section( 'wiral_lite_section_upgrade', array(
        'title'    => esc_html__( 'More Features', 'wiral-lite' ),
        'priority' => 70,
		'panel' => 'wiral_lite_options_panel' 
		)
	);
	
	// Add custom Upgrade Content control
	$wp_customize->add_setting( 'wiral_lite_theme_options[upgrade]', array(
        'default'           => '',
		'type'           	=> 'option',
        'transport'         => 'refresh',
        'sanitize_callback' => 'wiral_lite_sanitize_select'
        )
    );
    $wp_customize->add_control( new wiral_lite_Customize_Upgrade_Control(
        $wp_customize, 'wiral_lite_theme_options[upgrade]', array(
            'section' => 'wiral_lite_section_upgrade',
            'settings' => 'wiral_lite_theme_options[upgrade]',
            'priority' => 1
            )
        )
    );

}
add_action( 'customize_register', 'wiral_lite_customize_register_upgrade_options' );