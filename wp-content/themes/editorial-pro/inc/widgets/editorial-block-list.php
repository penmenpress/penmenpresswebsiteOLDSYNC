<?php
/**
 * Editorial: Block Posts (List)
 *
 * Widget shows the posts in list view
 *
 * @package Mystery Themes
 * @subpackage Editorial Pro
 * @since 1.0.0
 */

class Editorial_Pro_Block_List extends WP_Widget {

	/**
     * Register widget with WordPress.
     */
    public function __construct() {
        $widget_ops = array( 
            'classname' => 'editorial_pro_block_list',
            'description' => __( 'Display posts in block list layout', 'editorial-pro' )
        );
        parent::__construct( 'editorial_pro_block_list', __( 'Editorial: Block Posts (List)', 'editorial-pro' ), $widget_ops );
    }

    /**
     * Helper function that holds widget fields
     * Array is used in update and form functions
     */
    private function widget_fields() {
        global $editorial_pro_block_list_layout;

        $editorial_pro_category_dropdown = editorial_pro_category_dropdown();
        
        $fields = array(

            'editorial_pro_block_title' => array(
                'editorial_pro_widgets_name'         => 'editorial_pro_block_title',
                'editorial_pro_widgets_title'        => __( 'Block Title', 'editorial-pro' ),
                'editorial_pro_widgets_field_type'   => 'text'
            ),

            'editorial_pro_block_cat_id' => array(
                'editorial_pro_widgets_name' => 'editorial_pro_block_cat_id',
                'editorial_pro_widgets_title' => __( 'Category for Block Layout', 'editorial-pro' ),
                'editorial_pro_widgets_default'      => 0,
                'editorial_pro_widgets_field_type' => 'select',
                'editorial_pro_widgets_field_options' => $editorial_pro_category_dropdown
            ),

            'editorial_pro_block_subcats' => array(
                'editorial_pro_widgets_name'         => 'editorial_pro_block_subcats',
                'editorial_pro_widgets_title'        => __( 'Display Posts from Sub-categories', 'editorial-pro' ),
                'editorial_pro_widgets_field_type'   => 'checkbox'
            ),

            'editorial_pro_block_posts_count' => array(
                'editorial_pro_widgets_name'         => 'editorial_pro_block_posts_count',
                'editorial_pro_widgets_title'        => __( 'No. of Posts', 'editorial-pro' ),
                'editorial_pro_widgets_default'      => 5,
                'editorial_pro_widgets_field_type'   => 'number'
            ),

            'editorial_pro_block_layout' => array(
                'editorial_pro_widgets_name'         => 'editorial_pro_block_layout',
                'editorial_pro_widgets_title'        => __( 'Available Layouts', 'editorial-pro' ),
                'editorial_pro_widgets_default'      => 'block_layout_1',
                'editorial_pro_widgets_field_type'   => 'radio',
                'editorial_pro_widgets_field_options' => $editorial_pro_block_list_layout
            ),

            'post_excerpt_length' => array(
                'editorial_pro_widgets_name'         => 'post_excerpt_length',
                'editorial_pro_widgets_title'        => __( 'No. of words', 'editorial-pro' ),
                'editorial_pro_widgets_default'      => 50,
                'editorial_pro_widgets_field_type'   => 'number'
            ),

        );
        return $fields;
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        extract( $args );
        if( empty( $instance ) ) {
            return ;
        }

        $editorial_pro_block_title          = empty( $instance['editorial_pro_block_title'] ) ? '' : $instance['editorial_pro_block_title'];
        $editorial_pro_block_cat_id         = empty( $instance['editorial_pro_block_cat_id'] ) ? '' : $instance['editorial_pro_block_cat_id'];
        $editorial_pro_subcats              = empty( $instance['editorial_pro_block_subcats'] ) ? '' : $instance['editorial_pro_block_subcats'];
        $editorial_pro_block_posts_count    = empty( $instance['editorial_pro_block_posts_count'] ) ? 4 : $instance['editorial_pro_block_posts_count'];
        $editorial_pro_block_layout         = empty( $instance['editorial_pro_block_layout'] ) ? 'block_layout_1' : $instance['editorial_pro_block_layout'];
        $post_excerpt_length = empty( $instance['post_excerpt_length'] ) ? 50 : $instance['post_excerpt_length'];

        echo $before_widget;
    ?>
            <div class="block-list-wrapper clearfix">
                
                <?php editorial_pro_block_title( $editorial_pro_block_title, $editorial_pro_block_cat_id, $editorial_pro_subcats ); ?>

                <div class="posts-list-wrapper clearfix column-posts-block <?php echo esc_attr( $editorial_pro_block_layout ); ?>">
                    <?php
                        $block_list_args = editorial_pro_query_args( $editorial_pro_block_cat_id, $editorial_pro_block_posts_count );
                        $block_list_query = new WP_Query( $block_list_args );
                        $post_count = 1 ;
                        if( $block_list_query->have_posts() ) {
                            while ( $block_list_query->have_posts() ) {
                                $block_list_query->the_post();
                                $post_id = get_the_ID();
                                if( $editorial_pro_block_layout == 'block_layout_2' && $post_count == 1 ) {
                                    $post_class = 'first-post';
                                    if( has_post_thumbnail() ) {
                                        $image_path = get_the_post_thumbnail( $post_id, 'editorial-single-large' );
                                    } else {
                                        $image_src = editorial_pro_image_fallback( 'editorial-single-large' );
                                        $image_path = '<img src="'. $image_src[0] .'"/>';
                                    }
                                } else {
                                    $post_class = '';
                                    if( has_post_thumbnail() ) {
                                        $image_path = get_the_post_thumbnail( $post_id, 'editorial-block-medium' );
                                    } else {
                                        $image_src = editorial_pro_image_fallback( 'editorial-block-medium' );
                                        $image_path = '<img src="'. $image_src[0] .'"/>';
                                    }
                                }

                    ?>
                                <div class="single-post-wrapper ep-post-wrapper <?php editorial_pro_post_format_icon(); ?> clearfix <?php echo esc_attr( $post_class ); ?>">
                                    <div class="post-thumb-wrapper">
                                        <a href="<?php the_permalink();?>" title="<?php the_title();?>">
                                            <figure><?php echo $image_path; ?></figure>
                                        </a>
                                    </div>
                                    <div class="post-content-wrapper">
                                        <?php 
                                            do_action( 'editorial_pro_post_categories' );
                                            if( $editorial_pro_block_layout == 'block_layout_2' && $post_count == 1 ) {
                                        ?>
                                            <h2 class="post-title"><a href="<?php the_permalink();?>"><?php the_title(); ?></a></h2>
                                        <?php } else { ?>
                                            <h3 class="post-title large-size"><a href="<?php the_permalink();?>"><?php the_title(); ?></a></h3>
                                        <?php } ?>
                                        <div class="post-meta-wrapper">
                                            <?php editorial_pro_posted_on(); ?>
                                            <?php editorial_pro_post_comment(); ?>
                                            <?php do_action( 'editorial_widget_post_review' ); ?>
                                        </div><!-- .post-meta-wrapper -->
                                        <div class="post-content">
                                            <?php
                                                $post_content = get_the_content();
                                                echo wp_trim_words( $post_content, $post_excerpt_length, ' ' );
                                            ?>
                                        </div><!-- .post-content -->
                                    </div><!-- .post-content-wrapper -->
                                </div><!-- .single-post-wrapper -->
                    <?php
                                $post_count++; 
                            }
                        }
                        wp_reset_postdata();
                    ?>
                </div><!-- .posts-list-wrapper-->
            </div><!-- .block-list-wrapper -->
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
     * @uses    editorial_pro_widgets_updated_field_value()     defined in editorial-widget-fields.php
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
     * @uses    editorial_pro_widgets_show_widget_field()       defined in widget-fields.php
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