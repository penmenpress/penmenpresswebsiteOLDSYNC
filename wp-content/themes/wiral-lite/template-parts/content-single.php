<?php
/**
 * Template part for displaying single posts.
 *
 * @package Wiral
 */

//$theme_options = wiral_lite_theme_options();

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<?php if ( has_post_thumbnail() ) : ?>
			<div class="thumbnail">
				<?php the_post_thumbnail('wiral-lite-homepage-thumb-slider'); ?>
			</div>
		<?php endif; ?>
	
	<div class="content-wrap">
		<header class="entry-header clearfix">
			<?php the_title( '<h1 class="entry-title" itemprop="headline">', '</h1>' ); ?>
			<div class="entry-meta">
				<?php wiral_lite_posted_on(); ?>
			</div><!-- .entry-meta -->
		</header><!-- .entry-header -->
		<div class="entry-content" itemprop="text">
			<?php the_content(); ?>
			<?php
				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'wiral-lite' ),
					'after'  => '</div>',
				) );
			?>
		</div><!-- .entry-content -->
	</div>
	<footer class="entry-footer clearfixw">	
		<?php wiral_lite_entry_footer() ?>
		
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->