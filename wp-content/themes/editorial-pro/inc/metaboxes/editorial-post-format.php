<?php
/**
 * Functions for rendering post format meta
 * 
 * @package Mystery Themes
 * @subpackage Editorial Pro
 * @since 1.0.0
 */

add_action( 'add_meta_boxes', 'editorial_pro_meta_post_format', 10, 2 );

function editorial_pro_meta_post_format() {
    add_meta_box(
            'editorial_pro_post_formats',
            __( 'Post Format Settings', 'editorial-pro' ),
            'editorial_pro_post_formats_callback',
            'post',
            'normal',
            'high'
        );
}

function editorial_pro_post_formats_callback( $post ) {

	// Check for previously set.
    $get_post_video = get_post_meta( $post->ID, 'editorial_post_featured_video', true );
    $get_post_audio = get_post_meta( $post->ID, 'editorial_post_embed_audio', true );
    $post_gallery_images = get_post_meta( $post->ID, 'post_images', true );
    $post_images_count = get_post_meta( $post->ID, 'post_gallery_image_count', true );

	// Create our nonce field.
    wp_nonce_field( 'editorial_pro_nonce_' . basename( __FILE__ ) , 'editorial_pro_post_format_nonce' );
?>
		<div class="mt-meta-options-wrapper">

            <div class="single-meta-wrap" id="format-video">
                <div class="meta-title"><?php esc_html_e( 'Video Format', 'editorial-pro' ); ?></div>
                <div class="format-label"><strong><?php esc_html_e( 'Featured Video url', 'editorial-pro' );?></strong></div>
                <div class="format-input">
                    <input type="text" name="editorial_post_featured_video" size="90" class="post-featured-video" value="<?php echo esc_url( $get_post_video ); ?>" />
                    <input class="button" type="button" id="reset-video-embed" value="<?php echo esc_html( 'Reset video url ', 'editorial-pro' ) ;?>" />
                </div><!-- .format-input -->
                <span><em><?php echo esc_html( 'Paste a video link from Youtube, Vimeo it will be embedded in the post and the thumb used as the featured image of this post. ', 'editorial-pro' ); ?></em></span>
            </div><!-- #format-video -->

            <div class="single-meta-wrap" id="format-audio">
                <div class="meta-title"><?php esc_html_e( 'Audio Format', 'editorial-pro' ); ?></div>
                <div class="format-label"><strong><?php esc_html_e( 'Embed audio url', 'editorial-pro' );?></strong></div>
                <div class="format-input">
                    <input type="text" name="editorial_post_embed_audio" size="90" class="post-audio-url" value="<?php echo esc_url( $get_post_audio ); ?>" />
                    <input class="button" name="media_upload_button" id="post_audio_upload_button" value="<?php esc_html_e( 'Embed audio', 'editorial-pro' ); ?>" type="button" />
                    <input class="button" type="button" id="reset-audio-embed" value="<?php esc_html_e( 'Reset url', 'editorial-pro' ); ?>" />
                </div><!-- .format-input -->
            </div><!-- #format-audio -->

            <div class="single-meta-wrap" id="format-gallery">
                <div class="meta-title"><?php esc_html_e( 'Gallery Format', 'editorial-pro' ); ?></div>
                <div class="format-label"><strong><?php esc_html_e( 'Embed gallery images.', 'editorial-pro' );?></strong></div>
                <div class="format-input">
                    <div class="post-gallery-section">
                        <?php
                            $total_img = 0;
                            if( !empty( $post_gallery_images ) ){
                                $total_img = count( $post_gallery_images );
                                $img_counter = 0;
                                foreach( $post_gallery_images as $key => $img_value ){
                                   $attachment_id = editorial_pro_get_image_id_from_url( $img_value );
                                   $img_url = wp_get_attachment_image_src( $attachment_id, 'thumbnail', true );
                        ?>
                                    <div class="gal-img-block">
                                        <div class="gal-img"><img src="<?php echo esc_url( $img_url[0] ); ?>" /><span class="fig-remove" title="<?php echo esc_attr( 'remove', 'editorial-pro' ); ?>"></span></div>
                                        <input type="hidden" name="post_images[<?php echo $img_counter; ?>]" class="hidden-media-gallery" value="<?php echo esc_url( $img_value ); ?>" />
                                    </div>
                        <?php
                                    $img_counter++;
                                }
                            }
                        ?>
                    </div><!-- .post-gallery-section -->
                    <input id="post_image_count" type="hidden" name="post_gallery_image_count" value="" />
                    <span class="add-img-btn" id="post_gallery_upload_button" title="<?php esc_html_e( 'Add Images', 'editorial-pro' ); ?>"></span>
                </div><!-- .format-input -->
            </div><!-- #format-gallery -->

		</div><!-- .mt-meta-options-wrapper -->
<?php
}

