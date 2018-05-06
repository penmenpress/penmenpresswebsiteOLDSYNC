<?php
/**
 * Functions for rendering post review meta
 * 
 * @package Mystery Themes
 * @subpackage Editorial Pro
 * @since 1.0.0
 */

add_action( 'add_meta_boxes', 'editorial_pro_meta_post_review', 10, 2 );
function editorial_pro_meta_post_review() {
    add_meta_box(
        'editorial_pro_post_review',
        __( 'Post Review', 'editorial-pro' ),
        'editorial_pro_post_review_callback',
        'post',
        'normal',
        'default'
    );
}

function editorial_pro_post_review_callback( $post ) {
	global $post;

	$post_review_type = array(
            'no_review'      => __( 'No Review', 'editorial-pro' ),
            'star_review'    => __( 'Star Review', 'editorial-pro' ),
            'percent_review' => __( 'Percentage Review', 'editorial-pro' )
        );
	$post_star_review = array(
            '5'     => __( '5 Stars', 'editorial-pro' ),
            '4.5'   => __( '4.5 Stars', 'editorial-pro' ),
            '4'     => __( '4 Stars', 'editorial-pro' ),
            '3.5'   => __( '3.5 Stars', 'editorial-pro' ),
            '3'     => __( '3 Stars', 'editorial-pro' ),
            '2.5'   => __( '2.5 Stars', 'editorial-pro' ),
            '2'     => __( '2 Stars', 'editorial-pro' ),
            '1.5'   => __( '1.5 Stars', 'editorial-pro' ),
            '1'     => __( '1 Stars', 'editorial-pro' ),
            '0.5'   => __( '0.5 Stars', 'editorial-pro' )
        );

	$get_review_option = get_post_meta( $post->ID, 'post_review_option', true );
	$get_review_option = empty( $get_review_option ) ? 'no_review' : $get_review_option;

    $star_rating = get_post_meta( $post->ID, 'star_rating', true );
    $star_review_count = get_post_meta( $post->ID, 'star_review_count', true );

    $percent_rating = get_post_meta( $post->ID, 'percent_rating', true );
    $percent_review_count = get_post_meta( $post->ID, 'percent_review_count', true );

    $editorial_get_review_description = get_post_meta( $post->ID, 'post_review_description', true );

	// Create our nonce field.
    wp_nonce_field( 'editorial_pro_nonce_' . basename( __FILE__ ) , 'editorial_pro_post_review_nonce' );
?>
	<div class="mt-meta-review-wrap">
		<div class="type-selector">
	        <select id="selectReview" name="post_review_option" class="editorial-panel-dropdown">
	            <?php foreach( $post_review_type as $post_review => $post_review_label ) { ?>
	                <option value="<?php echo esc_attr( $post_review ); ?>" <?php selected( $get_review_option, $post_review ); ?>><?php echo esc_html( $post_review_label ); ?></option>
	            <?php } ?>
	        </select>
	    </div><!-- .type-selector -->
        <div id="type-star_review" class="review-types">
            <div class="star-review-label review-title"><strong><?php esc_html_e( 'Add star ratings for this post :', 'editorial-pro' );?></strong></div>
            <div class="post-review-section star-section">
                <?php
                    $count = 0;
                    if( !empty( $star_rating ) ){
                        foreach ( $star_rating as $rate_value ) {
                            if( !empty( $rate_value['feature_name'] ) || !empty( $rate_value['feature_star'] ) ) {
                            $count++;
                ?>

                <div class="review-section-group star-group">
                    <span class="custom-label"><?php esc_html_e( 'Feature Name:', 'editorial-pro' );?></span>
                    <input style="width: 300px;" type="text" name="star_ratings[<?php echo $count; ?>][feature_name]" value="<?php echo $rate_value['feature_name']; ?>"/>
                    <select name="star_ratings[<?php echo $count; ?>][feature_star]">
                        <option value=""><?php esc_html_e( 'Select rating', 'editorial-pro' );?></option>
                        <?php foreach ( $post_star_review as $key => $value ) { ?>
                                <option value="<?php echo esc_attr( $key ); ?>"<?php selected( $rate_value['feature_star'], $key ); ?>><?php echo esc_html( $value ); ?></option>
                        <?php } ?>
                    </select>
                    <a href="#" class="delete-review-stars dlt-btn button"><?php esc_html_e( 'Delete', 'editorial-pro' ) ;?></a>
                </div><!-- .review-section-group -->
                <?php
                            }
                        } 
                    } else {
                ?>
                        <div class="review-section-group star-group">
                            <span class="custom-label"><?php esc_html_e( 'Feature Name:', 'editorial-pro' );?></span>
                            <input style="width: 300px;" type="text" name="star_ratings[<?php echo $count; ?>][feature_name]" value=""/>
                            <select name="star_ratings[<?php echo $count; ?>][feature_star]">
                                <option value=""><?php esc_html_e( 'Select rating', 'editorial-pro' );?></option>
                                <?php foreach ( $post_star_review as $key => $value ) { ?>
                                        <option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></option>
                                <?php } ?>
                            </select>
                            <a href="#" class="delete-review-stars dlt-btn button"><?php esc_html_e( 'Delete', 'editorial-pro' ) ;?></a>
                        </div><!-- .review-section-group -->
                <?php
                    }
                ?>
            </div><!-- .post-review-section.star-section -->
            <input id="post_star_review_count" type="hidden" name="star_review_count" value="<?php echo $count; ?>" />
            <a href="#" class="add-review-stars add-review-btn button"><?php esc_html_e( 'Add rating category', 'editorial-pro' );?></a>
        </div><!-- #type-star_review -->

        <div id="type-percent_review" class="review-types">
            <div class="percent-review-label review-title"><strong><?php esc_html_e( 'Add Percentage ratings for this post :', 'editorial-pro' );?></strong></div>
            <div class="post-review-section percent-section">
                <?php 
                    $p_count = 0;
                    if( !empty( $percent_rating ) ) {
                        foreach ( $percent_rating as $key => $value ) {
                            $p_count++;
                ?>
                        <div class="review-section-group percent-group">
                            <span class="custom-label"><?php esc_html_e( 'Feature Name:', 'editorial-pro' );?></span>
                                <input style="width: 300px;" type="text" name="percent_ratings[<?php echo $p_count; ?>][feature_name]" value="<?php echo esc_html( $value['feature_name'] ); ?>"/>
                            <span class="opt-sep"><?php esc_html_e( 'Percent: ', 'editorial-pro' );?></span>
                            <input style="width: 100px;" type="number" min="1" max="100" name="percent_ratings[<?php echo $p_count; ?>][feature_percent]" value="<?php echo intval( $value['feature_percent'] ); ?>" step="1"/>
                            <a href="#" class="delete-review-percents dlt-btn button"><?php esc_html_e( 'Delete', 'editorial-pro' ) ;?></a>
                        </div><!-- .review-section-group -->
                <?php 
                        }
                    } else {
                ?>
                        <div class="review-section-group percent-group">
                            <span class="custom-label"><?php esc_html_e( 'Feature Name:', 'editorial-pro' );?></span>
                                <input style="width: 300px;" type="text" name="percent_ratings[<?php echo $p_count; ?>][feature_name]" value=""/>
                            <span class="opt-sep"><?php esc_html_e( 'Percent: ', 'editorial-pro' );?></span>
                            <input style="width: 100px;" type="number" min="1" max="100" name="percent_ratings[<?php echo $p_count; ?>][feature_percent]" value="" step="1"/>
                            <a href="#" class="delete-review-percents dlt-btn button"><?php esc_html_e( 'Delete', 'editorial-pro' ) ;?></a>
                        </div><!-- .review-section-group -->
                <?php
                    }
                ?>
            </div><!-- .post-review-section.percent-section -->
            <input id="post_percent_review_count" type="hidden" name="percent_review_count" value="<?php echo intval( $p_count ); ?>" />
            <a href="#" class="add-review-percents add-review-btn button"><?php esc_html_e( 'Add rating category', 'editorial-pro' );?></a>
        </div><!-- #type-percentage_review -->

        <div class="post-review-summary">
            <div class="review-title"><strong><?php esc_html_e( 'Review description:', 'editorial-pro' );?></strong></div>
            <p class="review-textarea">
                <textarea row="5" name="post_review_description"><?php echo wp_kses_post( $editorial_get_review_description ); ?></textarea>
            </p>
        </div><!-- .post-review-desc -->
	</div><!-- .mt-meta-review-wrap -->
<?php
}

