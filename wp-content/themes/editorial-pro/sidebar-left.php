<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Mystery Themes
 * @subpackage Editorial Pro
 * @since 1.0.0
 */

if ( ! is_active_sidebar( 'editorial_pro_left_sidebar' ) ) {
	return;
}
?>

<aside id="secondary" class="widget-area" role="complementary">
	<?php
	
		/**
		 *add extra hook before sidebar which helps to developer
		 */
		do_action( 'editorial_pro_before_sidebar' );

		/**
		 * Widget area about left sidebar 
		 */
		dynamic_sidebar( 'editorial_pro_left_sidebar' );

		/**
		 *add extra hook after sidebar which helps to developer
		 */
		do_action( 'editorial_pro_after_sidebar' );
	?>
</aside><!-- #secondary -->
