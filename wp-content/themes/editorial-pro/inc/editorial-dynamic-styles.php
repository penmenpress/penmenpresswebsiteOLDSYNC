<?php
/**
 * Functions and codes for dynamic styles
 *
 * @package Mystery Themes
 * @subpackage Editorial Pro
 * @since 1.0.0
 */

add_action( 'wp_enqueue_scripts', 'editorial_pro_dynamic_styles' );

if ( ! function_exists( 'editorial_pro_dynamic_styles' ) ):
    function editorial_pro_dynamic_styles() {

        $mt_theme_color = esc_attr( get_theme_mod( 'editorial_pro_theme_color', '#0288d1' ) );
        $get_categories = get_terms( 'category', array( 'hide_empty' => false ) );
        $mt_theme_hov_color = editorial_pro_hover_color( $mt_theme_color, '-9' );

        $mt_widget_bg_color = esc_attr( get_theme_mod( 'footer_widget_bg_color', '#F7F7F7' ) );

        $mt_widget_text_color = esc_attr( get_theme_mod( 'footer_widget_text_color', '#333333' ) );
        
        $output_css = '';

        foreach( $get_categories as $category ){

            $cat_color = esc_attr( get_theme_mod( 'editorial_pro_category_color_'.strtolower( $category->name ), '#00a9e0' ) );
            $cat_hover_color = esc_attr( editorial_pro_hover_color( $cat_color, '-50' ) );
            $cat_id = esc_attr( $category->term_id );
            
            if( !empty( $cat_color ) ) {
                $output_css .= ".category-button.mt-cat-$cat_id a { background: $cat_color; }\n";

                $output_css .= ".category-button.mt-cat-$cat_id a:hover { background: $cat_hover_color; }\n";

                $output_css .= ".category-txt.mt-cat-$cat_id a { color: $cat_color; }\n";

                $output_css .= ".category-txt.mt-cat-$cat_id a:hover { color: $cat_hover_color; }\n";

                $output_css .= ".block-header.mt-cat-$cat_id { border-left: 2px solid $cat_color; }\n";
                 
                $output_css .= ".archive .page-header.mt-cat-$cat_id { border-left: 4px solid $cat_color; }\n";

                $output_css .= ".rtl.archive .page-header.mt-cat-$cat_id { border-left: none; border-right: 4px solid $cat_color; }\n";

                $output_css .= "#site-navigation ul li.mt-cat-$cat_id { border-bottom-color: $cat_color; }\n";

                $output_css .= ".widget_title_layout2 .block-header.mt-cat-$cat_id { border-bottom-color: $cat_color; }\n";
            }
        }

        $output_css .= ".navigation .nav-links a,.bttn,button,input[type='button'],input[type='reset'],input[type='submit'],.edit-link .post-edit-link,.reply .comment-reply-link,.home-icon,.search-main,.header-search-wrapper .search-form-main .search-submit,.ticker-caption,.mt-slider-section .lSAction a:hover,.mt-slider-section .lSSlideOuter .lSPager.lSpg > li.active a,.mt-slider-section .lSSlideOuter .lSPager.lSpg > li:hover a,.widget_search .search-submit,.widget_search .search-submit,.error404 .page-title,.archive.archive-classic .entry-title a:after,.archive-classic-post-wrapper .entry-title a:after,.archive-columns .ep-read-more a:hover,.archive-grid-post-wrapper .ep-read-more a:hover,.archive-grid .ep-read-more a:hover,.list-archive .ep-read-more a:hover,.archive-classic .ep-read-more a:hover,#mt-scrollup,.editorial_pro_default_tabbed ul li a,.editorial_pro_carousel .carousel-controls:hover,.single-post.post_layout_1 .default-poston,.sub-toggle,#site-navigation ul > li:hover > .sub-toggle, #site-navigation ul > li.current-menu-item .sub-toggle, #site-navigation ul > li.current-menu-ancestor .sub-toggle,
            .post-format-video:before, .post-format-audio:before, .post-format-gallery:before,.widget_tag_cloud .tagcloud a:hover, .sub-toggle,.navigation .nav-links a:hover, .bttn:hover,button,input[type='button']:hover, input[type='reset']:hover,
            input[type='submit']:hover, .editorial_pro_default_tabbed ul li.ui-state-active a, .editorial_pro_default_tabbed ul li:hover a,.editorial_pro_fullwidth_tabbed .ep-tab-links li a:hover, .editorial_pro_fullwidth_tabbed .ep-tab-links li.active a,.woocommerce .price-cart:after,.woocommerce ul.products li.product .price-cart .button:hover,.woocommerce .widget_price_filter .ui-slider .ui-slider-range,.woocommerce .widget_price_filter .ui-slider .ui-slider-handle,.woocommerce .widget_price_filter .price_slider_wrapper .ui-widget-content,.woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button,.woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt,.woocommerce #respond input#submit:hover, .woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover,.woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover,.woocommerce #respond input#submit.alt.disabled, .woocommerce #respond input#submit.alt.disabled:hover, .woocommerce #respond input#submit.alt:disabled, .woocommerce #respond input#submit.alt:disabled:hover, .woocommerce #respond input#submit.alt[disabled]:disabled, .woocommerce #respond input#submit.alt[disabled]:disabled:hover, .woocommerce a.button.alt.disabled, .woocommerce a.button.alt.disabled:hover, .woocommerce a.button.alt:disabled, .woocommerce a.button.alt:disabled:hover, .woocommerce a.button.alt[disabled]:disabled, .woocommerce a.button.alt[disabled]:disabled:hover, .woocommerce button.button.alt.disabled, .woocommerce button.button.alt.disabled:hover, .woocommerce button.button.alt:disabled, .woocommerce button.button.alt:disabled:hover, .woocommerce button.button.alt[disabled]:disabled, .woocommerce button.button.alt[disabled]:disabled:hover, .woocommerce input.button.alt.disabled, .woocommerce input.button.alt.disabled:hover, .woocommerce input.button.alt:disabled, .woocommerce input.button.alt:disabled:hover, .woocommerce input.button.alt[disabled]:disabled, .woocommerce input.button.alt[disabled]:disabled:hover,.woocommerce ul.products li.product .onsale, .woocommerce span.onsale { background: $mt_theme_color; }\n";

        $output_css .= "a,.entry-footer a:hover,.comment-author .fn .url:hover,#cancel-comment-reply-link,#cancel-comment-reply-link:before,.logged-in-as a,.top-menu ul li a:hover,#footer-navigation ul li a:hover,#site-navigation ul li a:hover,#site-navigation ul li.current-menu-item a,.header-layout-3 .search-main:hover,.mt-slider-section .slide-title a:hover,.featured-post-wrapper .featured-title a:hover,.editorial_pro_block_grid .post-title a:hover,.editorial_pro_fullwidth_tabbed .grid_view .post-title a:hover,.editorial_pro_block_list .block_layout_2 .first-post .post-title a:hover,.related-articles-wrapper.boxed_layout  .post-title a:hover,.slider-meta-wrapper span:hover,.slider-meta-wrapper a:hover,.featured-meta-wrapper span:hover,.featured-meta-wrapper a:hover,.post-meta-wrapper > span:hover,.post-meta-wrapper span > a:hover,.block-header .block-title a:hover,.widget .widget-title a:hover,.related-articles-wrapper .related-title a:hover,.grid-posts-block .post-title a:hover,.list-posts-block .single-post-wrapper .post-content-wrapper .post-title a:hover,.column-posts-block .single-post-wrapper.secondary-post .post-content-wrapper .post-title a:hover,.widget a:hover,.widget a:hover::before,.widget li:hover::before,.entry-title a:hover,.entry-meta span a:hover,.post-readmore a:hover,.archive-classic .entry-title a:hover,.archive-classic-post-wrapper .entry-title a:hover,.archive-columns .entry-title a:hover,.archive-grid-post-wrapper .entry-title a:hover,.list-archive .entry-title a:hover,.related-posts-wrapper .post-title a:hover,.editorial_pro_default_tabbed .single-post-wrapper .post-content-wrapper .post-title a:hover,.single-post.post_layout_1 .default-extra-meta .post-view::before, .single-post.post_layout_1 .default-extra-meta .comments-link::before,.mt-single-review-wrapper .stars-count,.mt-single-review-wrapper .review-percent,a:hover, a:focus, a:active,.widget_title_layout2 #top-footer .block-header .block-title,.widget_title_layout2 #top-footer .widget .widget-title,.widget_title_layout2 #top-footer .related-articles-wrapper .related-title,.widget_title_layout2 #top-footer .mt-single-review-wrapper  .review-title,#colophon a:hover{ color: $mt_theme_color; }\n";

        $output_css .= ".navigation .nav-links a,.bttn,button,input[type='button'],input[type='reset'],input[type='submit'],.widget_search .search-submit,.archive-columns .ep-read-more a:hover,.archive-grid-post-wrapper .ep-read-more a:hover,.archive-grid .ep-read-more a:hover,.list-archive .ep-read-more a:hover,.archive-classic .ep-read-more a:hover,.widget_tag_cloud .tagcloud a:hover,.woocommerce form .form-row.woocommerce-validated .select2-container, .woocommerce form .form-row.woocommerce-validated input.input-text, .woocommerce form .form-row.woocommerce-validated select{ border-color: $mt_theme_color; }\n";

        $output_css .= ".comment-list .comment-body ,.header-search-wrapper .search-form-main,.woocommerce .woocommerce-info,.woocommerce .woocommerce-message{ border-top-color: $mt_theme_color; }\n";

        $output_css .= "#site-navigation ul li,.header-search-wrapper .search-form-main:before,.widget_title_layout2 .block-header,.widget_title_layout2 .widget .widget-title-wrapper,.widget_title_layout2 .related-articles-wrapper .widget-title-wrapper,.widget_title_layout2 .archive .page-header{ border-bottom-color: $mt_theme_color; }\n";

        $output_css .= ".ticker-caption::after,.block-header,.widget .widget-title-wrapper,.related-articles-wrapper .widget-title-wrapper,.mt-single-review-wrapper .section-title,.archive .page-header,.mt-bread-home,.woocommerce .woocommerce-breadcrumb{ border-left-color: $mt_theme_color; }\n";

        $output_css .= ".rtl .ticker-caption::after,.rtl .block-header,.rtl .widget .widget-title-wrapper,.rtl .related-articles-wrapper .widget-title-wrapper,.rtl .mt-single-review-wrapper .section-title,.rtl.archive .page-header,.rtl .mt-bread-home,.rtl .woocommerce .woocommerce-breadcrumb{ border-left-color: none; border-right-color: $mt_theme_color; }\n";

        /**
        * hover color
        */
        $output_css .= ".editorial_pro_default_tabbed ul li.ui-state-active a, .editorial_pro_default_tabbed ul li a:hover { background: $mt_theme_hov_color; }\n";

        $output_css .= "a:hover,a:focus,a:active,{ color: $mt_theme_color; }\n";

        $output_css .= "#colophon{ background-color: $mt_widget_bg_color ; }\n";

        $output_css .= "#colophon,#colophon a{ color: $mt_widget_text_color ; }\n";

        /**
         * Menu typography
         */
        $menu_font_family = get_theme_mod( 'menu_font_family', 'Titillium Web' );
        $menu_font_style = get_theme_mod( 'menu_font_style', '400' );
        $menu_text_decoration = get_theme_mod( 'menu_text_decoration', 'none' );
        $menu_text_transform = get_theme_mod( 'menu_text_transform', 'none' );
        $menu_font_size = get_theme_mod( 'menu_font_size', '14' ) . 'px';
        $menu_line_height = get_theme_mod( 'menu_line_height', '38' ) . 'px';
        $menu_font_color = get_theme_mod( 'menu_font_color', '#ffffff' );

        if ( !empty( $menu_font_style ) ) {
            $menu_font_style_weight = preg_split( '/(?<=[0-9])(?=[a-z]+)/i', $menu_font_style );
            if ( isset( $menu_font_style_weight[1] ) ) {
                $menu_font_style = $menu_font_style_weight[1];
            } else {
                $menu_font_style = 'normal';
            }

            if ( isset( $menu_font_style_weight[0] ) ) {
                $menu_font_weight = $menu_font_style_weight[0];
            } else {
                $menu_font_weight = 400;
            }
        }
        $output_css .= "#site-navigation ul li a,.header-layout-2 #site-navigation ul li a,.header-layout-3 #site-navigation ul li a {
                            font-family: $menu_font_family;
                            font-style: $menu_font_style;
                            font-weight: $menu_font_weight;
                            text-decoration: $menu_text_decoration;
                            text-transform: $menu_text_transform;
                            font-size: $menu_font_size;
                            color: $menu_font_color;
                        }\n";

        $menu_sec_bg_color = get_theme_mod( 'editorial_pro_main_menu_bg_color', '#333333' );
        $menu_font_hover_color = get_theme_mod( 'editorial_pro_menu_hover_color', '#32B3D3' );

        $output_css .= ".bottom-header-wrapper,#site-navigation ul.sub-menu,.is-sticky .bottom-header-wrapper,.header-layout-3 .bottom-header-wrapper{ background: $menu_sec_bg_color; }\n";
        
        $output_css .= "@media (max-width: 768px) {#site-navigation { background: $menu_sec_bg_color !important; }}\n";

        $output_css .= "#site-navigation ul li,.home-icon,.header-layout-2 #site-navigation ul li{ border-color: $menu_font_hover_color; line-height: $menu_line_height; }\n";
        
        $output_css .= ".menu-toggle{ color: $menu_font_color; }\n";
        
        $output_css .= "#site-navigation ul > li:hover > .sub-toggle, #site-navigation ul > li.current-menu-item .sub-toggle, #site-navigation ul > li.current-menu-ancestor .sub-toggle{ background: $menu_font_hover_color !important; }\n";
        
        $output_css .= "#site-navigation ul li a:hover, #site-navigation ul li.current-menu-item a,.menu-toggle:hover { color: $menu_font_hover_color; }\n";

        /**
         * H1 typography
         */
        $h1_font_family = get_theme_mod( 'h1_font_family', 'Titillium Web' );
        $h1_font_style = get_theme_mod( 'h1_font_style', '700' );
        $h1_text_decoration = get_theme_mod( 'h1_text_decoration', 'none' );
        $h1_text_transform = get_theme_mod( 'h1_text_transform', 'none' );
        $h1_font_size = get_theme_mod( 'h1_font_size', '34' ) . 'px';
        $h1_line_height = get_theme_mod( 'h1_line_height', '1.5' );
        $h1_color = get_theme_mod( 'h1_color', '#353535' );

        if ( !empty( $h1_font_style ) ) {
            $h1_font_style_weight = preg_split( '/(?<=[0-9])(?=[a-z]+)/i', $h1_font_style );
            if ( isset( $h1_font_style_weight[1] ) ) {
                $h1_font_style = $h1_font_style_weight[1];
            } else {
                $h1_font_style = 'normal';
            }

            if ( isset( $h1_font_style_weight[0] ) ) {
                $h1_font_weight = $h1_font_style_weight[0];
            } else {
                $h1_font_weight = 700;
            }
        }
        $output_css .= "h1,.search-results .entry-title, 
                        .archive .entry-title, 
                        .single .entry-title, 
                        .entry-title{
                            font-family: $h1_font_family;
                            font-style: $h1_font_style;
                            font-size: $h1_font_size;
                            font-weight: $h1_font_weight;
                            text-decoration: $h1_text_decoration;
                            text-transform: $h1_text_transform;
                            line-height: $h1_line_height;
                            color: $h1_color;
                        }\n";

        /**
         * H2 typography
         */
        $h2_font_family = get_theme_mod( 'h2_font_family', 'Titillium Web' );
        $h2_font_style = get_theme_mod( 'h2_font_style', '700' );
        $h2_text_decoration = get_theme_mod( 'h2_text_decoration', 'none' );
        $h2_text_transform = get_theme_mod( 'h2_text_transform', 'none' );
        $h2_font_size = get_theme_mod( 'h2_font_size', '28' );
        $h2_line_height = get_theme_mod( 'h2_line_height', '1.5' );
        $h2_color = get_theme_mod( 'h2_color', '#353535' );

        $h2_title_font_size = $h2_font_size-2;

        if ( !empty( $h2_font_style ) ) {
            $h2_font_style_weight = preg_split( '/(?<=[0-9])(?=[a-z]+)/i', $h2_font_style );
            
            if ( isset( $h2_font_style_weight[1] ) ) {
                $h2_font_style = $h2_font_style_weight[1];
            } else {
                $h2_font_style = 'normal';
            }

            if ( isset( $h2_font_style_weight[0] ) ) {
                $h2_font_weight = $h2_font_style_weight[0];
            } else {
                $h2_font_weight = 700;
            }
        }
        $output_css .= "h2, h2 a,#top-footer .block-header .block-title,
