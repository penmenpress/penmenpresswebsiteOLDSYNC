<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Mystery Themes
 * @subpackage Editorial Pro
 * @since 1.0.0
 */

get_header(); 
$post_id = get_the_ID();

$this_post_layout = get_post_meta( $post_id, 'editorial_pro_post_layout', true );
if( $this_post_layout == 'global_layout' || empty( $this_post_layout ) ) {
	$this_post_layout = get_theme_mod( 'editorial_pro_default_post_layout', 'post_layout_1' );
}

switch ( $this_post_layout ) {
	case 'post_layout_2':
		$req_file = 'two';
		break;
	case 'post_layout_3':
		$req_file = 'three';
		break;
	case 'post_layout_4':
		$req_file = 'four';
		break;
	case 'post_layout_5':
		$req_file = 'five';
		break;
	default:
		$req_file = 'one';
		break;
}

	while ( have_posts() ) : the_post();

		get_template_part( 'layouts/posts/layout', $req_file );

	endwhile; // End of the loop.

	/**
	 * Set post view
	 */
	editorial_set_post_views( get_the_ID() );

	get_footer();