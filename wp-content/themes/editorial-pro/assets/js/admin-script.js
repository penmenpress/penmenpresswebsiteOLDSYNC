// update function for review selector
function editorial_typesReview() {
    var cur_selection = jQuery('#selectReview option:selected').val();

    if( cur_selection === undefined ) {
        return;
    }

    if( cur_selection.indexOf("no_review") !== -1 ) {
        jQuery('.review-types').hide();
        jQuery('.post-review-desc').hide();
        jQuery('.post-review-summary').hide();
    } else {
        jQuery('.review-types').hide();
        jQuery('#type-' + cur_selection).fadeIn();
        jQuery('.post-review-desc').fadeIn();
        jQuery('.post-review-summary').fadeIn();
    }
}

// function about post format
function postFormat() {
    var cur_format = jQuery("input[type='radio'].post-format:checked").val();
    if (cur_format === '0') {
        jQuery('#editorial_pro_post_formats').hide();
    } else {
        jQuery('#editorial_pro_post_formats').fadeIn();
        jQuery('.single-meta-wrap').hide();
        jQuery('#format-'+cur_format).fadeIn();
    }
}

jQuery(document).ready(function($) {
    /*"use strict";*/

    /**
     * Script for switch option at widget
     */
    $('.widget_switch_options').each(function() {
        //This object
        var obj = $(this);

        var switchPart = obj.children('.widget_switch_part').data('switch');
        var input = obj.children('input'); //cache the element where we must set the value
        var input_val = obj.children('input').val(); //cache the element where we must set the value

        obj.children('.widget_switch_part.'+input_val).addClass('selected');
        obj.children('.widget_switch_part').on('click', function(){
            var switchVal = $(this).data('switch');
            obj.children('.widget_switch_part').removeClass('selected');
            $(this).addClass('selected');
            $(input).val(switchVal).change(); //Finally change the value to 1
        });

    });

    /**
     * Script for image selected from radio option
     */
    $('.controls#editorial-img-container li img').click(function(){
        $('.controls#editorial-img-container li').each(function(){
            $(this).find('img').removeClass ('editorial-radio-img-selected') ;
        });
        $(this).addClass ('editorial-radio-img-selected') ;
    });

    /**
     * Radio Image control in metabox
     */
    // Use buttonset() for radio images.
    $( '.mt-meta-options-wrap .buttonset' ).buttonset();

    /**
     * Review options
     */
    editorial_typesReview();
    $('#selectReview').change(function() {
        editorial_typesReview();
    });

    /**
     * Add new star row
     */
    var count = $('#post_star_review_count').val();
    $('.add-review-stars').click(function(e){
        e.preventDefault();
        count++;
        $('.post-review-section.star-section').append('<div class="review-section-group star-group">'+
                            '<span class="custom-label">Feature Name: </span>'+
                            '<input style="width: 300px;" type="text" name="star_ratings['+count+'][feature_name]" value="" />'+
                            ' <select name="star_ratings['+count+'][feature_star]">'+
                            '<option value="">Select rating</option>'+
                            '<option value="5">5 stars</option>'+
                            '<option value="4.5">4.5 stars</option>'+
                            '<option value="4">4 stars</option>'+
                            '<option value="3.5">3.5 stars</option>'+
                            '<option value="3">3 stars</option>'+
                            '<option value="2.5">2.5 stars</option>'+
                            '<option value="2">2 stars</option>'+
                            '<option value="1.5">1.5 stars</option>'+
                            '<option value="1">1 stars</option>'+
                            '<option value="0.5">0.5 stars</option>'+
                            '</select>'+
                            ' <a href="#" class="delete-review-stars dlt-btn button">Delete</a>'+
                            '</div></div>'
                            );
    });

    $(document).on('click', '.delete-review-stars', function(e){
        e.preventDefault();
        $(this).parent('.review-section-group.star-group').remove();
    });

    /**
    *Widget section toggle
    */

    $('.tab_widget_sec').each(function(){ 
        var epThis = $(this); 
        epThis.children('.section-header,.sec-click-icon').on('click', function(){
            epThis.children('.widget-sec-content').slideToggle("slow");
        });
    });

    /**
     * Add new percent row
     */
    var pCount = $('#post_percent_review_count').val();
    $('.add-review-percents').click(function(e){
        e.preventDefault();
        pCount++;
        $('.post-review-section.percent-section').append('<div class="review-section-group percent-group"><span class="custom-label">Feature Name: </span>'+
                            '<input style="width: 300px;" type="text" name="percent_ratings['+pCount+'][feature_name]" value="" />'+
                            ' <span class="opt-sep">Percent: </span>'+
                            '<input style="width: 100px;" type="number" min="1" max="100" name="percent_ratings['+pCount+'][feature_percent]" value="" step="1" />'+
                            '<a href="#" class="delete-review-percents dlt-btn button">Delete</a>'+
                            '</div>'
                            );
        
    });

    $(document).on('click', '.delete-review-percents', function(e){
        e.preventDefault();
       $(this).parent('.review-section-group.percent-group').remove();
    });

    /**
     * Widget section toggle
     */
    $( document ).ajaxComplete( function( event, XMLHttpRequest, ajaxOptions ) {
        // determine which ajax request is this (we're after "save-widget")
        var request = {}, pairs = ajaxOptions.data.split('&'), i, split, widget;
        for( i in pairs ) {
            split = pairs[i].split( '=' );
            request[decodeURIComponent( split[0] )] = decodeURIComponent( split[1] );
        }
        // only proceed if this was a widget-save request
        if( request.action && ( request.action === 'save-widget' ) ) {
            // locate the widget block
            widget = $('input.widget-id[value="' + request['widget-id'] + '"]').parents('.widget');

            // trigger manual save, if this was the save request 
            // and if we didn't get the form html response (the wp bug)
            if( !XMLHttpRequest.responseText ) 
                wpWidgets.save(widget, 0, 1, 0);

            // we got an response, this could be either our request above,
            // or a correct widget-save call, so fire an event on which we can hook our js
            else {
                $('.sec-click-icon').on('click', function(){
                    $(this).siblings('.widget-sec-content').slideToggle( "slow" );
                });
            }
        }
    });

    /**
     * Change the post format
     */
    postFormat();
    $('input[name="post_format"]').change(function () {
        postFormat();
    });

    /**
     * Reset video embed value
     */
    $('#reset-video-embed').click(function () {
        $('input[name="editorial_post_featured_video"]').val('');
    });

    /**
     * Reset audio embed value
     */
    $('#reset-audio-embed').click(function () {
        $('input[name="editorial_post_embed_audio"]').val('');
    });

    /**
     * Add audio file
     */
    $('#post_audio_upload_button').on('click', function (e) {
        e.preventDefault();
        var $this = $(this);
        var audio = wp.media.frames.file_frame = wp.media({
            title: 'Upload Audio File',
            button: {
                text: 'Use this file',
            },
            // multiple: true if you want to upload multiple files at once
            multiple: false,
            library: {
                type: 'audio'
            }
        }).open()
                .on('select', function (e) {
                    // This will return the selected audio from the Media Uploader, the result is an object
                    var uploaded_audio = audio.state().get('selection').first();
                    // We convert uploaded_audio to a JSON object to make accessing it easier
                    // Output to the console uploaded_audio
                    var audio_url = uploaded_audio.toJSON().url;
                    // Let's assign the url value to the input field
                    $this.prev('input').val(audio_url);
                });
        //$('#audiourl_remove').show();
    });

    $('#audiourl_remove').click(function () {
        $('input[name="editorial_post_embed_audio"]').val('');
    });

    /**
     * Add gallery images
     */
    $(document).on('click', '#post_gallery_upload_button', function (e) {
        var img_count = $('#post_image_count').val();
        var dis = $(this);
        var send_attachment_bkp = wp.media.editor.send.attachment;
        _custom_media = true;
        wp.media.editor.send.attachment = function (props, attachment) {
            if (_custom_media) {
                img = attachment.sizes.thumbnail.url;
                $('.post-gallery-section').append('<div class="gal-img-block"><div class="gal-img"><img src="' + img + '" height="150px" width="150px"/><span class="fig-remove" title="remove"></span></div><input type="hidden" name="post_images[' + img_count + ']" class="hidden-media-gallery" value="' + attachment.url + '" /></div>');
                img_count++;
                $('#post_image_count').val(img_count);
            } else {
                return _orig_send_attachment.apply( $(this), [props, attachment]);
            }
        }

        wp.media.editor.open($(this));
        return false;
    });

    $(document).on('click', '.fig-remove', function () {
        $(this).parents('.gal-img-block').remove();
    });

    /**
     * Add user meta image
     */
    var file_frame;

    $('.additional-user-image').on('click', function (event) {
        event.preventDefault();
        var $this = $(this);

        // If the media frame already exists, reopen it.
        if (file_frame) {
            file_frame.open();
            return;
        }

        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
            title: $(this).data('uploader_title'),
            button: {
                text: $(this).data('uploader_button_text'),
            },
            multiple: false  // Set to true to allow multiple files to be selected
        });

        // When an image is selected, run a callback.
        file_frame.on('select', function () {
            // We set multiple to false so only get one image from the uploader
            attachment = file_frame.state().get('selection').first();
            thumbImg = attachment.toJSON().sizes.thumbnail.url;
            var user_img_url = attachment.toJSON().url;

            $this.prev('input').val(user_img_url);
            $('.show-author-img').attr('src', thumbImg);
        });

        // Finally, open the modal
        file_frame.open();
    });

});