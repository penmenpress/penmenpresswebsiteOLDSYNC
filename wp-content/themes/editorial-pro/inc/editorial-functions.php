<?php
/**
 *  Define extra or custom functions
 *
 * @package Mystery Themes
 * @subpackage Editorial Pro
 * @since 1.0.0
 */

/**
 * Enqueue Scripts and styles for admin
 */
function editorial_pro_admin_scripts_style( $hook ) {

	global $editorial_pro_version;

	if( 'widgets.php' != $hook && 'edit.php' != $hook && 'post.php' != $hook && 'post-new.php' != $hook && 'profile.php' != $hook ) {
        return;
    }    

	if ( function_exists( 'wp_enqueue_media' ) ) {
        wp_enqueue_media();
	}

	wp_enqueue_style( 'font-awesome', get_template_directory_uri().'/assets/library/font-awesome/css/font-awesome.min.css', array(), '4.5.0' );

	wp_enqueue_style( 'editorial-admin-style', get_template_directory_uri() .'/assets/css/admin-style.css', array(), esc_attr( $editorial_pro_version ) );

    wp_register_script( 'editorial-media-uploader', get_template_directory_uri() . '/assets/js/media-uploader.js', array('jquery'), 1.70 );
    wp_enqueue_script( 'editorial-media-uploader' );
    wp_localize_script( 'editorial-media-uploader', 'editorial_pro_l10n', array(
        'upload' => __( 'Upload', 'editorial-pro' ),
        'remove' => __( 'Remove', 'editorial-pro' )
    ));

    wp_enqueue_script( 'jquery-ui-button' );

	wp_enqueue_script( 'editorial-admin-script', get_template_directory_uri() .'/assets/js/admin-script.js', array('jquery'), esc_attr( $editorial_pro_version ), true );
	
}
add_action( 'admin_enqueue_scripts', 'editorial_pro_admin_scripts_style' );

/*------------------------------------------------------------------------------------------------*/
/**
 * Enqueue scripts and styles.
 */
function editorial_pro_scripts() {

	global $editorial_pro_version;

	$query_args = array(
            'family' => 'Titillium+Web:400,600,700,300&subset=latin,latin-ext'
        );

	wp_enqueue_style( 'font-awesome', get_template_directory_uri().'/assets/library/font-awesome/css/font-awesome.min.css', array(), '4.5.0' );

	wp_enqueue_style( 'editorial-google-font', add_query_arg( $query_args, "//fonts.googleapis.com/css" ) );

	wp_enqueue_style( 'lightslider-style', get_template_directory_uri() . '/assets/library/lightslider/css/lightslider.min.css', array(), '1.1.3' );

	wp_enqueue_style( 'pretty-photo', get_template_directory_uri() . '/assets/library/prettyphoto/prettyPhoto.css', array(), '3.1.6' );

	wp_enqueue_style( 'editorial-preloaders', get_template_directory_uri() .'/assets/css/editorial-preloaders.css', array(), '1.0.0' );

	wp_enqueue_style( 'editorial-pro-style', get_stylesheet_uri(), array(), esc_attr( $editorial_pro_version ) );
    
    wp_enqueue_style( 'editorial-responsive', get_template_directory_uri().'/assets/css/editorial-responsive.css', array(), esc_attr( $editorial_pro_version ) );

    wp_enqueue_script( 'jquery-ui-tabs' );

	wp_enqueue_script( 'lightslider', get_template_directory_uri() . '/assets/library/lightslider/js/lightslider.min.js', array( 'jquery' ), '1.1.3', true );

	$menu_sticky_option = get_theme_mod( 'editorial_pro_sticky_option', 'enable' );
	if ( $menu_sticky_option != 'disable' ) {
          wp_enqueue_script( 'jquery-sticky', get_template_directory_uri(). '/assets/library/sticky/jquery.sticky.js', array( 'jquery' ), '20150416', true );
    
          wp_enqueue_script( 'editorial-sticky-menu-setting', get_template_directory_uri(). '/assets/library/sticky/sticky-setting.js', array( 'jquery-sticky' ), '20150309', true );
    }

    wp_enqueue_script( 'jquery-fitvids', get_template_directory_uri() . '/assets/library/fitvids/jquery.fitvids.min.js', array( 'jquery' ), '1.1', true );

    wp_enqueue_script( 'theia-sticky-sidebar', get_template_directory_uri() . '/assets/library/stickysidebar/theia-sticky-sidebar.js', array( 'jquery' ), '1.4.0', true );

    wp_enqueue_script( 'jquery-prettyphoto', get_template_directory_uri() .'/assets/library/prettyphoto/jquery.prettyPhoto.js', array( 'jquery' ), '3.1.6', true );

	wp_register_script( 'editorial-custom-script', get_template_directory_uri() . '/assets/js/custom-script.js', array( 'jquery' ), esc_attr( $editorial_pro_version ), true );

	wp_localize_script( 'editorial-custom-script', 'editorial_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php') ) );
	wp_enqueue_script( 'editorial-custom-script' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'editorial_pro_scripts' );

