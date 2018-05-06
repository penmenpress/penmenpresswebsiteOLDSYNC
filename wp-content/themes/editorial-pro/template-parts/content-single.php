<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Mystery Themes
 * @subpackage Editorial Pro
 * @since 1.0.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php do_action( 'editorial_pro_post_categories' ); ?>
		<h1 class="entry-title"><?php the_title();?></h1>
		<div class="entry-meta">
			<?php 
				editorial_pro_posted_on();
$post_view_count = editorial_get_post_views( get_the_ID() );
			echo '<span class="post-view">'. $post_view_count .'</span>';
editorial_pro_post_comment();
			?>
		
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->
	
	<?php if( has_post_thumbnail() ) { ?>
		<div class="single-post-image">
			<figure><?php the_post_thumbnail( 'editorial-single-large' ); ?></figure>
<?php endif; ?>

		</div><!-- .single-post-image -->
	<?php } ?>

	<div class="entry-content">
		<?php
			the_content( sprintf(
				/* translators: %s: Name of current post. */
				wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'editorial-pro' ), array( 'span' => array( 'class' => array() ) ) ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			) );

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'editorial-pro' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php editorial_pro_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
