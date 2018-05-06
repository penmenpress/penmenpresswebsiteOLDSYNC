<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package wiral
 */
$theme_options = wiral_lite_theme_options();

get_header(); ?>

	<?php if( $theme_options['slider'] == 1 && (is_home() || is_front_page()) && is_paged() == false) : ?>
	<div class="banner-section clearfix">
		<div class="banner">
			<?php get_template_part( 'template-parts/content', 'slide' ); ?>
		</div>
		<div class="sidebar-home">
			<?php get_sidebar('home'); ?>
		</div>
	</div>

	<?php if(has_nav_menu('category'))  : ?>
	<div id="secondary-menu" class="category-navigation" role="navigation">
		<a class="mobile-only toggle-mobile-menu" href="#"><?php _e('Category Menu', 'wiral-lite'); ?> <i class="fa fa-angle-down"></i></a>
		<?php wp_nav_menu( array( 'theme_location' => 'category', 'menu_class' => 'menu clearfix' ) ); ?>
	</div>
	<?php endif; ?>

	<?php endif; ?>
	
	<div id="primary" class="content-area posts-section">
		<main id="main" class="site-main clearfix" role="main">
			<div class="post-items clearfix">
			<?php if ( have_posts() ) : ?>

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php

					/*
					 * Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					
						get_template_part( 'template-parts/content', get_post_format());

				?>

			<?php endwhile; ?>
			<?php else : ?>

				<?php get_template_part( 'template-parts/content', 'none' ); ?>

			<?php endif; ?>
			</div>
		</main>
	</div>
	<?php wiral_lite_the_posts_navigation(); ?>
<?php get_footer(); ?>