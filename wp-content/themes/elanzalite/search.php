<?php
/**
* The template for displaying Search Results pages

*/
get_header();
$layout = elanzalite_page_get_layout();
?>
</div>
<div class="container" class="clearfix">
<div class="page-title breadcrumb">
	<h1><?php printf( __( 'Search Results for: %s', 'elanzalite' ), get_search_query() ); ?></h1>
</div>
</div>
<div id="page" class="clearfix <?php echo esc_attr($layout); ?>" >
<div class="content"><!-- Content Start -->
<?php if (have_posts()) : ?>
<?php $grid_layout = get_theme_mod('elanzalite_listing_layout','two-grid-layout');
 ?>
<div id="main">
	<ul class="<?php esc_attr($grid_layout); ?>">
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
		?>
	</ul>
	<div class="pagination">
		<div class="older"><?php next_posts_link( __('Older Posts','elanzalite') ); ?></div>
		<div class="newer"><?php previous_posts_link( __('Newer Posts','elanzalite') ); ?></div>
	</div>
</div>
	<?php
	else :
	// If no content, include the "No posts found" template.
	get_template_part( 'content', 'none' );
	endif;
	?>
</div>
<div class="sidebar-wrapper">
<?php get_sidebar(); ?>
</div>
</div>
<!-- / Content End -->
<?php get_footer(); ?>