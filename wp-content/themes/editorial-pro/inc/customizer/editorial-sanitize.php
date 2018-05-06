<?php
/**
 * Define function for customizer option sanitization.
 * 
 * @package Mystery Themes
 * @subpackage Editorial Pro
 * @since 1.0.0
 */

//Text
function editorial_pro_sanitize_text( $input ) {
    return wp_kses_post( force_balance_tags( $input ) );
}

/**
 * Sanitize checkbox
 *
 * @since 1.1.3
 */
function editorial_pro_sanitize_checkbox( $input ) {
    //returns true if checkbox is checked
    return ( ( isset( $input ) && true == $input ) ? true : false );
}

// Number
function editorial_pro_sanitize_number( $input ) {
    $output = absint( $input );
     return $output;
}

// Number Float-val
function editorial_pro_floatval( $input ) {
    $output = floatval( $input );
    return $output;
}

// site layout
function editorial_pro_sanitize_site_layout( $input ) {
    $valid_keys = array(
            'fullwidth_layout'  => __( 'Fullwidth Layout', 'editorial-pro' ),
            'boxed_layout'      => __( 'Boxed Layout', 'editorial-pro' )
        );
    if ( array_key_exists( $input, $valid_keys ) ) {
        return $input;
    } else {
        return '';
    }
}

// Switch option (enable/disable)
function editorial_pro_enable_switch_sanitize( $input ) {
    $valid_keys = array(
            'enable'  => __( 'Enable', 'editorial-pro' ),
            'disable' => __( 'Disable', 'editorial-pro' )
        );
    if ( array_key_exists( $input, $valid_keys ) ) {
        return $input;
    } else {
        return '';
    }
}

//switch option (show/hide)
function editorial_pro_show_switch_sanitize( $input ) {
    $valid_keys = array(
            'show'  => __( 'Show', 'editorial-pro' ),
            'hide'  => __( 'Hide', 'editorial-pro' )
        );
    if ( array_key_exists( $input, $valid_keys ) ) {
        return $input;
    } else {
        return '';
    }
}

//Archive page layout
function editorial_pro_sanitize_archive_layout( $input ) {
    $valid_keys = array(
        'classic'  => __( 'Classic Layout', 'editorial-pro' ),
        'columns'  => __( 'Columns Layout', 'editorial-pro' ),
        'grid'     => __( 'Classic with Grids Layout', 'editorial-pro' ),
        'list'     => __( 'List layout', 'editorial-pro' )
        );
    if ( array_key_exists( $input, $valid_keys ) ) {
        return $input;
    } else {
        return '';
    }
}

//Ticker layout
function editorial_pro_ticker_layout_sanitize( $input ) {
    $valid_keys = array(
            'ticker_layout_1' => __( 'Layout 1: ( Default Layout. )', 'editorial-pro' ),
            'ticker_layout_2' => __( 'Layout 2: ( Different style on caption and control. )', 'editorial-pro' )
        );
    if ( array_key_exists( $input, $valid_keys ) ) {
        return $input;
    } else {
        return '';
    }
}

// Post image hover type
function editorial_pro_image_hover_type( $input ) {
    $valid_keys = array(
            'zoomin'            => __( 'Zoom In', 'editorial-pro' ),
            'zoomin_rotate'     => __( 'Zoom In Rotate', 'editorial-pro' ),
            'zoomout'           => __( 'Zoom Out', 'editorial-pro' ),
            'zoomout_rotate'    => __( 'Zoom Out Rotate', 'editorial-pro' ),
            'shine'             => __( 'Shine', 'editorial-pro' ),
            'slanted_shine'     => __( 'Slanted Shine', 'editorial-pro' ),
            'grayscale'         => __( 'Grayscale', 'editorial-pro' ),
            'opacity'           => __( 'Opacity', 'editorial-pro' ),
            'flashing'          => __( 'Flashing', 'editorial-pro' ),
            'circle'            => __( 'Circle', 'editorial-pro' )
        );
    if ( array_key_exists( $input, $valid_keys ) ) {
        return $input;
    } else {
        return '';
    }
}

// Post categories list type
function editorial_pro_post_categories_list_type( $input ) {
    $valid_keys = array(
            'in_boxed'      => __( 'In Box', 'editorial-pro' ),
            'in_plain_text' => __( 'In Plain Text', 'editorial-pro' )
        );
    if ( array_key_exists( $input, $valid_keys ) ) {
        return $input;
    } else {
        return '';
    }
}

// Post related type
function editorial_pro_sanitize_related_type( $input ) {
    $valid_keys = array(
            'category' => __( 'by Category', 'editorial-pro' ),
            'tag'      => __( 'by Tags', 'editorial-pro' )
        );
    if ( array_key_exists( $input, $valid_keys ) ) { 
        return $input;
    } else {
        return '';
    }
}

// post related layout
function editorial_pro_sanitize_related_layout( $input ) {
    $valid_keys = array(
            'default_layout' => __( 'Default Layout', 'editorial-pro' ),
            'boxed_layout'   => __( 'Boxed Layout', 'editorial-pro' )
        );
    if ( array_key_exists( $input, $valid_keys ) ) { 
        return $input;
    } else {
        return '';
    }
}

// Pre loaders
function editorial_pro_sanitize_pre_loaders( $input ) {
    $valid_keys = array(
            'three_balls'       => __( '3 Balls', 'editorial-pro' ),
            'rectangles'        => __( 'Rectangles', 'editorial-pro' ),
            'steps'             => __( 'Steps', 'editorial-pro' ),
            'spinning_border'   => __( 'Spinning Border', 'editorial-pro' ),
            'single_bleep'      => __( 'Single Bleep', 'editorial-pro' ),
            'square'            => __( 'Square', 'editorial-pro' ),
            'hollow_circle'     => __( 'Hollow Circle', 'editorial-pro' ),
            'knight_rider'      => __( 'Knight Rider', 'editorial-pro' )
        );
    if ( array_key_exists( $input, $valid_keys ) ) { 
        return $input;
    } else {
        return '';
    }
}

//Footer widget columns
function editorial_pro_footer_widget_sanitize( $input ) {
    $valid_keys = array(
            'column1'   => __( 'One Column', 'editorial-pro' ),
            'column2'   => __( 'Two Columns', 'editorial-pro' ),
            'column3'   => __( 'Three Columns', 'editorial-pro' ),
            'column4'   => __( 'Four Columns', 'editorial-pro' )
        );
    if ( array_key_exists( $input, $valid_keys ) ) {
        return $input;
    } else {
        return '';
    }
}