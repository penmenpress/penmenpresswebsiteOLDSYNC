<?php
/**
 * Editorial Theme Customizer.
 *
 * @package Mystery Themes
 * @subpackage Editorial Pro
 * @since 1.0.0
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function editorial_pro_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

    $wp_customize->selective_refresh->add_partial( 
        'blogname', 
            array(
                'selector' => '.site-title a',
                'render_callback' => 'editorial_pro_customize_partial_blogname',
            )
    );

    $wp_customize->selective_refresh->add_partial( 
        'blogdescription', 
            array(
                'selector' => '.site-description',
                'render_callback' => 'editorial_pro_customize_partial_blogdescription',
            )
    );
}
add_action( 'customize_register', 'editorial_pro_customize_register' );

/**
 * Added customizer scripts
 */
function editorial_pro_customizer_script() {
    
    /*wp_enqueue_script( 'jquery-ui-button' );*/

    $query_args = array(
            'family' => 'Titillium+Web:400,600,700,300&subset=latin,latin-ext'
        );

    wp_enqueue_style( 'editorial-google-font', add_query_arg( $query_args, "//fonts.googleapis.com/css" ) );

    wp_enqueue_script( 'ajax_script_function', get_template_directory_uri(). '/assets/js/typo-ajax.js', array('jquery'), '1.0.0', true );   

    wp_localize_script( 'ajax_script_function', 'ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

    wp_enqueue_script( 'editorial-pro-customizer-script', get_template_directory_uri() .'/assets/js/customizer-script.js', array( 'jquery' ),'1.0.0', true  );    

    wp_enqueue_style( 'jquery-ui', esc_url( get_template_directory_uri() . '/assets/css/jquery-ui.css' ) );

    //wp_enqueue_style( 'editorial-pro-customizer-style', get_template_directory_uri() .'/assets/css/customizer-style.css', array(), '1.0.0' );

}
add_action( 'customize_controls_enqueue_scripts', 'editorial_pro_customizer_script' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function editorial_pro_customize_preview_js() {
	global $editorial_pro_version;
    
    wp_enqueue_script( 'editorial-pro-google-webfont', get_template_directory_uri() . '/assets/js/webfontloader.js', array( 'jquery' ) );

	wp_enqueue_script( 'editorial_pro_customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), esc_attr( $editorial_pro_version ), true );
}
add_action( 'customize_preview_init', 'editorial_pro_customize_preview_js' );

/**
 * Customizer Callback functions
 */
function editorial_pro_review_option_callback( $control ) {
    if ( $control->manager->get_setting( 'editorial_pro_post_review_option' )->value() == 'show' ) {
        return true;
    } else {
        return false;
    }
}

function editorial_pro_related_articles_option_callback( $control ) {
    if ( $control->manager->get_setting( 'editorial_pro_related_articles_option' )->value() != 'disable' ) {
        return true;
    } else {
        return false;
    }
}

function editorial_pro_archive_readmore_callback( $control ) {
    if ( $control->manager->get_setting( 'archive_readmore_option' )->value() != 'hide' ) {
        return true;
    } else {
        return false;
    }
}

function editorial_pro_pre_loader_callback( $control ) {
    if ( $control->manager->get_setting( 'site_pre_loader_option' )->value() != 'hide' ) {
        return true;
    } else {
        return false;
    }
}

function editorial_pro_top_header_option_callback( $control ) {
    if ( $control->manager->get_setting( 'editorial_pro_top_header_option' )->value() != 'disable' ) {
        return true;
    } else {
        return false;
    }
}

function editorial_pro_date_option_callback( $control ) {
    if ( $control->manager->get_setting( 'editorial_pro_header_date' )->value() != 'disable' ) {
        return true;
    } else {
        return false;
    }
}


/*---------------------------------------------------------------------------------------------------------------*/
/**
 * Render the site title for the selective refresh partial.
 *
 * @since Editorial Pro 1.0.1
 * @see editorial_pro_customize_register()
 *
 * @return void
 */
function editorial_pro_customize_partial_blogname() {
    bloginfo( 'name' );
}

/**
 * Render the site title for the selective refresh partial.
 *
 * @since Editorial Pro 1.0.1
 * @see editorial_pro_customize_register()
 *
 * @return void
 */
function editorial_pro_customize_partial_blogdescription() {
    bloginfo( 'description' );
}

/**
 * Render the site title for the selective refresh partial.
 *
 * @since Editorial Pro 1.1.0
 * @see editorial_pro_design_settings_register()
 *
 * @return void
 */
function editorial_pro_customize_partial_review_title() {
    return get_theme_mod( 'single_post_review_title' );
}

/**
 * Render the site title for the selective refresh partial.
 *
 * @since Editorial Pro 1.1.0
 * @see editorial_pro_design_settings_register()
 *
 * @return void
 */
function editorial_pro_customize_partial_related_title() {
    return get_theme_mod( 'editorial_pro_related_articles_title' );
}

/**
 * Render the site title for the selective refresh partial.
 *
 * @since Editorial Pro 1.1.0
 * @see editorial_pro_design_settings_register()
 *
 * @return void
 */
function editorial_pro_customize_partial_copyright() {
    return get_theme_mod( 'editorial_pro_copyright_text' );
}

/**
 * Render the site title for the selective refresh partial.
 *
 * @since Editorial Pro 1.1.0
 * @see editorial_pro_design_settings_register()
 *
 * @return void
 */
function editorial_pro_customize_partial_ticker_caption() {
    return get_theme_mod( 'editorial_pro_ticker_caption' );
}

/*---------------------------------------------------------------------------------------------------------------*/
/**
 * Load required files for customizer page
 */
require get_template_directory() . '/inc/customizer/general-panel.php'; //General settings panel
require get_template_directory() . '/inc/customizer/header-panel.php'; //header settings panel
require get_template_directory() . '/inc/customizer/design-panel.php'; //Design Settings panel
require get_template_directory() . '/inc/customizer/additional-panel.php'; //Additional settings panel
require get_template_directory() . '/inc/customizer/typography-panel.php'; //Typography settings panel

require get_template_directory() . '/inc/customizer/editorial-custom-classes.php'; //custom classes
require get_template_directory() . '/inc/customizer/editorial-sanitize.php'; //custom classes