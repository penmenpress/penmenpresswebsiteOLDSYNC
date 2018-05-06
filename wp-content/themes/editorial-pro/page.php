<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Mystery Themes
 * @subpackage Editorial Pro
 * @since 1.0.0
 */

get_header(); ?>

	<?php 
		/**
		 * add extra hook before sidebar which helps to developer
		 */
		do_action( 'editorial_pro_before_body_content' );
	?>
	
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php
			
			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/content', 'page' );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
	/**
	 * get editorial sidebar
	 */
	editorial_pro_sidebar();

	/**
	 * add extra hook after sidebar which helps to developer
	 */
	do_action( 'editorial_pro_after_body_content' );

	/**
	 * Get footer
	 */
	get_footer();
