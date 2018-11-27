<?php
/**
 * Define custom hook using in editorial theme
 *
 * @package Mystery Themes
 * @subpackage Editorial Pro
 * @since 1.0.0
 */

/**
 * Function about top header section
 *
 * @since 1.0.0
 */
add_action( 'editorial_pro_top_header', 'editorial_pro_top_header_callback' );

if( ! function_exists( 'editorial_pro_top_header_callback' ) ):
	function editorial_pro_top_header_callback() {
		$top_header_option = get_theme_mod( 'editorial_pro_top_header_option', 'enable' );
		if( $top_header_option == 'disable' ) {
			return;
		}
		$top_header_bg_option = get_theme_mod( 'top_header_bg_option' );
		if( $top_header_bg_option === true ) {
			$bg_option = 'hide-bg';
		} else {
			$bg_option = 'show-bg';
		}
?>
		<div class="top-header-section <?php echo esc_attr( $bg_option ); ?>">
			<div class="mt-container">
				<div class="top-left-header">
					<?php do_action( 'editorial_pro_current_date' ); ?>
					<nav id="top-header-navigation" class="top-navigation" role="navigation">
						<?php wp_nav_menu( array( 'theme_location' => 'top-header', 'container_class' => 'top-menu', 'fallback_cb' => false, 'items_wrap' => '<ul>%3$s</ul>' ) ); ?>
					</nav>
				</div>
				<?php do_action( 'editorial_pro_top_social_icons' ); ?>
			</div> <!-- mt-container end -->
		</div><!-- .top-header-section -->
<?php
	}
endif;

/*------------------------------------------------------------------------------------------------*/
/**
 * Current date at top header
 *
 * @since 1.0.0
 */

add_action( 'editorial_pro_current_date', 'editorial_pro_current_date_hook' );

if( ! function_exists( 'editorial_pro_current_date_hook' ) ):
	function editorial_pro_current_date_hook() {
		$date_option = get_theme_mod( 'editorial_pro_header_date', 'enable' );
		if( $date_option != 'disable' ) {
			$top_header_date_icon_option = get_theme_mod( 'top_header_date_icon_option' );
			if( $top_header_date_icon_option === true ) {
				$icon_option = 'hide-icon';
			} else {
				$icon_option = 'show-icon';
			}
?>
			<div class="date-section <?php echo esc_attr( $icon_option ); ?>">
				<?php echo esc_html( date_i18n( get_option( 'date_format' ) ) ); ?>
			</div>
<?php
		}
	}
endif;

/*------------------------------------------------------------------------------------------------*/
/**
 * Top header social icon section
 *
 * @since 1.0.0
 */
add_action( 'editorial_pro_top_social_icons', 'editorial_pro_top_social_icons_hook' );
if( ! function_exists('editorial_pro_top_social_icons_hook'  ) ):
	function editorial_pro_top_social_icons_hook() {
		$top_social_icons = get_theme_mod( 'editorial_pro_header_social_option', 'enable' );
		if( $top_social_icons != 'disable' ) {
?>
			<div class="top-social-wrapper">
				<?php editorial_pro_social_icons(); ?>
			</div><!-- .top-social-wrapper -->
<?php
		}
	}
endif;

/*------------------------------------------------------------------------------------------------*/
/**
 * Posts Categories with dynamic colors
 *
 * @since 1.0.0
 */
add_action( 'editorial_pro_post_categories', 'editorial_pro_post_categories_hook' );

