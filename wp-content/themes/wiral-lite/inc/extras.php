<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package wiral
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function wiral_lite_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	return $classes;
}
add_filter( 'body_class', 'wiral_lite_body_classes' );


if ( ! function_exists( 'wiral_lite_excerpt_length' ) ) {

	function wiral_lite_excerpt_length( $length ) {

		$theme_options = wiral_lite_theme_options();


		$number = intval ($theme_options['excerpt_length']) > 0 ?  intval ($theme_options['excerpt_length']) : $length;
		return $number;
	}
}

add_filter( 'excerpt_length', 'wiral_lite_excerpt_length', 999 );

if ( ! function_exists( 'wiral_lite_excerpt_more' ) ) {

	function wiral_lite_excerpt_more( $more ) {

		$theme_options = wiral_lite_theme_options();

		return esc_html($theme_options['excerpt_more']);
	}
}
add_filter('excerpt_more', 'wiral_lite_excerpt_more');



/**
|------------------------------------------------------------------------------
| Related Posts
|------------------------------------------------------------------------------
|
| You can show related posts by Categories or Tags. 
| It has two options to show related posts
|
| 1. Thumbnail related posts (default)
| 2. List of related posts
| 
| @return void
|
*/
if (! function_exists('wiral_lite_related_posts') ):
	function wiral_lite_related_posts() {
		global $post;

		$theme_options = wiral_lite_theme_options();

		$taxonomy = $theme_options['related_posts'];

		$args =  array();

		if ($taxonomy == 'tag') {

			$tags = wp_get_post_tags($post->ID);
			$arr_tags = array();
			foreach($tags as $tag) {
				array_push($arr_tags, $tag->term_id);
			}
			
			if (!empty($arr_tags)) { 
			    $args = array(  
				    'tag__in' => $arr_tags,  
				    'post__not_in' => array($post->ID),  
				    'posts_per_page'=> 4,
			    ); 
			}

		} else {

			 $args = array( 
			 	'category__in' => wp_get_post_categories($post->ID), 
			 	'posts_per_page' => 4, 
			 	'post__not_in' => array($post->ID) 
			 );

		}

		if (! empty($args) ) {
			$posts = get_posts($args);

			if ($posts) {
				
				?>
			<h2 class="title-related-posts"><?php _e('You may also enjoy...', 'wiral-lite') ?></h2>
				<ul class="related grid clearfix">
				<?php
				foreach ($posts as $p) {
					?>
					<li>
						<div class="related-entry">
							<?php if (has_post_thumbnail($p->ID)) : ?>
							<div class="thumbnail">
								<a href="<?php echo esc_url (get_the_permalink($p->ID) ) ?>">
								<?php echo get_the_post_thumbnail($p->ID, 'wiral-lite-home-thumbnail') ?>
								</a>
							</div>
							<?php else : ?>
							<div class="thumbnail">
								<a href="<?php echo esc_url ( get_the_permalink($p->ID) ) ?>"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/default-thumbnail.jpg"></a>
							</div>
							<?php endif; ?>
							<h2 class="entry-title"><a href="<?php echo esc_url (get_the_permalink($p->ID) ) ?>"><?php echo esc_html( get_the_title($p->ID) ) ?></a></h2>
						</div>
					</li>
					<?php
				}
				?>
				</ul>
				<?php
			
			}
		}
	}
endif;


/**
|------------------------------------------------------------------------------
| Custom Post Meta
|------------------------------------------------------------------------------
|
*/
function wiral_lite_post_meta() {


	?>	
	<div class="entry-meta">
		<div class="entry-footer-left">
		
		
			<?php
				if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
					echo '<span class="comments-link"><i class="fa fa-comment"></i> ';
					comments_popup_link( esc_html__( '0', 'wiral-lite' ), esc_html__( '1', 'wiral-lite' ), esc_html__( '%', 'wiral-lite' ) );
					echo '</span>';
				}
			?>
		</div>
	</div>
	<?php
}