<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Mystery Themes
 * @subpackage Editorial Pro
 * @since 1.0.0
 */

/*----------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function editorial_pro_body_classes( $classes ) {

    global $post;
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	/**
     * option for web site layout 
     */
    $editorial_pro_website_layout = esc_attr( get_theme_mod( 'site_layout_option', 'fullwidth_layout' ) );
    
    if( !empty( $editorial_pro_website_layout ) ) {
        $classes[] = $editorial_pro_website_layout;
    }

    /**
     * sidebar option for post/page/archive 
     */
    if( is_single() || is_page() ) {
        $sidebar_meta_option = esc_attr( get_post_meta( $post->ID, 'editorial_pro_sidebar_location', true ) );
    }
     
    if( is_home() ) {
        $set_id = esc_attr( get_option( 'page_for_posts' ) );
		$sidebar_meta_option = esc_attr( get_post_meta( $set_id, 'editorial_pro_sidebar_location', true ) );
    }
    
    if( empty( $sidebar_meta_option ) || is_archive() || is_search() ) {
        $sidebar_meta_option = 'default_sidebar';
    }
    $editorial_pro_archive_sidebar = esc_attr( get_theme_mod( 'editorial_pro_archive_sidebar', 'right_sidebar' ) );
    $editorial_pro_post_default_sidebar = esc_attr( get_theme_mod( 'editorial_pro_default_post_sidebar', 'right_sidebar' ) );
    $editorial_pro_page_default_sidebar = esc_attr( get_theme_mod( 'editorial_pro_default_page_sidebar', 'right_sidebar' ) );
    
    if( $sidebar_meta_option == 'default_sidebar' ) {
        if( is_single() ) {
            if( $editorial_pro_post_default_sidebar == 'right_sidebar' ) {
                $classes[] = 'right-sidebar';
            } elseif( $editorial_pro_post_default_sidebar == 'left_sidebar' ) {
                $classes[] = 'left-sidebar';
            } elseif( $editorial_pro_post_default_sidebar == 'no_sidebar' ) {
                $classes[] = 'no-sidebar';
            } elseif( $editorial_pro_post_default_sidebar == 'no_sidebar_center' ) {
                $classes[] = 'no-sidebar-center';
            }
        } elseif( is_page() ) {
            if( $editorial_pro_page_default_sidebar == 'right_sidebar' ) {
                $classes[] = 'right-sidebar';
            } elseif( $editorial_pro_page_default_sidebar == 'left_sidebar' ) {
                $classes[] = 'left-sidebar';
            } elseif( $editorial_pro_page_default_sidebar == 'no_sidebar' ) {
                $classes[] = 'no-sidebar';
            } elseif( $editorial_pro_page_default_sidebar == 'no_sidebar_center' ) {
                $classes[] = 'no-sidebar-center';
            }
        } elseif( $editorial_pro_archive_sidebar == 'right_sidebar' ) {
            $classes[] = 'right-sidebar';
        } elseif( $editorial_pro_archive_sidebar == 'left_sidebar' ) {
            $classes[] = 'left-sidebar';
        } elseif( $editorial_pro_archive_sidebar == 'no_sidebar' ) {
            $classes[] = 'no-sidebar';
        } elseif( $editorial_pro_archive_sidebar == 'no_sidebar_center' ) {
            $classes[] = 'no-sidebar-center';
        }
    } elseif( $sidebar_meta_option == 'right_sidebar' ) {
        $classes[] = 'right-sidebar';
    } elseif( $sidebar_meta_option == 'left_sidebar' ) {
        $classes[] = 'left-sidebar';
    } elseif( $sidebar_meta_option == 'no_sidebar' ) {
        $classes[] = 'no-sidebar';
    } elseif( $sidebar_meta_option == 'no_sidebar_center' ) {
        $classes[] = 'no-sidebar-center';
    }

    if( is_archive() ) {
        $editorial_pro_archive_layout = get_theme_mod( 'editorial_pro_archive_layout', 'classic' );
        if( !empty( $editorial_pro_archive_layout ) ) {
            $classes[] = 'archive-'.$editorial_pro_archive_layout;
        }
    }
    
    if( is_single() ) {
        $editorial_post_layout = get_post_meta( $post->ID, 'editorial_pro_post_layout', true );
        if( $editorial_post_layout == 'global_layout' || empty( $editorial_post_layout ) ) {
            $editorial_post_layout = get_theme_mod( 'editorial_pro_default_post_layout', 'post_layout_1' );
        }
        $classes[] = $editorial_post_layout;
    }

    $img_hover_type = get_theme_mod( 'post_image_hover_type', 'zoomin' );
    $classes[] = 'ep-image-'.$img_hover_type;

    $front_widget_title_layout = get_theme_mod( 'front_widget_title_layout', 'widget_title_layout1' );
    $classes[] = $front_widget_title_layout;

    $widget_post_border_option = get_theme_mod( 'widgets_posts_border_option' );
    if( $widget_post_border_option === true ) {
        $classes[] = 'posts-border-on';
    }

	return $classes;
}
add_filter( 'body_class', 'editorial_pro_body_classes' );

