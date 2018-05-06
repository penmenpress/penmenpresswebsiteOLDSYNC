<?php
/**
* The template for displaying Category pages.
*
* Used to display archive-type pages if nothing more specific matches a query.
* For example, puts together date-based pages if no date.php file exists.
*
*/
?>
<?php get_header();
$layout = elanzalite_blog_get_layout();?>
</div>
<div class="clearfix"></div>
<?php if (have_posts()) :
$grid_layout = get_theme_mod('elanzalite_listing_layout','two-grid-layout');
?>
<div id="page" class="clearfix <?php echo esc_attr($layout); ?>" >
<div class="container">
	<div class="archive-title">		
		<?php the_archive_title('<h1>','</h1>');?>
	</div>
</div>
<!-- Content Start -->
<div class="content">
	<div id="main">
		<ul class="<?php echo esc_attr($grid_layout); ?>">
			<?php
			while ( have_posts() ) : the_post();
				if($grid_layout=='standard-layout'):
						// Start the post formate loop.
				get_template_part( 'content', get_post_format() );
				else :
					// Start the post formate grid.
				get_template_part( 'content', 'grid' );
				endif;
			endwhile;
			// If no content, include the "No posts found" template.
			?>
		</ul>
	</div>
	<?php
	elanzalite_pagination();
	else :
	get_template_part( 'content', 'none' );
	endif;
	?>
</div>
<!-- left -->
<?php if($layout!='no-sidebar'): ?>
<div class="sidebar-wrapper">
<?php get_sidebar(); ?>
</div>
<?php endif; ?>
</div>
<!-- Content End -->
<?php get_footer(); ?>