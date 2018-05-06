<?php
/**
* The Index template file.
*
* This is the most generic template file in a WordPress theme
* and one of the two required files for a theme (the other being style.css).
* It is used to display a page when nothing more specific matches a query.
* E.g., it puts together the home page when no home.php file exists.
*
*/
get_header();
$layout = elanzalite_blog_get_layout(); 
?>
	<!-- Main Heading Start -->
	<div class="slider-wrapper">
 				<?php  if ( shortcode_exists( 'themehunk-customizer-elanzalite' ) ){
                    do_shortcode( '[themehunk-customizer-elanzalite page="cate-slider"]' );
                 }  ?>		
    </div> <!-- Main Heading End -->
	</div> 
	<div id="page" class="clearfix <?php echo esc_attr($layout);?>" >
		<div class="content-wrapper clearfix">
			<div class="content">  <!-- right -->
			<main id="main" class="site-main" role="main">
			<?php if ( have_posts() ) :
			$grid_layout = get_theme_mod('elanzalite_listing_layout','two-grid-layout');
			?>
			<ul class="load_post <?php echo esc_attr($grid_layout); ?>">
				<?php
				while ( have_posts() ) : the_post();
					if($grid_layout == 'standard-layout'):
							// Start the post formate loop.
					get_template_part( 'content', get_post_format() );
					else :
						// Start the post formate grid.
					get_template_part( 'content', 'grid');
					endif;
				endwhile;
				?>
			</ul>
			<div class="clearfix"></div>
			<?php
				elanzalite_pagination();
			else :
				// If no content, include the "No posts found" template.
				get_template_part( 'content', 'none' );
			endif;
			?>
			</main><!-- .site-main -->
			<div class="clearfix"></div>
		</div>
		<?php if($layout!='no-sidebar'): ?>
<div class="sidebar-wrapper">
<?php get_sidebar(); ?>
</div>
<?php endif; ?>
</div>
</div>
<!-- / content-wrapper end -->
<?php get_footer(); ?>