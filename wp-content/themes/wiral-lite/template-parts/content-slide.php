<?php
/**
 * The template part for displaying a message that posts cannot be found.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package wiral
 */
?>
		  
<?php
		$args = array( 		
				'posts_per_page' => 6
			);
		$slide_posts = new WP_Query( $args );
		$exclude_posts = array();

		?>
	
		<?php if ( $slide_posts->have_posts() ) : ?>
			<div dir="rtl" class="slider single-item-rtl">
				<?php /* Start the Loop */ ?>
				<?php 
				while ( $slide_posts->have_posts() ) : $slide_posts->the_post();

					array_push($exclude_posts, get_the_ID());

					if (has_post_thumbnail()) :
				 	?>
					<div>
						<a href="<?php the_permalink() ?>">
							<?php the_post_thumbnail('wiral-lite-homepage-thumb-slider') ?>
						</a>
						<p class="banner-caption"><a href="<?php the_permalink() ?>"><?php the_title() ?></a></p>
					</div>
					<?php else : ?>
					<div>
						<a href="<?php the_permalink() ?>">
							<img src="<?php echo esc_url( get_template_directory_uri() ) ?>/images/default-slide.jpg" alt="<?php the_title() ?>" />
						</a>
						<p class="banner-caption"><a href="<?php the_permalink() ?>"><?php the_title() ?></a></p>
					</div>
				<?php 
					endif;
				endwhile; 
				?>
			</div>
		<?php else : ?>
			<?php get_template_part( 'content', 'none' ); ?>
		<?php endif; ?>

		<?php wp_reset_postdata(); ?>