/*------------------------------------------------------------------------------------------------*/
/**
 * categories in dropdown
 */
if( ! function_exists( 'editorial_pro_category_dropdown' ) ) :
	function editorial_pro_category_dropdown() {
		$editorial_pro_categories = get_categories( array( 'hide_empty' => 0 ) );
		$editorial_pro_category_dropdown['0'] = __( 'Select Category', 'editorial-pro' );
		foreach ( $editorial_pro_categories as $editorial_pro_category ) {
			$editorial_pro_category_dropdown[$editorial_pro_category->term_id] = $editorial_pro_category->cat_name;
		}
		return $editorial_pro_category_dropdown;
	}
endif;


/**
 * no of columns for block
 */
$editorial_pro_block_columns = array(
		''	=> __( 'Select No.of Columns', 'editorial-pro' ),
		'1' => __( '1 Column', 'editorial-pro' ),
		'2' => __( '2 Columns', 'editorial-pro' ),
		'3'	=> __( '3 Columns', 'editorial-pro' ),
		'4'	=> __( '4 Columns', 'editorial-pro' )
	);

/**
 * no of items in carousel
 */
$editorial_pro_carousel_items = array(
		''	=> __( 'Select No.of Items', 'editorial-pro' ),
		'2' => __( '2 Items', 'editorial-pro' ),
		'3'	=> __( '3 Items', 'editorial-pro' ),
		'4'	=> __( '4 Items', 'editorial-pro' ),
		'5'	=> __( '5 Items', 'editorial-pro' )
	);

/**
 * carousel layout
 */
$editorial_pro_carousel_layout = array(
		'carousel_layout_1' => __( 'Layout 1: Post images are in rectangle.', 'editorial-pro' ),
		'carousel_layout_2' => __( 'Layout 2: Post image are in portrait.', 'editorial-pro' )
	);

/**
 * Featured section layout
 */
$editorial_pro_featured_sec_layout = array(
		'featured_layout_1' => __( 'Layout 1: ( Default Layout. )', 'editorial-pro' ),
		'featured_layout_2' => __( 'Layout 2: ( Horizontal image on top. )', 'editorial-pro' ),
		'featured_layout_3' => __( 'Layout 3: ( 4 Posts in square size. ) ', 'editorial-pro' ),
	);

/**
 * Block list layout
 */
$editorial_pro_block_list_layout = array(
		'block_layout_1' => __( 'Layout 1 (Default): All posts on list.', 'editorial-pro' ),
		'block_layout_2' => __( 'Layout 2: First post in fullwidth and rest are in list.', 'editorial-pro' )
	);

/**
 * Block grid layout
 */
$editorial_pro_block_grid_layout = array(
		'block_layout_1' => __( 'Layout 1 (Default)', 'editorial-pro' ),
		'block_layout_2' => __( 'Layout 2: Posts with excerpt content.', 'editorial-pro' ),
		'block_layout_3' => __( 'Layout 3: Posts title are over bottom of image.', 'editorial-pro' ),
	);

/**
 * Post in row for fullwidth tabbed
 */
$post_in_row_array = array(
        '2' => __( '2 Posts', 'editorial-pro' ),
        '3' => __( '3 Posts', 'editorial-pro' ),
        '4' => __( '4 Posts', 'editorial-pro' )
    );

/**
 * Post list option
 */
$editorial_pro_post_list_option = array(
		'latest' => __( 'Latest Posts', 'editorial-pro' ),
		'random' => __( 'Random Posts', 'editorial-pro' )
	);

/*------------------------------------------------------------------------------------------------*/
/**
 * Title for tab in Fullwidth Tabbed Widget
 * 
 * @param $tabbed_icon string
 * @param $tabbed_title string
 * @param $tabbed_cat_id int
 *
 * @return $tabbed_title or $category_title if parameter is empty
 * @since 1.0.0
 */
