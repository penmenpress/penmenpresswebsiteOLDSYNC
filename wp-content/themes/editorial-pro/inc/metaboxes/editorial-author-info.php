<?php
/**
 * Added extra info field about author
 *
 * @package Mystery Themes
 * @subpackage Editorial Pro
 * @since 1.0.0
 *
 */

/**
 * Adds additional user fields
 * more info: http://justintadlock.com/archives/2009/09/10/adding-and-using-custom-user-profile-fields
 */

add_action( 'show_user_profile', 'editorial_additional_user_fields' );
add_action( 'edit_user_profile', 'editorial_additional_user_fields' );

function editorial_additional_user_fields( $user ) { 

	wp_nonce_field( basename( __FILE__ ), 'editorial_pro_author_meta_nonce' );

	$user_img_url = get_the_author_meta( 'user_meta_image', $user->ID );
	$user_img_id = editorial_pro_get_image_id_from_url( $user_img_url );
	$user_thumb_img_url = wp_get_attachment_image_src( $user_img_id, 'thumbnail', true );
?>
 
    <h3><?php esc_html_e( 'Additional User Meta', 'editorial-pro' ); ?></h3>
 
    <table class="form-table">
 
        <tr>
            <th><label for="user_meta_image"><?php esc_html_e( 'A special image for each user', 'editorial-pro' ); ?></label></th>
            <td>
                <!-- Outputs the image after save -->
                <?php if( !empty( $user_img_url ) ) { ?>
                    <img class="show-author-img" src="<?php echo esc_url( $user_thumb_img_url[0] ); ?>" style="width:150px;"><br />
                <?php } ?>
                <!-- Outputs the text field and displays the URL of the image retrieved by the media uploader -->
                <input type="text" name="user_meta_image" id="user_meta_image" value="<?php echo esc_url_raw( get_the_author_meta( 'user_meta_image', $user->ID ) ); ?>" class="regular-text" />
                <!-- Outputs the save button -->
                <input type='button' class="additional-user-image button-primary" value="<?php esc_html_e( 'Upload Image', 'editorial-pro' ); ?>" id="uploadUserImage"/><br />
                <span class="description"><?php esc_html_e( 'Upload an additional image for your user profile.', 'editorial-pro' ); ?></span>
            </td>
        </tr>
 
    </table><!-- end form-table -->
<?php } // editorial_additional_user_fields

/**
* Saves additional user fields to the database
*/
function editorial_save_additional_user_meta( $user_id ) {

	// Verify the nonce before proceeding.
    if ( !isset( $_POST[ 'editorial_pro_author_meta_nonce' ] ) || !wp_verify_nonce( $_POST[ 'editorial_pro_author_meta_nonce' ], basename( __FILE__ ) ) ) {
        return;
    }
 
    // only saves if the current user can edit user profiles
    if ( !current_user_can( 'edit_user', $user_id ) ) {
        return false;
    }
 
    update_user_meta( $user_id, 'user_meta_image', $_POST['user_meta_image'] );
}
 
add_action( 'personal_options_update', 'editorial_save_additional_user_meta' );
add_action( 'edit_user_profile_update', 'editorial_save_additional_user_meta' );


$editorial_user_social_array = array(
    'behance'           => __( 'Behance', 'editorial-pro' ),
    'delicious'         => __( 'Delicious', 'editorial-pro' ),
    'deviantart'        => __( 'DeviantArt', 'editorial-pro' ),
    'digg'              => __( 'Digg', 'editorial-pro' ),
    'dribbble'          => __( 'Dribbble', 'editorial-pro' ),
    'facebook'          => __( 'Facebook', 'editorial-pro' ),
    'flickr'            => __( 'Flickr', 'editorial-pro' ),
    'github'            => __( 'Github', 'editorial-pro' ),
    'google-plus'       => __( 'Google+', 'editorial-pro' ),
    'html5'             => __( 'Html5', 'editorial-pro' ),
    'instagram'         => __( 'Instagram', 'editorial-pro' ),
    'linkedin'          => __( 'LinkedIn', 'editorial-pro' ),
    'paypal'            => __( 'PayPal', 'editorial-pro' ),
    'pinterest'         => __( 'Pinterest', 'editorial-pro' ),
    'reddit'            => __( 'Reddit', 'editorial-pro' ),
    'rss'               => __( 'RSS', 'editorial-pro' ),
    'share'             => __( 'Share', 'editorial-pro' ),
    'skype'             => __( 'Skype', 'editorial-pro' ),
    'soundcloud'        => __( 'SoundCloud', 'editorial-pro' ),
    'spotify'           => __( 'Spotify', 'editorial-pro' ),
    'stack-exchange'    => __( 'StackExchange', 'editorial-pro' ),
    'stack-overflow'    => __( 'Stackoverflow', 'editorial-pro' ),
    'steam'             => __( 	'Steam', 'editorial-pro' ),
    'stumbleupon'       => __( 'StumbleUpon', 'editorial-pro' ),
    'tumblr'            => __( 'Tumblr', 'editorial-pro' ),
    'twitter'           => __( 'Twitter', 'editorial-pro' ),
    'vimeo'             => __( 'Vimeo', 'editorial-pro' ),
    'vk'                => __( 'VKontakte', 'editorial-pro' ),
    'windows'           => __( 'Windows', 'editorial-pro' ),
    'wordpress'         => __( 'WordPress', 'editorial-pro' ),
    'yahoo'             => __( 'Yahoo', 'editorial-pro' ),
    'youtube'           => __( 'YouTube', 'editorial-pro' )
);

add_filter( 'user_contactmethods', 'editorial_author_meta_contact' );

function editorial_author_meta_contact() {
    global $editorial_user_social_array;
    foreach( $editorial_user_social_array as $icon_id => $icon_name ) {
        $contactmethods[$icon_id] = $icon_name;
    }
    return $contactmethods;
}