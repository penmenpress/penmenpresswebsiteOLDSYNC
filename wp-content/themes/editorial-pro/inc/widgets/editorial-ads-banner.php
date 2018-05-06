<?php
/**
 * Editorial: Banner Ads 
 *
 * Widget show the banner ads size of 728x90 (leaderboard) or large size of (300x250)
 *
 * @package Mystery Themes
 * @subpackage Editorial Pro
 * @since 1.0.0
 */

class Editorial_Pro_Ads_Banner extends WP_Widget {

	/**
     * Register widget with WordPress.
     */
    public function __construct() {
        $widget_ops = array( 
            'classname' => 'editorial_pro_ads_banner',
            'description' => __( 'You can place banner as advertisement with links.', 'editorial-pro' )
        );
        parent::__construct( 'editorial_pro_ads_banner', __( 'Editorial: Ads Banner', 'editorial-pro' ), $widget_ops );
    }

    /**
     * Helper function that holds widget fields
     * Array is used in update and form functions
     */
    private function widget_fields() {
        
        $ads_size = array(
                    'leaderboard'   => __( 'Leaderboard (728x90)', 'editorial-pro' ),
                    'large'         => __( 'Large (300x250)', 'editorial-pro' )
                    );
        
        $rel_attr_options = array(
            ''          => __( 'None', 'editorial-pro' ),
            'external'  => __( 'External Link', 'editorial-pro' ),
            'nofollow'  => __( 'Do Not Follow Link', 'editorial-pro' )
        );

        $fields = array(

            'banner_title' => array(
                'editorial_pro_widgets_name'         => 'banner_title',
                'editorial_pro_widgets_title'        => __( 'Title', 'editorial-pro' ),
                'editorial_pro_widgets_field_type'   => 'text'
            ),

            'banner_size' => array(
                'editorial_pro_widgets_name' => 'banner_size',
                'editorial_pro_widgets_title' => __( 'Ads Size', 'editorial-pro' ),
                'editorial_pro_widgets_default' => 'leaderboard',
                'editorial_pro_widgets_field_type' => 'radio',
                'editorial_pro_widgets_field_options' => $ads_size
            ),

            'banner_image' => array(
                'editorial_pro_widgets_name' => 'banner_image',
                'editorial_pro_widgets_title' => __( 'Add Image', 'editorial-pro' ),
                'editorial_pro_widgets_field_type' => 'upload',
            ),

            'banner_url' => array(
                'editorial_pro_widgets_name'         => 'banner_url',
                'editorial_pro_widgets_title'        => __( 'Add Url', 'editorial-pro' ),
                'editorial_pro_widgets_field_type'   => 'url'
            ),

            'ext_url' => array(
                'editorial_pro_widgets_name'         => 'ext_url',
                'editorial_pro_widgets_title'        => __( 'Open in new tab', 'editorial-pro' ),
                'editorial_pro_widgets_field_type'   => 'checkbox'
            ),

            'banner_rel' => array(
                'editorial_pro_widgets_name'           => 'banner_rel',
                'editorial_pro_widgets_default'        => '',
                'editorial_pro_widgets_title'          => __( 'Rel Attribute for URL Link', 'editorial-pro' ),
                'editorial_pro_widgets_field_options'  => $rel_attr_options,
                'editorial_pro_widgets_field_type'     => 'radio'
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

        $editorial_pro_banner_title  = empty( $instance['banner_title'] ) ? '' : $instance['banner_title'];
        $editorial_pro_banner_size   = empty( $instance['banner_size'] ) ? 'leaderboard' : $instance['banner_size'];
        $editorial_pro_banner_image  = empty( $instance['banner_image'] ) ? '' : $instance['banner_image'];
        $editorial_pro_banner_url    = empty( $instance['banner_url'] ) ? '' : $instance['banner_url'];
        $editorial_pro_ext_url       = empty( $instance['ext_url'] ) ? '' : $instance['ext_url'];
        $editorial_pro_banner_rel    = empty( $instance['banner_rel'] ) ? '' : $instance['banner_rel'];
        echo $before_widget;
        if( !empty( $editorial_pro_banner_image ) ) {
    ?>
            <div class="ads-wrapper <?php echo esc_attr( $editorial_pro_banner_size ); ?>">
                <?php if( !empty( $editorial_pro_banner_title ) ) { ?>
                    <h4 class="widget-title"><?php echo esc_html( $editorial_pro_banner_title ); ?></h4>
                <?php } ?>
                <?php
                    if( !empty( $editorial_pro_banner_url ) ) {
                ?>
                    <a href="<?php echo esc_url( $editorial_pro_banner_url );?>" <?php if( $editorial_pro_ext_url == 1 ) { echo 'target="_blank"'; }?> <?php if( !empty( $editorial_pro_banner_rel ) ) { echo 'rel="'. esc_attr( $editorial_pro_banner_rel ) .'"'; } ?>> <img src="<?php echo esc_url( $editorial_pro_banner_image ); ?>" /> </a>
                <?php
                    } else {
                ?>
                    <img src="<?php echo esc_url( $editorial_pro_banner_image ); ?>" />
                <?php
                    }
                ?>
            </div>
    <?php
        }
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