if( ! function_exists( 'editorial_pro_tabbed_title' ) ):
	function editorial_pro_tabbed_title( $tabbed_icon, $tabbed_title, $tabbed_cat_id ) {
		if( !empty( $tabbed_icon ) ) {
			echo '<span class="tab-icon"><i class="fa '. esc_attr( $tabbed_icon ) .'"></i></span>';
		}
		if( !empty( $tabbed_title ) ) {
			echo esc_html( $tabbed_title );
		} else {
			echo get_cat_name( $tabbed_cat_id );
		}
	}
endif;

/*------------------------------------------------------------------------------------------------*/
/**
 * Get category slug
 *
 * @param $req_cat_id int
 * @return $cat_slug string
 *
 * @since 1.0.0
 */
function editorial_pro_get_cat_slug( $req_cat_id ) {
	$cat_slug_obj = get_term_by( 'id', $req_cat_id , 'category' );
	$cat_slug = $cat_slug_obj->slug;
	return $cat_slug;
}

/*------------------------------------------------------------------------------------------------*/
/**
 * Custom function for wp_query args
 *
 * @since 1.0.0
 */
if( ! function_exists( 'editorial_pro_query_args' ) ):
	function editorial_pro_query_args( $cat_id, $post_count = null, $sub_cats = null ) {
		if( !empty( $cat_id ) ) {
			if( !empty( $sub_cats ) ) {
				$cat_option = 'cat';
			} else {
				$cat_option = 'category__in';
			}
			$editorial_pro_args = array(
						'post_type' 	=> 'post',
						$cat_option	=> absint( $cat_id ),
						'posts_per_page'=> intval( $post_count )
					);
		} else {
			$editorial_pro_args = array(
						'post_type'		=> 'post',
						'posts_per_page'=> intval( $post_count ),
						'ignore_sticky_posts' => 1
					);
		}
		return $editorial_pro_args;
	}
endif;

/*------------------------------------------------------------------------------------------------*/
/**
 * block widget title
 *
 * @since 1.0.0
 */
if( ! function_exists( 'editorial_pro_block_title' ) ):
	function editorial_pro_block_title( $block_title, $block_cat_id ) {
		$block_cat_name = get_cat_name( $block_cat_id );
		$cat_id_class = '';
		if( !empty( $block_cat_id ) ) {
			$cat_id_class = 'mt-cat-'. absint( $block_cat_id );
			$cat_link = get_category_link( $block_cat_id );
		}
		if( !empty( $block_title ) ) {
			$mt_widget_title = $block_title;
		} elseif( !empty( $block_cat_name ) ) {
			$mt_widget_title = $block_cat_name;
		} else {
			$mt_widget_title = '';
		}
?>
		<div class="block-header <?php echo esc_attr( $cat_id_class ); ?>">
			<h3 class="block-title">
				<?php 
					if( !empty( $block_cat_id ) ) {
				?>
						<a href="<?php echo esc_url( $cat_link ); ?>"><?php echo esc_html( $mt_widget_title ); ?></a>
				<?php
					} else {
						echo esc_html( $mt_widget_title );
					}
				?>
			</h3>
		</div>
<?php
	}
endif;

/*------------------------------------------------------------------------------------------------*/
/**
 * widget posts excerpt in words
 *
 * @since 1.0.0
 */
if( ! function_exists( 'editorial_pro_post_excerpt' ) ):
    function editorial_pro_post_excerpt( $content, $word_limit ) {
        $get_content = strip_tags( $content );
        $strip_content = strip_shortcodes( $get_content );
        $excerpt_words = explode( ' ', $strip_content );    
        return implode( ' ', array_slice( $excerpt_words, 0, $word_limit ) );
    }
endif;

/*------------------------------------------------------------------------------------------------*/
/**
 * Define function to show the social media icons
 *
 * @since 1.0.0
 */
