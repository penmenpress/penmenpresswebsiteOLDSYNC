<?php
/**
* The template for displaying archive pages
* If you'd like to further customize these archive views, you may create a
*
*/
?>
<?php get_header();
$layout = elanzalite_blog_get_layout();
?>
</div>
<?php if (have_posts()) : ?>
<div id="page" class="clearfix <?php echo esc_attr( $layout); ?>">
<div class="container" class="clearfix">
	<div class="archive-title">
		<?php the_archive_title('<h1>','</h1>');?>
	</div>
</div>
<!-- Content Start -->
<div class="content">
<?php $grid_layout = get_theme_mod('elanzalite_listing_layout','two-grid-layout');
	?>
	<div id="main">
		<ul class="<?php echo esc_attr( $grid_layout); ?>">
			<?php
			while ( have_posts() ) : the_post();
				if($grid_layout=='standard-layout'):
				get_template_part( 'content', get_post_format() );
				else :
					// Start the post formate grid.
				get_template_part( 'content', 'grid' );
				endif;
			endwhile;
			?>
		</ul>
	</div>
	<?php
	else :
	get_template_part( 'content', 'none' );
	endif;
	?>
</div>
<?php if($layout!='no-sidebar'): ?>
<div class="sidebar-wrapper">
<?php get_sidebar(); ?>
</div>
<?php endif; ?>
</div><!-- Content End -->
<?php get_footer(); ?>