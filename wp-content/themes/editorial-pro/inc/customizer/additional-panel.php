<?php
/**
 * Customizer settings for Additional Settings
 *
 * @package Mystery Themes
 * @subpackage Editorial Pro
 * @since 1.0.0
 */

add_action( 'customize_register', 'editorial_pro_additional_settings_register' );

function editorial_pro_additional_settings_register( $wp_customize ) {

	/**
     * Add Additional Settings Panel 
     */
    $wp_customize->add_panel( 
        'editorial_pro_additional_settings_panel', 
        array(
            'priority'       => 20,
            'capability'     => 'edit_theme_options',
            'theme_supports' => '',
            'title'          => __( 'Additional Settings', 'editorial-pro' ),
        ) 
    );
/*--------------------------------------------------------------------------------------------*/
	/**
     * Category Color Section
     */
    $wp_customize->add_section(
        'editorial_pro_categories_color_section',
        array(
            'title'         => __( 'Categories Color', 'editorial-pro' ),
            'priority'      => 5,
            'panel'         => 'editorial_pro_additional_settings_panel',
        )
    );

	$priority = 3;
	$categories = get_terms( 'category' ); // Get all Categories
	$wp_category_list = array();

	foreach ( $categories as $category_list ) {

		$wp_customize->add_setting( 
			'editorial_pro_category_color_'.esc_html( strtolower( $category_list->name ) ),
			array(
				'default'              => '#00a9e0',
				'capability'           => 'edit_theme_options',
				'sanitize_callback'    => 'sanitize_hex_color'
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize, 
				'editorial_pro_category_color_'.esc_html( strtolower($category_list->name) ),
				array(
					'label'    => sprintf( esc_html__( ' %s', 'editorial-pro' ), esc_html( $category_list->name ) ),
					'section'  => 'editorial_pro_categories_color_section',
					'priority' => $priority
				)
			)
		);
		$priority++;
	}
/*--------------------------------------------------------------------------------------------*/
	/**
     * Social icons
     */
	$wp_customize->add_section(
        'editorial_pro_social_media_section',
        array(
            'title'         => __( 'Social Media', 'editorial-pro' ),
            'priority'      => 10,
            'panel'         => 'editorial_pro_additional_settings_panel',
        )
    );

    $social_data = array(
            'fb' => __( 'Facebook', 'editorial-pro' ),
            'tw' => __( 'Twitter', 'editorial-pro' ),
            'gp' => __( 'Google+', 'editorial-pro' ),
            'lnk' => __( 'LinkedIn', 'editorial-pro' ),
            'yt' => __( 'YouTube', 'editorial-pro' ),
            'vm' => __( 'Vimeo', 'editorial-pro' ),
            'pin' => __( 'Pinterest', 'editorial-pro' ),
            'insta' => __( 'Instagram', 'editorial-pro' ),
        );

    $priority = 5;

    foreach ( $social_data as $key => $value ) {
        $wp_customize->add_setting(
            'social_'.$key.'_link',
            array(
                'default' => '',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'esc_url_raw'
            )
        );
        $wp_customize->add_control(
            'social_'.$key.'_link',
            array(
                'type' => 'text',
                'priority' => $priority,
                'label' => $value,
                'description' => __( 'Your Social Account URL', 'editorial-pro' ),
                'section' => 'editorial_pro_social_media_section'
            )
        );
    }
    
/*--------------------------------------------------------------------------------------------*/
    /**
     * Breadcrumbs section
     */
    $wp_customize->add_section(
        'editorial_pro_breadcrumb_section',
        array(
            'title'         => __( 'Breadcrumbs Settings', 'editorial-pro' ),
            'priority'      => 15,
            'panel'         => 'editorial_pro_additional_settings_panel',
        )
    );

    /**
     * Breadcrumbs option
     */
    $wp_customize->add_setting(
        'editorial_breadcrumbs_option', 
        array(
            'default' => 'show',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'editorial_pro_show_switch_sanitize'
        )
    );
    $wp_customize->add_control( new Editorial_Customize_Switch_Control(
        $wp_customize,
            'editorial_breadcrumbs_option', 
            array(
                'type' => 'switch',
                'label' => __( 'Breadcrumbs Option', 'editorial-pro' ),
                'description' => __( 'Show/hide breadcrumbs at innerpages.', 'editorial-pro' ),
                'priority'      => 5,
                'section' => 'editorial_pro_breadcrumb_section',
                'choices' => array(
                    'show' => __( 'Show', 'editorial-pro' ),
                    'hide' => __( 'Hide', 'editorial-pro' )
                )
            )
        )
    );

    /**
     * Breadcrumbs Home Text
     */
    $wp_customize->add_setting(
        'editorial_bread_home',
        array(
            'default' => __( 'Home', 'editorial-pro' ),
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'editorial_pro_sanitize_text'
        )
    );
    $wp_customize->add_control(
        'editorial_bread_home',
        array(
            'type' => 'text',
            'priority' => 10,
            'label' => __( 'Home Text', 'editorial-pro' ),
            'description' => __( 'Add breadcrumbs home text.', 'editorial-pro' ),
            'section' => 'editorial_pro_breadcrumb_section'
        )
    );

    /**
     * Breadcrumbs Separator value
     */
    $wp_customize->add_setting(
        'editorial_bread_sep',
        array(
            'default' => '>',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'editorial_pro_sanitize_text'
        )
    );
    $wp_customize->add_control(
        'editorial_bread_sep',
        array(
            'type' => 'text',
            'priority' => 15,
            'label' => __( 'Separator Value', 'editorial-pro' ),
            'section' => 'editorial_pro_breadcrumb_section'
        )
    );

/*--------------------------------------------------------------------------------------------*/
    /**
     * Post Image settings
     */
    $wp_customize->add_section(
        'editorial_pro_posts_section',
        array(
            'title'         => __( 'Posts Settings', 'editorial-pro' ),
            'priority'      => 20,
            'panel'         => 'editorial_pro_additional_settings_panel',
        )
    );

    /**
     * Post categories layout
     */
    $wp_customize->add_setting(
        'post_categories_list_type',
        array(
            'default' => 'in_boxed',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'editorial_pro_post_categories_list_type'
        )
    );
    $wp_customize->add_control(
        'post_categories_list_type',
        array(
            'type' => 'select',
            'priority' => 5,
            'label' => __( 'Post Categories Type', 'editorial-pro' ),
            'section' => 'editorial_pro_posts_section',
            'choices' => array(                    
                    'in_boxed' => __( 'In Box', 'editorial-pro' ),
                    'in_plain_text' => __( 'In Plain Text', 'editorial-pro' )
                )
        )
    );

    /**
     * Post image hover types
     */
    $wp_customize->add_setting(
        'post_image_hover_type',
        array(
            'default' => 'zoomin',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'editorial_pro_image_hover_type'
        )
    );
    $wp_customize->add_control(
        'post_image_hover_type',
        array(
            'type' => 'select',
            'priority' => 5,
            'label' => __( 'Image Hover Type', 'editorial-pro' ),
            'section' => 'editorial_pro_posts_section',
            'choices' => array(                    
                    'zoomin' => __( 'Zoom In', 'editorial-pro' ),
                    'zoomin_rotate' => __( 'Zoom In Rotate', 'editorial-pro' ),
                    'zoomout' => __( 'Zoom Out', 'editorial-pro' ),
                    'zoomout_rotate' => __( 'Zoom Out Rotate', 'editorial-pro' ),
                    'shine' => __( 'Shine', 'editorial-pro' ),
                    'slanted_shine' => __( 'Slanted Shine', 'editorial-pro' ),
                    'grayscale' => __( 'Grayscale', 'editorial-pro' ),
                    'opacity' => __( 'Opacity', 'editorial-pro' ),
                    'flashing' => __( 'Flashing', 'editorial-pro' ),
                    'circle' => __( 'Circle', 'editorial-pro' )
                )
        )
    );

    /**
     * Post date option (condition apply only for widget posts)
     */
    $wp_customize->add_setting(
        'editorial_widget_post_date', 
        array(
            'default' => 'show',
            'capability' => 'edit_theme_options',
            'transport' => 'postMessage',
            'sanitize_callback' => 'editorial_pro_show_switch_sanitize'
        )
    );
    $wp_customize->add_control( new Editorial_Customize_Switch_Control(
        $wp_customize,
            'editorial_widget_post_date', 
            array(
                'type' => 'switch',
                'label' => __( 'Post Date Option', 'editorial-pro' ),
                'description' => __( 'Show/hide date from post ( only from widget posts ).', 'editorial-pro' ),
                'priority'      => 10,
                'section' => 'editorial_pro_posts_section',
                'choices' => array(
                    'show' => __( 'Show', 'editorial-pro' ),
                    'hide' => __( 'Hide', 'editorial-pro' )
                )
            )
        )
    );

    /**
     * Post author option (condition apply only for widget posts)
     */
    $wp_customize->add_setting(
        'editorial_widget_post_author', 
        array(
            'default' => 'show',
            'capability' => 'edit_theme_options',
            'transport' => 'postMessage',
            'sanitize_callback' => 'editorial_pro_show_switch_sanitize'
        )
    );
    $wp_customize->add_control( new Editorial_Customize_Switch_Control(
        $wp_customize,
            'editorial_widget_post_author', 
            array(
                'type' => 'switch',
                'label' => __( 'Post Author Option', 'editorial-pro' ),
                'description' => __( 'Show/hide author from post ( only from widget posts ).', 'editorial-pro' ),
                'priority'      => 15,
                'section' => 'editorial_pro_posts_section',
                'choices' => array(
                    'show' => __( 'Show', 'editorial-pro' ),
                    'hide' => __( 'Hide', 'editorial-pro' )
                )
            )
        )
    );

    /**
     * Post comment option (condition apply only for widget posts)
     */
    $wp_customize->add_setting(
        'editorial_widget_post_comment', 
        array(
            'default' => 'show',
            'capability' => 'edit_theme_options',
            'transport' => 'postMessage',
            'sanitize_callback' => 'editorial_pro_show_switch_sanitize'
        )
    );
    $wp_customize->add_control( new Editorial_Customize_Switch_Control(
        $wp_customize,
            'editorial_widget_post_comment', 
            array(
                'type' => 'switch',
                'label' => __( 'Post Comment Option', 'editorial-pro' ),
                'description' => __( 'Show/hide comment from post ( only from widget posts ).', 'editorial-pro' ),
                'priority'      => 20,
                'section' => 'editorial_pro_posts_section',
                'choices' => array(
                    'show' => __( 'Show', 'editorial-pro' ),
                    'hide' => __( 'Hide', 'editorial-pro' )
                )
            )
        )
    );

    /**
     * Post categories option (condition apply only for widget posts)
     */
    $wp_customize->add_setting(
        'editorial_widget_post_categories', 
        array(
            'default' => 'show',
            'capability' => 'edit_theme_options',
            'transport' => 'postMessage',
            'sanitize_callback' => 'editorial_pro_show_switch_sanitize'
        )
    );
    $wp_customize->add_control( new Editorial_Customize_Switch_Control(
        $wp_customize,
            'editorial_widget_post_categories', 
            array(
                'type' => 'switch',
                'label' => __( 'Post Categories Option', 'editorial-pro' ),
                'description' => __( 'Show/hide categories from post ( only from widget posts ).', 'editorial-pro' ),
                'priority'      => 25,
                'section' => 'editorial_pro_posts_section',
                'choices' => array(
                    'show' => __( 'Show', 'editorial-pro' ),
                    'hide' => __( 'Hide', 'editorial-pro' )
                )
            )
        )
    );

    /**
     * Post review option (condition apply only for widget posts)
     */
    $wp_customize->add_setting(
        'editorial_widget_post_review', 
        array(
            'default' => 'show',
            'capability' => 'edit_theme_options',
            'transport' => 'postMessage',
            'sanitize_callback' => 'editorial_pro_show_switch_sanitize'
        )
    );
    $wp_customize->add_control( new Editorial_Customize_Switch_Control(
        $wp_customize,
            'editorial_widget_post_review', 
            array(
                'type' => 'switch',
                'label' => __( 'Post Review Option', 'editorial-pro' ),
                'description' => __( 'Show/hide review from post ( only from widget posts ).', 'editorial-pro' ),
                'priority'      => 30,
                'section' => 'editorial_pro_posts_section',
                'choices' => array(
                    'show' => __( 'Show', 'editorial-pro' ),
                    'hide' => __( 'Hide', 'editorial-pro' )
                )
            )
        )
    );

    /**
     * Post format icon
     */
    $wp_customize->add_setting(
        'editorial_widget_post_format_icon', 
        array(
            'default' => 'show',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'editorial_pro_show_switch_sanitize'
        )
    );
    $wp_customize->add_control( new Editorial_Customize_Switch_Control(
        $wp_customize,
            'editorial_widget_post_format_icon', 
            array(
                'type' => 'switch',
                'label' => __( 'Post Format Icon Option', 'editorial-pro' ),
                'description' => __( 'Show/hide format icon at post featured image.', 'editorial-pro' ),
                'priority'      => 35,
                'section' => 'editorial_pro_posts_section',
                'choices' => array(
                    'show' => __( 'Show', 'editorial-pro' ),
                    'hide' => __( 'Hide', 'editorial-pro' )
                )
            )
        )
    );

    /**
     * Post fallback image
     */
    $wp_customize->add_setting(
        'post_fallback_image',
            array(
                'default' => '',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'esc_url_raw'
            )
    );
    $wp_customize->add_control( new WP_Customize_Image_Control(
        $wp_customize,
        'post_fallback_image',
            array(
                'label'      => esc_html__( 'Fall back Image', 'editorial-pro' ),
                'section'    => 'editorial_pro_posts_section',
                'priority' => 40
            )
        )
    );

}