<?php
/**
 * The template for displaying a "No posts found" message
 */
?>
<div class="page-header">
	<h1 class="page-title"><?php esc_html__( 'Nothing Found', 'elanzalite' ); ?></h1>
</div>
<div class="page-content">
	<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

	<p><?php printf( esc_html__( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'elanzalite' ), admin_url( 'post-new.php' ) ); ?></p>

	<?php elseif ( is_search() ) : ?>

	<p><?php esc_html__( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'elanzalite' ); ?></p>
	<?php get_search_form(); ?>

	<?php else : ?>

	<p><?php esc_html__( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'elanzalite' ); ?></p>
	<?php get_search_form(); ?>

	<?php endif; ?>
</div>
<!-- .page-content -->
