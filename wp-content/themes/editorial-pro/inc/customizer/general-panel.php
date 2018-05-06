<?php
/**
 * Customizer settings for General purpose
 *
 * @package Mystery Themes
 * @subpackage Editorial Pro
 * @since 1.0.0
 */

add_action( 'customize_register', 'editorial_pro_general_settings_register' );

function editorial_pro_general_settings_register( $wp_customize ) {

	$wp_customize->get_section( 'title_tagline' )->panel = 'editorial_pro_general_settings_panel';
    $wp_customize->get_section( 'title_tagline' )->priority = '3';
    $wp_customize->get_section( 'colors' )->panel = 'editorial_pro_general_settings_panel';
    $wp_customize->get_section( 'colors' )->priority = '4';
    $wp_customize->get_section( 'background_image' )->panel = 'editorial_pro_general_settings_panel';
    $wp_customize->get_section( 'background_image' )->priority = '5';
    $wp_customize->get_section( 'static_front_page' )->panel = 'editorial_pro_general_settings_panel';
    $wp_customize->get_section( 'static_front_page' )->priority = '6';

    /**
     * Add General Settings Panel 
     */
    $wp_customize->add_panel( 
        'editorial_pro_general_settings_panel', 
        array(
            'priority'       => 5,
            'capability'     => 'edit_theme_options',
            'theme_supports' => '',
            'title'          => __( 'General Settings', 'editorial-pro' ),
            ) 
        );

/*---------------------------------------------------------------------------------------------------------------*/
    /**
     * Theme color
     */
    $wp_customize->add_setting(
        'editorial_pro_theme_color',
        array(
            'default'           => '#32B3D3',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'editorial_pro_theme_color',
            array(
                'label'         => __( 'Theme color', 'editorial-pro' ),
                'section'       => 'colors',
                'priority'      => 5
            )
        )
    );

/*---------------------------------------------------------------------------------------------------------------*/
    /**
     * Website layout
     */
    $wp_customize->add_section(
        'editorial_pro_site_layout',
        array(
            'title'         => __( 'Website Layout', 'editorial-pro' ),
            'description'   => __( 'Choose site layout which shows your website more effective.', 'editorial-pro' ),
            'priority'      => 5,
            'panel'         => 'editorial_pro_general_settings_panel',
        )
    );

    /**
     * Pre loaders option
     */
    $wp_customize->add_setting(
        'site_pre_loader_option', 
        array(
            'default'       => 'show',
            'capability'    => 'edit_theme_options',
            'sanitize_callback' => 'editorial_pro_show_switch_sanitize'
        )
    );
    $wp_customize->add_control( new Editorial_Customize_Switch_Control(
        $wp_customize,
            'site_pre_loader_option', 
            array(
                'type'          => 'switch',
                'label'         => __( 'Pre Loader Option', 'editorial-pro' ),
                'description'   => __( 'Show/hide pre loaders from site.', 'editorial-pro' ),
                'priority'      => 5,
                'section'       => 'editorial_pro_site_layout',
                'choices'       => array(
                    'show'          => __( 'Show', 'editorial-pro' ),
                    'hide'          => __( 'Hide', 'editorial-pro' )
                )
            )
        )
    );

    /**
     * Pre loaders layouts
     */
    $wp_customize->add_setting(
        'site_pre_loader',
        array(
            'default'           => 'three_balls',
            'sanitize_callback' => 'editorial_pro_sanitize_pre_loaders',
        )       
    );
    $wp_customize->add_control(
        'site_pre_loader',
        array(
            'type' => 'select',
            'priority'    => 10,
            'label' => __( 'Pre loaders', 'editorial-pro' ),
            'section' => 'editorial_pro_site_layout',
            'choices' => array(
                'three_balls'       => __( '3 Balls', 'editorial-pro' ),
                'rectangles'        => __( 'Rectangles', 'editorial-pro' ),
                'steps'             => __( 'Steps', 'editorial-pro' ),
                'spinning_border'   => __( 'Spinning Border', 'editorial-pro' ),
                'single_bleep'      => __( 'Single Bleep', 'editorial-pro' ),
                'square'            => __( 'Square', 'editorial-pro' ),
                'hollow_circle'     => __( 'Hollow Circle', 'editorial-pro' ),
                'knight_rider'      => __( 'Knight Rider', 'editorial-pro' )
            ),
            'active_callback' => 'editorial_pro_pre_loader_callback'
        )
    );
    
    /**
     * Website layout
     */
    $wp_customize->add_setting(
        'site_layout_option',
        array(
            'default'           => 'fullwidth_layout',
            'sanitize_callback' => 'editorial_pro_sanitize_site_layout',
        )       
    );
    $wp_customize->add_control(
        'site_layout_option',
        array(
            'type'          => 'radio',
            'priority'      => 15,
            'label'         => __( 'Site Layout', 'editorial-pro' ),
            'section'       => 'editorial_pro_site_layout',
            'choices'       => array(
                'fullwidth_layout'  => __( 'FullWidth Layout', 'editorial-pro' ),
                'boxed_layout'      => __( 'Boxed Layout', 'editorial-pro' )
            ),
        )
    );
}