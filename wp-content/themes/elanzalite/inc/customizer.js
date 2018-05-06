jQuery(document).ready(function () {

    tb_show('Themehunk Customizer', '#TB_inline?width=400&height=430&inlineId=elanzalite_homepage');
    jQuery('#TB_closeWindowButton').hide();
    jQuery('#TB_window').css({
        'z-index': '5000001',
        'height': '480px',
        'width': '780px'
    })
    jQuery('#TB_overlay').css({
        'z-index': '5000000'
    });

    jQuery('#TB_window').addClass('elanzalite-popup');
    jQuery('#disable-popup-cb').click(function () {
                    alert();
                    jQuery.post( ajaxurl, 
                    {
                            value: this.checked ? 1 : 0,
                            action: "customizer_disable_popup",
                        },
                        function(result){
                            alert(result);
                        tb_remove()                        
                        });
    });
});