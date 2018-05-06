<!DOCTYPE html>
<html <?php language_attributes(); ?> >
  <head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <?php    wp_head();   ?>
  </head>
<?php 
$magazine_box_cls = '';    
if(get_theme_mod('magzine_boxed_layout')=="boxed-all"){
$magazine_box_cls = 'magazine-box';
} 
if(get_theme_mod('magzine_boxed_layout')=="boxed-single"){
$magazine_box_cls = 'magazine-single-box';
}
if(get_theme_mod('magzine_boxed_layout')=="disable-all"){
$magazine_box_cls = '';
}?>
<body <?php body_class($magazine_box_cls); ?>>
<?php 
if(get_theme_mod('elanzalite_sticky_header_disable','')=='1'){
$headr_static_cls = 'header-static';
}else{
$headr_static_cls = ''; 
} 
if(get_theme_mod('heaer_bg_trnsparent_active','')=='1'){
    $hdr_trnsprnt ='hdr-transparent';
    }else{
    $hdr_trnsprnt ='';
 }  
if (get_theme_mod('header_visibility_active','')=='' || get_theme_mod('header_visibility_active','')=='0'){
    $header_hide ='';
    }else{
    $header_hide ='header-hide';
    }
if(get_theme_mod('header_style1_active')=='hdr_one_ads'){
    $header_style_one ='header-style-one';
    }else{
    $header_style_one ='';
    }    
if(get_theme_mod('top_hdr_active')=='' || get_theme_mod('top_hdr_active')=='0'){
    $top_hder ='top-header-show';
    }else{
    $top_hder ='top-header-hide';
    }     
?>
 <!--Main Header Start -->
    <div class="header-wrapper" id="header" >
      <!-- Top Header Start -->
      <header class="<?php echo $headr_static_cls;?> <?php echo $hdr_trnsprnt;?>  <?php echo $header_hide;?> <?php echo $header_style_one; ?> <?php echo $top_hder; ?>">
<?php if (get_theme_mod('top_hdr_active')=='' || get_theme_mod('top_hdr_active')=='0'){  ?>      
        <div class="top-container">
      <div class="header-wrap-top">
              <div class="top-date">
                <i class="fa fa-calendar-o" aria-hidden="true"></i><?php echo date(get_option('date_format')); ?>
              </div>
              <div class="inner-wrap-top">
                <?php if (function_exists( 'elanzalite_top_nav_menu' ) ){?>
              <div class="top-menu">
                 <?php elanzalite_top_nav_menu(); ?>
              </div>
              <?php } ?>
              <div class="top-social-icon"> 
                  <ul>
            <?php if($f_link = esc_url(get_theme_mod('f_link'))) : ?><li><a target='_blank' href="<?php echo esc_url($f_link); ?>" >
            <i class='fa fa-facebook'></i></a></li><?php endif; ?>
            <?php if($g_link = esc_url(get_theme_mod('g_link'))) : ?>
            <li><a target='_blank' href="<?php echo esc_url($g_link); ?>" >
            <i class='fa fa-google-plus'></i></a></li><?php endif; ?>
            <?php if($l_link = esc_url(get_theme_mod('l_link'))) : ?>
            <li><a target='_blank' href="<?php echo esc_url($l_link); ?>" >
            <i class='fa fa-linkedin'></i></a></li><?php endif; ?>
            <?php if($p_link = esc_url(get_theme_mod('p_link'))) : ?><li><a target='_blank' href="<?php echo esc_url($p_link); ?>" ><i class='fa fa-pinterest'></i></a></li><?php endif; ?>
            <?php if($t_link = esc_url(get_theme_mod('t_link'))) : ?><li><a target='_blank' href="<?php echo esc_url($t_link); ?>" ><i class='fa fa-twitter'></i></a></li><?php endif; ?>
          </ul>
              
              </div>
            </div>
            <a href="#" id="pull" class="toggle-mobile-top-menu"></a>
           </div>
         </div>
         <?php } ?>


        <div class="container">
          <div class="header">
            <!-- Logo Start -->
            <div class="logo">
      <?php elanzalite_the_custom_logo(); ?>
          <?php
          if(get_theme_mod('header_textcolor')!='blank'){
           if ( is_front_page() && is_home() ) : ?>
            <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
          <?php else : ?>
            <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
          <?php endif;

          $description = get_bloginfo( 'description', 'display' );
          if ( $description || is_customize_preview() ) : ?>
            <p class="site-description"><?php echo $description; ?></p>
          <?php endif;  } ?>
        </div><!-- .site-branding -->

        <?php if($header_style_one=='header-style-one'):?>
            <!-- header-style-one-div-show start-->
            <div class="header-ads">
              <dic class="content-ads">
                <?php if(get_theme_mod('ads_select','ads_image')=='ads_image'):
                    if(get_theme_mod('hdr_ads_image','')!==''){?>

                     <a href="<?php echo get_theme_mod('ads_link');?>" target="_blank"><img src="<?php echo get_theme_mod('hdr_ads_image');?>">

                 <?php }else{ ?>
                 <a href="#"><img src="<?php echo get_template_directory_uri(); ?>/images/Elanza-magazine-ad.png"></a>
                <?php } endif;?>
                <?php if(get_theme_mod('ads_select','ads_code')=='ads_code'): ?>
                   <?php echo get_theme_mod('hdr_adsense_code');?> 
                <?php endif;?>
              </dic>
            </div>
            <!-- header-style-one-div-show end-->
            <?php endif; ?>
            <!-- Menu Start -->
            <div id="main-menu-wrapper">
              <a href="#" id="pull" class="toggle-mobile-menu"></a>
              <nav class="navigation clearfix mobile-menu-wrapper">
                <?php elanzalite_nav_menu(); ?>
              </nav>
              <div class="clearfix"></div>
            </div>
            <!--/ Menu end -->
          </div>
          <!-- / Header End -->
        </div>
      </header>
      <!-- / Top Header End -->