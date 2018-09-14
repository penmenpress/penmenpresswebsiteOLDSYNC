<?php
/**
 * Template part for displaying post layout one.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Mystery Themes
 * @subpackage Editorial Pro
 * @since 1.0.0
 */

$post_id = get_the_ID();
?>
<header class="entry-header">
	<?php 
		editorial_breadcrumbs();
		do_action( 'editorial_pro_post_categories' );
	?>
	<h1 class="entry-title"><?php the_title();?></h1>
	<div class="entry-meta">
		<?php 
			editorial_pro_posted_on();
			editorial_post_views_count();
			editorial_pro_post_comment();
		?>
	</div><!-- .entry-meta -->
</header><!-- .entry-header -->
<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
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
					<figure>
						<?php 
							if( has_post_thumbnail() ) {
								the_post_thumbnail( 'editorial-single-large' );
						?> <p>
						<?php
							echo apply_filters( class="post_thumbnail_caption",'the_post_thumbnail_caption', get_the_post_thumbnail_caption( $post ) );
							?>
									</p> <?php
							} else {
								$image_src = editorial_pro_image_fallback( 'editorial-single-large' );
								$image_path = '<img src="'. $image_src[0] .'"/>';
							}
						?>
						
					</figure>
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

			<?php
				//Review section
				do_action( 'editorial_single_post_review' );
				
				//post author box
				do_action( 'editorial_pro_author_box' );

				//related articles
				do_action( 'editorial_related_articles' );


				the_post_navigation();

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
			?>
		</article><!-- #post-## -->
	</main><!-- #main -->
</div><!-- #primary -->
<?php
	/**
	 * get editorial sidebar
	 */
	editorial_pro_sidebar();