<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package wiral
 */

if ( ! is_active_sidebar( 'sidebar-2' ) ) {
	return;
}
?>
<?php dynamic_sidebar( 'sidebar-2' ); ?>