if( ! function_exists( 'editorial_pro_social_icons' ) ):
	function editorial_pro_social_icons() {
		$social_fb_link = get_theme_mod( 'social_fb_link', '' );
        $social_tw_link = get_theme_mod( 'social_tw_link', '' );
        $social_gp_link = get_theme_mod( 'social_gp_link', '' );
        $social_lnk_link = get_theme_mod( 'social_lnk_link', '' );
        $social_yt_link = get_theme_mod( 'social_yt_link', '' );
        $social_vm_link = get_theme_mod( 'social_vm_link', '' );
        $social_pin_link = get_theme_mod( 'social_pin_link', '' );
        $social_insta_link = get_theme_mod( 'social_insta_link', '' );

        $social_fb_icon	= 'fa-facebook';
        $social_fb_icon	= apply_filters( 'social_fb_icon', $social_fb_icon );
        
        $social_tw_icon	= 'fa-twitter';
        $social_tw_icon	= apply_filters( 'social_tw_icon', $social_tw_icon );

        $social_gp_icon	= 'fa-google-plus';
        $social_gp_icon	= apply_filters( 'social_gp_icon', $social_gp_icon );

        $social_lnk_icon	= 'fa-linkedin';
        $social_lnk_icon	= apply_filters( 'social_lnk_icon', $social_lnk_icon );

        $social_yt_icon	= 'fa-youtube';
        $social_yt_icon	= apply_filters( 'social_yt_icon', $social_yt_icon );

        $social_vm_icon	= 'fa-vimeo';
        $social_vm_icon	= apply_filters( 'social_vm_icon', $social_vm_icon );

        $social_pin_icon	= 'fa-pinterest';
        $social_pin_icon	= apply_filters( 'social_pin_icon', $social_pin_icon );

        $social_insta_icon	= 'fa-instagram';
        $social_insta_icon = apply_filters( 'social_insta_icon', $social_insta_icon );

        if( !empty( $social_fb_link ) ) {
        	echo '<span class="social-link"><a href="'. esc_url( $social_fb_link ) .'" target="_blank"><i class="fa '. esc_attr( $social_fb_icon ) .'"></i></a></span>';
        }
        if( !empty( $social_tw_link ) ) {
        	echo '<span class="social-link"><a href="'. esc_url( $social_tw_link ) .'" target="_blank"><i class="fa '. esc_attr( $social_tw_icon ) .'"></i></a></span>';
        }
        if( !empty( $social_gp_link ) ) {
        	echo '<span class="social-link"><a href="'. esc_url( $social_gp_link ) .'" target="_blank"><i class="fa '. esc_attr( $social_gp_icon ) .'"></i></a></span>';
        }
        if( !empty( $social_lnk_link ) ) {
        	echo '<span class="social-link"><a href="'. esc_url( $social_lnk_link ) .'" target="_blank"><i class="fa '. esc_attr( $social_lnk_icon ) .'"></i></a></span>';
        }
        if( !empty( $social_yt_link ) ) {
        	echo '<span class="social-link"><a href="'. esc_url( $social_yt_link ) .'" target="_blank"><i class="fa '. esc_attr( $social_yt_icon ) .'"></i></a></span>';
        }
        if( !empty( $social_vm_link ) ) {
        	echo '<span class="social-link"><a href="'. esc_url( $social_vm_link ) .'" target="_blank"><i class="fa '. esc_attr( $social_vm_icon ) .'"></i></a></span>';
        }
        if( !empty( $social_pin_link ) ) {
        	echo '<span class="social-link"><a href="'. esc_url( $social_pin_link ) .'" target="_blank"><i class="fa '. esc_attr( $social_pin_icon ) .'"></i></a></span>';
        }
        if( !empty( $social_insta_link ) ) {
        	echo '<span class="social-link"><a href="'. esc_url( $social_insta_link ) .'" target="_blank"><i class="fa '. esc_attr( $social_insta_icon ) .'"></i></a></span>';
        }
	}
endif;

/*------------------------------------------------------------------------------------------------*/
/**
 * Add cat id in menu class
 *
 * @since 1.0.0
 */
function editorial_pro_category_nav_class( $classes, $item ){
    if( 'category' == $item->object ){
        $category = get_category( $item->object_id );
        $classes[] = 'mt-cat-' . $category->term_id;
    }
    return $classes;
}
add_filter( 'nav_menu_css_class', 'editorial_pro_category_nav_class', 10, 2 );

/*------------------------------------------------------------------------------------------------*/
/**
 * Generate darker color
 * Source: http://stackoverflow.com/questions/3512311/how-to-generate-lighter-darker-color-with-php
 *
 * @since 1.0.0
 */
if( ! function_exists( 'editorial_pro_hover_color' ) ) :
function editorial_pro_hover_color( $hex, $steps ) {
	// Steps should be between -255 and 255. Negative = darker, positive = lighter
	$steps = max( -255, min( 255, $steps ) );

	// Normalize into a six character long hex string
	$hex = str_replace( '#', '', $hex );
	if ( strlen( $hex ) == 3) {
		$hex = str_repeat( substr( $hex,0,1 ), 2 ).str_repeat( substr( $hex, 1, 1 ), 2 ).str_repeat( substr( $hex,2,1 ), 2 );
	}

	// Split into three parts: R, G and B
	$color_parts = str_split( $hex, 2 );
	$return = '#';

	foreach ( $color_parts as $color ) {
		$color   = hexdec( $color ); // Convert to decimal
		$color   = max( 0, min( 255, $color + $steps ) ); // Adjust color
		$return .= str_pad( dechex( $color ), 2, '0', STR_PAD_LEFT ); // Make two char hex code
	}

	return $return;
}
endif;

