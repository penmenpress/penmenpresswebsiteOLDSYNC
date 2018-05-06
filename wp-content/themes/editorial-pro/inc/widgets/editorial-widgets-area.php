<?php
/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 *
 * @package Mystery Themes
 * @subpackage Editorial Pro
 * @since 1.0.0
 */

/*--------------------------------------------------------------------------------------------------------------------*/
/**
 * Register required widget areas
 */

function editorial_pro_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'editorial-pro' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Left Sidebar', 'editorial-pro' ),
		'id'            => 'editorial_pro_left_sidebar',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Header Ads', 'editorial-pro' ),
		'id'            => 'editorial_pro_header_ads_area',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'HomePage Slider Area', 'editorial-pro' ),
		'id'            => 'editorial_pro_home_slider_area',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'HomePage Top Content Area', 'editorial-pro' ),
		'id'            => 'editorial_pro_home_top_content_area',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'HomePage Top Sidebar Area', 'editorial-pro' ),
		'id'            => 'editorial_pro_home_top_sidebar_area',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'HomePage Top Fullwidth Area', 'editorial-pro' ),
		'id'            => 'editorial_pro_home_top_fullwidth_area',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'HomePage Bottom Content Area', 'editorial-pro' ),
		'id'            => 'editorial_pro_home_bottom_content_area',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'HomePage Bottom Sidebar Area', 'editorial-pro' ),
		'id'            => 'editorial_pro_home_bottom_sidebar_area',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'HomePage Bottom Fullwidth Area', 'editorial-pro' ),
		'id'            => 'editorial_pro_home_bottom_fullwidth_area',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer 1st Column', 'editorial-pro' ),
		'id'            => 'editorial_pro_footer_one',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer 2nd Column', 'editorial-pro' ),
		'id'            => 'editorial_pro_footer_two',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer 3rd Column', 'editorial-pro' ),
		'id'            => 'editorial_pro_footer_three',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer 4th Column', 'editorial-pro' ),
		'id'            => 'editorial_pro_footer_four',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

}
add_action( 'widgets_init', 'editorial_pro_widgets_init' );

/*--------------------------------------------------------------------------------------------------------------------*/
/**
 * Register widgets
 */
add_action( 'widgets_init', 'editorial_pro_register_widgets' );

function editorial_pro_register_widgets() {
	
	// Ads banner
	register_widget( 'Editorial_Pro_Ads_Banner' );

	// Block Column
	register_widget( 'Editorial_Pro_Block_Column' );

	// Block Grid
	register_widget( 'Editorial_Pro_Block_Grid' );

	// Block Layout
	register_widget( 'Editorial_Pro_Block_Layout' );

	// Block List
	register_widget( 'Editorial_Pro_Block_List' );

	// Carousel
	register_widget( 'Editorial_Pro_Carousel' );

	// Default Tabbed
	register_widget( 'Editorial_Pro_Default_Tabbed' );

	// Featured Slider
	register_widget( 'Editorial_Pro_Featured_slider' );

	// Fullwidth Tabbed
	register_widget( 'Editorial_Pro_Fullwidth_Tabbed' );

	// Post List
	register_widget( 'Editorial_Pro_Posts_List' );

	// Show Video
	register_widget( 'Editorial_Pro_Show_Video' );
}

/*--------------------------------------------------------------------------------------------------------------------*/
/**
 * Load widget required files
 */
require get_template_directory() . '/inc/widgets/editorial-widget-fields.php';
require get_template_directory() . '/inc/widgets/editorial-featured-slider.php';
require get_template_directory() . '/inc/widgets/editorial-block-grid.php';
require get_template_directory() . '/inc/widgets/editorial-block-column.php';
require get_template_directory() . '/inc/widgets/editorial-ads-banner.php';
require get_template_directory() . '/inc/widgets/editorial-block-layout.php';
require get_template_directory() . '/inc/widgets/editorial-posts-list.php';
require get_template_directory() . '/inc/widgets/editorial-block-list.php';
require get_template_directory() . '/inc/widgets/editorial-default-tabbed.php';
require get_template_directory() . '/inc/widgets/editorial-carousel.php';
require get_template_directory() . '/inc/widgets/editorial-show-video.php';
require get_template_directory() . '/inc/widgets/editorial-fullwidth-tabbed.php';