<?php
/**
 * Editorial: Fullwidth Tabbed
 *
 * A Widget to show the categories posts in tabbed formed.
 *
 * @package Mystery Themes
 * @subpackage Editorial Pro
 * @since 1.0.0
 */

class Editorial_Pro_Fullwidth_Tabbed extends WP_Widget {

	/**
     * Register widget with WordPress.
     */
    public function __construct() {
        $widget_ops = array( 
            'classname' => 'editorial_pro_fullwidth_tabbed',
            'description' => __( 'A widget to show the categories posts in fullwidth tabbed layout.', 'editorial-pro' )
        );
        parent::__construct( 'editorial_pro_fullwidth_tabbed', __( 'Editorial: Fullwidth Tabbed', 'editorial-pro' ), $widget_ops );
    }

    /**
     * Helper function that holds widget fields
     * Array is used in update and form functions
     */
    private function widget_fields() {

        global $post_in_row_array;
        $editorial_pro_category_dropdown = editorial_pro_category_dropdown();

        $fields = array(

            'fullwidth_tab_settings' => array(
                'editorial_pro_widgets_name'         => 'fullwidth_tab_settings',
                'editorial_pro_widgets_title'        => __( 'Tab Settings', 'editorial-pro' ),
                'editorial_pro_widgets_field_type'   => 'widget_section_header'
            ),

            'post_in_row' => array(
                'editorial_pro_widgets_name'         => 'post_in_row',
                'editorial_pro_widgets_title'        => __( 'Post in Row', 'editorial-pro' ),
                'editorial_pro_widgets_default'      => 3,
                'editorial_pro_widgets_field_type'   => 'select',
                'editorial_pro_widgets_field_options'=> $post_in_row_array
            ),

            'tab_content_layout' => array(
                'editorial_pro_widgets_name'         => 'tab_content_layout',
                'editorial_pro_widgets_title'        => __( 'Tab Content Layout', 'editorial-pro' ),
                'editorial_pro_widgets_default'      => 'block_view',
                'editorial_pro_widgets_field_type'   => 'radio',
                'editorial_pro_widgets_field_options'=> array(
                                                            'block_view' => __( 'Block View', 'editorial-pro' ),
                                                            'grid_view' => __( 'Grid View', 'editorial-pro' ),
                                                        )
            ),

            'tab_subcats_option' => array(
                'editorial_pro_widgets_name'         => 'tab_subcats_option',
                'editorial_pro_widgets_title'        => __( 'Display Posts from Sub-categories', 'editorial-pro' ),
                'editorial_pro_widgets_field_type'   => 'checkbox'
            ),

            'tab_post_count' => array(
                'editorial_pro_widgets_name'         => 'tab_post_count',
                'editorial_pro_widgets_title'        => __( 'No. of Posts', 'editorial-pro' ),
                'editorial_pro_widgets_default'      => 3,
                'editorial_pro_widgets_field_type'   => 'number'
            ),

            'post_excerpt_length' => array(
                'editorial_pro_widgets_name'         => 'post_excerpt_length',
                'editorial_pro_widgets_title'        => __( 'No. of Words', 'editorial-pro' ),
                'editorial_pro_widgets_default'      => 50,
                'editorial_pro_widgets_field_type'   => 'number'
            ),

            'first_tab_section_start' => array(
                'editorial_pro_widgets_name'         => 'first_tab_section_start',
                'editorial_pro_widgets_class'        => 'tab_widget_sec',
                'editorial_pro_widgets_title'        => __( 'First Tab', 'editorial-pro' ),
                'editorial_pro_widgets_field_type'   => 'widget_section_wrapper_start'
            ),

            'first_tab_icon' => array(
                'editorial_pro_widgets_name'         => 'first_tab_icon',
                'editorial_pro_widgets_title'        => __( 'Tab Icon', 'editorial-pro' ),
                'editorial_pro_widgets_description'  => __( 'Use only Font Awesome icon class eg.( fa-globe )', 'editorial-pro' ),
                'editorial_pro_widgets_field_type'   => 'text'
            ),

            'first_tab_title' => array(
                'editorial_pro_widgets_name'         => 'first_tab_title',
                'editorial_pro_widgets_title'        => __( 'Tab Title', 'editorial-pro' ),
                'editorial_pro_widgets_field_type'   => 'text'
            ),

            'first_tab_category' => array(
                'editorial_pro_widgets_name' => 'first_tab_category',
                'editorial_pro_widgets_title' => __( 'Category for first tab', 'editorial-pro' ),
                'editorial_pro_widgets_default'      => 0,
                'editorial_pro_widgets_field_type' => 'select',
                'editorial_pro_widgets_field_options' => $editorial_pro_category_dropdown
            ),

            'first_tab_section_end' => array(
                'editorial_pro_widgets_name'         => 'first_tab_section_end',
                'editorial_pro_widgets_field_type'   => 'widget_section_wrapper_end'
            ),

            'second_tab_section_start' => array(
                'editorial_pro_widgets_name'         => 'second_tab_section_start',
                'editorial_pro_widgets_class'        => 'tab_widget_sec',
                'editorial_pro_widgets_title'        => __( 'Second Tab', 'editorial-pro' ),
                'editorial_pro_widgets_field_type'   => 'widget_section_wrapper_start'
            ),

            'second_tab_icon' => array(
                'editorial_pro_widgets_name'         => 'second_tab_icon',
                'editorial_pro_widgets_title'        => __( 'Tab Icon', 'editorial-pro' ),
                'editorial_pro_widgets_description'  => __( 'Use only Font Awesome icon class eg.( fa-globe )', 'editorial-pro' ),
                'editorial_pro_widgets_field_type'   => 'text'
            ),

            'second_tab_title' => array(
                'editorial_pro_widgets_name'         => 'second_tab_title',
                'editorial_pro_widgets_title'        => __( 'Tab Title', 'editorial-pro' ),
                'editorial_pro_widgets_field_type'   => 'text'
            ),

            'second_tab_category' => array(
                'editorial_pro_widgets_name' => 'second_tab_category',
                'editorial_pro_widgets_title' => __( 'Category for second tab', 'editorial-pro' ),
                'editorial_pro_widgets_default'      => 0,
                'editorial_pro_widgets_field_type' => 'select',
                'editorial_pro_widgets_field_options' => $editorial_pro_category_dropdown
            ),

            'second_tab_section_end' => array(
                'editorial_pro_widgets_name'         => 'second_tab_section_end',
                'editorial_pro_widgets_field_type'   => 'widget_section_wrapper_end'
            ),

            'third_tab_section_start' => array(
                'editorial_pro_widgets_name'         => 'third_tab_section_start',
                'editorial_pro_widgets_class'        => 'tab_widget_sec',
                'editorial_pro_widgets_title'        => __( 'Third Tab', 'editorial-pro' ),
                'editorial_pro_widgets_field_type'   => 'widget_section_wrapper_start'
            ),

            'third_tab_icon' => array(
                'editorial_pro_widgets_name'         => 'third_tab_icon',
                'editorial_pro_widgets_title'        => __( 'Tab Icon', 'editorial-pro' ),
                'editorial_pro_widgets_description'  => __( 'Use only Font Awesome icon class eg.( fa-globe )', 'editorial-pro' ),
                'editorial_pro_widgets_field_type'   => 'text'
            ),

            'third_tab_title' => array(
                'editorial_pro_widgets_name'         => 'third_tab_title',
                'editorial_pro_widgets_title'        => __( 'Tab Title', 'editorial-pro' ),
                'editorial_pro_widgets_field_type'   => 'text'
            ),

            'third_tab_category' => array(
                'editorial_pro_widgets_name' => 'third_tab_category',
                'editorial_pro_widgets_title' => __( 'Category for third tab', 'editorial-pro' ),
                'editorial_pro_widgets_default'      => 0,
                'editorial_pro_widgets_field_type' => 'select',
                'editorial_pro_widgets_field_options' => $editorial_pro_category_dropdown
            ),

            'third_tab_section_end' => array(
                'editorial_pro_widgets_name'         => 'third_tab_section_end',
                'editorial_pro_widgets_field_type'   => 'widget_section_wrapper_end'
            ),

            'forth_tab_section_start' => array(
                'editorial_pro_widgets_name'         => 'forth_tab_section_start',
                'editorial_pro_widgets_class'        => 'tab_widget_sec',
                'editorial_pro_widgets_title'        => __( 'Fourth Tab', 'editorial-pro' ),
                'editorial_pro_widgets_field_type'   => 'widget_section_wrapper_start'
            ),

            'forth_tab_icon' => array(
                'editorial_pro_widgets_name'         => 'forth_tab_icon',
                'editorial_pro_widgets_title'        => __( 'Tab Icon', 'editorial-pro' ),
                'editorial_pro_widgets_description'  => __( 'Use only Font Awesome icon class eg.( fa-globe )', 'editorial-pro' ),
                'editorial_pro_widgets_field_type'   => 'text'
            ),

            'forth_tab_title' => array(
                'editorial_pro_widgets_name'         => 'forth_tab_title',
                'editorial_pro_widgets_title'        => __( 'Tab Title', 'editorial-pro' ),
                'editorial_pro_widgets_field_type'   => 'text'
            ),

            'forth_tab_category' => array(
                'editorial_pro_widgets_name' => 'forth_tab_category',
                'editorial_pro_widgets_title' => __( 'Category for forth tab', 'editorial-pro' ),
                'editorial_pro_widgets_default'      => 0,
                'editorial_pro_widgets_field_type' => 'select',
                'editorial_pro_widgets_field_options' => $editorial_pro_category_dropdown
            ),

            'forth_tab_section_end' => array(
                'editorial_pro_widgets_name'         => 'forth_tab_section_end',
                'editorial_pro_widgets_field_type'   => 'widget_section_wrapper_end'
            ),

            'fifth_tab_section_start' => array(
                'editorial_pro_widgets_name'         => 'fifth_tab_section_start',
                'editorial_pro_widgets_class'        => 'tab_widget_sec',
                'editorial_pro_widgets_title'        => __( 'Fifth Tab', 'editorial-pro' ),
                'editorial_pro_widgets_field_type'   => 'widget_section_wrapper_start'
            ),

            'fifth_tab_icon' => array(
                'editorial_pro_widgets_name'         => 'fifth_tab_icon',
                'editorial_pro_widgets_title'        => __( 'Tab Icon', 'editorial-pro' ),
                'editorial_pro_widgets_description'  => __( 'Use only Font Awesome icon class eg.( fa-globe )', 'editorial-pro' ),
                'editorial_pro_widgets_field_type'   => 'text'
            ),

            'fifth_tab_title' => array(
                'editorial_pro_widgets_name'         => 'fifth_tab_title',
                'editorial_pro_widgets_title'        => __( 'Tab Title', 'editorial-pro' ),
                'editorial_pro_widgets_field_type'   => 'text'
            ),

            'fifth_tab_category' => array(
                'editorial_pro_widgets_name' => 'fifth_tab_category',
                'editorial_pro_widgets_title' => __( 'Category for fifth tab', 'editorial-pro' ),
                'editorial_pro_widgets_default'      => 0,
                'editorial_pro_widgets_field_type' => 'select',
                'editorial_pro_widgets_field_options' => $editorial_pro_category_dropdown
            ),

            'fifth_tab_section_end' => array(
                'editorial_pro_widgets_name'         => 'fifth_tab_section_end',
                'editorial_pro_widgets_field_type'   => 'widget_section_wrapper_end'
            )
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

        $tab_post_in_row    = empty( $instance['post_in_row'] ) ? 2 : $instance['post_in_row'];
        $post_in_row_class  = 'posts'.$tab_post_in_row;
        $tab_content_layout = empty( $instance['tab_content_layout'] ) ? 'grid_view' : $instance['tab_content_layout'];
        $tab_subcats        = empty( $instance['tab_subcats_option'] ) ? '' : $instance['tab_subcats_option'];
        $post_count         = empty( $instance['tab_post_count'] ) ? 3 : $instance['tab_post_count'];
        $post_excerpt_length = empty( $instance['post_excerpt_length'] ) ? 50 : $instance['post_excerpt_length'];

        $first_tab_icon     = empty( $instance['first_tab_icon'] ) ? '' : $instance['first_tab_icon'];
        $first_tab_title    = empty( $instance['first_tab_title'] ) ? '' : $instance['first_tab_title'];
        $first_tab_cat_id   = empty( $instance['first_tab_category'] ) ? 0 : $instance['first_tab_category'];

        $second_tab_icon    = empty( $instance['second_tab_icon'] ) ? '' : $instance['second_tab_icon'];
        $second_tab_title   = empty( $instance['second_tab_title'] ) ? '' : $instance['second_tab_title'];
        $second_tab_cat_id  = empty( $instance['second_tab_category'] ) ? 0 : $instance['second_tab_category'];

        $third_tab_icon     = empty( $instance['third_tab_icon'] ) ? '' : $instance['third_tab_icon'];
        $third_tab_title    = empty( $instance['third_tab_title'] ) ? '' : $instance['third_tab_title'];
        $third_tab_cat_id   = empty( $instance['third_tab_category'] ) ? 0 : $instance['third_tab_category'];

        $forth_tab_icon     = empty( $instance['forth_tab_icon'] ) ? '' : $instance['forth_tab_icon'];
        $forth_tab_title    = empty( $instance['forth_tab_title'] ) ? '' : $instance['forth_tab_title'];
        $forth_tab_cat_id   = empty( $instance['forth_tab_category'] ) ? 0 : $instance['forth_tab_category'];

        $fifth_tab_icon     = empty( $instance['fifth_tab_icon'] ) ? '' : $instance['fifth_tab_icon'];
        $fifth_tab_title    = empty( $instance['fifth_tab_title'] ) ? '' : $instance['fifth_tab_title'];
        $fifth_tab_cat_id   = empty( $instance['fifth_tab_category'] ) ? 0 : $instance['fifth_tab_category'];

        if( empty( $first_tab_cat_id ) && empty( $second_tab_cat_id ) && empty( $third_tab_cat_id ) && empty( $forth_tab_cat_id ) && empty( $fifth_tab_cat_id ) ) {
            return;
        }

        $tab_cat_array = array();

        echo $before_widget;
    ?>
            <div class="fullwidth-tabbed-content-wrapper mt-tabbed-widget clearfix">
                <div class="ep-tabbed-header">
                    <ul class="widget-tabs ep-tab-links clearfix" id="mt-fullwidth-widget-tab">
                        <?php 
                            if( $first_tab_cat_id ) { 
                                $tab_cat_array[] = $first_tab_cat_id;
                                $first_tab_cat_slug = editorial_pro_get_cat_slug( $first_tab_cat_id );
                        ?>
                            <li class="cat-tab first-tabs">
                                <a href="#" data-catid="<?php echo intval( $first_tab_cat_id ); ?>" data-catslug="<?php echo esc_attr( $first_tab_cat_slug ); ?>"><?php editorial_pro_tabbed_title( $first_tab_icon, $first_tab_title, $first_tab_cat_id ); ?></a>
                            </li>
                        <?php } ?>
                        <?php 
                            if( $second_tab_cat_id ) { 
                                $tab_cat_array[] = $second_tab_cat_id;
                                $second_tab_cat_slug = editorial_pro_get_cat_slug( $second_tab_cat_id );
                        ?>
                            <li class="cat-tab second-tabs">
                                <a href="#" data-catid="<?php echo intval( $second_tab_cat_id ); ?>" data-catslug="<?php echo esc_attr( $second_tab_cat_slug ); ?>"><?php editorial_pro_tabbed_title( $second_tab_icon, $second_tab_title, $second_tab_cat_id ); ?></a>
                            </li>
                        <?php } ?>
                        <?php 
                            if( $third_tab_cat_id ) { 
                                $tab_cat_array[] = $third_tab_cat_id;
                                $third_tab_cat_slug = editorial_pro_get_cat_slug( $third_tab_cat_id );
                        ?>
                            <li class="cat-tab third-tabs">
                                <a href="#" data-catid="<?php echo intval( $third_tab_cat_id ); ?>" data-catslug="<?php echo esc_attr( $third_tab_cat_slug ); ?>"><?php editorial_pro_tabbed_title( $third_tab_icon, $third_tab_title, $third_tab_cat_id ); ?></a>
                            </li>
                        <?php } ?>
                        <?php 
                            if( $forth_tab_cat_id ) { 
                                $tab_cat_array[] = $forth_tab_cat_id;
                                $forth_tab_cat_slug = editorial_pro_get_cat_slug( $forth_tab_cat_id );
                        ?>
                            <li class="cat-tab forth-tabs">
                                <a href="#" data-catid="<?php echo intval( $forth_tab_cat_id ); ?>" data-catslug="<?php echo esc_attr( $forth_tab_cat_slug ); ?>"><?php editorial_pro_tabbed_title( $forth_tab_icon, $forth_tab_title, $forth_tab_cat_id ); ?></a>
                            </li>
                        <?php } ?>
                        <?php 
                            if( $fifth_tab_cat_id ) { 
                                $tab_cat_array[] = $fifth_tab_cat_id;
                                $fifth_tab_cat_slug = editorial_pro_get_cat_slug( $fifth_tab_cat_id );
                        ?>
                            <li class="cat-tab fifth-tabs">
                                <a href="#" data-catid="<?php echo intval( $fifth_tab_cat_id ); ?>" data-catslug="<?php echo esc_attr( $fifth_tab_cat_slug ); ?>"><?php editorial_pro_tabbed_title( $fifth_tab_icon, $fifth_tab_title, $fifth_tab_cat_id ); ?></a>
                            </li>
                        <?php } ?>
                        <?php
                            $top_tab_cat_id = reset( $tab_cat_array );
                            $top_tab_cat_slug = editorial_pro_get_cat_slug( $top_tab_cat_id );
                        ?>
                    </ul><!-- .widget-tabs -->
                </div><!-- .ep-tabbed-header -->

                <div class="tabbed-posts-wrapper grid-posts-block <?php echo esc_attr( $tab_content_layout ) .' '. esc_attr( $post_in_row_class ); ?>" data-postcount="<?php echo intval( $post_count ); ?>" data-subcats="<?php echo esc_attr( $tab_subcats ); ?>" data-excerptlength="<?php echo intval( $post_excerpt_length ); ?>">
                    <div class="content-loader" style="display:none;">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/AjaxLoader.gif">
                    </div>
                    <div class="tab-cat-content <?php echo esc_attr( $top_tab_cat_slug ); ?>">
                        <?php
                            $tabbed_post_args = editorial_pro_query_args( $top_tab_cat_id, $post_count, $tab_subcats );
                            $tabbed_post_query = new WP_Query( $tabbed_post_args );
                            if( $tabbed_post_query->have_posts() ) {
                                while( $tabbed_post_query->have_posts() ) {
                                    $tabbed_post_query->the_post();
                                    $title_size = 'small-size';
                        ?>
                                    <div class="single-post-wrapper ep-post-wrapper <?php editorial_pro_post_format_icon(); ?> clearfix">
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
                </div><!-- .tabbed-posts-wrapper -->
            </div><!-- .fullwidth-tabbed-content-wrapper -->
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