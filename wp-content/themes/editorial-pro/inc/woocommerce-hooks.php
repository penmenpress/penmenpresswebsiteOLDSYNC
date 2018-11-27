<?php
/**
 * Define new and managed hook about woocommerce
 *
 * @package Mystery Themes
 * @subpackage Editorial Pro
 * @since 1.0.4
 */

/**
 * Managed woocommerce breadcrumbs
 */

$bread_option = get_theme_mod( 'editorial_breadcrumbs_option', 'show' );
if (  $bread_option == 'hide' ) {
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
}

add_filter( 'woocommerce_breadcrumb_defaults', 'mt_change_breadcrumb_home_text' );
function mt_change_breadcrumb_home_text( $defaults ) {
    $home_value = get_theme_mod( 'editorial_bread_home', __( 'Home', 'editorial-pro' ) );
	$defaults['home'] = $home_value;
	return $defaults;
}

add_filter( 'woocommerce_breadcrumb_defaults', 'mt_change_breadcrumb_delimiter' );
function mt_change_breadcrumb_delimiter( $defaults ) {
	$sep_value = get_theme_mod( 'editorial_bread_sep', '>' );
	$defaults['delimiter'] = $sep_value;
	return $defaults;
}

/**
 * Change woocommerce page title
 */
add_filter( 'woocommerce_show_page_title', 'woocommerce_show_page_title_callback' );

function woocommerce_show_page_title_callback() {
?>
	<h1 class="entry-title"><?php woocommerce_page_title(); ?></h1>
<?php
}

/**
 * Add starter wrapper before main content
 */
add_action( 'woocommerce_before_main_content', 'add_start_wrapper_before_main_content', 5 );

function add_start_wrapper_before_main_content() {
	echo '<div id="primary" class="content-area">';
	echo '<main id="main" class="site-main" role="main">';
}

/**
 * Add end wrapper before main content
 */
add_action( 'woocommerce_after_main_content', 'add_end_after_main_content', 15 );
function add_end_after_main_content() {
	echo '</main>';
	echo '</div>';
}

/**
 * Change number or products per row to 3
 */
add_filter( 'loop_shop_columns', 'editorial_pro_loop_columns' );
if( ! function_exists( 'editorial_pro_loop_columns' ) ) {
	function editorial_pro_loop_columns() {
		return 3; // 3 products per row
	}
}

/**
 * Change number of related products on product page
 */
add_filter( 'woocommerce_output_related_products_args', 'editorial_pro_related_products_args' );
if( ! function_exists( 'editorial_pro_related_products_args' ) ) {
	function editorial_pro_related_products_args( $args ) {
        $args['posts_per_page'] = 3; // 3 related products
        $args['columns'] = 3; // arranged in 3 columns
        return $args;
	}
}

/**
 * Added class on body about columns
 */
add_action( 'body_class', 'editorial_pro_woo_body_class' );
if( ! function_exists( 'editorial_pro_woo_body_class' ) ) {
	function editorial_pro_woo_body_class( $class ) {
        $class[] = 'columns-'.editorial_pro_loop_columns();
        return $class;
	}
}

/**
 * Modified posts per page at shop
 */
add_filter( 'loop_shop_per_page', 'editorial_pro_products_per_page', 20 );
if( !function_exists( 'editorial_pro_products_per_page' ) ) {
    function editorial_pro_products_per_page() {
        return 12;
    }
}