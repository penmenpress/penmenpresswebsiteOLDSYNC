<?php
/**
 * wiral functions and definitions
 *
 * @package wiral
 */

define('WIRAL_PRO_URL', 'https://themecountry.com/themes/wiral');

if ( ! function_exists( 'wiral_lite_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function wiral_lite_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on wiral, use a find and replace
	 * to change 'wiral-lite' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'wiral-lite', get_template_directory() . '/languages' );

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
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'wiral-lite-home-thumbnail', 300, 250, true);
	add_image_size( 'wiral-lite-homepage-thumb-slider', 635, 400, true);
	

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' 	=> __( 'Primary Menu', 'wiral-lite' ),
		'category' 	=> __( 'Category Menu', 'wiral-lite' ),
		'footer' 	=> __('Footer Menu', 'wiral-lite')
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
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
	) );

	/**
	 * Support Logo
	 * 
	 */
	add_theme_support( 'custom-logo', array(
		'height'      => 60, // change to your height logo
		'width'       => 200, // change to your width logo
		'flex-width' => true, // change to flexible width
		'flex-width' => true, // change to flexible width
	) );
	//add_theme_support( 'custom-logo' );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'wiral_lite_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}

endif; // wiral_lite_setup
add_action( 'after_setup_theme', 'wiral_lite_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function wiral_lite_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'wiral_lite_content_width', 640 );
}
add_action( 'after_setup_theme', 'wiral_lite_content_width', 0 );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function wiral_lite_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'wiral-lite' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar Home', 'wiral-lite' ),
		'id'            => 'sidebar-2',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'wiral_lite_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function wiral_lite_scripts() {
	wp_enqueue_style( 'wiral-lite-google-fonts', '//fonts.googleapis.com/css?family=Oswald:400,700|Source+Sans+Pro:400,700');
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() .  '/css/font-awesome.min.css');
	wp_enqueue_style( 'slick-style', get_template_directory_uri() .  '/css/slick.css');
	wp_enqueue_style( 'wiral-style', get_stylesheet_uri() );
	wp_enqueue_style( 'wiral-responsive-style', get_template_directory_uri() .  '/css/responsive.css');

	wp_enqueue_script( 'slick-script', get_template_directory_uri() . '/js/slick.min.js', array('jquery'));
	wp_enqueue_script( 'wiral-custom-script', get_template_directory_uri() . '/js/script.js', array('jquery'));
	wp_localize_script( 'wiral-custom-script', 'AdminAjaxURL', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	wp_enqueue_script( 'wiral-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '12332', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

add_action( 'wp_enqueue_scripts', 'wiral_lite_scripts' );



/**
|------------------------------------------------------------------------------
| Custom Tags Cloud
|------------------------------------------------------------------------------
|
*/


function wiral_lite_custom_tag_cloud_widget($args) {
	$args['largest'] = 12; //largest tag
	$args['smallest'] = 12; //smallest tag
	$args['unit'] = 'px'; //tag font unit
	return $args;
}
add_filter( 'widget_tag_cloud_args', 'wiral_lite_custom_tag_cloud_widget' );

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
// include Theme Customizer Options
require get_template_directory() . '/inc/customizer/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/** 
|------------------------------------------------------------------------------
|  Remove comment box
|------------------------------------------------------------------------------
*/
function wiral_lite_comments_form_defaults($default) {
	$default['comment_notes_after'] = '';
	return $default;
}

add_filter('comment_form_defaults','wiral_lite_comments_form_defaults');

// Remove Sticky posts enable slideshow

function wiral_lite_exclude_sticky_post( $query ) {

	$theme_options = wiral_lite_theme_options();

        $sticky = get_option( 'sticky_posts' );

        if ( ! is_admin() && $query->is_home() && $query->is_main_query() && $theme_options['slider'] == 1 && $sticky ) {

                $query->set( 'post__not_in', $sticky );
                $query->set( 'ignore_sticky_posts', true );
        }    
}

add_action( 'pre_get_posts', 'wiral_lite_exclude_sticky_post' );