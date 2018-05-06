<?php
/**
* The Template for displaying all single posts
*/
get_header();
$layout = elanzalite_blog_single_get_layout();
?>
</div>
<div id="page" class="clearfix <?php esc_attr($layout);?>">
<?php elanzalite_breadcrumb(); ?>
<!-- Content Start -->
<div class="content">
	<!-- blog-single -->
	<div class="blog-single">
		<?php if (have_posts()) : while (have_posts()) : the_post();
		get_template_part('content');
		// If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || get_comments_number() ) {
		comments_template();
		}
		endwhile; endif; ?>
	</div>
	<!-- /blog-single -->
</div>
<!-- / Content End -->
<?php if($layout!='no-sidebar'): ?>
<div class="sidebar-wrapper">
<?php get_sidebar(); ?>
</div>
<?php endif; ?>
</div>
<?php get_footer(); ?>