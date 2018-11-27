<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Mystery Themes
 * @subpackage Editorial Pro
 * @since 1.0.0
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php
	/**
	 * editorial pre loader
	 */ 
	do_action( 'editorial_pro_pre_loader' );

	/**
	 * added extra hook before page
	 */
	do_action( 'editorial_pro_before_page' ); 
?>
<div id="page" class="site">
	<?php 
		/**
		 * added extra hook before header
		 */
		do_action( 'editorial_pro_before_header' );
	?>

	<?php
		/**
		 * Call respective header layout files
		 */
		$editorial_pro_header_layout = get_theme_mod( 'editorial_pro_header_layout', 'header_layout_1' );
		if( $editorial_pro_header_layout == 'header_layout_4' ) {
			get_template_part( 'layouts/headers/header', 'layout4' );
		} elseif( $editorial_pro_header_layout == 'header_layout_3' ) {
			get_template_part( 'layouts/headers/header', 'layout3' );
		} elseif( $editorial_pro_header_layout == 'header_layout_2' ) {
			get_template_part( 'layouts/headers/header', 'layout2' );
		} else {
			get_template_part( 'layouts/headers/header', 'layout1' );
		}
	?>
	<?php do_action( 'editorial_pro_after_header' ); ?>
	<?php do_action( 'editorial_pro_before_main' ); ?>

	

	<div id="content" class="site-content">
		<?php
			$container_value = 'show';
			if( is_single() ) {
				global $post;

				$editorial_post_layout = get_post_meta( $post->ID, 'editorial_pro_post_layout', true );
		        if( $editorial_post_layout == 'global_layout' || empty( $editorial_post_layout ) ) {
		            $editorial_post_layout = get_theme_mod( 'editorial_pro_default_post_layout', 'post_layout_1' );
		        }
		        if( is_single() && $editorial_post_layout == 'post_layout_5' ) {
		        	$container_value = 'hide';
		        } else {
		        	$container_value = 'show';
		        }
			}
			if ( !is_page_template( 'templates/magazine-template.php' ) && $container_value == 'show' ) {
				echo '<div class="mt-container">';
			}
		?>