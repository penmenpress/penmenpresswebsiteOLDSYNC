<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package wiral
 */
$theme_options = wiral_lite_theme_options();
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php endif; ?>

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'wiral-lite' ); ?></a>

	<header id="masthead" class="site-header" role="banner">
		<div <?php if ( $theme_options['sticky_header'] == 1 ) :  ?>id="sticky"<?php endif; ?> class="header clearfix">
			<div class="inner">
				<div class="menu-wrap">
					<a class="toggle-mobile-menu" href="#" title="Menu"><i class="fa fa-bars"></i>&nbsp;&nbsp;Browse</a>
					<nav id="primary-navigation" class="main-navigation" role="navigation">
						<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu', 'menu_class' => 'menu clearfix' ) ); ?>
					</nav><!-- #site-navigation -->
				</div>
				<div class="site-branding">
					<?php wiral_lite_header_title() ?>
				</div><!-- .site-branding -->
				<div class="search-style-one">
					<a id="trigger-overlay">
						<i class="fa fa-search"></i>
					</a>
					<div class="overlay overlay-slideleft">
						<div class="search-row">
							<form method="get" id="searchform" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" _lpchecked="1">
								<a ahref="#" class="overlay-close"><i class="fa fa-times"></i></a>
								<input type="text" name="s" id="s" value="<?php echo get_search_query(); ?>" placeholder="<?php esc_html_e('Search Keyword ...', 'wiral-lite'); ?>" />
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="catcher"></div>
	</header><!-- #masthead -->
	<?php
		$sidebar_position = $theme_options['layout'];

		if ( $sidebar_position == 'left-sidebar' || is_page_template( 'sidebar-left.php' )) {
			$class = ' sidebar-left';
		} else {
			$class = ' sidebar-right';
		}

		// for page condition
		if (is_page()) {
			if ( is_page_template( 'sidebar-left.php' )) {
				$class = ' sidebar-left';
			} else if ( is_page_template( 'full-width.php' )) {
				$class = ' full-width';
			} else {
				$class = ' sidebar-right';
			}
		}
	?>

	<div id="content" class="site-content<?php echo $class; ?>">
		<div class="inner clearfix">