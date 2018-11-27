<?php
/**
 * Define HTML for header layout 1
 *
 * @package Mystery Themes
 * @subpackage Editorial Pro
 * @since 1.0.0
 *
 */
?>

<header id="masthead" class="site-header" role="banner">
		
	<?php do_action( 'editorial_pro_top_header' ); ?>

	<div class="logo-ads-wrapper clearfix">
		<div class="mt-container">
			<div class="site-branding">
				<?php if ( the_custom_logo() ) { ?>
					<div class="site-logo">
						<?php the_custom_logo(); ?>
					</div><!-- .site-logo -->
				<?php } ?>
				<?php 
					$site_title_option = get_theme_mod( 'header_textcolor' );
					if( $site_title_option != 'blank' ) {
				?>
					<div class="site-title-wrapper">
						<?php
						if ( is_front_page() && is_home() ) : ?>
							<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
						<?php else : ?>
							<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
						<?php
						endif;

						$description = get_bloginfo( 'description', 'display' );
						if ( $description || is_customize_preview() ) : ?>
							<p class="site-description"><?php echo $description; /* WPCS: xss ok. */ ?></p>
						<?php
						endif; ?>
					</div><!-- .site-title-wrapper -->
				<?php 
					}
				?>
			</div><!-- .site-branding -->
			<div class="header-ads-wrapper">
				<?php
		        	if( is_active_sidebar( 'editorial_pro_header_ads_area' ) ) {
		            	if ( !dynamic_sidebar( 'editorial_pro_header_ads_area' ) ):
		            	endif;
		         	}
		        ?>
			</div><!-- .header-ads-wrapper -->
		</div>
	</div><!-- .logo-ads-wrapper -->

	<div id="mt-menu-wrap" class="bottom-header-wrapper clearfix">
		<div class="mt-container">
			<?php
				$ep_home_icon_option = get_theme_mod( 'editorial_pro_home_icon_option', 'enable' );
				if( $ep_home_icon_option == 'enable' ) {
			?>
			<div class="home-icon"> <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"> <i class="fa fa-home"> </i> </a> </div>
			<?php } ?>
			<a href="#" class="menu-toggle"> <i class="fa fa-navicon"> </i> </a>
			<nav id="site-navigation" class="main-navigation" role="navigation">
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container_class' => 'menu', 'items_wrap' => '<ul>%3$s</ul>' ) ); ?>
			</nav><!-- #site-navigation -->
			<div class="header-search-wrapper">
                <span class="search-main"><i class="fa fa-search"></i></span>
                <div class="search-form-main clearfix">
	                <?php get_search_form(); ?>
	            </div>
			</div><!-- .header-search-wrapper -->
		</div><!-- .mt-container -->
	</div><!-- #mt-menu-wrap -->

	<?php get_template_part( 'layouts/headers/news', 'ticker' ); ?>
		
</header><!-- #masthead -->