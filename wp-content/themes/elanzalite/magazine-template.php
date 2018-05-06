<?php
/*
Template Name: Magazine Template
*/
?>
<?php
get_header();

?>
<input id="magzinelayout" type="text" hidden value="no-sidebar">
<div id="page" class="clearfix magazine-top no-sidebar">
<div class="content"><!-- Content Start -->
<div class="page-content"><!-- blog-single -->
<div class="page-description">
	<!-- magzine-tamplte-html-start -->	
	<?php  if ( shortcode_exists( 'themehunk-customizer-elanzalite' ) ){
	do_shortcode( '[themehunk-customizer-elanzalite page="magzine"]' );
	}  ?>
	<!-- magzine-tamplte-html-end -->
</div>
</div>
</div>
</div>
<?php $layout = elanzalite_magzine_get_layout();?>
<div id="page" class="clearfix magazine-bottom <?php echo $layout;?>">
<div class="content"><!-- Content Start -->
<div class="page-content"><!-- blog-single -->
<div class="page-description">
	<!-- magzine-tamplte-html-start -->	
	<?php  if ( shortcode_exists( 'themehunk-customizer-elanzalite' ) ){
	do_shortcode( '[themehunk-customizer-elanzalite page="magzine-sidebar"]' );
	}  ?>
	<!-- magzine-tamplte-html-end -->
</div>
</div>
</div>
<!-- / Content End -->
<?php if($layout!='no-sidebar'): ?>
<div class="sidebar-wrapper">
<?php get_sidebar(); ?>
</div>
<?php endif; ?>
</div>
<?php get_footer(); ?>