/*------------------------------------------------------------------------------------------------*/
/**
 * Function define about page/post/archive sidebar
 *
 * @since 1.0.0
 */
if( ! function_exists( 'editorial_pro_sidebar' ) ):
function editorial_pro_sidebar() {
    global $post;
    if( is_single() || is_page() ) {
        $sidebar_meta_option = get_post_meta( $post->ID, 'editorial_pro_sidebar_location', true );
    }
     
    if( is_home() ) {
        $set_id = get_option( 'page_for_posts' );
		$sidebar_meta_option = get_post_meta( $set_id, 'editorial_pro_sidebar_location', true );
    }
    
    if( empty( $sidebar_meta_option ) || is_archive() || is_search() ) {
        $sidebar_meta_option = 'default_sidebar';
    }
    
    $editorial_pro_archive_sidebar = get_theme_mod( 'editorial_pro_archive_sidebar', 'right_sidebar' );
    $editorial_pro_post_default_sidebar = get_theme_mod( 'editorial_pro_default_post_sidebar', 'right_sidebar' );
    $editorial_pro_page_default_sidebar = get_theme_mod( 'editorial_pro_default_page_sidebar', 'right_sidebar' );
    
    if( $sidebar_meta_option == 'default_sidebar' ) {
        if( is_single() ) {
            if( $editorial_pro_post_default_sidebar == 'right_sidebar' ) {
                get_sidebar();
            } elseif( $editorial_pro_post_default_sidebar == 'left_sidebar' ) {
                get_sidebar( 'left' );
            }
        } elseif( is_page() ) {
            if( $editorial_pro_page_default_sidebar == 'right_sidebar' ) {
                get_sidebar();
            } elseif( $editorial_pro_page_default_sidebar == 'left_sidebar' ) {
                get_sidebar( 'left' );
            }
        } elseif( $editorial_pro_archive_sidebar == 'right_sidebar' ) {
            get_sidebar();
        } elseif( $editorial_pro_archive_sidebar == 'left_sidebar' ) {
            get_sidebar( 'left' );
        }
    } elseif( $sidebar_meta_option == 'right_sidebar' ) {
        get_sidebar();
    } elseif( $sidebar_meta_option == 'left_sidebar' ) {
        get_sidebar( 'left' );
    }
}
endif;

/*------------------------------------------------------------------------------------------------*/
/**
 * Filter the category title
 *
 * @since 1.0.0
 */
add_filter( 'get_the_archive_title', function ( $title ) {
    if( is_category() ) {
        $title = single_cat_title( '', false );
    }
    return $title;
});

/*------------------------------------------------------------------------------------------------*/
/*** 
 * Display Star according to number of rating
 *
 * @since 1.0.0
 */
if( ! function_exists( 'editorial_display_post_rating' ) ):
    function editorial_display_post_rating ( $total_stars ) {

        $star_integer = absint( $total_stars );

        // this echo full stars
        for ( $i = 0; $i < $star_integer; $i++ ) {
            echo '<span class="star-value"><i class="fa fa-star"></i></span>';
        }

        $star_rest = $total_stars - $star_integer;

        // this echo full star or half or empty star
        if ( $star_rest >= 0.25 && $star_rest < 0.75 ) {
            echo '<span class="star-value"><i class="fa fa-star-half-o"></i></span>';
        } elseif ( $star_rest >= 0.75 ) {
            echo '<span class="star-value"><i class="fa fa-star"></i></span>';
        } elseif ( $total_stars != 5 ) {
            echo '<span class="star-value"><i class="fa fa-star-o"></i></span>';
        }

        // this echo empty star
        $count = 4-$star_integer;
        for ( $i = 0; $i < $count; $i++ ) {
            echo '<span class="star-value"><i class="fa fa-star-o"></i></span>';
        }
    }
endif;

/*------------------------------------------------------------------------------------------------*/
/**
 * Get Post view count
 *
 * @since 1.0.0
 */
