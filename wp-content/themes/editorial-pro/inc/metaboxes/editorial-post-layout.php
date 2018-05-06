<?php
/**
 * Functions for rendering post layout meta
 * 
 * @package Mystery Themes
 * @subpackage Editorial Pro
 * @since 1.0.0
 */

add_action( 'add_meta_boxes', 'editorial_pro_meta_post_layout', 10, 2 );
function editorial_pro_meta_post_layout() {
    add_meta_box(
        'editorial_pro_post_layout',
        __( 'Post Layout', 'editorial-pro' ),
        'editorial_pro_post_layout_callback',
        'post',
        'normal',
        'default'
    );
}

function editorial_pro_post_layout_callback( $post ) {

    /**
     * Available options for post layout
     */
    $editorial_pro_post_layout_option = array(
        'global-layout' => array(
            'id'        => 'global-layout',
            'value'     => 'global_layout',
            'label'     => __( 'Global Layout', 'editorial-pro' ),
            'thumbnail' => get_template_directory_uri() . '/inc/admin/assets/images/default-layout.jpg'
        ),
        'post-layout-1' => array(
            'id'        => 'post-layout-1',
            'value'     => 'post_layout_1',
            'label'     => __( 'Post Layout 1', 'editorial-pro' ),
            'thumbnail' => get_template_directory_uri() . '/inc/admin/assets/images/post-layout-1.jpg'
        ),
        'post-layout-2' => array(
            'id'        => 'post-layout-2',
            'value'     => 'post_layout_2',
            'label'     => __( 'Post Layout 2', 'editorial-pro' ),
            'thumbnail' => get_template_directory_uri() . '/inc/admin/assets/images/post-layout-2.jpg'
        ),
        'post-layout-3' => array(
            'id'        => 'post-layout-3',
            'value'     => 'post_layout_3',
            'label'     => __( 'Post Layout 3', 'editorial-pro' ),
            'thumbnail' => get_template_directory_uri() . '/inc/admin/assets/images/post-layout-3.jpg'
        ),
        'post-layout-4' => array(
            'id'        => 'post-layout-4',
            'value'     => 'post_layout_4',
            'label'     => __( 'Post Layout 4', 'editorial-pro' ),
            'thumbnail' => get_template_directory_uri() . '/inc/admin/assets/images/post-layout-4.jpg'
        ),
        'post-layout-5' => array(
            'id'        => 'post-layout-5',
            'value'     => 'post_layout_5',
            'label'     => __( 'Post Layout 5', 'editorial-pro' ),
            'thumbnail' => get_template_directory_uri() . '/inc/admin/assets/images/post-layout-5.jpg'
        )
    );

    // Check for previously set.
    $get_layout = get_post_meta( $post->ID, 'editorial_pro_post_layout', true );
    // If it is then we use it otherwise set to default.
    $get_layout = ( $get_layout ) ? $get_layout : 'global_layout';

    // Create our nonce field.
    wp_nonce_field( 'editorial_pro_nonce_' . basename( __FILE__ ) , 'editorial_pro_post_layout_nonce' );
?>
    <div class="mt-meta-options-wrap">
        <div class="buttonset">
        <?php foreach ( $editorial_pro_post_layout_option as $field ) { ?>
            <input type="radio" id="<?php echo esc_attr( $field['id'] ); ?>" name="editorial_pro_post_layout" value="<?php echo esc_attr( $field['value'] ); ?>" <?php checked( $field['value'], $get_layout ); ?>/>
            <label for="<?php echo esc_attr( $field['id'] ); ?>">
                <span class="screen-reader-text"><?php echo esc_html( $field['label'] ); ?></span>
                <img src="<?php echo esc_url( $field['thumbnail'] ); ?>" title="<?php echo esc_attr( $field['label'] ); ?>" alt="<?php echo esc_attr( $field['label'] ); ?>" />
            </label>
        <?php } ?>
        </div><!-- .buttonset -->
    </div><!-- .mt-meta-options-wrap -->
<?php
}

/**
 * Save the post value
 */
add_action( 'save_post', 'editorial_pro_save_post_layout_meta' );

function editorial_pro_save_post_layout_meta( $post_id ) {
    
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST['editorial_pro_post_layout_nonce'] ) && wp_verify_nonce( $_POST['editorial_pro_post_layout_nonce'], 'editorial_pro_nonce_' . basename( __FILE__ ) ) ) ? 'true' : 'false';

    // Exits script depending on save status
    if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
        return;
    }

    // Check for out input value.
    if ( isset( $_POST['editorial_pro_post_layout'] ) ) {
        // We validate making sure that the option is something we can expect.
        $value = in_array( $_POST['editorial_pro_post_layout'], array( 'global_layout', 'post_layout_1', 'post_layout_2', 'post_layout_3', 'post_layout_4', 'post_layout_5' ) ) ? $_POST['editorial_pro_post_layout'] : 'post_layout_1';
        
        // We update our post meta.
        update_post_meta( $post_id, 'editorial_pro_post_layout', $value );
    }
}