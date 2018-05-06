<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Mystery Themes
 * @subpackage Editorial Pro
 * @since 1.0.0
 */

	$container_value = 'show';
	if( is_single() ) {
		global $post;

		$editorial_post_layout = get_post_meta( $post->ID, 'editorial_pro_post_layout', true );
        if( $editorial_post_layout == 'global_layout' || empty( $editorial_post_layout ) ) {
            $editorial_post_layout = get_theme_mod( 'editorial_pro_default_post_layout', 'post_layout_1' );
        }
        if( is_single() && $editorial_post_layout == 'post_layout_5' ) {
        	$container_value = 'hide';
        } else {
        	$container_value = 'show';
        }
    }
	if ( !is_page_template( 'templates/magazine-template.php' ) && $container_value == 'show' ) {
		echo '</div><!--.mt-container-->';
	}
?>
	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
			<?php get_sidebar( 'footer' ); ?>
			<div id="bottom-footer" class="sub-footer-wrapper clearfix">
				<div class="mt-container">
					<div class="site-info">
						<?php
							$footer_copyright = get_theme_mod( 'editorial_pro_copyright_text', '<span class="copy-info">2016 editorial</span><span class="sep"> | </span>Editorial Pro by <a href="https://mysterythemes.com/" rel="designer" class="customize-unpreviewable">Mystery Themes</a>.' );
							echo $footer_copyright;
						?>
					</div><!-- .site-info -->
					<nav id="footer-navigation" class="sub-footer-navigation" role="navigation">
						<?php wp_nav_menu( array( 'theme_location' => 'footer', 'container_class' => 'footer-menu', 'fallback_cb' => false, 'items_wrap' => '<ul>%3$s</ul>' ) ); ?>
					</nav>
				</div>
			</div><!-- .sub-footer-wrapper -->
	</footer><!-- #colophon -->
	<div id="mt-scrollup" class="animated arrow-hide"><i class="fa fa-chevron-up"></i></div>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>