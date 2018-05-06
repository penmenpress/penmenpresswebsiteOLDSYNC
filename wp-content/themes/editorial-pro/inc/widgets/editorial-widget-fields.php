<?php
/**
 * Define custom fields for widgets
 * 
 * @package Mystery Themes
 * @subpackage Editorial Pro
 * @since 1.0.0
 */

function editorial_pro_widgets_show_widget_field( $instance = '', $widget_field = '', $athm_field_value = '' ) {
    
    extract( $widget_field );

    switch ( $editorial_pro_widgets_field_type ) {

    	// Standard text field
        case 'text' :
        ?>
            <p>
                <label for="<?php echo esc_attr( $instance->get_field_id( $editorial_pro_widgets_name ) ); ?>"><?php echo esc_html( $editorial_pro_widgets_title ); ?>:</label>
                <input class="widefat" id="<?php echo esc_attr ( $instance->get_field_id( $editorial_pro_widgets_name ) ); ?>" name="<?php echo esc_attr ( $instance->get_field_name( $editorial_pro_widgets_name ) ); ?>" type="text" value="<?php echo esc_html( $athm_field_value ); ?>" />

                <?php if ( isset( $editorial_pro_widgets_description ) ) { ?>
                    <br />
                    <small><?php echo esc_html( $editorial_pro_widgets_description ); ?></small>
                <?php } ?>
            </p>
        <?php
            break;

        // Standard url field
        case 'url' :
        ?>
            <p>
                <label for="<?php echo esc_attr( $instance->get_field_id( $editorial_pro_widgets_name ) ); ?>"><?php echo esc_html( $editorial_pro_widgets_title ); ?>:</label>
                <input class="widefat" id="<?php echo esc_attr ( $instance->get_field_id( $editorial_pro_widgets_name ) ); ?>" name="<?php echo esc_attr( $instance->get_field_name( $editorial_pro_widgets_name ) ); ?>" type="text" value="<?php echo esc_html( $athm_field_value ); ?>" />

                <?php if ( isset( $editorial_pro_widgets_description ) ) { ?>
                    <br />
                    <small><?php echo esc_html( $editorial_pro_widgets_description ); ?></small>
                <?php } ?>
            </p>
        <?php
            break;
        // Radio fields
        case 'radio' :
        	if( empty( $athm_field_value ) ) {
        		$athm_field_value = $editorial_pro_widgets_default;
        	}
        ?>
            <p>
                <label for="<?php echo esc_attr( $instance->get_field_id( $editorial_pro_widgets_name ) ); ?>"><?php echo esc_html( $editorial_pro_widgets_title ); ?>:</label>
                <div class="radio-wrapper">
                    <?php
                        foreach ( $editorial_pro_widgets_field_options as $athm_option_name => $athm_option_title ) {
                    ?>
                        <input id="<?php echo esc_attr( $instance->get_field_id( $athm_option_name ) ); ?>" name="<?php echo esc_attr( $instance->get_field_name( $editorial_pro_widgets_name ) ); ?>" type="radio" value="<?php echo esc_html( $athm_option_name ); ?>" <?php checked( $athm_option_name, $athm_field_value ); ?> />
                        <label for="<?php echo esc_attr( $instance->get_field_id( $athm_option_name ) ); ?>"><?php echo esc_html( $athm_option_title ); ?></label>
                    <?php } ?>
                </div>

                <?php if ( isset( $editorial_pro_widgets_description ) ) { ?>
                    <small><?php echo esc_html( $editorial_pro_widgets_description ); ?></small>
                <?php } ?>
            </p>
        <?php
            break;

        // Select field
        case 'select' :
            if( empty( $athm_field_value ) ) {
                $athm_field_value = $editorial_pro_widgets_default;
            }
        ?>
            <p>
                <label for="<?php echo esc_attr( $instance->get_field_id( $editorial_pro_widgets_name ) ); ?>"><?php echo esc_html( $editorial_pro_widgets_title ); ?>:</label>
                <select name="<?php echo esc_attr( $instance->get_field_name( $editorial_pro_widgets_name ) ); ?>" id="<?php echo esc_attr( $instance->get_field_id( $editorial_pro_widgets_name ) ); ?>" class="widefat">
                    <?php foreach ( $editorial_pro_widgets_field_options as $athm_option_name => $athm_option_title ) { ?>
                        <option value="<?php echo esc_attr( $athm_option_name ); ?>" id="<?php echo esc_attr( $instance->get_field_id($athm_option_name) ); ?>" <?php selected( $athm_option_name, $athm_field_value ); ?>><?php echo esc_html( $athm_option_title ); ?></option>
                    <?php } ?>
                </select>

                <?php if ( isset( $editorial_pro_widgets_description ) ) { ?>
                    <br />
                    <small><?php echo esc_html( $editorial_pro_widgets_description ); ?></small>
                <?php } ?>
            </p>
        <?php
            break;

        case 'number' :
        	if( empty( $athm_field_value ) ) {
        		$athm_field_value = $editorial_pro_widgets_default;
        	}
        ?>
            <p>
                <label for="<?php echo esc_attr( $instance->get_field_id( $editorial_pro_widgets_name ) ); ?>"><?php echo esc_html( $editorial_pro_widgets_title ); ?>:</label><br />
                <input name="<?php echo esc_attr( $instance->get_field_name( $editorial_pro_widgets_name ) ); ?>" type="number" step="1" min="1" id="<?php echo esc_attr( $instance->get_field_id( $editorial_pro_widgets_name ) ); ?>" value="<?php echo esc_html( $athm_field_value ); ?>" />

                <?php if ( isset( $editorial_pro_widgets_description ) ) { ?>
                    <br />
                    <small><?php echo esc_html( $editorial_pro_widgets_description ); ?></small>
                <?php } ?>
            </p>
       	<?php
            break;

        case 'widget_section_header':
        ?>
        	<p><span class="section-header <?php echo esc_attr( $editorial_pro_widgets_name ); ?>"><?php echo esc_html( $editorial_pro_widgets_title ); ?></span></p>
        <?php
        	break;

        case 'widget_section_wrapper_start':
        ?>
            <div class="widget-section-wrapper <?php echo esc_attr( $editorial_pro_widgets_class ); ?>">
                <span class="section-header"><?php echo esc_html( $editorial_pro_widgets_title ); ?></span>
                <span class="sec-click-icon">icon</span>
                    <div class="widget-sec-content" style="display:none;">
        <?php
            break;

        case 'widget_section_wrapper_end':
        ?>
            </div></div>
        <?php
            break;

        case 'widget_layout_image':
        ?>
            <div class="layout-image-wrapper">
                <span class="image-title"><?php echo esc_html( $editorial_pro_widgets_title ); ?></span>
                <img src="<?php echo esc_url( $editorial_pro_widgets_layout_img ); ?>" title="<?php echo esc_attr( 'Widget Layout', 'editorial-pro' ); ?>" />
            </div>
        <?php
            break;

        // Checkbox field
        case 'checkbox' :
        ?>
            <p>
                <input id="<?php echo esc_attr( $instance->get_field_id( $editorial_pro_widgets_name ) ); ?>" name="<?php echo esc_html( $instance->get_field_name( $editorial_pro_widgets_name ) ); ?>" type="checkbox" value="1" <?php checked('1', $athm_field_value); ?>/>
                <label for="<?php echo esc_attr( $instance->get_field_id( $editorial_pro_widgets_name ) ); ?>"><?php echo esc_html( $editorial_pro_widgets_title ); ?></label>

                <?php if ( isset( $editorial_pro_widgets_description ) ) { ?>
                    <br />
                    <small><?php echo esc_html( $editorial_pro_widgets_description ); ?></small>
                <?php } ?>
            </p>
        <?php
            break;

        case 'upload' :

            $output = '';
            $id = esc_attr( $instance->get_field_id( $editorial_pro_widgets_name ) );
            $class = '';
            $int = '';
            $value = $athm_field_value;
            $name = esc_attr( $instance->get_field_name( $editorial_pro_widgets_name ) );

            if ( $value ) {
                $class = ' has-file';
                $value = explode( 'wp-content', $value );
                $value = content_url().$value[1];
            }
            $output .= '<div class="sub-option widget-upload">';
            $output .= '<label for="' . esc_attr( $instance->get_field_id( $editorial_pro_widgets_name ) ) . '">' . esc_html( $editorial_pro_widgets_title ) . '</label><br/>';
            $output .= '<input id="' . $id . '" class="upload' . $class . '" type="text" name="' . $name . '" value="' . $value . '" placeholder="' . __( 'No file chosen', 'editorial-pro' ) . '" />' . "\n";
            if ( function_exists( 'wp_enqueue_media' ) ) {
                if ( ( $value == '') ) {
                    $output .= '<input id="upload-' . $id . '" class="wid-upload-button button" type="button" value="' . __( 'Upload', 'editorial-pro' ) . '" />' . "\n";
                } else {
                    $output .= '<input id="remove-' . $id . '" class="wid-remove-file button" type="button" value="' . __( 'Remove', 'editorial-pro' ) . '" />' . "\n";
                }
            } else {
                $output .= '<p><i>' . __( 'Upgrade your version of WordPress for full media support.', 'editorial-pro' ) . '</i></p>';
            }

            $output .= '<div class="screenshot upload-thumb" id="' . $id . '-image">' . "\n";

            if ( $value != '' ) {
                $remove = '<a class="remove-image">'. __( 'Remove', 'editorial-pro' ).'</a>';
                $image = preg_match( '/(^.*\.jpg|jpeg|png|gif|ico*)/i', $value );
                if ( $image ) {
                    $output .= '<img src="' . $value . '" alt="'.__( 'Upload image', 'editorial-pro' ).'" />';
                } else {
                    $parts = explode( "/", $value );
                    for ( $i = 0; $i < sizeof( $parts ); ++$i ) {
                        $title = $parts[$i];
                    }

                    // No output preview if it's not an image.
                    $output .= '';

                    // Standard generic output if it's not an image.
                    $title = __( 'View File', 'editorial-pro' );
                    $output .= '<div class="no-image"><span class="file_link"><a href="' . $value . '" target="_blank" rel="external">' . $title . '</a></span></div>';
                }
            }
            $output .= '</div></div>' . "\n";
            echo $output;
            break;

        case 'switch':
            if( empty( $athm_field_value ) ) {
                $athm_field_value = $editorial_pro_widgets_default;
            }
        ?>
            <p>
                <label for="<?php echo esc_attr( $instance->get_field_id( $editorial_pro_widgets_name ) ); ?>"><?php echo esc_html( $editorial_pro_widgets_title ); ?>:</label>
                <div class="widget_switch_options">
                    <?php 
                        foreach ( $editorial_pro_widgets_field_options as $key => $value ) {
                            if( $key == $athm_field_value ) {
                                echo '<span class="widget_switch_part '.$key.' selected" data-switch="'.$key.'">'. $value.'</span>';
                            } else {
                                echo '<span class="widget_switch_part '.$key.'" data-switch="'.$key.'">'. $value.'</span>';
                            }
                        }
                    ?>
                    <input type="hidden" id="<?php echo esc_attr( $instance->get_field_id( $key ) ); ?>" name="<?php echo esc_html( $instance->get_field_name( $editorial_pro_widgets_name ) ); ?>" value="<?php echo $athm_field_value; ?>" />
                </div>
            </p>
        <?php
            break;
    }
}

function editorial_pro_widgets_updated_field_value( $widget_field, $new_field_value ) {

    extract( $widget_field );

    // Allow only integers in number fields
    if ( $editorial_pro_widgets_field_type == 'number') {
        return editorial_pro_sanitize_number( $new_field_value );
    } elseif ( $editorial_pro_widgets_field_type == 'url' ) {
        return esc_url( $new_field_value );
    } else {
        return sanitize_text_field( $new_field_value );
    }
}