function editorial_get_post_views( $postID ){
    $count_key = 'editorial_post_views_count';
    $count = get_post_meta( $postID, $count_key, true) ;
    if( $count == '' ){
        delete_post_meta( $postID, $count_key );
        add_post_meta( $postID, $count_key, '0' );
        return '0';
    }
    return $count;
}
/*------------------------------------------------------------------------------------------------*/
/**
 * Set Post view count
 *
 * @since 1.0.0
 */
function editorial_set_post_views( $postID ) {
    $count_key = 'editorial_post_views_count';
    $count = get_post_meta( $postID, $count_key, true );
    if( $count == '' ){
        $count = 0;
        delete_post_meta( $postID, $count_key );
        add_post_meta( $postID, $count_key, '0' );
    }else{
        $count++;
        update_post_meta( $postID, $count_key, $count );
    }
}

// Remove issues with pref-etching adding extra views
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );

/*------------------------------------------------------------------------------------------------*/
/**
 * Load google fonts api link at wp_head
 *
 * @since 1.0.2
 */
function editorial_pro_googlefont_callback() {

	$p_font_family = get_theme_mod( 'p_font_family', 'Titillium Web' );
	$p_font_style = get_theme_mod( 'p_font_style' );
	$p_typo = $p_font_family.":".$p_font_style;

	$h1_font_family = get_theme_mod( 'h1_font_family', 'Titillium Web' );
	$h1_font_style = get_theme_mod( 'h1_font_style' );
	$h1_typo = $h1_font_family.":".$h1_font_style;

	$h2_font_family = get_theme_mod( 'h2_font_family', 'Titillium Web' );
	$h2_font_style = get_theme_mod( 'h2_font_style' );
	$h2_typo = $h2_font_family.":".$h2_font_style;

	$h3_font_family = get_theme_mod( 'h3_font_family', 'Titillium Web' );
	$h3_font_style = get_theme_mod( 'h3_font_style' );
	$h3_typo = $h3_font_family.":".$h3_font_style;

	$h4_font_family = get_theme_mod( 'h4_font_family', 'Titillium Web' );
	$h4_font_style = get_theme_mod( 'h4_font_style' );
	$h4_typo = $h4_font_family.":".$h4_font_style;

	$h5_font_family = get_theme_mod( 'h5_font_family', 'Titillium Web' );
	$h5_font_style = get_theme_mod( 'h5_font_style' );
	$h5_typo = $h5_font_family.":".$h5_font_style;

	$h6_font_family = get_theme_mod( 'h6_font_family', 'Titillium Web' );
	$h6_font_style = get_theme_mod( 'h6_font_style' );
	$h6_typo = $h6_font_family.":".$h6_font_style;

	$menu_font_family = get_theme_mod( 'menu_font_family', 'Titillium Web' );
	$menu_font_style = get_theme_mod( 'menu_font_style' );
	$menu_typo = $menu_font_family.":".$menu_font_style;

	$get_fonts = array( $p_typo , $h1_typo, $h2_typo, $h3_typo, $h4_typo, $h5_typo, $h6_typo, $menu_typo );

	$font_weight_array = array();

	foreach( $get_fonts as $fonts ){
		$each_font = explode( ':', $fonts );
		if( !isset( $font_weight_array[$each_font[0]] ) ){

		$font_weight_array[$each_font[0]][] = $each_font[1];
		}else{
			if( !in_array( $each_font[1], $font_weight_array[$each_font[0]] ) ){
				$font_weight_array[$each_font[0]][] = $each_font[1];
			}
		}
	}

	$final_font_array = array();
	
	foreach( $font_weight_array as $font => $font_weight ){
		$each_font_string = $font.':'.implode( ',', $font_weight );
		$final_font_array[] = $each_font_string;
	}

	$final_font_string = implode( '|', $final_font_array );

    $query_args = array(
        'family' => urlencode( $final_font_string ),
    	'subset' => urlencode( 'latin,cyrillic-ext,greek-ext,greek,vietnamese,latin-ext,cyrillic,khmer,devanagari,arabic,hebrew,telugu' )
    ); 
    
    echo "\n<link href='" . add_query_arg( $query_args, "//fonts.googleapis.com/css" ) . "' rel='stylesheet' type='text/css'>\n";
}
add_action( 'wp_head', 'editorial_pro_googlefont_callback' );
add_action( 'wp_head_preview', 'editorial_pro_googlefont_callback' );


/**
 * get Google font variants
 *
 * @since 1.0.0
 */

