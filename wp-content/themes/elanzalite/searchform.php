<form role="search" method="get" id="searchform" action="<?php echo esc_url(home_url( '/' )); ?>">
	<div>
		<input type="text" placeholder="<?php echo esc_attr_x( 'Search', 'placeholder', 'elanzalite' ); ?>" name="s" id="s" value="<?php the_search_query(); ?>"/>
		<input type="submit" value="<?php echo esc_attr_x( 'Search', 'text', 'elanzalite' ); ?>" />
	</div>
</form>