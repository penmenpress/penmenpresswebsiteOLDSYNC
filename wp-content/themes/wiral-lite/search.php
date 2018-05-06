<?php
/**
 * The template for displaying search results pages.
 *
 * @package wiral
 */

get_header(); ?>

	<div id="primary" class="content-area posts-section">
		<main id="main" class="site-main" role="main">
			<?php if ( have_posts() ) : ?>
			<header class="page-header">
				<h1 class="page-title"><?php printf( esc_html__( 'Search Results for: %s', 'wiral-lite' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
			</header><!-- .page-header -->

			<?php /* Start the Loop */ ?>
			<div class="post-items clearfix">
				<?php while ( have_posts() ) : the_post(); ?>

					<?php
						/**
						 * Run the loop for the search to output the results.
						 * If you want to overload this in a child theme then include a file
						 * called content-search.php and that will be used instead.
						 */
						get_template_part( 'template-parts/content', get_post_format() );
					?>

				<?php endwhile; ?>

			</div>

			<?php else : ?>

				<?php get_template_part( 'template-parts/content', 'none' ); ?>

			<?php endif; ?>
			
			<?php wiral_lite_the_posts_navigation(); ?>
		</main><!-- #main-->
	</div> <!-- #primary -->
<?php get_footer(); ?>