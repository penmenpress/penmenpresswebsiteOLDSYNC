jQuery(document).ready(function($) {
    "use strict";
    
    if($('body').hasClass("rtl")) {
        var rtlValue = true;
    } else {
        var rtlValue = false;
    }
    // Ticker
    $('.tickerWrap').lightSlider({
        item:1,
        loop:true,
        vertical: true,
        pager:false,
        auto:true,
        controls: true,
        speed: 1000,
        pause: 3000,
        enableDrag:false,
        verticalHeight:80,
        onSliderLoad: function() {
           $('.tickerWrap').removeClass( 'cS-hidden' );
        }
    });

    // Slider
    $('.ep-slider').each(function(){

        var Id = $(this).parent().attr('id');
        var NewId = Id;
        var slideAuto = $(this).data('auto');
        var slideControl = $(this).data('control');
        var slidePager = $(this).data('pager');
        var slideSpeed = $(this).data('speed');
        var slidePause = $(this).data('pause');

        NewId = $('#'+Id+" .editorialSlider").lightSlider({
            item:1,
            pager: slidePager,
            controls: slideControl,
            loop: true,
            auto: slideAuto,
            speed: slideSpeed,
            pause: slidePause,
            rtl: rtlValue,
            onSliderLoad: function() {
               $('.editorialSlider').removeClass( 'cS-hidden' );
            }
        });
    });
    

    //carousel
    $('.ep-carousel').each(function(){
        
        var Id = $(this).parent().attr('id');
        var NewId = Id;
        var crsItem = $(this).data('items');

        NewId = $('#'+Id+" .block-carousel").lightSlider({
            item:crsItem,
            pager:false,
            enableDrag:false,
            controls:false,
            speed:650,
            rtl: rtlValue,
            onSliderLoad: function() {
                $('.block-carousel').removeClass('cS-hidden');
            },
            responsive : [
                {
                    breakpoint:840,
                    settings: {
                        item:2,
                        slideMove:1,
                        slideMargin:6,
                      }
                },
                {
                    breakpoint:480,
                    settings: {
                        item:1,
                        slideMove:1,
                      }
                }
            ]
        });

        $('#'+Id+' .ep-navPrev').click(function(){
            NewId.goToPrevSlide(); 
        });
        $('#'+Id+' .ep-navNext').click(function(){
            NewId.goToNextSlide(); 
        });

    });

    //Search toggle
    $('.header-search-wrapper .search-main').click(function() {
        $('.search-form-main').toggleClass('active-search');
        $('.search-form-main .search-field').focus();
    });

    //widget title wrap
    $('.widget .widget-title,.related-articles-wrapper .related-title').wrap('<div class="widget-title-wrapper"></div>');

    //responsive menu toggle
    $('#masthead .menu-toggle').click(function(event) {
        $('#masthead #site-navigation').slideToggle('slow');
    });

    //responsive sub menu toggle
    $('#site-navigation .menu-item-has-children').append('<span class="sub-toggle"> <i class="fa fa-angle-right"></i> </span>');

    $('#site-navigation .sub-toggle').click(function() {
        $(this).parent('.menu-item-has-children').children('ul.sub-menu').first().slideToggle('1000');
        $(this).children('.fa-angle-right').first().toggleClass('fa-angle-down');
    });

    // Scroll To Top
    $(window).scroll(function() {
        if ($(this).scrollTop() > 700) {
            $('#mt-scrollup').fadeIn('slow');
        } else {
            $('#mt-scrollup').fadeOut('slow');
        }
    });

    $('#mt-scrollup').click(function() {
        $("html, body").animate({
            scrollTop: 0
        }, 600);
        return false;
    });

    /**
     * Fitvids settings
     */
    if ( typeof jQuery.fn.fitVids !== 'undefined' ) {
        $('.fitvids-video').fitVids();
    }

    /**
     * Default widget tabbed
     */
    $( "#mt-tabbed-widget" ).tabs();

    /**
     * Tabbed content using ajax
     */
    $('.ep-tab-links li:first').addClass('active');
    $('.fullwidth-tabbed-content-wrapper .ep-tab-links a').on('click', function(e) {
        e.preventDefault();
        $.dis = $(this);
        $('.cat-tab').removeClass('active');
        var currentAttrValue = $(this).data('catid');
        var currentAttrSlug = $(this).data('catslug');
        var postCount = $(this).parents('.ep-tabbed-header').siblings('.tabbed-posts-wrapper').data('postcount');
        var postExcerpt = $(this).parents('.ep-tabbed-header').siblings('.tabbed-posts-wrapper').data('excerptlength');
        var tabSubcats = $(this).parents('.ep-tabbed-header').siblings('.tabbed-posts-wrapper').data('subcats');
        $(this).parent('li').addClass('active');

        if($(this).parents('.ep-tabbed-header').siblings('.tabbed-posts-wrapper').find('.'+currentAttrSlug).length > 0){
            $(this).parents('.ep-tabbed-header').siblings('.tabbed-posts-wrapper').find('.tab-cat-content').hide();
            $(this).parents('.ep-tabbed-header').siblings('.tabbed-posts-wrapper').find('.'+currentAttrSlug).show();

        } else {
            $.dis.parents('.ep-tabbed-header').siblings('.tabbed-posts-wrapper').find('.content-loader').show();
            $.ajax({
                url : editorial_ajax_script.ajaxurl,
                
                data:{
                        action : 'editorial_tabs_ajax_action',
                        category_id:  currentAttrValue,
                        category_slug: currentAttrSlug,
                        post_count: postCount,
                        post_excerpt_length: postExcerpt,
                        tab_subcats: tabSubcats,
                    },
                type:'post',
                 success: function(res){
                        $.dis.parents('.ep-tabbed-header').siblings('.tabbed-posts-wrapper').append(res);
                        $.dis.parents('.ep-tabbed-header').siblings('.tabbed-posts-wrapper').find('.tab-cat-content').hide();
                        $.dis.parents('.ep-tabbed-header').siblings('.tabbed-posts-wrapper').find('.'+currentAttrSlug).show();
                        $('.content-loader').hide();
                    }
            });
        }
    });

    /**
     * Block column widget
     */
    $('.editorial_pro_block_column').each(function(){
        var epColThis = $(this);
        var widCol = epColThis.find('.column-posts-block').data('columns');
        epColThis.addClass('widget-columns-'+widCol);
    });

    /**
     * Sticky sidebar
     */
    $('.home-top-primary, .home-top-secondary').theiaStickySidebar({
        additionalMarginTop: 30
    });

    $('.home-bottom-primary, .home-bottom-secondary').theiaStickySidebar({
        additionalMarginTop: 30
    });

    $('#primary, #secondary').theiaStickySidebar({
        additionalMarginTop: 30
    });

    /**
     * Embed gallery on single post
     */
    $('.embed-gallery').lightSlider({
        item:1,
        mode: 'fade',
        pager: false,
        controls:false,
        loop: true,
        auto: true,
        speed: 600,
        pause: 1500,
        onSliderLoad: function() {
           $('.embed-gallery').removeClass( 'cS-hidden' );
        }
    });

    /**
     * Show image on Lightbox for default gallery
     */
    $("a[rel^='prettyPhoto']").prettyPhoto({
        show_title: false,
        deeplinking: false,
        social_tools: ''
    });

    /**
     * Preloader
     */
    if($('#preloader-background').length > 0) {
        setTimeout(function(){$('#preloader-background').hide();}, 600);
    }


    //column block wrap js 
    var divs = jQuery("section.editorial_pro_block_column.widget-columns-1");
    for(var i=0; i<divs.length;) {
        i += divs.eq(i).nextUntil(':not(.editorial_pro_block_column.widget-columns-1)').andSelf().wrapAll('<div class="editorial_pro_block_column-wrap"> </div>').length;
    }

    $('.rtl .ticker-content-wrapper .lSSlideOuter').removeClass('lSrtl');
});