#top-footer .widget .widget-title,#top-footer .related-articles-wrapper .related-title, #top-footer .mt-single-review-wrapper  .review-title ,.editorial-author-wrapper .author-desc-wrapper .author-title,.archive-classic .entry-title a,.archive-classic-post-wrapper .entry-title a{
                            font-family: $h2_font_family;
                            font-style: $h2_font_style;
                            font-weight: $h2_font_weight;
                            text-decoration: $h2_text_decoration;
                            text-transform: $h2_text_transform;
                            line-height: $h2_line_height;
                            color: $h2_color;
                        }\n";
        $output_css .= "h2, h2 a { font-size: ". $h2_font_size."px }\n";
        $output_css .= "h2.slide-title a, h2.post-title a { font-size: ". $h2_title_font_size."px }\n";

        /**
         * H3 typography
         */
        $h3_font_family = get_theme_mod( 'h3_font_family', 'Titillium Web' );
        $h3_font_style = get_theme_mod( 'h3_font_style', '700' );
        $h3_text_decoration = get_theme_mod( 'h3_text_decoration', 'none' );
        $h3_text_transform = get_theme_mod( 'h3_text_transform', 'none' );
        $h3_font_size = get_theme_mod( 'h3_font_size', '22' );
        $h3_line_height = get_theme_mod( 'h3_line_height', '1.5' );
        $h3_color = get_theme_mod( 'h3_color', '#333333' );

        $h3_large_font_size = $h3_font_size-4;
        $h3_small_font_size = $h3_font_size-7;

        if ( !empty( $h3_font_style ) ) {
            $h3_font_style_weight = preg_split( '/(?<=[0-9])(?=[a-z]+)/i', $h3_font_style );
            if ( isset( $h3_font_style_weight[1] ) ) {
                $h3_font_style = $h3_font_style_weight[1];
            } else {
                $h3_font_style = 'normal';
            }

            if ( isset( $h3_font_style_weight[0] ) ) {
                $h3_font_weight = $h3_font_style_weight[0];
            } else {
                $h3_font_weight = 400;
            }
        }
        $output_css .= "h3, h3 a, h3.post-title a,.grid-posts-block .post-title a, .column-posts-block .post-title a,.list-posts-block .single-post-wrapper .post-content-wrapper .post-title a, .column-posts-block .single-post-wrapper.secondary-post .post-content-wrapper .post-title a,.editorial_pro_default_tabbed .single-post-wrapper .post-content-wrapper .post-title a,.related-posts-wrapper .post-title a {
                            font-family: $h3_font_family;
                            font-style: $h3_font_style;
                            font-weight: $h3_font_weight;
                            text-decoration: $h3_text_decoration;
                            text-transform: $h3_text_transform;
                            line-height: $h3_line_height;
                            color: $h3_color;
                        }\n";
        $output_css .= "h3, h3 a { font-size: ". $h3_font_size."px }\n";
        $output_css .= "h3.post-title.large-size a { font-size: ". $h3_large_font_size."px }\n";
        $output_css .= "h3.post-title.small-size a { font-size: ". $h3_small_font_size."px }\n";

        /**
         * H4 typography
         */
        $h4_font_family = get_theme_mod( 'h4_font_family', 'Titillium Web' );
        $h4_font_style = get_theme_mod( 'h4_font_style', '700' );
        $h4_text_decoration = get_theme_mod( 'h4_text_decoration', 'none' );
        $h4_text_transform = get_theme_mod( 'h4_text_transform', 'none' );
        $h4_font_size = get_theme_mod( 'h4_font_size', '18' ) . 'px';
        $h4_line_height = get_theme_mod( 'h4_line_height', '1.5' );
        $h4_color = get_theme_mod( 'h4_color', '#333333' );

        if ( !empty( $h4_font_style ) ) {
            $h4_font_style_weight = preg_split( '/(?<=[0-9])(?=[a-z]+)/i', $h4_font_style );
            if ( isset( $h4_font_style_weight[1] ) ) {
                $h4_font_style = $h4_font_style_weight[1];
            } else {
                $h4_font_style = 'normal';
            }

            if ( isset( $h4_font_style_weight[0] ) ) {
                $h4_font_weight = $h4_font_style_weight[0];
            } else {
                $h4_font_weight = 400;
            }
        }
        $output_css .= "h4 {
                            font-family: $h4_font_family;
                            font-style: $h4_font_style;
                            font-size: $h4_font_size;
                            font-weight: $h4_font_weight;
                            text-decoration: $h4_text_decoration;
                            text-transform: $h4_text_transform;
                            line-height: $h4_line_height;
                            color: $h4_color;
                        }\n";

        /**
         * H5 typography
         */
        $h5_font_family = get_theme_mod( 'h5_font_family', 'Titillium Web' );
        $h5_font_style = get_theme_mod( 'h5_font_style', '700' );
        $h5_text_decoration = get_theme_mod( 'h5_text_decoration', 'none' );
        $h5_text_transform = get_theme_mod( 'h5_text_transform', 'none' );
        $h5_font_size = get_theme_mod( 'h5_font_size', '16' ) . 'px';
        $h5_line_height = get_theme_mod( 'h5_line_height', '1.5' );
        $h5_color = get_theme_mod( 'h5_color', '#333333' );

        if ( !empty( $h5_font_style ) ) {
            $h5_font_style_weight = preg_split( '/(?<=[0-9])(?=[a-z]+)/i', $h5_font_style );
            if ( isset( $h5_font_style_weight[1] ) ) {
                $h5_font_style = $h5_font_style_weight[1];
            } else {
                $h5_font_style = 'normal';
            }

            if ( isset( $h5_font_style_weight[0] ) ) {
                $h5_font_weight = $h5_font_style_weight[0];
            } else {
                $h5_font_weight = 400;
            }
        }
        $output_css .= "h5 {
                            font-family: $h5_font_family;
                            font-style: $h5_font_style;
                            font-size: $h5_font_size;
                            font-weight: $h5_font_weight;
                            text-decoration: $h5_text_decoration;
                            text-transform: $h5_text_transform;
                            line-height: $h5_line_height;
                            color: $h5_color;
                        }\n";

        /**
         * H6 typography
         */
        $h6_font_family = get_theme_mod( 'h6_font_family', 'Titillium Web' );
        $h6_font_style = get_theme_mod( 'h6_font_style', '700' );
        $h6_text_decoration = get_theme_mod( 'h6_text_decoration', 'none' );
        $h6_text_transform = get_theme_mod( 'h6_text_transform', 'none' );
        $h6_font_size = get_theme_mod( 'h6_font_size', '14' ) . 'px';
        $h6_line_height = get_theme_mod( 'h6_line_height', '1.5' );
        $h6_color = get_theme_mod( 'h6_color', '#333333' );

        if ( !empty( $h6_font_style ) ) {
            $h6_font_style_weight = preg_split( '/(?<=[0-9])(?=[a-z]+)/i', $h6_font_style );
            if ( isset( $h6_font_style_weight[1] ) ) {
                $h6_font_style = $h6_font_style_weight[1];
            } else {
                $h6_font_style = 'normal';
            }

            if ( isset( $h6_font_style_weight[0] ) ) {
                $h6_font_weight = $h6_font_style_weight[0];
            } else {
                $h6_font_weight = 400;
            }
        }
        $output_css .= "h6 {
                            font-family: $h6_font_family;
                            font-style: $h6_font_style;
                            font-size: $h6_font_size;
                            font-weight: $h6_font_weight;
                            text-decoration: $h6_text_decoration;
                            text-transform: $h6_text_transform;
                            line-height: $h6_line_height;
                            color: $h6_color;
                        }\n";

        /**
         * Body typography
         */
        $p_font_family = get_theme_mod( 'p_font_family', 'Titillium Web' );
        $p_font_style = get_theme_mod( 'p_font_style', '400' );
        $p_text_decoration = get_theme_mod( 'p_text_decoration', 'none' );
        $p_text_transform = get_theme_mod( 'p_text_transform', 'none' );
        $p_font_size = get_theme_mod( 'p_font_size', '14' ) . 'px';
        $p_line_height = get_theme_mod( 'p_line_height', '1.5' );
        $p_color = get_theme_mod( 'p_color', '#656565' );

        if ( !empty( $p_font_style ) ) {
            $p_font_style_weight = preg_split( '/(?<=[0-9])(?=[a-z]+)/i', $p_font_style );
            if ( isset( $p_font_style_weight[1] ) ) {
                $p_font_style = $p_font_style_weight[1];
            } else {
                $p_font_style = 'normal';
            }

            if ( isset( $p_font_style_weight[0] ) ) {
                $p_font_weight = $p_font_style_weight[0];
            } else {
                $p_font_weight = 400;
            }
        }
        $output_css .= "body, p,.post-content-wrapper .post-content,.widget_archive a, .widget_categories a, .widget_recent_entries a, .widget_meta a, .widget_recent_comments li, .widget_rss li, .widget_pages li a, .widget_nav_menu li a {
                            font-family: $p_font_family;
                            font-style: $p_font_style;
                            font-size: $p_font_size;
                            font-weight: $p_font_weight;
                            text-decoration: $p_text_decoration;
                            text-transform: $p_text_transform;
                            line-height: $p_line_height;
                            color: $p_color;
                        }\n";

        $refine_output_css = editorial_pro_css_strip_whitespace( $output_css );

        wp_add_inline_style( 'editorial-pro-style', $refine_output_css );

    }
endif;