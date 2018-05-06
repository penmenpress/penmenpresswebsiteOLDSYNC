<?php 
/**
 * File for news ticker section
 *
 * @package Mystery Themes
 * @subpackage Editorial Pro
 * @since 1.0.0
 *
 */

$editorial_pro_ticker_option = get_theme_mod( 'editorial_pro_ticker_option', 'enable' );
if( $editorial_pro_ticker_option != 'disable' && is_front_page() ) {
	$editorial_pro_ticker_caption = get_theme_mod( 'editorial_pro_ticker_caption', __( 'Latest', 'editorial-pro' ) );
	$editorial_pro_ticker_layout = get_theme_mod( 'editorial_pro_ticker_layout', 'ticker_layout_1' );
?>
	<div class="editorial-ticker-wrapper <?php echo esc_attr( $editorial_pro_ticker_layout ); ?>">
		<div class="mt-container">
			<span class="ticker-caption"><?php echo esc_html( $editorial_pro_ticker_caption ); ?></span>
			<div class="ticker-content-wrapper">
				<?php
					$ticker_args = editorial_pro_query_args( $cat_id = null, 10 );
					$ticker_query = new WP_Query( $ticker_args );
					if( $ticker_query->have_posts() ) {
						echo '<ul class="tickerWrap cS-hidden">';
						while( $ticker_query->have_posts() ) {
							$ticker_query->the_post();
				?>			
							<li><a href="<?php the_permalink(); ?>"><?php the_title();?></a></li>
				<?php
						}
						echo '</ul><!-- .tickerWrap -->';
					}
				?>
			</div>
		</div><!-- .mt-container -->
	</div><!-- .editorial-ticker-wrapper-->
<?php
}