if( ! function_exists( 'editorial_pro_post_categories_hook' ) ):
	function editorial_pro_post_categories_hook() {

		$categories_option = get_theme_mod( 'editorial_widget_post_categories', 'show' );
		if( $categories_option == 'hide' ) {
			return;
		}

		$categories_type = get_theme_mod( 'post_categories_list_type', 'in_boxed' );
		
		global $post;
		$post_id = $post->ID;
		$categories_list = get_the_category($post_id);
		if( !empty( $categories_list ) ) {
?>
		<div class="post-cat-list">
			<?php 
				foreach ( $categories_list as $cat_data ) {
					$cat_name = $cat_data->name;
					$cat_id = $cat_data->term_id;
					$cat_link = get_category_link( $cat_id );
					if( $categories_type == 'in_boxed' ) {
			?>
						<span class="category-button mt-cat-<?php echo esc_attr( $cat_id ); ?>"><a href="<?php echo esc_url( $cat_link ); ?>"><?php echo esc_html( $cat_name ); ?></a></span>
			<?php   } else { ?>
						<span class="category-txt mt-cat-<?php echo esc_attr( $cat_id ); ?>"><a href="<?php echo esc_url( $cat_link ); ?>"><?php echo esc_html( $cat_name ); ?></a></span>
			<?php
					}
				}
			?>
		</div>
<?php
		}
	}
endif;

/*------------------------------------------------------------------------------------------------*/
/**
 * Function to display review section at widget post
 *
 * @since 1.0.0
 */
add_action( 'editorial_widget_post_review', 'editorial_widget_post_review_cb' );

if( ! function_exists( 'editorial_widget_post_review_cb' ) ):
    function editorial_widget_post_review_cb() {
        global $post;

        $post_review_option = get_theme_mod( 'editorial_widget_post_review', 'show' );
        if( $post_review_option == 'hide' ) {
        	return;
        }

        $post_review_type = get_post_meta( $post->ID, 'post_review_option', true );
        switch ( $post_review_type ){
            case 'star_review':
                $post_meta_name = 'star_rating';
                $post_meta_value = 'feature_star';
                break;
            case 'percent_review':
                $post_meta_name = 'percent_rating';
                $post_meta_value = 'feature_percent';
                break;
            case 'point_review':
                $post_meta_name = 'points_rating';
                $post_meta_value = 'feature_points';
                break;
            default:
                $post_meta_name = 'star_rating';
                $post_meta_value = 'feature_star';
        }
        if( $post_review_type != 'no_review' && !empty( $post_review_type ) ){
            $product_rating = get_post_meta( $post->ID, $post_meta_name, true );
            $count = count($product_rating);
            $total_review = 0;
            foreach ( $product_rating as $key => $value ) {
                $rate_value = $value[ $post_meta_value ];
                $total_review = $total_review+$rate_value;
            }
            if( $post_meta_name == 'star_rating' ){
                $total_review = $total_review/$count;
                $final_value = round( $total_review, 1, PHP_ROUND_HALF_UP );
                echo '<div class="post-review-wrapper">';
                editorial_display_post_rating( $final_value );
                echo '</div>';
            } elseif( $post_meta_name == 'percent_rating' ){
                $total_review = $total_review/$count/10/2;
                $final_value = round( $total_review, 1, PHP_ROUND_HALF_UP );
                echo '<div class="post-review-wrapper">';
                editorial_display_post_rating( $final_value );
                echo '</div>';           
            }
        }
    }
endif;

/*------------------------------------------------------------------------------------------------*/
/**
 * Function to display review section at single post
 *
 * @since 1.0.0
 */
add_action( 'editorial_single_post_review', 'editorial_single_post_review_cb' );

