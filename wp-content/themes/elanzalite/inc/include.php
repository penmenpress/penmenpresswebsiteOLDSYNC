<?php
/**
 * all file includeed
 *
 * @param  
 * @return mixed|string
 */
	require get_parent_theme_file_path('/inc/plugin-install.php');
	require get_parent_theme_file_path('/inc/static-function.php');
	require get_parent_theme_file_path('/inc/breadcrumb.php' );	
	//sidebar and footer widget
	require get_parent_theme_file_path('/inc/widget.php');
	require get_parent_theme_file_path('/inc/customizer.php');
	require trailingslashit( get_template_directory() ) . '/inc/elanzalite-theme.php';
	require trailingslashit( get_template_directory() ) . '/inc/pro-button/class-customize.php';


