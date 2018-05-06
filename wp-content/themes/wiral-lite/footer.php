<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package wiral
 */

?>		</div><!-- .inner -->
	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<div class="inner clearfix">
				<div class="copyright">
					<?php wiralite_footer_copyright() ?>
				</div>
				<?php if( has_nav_menu('footer'))  : ?>
				<div class="menu-footer">
					<?php wp_nav_menu( array( 'theme_location' => 'footer', 'menu_class' => 'menu clearfix' ) ); ?>
				</div>
				<?php endif; ?>
			</div>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->
<!-- Back To Top -->
<span class="back-to-top"><i class="fa fa-angle-double-up"></i></span>
<?php wp_footer(); ?>
</body>
</html>
