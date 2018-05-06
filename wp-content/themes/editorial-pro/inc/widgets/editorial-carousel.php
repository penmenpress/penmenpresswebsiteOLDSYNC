<?php
/**
 * Editorial: Carousel
 *
 * Widget to display latest or selected category posts as on carousel style.
 *
 * @package Mystery Themes
 * @subpackage Editorial Pro
 * @since 1.0.0
 */

class Editorial_Pro_Carousel extends WP_Widget {

	/**
     * Register widget with WordPress.
     */
    public function __construct() {
        $widget_ops = array( 
            'classname' => 'editorial_pro_carousel',
            'description' => __( 'A widget to display posts in carousel layout.', 'editorial-pro' )
        );
        parent::__construct( 'editorial_pro_carousel', __( 'Editorial : Carousel', 'editorial-pro' ), $widget_ops );
    }

    /**
     * Helper function that holds widget fields
     * Array is used in update and form functions
     */
    private function widget_fields() {

        global $editorial_pro_carousel_items, $editorial_pro_carousel_layout;

        $editorial_pro_category_dropdown = editorial_pro_category_dropdown();
        
        $fields = array(

            'block_title' => array(
                'editorial_pro_widgets_name'         => 'block_title',
                'editorial_pro_widgets_title'        => __( 'Block Title', 'editorial-pro' ),
                'editorial_pro_widgets_field_type'   => 'text'
            ),

            'block_post_category' => array(
                'editorial_pro_widgets_name' => 'block_post_category',
                'editorial_pro_widgets_title' => __( 'Category for Block Posts', 'editorial-pro' ),
                'editorial_pro_widgets_default'      => 0,
                'editorial_pro_widgets_field_type' => 'select',
                'editorial_pro_widgets_field_options' => $editorial_pro_category_dropdown
            ),

            'editorial_pro_block_subcats' => array(
                'editorial_pro_widgets_name'         => 'editorial_pro_block_subcats',
                'editorial_pro_widgets_title'        => __( 'Display Posts from Sub-categories', 'editorial-pro' ),
                'editorial_pro_widgets_field_type'   => 'checkbox'
            ),

            'editorial_pro_carousel_layout' => array(
                'editorial_pro_widgets_name'         => 'editorial_pro_carousel_layout',
                'editorial_pro_widgets_title'        => __( 'Available Layouts', 'editorial-pro' ),
                'editorial_pro_widgets_default'      => 'carousel_layout_1',
                'editorial_pro_widgets_field_type'   => 'radio',
                'editorial_pro_widgets_field_options' => $editorial_pro_carousel_layout
            ),

            'block_posts_col_count' => array(
                'editorial_pro_widgets_name'         => 'block_posts_col_count',
                'editorial_pro_widgets_title'        => __( 'No. of Items', 'editorial-pro' ),
                'editorial_pro_widgets_default'      => 3,
                'editorial_pro_widgets_field_type' => 'select',
                'editorial_pro_widgets_field_options' => $editorial_pro_carousel_items
            ),

            'block_posts_count' => array(
                'editorial_pro_widgets_name'         => 'block_posts_count',
                'editorial_pro_widgets_title'        => __( 'No. of Posts', 'editorial-pro' ),
                'editorial_pro_widgets_default'      => 3,
                'editorial_pro_widgets_field_type'   => 'number'
            )

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

        $editorial_pro_block_title          = empty( $instance['block_title'] ) ? '' : $instance['block_title'];
        $editorial_pro_block_cat_id         = empty( $instance['block_post_category'] ) ? null: $instance['block_post_category'];
        $editorial_pro_subcats              = empty( $instance['editorial_pro_block_subcats'] ) ? '' : $instance['editorial_pro_block_subcats'];
        $editorial_pro_block_layout         = empty( $instance['editorial_pro_carousel_layout'] ) ? 'carousel_layout_1': $instance['editorial_pro_carousel_layout'];
        $editorial_pro_block_posts_count    = empty( $instance['block_posts_count'] ) ? 3 : $instance['block_posts_count'];
        $editorial_pro_block_posts_col_count    = empty( $instance['block_posts_col_count'] ) ? 3 : $instance['block_posts_col_count'];

        if( $editorial_pro_block_layout == 'carousel_layout_2' ) {
            $carousel_class = 'portrait-layout';
        } else {
            $carousel_class = 'default-layout';
        }
    
        echo $before_widget;
    ?>
        <div class="block-post-wrapper clearfix ep-carousel <?php echo esc_attr( $carousel_class ); ?>" data-items="<?php echo absint( $editorial_pro_block_posts_col_count ); ?>">
            <div class="block-header-wrapper clearfix">
                <?php editorial_pro_block_title( $editorial_pro_block_title, $editorial_pro_block_cat_id, $editorial_pro_subcats ); ?>
                <div class="carousel-nav-action">
                    <div class="ep-navPrev carousel-controls"><i class="fa fa-angle-left"></i></div>
                    <div class="ep-navNext carousel-controls"><i class="fa fa-angle-right"></i></div>
                </div>
            </div><!-- .block-header-->
            <?php 
                $carousel_args = editorial_pro_query_args( $editorial_pro_block_cat_id, $editorial_pro_block_posts_count );
                $carousel_query = new WP_Query( $carousel_args );
                if( $carousel_query->have_posts() ) {
                    echo '<ul class="block-carousel cS-hidden">';
                    while( $carousel_query->have_posts() ) {
                        $carousel_query->the_post();
                        $post_id = get_the_ID();
                        if( $editorial_pro_block_layout == 'carousel_layout_2' ) {
                            if( has_post_thumbnail() ) {
                                $image_path = get_the_post_thumbnail( $post_id, 'editorial-block-portrait' );
                            } else {
                                $image_src = editorial_pro_image_fallback( 'editorial-block-portrait' );
                                $image_path = '<img src="'. $image_src[0] .'"/>';
                            }
                        } else {
                            if( has_post_thumbnail() ) {
                                $image_path = get_the_post_thumbnail( $post_id, 'editorial-block-medium' );
                            } else {
                                $image_src = editorial_pro_image_fallback( 'editorial-block-medium' );
                                $image_path = '<img src="'. $image_src[0] .'"/>';
                            }
                        }
            ?>
                        <li class="single-post ep-post-wrapper <?php editorial_pro_post_format_icon(); ?> clearfix grid-posts-block">
                            <div class="post-thumb">
                                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                                    <figure><?php echo $image_path; ?></figure>
                                </a>
                                <?php do_action( 'editorial_pro_post_format_icon' ); ?>
                            </div>
                            <div class="post-caption post-content-wrapper">
                                <?php do_action( 'editorial_pro_post_categories' ); ?>
                                <h3 class="post-title large-size"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <div class="post-meta-wrapper">
                                    <?php editorial_pro_posted_on(); ?>
                                    <?php editorial_pro_post_comment(); ?>
                                    <?php do_action( 'editorial_widget_post_review' ); ?>
                                </div><!-- .post-meta-wrapper -->
                            </div><!-- .post-caption -->
                        </li><!-- .single-post -->
            <?php
                    }
                    echo '</ul>';
                }
                wp_reset_query();
            ?>
        </div><!-- .block-post-wrapper -->
    <?php
        echo $after_widget;
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param   array   $new_instance   Values just sent to be saved.
     * @param   array   $old_instance   Previously saved values from database.
     *
     * @uses    editorial_pro_widgets_updated_field_value()      defined in editorial-widget-fields.php
     *
     * @return  array Updated safe values to be saved.
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
     * @param   array $instance Previously saved values from database.
     *
     * @uses    editorial_pro_widgets_show_widget_field()        defined in editorial-widget-fields.php
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