if( !function_exists( 'editorial_single_post_review_cb' ) ){
	function editorial_single_post_review_cb() {

		global $post;

		$post_review_sec_option = get_theme_mod( 'editorial_pro_post_review_option', 'show' );
		if( $post_review_sec_option == 'hide' ) {
			return ;
		}

		$post_review_title = get_theme_mod( 'single_post_review_title', __( 'Review Overview', 'editorial-pro' ) );
		$post_review_sum_title = get_theme_mod( 'single_post_review_summary_title', __( 'Summary', 'editorial-pro' ) );

		$post_review_type = get_post_meta( $post->ID, 'post_review_option', true );
    	$post_review_description = get_post_meta( $post->ID, 'post_review_description', true );
    	
    	if( $post_review_type == 'no_review' || empty( $post_review_type ) ) {
    		return ;
    	}
?>
		<div class="mt-single-review-wrapper">
			<div class="section-title"><h4 class="review-title"><?php echo esc_html( $post_review_title ); ?></h4></div>
			<div class="review-content-wrapper">
				<?php
					if( $post_review_type == 'star_review' ){
						$star_rating = get_post_meta( $post->ID, 'star_rating', true );
						if( !empty ( $star_rating ) ){
				            $count = count( $star_rating );
				            $total_review = 0;
				            foreach ( $star_rating as $key => $value ) {
				            	$featured_name = $value['feature_name'];
		                        $star_value = $value['feature_star'];
		                        $total_review = $total_review+$star_value;
				?>
				    		<div class="single-review-wrap star-review clearfix">
								<span class="review-featured-name"><?php echo esc_html( $featured_name ); ?></span>
		                        <span class="stars-count"><?php editorial_display_post_rating( $star_value );?></span>
							</div><!-- .single-review-wrap -->
				<?php
				            }
				            $total_review = $total_review/$count;
		            		$total_review = round( $total_review, 1 );
		            		$final_value = round( $total_review, 1 );
				    	}
				    } elseif( $post_review_type == 'percent_review' ) {
				    	$percent_rating = get_post_meta( $post->ID, 'percent_rating', true );
				        if( !empty ( $percent_rating ) ){
				            $count = count( $percent_rating );
				            $total_review = 0;
				            foreach ( $percent_rating as $key => $value ) {
				            	$featured_name = $value['feature_name'];
				                $percent_value = $value['feature_percent'];
				?>
							<div class="single-review-wrap percent-review clearfix">
								<div class="review-details">
									<span class="review-featured-name"><?php echo esc_html( $featured_name ); ?></span>
									<span class="review-percent"><?php echo esc_attr( $percent_value );?> &#37; </span>
								</div>
							</div><!-- .single-review-wrap -->
				<?php
				                if( empty( $percent_value ) ) {
				                    $percent_value = '1';
				                }
				                $total_review = $total_review+$percent_value;
				            }
				            $total_review = $total_review/$count; 
				            $total_review = round( $total_review, 1 );
				            $final_value = $total_review/20;
				    	}
				    }

				?>				
				<div class="review-summary-wrap clearfix">
					<div class="sum-title-detail-wrap">
						<span class="sum-title"><?php echo esc_html( $post_review_sum_title ); ?></span>
						<div class="sum-details"><?php echo wp_kses_post( $post_review_description ); ?></div>
					</div>
					<div class="total-review-wrapper">
						<div class="total-value-star-wrap">
		                    <span class="total-value"><?php echo esc_html( $total_review ); ?></span>
		                    <span class="stars-count"><?php editorial_display_post_rating( $final_value ); ?></span>
	                    </div>
	                </div>
				</div><!-- .review-summary-wrap -->
			</div><!-- .review-content-wrapper -->
		</div><!-- .mt-single-review-wrapper -->
<?php
	}
}

/*------------------------------------------------------------------------------------------------*/
/**
 * Get author info
 *
 * @since 1.0.0
 */
add_action( 'editorial_pro_author_box', 'editorial_pro_author_box_hook' );