/*----------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Breadcrumb for Editorial Pro
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'editorial_breadcrumbs' ) ) :
function editorial_breadcrumbs() {

    /**
     * Breadcrumbs Settings
     */
    $sep_value = get_theme_mod( 'editorial_bread_sep', '>' );
    $home_value = get_theme_mod( 'editorial_bread_home', __( 'Home', 'editorial-pro' ) );
    $bread_option = get_theme_mod( 'editorial_breadcrumbs_option', 'show' );

    // Get the query & post information
    wp_reset_postdata();

    // Do not display on the homepage
    if (  $bread_option != 'hide' ) {

        $text['home']     = esc_html( $home_value );
        $text['category'] = '%s'; // text for a category page
        $text['tax']      = '%s'; // text for a taxonomy page
        $text['search']   = '%s'; // text for a search results page
        $text['tag']      = '%s'; // text for a tag page
        $text['author']   = '%s'; // text for an author page
        $text['404']      = __( 'Error 404', 'editorial-pro' ); // text for the 404 page
        $showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
        $showOnHome  = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
        $delimiter   = $sep_value; // delimiter between crumbs
        $before      = '<span class="current">'; // tag before the current crumb
        $after       = '</span>'; // tag after the current crumb
        /* === END OF OPTIONS === */
        global $post;
        $homeLink = esc_url( home_url( '/' ) );
        $linkBefore = '<span typeof="v:Breadcrumb">';
        $linkAfter = '</span>';
        $linkAttr = ' rel="v:url" property="v:title"';
        $link = $linkBefore . '<a' . $linkAttr . ' href="%1$s">%2$s</a>' . $linkAfter;
        if (is_home() || is_front_page()) {
            if ($showOnHome == 1) echo '<div class="mt-bread-home" id="mt-breadcrumbs"><a href="' . $homeLink . '">' . $text['home'] . '</a></div>';
        } else {
            echo '<div class="mt-bread-home" id="mt-breadcrumbs" xmlns:v="http://rdf.data-vocabulary.org/#">' . sprintf($link, $homeLink, $text['home']) . $delimiter;
            
            if ( is_category() ) {
                $thisCat = get_category(get_query_var('cat'), false);
                if ($thisCat->parent != 0) {
                    $cats = get_category_parents($thisCat->parent, TRUE, $delimiter);
                    $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
                    $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
                    echo $cats;
                }
                echo $before . sprintf($text['category'], single_cat_title('', false)) . $after;
            } elseif( is_tax() ){
                $thisCat = get_category(get_query_var('cat'), false);
                if ($thisCat->parent != 0) {
                    $cats = get_category_parents($thisCat->parent, TRUE, $delimiter);
                    $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
                    $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
                    echo $cats;
                }
                echo $before . sprintf($text['tax'], single_cat_title('', false)) . $after;
            
            }elseif ( is_search() ) {
                echo $before . sprintf($text['search'], get_search_query()) . $after;
            } elseif ( is_day() ) {
                echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
                echo sprintf($link, get_month_link(get_the_time('Y'),get_the_time('m')), get_the_time('F')) . $delimiter;
                echo $before . get_the_time('d') . $after;
            } elseif ( is_month() ) {
                echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
                echo $before . get_the_time('F') . $after;
            } elseif ( is_year() ) {
                echo $before . get_the_time('Y') . $after;
            } elseif ( is_single() && !is_attachment() ) {
                 if( 'product' == get_post_type()){
                    $post_type = get_post_type_object(get_post_type());
                    $shop_page_url = get_permalink( wc_get_page_id( 'shop' ) );
                    printf($link,$shop_page_url . '/', $post_type->labels->singular_name);
                    if ($showCurrent == 1) echo $delimiter . $before . get_the_title() . $after;
                 }
                elseif ( get_post_type() != 'post' ) {
                    $post_type = get_post_type_object(get_post_type());
                    $slug = $post_type->rewrite;
                    printf($link, $homeLink . '/' . $slug['slug'] . '/', $post_type->labels->singular_name);
                    if ($showCurrent == 1) echo $delimiter . $before . get_the_title() . $after;
                } else {
                    $cat = get_the_category(); $cat = $cat[0];
                    $cats = get_category_parents($cat, TRUE, $delimiter);
                    if ($showCurrent == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
                    $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
                    $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
                    echo $cats;
                    if ($showCurrent == 1) echo $before . get_the_title() . $after;
                }
            } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
                $post_type = get_post_type_object(get_post_type());
                echo $before . $post_type->labels->singular_name . $after;
            } elseif ( is_attachment() ) {
                $parent = get_post($post->post_parent);
                $cat = get_the_category($parent->ID); $cat = $cat[0];
                $cats = get_category_parents($cat, TRUE, $delimiter);
                $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
                $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
                echo $cats;
                printf($link, get_permalink($parent), $parent->post_title);
                if ($showCurrent == 1) echo $delimiter . $before . get_the_title() . $after;
            } elseif ( is_page() && !$post->post_parent ) {
                if ($showCurrent == 1) echo $before . get_the_title() . $after;
            } elseif ( is_page() && $post->post_parent ) {
                $parent_id  = $post->post_parent;
                $breadcrumbs = array();
                while ($parent_id) {
                    $page = get_post($parent_id);
                    $breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));
                    $parent_id  = $page->post_parent;
                }
                $breadcrumbs = array_reverse($breadcrumbs);
                for ($i = 0; $i < count($breadcrumbs); $i++) {
                    echo $breadcrumbs[$i];
                    if ($i != count($breadcrumbs)-1) echo $delimiter;
                }
                if ($showCurrent == 1) echo $delimiter . $before . get_the_title() . $after;
            } elseif ( is_tag() ) {
                echo $before . sprintf($text['tag'], single_tag_title('', false)) . $after;
            } elseif ( is_author() ) {
                global $author;
                $userdata = get_userdata($author);
                echo $before . sprintf($text['author'], $userdata->display_name) . $after;
            } elseif ( is_404() ) {
                echo $before . $text['404'] . $after;
            }
            if ( get_query_var('paged') ) {
                if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
                echo __( 'Page', 'editorial-pro' ) . ' ' . get_query_var('paged');
                if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
            }
            echo '</div>';
        }

    }

}
endif;
