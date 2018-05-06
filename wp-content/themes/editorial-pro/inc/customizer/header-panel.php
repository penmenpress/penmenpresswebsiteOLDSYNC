<?php
/**
 * Customizer option for Header sections
 *
 * @package Mystery Themes
 * @subpackage Editorial Pro
 * @since 1.0.0
 */

add_action( 'customize_register', 'editorial_pro_header_settings_register' );

function editorial_pro_header_settings_register( $wp_customize ) {
    $wp_customize->remove_section( 'header_image' );

    //Register the custom class for typography
    $wp_customize->register_control_type( 'Editorial_Pro_Typography_Customizer_Control' );

	/**
	 * Add header panels
	 */
	$wp_customize->add_panel(
	    'editorial_pro_header_settings_panel', 
	    array(
	        'priority'       => 10,
	        'capability'     => 'edit_theme_options',
	        'theme_supports' => '',
	        'title'          => __( 'Header Settings', 'editorial-pro' ),
	    ) 
    );
/*----------------------------------------------------------------------------------------------------*/
    /**
     * Header Layout
     */
    $wp_customize->add_section(
        'editorial_pro_header_layout_section',
        array(
            'title'         => __( 'Header Layouts', 'editorial-pro' ),
            'priority'      => 5,
            'panel'         => 'editorial_pro_header_settings_panel'
        )
    );

    /** 
     * Header layouts
     */
    $wp_customize->add_setting(
        'editorial_pro_header_layout',
        array(
            'default' =>'header_layout_1',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_key',
        )
    );
    $wp_customize->add_control( new Editorial_Customize_Control_Radio_Image(
        $wp_customize,
        'editorial_pro_header_layout',
            array(
                'label'    => esc_html__( 'Available Layouts', 'editorial-pro' ),
                'description' => esc_html__( 'Select header layout from available layouts.', 'editorial-pro' ),
                'section'  => 'editorial_pro_header_layout_section',
                'choices'  => array(
                        'header_layout_1' => array(
                            'label' => esc_html__( 'Layout 1', 'editorial-pro' ),
                            'url'   => '%s/assets/images/header-layout-1.png'
                        ),
                        'header_layout_2' => array(
                            'label' => esc_html__( 'Layout 2', 'editorial-pro' ),
                            'url'   => '%s/assets/images/header-layout-2.png'
                        ),
                        'header_layout_3' => array(
                            'label' => esc_html__( 'Layout 3', 'editorial-pro' ),
                            'url'   => '%s/assets/images/header-layout-3.png'
                        ),
                        'header_layout_4' => array(
                            'label' => esc_html__( 'Layout 4', 'editorial-pro' ),
                            'url'   => '%s/assets/images/header-layout-4.png'
                        )
                ),
                'priority' => 5
            )
        )
    );

/*----------------------------------------------------------------------------------------------------*/
    /**
     * Top Header Section
     */
    $wp_customize->add_section(
        'editorial_pro_top_header_section',
        array(
            'title'         => __( 'Top Header Section', 'editorial-pro' ),
            'priority'      => 10,
            'panel'         => 'editorial_pro_header_settings_panel'
        )
    );

    /**
     * Top header option
     */
    $wp_customize->add_setting(
        'editorial_pro_top_header_option',
        array(
            'default' => 'enable',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'editorial_pro_enable_switch_sanitize'
        )
    );
    $wp_customize->add_control( new Editorial_Customize_Switch_Control(
        $wp_customize,
            'editorial_pro_top_header_option', 
            array(
                'type' => 'switch',
                'label' => __( 'Top Header Option', 'editorial-pro' ),
                'description' => __( 'Enable/disable top header section.', 'editorial-pro' ),
                'priority'      => 5,
                'section' => 'editorial_pro_top_header_section',
                'choices' => array(
                    'enable' => __( 'Enable', 'editorial-pro' ),
                    'disable' => __( 'Disable', 'editorial-pro' )
                )
            )
        )
    );

    //removed background from top header
    $wp_customize->add_setting(
        'top_header_bg_option', 
        array(
          'default' => false,
          'capability' => 'edit_theme_options',
          'sanitize_callback' => 'editorial_pro_sanitize_checkbox',
        )
    );
    $wp_customize->add_control(
        'top_header_bg_option', 
        array(
          'type' => 'checkbox',
          'label' => __( 'Checked to hide top header background.', 'editorial-pro' ),
          'section' => 'editorial_pro_top_header_section',
          'priority' => 10
        )
    );

    // Display Current Date
    $wp_customize->add_setting(
        'editorial_pro_header_date', 
        array(
			'default' => 'enable',
			'capability' => 'edit_theme_options',
            'transport' => 'postMessage',
			'sanitize_callback' => 'editorial_pro_enable_switch_sanitize'
        )
    );
    $wp_customize->add_control( new Editorial_Customize_Switch_Control(
        $wp_customize,
            'editorial_pro_header_date', 
            array(
    			'type' => 'switch',
    			'label' => __( 'Current Date Option', 'editorial-pro' ),
    			'description' => __( 'Enable/disable current date from top header.', 'editorial-pro' ),
                'priority'      => 15,
    			'section' => 'editorial_pro_top_header_section',
                'choices' => array(
                    'enable' => __( 'Enable', 'editorial-pro' ),
                    'disable' => __( 'Disable', 'editorial-pro' )
                )
    		)
        )
    );

    //removed background from top header
    $wp_customize->add_setting(
        'top_header_date_icon_option', 
        array(
          'default' => false,
          'capability' => 'edit_theme_options',
          'sanitize_callback' => 'editorial_pro_sanitize_checkbox',
        )
    );
    $wp_customize->add_control(
        'top_header_date_icon_option', 
        array(
          'type' => 'checkbox',
          'label' => __( 'Checked to hide top header date icon.', 'editorial-pro' ),
          'section' => 'editorial_pro_top_header_section',
          'priority' => 20
        )
    );

    // Option about top header social icons
    $wp_customize->add_setting(
        'editorial_pro_header_social_option', 
        array(
            'default' => 'enable',
            'capability' => 'edit_theme_options',
            'transport' => 'postMessage',
            'sanitize_callback' => 'editorial_pro_enable_switch_sanitize'
        )
    );
    $wp_customize->add_control( new Editorial_Customize_Switch_Control(
        $wp_customize,
            'editorial_pro_header_social_option', 
            array(
                'type' => 'switch',
                'label' => __( 'Social Icon Option', 'editorial-pro' ),
                'description' => __( 'Enable/disable social icons from top header (right).', 'editorial-pro' ),
                'priority'      => 35,
                'section' => 'editorial_pro_top_header_section',
                'choices' => array(
                    'enable' => __( 'Enable', 'editorial-pro' ),
                    'disable' => __( 'Disable', 'editorial-pro' )
                )
            )
        )
    );
/*----------------------------------------------------------------------------------------------------*/
    /**
     * Main Menu Settings
     */
    $wp_customize->add_section(
        'editorial_pro_main_menu_section',
        array(
            'title'         => __( 'Main Menu Section', 'editorial-pro' ),
            'priority'      => 15,
            'panel'         => 'editorial_pro_header_settings_panel'
        )
    );

    //Sticky header option
    $wp_customize->add_setting(
        'editorial_pro_sticky_option', 
        array(
            'default' => 'enable',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'editorial_pro_enable_switch_sanitize'
        )
    );
    $wp_customize->add_control( new Editorial_Customize_Switch_Control(
        $wp_customize,
            'editorial_pro_sticky_option', 
            array(
                'type' => 'switch',
                'label' => __( 'Menu Sticky', 'editorial-pro' ),
                'description' => __( 'Enable/disable option for Menu Sticky', 'editorial-pro' ),
                'priority'      => 5,
                'section' => 'editorial_pro_main_menu_section',
                'choices' => array(
                    'enable' => __( 'Enable', 'editorial-pro' ),
                    'disable' => __( 'Disable', 'editorial-pro' )
                )
            )
        )
    );

    /** 
     * home icon option
     */
    $wp_customize->add_setting(
        'editorial_pro_home_icon_option', 
        array(
            'default' => 'enable',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'editorial_pro_enable_switch_sanitize'
        )
    );
    $wp_customize->add_control( new Editorial_Customize_Switch_Control(
        $wp_customize,
            'editorial_pro_home_icon_option', 
            array(
                'type' => 'switch',
                'label' => __( 'Home Icon Option', 'editorial-pro' ),
                'description' => __( 'Enable/disable option for home icon at primary menu.', 'editorial-pro' ),
                'priority'      => 10,
                'section' => 'editorial_pro_main_menu_section',
                'choices' => array(
                    'enable' => __( 'Enable', 'editorial-pro' ),
                    'disable' => __( 'Disable', 'editorial-pro' )
                )
            )
        )
    );

    /**
     * Settings for main menu typography 
     */
    $wp_customize->add_setting( 'menu_font_family', array( 'default' => 'Titillium Web', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
    $wp_customize->add_setting( 'menu_font_style', array( 'default' => '400', 'sanitize_callback' => 'sanitize_key', 'transport' => 'postMessage' ) );
    $wp_customize->add_setting( 'menu_text_decoration', array( 'default' => 'none', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
    $wp_customize->add_setting( 'menu_text_transform', array( 'default' => 'none', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
    $wp_customize->add_setting( 'menu_font_size', array( 'default' => '14', 'sanitize_callback' => 'absint', 'transport' => 'postMessage' ) );
    $wp_customize->add_setting( 'menu_line_height', array( 'default' => '38', 'sanitize_callback' => 'editorial_pro_sanitize_number', 'transport' => 'postMessage' ) );
    $wp_customize->add_setting( 'menu_font_color', array( 'default' => '#ffffff', 'sanitize_callback' => 'sanitize_hex_color', 'transport' => 'postMessage' ) );

    /**
     * Controls for menu typography 
     */
    $wp_customize->add_control( new Editorial_Pro_Typography_Customizer_Control (
        $wp_customize,
            'menu_typography',
            array(
                'label'       => esc_html__( 'Menu Typography', 'editorial-pro' ),
                'description' => __( 'Select how you want your main menu to appear.', 'editorial-pro' ),
                'section'     => 'editorial_pro_main_menu_section',
                'priority'      => 15,
                'settings'    => array(
                    'family'      => 'menu_font_family',
                    'style'       => 'menu_font_style',
                    'text_decoration' => 'menu_text_decoration',
                    'text_transform' => 'menu_text_transform',
                    'size'        => 'menu_font_size',
                    'px_line_height' => 'menu_line_height',
                    'typocolor'  => 'menu_font_color'
                ),
                // Pass custom labels. Use the setting key (above) for the specific label.
                'l10n'        => array(),
            )
        )
    );

    /** 
     * Main Menu Background Color
     */
    $wp_customize->add_setting(
        'editorial_pro_main_menu_bg_color',
        array(
            'default'           => '#333333',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'editorial_pro_main_menu_bg_color',
            array(
                'label'         => __( 'Main Menu Background Color', 'editorial-pro' ),
                'section'       => 'editorial_pro_main_menu_section',
                'priority'      => 20
            )
        )
    );

    /** 
     * Menu hover Color
     */
    $wp_customize->add_setting(
        'editorial_pro_menu_hover_color',
        array(
            'default'           => '#32B3D3',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'editorial_pro_menu_hover_color',
            array(
                'label'         => __( 'Menu Hover Color', 'editorial-pro' ),
                'section'       => 'editorial_pro_main_menu_section',
                'priority'      => 25
            )
        )
    );

/*----------------------------------------------------------------------------------------------------*/
    /**
     * News Ticker section
     */
    $wp_customize->add_section(
        'editorial_pro_news_ticker_section',
        array(
            'title'         => __( 'News Ticker Section', 'editorial-pro' ),
            'priority'      => 20,
            'panel'         => 'editorial_pro_header_settings_panel'
        )
    );

    //Ticker display option
    $wp_customize->add_setting(
        'editorial_pro_ticker_option', 
        array(
            'default' => 'enable',
            'capability' => 'edit_theme_options',
            'transport' => 'postMessage',
            'sanitize_callback' => 'editorial_pro_enable_switch_sanitize'
        )
    );
    $wp_customize->add_control( new Editorial_Customize_Switch_Control(
        $wp_customize,
            'editorial_pro_ticker_option', 
            array(
                'type' => 'switch',
                'label' => __( 'News Ticker Option', 'editorial-pro' ),
                'description' => __( 'Enable/disable news ticker at header.', 'editorial-pro' ),
                'priority'      => 4,
                'section' => 'editorial_pro_news_ticker_section',
                'choices' => array(
                    'enable' => __( 'Enable', 'editorial-pro' ),
                    'disable' => __( 'Disable', 'editorial-pro' )
                )
            )
        )
    );

    //Ticker Caption
    $wp_customize->add_setting(
        'editorial_pro_ticker_caption', 
        array(
              'default' => __( 'Latest', 'editorial-pro' ),
              'capability' => 'edit_theme_options',
              'transport'=> 'postMessage',
              'sanitize_callback' => 'editorial_pro_sanitize_text',
            )
    );
    $wp_customize->add_control(
        'editorial_pro_ticker_caption', 
        array(
              'type'     => 'text',
              'label'    => __( 'News Ticker Caption', 'editorial-pro' ),
              'section'  => 'editorial_pro_news_ticker_section',
              'priority' => 5
            )
    );
    $wp_customize->selective_refresh->add_partial( 
        'editorial_pro_ticker_caption', 
            array(
                'selector' => '.ticker-caption',
                'render_callback' => 'editorial_pro_customize_partial_ticker_caption',
            )
    );

    /**
     * Ticker count
     */
    $wp_customize->add_setting(
        'editorial_pro_ticker_post_count', 
        array(
              'default' => 5,
              'capability' => 'edit_theme_options',
              'sanitize_callback' => 'editorial_pro_sanitize_number',
            )
    );
    $wp_customize->add_control(
        'editorial_pro_ticker_post_count', 
        array(
              'type'     => 'number',
              'label'    => __( 'News Ticker Post Count', 'editorial-pro' ),
              'section'  => 'editorial_pro_news_ticker_section',
              'priority' => 10
            )
    );

    /** 
     * Ticker layouts
     */
    $wp_customize->add_setting(
        'editorial_pro_ticker_layout',
        array(
            'default' =>'ticker_layout_1',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_key',
        )
    );
    $wp_customize->add_control( new Editorial_Customize_Control_Radio_Image(
        $wp_customize,
        'editorial_pro_ticker_layout',
            array(
                'label'    => esc_html__( 'Available Layouts', 'editorial-pro' ),
                'description' => esc_html__( 'Select ticker layout from available layouts.', 'editorial-pro' ),
                'section'  => 'editorial_pro_news_ticker_section',
                'choices'  => array(
                        'ticker_layout_1' => array(
                            'label' => esc_html__( 'Layout 1', 'editorial-pro' ),
                            'url'   => '%s/assets/images/news-ticket-1.png'
                        ),
                        'ticker_layout_2' => array(
                            'label' => esc_html__( 'Layout 2', 'editorial-pro' ),
                            'url'   => '%s/assets/images/news-ticket-2.png'
                        )
                ),
                'priority' => 15
            )
        )
    );
}