<?php
 /**
 * Enable support for Post Thumbnails on posts and pages.
 *
 */

function elanzalite_grid_thumb($grid_layout, $thumb_crop=true){
    if($thumb_crop):
switch($grid_layout){
        case 'two-grid-layout':
             the_post_thumbnail('elanzalite-two-grid-thumb');
            break;
}    
endif;
}

if ( ! function_exists( 'elanzalite_the_custom_logo' ) ) :
/**
 * Displays the optional custom logo.
 *
 * Does nothing if the custom logo is not available.
 *
 */
function elanzalite_the_custom_logo() {
    if ( function_exists( 'the_custom_logo' ) ) {
        the_custom_logo();
    }
}
endif;


/*
 * Custom header menu
 *
*/
add_action( 'after_setup_theme', 'elanzalite_register_theme_menu' );
function elanzalite_register_theme_menu() {
  register_nav_menu( 'top-menu', __( 'Top Menu', 'elanzalite' ) );
  register_nav_menu( 'primary', __( 'Primary Menu', 'elanzalite' ) );
}
    function elanzalite_nav_menu(){
    	wp_nav_menu( array('theme_location' => 'primary', 
    	'container' => false, 
    		'menu_class' => 'menu', 
    		'menu_id'         => 'menu',
    		'fallback_cb'     => 'elanzalite_wp_page_menu'));
    }

   function elanzalite_wp_page_menu()
{
    echo '<ul class="menu" id="menu">';
    wp_list_pages(array('title_li' => ''));
    echo '</ul>';
}
function elanzalite_top_nav_menu(){
  if ( has_nav_menu('top-menu') ) {
      wp_nav_menu( array('theme_location' => 'top-menu', 
      'container' => false, 
        'menu_class' => 'top', 
        'menu_id'         => 'menu',
        'fallback_cb'     => 'elanzalite_top_wp_page_menu'));
    }
}
function elanzalite_top_wp_page_menu()
{
    echo '<ul class="top menu" id="menu">';
    wp_list_pages(array('title_li' => ''));
    echo '</ul>';
}
/**
 * Display navigation to next/previous post when applicable.
 *
 * @since ThemeHunk 1.0
 */

