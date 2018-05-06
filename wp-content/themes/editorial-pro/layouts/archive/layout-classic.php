<?php
/**
 * Template part for displaying archive loop post in classic layout.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Mystery Themes
 * @subpackage Editorial Pro
 * @since 1.0.0
 */
$post_id = get_the_ID();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
		$get_post_format = get_post_format();

		$post_featured_video 	= get_post_meta( $post_id, 'editorial_post_featured_video', true );
		$post_embed_audio 		= get_post_meta( $post_id, 'editorial_post_embed_audio', true );
		$post_embed_gallery 	= get_post_meta( $post_id, 'post_images', true );

		if( $get_post_format == 'video' && !empty( $post_featured_video ) ) {
	?>
			<div class="single-post-video fitvids-video">
				<div class="video-wrap">
	                <?php echo wp_oembed_get( $post_featured_video ); ?>
	            </div><!-- .video-wrap -->
			</div><!-- .single-post-video -->
	<?php } elseif( $get_post_format == 'audio' ) { ?>
			<div class="single-post-image">
				<figure>
					<?php  
						if( has_post_thumbnail() ) {
							the_post_thumbnail( 'editorial-single-large' );
						} else {
							$image_src = editorial_pro_image_fallback( 'editorial-single-large' );
							$image_path = '<img src="'. $image_src[0] .'"/>';
						}
					?>
				</figure>
			</div><!-- .single-post-image -->
			<?php if( !empty( $post_embed_audio ) ) { ?>
				<div class="post-audio"><?php echo do_shortcode( '[audio src="'.$post_embed_audio. '"]' ); ?></div>
			<?php } ?>
	<?php } elseif( $get_post_format == 'gallery' && !empty( $post_embed_gallery ) ) { ?>
			<div class="post-gallery-wrapper">
				<ul class="embed-gallery cS-hidden">
					<?php 
						foreach ( $post_embed_gallery as $key => $value ) {
							$image_id = editorial_pro_get_image_id_from_url( $value );
							$image_path = wp_get_attachment_image_src( $image_id, 'editorial-single-large', true );
							$full_image_path = wp_get_attachment_image_src( $image_id, 'full', true );
					?>
							<li><a href="<?php echo esc_url( $full_image_path[0] ); ?>" rel="prettyPhoto[embed-img]"><img src="<?php echo esc_url( $image_path[0] ); ?>" /></a></li>
					<?php
						}
					?>
				</ul>
			</div><!-- .post-gallery-wrapper -->
	<?php } else { ?>
			<div class="single-post-image">
				<a href="<?php the_permalink();?>" title="<?php the_title();?>">
					<figure>
						<?php 
							if( has_post_thumbnail() ) {
								the_post_thumbnail( 'editorial-single-large' );
							} else {
								$image_src = editorial_pro_image_fallback( 'editorial-single-large' );
								$image_path = '<img src="'. $image_src[0] .'"/>';
							}
						?>
					</figure>
				</a>
			</div><!-- .single-post-image -->
	<?php } ?>

	<div class="archive-desc-wrapper clearfix">
		<header class="entry-header">
			<?php
				do_action( 'editorial_pro_post_categories' );
				the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
			?>
		</header><!-- .entry-header -->	
		<div class="entry-content">
			<?php
				$excerpt_length = get_theme_mod( 'archive_excerpt_length', '70' );
				$post_content = get_the_content();
				echo wp_trim_words( $post_content, $excerpt_length, '' );
			?>
		</div><!-- .entry-content -->

		<?php editorial_pro_archive_readmore(); ?>

		<footer class="entry-footer">
			<div class="entry-meta">
				<?php 
					editorial_pro_posted_on();
					editorial_pro_post_comment();
				?>
			</div><!-- .entry-meta -->
			<?php editorial_pro_entry_footer(); ?>
		</footer><!-- .entry-footer -->
	</div><!-- .archive-desc-wrapper -->
</article><!-- #post-## -->