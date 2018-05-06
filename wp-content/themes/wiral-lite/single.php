<?php
/**
 * The template for displaying all single posts.
 *
 * @package wiral
 */


get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'template-parts/content', 'single' ); ?>
		</main><!-- #main -->

		
			<?php

			the_post_navigation( array(
	            'prev_text'                  => __( '<i class="fa fa-angle-double-left"></i> %title', 'wiral-lite' ),
	            'next_text'                  => __( '%title <i class="fa fa-angle-double-right"></i>', 'wiral-lite' ),
	            'in_same_term'               => true,
	        ) );

			?>	

		<?php
			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;
			
		?>
		
		<?php endwhile; // End of the loop. ?>
	</div><!-- #primary -->

	<?php
	get_sidebar();
	?>

	<div class="related-posts clearfix">
		<?php wiral_lite_related_posts() ?>
	</div>
<?php get_footer(); ?>