if ( ! function_exists( 'elanzalite_post_nav' ) ) :
function elanzalite_post_nav() {
    // Don't print empty markup if there's nowhere to navigate.
    ?>

    <nav class="navigation post-navigation" role="navigation">
        <div class="nav-links">
           <?php
              the_post_navigation( array(
                'next_text' => '<span class="meta-nav" aria-hidden="true">' . __( '%title', 'elanzalite' ) . '</span> ' ,
                'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __( '%title', 'elanzalite' )));
                //%title
            ?>
        </div><!-- .nav-links -->
    </nav><!-- .navigation -->
    <?php
}
endif;
/**
 * custom post excerpt
 */
function elanzalite_get_custom_excerpt(){
$excerpt_lenghth = get_theme_mod('excerpt_lenght','');  
if($excerpt_lenghth!==''){
$excerptlenghth=$excerpt_lenghth;
}else{
$excerptlenghth='200';  
}
$excerpt = get_the_content();
$excerpt = preg_replace(" (\[.*?\])",'',$excerpt);
$excerpt = strip_shortcodes($excerpt);
$excerpt = strip_tags($excerpt);
$excerpt = substr($excerpt, 0, $excerptlenghth);
$excerpt = substr($excerpt, 0, strripos($excerpt, " "));
$excerpt = trim(preg_replace( '/\s+/', ' ', $excerpt));
return $excerpt;
}

// related post
  function elanzalite_get_related_sigle_post() {
    global $post;
     $args = array(
               'category__in' => wp_get_post_categories($post->ID),
               'post__not_in' => array($post->ID),
                'post_status' => array('publish'),                         
                'meta_key' => '_thumbnail_id',
                'posts_per_page' => 3,
            );
        $my_query = new WP_Query($args);
        if ($my_query->have_posts()) {
            while ($my_query->have_posts()) : $my_query->the_post();
                ?>
               <li class="sl-related-thumbnail">
                    <div class="sl-related-thumbnail-size">
                        <?php
                       if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) {
                            ?>
                            <a href="<?php esc_url(the_permalink()); ?>"><?php the_post_thumbnail('elanzalite-related-post',array('class' => "postimg listing-thumbnail")); ?></a>
                            <?php
                        } ?>
                        </div>
                    <h3><a href="<?php esc_url(the_permalink()); ?>"><?php the_title(); ?></a></h3>
                </li>
                <?php
            endwhile;
        }
    wp_reset_postdata(); // to use the original query again
}

//pagination
function elanzalite_pagination() {

the_posts_pagination( array(
    'mid_size' => 2,
    'prev_text' => __( '&laquo;', 'elanzalite' ),
    'next_text' => __( '&raquo;', 'elanzalite' ),
) );
}

/*Number of comment*/
function elanzalite_comment_number(){ ?>
<span class="post-comment"><?php comments_popup_link(__('No Comment','elanzalite'), __('1 Comment','elanzalite'), __('% Comments','elanzalite')); ?></span>
<?php }

function elanzalite_userPic(){
     $address = get_the_author_meta('user_email');
     $nicname = get_the_author_meta('user_nicename');
     $pic = get_avatar( $address, 60, '', $nicname); 
    return $pic;
  }
function elanzalite_custom_header(){
    $bg  ='';
    $custom_css='';
    if(get_header_image()!=''){
          $bg ="background-image:url(".esc_url(get_header_image()).");";
         $custom_css .= "header{ {$bg} }";
    }
    $tc ="color:#".esc_attr(get_header_textcolor()).";";
   $custom_css .= ".home .header .logo p,.header .logo p{ {$tc} }";

                return $custom_css;
}
// ----------------------------//
// blog-layout choose function
//-----------------------------//
if (!function_exists( 'elanzalite_blog_get_layout' ) ) {
    function elanzalite_blog_get_layout( $default = 'right' ) {
    $layout = get_theme_mod( 'elanzalite_blog_layout', $default );
    return apply_filters( 'elanzalite_blog_get_layout', $layout, $default );
    }
}
// ----------------------------//
// blog-single-layout choose function
//-----------------------------//
if (!function_exists( 'elanzalite_blog_single_get_layout' ) ) {
    function elanzalite_blog_single_get_layout( $default = 'right' ) {
    $layout = get_theme_mod( 'elanzalite_blog_single_layout', $default );
    return apply_filters( 'elanzalite_blog_single_get_layout', $layout, $default );
    }
}
// ----------------------------//
// page-layout choose function
//-----------------------------//
if (!function_exists( 'elanzalite_page_get_layout' ) ) {
    function elanzalite_page_get_layout( $default = 'right' ) {
    $layout = get_theme_mod( 'elanzalite_page_layout', $default );
    return apply_filters( 'elanzalite_page_get_layout', $layout, $default );
    }
}
// ----------------------------//
// magazine-layout choose function
//-----------------------------//
if (!function_exists('elanzalite_magzine_get_layout' ) ) {
    function elanzalite_magzine_get_layout( $default = 'right' ) {
    $layout = get_theme_mod( 'elanzalite_magzine_layout', $default );
    return apply_filters( 'elanzalite_magzine_get_layout', $layout, $default );
    }
}
// ----------------------------//
// prefix archive hide
// ----------------------------//
if(get_theme_mod('archive_pre_hide')=='1'){
add_filter( 'get_the_archive_title','elanzalite_archive_prefix_hide');
}

function elanzalite_archive_prefix_hide($title) {
    if ( is_category() ) {

            $title = single_cat_title( '', false );

        } elseif ( is_tag() ) {

            $title = single_tag_title( '', false );

        } elseif ( is_author() ) {

            $title = '<span class="vcard">' . get_the_author() . '</span>' ;

        }

    return $title;
}