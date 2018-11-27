<?php
/**
 * Functions for rendering sidebar meta boxes in post or page area
 * 
 * @package Mystery Themes
 * @subpackage Editorial Pro
 * @since 1.0.0
 */

add_action( 'add_meta_boxes', 'editorial_pro_meta_sidebar', 10, 2 );
function editorial_pro_meta_sidebar() {
    add_meta_box(
        'editorial_pro_post_sidebar',
        __( 'Sidebar Position', 'editorial-pro' ),
        'editorial_pro_sidebar_callback',
        'post',
        'normal',
        'default'
    );
    add_meta_box(
        'editorial_pro_post_sidebar',
        __( 'Sidebar Position', 'editorial-pro' ),
        'editorial_pro_sidebar_callback',
        'page',
        'normal',
        'default'
    );
}

function editorial_pro_sidebar_callback( $post ) {

    /**
     * Available options for post sidebar
     */
    $editorial_pro_page_sidebar_option = array(
        'default-sidebar' => array(
            'id'        => 'default-sidebar',
            'value'     => 'default_sidebar',
            'label'     => __( 'Default Sidebar', 'editorial-pro' ),
            'thumbnail' => get_template_directory_uri() . '/assets/images/default-sidebar.png'
        ),
        'left-sidebar' => array(
            'id'        => 'left-sidebar',
            'value'     => 'left_sidebar',
            'label'     => __( 'Left Sidebar', 'editorial-pro' ),
            'thumbnail' => get_template_directory_uri() . '/assets/images/left-sidebar.png'
        ),
        'right-sidebar' => array(
            'id'        => 'right-sidebar',
            'value'     => 'right_sidebar',
            'label'     => __( 'Right Sidebar', 'editorial-pro' ),
            'thumbnail' => get_template_directory_uri() . '/assets/images/right-sidebar.png'
        ),        
        'no-sidebar-full-width' => array(
            'id'        => 'no-sidebar',
            'value'     => 'no_sidebar',
            'label'     => __( 'No Sidebar Full Width', 'editorial-pro' ),
            'thumbnail' => get_template_directory_uri() . '/assets/images/no-sidebar.png'
        ),
        'no-sidebar-content-centered' => array(
            'id'        => 'no-sidebar-center',
            'value'     => 'no_sidebar_center',
            'label'     => __( 'No Sidebar Content Centered', 'editorial-pro' ),
            'thumbnail' => get_template_directory_uri() . '/assets/images/no-sidebar-center.png'
        ),
    );

    // Check for previously set.
    $location = get_post_meta( $post->ID, 'editorial_pro_sidebar_location', true );
    // If it is then we use it otherwise set to default.
    $location = ( $location ) ? $location : 'default_sidebar';

    // Create our nonce field.
    wp_nonce_field( 'editorial_pro_nonce_' . basename( __FILE__ ) , 'editorial_pro_sidebar_location_nonce' );
?>
    <div class="mt-meta-options-wrap">
        <div class="buttonset">
        <?php foreach ( $editorial_pro_page_sidebar_option as $field ) { ?>
            <input type="radio" id="<?php echo esc_attr( $field['id'] ); ?>" name="editorial_pro_sidebar_location" value="<?php echo esc_attr( $field['value'] ); ?>" <?php checked( $field['value'], $location ); ?>/>
            <label for="<?php echo esc_attr( $field['id'] ); ?>">
                <span class="screen-reader-text"><?php echo esc_html( $field['label'] ); ?></span>
                <img src="<?php echo esc_url( $field['thumbnail'] ); ?>" title="<?php echo esc_attr( $field['label'] ); ?>" alt="<?php echo esc_attr( $field['label'] ); ?>" />
            </label>
        <?php } ?>
        </div><!-- .buttonset -->
    </div><!-- .mt-meta-options-wrap -->
<?php
    }

add_action( 'save_post', 'editorial_pro_save_post_sidebar_meta' );
function editorial_pro_save_post_sidebar_meta( $post_id ) {
    // Checks save status
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST['editorial_pro_sidebar_location_nonce'] ) && wp_verify_nonce( $_POST['editorial_pro_sidebar_location_nonce'], 'editorial_pro_nonce_' . basename( __FILE__ ) ) ) ? 'true' : 'false';

    // Exits script depending on save status
    if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
        return;
    }

    // Check for out input value.
    if ( isset( $_POST['editorial_pro_sidebar_location'] ) ) {
        // We validate making sure that the option is something we can expect.
        $value = in_array( $_POST['editorial_pro_sidebar_location'], array( 'no_sidebar', 'left_sidebar', 'right_sidebar', 'no_sidebar_center', 'default_sidebar' ) ) ? $_POST['editorial_pro_sidebar_location'] : 'default_sidebar';
        // We update our post meta.
        update_post_meta( $post_id, 'editorial_pro_sidebar_location', $value );
    }
}