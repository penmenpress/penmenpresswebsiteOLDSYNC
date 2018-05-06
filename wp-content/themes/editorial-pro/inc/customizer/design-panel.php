<?php
/**
 * Customizer option for Design Settings
 *
 * @package Mystery Themes
 * @subpackage Editorial Pro
 * @since 1.0.0
 */

add_action( 'customize_register', 'editorial_pro_design_settings_register' );

function editorial_pro_design_settings_register( $wp_customize ) {

    // Register the radio image control class as a JS control type.
    $wp_customize->register_control_type( 'Editorial_Customize_Control_Radio_Image' );


    /**
     * Add Design Panel
     */
    $wp_customize->add_panel(
	    'editorial_pro_design_settings_panel', 
	    array(
	        'priority'       => 15,
	        'capability'     => 'edit_theme_options',
	        'theme_supports' => '',
	        'title'          => __( 'Design Settings', 'editorial-pro' ),
	    ) 
    );

/*--------------------------------------------------------------------------------*/
	/**
	 * Archive page Settings
	 */
	$wp_customize->add_section(
        'editorial_pro_archive_section',
        array(
            'title'         => __( 'Archive Settings', 'editorial-pro' ),
            'priority'      => 10,
            'panel'         => 'editorial_pro_design_settings_panel'
        )
    );

    /**
     * Archive page sidebar
     */
    $wp_customize->add_setting(
        'editorial_pro_archive_sidebar',
        array(
            'default' =>'right_sidebar',
            'sanitize_callback' => 'sanitize_key',
        )
    );
    $wp_customize->add_control( new Editorial_Customize_Control_Radio_Image(
        $wp_customize,
        'editorial_pro_archive_sidebar',
            array(
                'label'    => esc_html__( 'Available Sidebars', 'editorial-pro' ),
                'description' => esc_html__( 'Choose sidebar from available options.', 'editorial-pro' ),
                'section'  => 'editorial_pro_archive_section',
                'choices'  => array(
                        'left_sidebar' => array(
                            'label' => esc_html__( 'Left Sidebar', 'editorial-pro' ),
                            'url'   => '%s/assets/images/left-sidebar.png'
                        ),
                        'right_sidebar' => array(
                            'label' => esc_html__( 'Right Sidebar', 'editorial-pro' ),
                            'url'   => '%s/assets/images/right-sidebar.png'
                        ),
                        'no_sidebar' => array(
                            'label' => esc_html__( 'No Sidebar', 'editorial-pro' ),
                            'url'   => '%s/assets/images/no-sidebar.png'
                        ),
                        'no_sidebar_center' => array(
                            'label' => esc_html__( 'No Sidebar Center', 'editorial-pro' ),
                            'url'   => '%s/assets/images/no-sidebar-center.png'
                        )
                ),
                'priority' => 5
            )
        )
    );

    /** 
     * Archive page layouts
     */
    $wp_customize->add_setting(
        'editorial_pro_archive_layout',
        array(
            'default'           => 'classic',
            'sanitize_callback' => 'editorial_pro_sanitize_archive_layout',
        )
    );
    $wp_customize->add_control(
        'editorial_pro_archive_layout',
        array(
            'type'        => 'radio',
            'label'       => __( 'Archive Page Layout', 'editorial-pro' ),
            'description' => __( 'Choose available layout for all archive pages.', 'editorial-pro' ),
            'section'     => 'editorial_pro_archive_section',
            'choices' => array(
                'classic'   => __( 'Classic Layout', 'editorial-pro' ),
                'columns'   => __( 'Columns Layout', 'editorial-pro' ),
                'grid'      => __( 'Classic with Grids Layout', 'editorial-pro' ),
                'list'      => __( 'List layout', 'editorial-pro' )
            ),
            'priority'  => 10
        )
    );

    /** 
     * Archive excerpt length
     */
    $wp_customize->add_setting(
        'archive_excerpt_length',
        array(
            'default'           => 70,
            'sanitize_callback' => 'editorial_pro_sanitize_number',
        )
    );
    $wp_customize->add_control(
        'archive_excerpt_length',
        array(
            'type'        => 'number',
            'label'       => __( 'Archive excerpt length', 'editorial-pro' ),
            'section'     => 'editorial_pro_archive_section',
            'priority'  => 15
        )
    );

    /**
     * Archive read more
     */
    $wp_customize->add_setting(
        'archive_readmore_option', 
        array(
            'default' => 'show',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'editorial_pro_show_switch_sanitize'
        )
    );
    $wp_customize->add_control( new Editorial_Customize_Switch_Control(
        $wp_customize,
            'archive_readmore_option', 
            array(
                'type' => 'switch',
                'label' => __( 'Archive Read More', 'editorial-pro' ),
                'description' => __( 'Show/hide read more link on archive posts.', 'editorial-pro' ),
                'priority'      => 20,
                'section' => 'editorial_pro_archive_section',
                'choices' => array(
                    'show' => __( 'Show', 'editorial-pro' ),
                    'hide' => __( 'Hide', 'editorial-pro' )
                )
            )
        )
    );

    /** 
     * Archive Read more text
     */
    $wp_customize->add_setting(
        'archive_readmore_text',
        array(
            'default'           => __( 'Read More', 'editorial-pro' ),
            'sanitize_callback' => 'editorial_pro_sanitize_text',
        )
    );
    $wp_customize->add_control(
        'archive_readmore_text',
        array(
            'type'        => 'text',
            'label'       => __( 'Read More Text', 'editorial-pro' ),
            'section'     => 'editorial_pro_archive_section',
            'priority'  => 25,
            'active_callback'   => 'editorial_pro_archive_readmore_callback'
        )
    );

    /** 
     * Archive Read more type
     */
    $wp_customize->add_setting(
        'archive_readmore_type',
        array(
            'default'           => 'rm_button',
            'sanitize_callback' => 'sanitize_key',
        )
    );
    $wp_customize->add_control(
        'archive_readmore_type',
        array(
            'type'        => 'radio',
            'label'       => __( 'Read More Type', 'editorial-pro' ),
            'section'     => 'editorial_pro_archive_section',
            'priority'  => 30,
            'active_callback'   => 'editorial_pro_archive_readmore_callback',
            'choices' => array(
                    'rm_button' => __( 'Button', 'editorial-pro' ),
                    'rm_text'   => __( 'Only Text', 'editorial-pro' )
                )
        )
    );

/*--------------------------------------------------------------------------------*/
    /**
     * Single post Settings
     */
    $wp_customize->add_section(
        'editorial_pro_single_post_section',
        array(
            'title'         => __( 'Post Settings', 'editorial-pro' ),
            'priority'      => 15,
            'panel'         => 'editorial_pro_design_settings_panel'
        )
    );

    /**
     * Single post sidebar
     */
    $wp_customize->add_setting(
        'editorial_pro_default_post_sidebar',
        array(
            'default' =>'right_sidebar',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_key',
        )
    );
    $wp_customize->add_control( new Editorial_Customize_Control_Radio_Image(
        $wp_customize,
        'editorial_pro_default_post_sidebar',
            array(
                'label'    => esc_html__( 'Available Sidebars', 'editorial-pro' ),
                'description' => esc_html__( 'Choose sidebar from available options.', 'editorial-pro' ),
                'section'  => 'editorial_pro_single_post_section',
                'choices'  => array(
                        'left_sidebar' => array(
                            'label' => esc_html__( 'Left Sidebar', 'editorial-pro' ),
                            'url'   => '%s/assets/images/left-sidebar.png'
                        ),
                        'right_sidebar' => array(
                            'label' => esc_html__( 'Right Sidebar', 'editorial-pro' ),
                            'url'   => '%s/assets/images/right-sidebar.png'
                        ),
                        'no_sidebar' => array(
                            'label' => esc_html__( 'No Sidebar', 'editorial-pro' ),
                            'url'   => '%s/assets/images/no-sidebar.png'
                        ),
                        'no_sidebar_center' => array(
                            'label' => esc_html__( 'No Sidebar Center', 'editorial-pro' ),
                            'url'   => '%s/assets/images/no-sidebar-center.png'
                        )
                ),
                'priority' => 5
            )
        )
    );

    /**
     * Single post layouts
     */
    $wp_customize->add_setting(
        'editorial_pro_default_post_layout',
        array(
            'default' =>'post_layout_1',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_key',
        )
    );
    $wp_customize->add_control( new Editorial_Customize_Control_Radio_Image(
        $wp_customize,
        'editorial_pro_default_post_layout',
            array(
                'label'    => esc_html__( 'Available Layouts', 'editorial-pro' ),
                'description' => esc_html__( 'Choose single post layout from available layouts.', 'editorial-pro' ),
                'section'  => 'editorial_pro_single_post_section',
                'choices'  => array(
                        'post_layout_1' => array(
                            'label' => esc_html__( 'Post Layout 1', 'editorial-pro' ),
                            'url'   => '%s/assets/images/post-layout-1.jpg'
                        ),
                        'post_layout_2' => array(
                            'label' => esc_html__( 'Post Layout 2', 'editorial-pro' ),
                            'url'   => '%s/assets/images/post-layout-2.jpg'
                        ),
                        'post_layout_3' => array(
                            'label' => esc_html__( 'Post Layout 3', 'editorial-pro' ),
                            'url'   => '%s/assets/images/post-layout-3.jpg'
                        ),
                        'post_layout_4' => array(
                            'label' => esc_html__( 'Post Layout 4', 'editorial-pro' ),
                            'url'   => '%s/assets/images/post-layout-4.jpg'
                        ),
                        'post_layout_5' => array(
                            'label' => esc_html__( 'Post Layout 5', 'editorial-pro' ),
                            'url'   => '%s/assets/images/post-layout-5.jpg'
                        )
                ),
                'priority' => 10
            )
        )
    );

    /**
     * Review section
     */
    $wp_customize->add_setting(
        'editorial_pro_post_review_option', 
        array(
            'default' => 'show',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'editorial_pro_show_switch_sanitize'
        )
    );
    $wp_customize->add_control( new Editorial_Customize_Switch_Control(
        $wp_customize,
            'editorial_pro_post_review_option', 
            array(
                'type' => 'switch',
                'label' => __( 'Post Review Option', 'editorial-pro' ),
                'description' => __( 'Enable/disable review section at single post page.', 'editorial-pro' ),
                'priority'      => 10,
                'section' => 'editorial_pro_single_post_section',
                'choices' => array(
                    'show' => __( 'Show', 'editorial-pro' ),
                    'hide' => __( 'Hide', 'editorial-pro' )
                )
            )
        )
    );

    /**
     * Review section title
     */
    $wp_customize->add_setting(
        'single_post_review_title', 
        array(
              'default' => __( 'Review Overview', 'editorial-pro' ),
              'capability' => 'edit_theme_options',
              'transport'=> 'postMessage',
              'sanitize_callback' => 'editorial_pro_sanitize_text',
            )
    );
    $wp_customize->add_control(
        'single_post_review_title', 
        array(
              'type' => 'text',
              'label' => __( 'Section Title', 'editorial-pro' ),
              'section' => 'editorial_pro_single_post_section',
              'active_callback'   => 'editorial_pro_review_option_callback',
              'priority' => 15
            )
    );
    $wp_customize->selective_refresh->add_partial( 
        'single_post_review_title', 
            array(
                'selector' => 'h4.review-title',
                'render_callback' => 'editorial_pro_customize_partial_review_title',
            )
    );

    /**
     * Summary title
     */
    $wp_customize->add_setting(
        'single_post_review_summary_title', 
        array(
              'default' => __( 'Summary', 'editorial-pro' ),
              'capability' => 'edit_theme_options',
              'transport'=> 'postMessage',
              'sanitize_callback' => 'editorial_pro_sanitize_text',
            )
    );
    $wp_customize->add_control(
        'single_post_review_summary_title', 
        array(
              'type' => 'text',
              'label' => __( 'Summary Title', 'editorial-pro' ),
              'section' => 'editorial_pro_single_post_section',
              'active_callback'   => 'editorial_pro_review_option_callback',
              'priority' => 20
            )
    );

    /**
     * Author box
     */
    $wp_customize->add_setting(
        'editorial_pro_author_box_option', 
        array(
            'default' => 'show',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'editorial_pro_show_switch_sanitize'
        )
    );
    $wp_customize->add_control( new Editorial_Customize_Switch_Control(
        $wp_customize,
            'editorial_pro_author_box_option', 
            array(
                'type' => 'switch',
                'label' => __( 'Author Option', 'editorial-pro' ),
                'description' => __( 'Enable/disable author information at single post page.', 'editorial-pro' ),
                'priority'      => 25,
                'section' => 'editorial_pro_single_post_section',
                'choices' => array(
                    'show' => __( 'Show', 'editorial-pro' ),
                    'hide' => __( 'Hide', 'editorial-pro' )
                )
            )
        )
    );

    /**
     * Related Articles
     */
    $wp_customize->add_setting(
        'editorial_pro_related_articles_option', 
        array(
            'default' => 'enable',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'editorial_pro_enable_switch_sanitize'
        )
    );
    $wp_customize->add_control( new Editorial_Customize_Switch_Control(
        $wp_customize,
            'editorial_pro_related_articles_option', 
            array(
                'type' => 'switch',
                'label' => __( 'Related Articles Option', 'editorial-pro' ),
                'description' => __( 'Enable/disable related articles section at single post page.', 'editorial-pro' ),
                'priority'      => 30,
                'section' => 'editorial_pro_single_post_section',
                'choices' => array(
                    'enable' => __( 'Enable', 'editorial-pro' ),
                    'disable' => __( 'Disable', 'editorial-pro' )
                )
            )
        )
    );

    /**
     * Related articles section title
     */
    $wp_customize->add_setting(
        'editorial_pro_related_articles_title', 
        array(
              'default' => __( 'Related Articles', 'editorial-pro' ),
              'capability' => 'edit_theme_options',
              'transport'=> 'postMessage',
              'sanitize_callback' => 'editorial_pro_sanitize_text',
            )
    );
    $wp_customize->add_control(
        'editorial_pro_related_articles_title', 
        array(
              'type' => 'text',
              'label' => __( 'Section Title', 'editorial-pro' ),
              'section' => 'editorial_pro_single_post_section',
              'active_callback'   => 'editorial_pro_related_articles_option_callback',
              'priority' => 35
            )
    );
    $wp_customize->selective_refresh->add_partial( 
        'editorial_pro_related_articles_title', 
            array(
                'selector' => 'h2.related-title',
                'render_callback' => 'editorial_pro_customize_partial_related_title',
            )
    );

    /**
     * Types of Related articles
     */
    $wp_customize->add_setting(
        'editorial_pro_related_articles_type',
        array(
            'default'           => 'category',
            'sanitize_callback' => 'editorial_pro_sanitize_related_type',
        )
    );
    $wp_customize->add_control(
        'editorial_pro_related_articles_type',
        array(
            'type'        => 'radio',
            'label'       => __( 'Types of Related Articles', 'editorial-pro' ),
            'description' => __( 'Option to display related articles from category/tags.', 'editorial-pro' ),
            'section'     => 'editorial_pro_single_post_section',
            'choices' => array(
                'category'   => __( 'by Category', 'editorial-pro' ),
                'tag'   => __( 'by Tags', 'editorial-pro' )
            ),
            'active_callback'   => 'editorial_pro_related_articles_option_callback',
            'priority'  => 40
        )
    );

    /**
     * Related articles layout
     */
    $wp_customize->add_setting(
        'editorial_pro_related_articles_layout',
        array(
            'default'           => 'default_layout',
            'sanitize_callback' => 'editorial_pro_sanitize_related_layout',
        )
    );
    $wp_customize->add_control(
        'editorial_pro_related_articles_layout',
        array(
            'type'        => 'radio',
            'label'       => __( 'Related Articles Layouts', 'editorial-pro' ),
            'section'     => 'editorial_pro_single_post_section',
            'choices' => array(
                'default_layout'   => __( 'Default Layout', 'editorial-pro' ),
                'boxed_layout'   => __( 'Boxed Layout', 'editorial-pro' )
            ),
            'active_callback'   => 'editorial_pro_related_articles_option_callback',
            'priority'  => 45
        )
    );

    /** 
     * Related post excerpt length
     */
    $wp_customize->add_setting(
        'related_post_excerpt_length',
        array(
            'default'           => 20,
            'sanitize_callback' => 'editorial_pro_sanitize_number',
        )
    );
    $wp_customize->add_control(
        'related_post_excerpt_length',
        array(
            'type'        => 'number',
            'label'       => __( 'Post excerpt length', 'editorial-pro' ),
            'section'     => 'editorial_pro_single_post_section',
            'priority'  => 50
        )
    );
/*--------------------------------------------------------------------------------*/
    /**
     * Single page Settings
     */
    $wp_customize->add_section(
        'editorial_pro_single_page_section',
        array(
            'title'         => __( 'Page Settings', 'editorial-pro' ),
            'priority'      => 20,
            'panel'         => 'editorial_pro_design_settings_panel'
        )
    );

    // Archive page sidebar
    $wp_customize->add_setting(
        'editorial_pro_default_page_sidebar',
        array(
            'default' =>'right_sidebar',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_key',
        )
    );
    $wp_customize->add_control( new Editorial_Customize_Control_Radio_Image(
        $wp_customize,
        'editorial_pro_default_page_sidebar',
            array(
                'label'    => esc_html__( 'Archive Sidebars', 'editorial-pro' ),
                'description' => esc_html__( 'Choose sidebar from available options.', 'editorial-pro' ),
                'section'  => 'editorial_pro_single_page_section',
                'choices'  => array(
                        'left_sidebar' => array(
                            'label' => esc_html__( 'Left Sidebar', 'editorial-pro' ),
                            'url'   => '%s/assets/images/left-sidebar.png'
                        ),
                        'right_sidebar' => array(
                            'label' => esc_html__( 'Right Sidebar', 'editorial-pro' ),
                            'url'   => '%s/assets/images/right-sidebar.png'
                        ),
                        'no_sidebar' => array(
                            'label' => esc_html__( 'No Sidebar', 'editorial-pro' ),
                            'url'   => '%s/assets/images/no-sidebar.png'
                        ),
                        'no_sidebar_center' => array(
                            'label' => esc_html__( 'No Sidebar Center', 'editorial-pro' ),
                            'url'   => '%s/assets/images/no-sidebar-center.png'
                        )
                ),
                'priority' => 5
            )
        )
    );

/*--------------------------------------------------------------------------------------------------------*/
    /**
     * Footer widget area
     */
    $wp_customize->add_section(
        'editorial_pro_footer_widget_section',
        array(
            'title'         => __( 'Footer Settings', 'editorial-pro' ),
            'priority'      => 25,
            'panel'         => 'editorial_pro_design_settings_panel'
        )
    );

    /**
     * footer widget section option
     */
    $wp_customize->add_setting(
        'footer_widget_section_option', 
        array(
            'default' => 'show',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'editorial_pro_show_switch_sanitize'
        )
    );
    $wp_customize->add_control( new Editorial_Customize_Switch_Control(
        $wp_customize,
            'footer_widget_section_option', 
            array(
                'type' => 'switch',
                'label' => __( 'Widget Area Section', 'editorial-pro' ),
                'description' => __( 'Show/Hide option about footer widget area.', 'editorial-pro' ),
                'priority'      => 5,
                'section' => 'editorial_pro_footer_widget_section',
                'choices' => array(
                    'show' => __( 'Show', 'editorial-pro' ),
                    'hide' => __( 'Hide', 'editorial-pro' )
                )
            )
        )
    );

    /**
     * Footer widget area
     */
    $wp_customize->add_setting(
        'footer_widget_option',
        array(
            'default' =>'column3',
            'sanitize_callback' => 'sanitize_key',
        )
    );
    $wp_customize->add_control( new Editorial_Customize_Control_Radio_Image(
        $wp_customize,
        'footer_widget_option',
            array(
                'label'    => esc_html__( 'Widget Area Layout', 'editorial-pro' ),
                'description' => esc_html__( 'Choose layout from available option to display number of columns in footer area.', 'editorial-pro' ),
                'section'  => 'editorial_pro_footer_widget_section',
                'choices'  => array(
                        'column4' => array(
                            'label' => esc_html__( 'Four Columns', 'editorial-pro' ),
                            'url'   => '%s/assets/images/footer-4.png'
                        ),
                        'column3' => array(
                            'label' => esc_html__( 'Three Columns', 'editorial-pro' ),
                            'url'   => '%s/assets/images/footer-3.png'
                        ),
                        'column2' => array(
                            'label' => esc_html__( 'Two Columns', 'editorial-pro' ),
                            'url'   => '%s/assets/images/footer-2.png'
                        ),
                        'column1' => array(
                            'label' => esc_html__( 'One Column', 'editorial-pro' ),
                            'url'   => '%s/assets/images/footer-1.png'
                        )
                ),
                'priority' => 10
            )
        )
    );

    /**
     * Widget area bg color
     */
    $wp_customize->add_setting(
        'footer_widget_bg_color',
        array(
            'default'           => '#F7F7F7',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );
    $wp_customize->add_control( new WP_Customize_Color_Control(
        $wp_customize,
        'footer_widget_bg_color',
            array(
                'label'         => __( 'Background Color', 'editorial-pro' ),
                'section'       => 'editorial_pro_footer_widget_section',
                'priority'      => 15
            )
        )
    );

    /**
     * Widget area text color
     */
    $wp_customize->add_setting(
        'footer_widget_text_color',
        array(
            'default'           => '#333333',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );
    $wp_customize->add_control( new WP_Customize_Color_Control(
        $wp_customize,
        'footer_widget_text_color',
            array(
                'label'         => __( 'Text Color', 'editorial-pro' ),
                'section'       => 'editorial_pro_footer_widget_section',
                'priority'      => 20
            )
        )
    );

    /**
     * Copyright text
     */
    $wp_customize->add_setting(
        'editorial_pro_copyright_text', 
        array(
              'default' => '<span class="copy-info">2016 editorial</span><span class="sep"> | </span>Editorial Pro by <a href="https://mysterythemes.com/" rel="designer" class="customize-unpreviewable">Mystery Themes</a>.',
              'capability' => 'edit_theme_options',
              'transport'=> 'postMessage',
              'sanitize_callback' => 'wp_kses_post',
            )
    );
    $wp_customize->add_control(
        'editorial_pro_copyright_text',
        array(
              'type' => 'textarea',
              'label' => __( 'Copyright Info', 'editorial-pro' ),
              'section' => 'editorial_pro_footer_widget_section',
              'priority' => 30
            )
    );
    $wp_customize->selective_refresh->add_partial( 
        'editorial_pro_copyright_text', 
            array(
                'selector' => '#bottom-footer .site-info',
                'render_callback' => 'editorial_pro_customize_partial_copyright',
            )
    );

/*--------------------------------------------------------------------------------*/
    /**
     * Front widget settings
     */
    $wp_customize->add_section(
        'editorial_pro_front_widget_section',
        array(
            'title'         => __( 'HomePage Widget Settings', 'editorial-pro' ),
            'priority'      => 30,
            'panel'         => 'editorial_pro_design_settings_panel'
        )
    );

    /**
     * Widget title layout
     */
    $wp_customize->add_setting(
        'front_widget_title_layout',
        array(
            'default' =>'widget_title_layout1',
            'sanitize_callback' => 'sanitize_key',
        )
    );
    $wp_customize->add_control( new Editorial_Customize_Control_Radio_Image(
        $wp_customize,
        'front_widget_title_layout',
            array(
                'label'    => esc_html__( 'Widget Title Layout', 'editorial-pro' ),
                'description' => esc_html__( 'Choose option to managed the widget title layout only in homepage widgetarea.', 'editorial-pro' ),
                'section'  => 'editorial_pro_front_widget_section',
                'choices'  => array(
                        'widget_title_layout1' => array(
                            'label' => esc_html__( 'Layout 1', 'editorial-pro' ),
                            'url'   => '%s/assets/images/widget-title-1.png'
                        ),
                        'widget_title_layout2' => array(
                            'label' => esc_html__( 'Layout 2', 'editorial-pro' ),
                            'url'   => '%s/assets/images/widget-title-2.png'
                        )
                ),
                'priority' => 5
            )
        )
    );

    $wp_customize->add_setting(
        'widgets_posts_border_option',
        array(
          'default' => false,
          'capability' => 'edit_theme_options',
          'sanitize_callback' => 'editorial_pro_sanitize_checkbox',
        )
    );
    $wp_customize->add_control(
        'widgets_posts_border_option',
        array(
          'type' => 'checkbox',
          'label' => __( 'Checked to added border for each posts in widget section.', 'editorial-pro' ),
          'section' => 'editorial_pro_front_widget_section',
          'priority' => 10
        )
    );
}