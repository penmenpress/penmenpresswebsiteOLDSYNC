<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package wiral
 */

if ( ! function_exists('wiral_lite_header_title') ) :
	
	function wiral_lite_header_title() {

		$logo = get_theme_mod('custom_logo');
		$custom_logo = wp_get_attachment_image_src( $logo , 'full' );
		?>
			<?php if ( !empty($logo) ) : ?>
				<?php if( is_front_page() || is_home() ) : ?>
				<h1 class="site-title logo" itemprop="headline">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" title="<?php echo esc_attr(get_bloginfo( 'description' )); ?>">
						<img src="<?php echo $custom_logo[0]; ?>" alt="<?php echo esc_attr(get_bloginfo( 'description' )); ?>" />
					</a>
				</h1>
				<?php else : ?>
					<h2 class="site-title logo" itemprop="headline">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" title="<?php echo esc_attr(get_bloginfo( 'description' )); ?>">
							<img src="<?php echo $custom_logo[0]; ?>" alt="<?php echo esc_attr(get_bloginfo( 'description' )); ?>" />
						</a>
					</h2>
				<?php endif ?>
			<?php else : ?>
				<?php if( is_front_page() || is_home() ) : ?>
					<h1 itemprop="headline" class="site-title">
						<a itemprop="url" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" title="<?php echo esc_attr(get_bloginfo( 'description' )); ?>">
							<?php bloginfo( 'name' ); ?>
						</a>
					</h1>
					<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
					<?php else : ?>
						<h2 class="site-title">
						<a itemprop="url" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" title="<?php echo esc_attr(get_bloginfo( 'description' )); ?>">
							<?php bloginfo( 'name' ); ?>
						</a>
						</h2>
						<h3 class="site-description"><?php bloginfo( 'description' ); ?></h3>
					<?php endif ?>
			<?php endif ?>
		<?php
	}
endif;

if ( ! function_exists( 'wiral_lite_the_posts_navigation' ) ) :
/**
 |------------------------------------------------------------------------------
 | Display navigation to next/previous set of posts when applicable.
 |------------------------------------------------------------------------------
 |
 | @todo Remove this function when WordPress 4.3 is released.
 |
 */
function wiral_lite_the_posts_navigation() {
	

	$theme_options = wiral_lite_theme_options();

	$nav_style =  $theme_options['paging'];
	
	if ( $nav_style == 'pageing-numberal') :
		// Previous/next page navigation.
			the_posts_pagination( array(
				'prev_text'          => __( 'Previous page', 'wiral-lite' ),
				'next_text'          => __( 'Next page', 'wiral-lite' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'wiral-lite' ) . ' </span>',
			) );

	else :
			$args = array(
	            'prev_text'          =>  __( '<i class="fa fa-angle-double-left"></i> Older posts', 'wiral-lite' ),
	            'next_text'          => __( 'Newer posts <i class="fa fa-angle-double-right"></i>', 'wiral-lite' )
        	);

			the_posts_navigation($args);	
	
	endif;
}
endif;

if ( ! function_exists( 'wiral_lite_footer_copyright' ) ) :

	function wiralite_footer_copyright() {
		?>
		<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'wiral-lite' ) ); ?>"><?php printf( esc_html__( 'Proudly powered by %s', 'wiral-lite' ), 'WordPress' ); ?></a>
					<span class="sep"> | </span>
					<?php printf( esc_html__( 'Theme: %1$s by %2$s.', 'wiral-lite' ), 'Wiral Lite', '<a href="'. WIRAL_PRO_URL .'" rel="designer">ThemeCountry</a>' ); ?>
		<?php

	}

endif;

if ( ! function_exists( 'wiral_lite_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function wiral_lite_posted_on() {

	
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			esc_html_x( 'Posted on %s ', 'post date', 'wiral-lite' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		$byline = sprintf(
			esc_html_x( 'By %s', 'post author', 'wiral-lite' ),
			'<span class="author vcard" itemprop="name"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="posted-on"> ' . $posted_on . '</span>';
		echo '<span class="byline">' . $byline . '</span>';
}
endif;

if ( ! function_exists( 'wiral_lite_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function wiral_lite_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' == get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_html__( ', ', 'wiral-lite' ) );
		if ( $categories_list && wiral_lite_categorized_blog() ) {
			printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'wiral-lite' ) . '</span>', $categories_list ); // WPCS: XSS OK.
		}

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', esc_html__( ', ', 'wiral-lite' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'wiral-lite' ) . '</span>', $tags_list ); // WPCS: XSS OK.
		}
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( esc_html__( 'Leave a comment', 'wiral-lite' ), esc_html__( '1 Comment', 'wiral-lite' ), esc_html__( '% Comments', 'wiral-lite' ) );
		echo '</span>';
	}

	edit_post_link('<i class="fa fa-pencil-square-o"></i>', '<span class="edit-link">', '</span>' ); 
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function wiral_lite_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'wiral_lite_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'wiral_lite_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so wiral_lite_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so wiral_lite_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in wiral_lite_categorized_blog.
 */
function wiral_lite_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'wiral_lite_categories' );
}
add_action( 'edit_category', 'wiral_lite_category_transient_flusher' );
add_action( 'save_post',     'wiral_lite_category_transient_flusher' );