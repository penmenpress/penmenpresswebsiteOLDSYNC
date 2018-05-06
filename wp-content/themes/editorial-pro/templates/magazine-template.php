<?php
/**
 * Template Name: Magazine Page
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Mystery Themes
 * @subpackage Editorial Pro
 * @since 1.0.0
 */
get_header();
?>
	
	<div class="mt-container">
		<div class="featured-slider-section clearfix">
			<?php
	        	if( is_active_sidebar( 'editorial_pro_home_slider_area' ) ) {
	            	if ( !dynamic_sidebar( 'editorial_pro_home_slider_area' ) ):
	            	endif;
	         	}
	        ?>
		</div><!-- .featured-slider-section -->
	</div><!-- .mt-container -->

<div class="mt-container">
	<div class="home-top-content-wrapper clearfix">
		<div class="home-primary-wrapper home-top-primary">
			<div class="theiaStickySidebar">
				<?php
		        	if( is_active_sidebar( 'editorial_pro_home_top_content_area' ) ) {
		            	if ( !dynamic_sidebar( 'editorial_pro_home_top_content_area' ) ):
		            	endif;
		         	}
		        ?>
	        </div><!-- .theiaStickySidebar -->
		</div><!-- .home-primary-wrapper -->
		<div class="home-secondary-wrapper home-top-secondary">
			<div class="theiaStickySidebar">
				<?php
		        	if( is_active_sidebar( 'editorial_pro_home_top_sidebar_area' ) ) {
		            	if ( !dynamic_sidebar( 'editorial_pro_home_top_sidebar_area' ) ):
		            	endif;
		         	}
		        ?>
	        </div><!-- .theiaStickySidebar -->
		</div><!-- .home-secondary-wrapper -->
	</div><!-- .home-top-content-wrapper -->

	<div class="home-top-fullwidth-content-wrapper">
		<?php
        	if( is_active_sidebar( 'editorial_pro_home_top_fullwidth_area' ) ) {
            	if ( !dynamic_sidebar( 'editorial_pro_home_top_fullwidth_area' ) ):
            	endif;
         	}
        ?>
	</div><!-- .home-top-fullwidth-content-wrapper -->

	<div class="home-bottom-content-wrapper clearfix">
		<div class="home-primary-wrapper home-bottom-primary">
			<div class="theiaStickySidebar">
				<?php
		        	if( is_active_sidebar( 'editorial_pro_home_bottom_content_area' ) ) {
		            	if ( !dynamic_sidebar( 'editorial_pro_home_bottom_content_area' ) ):
		            	endif;
		         	}
		        ?>
		    </div><!-- .theiaStickySidebar -->
		</div><!-- .home-primary-wrapper -->
		<div class="home-secondary-wrapper home-bottom-secondary">
			<div class="theiaStickySidebar">
				<?php
		        	if( is_active_sidebar( 'editorial_pro_home_bottom_sidebar_area' ) ) {
		            	if ( !dynamic_sidebar( 'editorial_pro_home_bottom_sidebar_area' ) ):
		            	endif;
		         	}
		        ?>
		    </div><!-- .theiaStickySidebar -->
		</div><!-- .home-secondary-wrapper -->
	</div><!-- .home-bottom-content-wrapper -->

	<div class="home-bottom-fullwidth-content-wrapper">
		<?php
        	if( is_active_sidebar( 'editorial_pro_home_bottom_fullwidth_area' ) ) {
            	if ( !dynamic_sidebar( 'editorial_pro_home_bottom_fullwidth_area' ) ):
            	endif;
         	}
        ?>
	</div><!-- .home-bottom-fullwidth-content-wrapper -->
</div><!-- .mt-container -->

<?php
get_footer();