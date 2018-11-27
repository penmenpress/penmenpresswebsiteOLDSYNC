<?php
/**
 * Editorial: Posts List
 *
 * Widget show latest or random posts in list view
 *
 * @package Mystery Themes
 * @subpackage Editorial Pro
 * @since 1.0.0
 */

class Editorial_Pro_Posts_List extends WP_Widget {

	/**
     * Register widget with WordPress.
     */
    public function __construct() {
        $widget_ops = array( 
            'classname' => 'editorial_pro_posts_list',
            'description' => __( 'Display latest or random posts in list view.', 'editorial-pro' )
        );
        parent::__construct( 'editorial_pro_posts_list', __( 'Editorial: Posts Lists', 'editorial-pro' ), $widget_ops );
    }

    /**
     * Helper function that holds widget fields
     * Array is used in update and form functions
     */
    private function widget_fields() {

    	global $editorial_pro_post_list_option;
        
        $fields = array(

            'editorial_pro_block_title' => array(
                'editorial_pro_widgets_name'         => 'editorial_pro_block_title',
                'editorial_pro_widgets_title'        => __( 'Widget Title', 'editorial-pro' ),
                'editorial_pro_widgets_field_type'   => 'text'
            ),

            'editorial_pro_block_posts_count' => array(
                'editorial_pro_widgets_name'         => 'editorial_pro_block_posts_count',
                'editorial_pro_widgets_title'        => __( 'No. of Posts', 'editorial-pro' ),
                'editorial_pro_widgets_default'      => 4,
                'editorial_pro_widgets_field_type'   => 'number'
            ),

            'editorial_pro_block_posts_type' => array(
                'editorial_pro_widgets_name'         => 'editorial_pro_block_posts_type',
                'editorial_pro_widgets_title'        => __( 'Posts Type', 'editorial-pro' ),
                'editorial_pro_widgets_default'		 => 'latest',
                'editorial_pro_widgets_field_options'=> $editorial_pro_post_list_option,
                'editorial_pro_widgets_field_type'   => 'radio'
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

        $editorial_pro_block_title      	= empty( $instance['editorial_pro_block_title'] ) ? '' : $instance['editorial_pro_block_title'];
        $editorial_pro_block_posts_count    = intval( empty( $instance['editorial_pro_block_posts_count'] ) ? 4 : $instance['editorial_pro_block_posts_count'] );
        $editorial_pro_block_posts_type     = empty( $instance['editorial_pro_block_posts_type'] ) ? '' : $instance['editorial_pro_block_posts_type'];
        echo $before_widget;
?>
			<div class="widget-block-wrapper clearfix">
				<div class="block-header">
	                <h3 class="block-title"><?php echo esc_html( $editorial_pro_block_title ); ?></h3>
	            </div><!-- .block-header -->
	            <div class="posts-list-wrapper list-posts-block">
	            	<?php
	            		$posts_list_args = editorial_pro_query_args( $cat_id = null, $editorial_pro_block_posts_count );
	            		if( $editorial_pro_block_posts_type == 'random' ) {
	            			$posts_list_args['orderby'] = 'rand';
	            		}
	            		$posts_list_query = new WP_Query( $posts_list_args );
	            		if( $posts_list_query->have_posts() ) {
	            			while( $posts_list_query->have_posts() ) {
	            				$posts_list_query->the_post();
	                ?>
	                			<div class="single-post-wrapper ep-post-wrapper <?php editorial_pro_post_format_icon(); ?> clearfix">
                                    <div class="post-thumb-wrapper">
    	                                <a href="<?php the_permalink();?>" title="<?php the_title();?>">
    	                                    <figure>
                                                <?php 
                                                    if( has_post_thumbnail() ) {
                                                        the_post_thumbnail( 'editorial-block-thumb' );
                                                    } else {
                                                        $image_src = editorial_pro_image_fallback( 'editorial-block-thumb' );
                                                        echo '<img src="'. $image_src[0] .'"/>';
                                                    }                                                    
                                                ?>                                                    
                                            </figure>
    	                                </a>
                                    </div>
                                    <div class="post-content-wrapper">
                                        <h3 class="post-title small-size"><a href="<?php the_permalink();?>"><?php the_title(); ?></a></h3>
    	                                <div class="post-meta-wrapper">
    	                                    <?php editorial_pro_posted_on(); ?>
    	                                </div><!-- .post-meta-wrapper -->
                                    </div><!-- .post-content-wrapper -->
	                            </div><!-- .single-post-wrapper -->
	                <?php
	            			}
	            		}
	            		
	            	?>
	            </div><!-- .posts-list-wrapper -->
			</div><!-- .widget-block-wrapper -->
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