function get_google_font_variants() {
    $editorial_pro_font_list = get_option( 'editorial_pro_google_font', '' );
    
    $font_family = $_REQUEST['font_family'];
    
    $font_array = editorial_pro_search_key( $editorial_pro_font_list, 'family', $font_family );
    
    $variants_array = $font_array['0']['variants'] ;
    $options_array = "";
    foreach ( $variants_array  as $key=>$variants ) {
        $options_array .= '<option value="'.$key.'">'.$variants.'</option>';
    }
    echo $options_array;
    die();
}
add_action( "wp_ajax_get_google_font_variants", "get_google_font_variants" );

function editorial_pro_search_key( $array, $key, $value ) {
    $results = array();

    if ( is_array( $array ) ) {
        if ( isset($array[$key]) && $array[$key] == $value ) {
            $results[] = $array;
        }

        foreach ( $array as $subarray ) {
            $results = array_merge( $results, editorial_pro_search_key( $subarray, $key, $value ) );
        }
    }

    return $results;
}
/*------------------------------------------------------------------------------------------------*/
/**
 * Tabbed post content Ajax Function
 *
 * @since 1.0.0
**/
if ( ! function_exists( 'editorial_tabs_ajax_action' ) ) {
    function editorial_tabs_ajax_action() {
        $cat_id    = $_POST['category_id'];
        $cat_slug  = $_POST['category_slug'];
        $post_count = $_POST['post_count'];
        $post_excerpt_length = intval( $_POST['post_excerpt_length'] );
        $tab_subcats = $_POST['tab_subcats'];
        ob_start();
?>
		<div class="tab-cat-content <?php echo esc_attr( $cat_slug ); ?>">
            <?php
                $tabbed_post_args = editorial_pro_query_args( $cat_id, $post_count, $tab_subcats );
                $tabbed_post_query = new WP_Query( $tabbed_post_args );
                if( $tabbed_post_query->have_posts() ) {
                    while( $tabbed_post_query->have_posts() ) {
                        $tabbed_post_query->the_post();
                        $title_size = 'small-size';
            ?>
                        <div class="single-post-wrapper <?php editorial_pro_post_format_icon(); ?> clearfix">
                            <div class="post-thumb-wrapper">
                                <a href="<?php the_permalink();?>" title="<?php the_title();?>">
	                                <figure>
	                                    <?php 
	                                        if( has_post_thumbnail() ) {
	                                            the_post_thumbnail( 'editorial-block-medium' );
	                                        } else {
	                                            $image_src = editorial_pro_image_fallback( 'editorial-block-medium' );
	                                            echo '<img src="'. $image_src[0] .'" />';
	                                        }
	                                    ?>
	                                </figure>
	                            </a>
                            </div><!-- .post-thumb-wrapper -->
                            <div class="post-content-wrapper">
                                <?php do_action( 'editorial_pro_post_categories' ); ?>
                                <h3 class="post-title <?php echo esc_attr( $title_size ); ?>"><a href="<?php the_permalink();?>"><?php the_title(); ?></a></h3>
                                <div class="post-meta-wrapper">
                                    <?php editorial_pro_posted_on(); ?>
                                    <?php editorial_pro_post_comment(); ?>
                                    <?php do_action( 'editorial_widget_post_review' ); ?>
                                </div>
                                <div class="post-desc">
                                	<?php
                                		$post_content = get_the_content();
                                        echo wp_trim_words( $post_content, $post_excerpt_length, ' ' );
                                    ?>
                                </div>
                            </div><!-- .post-content-wrapper -->
                        </div><!-- .single-post-wrapper -->
            <?php
                    }
                }
            ?>
        </div><!-- .tab-cat-content -->
<?php
        $sv_html = ob_get_contents();
        ob_get_clean();
        echo $sv_html;
        die();
    }
}
add_action( 'wp_ajax_editorial_tabs_ajax_action', 'editorial_tabs_ajax_action' );
add_action( 'wp_ajax_nopriv_editorial_tabs_ajax_action', 'editorial_tabs_ajax_action' );

/*------------------------------------------------------------------------------------------------*/
/**
 * Post image fallback
 *
 * @since 1.0.0
 */
if( ! function_exists( 'editorial_pro_image_fallback' ) ):
	function editorial_pro_image_fallback( $image_size ) {
		$fallback_img_url = get_theme_mod( 'post_fallback_image', '' );
		if( empty( $fallback_img_url ) ) {
			return;
		}
		$fallback_img_id = editorial_pro_get_image_id_from_url( $fallback_img_url );
		$fallback_image_path = wp_get_attachment_image_src( $fallback_img_id, $image_size, true );
		return $fallback_image_path;
	}
