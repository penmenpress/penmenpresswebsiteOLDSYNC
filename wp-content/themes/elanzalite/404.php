<?php
/**
* The template for displaying 404 pages (Not Found)
*/
get_header(); ?>
<div class="container" class="clearfix">
	<div class="page-title breadcrumb">
		<h1><?php esc_html_e( 'Not Found', 'elanzalite' ); ?></h1>
	</div>
</div>
<div id="page" class="clearfix right" >
	<div class="content">
		<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try a search?', 'elanzalite' ); ?></p>
		<?php get_search_form(); ?>
	</div>
	<div class="sidebar-wrapper">
		<?php get_sidebar(); ?>
	</div>
</div>
<!-- page End -->
<?php get_footer(); ?>