if( ! function_exists('editorial_pro_author_box_hook') ):
	function editorial_pro_author_box_hook() {
		global $post;
        $author_id = $post->post_author;
        $author_avatar = get_avatar( $author_id, '132' );
        $author_nickname = get_the_author_meta( 'display_name' );
        $author_extra_img_url = get_the_author_meta( 'user_meta_image', $post->post_author );
        $editorial_pro_author_option = get_theme_mod( 'editorial_pro_author_box_option', 'show' );
        $editorial_pro_author_website = get_the_author_meta( 'user_url' );
        if( $editorial_pro_author_option != 'hide' ) {
?>
            <div class="editorial-author-wrapper clearfix">
                <div class="author-avatar">
                    <a class="author-image" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );?>">
                        <?php 
                            if( !empty( $author_extra_img_url ) ) {
                                $author_img_id = editorial_pro_get_image_id_from_url( $author_extra_img_url );
                                $author_thumb_img = wp_get_attachment_image_src( $author_img_id, 'thumbnail', true );
                                echo '<img src="'. esc_url( $author_thumb_img[0] ) .'" />';
                            } else {
                                echo $author_avatar;
                            }
                        ?>
                    </a>
                </div><!-- .author-avatar -->
                <div class="author-desc-wrapper">                
                    <a class="author-title" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );?>"><?php echo esc_html( $author_nickname ); ?></a>
                    <div class="author-description"><?php echo get_the_author_meta('description');?></div>
                    <div class="author-social">
                        <?php 
                            global $editorial_user_social_array;
                            foreach( $editorial_user_social_array as $icon_id => $icon_name ) {
                                $author_social_link = get_the_author_meta( $icon_id );
                                if( !empty( $author_social_link ) ) {
                        ?>
                                    <span class="social-icon-wrap"><a href="<?php echo esc_url( $author_social_link )?>" target="_blank" title="<?php echo esc_attr( $icon_name )?>"><i class="fa fa-<?php echo esc_attr( $icon_id ); ?>"></i></a></span>
                        <?php            
                                }
                            }
                        ?>
                    </div><!-- .author-social -->
                    <?php if( !empty( $editorial_pro_author_website ) ) { ?>
                        <a href="<?php echo esc_url( $editorial_pro_author_website ); ?>" target="_blank" class="admin-dec"><?php echo esc_url( $editorial_pro_author_website ); ?></a>
                    <?php } ?>
                </div><!-- .author-desc-wrapper-->
            </div><!--editorial-author-wrapper-->
<?php
        }
	}
endif;

/*------------------------------------------------------------------------------------------------*/
/**
 * Related articles
 *
 * @since 1.0.0
 */
add_action( 'editorial_related_articles', 'editorial_related_articles_hook' );

if( ! function_exists( 'editorial_related_articles_hook' ) ):
	function editorial_related_articles_hook() {
		$editorial_pro_related_option = esc_attr( get_theme_mod( 'editorial_pro_related_articles_option', 'enable' ) );
		$editorial_pro_related_title = get_theme_mod( 'editorial_pro_related_articles_title', __( 'Related Articles', 'editorial-pro' ) );
		$related_post_layout = get_theme_mod( 'editorial_pro_related_articles_layout', 'default_layout' );
		$related_post_excerpt_length = get_theme_mod( 'related_post_excerpt_length', '20' );
		if( $editorial_pro_related_option != 'disable' ) {
	?>
			<div class="related-articles-wrapper <?php echo esc_attr( $related_post_layout ); ?>">
				<h2 class="related-title"><?php echo esc_html( $editorial_pro_related_title ); ?></h2>
				<?php
					global $post;
	                if( empty( $post ) ) {
	                    $post_id = '';
	                } else {
	                    $post_id = $post->ID;
	                }

	                $editorial_pro_related_type = get_theme_mod( 'editorial_pro_related_articles_type', 'category' );
	                $related_post_count = 3;
	                $related_post_count = apply_filters( 'related_posts_count', $related_post_count );

	                // Define related post arguments
	                $related_args = array(
	                    'no_found_rows'            => true,
	                    'update_post_meta_cache'   => false,
	                    'update_post_term_cache'   => false,
	                    'ignore_sticky_posts'      => 1,
	                    'orderby'                  => 'rand',
	                    'post__not_in'             => array( $post_id ),
	                    'posts_per_page'           => $related_post_count
	                );

	                
	                if ( $editorial_pro_related_type == 'tag' ) {
	                    $tags = wp_get_post_tags( $post_id );
	                    if ( $tags ) {
	                        $tag_ids = array();
	                        foreach( $tags as $tag_ed ) {
	                        	$tag_ids[] = $tag_ed->term_id;
	                        }
	                        $related_args['tag__in'] = $tag_ids;
	                    }
	                } else {
	                    $categories = get_the_category( $post_id );
	                    if ( $categories ) {
	                        $category_ids = array();
	                        foreach( $categories as $category_ed ) {
	                            $category_ids[] = $category_ed->term_id;
	                        }
	                        $related_args['category__in'] = $category_ids;
	                    }
	                }

	                $related_query = new WP_Query( $related_args );
	                if( $related_query->have_posts() ) {
	                    echo '<div class="related-posts-wrapper clearfix">';
	                    while( $related_query->have_posts() ) {
	                        $related_query->the_post();
				?>
							<div class="single-post-wrap">
	                            <div class="post-thumb-wrapper">
                                    <a href="<?php the_permalink();?>" title="<?php the_title();?>">
                                        <figure><?php the_post_thumbnail( 'editorial-block-medium' ); ?></figure>
                                    </a>
                                </div><!-- .post-thumb-wrapper -->
                                <div class="related-content-wrapper">
                                    <?php do_action( 'editorial_pro_post_categories' ); ?>
                                    <h3 class="post-title"><a href="<?php the_permalink();?>"><?php the_title(); ?></a></h3>
                                    <div class="post-meta-wrapper">
                                    	<?php editorial_pro_posted_on(); ?>
                                    </div>
                                    <div class="related-post-content">
	                                    <?php
	                                    	$post_content = get_the_content();
	                                        echo wp_trim_words( $post_content, $related_post_excerpt_length, ' ' );
	                                    ?>
	                                    </div><!-- .related-post-content -->
                                </div><!-- related-content-wrapper -->
	                        </div><!--. single-post-wrap -->
	            <?php
                    	}
                    	echo '</div>';
                	}
                	wp_reset_postdata();
        		?>
			</div><!-- .related-articles-wrapper -->
	<?php
		}
	}
