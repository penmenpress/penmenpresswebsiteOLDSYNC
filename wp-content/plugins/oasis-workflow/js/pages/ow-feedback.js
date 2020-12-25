jQuery( document ).ready( function () {
   var deactivateLink = "";
   
   jQuery('#the-list').on('click', 'a.owf-deactivate-link', function (e) {
      e.preventDefault();
      deactivateLink = jQuery(this).attr('href');
      jQuery("#owf-deactivate").attr('href', deactivateLink);
      jQuery( "#owf_deactivate_feedback" ).owfmodal( {
         onShow: function ( dlg ) {
            jQuery("#simplemodal-container").css({
               "width": "523px",
               "max-height": "90%",
               "top":"60px"
            });
            jQuery( dlg.wrap ).css( 'overflow', 'auto' );
         }
      } );
   });
   
   jQuery( document ).on( "click", "#owf-feedback-save", function () {
      
      jQuery( ".btn-submit-feedback-group span" ).addClass( "loading" );
      jQuery( "#owf-feedback-save" ).hide();
      
      var selectedFeedback = jQuery(".selected-reason:checked").val();
      var feedbackThoughts = jQuery(".feedback-thoughts").val();
      var feedbackEmail = jQuery(".ow-feedback-email").val();
      
      var submit_feedback_data = {
         action:     'submit_deactivation_feedback',
         feedback:   selectedFeedback,
         thoughts:   feedbackThoughts,
         email:      feedbackEmail,
         security:   jQuery("#owf_feedback_ajax_nonce").val()
      };
      
      jQuery.post(ajaxurl, submit_feedback_data, function (response) {
         if (response == -1) {
            return false; // Invalid nonce
         }

         window.location.href = deactivateLink;         
      });
      
   });
   
});