( function( api ) {

    api.controlConstructor['typography'] = api.Control.extend( {
        ready: function() {
            var control = this;

            control.container.on( 'change', '.typography-font-family select',
                function() {
                    control.settings['family'].set( jQuery( this ).val() );
                }
            );

            control.container.on( 'change', '.typography-font-style select',
                function() {
                    control.settings['style'].set( jQuery( this ).val() );
                }
            );

            control.container.on( 'change', '.typography-text-transform select',
                function() {
                    control.settings['text_transform'].set( jQuery( this ).val() );
                }
            );

            control.container.on( 'change', '.typography-text-decoration select',
                function() {
                    control.settings['text_decoration'].set( jQuery( this ).val() );
                }
            );

            control.container.on( 'change', '.typography-font-size input',
                function() {
                    control.settings['size'].set( jQuery( this ).val() );
                }
            );

            /*control.container.on( 'change', '.typography-line-height input',
                function() {
                    control.settings['line_height'].set( jQuery( this ).val() );
                }
            );*/

            control.container.on( 'change', '.typography-color input',
                function() {
                    control.settings['typocolor'].set( jQuery( this ).val() );
                }
            );
        }
    } );

} )( wp.customize );

jQuery(document).ready(function($) {
   "use strict";

    /**
     * Script for switch option
     */
    $('.switch_options').each(function() {
        //This object
        var obj = $(this);

        var switchPart = obj.children('.switch_part').attr('data-switch');
        var input = obj.children('input'); //cache the element where we must set the value
        var input_val = obj.children('input').val(); //cache the element where we must set the value

        obj.children('.switch_part.'+input_val).addClass('selected');
        obj.children('.switch_part').on('click', function(){
            var switchVal = $(this).attr('data-switch');
            obj.children('.switch_part').removeClass('selected');
            $(this).addClass('selected');
            $(input).val(switchVal).change(); //Finally change the value to 1
        });

    });

    /** 
     * Import Demo Data Ajax Function Area 
     */ 
    $("#mt_demo_import").click(function (e){
        e.preventDefault();

        var import_true = confirm('Are you sure to import dummy content ? It will overwrite the existing data.');
        if( import_true == false ) {
            return;  
        } 
        var imp = $(this).next('div');
        imp.addClass('demo-loading');

        $(".import-message").html("The Demo Contents are Loading. It might take a while. Please keep patience.");
        $("#mt_demo_import").fadeOut();
        $.ajax({
            url: ajaxurl,
            data: ({
                'action': 'editorial_pro_demo_import',            
            }),
            success: function(response){
               imp.removeClass('demo-loading');
               alert("Demo Contents Successfully Imported");
               location.reload();
          }
       });
    });

    /**
     * Radio Image control in customizer
     */
    // Use buttonset() for radio images.
    $( '.customize-control-radio-image .buttonset' ).buttonset();

    // Handles setting the new value in the customizer.
    $( '.customize-control-radio-image input:radio' ).change(
        function() {

            // Get the name of the setting.
            var setting = $( this ).attr( 'data-customize-setting-link' );

            // Get the value of the currently-checked radio input.
            var image = $( this ).val();

            // Set the new value.
            wp.customize( setting, function( obj ) {

                obj.set( image );
            } );
        }
    );

    /**
     * Theme info
     */
    $('#customize-info .preview-notice').append(
         '<div class="update-info">'+
         '<a class="editorial-pro-info" href="https://mysterythemes.com/my-account/" target="_blank">Check for Update</a>'+
         '</div>'
    );

});