jQuery(document).ready(function($) {
    $(document).on('change', '.typography_face', function() {
        var font_family = $(this).val();
        var dis = $(this);
        $.ajax({
            url: ajaxurl,
            data: ({
                'action': 'get_google_font_variants',
                'font_family': font_family,
            }),
            success: function(response) {
                dis.parent('.typography-font-family').next('.typography-font-style').children('select').html(response);
                var mtFontWeight = dis.parent('.typography-font-family').next('.typography-font-style').children('select').attr( 'data-customize-setting-link' );
                var setVal = dis.parent('.typography-font-family').next('.typography-font-style').children('select').val();
                wp.customize( mtFontWeight, function( obj ) {
                    obj.set( setVal );
                } );
            }
        });
    });   

    $('.typography-color .color-picker-hex').wpColorPicker({

        change: function(event, ui){

            var setting = $( this ).attr( 'data-customize-setting-link' );
            var hexcolor = $( this ).wpColorPicker( 'color' );
            // Set the new value.
            wp.customize( setting, function( obj ) {
                obj.set(hexcolor);
            });
        }
    });

    $( ".slider-range-size" ).slider({
        range: "min",
        value: 18,
        min: 12,
        max: 100,
        step: 1,
        slide: function( event, ui ) {
            $(this).parent().find( ".slider-value-size" ).text( ui.value );

            var setting = $(this).parent().find("input").attr( 'data-customize-setting-link' );
            
            // Set the new value.
            wp.customize( setting, function( obj ) {

                obj.set( ui.value );
            });

        }
    });

    $( ".slider-range-size" ).each(function(){
        $( this ).slider('value', $( this ).attr('value'));
    });

    $('.slider-value-size').each(function(){
        var value = $( this ).next().attr('value');
        $(this).text(value);
    });

    $( ".slider-range-line-height" ).slider({
        range: "min",
        value: 1.5,
        min: 0.8,
        max: 5,
        step: 0.1,
        slide: function( event, ui ) {
           $(this).parent().find( ".slider-value-line-height" ).text( ui.value );

            var setting = $(this).parent().find("input").attr( 'data-customize-setting-link' );

            // Set the new value.
            wp.customize( setting, function( obj ) {
                obj.set( ui.value );
            });

        }
    });

    $( ".slider-range-line-height" ).each(function(){
        $( this ).slider('value', $( this ).attr('value'));
    });

    $('.slider-value-line-height').each(function(){
        var value = $( this ).next().attr('value');
        $(this).text(value);
    });

});