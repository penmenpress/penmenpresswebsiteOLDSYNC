<?php
/**
 * A child theme for Editorial
 *
 * @package Mystery Themes
 * @subpackage Editorial News
 * @since 1.0.0
 */
/*-------------------------------------------------------------------------------------------------------------------------------*/
if ( ! function_exists( 'editorial_news_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function editorial_news_setup() {

    add_image_size( 'editorial-news-block-thumb', 322, 235, true );

    $editorial_news_theme_info = wp_get_theme();
    $GLOBALS['editorial_news_version'] = $editorial_news_theme_info->get( 'Version' );
}
endif;

add_action( 'after_setup_theme', 'editorial_news_setup' );

/*-------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Managed the theme default color
 */
function editorial_news_customize_register( $wp_customize ) {
		global $wp_customize;

		$wp_customize->get_setting( 'editorial_theme_color' )->default = '#FF7D0E';

	}

add_action( 'customize_register', 'editorial_news_customize_register', 20 );

/*-------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Register Google fonts for Editorial News.
 *
 * @return string Google fonts URL for the theme.
 * @since 1.0.0
 */
if ( ! function_exists( 'editorial_news_fonts_url' ) ) :
    function editorial_news_fonts_url() {

        $fonts_url = '';
        $font_families = array();

        /*
         * Translators: If there are characters in your language that are not supported
         * by Roboto, translate this to 'off'. Do not translate into your own language.
         */
        if ( 'off' !== _x( 'on', 'Roboto font: on or off', 'editorial-news' ) ) {
            $font_families[] = 'Roboto:300,400,400i,500,700';
        }

        if( $font_families ) {
            $query_args = array(
                'family' => urlencode( implode( '|', $font_families ) ),
                'subset' => urlencode( 'latin,latin-ext' ),
            );

            $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
        }

        return $fonts_url;
    }
endif;

/*-------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Enqueue child theme styles and scripts
 */
add_action( 'wp_enqueue_scripts', 'editorial_news_scripts', 20 );

function editorial_news_scripts() {

    global $editorial_news_version;

	wp_dequeue_style( 'editorial-google-font' );

	wp_enqueue_style( 'editorial-news-google-font', editorial_news_fonts_url(), array(), null );

	wp_dequeue_style( 'editorial-style' );
    wp_dequeue_style( 'editorial-responsive' );

    wp_enqueue_style( 'editorial-parent-style', get_template_directory_uri() . '/style.css', array(), esc_attr( $editorial_news_version ) );

    wp_enqueue_style( 'editorial-parent-responsive', get_template_directory_uri() . '/assets/css/editorial-responsive.css', array(), esc_attr( $editorial_news_version ) );

	wp_enqueue_style( 'editorial-news-style', get_stylesheet_uri(), array(), esc_attr( $editorial_news_version ) );

    $en_theme_color = esc_attr( get_theme_mod( 'editorial_theme_color', '#FF7D0E' ) );
    
    $get_categories = get_terms( 'category', array( 'hide_empty' => false ) );

    $output_css = '';

    foreach( $get_categories as $category ){

        $cat_color = esc_attr( get_theme_mod( 'editorial_category_color_'.strtolower( $category->name ), $en_theme_color ) );
        $cat_hover_color = esc_attr( editorial_hover_color( $cat_color, '-50' ) );
        $cat_id = esc_attr( $category->term_id );

        if( !empty( $cat_color ) ) {
            $output_css .= ".category-button.mt-cat-".$cat_id." a { background: ". $cat_color ."}\n";

            $output_css .= ".category-button.mt-cat-".$cat_id." a:hover { background: ". $cat_hover_color ."}\n";

            $output_css .= ".block-header.mt-cat-".$cat_id." { border-bottom: 3px solid ".$cat_color." }\n";

            //$output_css .= ".rtl .block-header.mt-cat-".$cat_id." { border-left: none; border-right: 2px solid ".$cat_color." }\n";

            $output_css .= ".archive .page-header.mt-cat-".$cat_id." { border-left: 4px solid ".$cat_color." }\n";

            $output_css .= ".rtl.archive .page-header.mt-cat-".$cat_id." { border-left: none; border-right: 4px solid ".$cat_color." }\n";

            $output_css .= "#site-navigation ul li.mt-cat-".$cat_id." { border-bottom-color: ".$cat_color." }\n";
        }
    }

    $output_css .= ".navigation .nav-links a,.bttn,button,input[type='button'],input[type='reset'],input[type='submit'],.navigation .nav-links a:hover,.bttn:hover,button,input[type='button']:hover,input[type='reset']:hover,input[type='submit']:hover,.edit-link .post-edit-link ,.reply .comment-reply-link,.home-icon,.search-main,.header-search-wrapper .search-form-main .search-submit,.mt-slider-section .bx-controls a:hover,.widget_search .search-submit,.error404 .page-title,.archive.archive-classic .entry-title a:after,#mt-scrollup,.widget_tag_cloud .tagcloud a:hover,.sub-toggle,#site-navigation ul > li:hover > .sub-toggle, #site-navigation ul > li.current-menu-item .sub-toggle, #site-navigation ul > li.current-menu-ancestor .sub-toggle,.ticker-caption{ background:". $en_theme_color ."}\n";

        $output_css .= ".navigation .nav-links a,.bttn,button,input[type='button'],input[type='reset'],input[type='submit'],.widget_search .search-submit,.widget_tag_cloud .tagcloud a:hover{ border-color:". $en_theme_color ."}\n";

        $output_css .= ".comment-list .comment-body ,.header-search-wrapper .search-form-main{ border-top-color:". $en_theme_color ."}\n";

        $output_css .= "#site-navigation ul li,.header-search-wrapper .search-form-main:before,.block-header, .widget .widget-title-wrapper, .related-articles-wrapper .widget-title-wrapper, .archive .page-header{ border-bottom-color:". $en_theme_color ."}\n";

        $output_css .= ".archive .page-header,.block-header, .widget .widget-title-wrapper, .related-articles-wrapper .widget-title-wrapper{ border-left-color:". $en_theme_color ."}\n";

        $output_css .= "a,a:hover,a:focus,a:active,.entry-footer a:hover,.comment-author .fn .url:hover,#cancel-comment-reply-link,#cancel-comment-reply-link:before, .logged-in-as a,.top-menu ul li a:hover,#footer-navigation ul li a:hover,#site-navigation ul li a:hover#site-navigation ul li:hover > a,#site-navigation ul li.current-menu-item > a,#site-navigation ul li.current_page_ancestor > a,#site-navigation ul li.current_page_item > a,#site-navigation ul li.current-menu-ancestor > a,.mt-slider-section .slide-title a:hover,.featured-post-wrapper .featured-title a:hover,.editorial_block_grid .post-title a:hover,.slider-meta-wrapper span:hover,.slider-meta-wrapper a:hover,.featured-meta-wrapper span:hover,.featured-meta-wrapper a:hover,.post-meta-wrapper > span:hover,.post-meta-wrapper span > a:hover ,.grid-posts-block .post-title a:hover,.list-posts-block .single-post-wrapper .post-content-wrapper .post-title a:hover,.column-posts-block .single-post-wrapper.secondary-post .post-content-wrapper .post-title a:hover,.widget a:hover,.widget a:hover::before,.widget li:hover::before,.entry-title a:hover,.entry-meta span a:hover,.post-readmore a:hover,.archive-classic .entry-title a:hover,
            .archive-columns .entry-title a:hover,.related-posts-wrapper .post-title a:hover,.block-header .block-title a:hover,.widget .widget-title a:hover,.related-articles-wrapper .related-title a:hover,#colophon .widget_archive a:hover, #colophon .widget_categories a:hover, #colophon .widget_recent_entries a:hover, #colophon .widget_meta a:hover, #colophon .widget_recent_comments li, #colophon .widget_rss li, #colophon .widget_pages li a:hover, #colophon .widget_nav_menu li a:hover, #colophon .widget .widget-title,.home.blog .archive-desc-wrapper .entry-title a:hover { color:". $en_theme_color ."}\n";

    $refine_output_css = editorial_css_strip_whitespace( $output_css );

    wp_add_inline_style( 'editorial-news-style', $refine_output_css );
}

/*-------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Unregister widget to managed the sections
 *
 * @since 1.0.0
 */

add_action( 'widgets_init', 'editorial_news_parent_unregister_widgets', 99 );

function editorial_news_parent_unregister_widgets() {
    unregister_widget( 'Editorial_Featured_Slider' );
}

/**
 * Load required files for widgets
 */
require get_stylesheet_directory() . '/inc/widgets/editorial-featured-slider.php';