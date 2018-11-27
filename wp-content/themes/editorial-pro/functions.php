<?php
/**
 * Editorial functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Mystery Themes
 * @subpackage Editorial Pro
 * @since 1.0.0
 */

if ( ! function_exists( 'editorial_pro_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function editorial_pro_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Editorial, use a find and replace
	 * to change 'editorial-pro' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'editorial-pro', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for custom logo.
	 */
	add_theme_support( 'custom-logo', array(
			'height' => 60,
			'width'  => 320,
			'flex-height' => true,
			'flex-width' => true
		) 
	);

	add_image_size( 'editorial-slider-large', 1020, 732, true );
	add_image_size( 'editorial-horizontal-thumb', 573, 205, true );
	add_image_size( 'editorial-featured-medium', 457, 334, true );
	add_image_size( 'editorial-featured-long', 690, 1024, true );
	add_image_size( 'editorial-block-medium', 464, 290, true );
	add_image_size( 'editorial-block-thumb', 322, 230, true );
	add_image_size( 'editorial-single-large', 1210, 642, true );
	add_image_size( 'editorial-single-fullwidth', 1920, 1080, true );
	add_image_size( 'editorial-featured-square', 283, 204, true );
	add_image_size( 'editorial-block-portrait', 425, 713, true );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'editorial-pro' ),
		'top-header' => esc_html__( 'Top Header Menu', 'editorial-pro' ),
		'footer' => esc_html__( 'Footer Menu', 'editorial-pro' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See https://developer.wordpress.org/themes/functionality/post-formats/
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'video',
		'audio',
		'gallery'
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'editorial_pro_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Selective refresh for widgets.
    add_theme_support( 'customize-selective-refresh-widgets' );
}
endif;

add_action( 'after_setup_theme', 'editorial_pro_setup' );

/**
 * Set the theme version, based on theme stylesheet.
 *
 * @global string $editorial_pro_version
 */
function editorial_pro_version_info() {
	$editorial_pro_theme_info = wp_get_theme();
	$GLOBALS['editorial_pro_version'] = $editorial_pro_theme_info->get( 'Version' );
}
add_action( 'after_setup_theme', 'editorial_pro_version_info', 0 );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function editorial_pro_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'editorial_pro_content_width', 640 );
}
add_action( 'after_setup_theme', 'editorial_pro_content_width', 0 );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer/customizer.php';

/**
 * Editorial custom hook
 */
require get_template_directory() . '/inc/editorial-hooks.php';

/**
 * Check if WooCommerce is active.
 */
$all_active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
if ( stripos( implode( $all_active_plugins ), 'woocommerce.php' ) ) {
    /**
     * WooCommerce theme compatibility hooks.
     */
    require get_template_directory() . '/inc/woocommerce-hooks.php';
    
    /**
     * Load WooCommerce theme support.
     */
    function woocommerce_support() {
        add_theme_support( 'woocommerce' );
    }
    add_action( 'after_setup_theme', 'woocommerce_support' );
}

/**
 * Editorial custom functions
 */
require get_template_directory() . '/inc/editorial-functions.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load Editorial widgets
 */
require get_template_directory() . '/inc/widgets/editorial-widgets-area.php';

/**
 * Load metabox
 */
require get_template_directory() . '/inc/metaboxes/editorial-author-info.php';
require get_template_directory() . '/inc/metaboxes/editorial-post-format.php';
require get_template_directory() . '/inc/metaboxes/editorial-post-layout.php';
require get_template_directory() . '/inc/metaboxes/editorial-post-sidebar.php';
require get_template_directory() . '/inc/metaboxes/editorial-post-review.php';

/**
 * Load dynamic styles file
 */
require get_template_directory() . '/inc/editorial-dynamic-styles.php';

/**
 * Load demo data importer file
 */
require get_template_directory() . '/inc/import/mt-importer.php';