/**
 * Save the post value
 */
add_action( 'save_post', 'editorial_pro_save_post_format_meta' );

function editorial_pro_save_post_format_meta( $post_id ) {
    
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST['editorial_pro_post_format_nonce'] ) && wp_verify_nonce( $_POST['editorial_pro_post_format_nonce'], 'editorial_pro_nonce_' . basename( __FILE__ ) ) ) ? 'true' : 'false';

    // Exits script depending on save status
    if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
        return;
    }

    /**
     * update data for featured video
     */ 
    if ( isset( $_POST['editorial_post_featured_video'] ) ) {
           
        $post_featured_video = get_post_meta( $post_id, 'editorial_post_featured_video', true );
        $stz_post_featured_video = esc_url_raw( $_POST[ 'editorial_post_featured_video' ] );

        if ( $stz_post_featured_video && '' == $stz_post_featured_video ){
            add_post_meta( $post_id, 'editorial_post_featured_video', $stz_post_featured_video );
        }elseif ( $stz_post_featured_video && $stz_post_featured_video != $post_featured_video ) {
            update_post_meta($post_id, 'editorial_post_featured_video', $stz_post_featured_video );
        } elseif ( '' == $stz_post_featured_video && $post_featured_video ) {
            delete_post_meta( $post_id, 'editorial_post_featured_video', $post_featured_video );
        }

    }

    /**
     * update data for embed audio
     */ 
    if ( isset( $_POST['editorial_post_embed_audio'] ) ) {
           
        $post_embed_audio = get_post_meta( $post_id, 'editorial_post_embed_audio', true );
        $stz_post_embed_audio = esc_url_raw( $_POST[ 'editorial_post_embed_audio' ] );

        if ( $stz_post_embed_audio && '' == $stz_post_embed_audio ){
            add_post_meta( $post_id, 'editorial_post_embed_audio', $stz_post_embed_audio );
        }elseif ( $stz_post_embed_audio && $stz_post_embed_audio != $post_embed_audio ) {
            update_post_meta($post_id, 'editorial_post_embed_audio', $stz_post_embed_audio );
        } elseif ( '' == $stz_post_embed_audio && $post_embed_audio ) {
            delete_post_meta( $post_id, 'editorial_post_embed_audio', $post_embed_audio );
        }

    }

    /**
     * update data for embed gallery
     */
    if ( isset( $_POST['post_images'] ) ) {
        $stz_post_image = $_POST['post_images'];
        update_post_meta( $post_id, 'post_images', $stz_post_image );

        $image_count = get_post_meta( $post_id, 'post_gallery_image_count', true );
        $stz_image_count = intval( $_POST['post_gallery_image_count'] );
       
        if ( $stz_image_count && '' == $stz_image_count ){
            add_post_meta( $post_id, 'post_gallery_image_count', $stz_image_count );
        }elseif ($stz_image_count && $stz_image_count != $image_count) {
            update_post_meta($post_id, 'post_gallery_image_count', $stz_image_count);
        } elseif ('' == $stz_image_count && $image_count) {
            delete_post_meta($post_id,'post_gallery_image_count');
        }
    }
}