endif;

/*------------------------------------------------------------------------------------------------*/
/**
 * Get media attachment id from url
 *
 * @since 1.0.0
 */ 
if ( ! function_exists( 'editorial_pro_get_image_id_from_url' ) ):
    function editorial_pro_get_image_id_from_url( $attachment_url ) {
        global $wpdb;
        $attachment_id = false;
     
        // If there is no url, return.
        if ( '' == $attachment_url )
            return;
     
        // Get the upload directory paths
        $upload_dir_paths = wp_upload_dir();
     
        // Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
        if ( false !== strpos( $attachment_url, $upload_dir_paths['baseurl'] ) ) {
     
            // If this is the URL of an auto-generated thumbnail, get the URL of the original image
            $attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );
     
            // Remove the upload path base directory from the attachment URL
            $attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $attachment_url );
     
            // Finally, run a custom database query to get the attachment ID from the modified attachment URL
            $attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachment_url ) );
     
        }     
        return $attachment_id;
    }
endif;

/*------------------------------------------------------------------------------------------------*/
/**
 * Archive readmore
 *
 * @since 1.0.0
 */

if( ! function_exists( 'editorial_pro_archive_readmore' ) ):
	function editorial_pro_archive_readmore() {
		$readmore_option = get_theme_mod( 'archive_readmore_option', 'show' );
		if( $readmore_option == 'hide' ) {
			return;
		}
		global $post;

		$readmore_text = get_theme_mod( 'archive_readmore_text', __( 'Read More', 'editorial-pro' ) );
		$readmore_type = get_theme_mod( 'archive_readmore_type', 'rm_button' );
		$post_permalink = get_permalink( $post->ID );

		if( $readmore_type == 'rm_text' ) {
	?>
			<div class="ep-read-more rm-text">
				<a href="<?php echo esc_url( $post_permalink ); ?>"><i class="fa fa-arrow-circle-o-right"> </i> <?php echo esc_html( $readmore_text ); ?></a>
			</div>
	<?php } else { ?>
			<div class="ep-read-more rm-button">
				<a href="<?php echo esc_url( $post_permalink ); ?>"><i class="fa fa-arrow-circle-o-right"> </i> <?php echo esc_html( $readmore_text ); ?></a>
			</div>
	<?php
		}
	}
endif;

/*------------------------------------------------------------------------------------------------*/
/**
 * Add relation of prettyPhoto in gallery image
 *
 * @since 1.0.0
 */
add_filter( 'wp_get_attachment_link', 'editorial_prettyadd' );
 
function editorial_prettyadd ($content) {
    $content = preg_replace("/<a/","<a rel=\"prettyPhoto[slides]\"",$content,1);
    return $content;
}

/*------------------------------------------------------------------------------------------------*/
/**
 * Define format class
 *
 * @since 1.0.0
 */
if( ! function_exists( 'editorial_pro_post_format_icon' ) ):
	function editorial_pro_post_format_icon() {

		$format_icon_option = get_theme_mod( 'editorial_widget_post_format_icon', 'show' );
		if( $format_icon_option == 'hide' ) {
			return;
		}

		global $post;
		$post_id = $post->ID;
		$post_format = get_post_format( get_the_ID() );
		if( !empty( $post_format ) ) {
			$post_format_class = 'post-format-'.$post_format;
		echo esc_attr( $post_format_class );
		}
	}
endif;

/*-----------------------------------------------------------------------------------------------------------------------*/
/**
 * Get minified css and removed space
 *
 * @since 1.1.3
 */
function editorial_pro_css_strip_whitespace( $css ){
    $replace = array(
        "#/\*.*?\*/#s" => "",  // Strip C style comments.
        "#\s\s+#"      => " ", // Strip excess whitespace.
    );
    $search = array_keys( $replace );
    $css = preg_replace( $search, $replace, $css );

    $replace = array(
        ": "  => ":",
        "; "  => ";",
        " {"  => "{",
        " }"  => "}",
        ", "  => ",",
        "{ "  => "{",
        ";}"  => "}", // Strip optional semicolons.
        ",\n" => ",", // Don't wrap multiple selectors.
        "\n}" => "}", // Don't wrap closing braces.
        "} "  => "}\n", // Put each rule on it's own line.
    );
    $search = array_keys( $replace );
    $css = str_replace( $search, $replace, $css );

    return trim( $css );
}