/**
 * Save the post value
 */
add_action( 'save_post', 'editorial_pro_save_post_review_meta' );

function editorial_pro_save_post_review_meta( $post_id ) {

    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST['editorial_pro_post_review_nonce'] ) && wp_verify_nonce( $_POST['editorial_pro_post_review_nonce'], 'editorial_pro_nonce_' . basename( __FILE__ ) ) ) ? 'true' : 'false';

    // Exits script depending on save status
    if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
        return;
    }    

    if ( isset( $_POST['post_review_option'] ) ) {
        /**
         * update post review option
         */
        $post_review_option = get_post_meta( $post_id, 'post_review_option', true );
        $stz_post_review_option = sanitize_text_field( $_POST[ 'post_review_option' ] );

        if ( $stz_post_review_option && '' == $stz_post_review_option ){
            add_post_meta( $post_id, 'post_review_option', $stz_post_review_option );
        }elseif ( $stz_post_review_option && $stz_post_review_option != $post_review_option ) {
            update_post_meta($post_id, 'post_review_option', $stz_post_review_option );
        } elseif ( '' == $stz_post_review_option && $post_review_option ) {
            delete_post_meta( $post_id,'post_review_option', $post_review_option );
        }
    }

    if ( isset( $_POST['star_ratings'] ) && $stz_post_review_option == 'star_review' ) {
        /**
         * update all data of star review
         */
        $stz_star_rating = $_POST['star_ratings'];
        update_post_meta( $post_id, 'star_rating', $stz_star_rating );

        /**
         * update data for star count
         */    
        $star_review_count = get_post_meta( $post_id, 'star_review_count', true );
        $stz_star_review_count = sanitize_text_field( $_POST[ 'star_review_count' ] );

        if ( $stz_star_review_count && '' == $stz_star_review_count ){
            add_post_meta( $post_id, 'star_review_count', $stz_star_review_count );
        }elseif ( $stz_star_review_count && $stz_star_review_count != $star_review_count ) {
            update_post_meta($post_id, 'star_review_count', $stz_star_review_count );
        } elseif ( '' == $stz_star_review_count && $star_review_count ) {
            delete_post_meta( $post_id, 'star_review_count', $star_review_count );
        }
    }

    if ( isset( $_POST['percent_ratings'] ) && $stz_post_review_option == 'percent_review' ) {
        /**
         * update all data of percentage review
         */
        $stz_percent_rating = $_POST['percent_ratings'];
        update_post_meta( $post_id, 'percent_rating', $stz_percent_rating );

        /**
         * update data for percent count
         */    
        $percent_review_count = get_post_meta( $post_id, 'percent_review_count', true );
        $stz_percent_review_count = sanitize_text_field( $_POST[ 'percent_review_count' ] );

        if ( $stz_percent_review_count && '' == $stz_percent_review_count ){
            add_post_meta( $post_id, 'percent_review_count', $stz_percent_review_count );
        }elseif ( $stz_percent_review_count && $stz_percent_review_count != $percent_review_count ) {
            update_post_meta($post_id, 'percent_review_count', $stz_percent_review_count );
        } elseif ( '' == $stz_percent_review_count && $percent_review_count ) {
            delete_post_meta( $post_id, 'percent_review_count', $percent_review_count );
        }
    }

    /**
     * Update review description
     */
    if ( isset( $_POST['post_review_description'] ) ) {
        $post_review_description = get_post_meta( $post_id, 'post_review_description', true );
        $stz_review_description  = wp_kses_post( $_POST[ 'post_review_description' ] );

        if ( $stz_review_description && '' == $stz_review_description ){
            add_post_meta( $post_id, 'post_review_description', $stz_review_description );
        }elseif ( $stz_review_description && $stz_review_description != $post_review_description ) {
            update_post_meta($post_id, 'post_review_description', $stz_review_description );
        } elseif ( '' == $stz_review_description && $post_review_description ) {
            delete_post_meta( $post_id, 'post_review_description', $post_review_description );
        }
    }
    
}