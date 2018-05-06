<?php
/**
 * Template part for displaying posts.
 *
 * @package Wiral
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('item has-post-thumbnail'); ?>>
	<header class="entry-header">
		<?php the_title( sprintf( '<h2 class="entry-title" itemprop="headline"><a href="%s" rel="bookmark" title="%s">', esc_url( get_permalink() ), get_the_title() ), '</a></h2>' ); ?>
	</header><!-- .entry-header -->
	<div class="thumbnail">
	<?php if ( has_post_thumbnail() ) : ?>
		<a href="<?php the_permalink() ?>" rel="bookmark"><?php the_post_thumbnail('wiral-lite-home-thumbnail'); ?></a>
	<?php else : ?>
		<a href="<?php the_permalink() ?>" rel="bookmark"><img src="<?php echo esc_url( get_template_directory_uri() ) ?>/images/default-thumbnail.jpg" /></a>
	<?php endif; ?>
	</div>
	<div class="item-description" itemprop="text">
		<div class="entry-content">
			<?php the_excerpt(); ?>
			<?php
				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'wiral-lite' ),
					'after'  => '</div>',
				) );
			?>
		</div><!-- .entry-content -->
	</div>
	<footer class="entry-footer clearfix">
		<div class="entry-meta-inner">
		
			<?php wiral_lite_post_meta(); ?>
		
			<?php edit_post_link('<i class="fa fa-pencil-square-o"></i>', '<span class="edit-link">', '</span>' ); ?>
		</div>
		<a class="read-more-btn" href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php esc_html_e( 'Read more', 'wiral-lite' ); ?></a>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
