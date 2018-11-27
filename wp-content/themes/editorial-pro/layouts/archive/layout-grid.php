<?php
/**
 * Template part for displaying archive loop post in columns layout.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Mystery Themes
 * @subpackage Editorial Pro
 * @since 1.0.0
 */

global $wp_query;

$post_count = $wp_query->current_post;
$total_post_count = $wp_query->found_posts;

if( $post_count % 5 == 0 ) {
	$article_layout = 'classic-post';
	echo '<div class="archive-classic-post-wrapper">';
} else {
	if( $post_count == 1 || $post_count == 6 ) {
		echo '<div class="archive-grid-post-wrapper">';
	}
	$article_layout = 'grid-post';
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $article_layout ); ?>>
	
	<div class="post-image">
		<a href="<?php the_permalink();?>" title="<?php the_title();?>">
			<figure>
				<?php
					if( has_post_thumbnail() ) {
						the_post_thumbnail( 'editorial-single-large' );
					} else {
						$image_src = editorial_pro_image_fallback( 'editorial-block-medium' );
                        echo '<img src="'. $image_src[0] .'"/>';
					}
				?>
			</figure>
		</a>
	</div>

	<div class="archive-desc-wrapper clearfix">
		<header class="entry-header">
			<?php
				do_action( 'editorial_pro_post_categories' );
				the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
			?>
		</header><!-- .entry-header -->	
		<div class="entry-content">
			<div class="entry-meta">
				<?php 
					editorial_pro_posted_on();
					editorial_pro_post_comment();
				?>
			</div><!-- .entry-meta -->
			<?php
				$excerpt_length = get_theme_mod( 'archive_excerpt_length', '70' );
				$post_content = get_the_content();
				echo wp_trim_words( $post_content, $excerpt_length, '' );
			?>
		</div><!-- .entry-content -->

		<?php editorial_pro_archive_readmore(); ?>

		<footer class="entry-footer">
			<?php editorial_pro_entry_footer(); ?>
		</footer><!-- .entry-footer -->
	</div><!-- .archive-desc-wrapper -->
</article><!-- #post-## -->

<?php
	if( $post_count % 5 == 0 ) {
		echo '</div>';
	} else {
		if( $post_count == 4 || $post_count == 9 || $post_count == $total_post_count-1 ) {
			echo '</div>';
		}
	}
?>