<?php
/**
* The template for displaying all pages
*
* This is the template that displays all pages by default.
* Please note that this is the WordPress construct of pages and that
* other 'pages' on your WordPress site will use a different template.
*/
get_header();
$layout = elanzalite_page_get_layout();
?>
</div>
<div id="page" class="clearfix <?php echo esc_attr($layout); ?>">
<div class="content"><!-- Content Start -->
<div class="page-content"><!-- blog-single -->
<?php if (have_posts()) : while (have_posts()) : the_post();?>
<div class="page-title"><h1><?php the_title(); ?></h1></div>
<div class="page-description">
	<?php the_content(); ?>
</div>
<div class="multipage-links">
	<?php
		wp_link_pages( array(
					'before'      => '<span class="meta-nav">' . __( 'Pages:', 'elanzalite' ) . '</span>',
					'after'       => '',
					'link_before' => '<span class="active">',
					'link_after'  => '</span>',
				) );
	?>
</div>
<?php
// If comments are open or we have at least one comment, load up the comment template.
if ( comments_open() || get_comments_number() ) {
comments_template();
}
endwhile;
endif;
?>
</div>
</div>
<!-- / Content End -->
<?php if($layout!='no-sidebar'): ?>
<div class="sidebar-wrapper">
<?php get_sidebar(); ?>
</div>
<?php endif; ?>
</div>
<?php get_footer(); ?>