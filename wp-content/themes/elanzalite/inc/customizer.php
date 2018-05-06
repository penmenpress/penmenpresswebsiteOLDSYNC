<?php
/*theme customizer*/
function elanzalite_customize_register( $wp_customize ) {
    if (!shortcode_exists( 'themehunk-customizer-elanzalite' ) ):
        $obj = New Elanzalite_Plugin();
        $obj->active();
    endif;
     //  =============================
     //  = Genral Settings     =
     //  =============================
$wp_customize->get_section('title_tagline')->title = esc_html__('General Setting', 'elanzalite');
$wp_customize->get_section('title_tagline')->priority = 3;

// *****************************
// Theme Option
// ******************************
    $wp_customize->add_panel( 'elanzalite_theme_options', array(
        'priority'       => 4,
        'title'          => __('Theme Option', 'elanzalite'),
    ) );
    $wp_customize->add_section('blog_option', array(
        'title'    => __('Blog Post', 'elanzalite'),
        'priority' => 4,
        'panel' => 'elanzalite_theme_options',
        'active_callback' => 'elanzalite_is_not_magazine_page',
    ));

    $wp_customize->add_setting( 'elanzalite_listing_layout',
        array(
              'sanitize_callback' => 'sanitize_text_field',
              'default'           => 'two-grid-layout',
              )
         );
     $wp_customize->add_control( 'elanzalite_listing_layout',
        array(
        'type'        => 'select',
        'label'       => esc_html__('Post Layout', 'elanzalite'),
        'description'       => esc_html__('Choose Post Layout option for Blog Post', 'elanzalite'),
        'section'     => 'blog_option',
        'choices' => array(  
        'two-grid-layout' => esc_html__('Two Grid', 'elanzalite'),
        'standard-layout' => esc_html__('Standard Layout', 'elanzalite'),
                    )
                )
            );
    //  =============================
    //  = Single post setting =
    //  =============================
    $wp_customize->add_section('page_option', array(
        'title'    => __('Page Sidebar', 'elanzalite'),
        'priority' => 6,
        'panel' => 'elanzalite_theme_options',
    ));
     // Sidebar settings
    $wp_customize->add_setting('elanzalite_page_layout',
    array(
              'sanitize_callback' => 'sanitize_text_field',
              'default'           => 'right',
               
              )
         );
     $wp_customize->add_control('elanzalite_page_layout',
        array(
        'type'        => 'select',
        'label'       => esc_html__('Sidebar Alignment', 'elanzalite'),
        'description'       => esc_html__('Choose sidebar option for Pages', 'elanzalite'),
        'section'     => 'page_option',
        'choices' => array(
        'right' => esc_html__('Right sidebar', 'elanzalite'),
        'left' => esc_html__('Left sidebar', 'elanzalite'),
        'no-sidebar' => esc_html__('No sidebar', 'elanzalite'),
                    ) ) );
    //  =============================
    //  = Social Settings       =
    //  =============================
 $wp_customize->add_section('social_option', array(
        'title'    => __('Footer Social Icon', 'elanzalite'),
        'priority' => 8,
        'panel' => 'elanzalite_theme_options',

    ));
    //= social Options = facebook
     $wp_customize->add_setting('f_link', array(
        'default'        => '',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('f_link', array(
        'settings' => 'f_link',
        'label'   => esc_html__('Facebook Link:','elanzalite'),
        'section' => 'social_option',
        'type'    => 'text',
    )  );
    //google icon
      $wp_customize->add_setting('g_link', array(
        'default'        => '',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('g_link', array(
        'settings' => 'g_link',
        'label'   => esc_html__('Google Link:','elanzalite'),
        'section' => 'social_option',
        'type'    => 'text',
    )  );

    //linkdin icon

      $wp_customize->add_setting('l_link', array(
        'default'        => '',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('l_link', array(
        'settings' => 'l_link',
        'label'   => esc_html__('Linkedin Link:','elanzalite'),
        'section' => 'social_option',
        'type'    => 'text',
    )  );

    //pintrest
      $wp_customize->add_setting('p_link', array(
        'default'        => '',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('p_link', array(
        'settings' => 'p_link',
        'label'   => esc_html__('Pinterest Link:','elanzalite'),
        'section' => 'social_option',
        'type'    => 'text',
    )  );
    //twitter
     $wp_customize->add_setting('t_link', array(
        'default'        => '',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('t_link', array(
        'settings' => 't_link',
        'label'   => esc_html__('Twitter Link:','elanzalite'),
        'section' => 'social_option',
        'type'    => 'text',
    ));

    //  =============================
    //  = Footer Settings      =
    //  =============================
    $wp_customize->add_section('footer_option', array(
        'title'    => __('Footer Option', 'elanzalite'),
        'priority' => 100,
        'panel' =>'elanzalite_theme_options',
    ));

    $wp_customize->add_setting('copyright_textbox', array(
        'default'           => '',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('copyright_textbox', array(
        'settings'=> 'copyright_textbox',
        'label'   => esc_html__('Copyright Text', 'elanzalite'),
        'section' => 'footer_option',
        'type'    => 'text',
    ) );
// wordpress-default-option
$wp_customize->add_section( 'header_image', array(
  'title'          => __( 'Header Background Image', 'elanzalite' ),
  'theme_supports' => 'custom-header',
  'priority'       => 40,
  
) );
// custom color
    $wp_customize->get_section('colors')->title = esc_html__('Body Background Color', 'elanzalite');
    $wp_customize->get_section('colors')->priority = 60;
    
// custom background
$wp_customize->add_section( 'background_image', array(
  'title'          => __( 'Body Background Image', 'elanzalite' ),
  'theme_supports' => 'custom-background',
  'priority'       => 80,
  
) );  
}
add_action('customize_register','elanzalite_customize_register');