endif;

/*------------------------------------------------------------------------------------------------*/
/**
 * Function for display pre loader
 *
 * @since 1.0.0
 */
add_action( 'editorial_pro_pre_loader', 'editorial_pro_pre_loader_hook' );
if( ! function_exists( 'editorial_pro_pre_loader_hook' ) ) :
	function editorial_pro_pre_loader_hook() {
		$ep_pre_loader_options = get_theme_mod( 'site_pre_loader_option', 'show' );
		if( $ep_pre_loader_options == 'hide' ) {
			return;
		}
		$ep_pre_loaders = get_theme_mod( 'site_pre_loader', 'three_balls' );
?>
		<div id="preloader-background">
			<div class="preloader-wrapper">
				<?php if( $ep_pre_loaders == 'three_balls' ) { ?>
					<div class="multiple1">
						<div class="ball1"></div>
						<div class="ball2"></div>
						<div class="ball3"></div>
					</div>
				<?php } elseif( $ep_pre_loaders == 'rectangles' ) { ?>
					<div class="mult2rect mult2rect1"></div>
					<div class="mult2rect mult2rect2"></div>
					<div class="mult2rect mult2rect3"></div>
					<div class="mult2rect mult2rect4"></div>
					<div class="mult2rect mult2rect5"></div>
				<?php } elseif( $ep_pre_loaders == 'steps' ) { ?>
					<div class="single1">
					   <div class="single1ball"></div>
					</div>
				<?php } elseif( $ep_pre_loaders == 'spinning_border' ) { ?>
					<div class="single4"></div>
				<?php } elseif( $ep_pre_loaders == 'single_bleep' ) { ?>
					<div class="single6"></div>
				<?php } elseif( $ep_pre_loaders == 'square' ) { ?>
					<div class="single5"></div>
				<?php } elseif( $ep_pre_loaders == 'hollow_circle' ) { ?>
					<div class="single8"></div>
				<?php } elseif( $ep_pre_loaders == 'knight_rider' ) { ?>
					<div class="single9"></div>
				<?php } else { ?>
					<div class="multiple1">
						<div class="ball1"></div>
						<div class="ball2"></div>
						<div class="ball3"></div>
					</div>
				<?php } ?>
			</div>
		</div><!-- #preloader-background -->
<?php
	}

endif;