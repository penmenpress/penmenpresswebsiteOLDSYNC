<?php
/**
 * Editorial: Default Tabbed
 *
 * A Widget to show the popular posts, latest posts and comments
 *
 * @package Mystery Themes
 * @subpackage Editorial Pro
 * @since 1.0.0
 */

class Editorial_Pro_Default_Tabbed extends WP_Widget {

	/**
     * Register widget with WordPress.
     */
    public function __construct() {
        $widget_ops = array( 
            'classname' => 'editorial_pro_default_tabbed',
            'description' => __( 'A widget to show the popular, latest and comment section in single widget.', 'editorial-pro' )
        );
        parent::__construct( 'editorial_pro_default_tabbed', __( 'Editorial: Default Tabbed', 'editorial-pro' ), $widget_ops );
    }

    /**
     * Helper function that holds widget fields
     * Array is used in update and form functions
     */
    private function widget_fields() {

        $fields = array(

            'first_tab_section' => array(
                'editorial_pro_widgets_name'         => 'first_tab_section',
                'editorial_pro_widgets_title'        => __( 'Popular Posts Tab', 'editorial-pro' ),
                'editorial_pro_widgets_field_type'   => 'widget_section_header'
            ),

            'first_tab_title' => array(
                'editorial_pro_widgets_name'         => 'first_tab_title',
                'editorial_pro_widgets_title'        => __( 'Tab Title', 'editorial-pro' ),
                'editorial_pro_widgets_field_type'   => 'text'
            ),

            'first_tab_post_count' => array(
                'editorial_pro_widgets_name'         => 'first_tab_post_count',
                'editorial_pro_widgets_title'        => __( 'No. of. Posts', 'editorial-pro' ),
                'editorial_pro_widgets_default'      => 3,
                'editorial_pro_widgets_field_type'   => 'number'
            ),

            'second_tab_section' => array(
                'editorial_pro_widgets_name'         => 'second_tab_section',
                'editorial_pro_widgets_title'        => __( 'Latest Posts Tab', 'editorial-pro' ),
                'editorial_pro_widgets_field_type'   => 'widget_section_header'
            ),            

            'second_tab_title' => array(
                'editorial_pro_widgets_name'         => 'second_tab_title',
                'editorial_pro_widgets_title'        => __( 'Tab Title', 'editorial-pro' ),
                'editorial_pro_widgets_field_type'   => 'text'
            ),

            'second_tab_post_count' => array(
                'editorial_pro_widgets_name'         => 'second_tab_post_count',
                'editorial_pro_widgets_title'        => __( 'No. of. Posts', 'editorial-pro' ),
                'editorial_pro_widgets_default'      => 3,
                'editorial_pro_widgets_field_type'   => 'number'
            ),

            'third_tab_section' => array(
                'editorial_pro_widgets_name'         => 'third_tab_section',
                'editorial_pro_widgets_title'        => __( 'Comments Tab', 'editorial-pro' ),
                'editorial_pro_widgets_field_type'   => 'widget_section_header'
            ),            

            'third_tab_title' => array(
                'editorial_pro_widgets_name'         => 'third_tab_title',
                'editorial_pro_widgets_title'        => __( 'Tab Title', 'editorial-pro' ),
                'editorial_pro_widgets_field_type'   => 'text'
            ),

            'third_tab_post_count' => array(
                'editorial_pro_widgets_name'         => 'third_tab_post_count',
                'editorial_pro_widgets_title'        => __( 'No. of Comments', 'editorial-pro' ),
                'editorial_pro_widgets_default'      => 5,
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

        $first_tab_title = empty( $instance['first_tab_title'] ) ? __( 'Popular', 'editorial-pro' ) : $instance['first_tab_title'];
        $first_tab_post_count = empty( $instance['first_tab_post_count'] ) ? 3 : $instance['first_tab_post_count'];

        $second_tab_title = empty( $instance['second_tab_title'] ) ? __( 'Latest', 'editorial-pro' ) : $instance['second_tab_title'];
        $second_tab_post_count = empty( $instance['second_tab_post_count'] ) ? 3 : $instance['second_tab_post_count'];

        $third_tab_title = empty( $instance['third_tab_title'] ) ? __( 'Comments', 'editorial-pro' ) : $instance['third_tab_title'];
        $third_tab_post_count = empty( $instance['third_tab_post_count'] ) ? 5 : $instance['third_tab_post_count'];

        echo $before_widget;
    ?>
            <div class="default-tabbed-content-wrapper clearfix" id="mt-tabbed-widget">
                
                <ul class="widget-tabs clearfix" id="mt-widget-tab">
                    <li><a href="#popular"><?php echo esc_html( $first_tab_title ); ?></a></li>
                    <li><a href="#latest"><?php echo esc_html( $second_tab_title ); ?></a></li>
                    <li><a href="#comments"><?php echo esc_html( $third_tab_title ); ?></a></li>
                </ul><!-- .widget-tabs -->

                <div id="popular" class="mt-tabbed-section active">
                    <?php
                        $popular_args = array(
                                'posts_per_page' => $first_tab_post_count,
                                'meta_key' => 'editorial_post_views_count',
                                'orderby' => 'meta_value_num',
                            );
                        $popular_query = new WP_Query( $popular_args );
                        if( $popular_query->have_posts() ) {
                            while( $popular_query->have_posts() ) {
                                $popular_query->the_post();
                    ?>
                                <div class="single-post-wrapper ep-post-wrapper <?php editorial_pro_post_format_icon(); ?> clearfix">
                                    <div class="post-thumb">
                                        <a href="<?php the_permalink();?>" title="<?php the_title();?>">
                                            <?php
                                                if( has_post_thumbnail() ) {
                                                    the_post_thumbnail( 'editorial-block-thumb' );
                                                } else {
                                                    $img_src = editorial_pro_image_fallback( 'editorial-block-thumb' );
                                                    echo '<img src="' .$img_src[0] .'" />';
                                                }
                                            ?>
                                        </a>
                                    </div><!-- .post-thumb -->
                                    <div class="post-content-wrapper">
                                        <h3 class="post-title small-size"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                        <div class="post-meta-wrapper">
                                            <?php editorial_pro_posted_on(); ?>
                                        </div><!-- .post-meta-wrapper -->
                                    </div><!-- .post-content-wrap -->
                                </div><!-- .single-post-wrapper -->
                    <?php
                            }
                        }
                    ?>
                </div><!-- #sec-popular -->

                <div id="latest" class="mt-tabbed-section">
                    <?php
                        $latest_args = array(
                                'posts_per_page' => $second_tab_post_count
                            );
                        $latest_query = new WP_Query( $latest_args );
                        if( $latest_query->have_posts() ) {
                            while( $latest_query->have_posts() ) {
                                $latest_query->the_post();
                    ?>
                                <div class="single-post-wrapper <?php editorial_pro_post_format_icon(); ?> clearfix">
                                    <div class="post-thumb">
                                        <a href="<?php the_permalink();?>" title="<?php the_title();?>">
                                            <?php
                                                if( has_post_thumbnail() ) {
                                                    the_post_thumbnail( 'editorial-block-thumb' );
                                                } else {
                                                    $img_src = editorial_pro_image_fallback( 'editorial-block-thumb' );
                                                    echo '<img src="' .$img_src[0] .'" />';
                                                }
                                            ?>
                                        </a>
                                    </div><!-- .post-thumb -->
                                    <div class="post-content-wrapper">
                                        <h3 class="post-title small-size"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                        <div class="post-meta-wrapper">
                                            <?php editorial_pro_posted_on(); ?>
                                        </div><!-- .post-meta-wrapper -->
                                    </div><!-- .post-content-wrap -->
                                </div><!-- .single-post-wrapper -->
                    <?php
                            }
                        }
                    ?>
                </div><!-- #sec-latest -->

                <div id="comments" class="mt-tabbed-section">
                    <?php
                        $mt_tabbed_comments = get_comments( array( 'number' => $third_tab_post_count ) );
                        foreach( $mt_tabbed_comments as $comment  ) {
                    ?>
                            <li class="clearfix">
                                <?php
                                    $title = get_the_title( $comment->comment_post_ID );
                                    echo '<div class="mt-cmt-avatar">'. get_avatar( $comment, '55' ) .'</div>';
                                ?>
                                <div class="comment-desc-wrap">
                                    <strong><?php echo strip_tags( $comment->comment_author ); ?></strong>
                                    <?php esc_html_e( '&nbsp;commented on', 'editorial-pro' ); ?> 
                                    <a href="<?php echo get_permalink( $comment->comment_post_ID ); ?>" rel="external nofollow" title="<?php echo esc_attr( $title ); ?>"> <?php echo esc_html( $title ); ?></a>: <?php echo wp_html_excerpt( $comment->comment_content, 50 ); ?>
                                </div>
                            </li>
                    <?php
                        }
                    ?>
                </div><!-- #sec-comments -->
            </div><!-- .default-tabbed-content-wrapper -->
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

