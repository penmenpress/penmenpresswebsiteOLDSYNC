<?php
/**
 * Customizer Theme Customizer.
 *
 * @package wiral
 */

// Load Customizer Helper Functions
require( get_template_directory() . '/inc/customizer/functions/custom-controls.php' );
require( get_template_directory() . '/inc/customizer/functions/sanitize-functions.php' );
require( get_template_directory() . '/inc/customizer/functions/callback-functions.php' );

// Load Customizer Section Files
require( get_template_directory() . '/inc/customizer/sections/customizer-general.php' );
require( get_template_directory() . '/inc/customizer/sections/customizer-home-archives.php' );
require( get_template_directory() . '/inc/customizer/sections/customizer-single.php' );
require( get_template_directory() . '/inc/customizer/sections/customizer-upgrade.php' );
require( get_template_directory() . '/inc/customizer/sections/customizer-slider.php' );

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function wiral_lite_customize_register( $wp_customize ) {

	// Add Theme Options Panel
	$wp_customize->add_panel( 'wiral_lite_options_panel', array(
		'priority'       => 200,
		'capability'     => 'edit_theme_options',
		'theme_supports' => '',
		'title'          => esc_html__( 'Theme Options', 'wiral-lite' ),
		'description'    => '',
	) );
	

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

}
add_action( 'customize_register', 'wiral_lite_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function wiral_lite_customize_preview_js() {
	wp_enqueue_script( 'wiral_lite_customizer', get_template_directory_uri() . '/inc/customizer/js/customizer.js', array( 'customize-preview' ), '20160530', true );
}
add_action( 'customize_preview_init', 'wiral_lite_customize_preview_js' );

/**
 * Embed JS file for Customizer Controls
 *
 */
function wiral_lite_customize_controls_js() {
	
	wp_enqueue_script( 'wiral-customizer-controls', get_template_directory_uri() . '/inc/customizer/js/customizer-controls.js', array(), '20160530', true );
	
	// Localize the script
	wp_localize_script( 'wiral-customizer-controls', 'wiral_lite_theme_links', array(
		'title'	=> esc_html__( 'Theme Links', 'wiral-lite' ),
		'themeURL'	=> esc_url( __( 'https://themecountry.com/wiral-lite/', 'wiral-lite' )),
		'themeLabel'	=> esc_html__( 'Theme Page', 'wiral-lite' ),
		'docuURL'	=> esc_url( __( 'https://themecountry.com/docs/wiral-lite-docs/', 'wiral-lite' )),
		'docuLabel'	=>  esc_html__( 'Theme Documentation', 'wiral-lite' ),
		'rateURL'	=> esc_url( 'https://wordpress.org/support/view/theme-reviews/wiral-lite#postform' ),
		'rateLabel'	=> esc_html__( 'Rate this theme', 'wiral-lite' ),
		)
	);

}
add_action( 'customize_controls_enqueue_scripts', 'wiral_lite_customize_controls_js' );


/**
 * Embed CSS styles for the theme options in the Customizer
 *
 */
function wiral_lite_customize_preview_css() {
	wp_enqueue_style( 'wiral-customizer-css', get_template_directory_uri() . 'inc/customizer/css/customizer.css', array(), '20160530' );
}
add_action( 'customize_controls_print_styles', 'wiral_lite_customize_preview_css' );

/**
 * Returns theme options
 *
 * Uses sane defaults in case the user has not configured any theme options yet.
 */
function wiral_lite_theme_options() {
	// Merge Theme Options Array from Database with Default Options Array
	$theme_options = wp_parse_args( 
		
		// Get saved theme options from WP database
		get_option( 'wiral_lite_theme_options', array() ), 
		
		// Merge with Default Options if setting was not saved yet
		wiral_lite_default_options() 
		
	);

	// Return theme options
	return $theme_options;
}

/**
 * Returns the default settings of the theme
 *
 * @return array
 */
function wiral_lite_default_options() {

	$default_options = array(
		'site_title'						=> true,
		'layout' 							=> 'right-sidebar',
		'sticky_header'						=> false,
		'post_layout_archives'				=> 'left',
		'post_layout_single' 				=> 'header',
		'post_content' 						=> 'excerpt',
		'excerpt_length' 					=> 20,
		'excerpt_more' 						=> ' [...]',
		'meta_date'							=> true,
		'meta_author'						=> true,
		'meta_category'						=> true,
		'meta_tags'							=> false,
		'post_navigation'					=> true,
		'related_posts'						=> 'cat',
		'paging'							=> 'pageing-default',
		'meta_like_count'					=> true,
		'meta_view_count'					=> true,
		'meta_comment_count'				=> true,
		'slider'							=> false,
		'sticky_header'				 		=> false,
		'footer_copyright'					=> '',
	);
	
	return $default_options;
}