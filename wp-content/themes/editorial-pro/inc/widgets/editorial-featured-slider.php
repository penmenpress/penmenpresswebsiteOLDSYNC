<?php
/**
 * Editorial: Homepage Featured Slider
 *
 * Homepage slider section with featured section
 *
 * @package Mystery Themes
 * @subpackage Editorial Pro
 * @since 1.0.0
 */

class Editorial_Pro_Featured_slider extends WP_Widget {

	/**
     * Register widget with WordPress.
     */
    public function __construct() {
        $widget_ops = array( 
            'classname' => 'editorial_pro_featured_slider clearfix',
            'description' => __( 'Display slider with featured posts.', 'editorial-pro' )
        );
        parent::__construct( 'editorial_pro_featured_slider', __( 'Editorial: Featured Slider', 'editorial-pro' ), $widget_ops );
    }

    /**
     * Helper function that holds widget fields
     * Array is used in update and form functions
     */
    private function widget_fields() {
	
    	global $editorial_pro_widget_posts_type, $editorial_pro_featured_sec_layout;

        $editorial_pro_category_dropdown = editorial_pro_category_dropdown();

    	$fields = array(

            'slider_header_section' => array(
                'editorial_pro_widgets_name' => 'slider_header_section',
                'editorial_pro_widgets_title' => __( 'Slider Section', 'editorial-pro' ),
                'editorial_pro_widgets_field_type' => 'widget_section_header'
            ),

            'editorial_pro_slider_category' => array(
                'editorial_pro_widgets_name' => 'editorial_pro_slider_category',
                'editorial_pro_widgets_title' => __( 'Category for Slider', 'editorial-pro' ),
                'editorial_pro_widgets_default'      => 0,
                'editorial_pro_widgets_field_type' => 'select',
                'editorial_pro_widgets_field_options' => $editorial_pro_category_dropdown
            ),

            'editorial_pro_slide_subcats' => array(
                'editorial_pro_widgets_name'         => 'editorial_pro_slide_subcats',
                'editorial_pro_widgets_title'        => __( 'Display Posts from Sub-categories', 'editorial-pro' ),
                'editorial_pro_widgets_field_type'   => 'checkbox'
            ),

            'is_slide_auto' => array(
                'editorial_pro_widgets_name' => 'is_slide_auto',
                'editorial_pro_widgets_title' => __( 'Slide Auto Play', 'editorial-pro' ),
                'editorial_pro_widgets_field_type' => 'checkbox'
            ),

            'is_slide_pager' => array(
                'editorial_pro_widgets_name' => 'is_slide_pager',
                'editorial_pro_widgets_title' => __( 'Display Slider Pager', 'editorial-pro' ),
                'editorial_pro_widgets_field_type' => 'checkbox'
            ),

            'is_slide_control' => array(
                'editorial_pro_widgets_name' => 'is_slide_control',
                'editorial_pro_widgets_title' => __( 'Display Slider Control', 'editorial-pro' ),
                'editorial_pro_widgets_field_type' => 'checkbox'
            ),            

            'slide_pause' => array(
                'editorial_pro_widgets_name' => 'slide_pause',
                'editorial_pro_widgets_title' => __( 'Slide pause time', 'editorial-pro' ),
                'editorial_pro_widgets_default'      => 3000,
                'editorial_pro_widgets_field_type' => 'number'
            ),

            'slide_speed' => array(
                'editorial_pro_widgets_name' => 'slide_speed',
                'editorial_pro_widgets_title' => __( 'Slide speed time', 'editorial-pro' ),
                'editorial_pro_widgets_default'      => 600,
                'editorial_pro_widgets_field_type' => 'number'
            ),

            'editorial_pro_slide_count' => array(
                'editorial_pro_widgets_name' => 'editorial_pro_slide_count',
                'editorial_pro_widgets_title' => __( 'No. of slides', 'editorial-pro' ),
                'editorial_pro_widgets_default' => 5,
                'editorial_pro_widgets_field_type' => 'number'
            ),

            'featured_header_section' => array(
                'editorial_pro_widgets_name' => 'featured_header_section',
                'editorial_pro_widgets_title' => __( 'Featured Posts Section', 'editorial-pro' ),
                'editorial_pro_widgets_field_type' => 'widget_section_header'
            ),

            'is_featured_sec' => array(
                'editorial_pro_widgets_name' => 'is_featured_sec',
                'editorial_pro_widgets_title' => __( 'Removed Featured Posts Section ', 'editorial-pro' ),
                'editorial_pro_widgets_field_type' => 'checkbox'
            ),

            'editorial_pro_featured_category' => array(
                'editorial_pro_widgets_name' => 'editorial_pro_featured_category',
                'editorial_pro_widgets_title' => __( 'Category for Featured Posts', 'editorial-pro' ),
                'editorial_pro_widgets_default'      => 0,
                'editorial_pro_widgets_field_type' => 'select',
                'editorial_pro_widgets_field_options' => $editorial_pro_category_dropdown
            ),

            'editorial_pro_featured_subcats' => array(
                'editorial_pro_widgets_name'         => 'editorial_pro_featured_subcats',
                'editorial_pro_widgets_title'        => __( 'Display Posts from Sub-categories', 'editorial-pro' ),
                'editorial_pro_widgets_field_type'   => 'checkbox'
            ),

            'slider_featured_sec_layout' => array(
                'editorial_pro_widgets_name' => 'slider_featured_sec_layout',
                'editorial_pro_widgets_title' => __( 'Available Layouts', 'editorial-pro' ),
                'editorial_pro_widgets_default' => 'featured_layout_1',
                'editorial_pro_widgets_field_type' => 'radio',
                'editorial_pro_widgets_field_options' => $editorial_pro_featured_sec_layout
            ),

        );
        return $fields;
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        extract( $args );
        if( empty( $instance ) ) {
            return ;
        }

        $editorial_pro_slider_category_id      = empty( $instance['editorial_pro_slider_category'] ) ? null : $instance['editorial_pro_slider_category'];
        $editorial_pro_slide_subcats           = empty( $instance['editorial_pro_slide_subcats'] ) ? '' : $instance['editorial_pro_slide_subcats'];
        $editorial_pro_slide_count             = empty( $instance['editorial_pro_slide_count'] ) ? 5 : $instance['editorial_pro_slide_count'];
        $editorial_pro_featured_category_id    = empty( $instance['editorial_pro_featured_category'] ) ? null : $instance['editorial_pro_featured_category'];
        $editorial_pro_featured_subcats           = empty( $instance['editorial_pro_featured_subcats'] ) ? '' : $instance['editorial_pro_featured_subcats'];
        $featured_sec_layout     = empty( $instance['slider_featured_sec_layout'] ) ? 'featured_layout_1' : $instance['slider_featured_sec_layout'];
        $slider_auto = empty( $instance['is_slide_auto'] ) ? null : $instance['is_slide_auto'];
        if( $slider_auto == 1 ) {
            $slide_auto = 'true';
        } else {
            $slide_auto = 'false';
        }
        $slider_pager = empty( $instance['is_slide_pager'] ) ? null : $instance['is_slide_pager'];
        if( $slider_pager == 1 ) {
            $slide_pager = 'true';
        } else {
            $slide_pager = 'false';
        }
        $slider_control = empty( $instance['is_slide_control'] ) ? null : $instance['is_slide_control'];
        if( $slider_control == 1 ) {
            $slide_control = 'true';
        } else {
            $slide_control = 'false';
        }
        $slider_speed = empty( $instance['slide_speed'] ) ? 0 : $instance['slide_speed'];
        $slider_pause = empty( $instance['slide_pause'] ) ? 0 : $instance['slide_pause'];

        $is_featured_sec = empty( $instance['is_featured_sec'] ) ? null : $instance['is_featured_sec'];
        if( $is_featured_sec == 1 ) {
            $slider_class = 'sliderfull';
            $slide_image_size = 'full';
        } else {
            $slider_class = '';
            $slide_image_size = 'editorial-slider-large';
        }

        echo $before_widget;
    ?>
        <div class="mt-featured-slider-wrapper clearfix ep-slider <?php echo esc_attr( $slider_class ); ?>" data-auto="<?php echo esc_attr( $slide_auto ); ?>" data-control="<?php echo esc_attr( $slide_control ); ?>" data-pager="<?php echo esc_attr( $slide_pager ); ?>" data-speed="<?php echo absint( $slider_speed ); ?>" data-pause="<?php echo absint( $slider_pause ); ?>">
            <div class="mt-slider-section">
                <?php
                    $slider_args = editorial_pro_query_args( $editorial_pro_slider_category_id, $editorial_pro_slide_count, $editorial_pro_slide_subcats );
                    $slider_query = new WP_Query( $slider_args );
                    if( $slider_query->have_posts() ) {
                        echo '<ul class="editorialSlider cS-hidden">';
                        while( $slider_query->have_posts() ) {
                            $slider_query->the_post();
                    ?>
                            <li class="ep-post-wrapper">
                                <a href="<?php the_permalink();?>" title="<?php the_title(); ?>">
                                    <figure>
                                        <?php
                                            if( has_post_thumbnail() ) {
                                                the_post_thumbnail( $slide_image_size );    
                                            } else {
                                                $image_src = editorial_pro_image_fallback( $slide_image_size );
                                                echo '<img src="'. $image_src[0] .'"/>';
                                            }
                                            
                                        ?>
                                    </figure>
                                </a>
                                <div class="slider-content-wrapper">
                                    <?php do_action( 'editorial_pro_post_categories' ); ?>
                                    <h2 class="slide-title large-size"><a href="<?php the_permalink();?>"><?php the_title(); ?></a></h2>
                                    <div class="post-meta-wrapper">
                                        <?php editorial_pro_posted_on(); ?>
                                        <?php editorial_pro_post_comment(); ?>
                                    </div>
                                </div><!-- .post-meta-wrapper -->
                            </li>
                    <?php
                        }
                        echo '</ul>';
                    }
                    wp_reset_postdata();
                ?>
            </div><!-- .mt-slider-section -->
        </div><!-- .mt-featured-slider-wrapper -->
        <?php if( $is_featured_sec != 1 ) { ?>
        <div class="featured-post-wrapper <?php echo esc_attr( $featured_sec_layout ); ?>">
            <?php
                if( $featured_sec_layout == 'featured_layout_3' ) {
                    $featured_posts = 4;
                    $title_size = 'small-size';
                    echo '<div class="posts-wrapper-layout3">';
                } else {
                    $featured_posts = 3;
                }
                $featured_args = editorial_pro_query_args( $editorial_pro_featured_category_id, $featured_posts, $editorial_pro_featured_subcats );
                $featured_query = new WP_Query( $featured_args );
                $total_post_count = $featured_query->post_count;
                if( $featured_query->have_posts() ) {
                    $post_count = 1;
                    while ( $featured_query->have_posts() ) {
                        $featured_query->the_post();
                        $post_id = get_the_ID();
                        if( has_post_thumbnail() ) {
                            $image_path = get_the_post_thumbnail( $post_id, 'editorial-featured-medium' );
                        } else {
                            $image_src = editorial_pro_image_fallback( 'editorial-featured-medium' );
                            $image_path = '<img src="'. $image_src[0] .'"/>';
                        }
                        if( $post_count == 1 && $featured_sec_layout == 'featured_layout_1' ) {
                            $title_size = 'small-size';
                            echo '<div class="featured-left-section">';
                        } elseif( $post_count == 3 && $featured_sec_layout == 'featured_layout_1' ) {
                            $title_size = 'small-size';
                            if( has_post_thumbnail() ) {
                                $image_path = get_the_post_thumbnail( $post_id, 'editorial-featured-long' );
                            } else {
                                $image_src = editorial_pro_image_fallback( 'editorial-featured-long' );
                                $image_path = '<img src="'. $image_src[0] .'"/>';
                            }
                            echo '<div class="featured-right-section">';
                        } elseif( $post_count == 1 && $featured_sec_layout == 'featured_layout_2' ) {
                            $title_size = 'large-size';
                            if( has_post_thumbnail() ) {
                                $image_path = get_the_post_thumbnail( $post_id, 'editorial-horizontal-thumb' );
                            } else {
                                $image_src = editorial_pro_image_fallback( 'editorial-horizontal-thumb' );
                                $image_path = '<img src="'. $image_src[0] .'"/>';
                            }
                            echo '<div class="featured-top-section">';
                        } elseif( $post_count == 2 && $featured_sec_layout == 'featured_layout_2' ) {
                            $title_size = 'small-size';
                            echo '<div class="featured-bottom-section">';
                        } elseif ( $featured_sec_layout == 'featured_layout_3' ) {
                            if( has_post_thumbnail() ) {
                                $image_path = get_the_post_thumbnail( $post_id, 'editorial-featured-medium' );
                            } else {
                                $image_src = editorial_pro_image_fallback( 'editorial-featured-medium' );
                                $image_path = '<img src="'. $image_src[0] .'"/>';
                            }
                        }
            ?>
                    <div class="single-featured-wrap ep-post-wrapper <?php editorial_pro_post_format_icon(); ?>">
                        <a href="<?php the_permalink();?>" title="<?php the_title(); ?>">
                            <figure><?php echo $image_path; ?></figure>
                        </a>
                        <div class="featured-content-wrapper">
                            <?php do_action( 'editorial_pro_post_categories' ); ?>
                            <h3 class="featured-title <?php echo esc_attr( $title_size ); ?>"><a href="<?php the_permalink();?>"><?php the_title(); ?></a></h3>
                            <div class="post-meta-wrapper"> <?php editorial_pro_posted_on(); ?> </div>
                        </div><!-- .post-meta-wrapper -->
                    </div><!-- .single-featured-wrap -->
            <?php
                        if( $post_count == 2 && $featured_sec_layout == 'featured_layout_1' ) {
                            echo '</div><!-- .featured-left-section -->';
                        } elseif( $post_count == $total_post_count && $featured_sec_layout == 'featured_layout_1' ) {
                            echo '</div><!-- .featured-right-section -->';
                        } elseif( $post_count == 1 && $featured_sec_layout == 'featured_layout_2' ) {
                            echo '</div><!-- .featured-top-section -->';
                        } elseif ( $post_count == $total_post_count && $featured_sec_layout == 'featured_layout_2' ) {
                            echo '</div><!-- .featured-bottom-section -->';
                        }
                        $post_count++;
                    }
                }
                if( $featured_sec_layout == 'featured_layout_3' ) {
                    echo '</div>';
                }
                wp_reset_postdata();
            ?>
        </div><!-- .featured-post-wrapper -->
        <?php } ?>
        
    <?php
        echo $after_widget;
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param	array	$new_instance	Values just sent to be saved.
     * @param	array	$old_instance	Previously saved values from database.
     *
     * @uses	editorial_pro_widgets_updated_field_value()		defined in editorial-widget-fields.php
     *
     * @return	array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        $widget_fields = $this->widget_fields();

        // Loop through fields
        foreach ( $widget_fields as $widget_field ) {

            extract( $widget_field );

            // Use helper function to get updated field values
            $instance[$editorial_pro_widgets_name] = editorial_pro_widgets_updated_field_value( $widget_field, $new_instance[$editorial_pro_widgets_name] );
        }

        return $instance;
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param	array $instance Previously saved values from database.
     *
     * @uses	editorial_pro_widgets_show_widget_field()		defined in widget-fields.php
     */
    public function form( $instance ) {
        $widget_fields = $this->widget_fields();

        // Loop through fields
        foreach ( $widget_fields as $widget_field ) {

            // Make array elements available as variables
            extract( $widget_field );
            $editorial_pro_widgets_field_value = !empty( $instance[$editorial_pro_widgets_name] ) ? wp_kses_post( $instance[$editorial_pro_widgets_name] ) : '';
            editorial_pro_widgets_show_widget_field( $this, $widget_field, $editorial_pro_widgets_field_value );
        }
    }

}
