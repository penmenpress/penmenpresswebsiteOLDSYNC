<?php
function elanzalite_widgets_init() {
// Area , located below the Primary Widget Area in the sidebar. Empty by default.
register_sidebar(array(
'name' => __('Primary Sidebar', 'elanzalite'),
'id' => 'primary-sidebar',
'description' => __('Main sidebar that appears on the left.', 'elanzalite'),
'before_widget' => '<div class="sidebar-inner-widget">',
'after_widget' => '</div><div class="clearfix"></div>',
'before_title' => '<h4 class="widgettitle">',
'after_title' => '</h4>',
));
// Area , located below the Secondry Widget Area in the sidebar. Empty by default.
register_sidebar(array(
'name' => __('Secondry Sidebar', 'elanzalite'),
'id' => 'secondary-sidebar',
'description' => __('Secondry sidebar that appears on the left.', 'elanzalite'),
'before_widget' => '<div class="sidebar-inner-widget">',
'after_widget' => '</div><div class="clearfix"></div>',
'before_title' => '<h4 class="widgettitle">',
'after_title' => '</h4>',
));
// Area 1, located in the footer. Empty by default.
register_sidebar(array(
'name' => __('First Footer Widget Area', 'elanzalite'),
'id' => 'first-footer-widget-area',
'description' => __('Appears in the first footer section of the site.', 'elanzalite'),
'before_widget' => '',
'after_widget' => '',
'before_title' => '<h4 class="widgettitle" >',
'after_title' => '</h4>',
));
// Area 2, located in the footer. Empty by default.
register_sidebar(array(
'name' => __('Second Footer Widget Area', 'elanzalite'),
'id' => 'second-footer-widget-area',
'description' => __('Appears in the Second footer section of the site.', 'elanzalite'),
'before_widget' => '',
'after_widget' => '',
'before_title' => '<h4 class="widgettitle" >',
'after_title' => '</h4>',
));

// Area 3, located in the footer. Empty by default.
register_sidebar(array(
'name' => __('Third Footer Widget Area', 'elanzalite'),
'id' => 'third-footer-widget-area',
'description' => __('Appears in the Third footer section of the site.', 'elanzalite'),
'before_widget' => '',
'after_widget' => '',
'before_title' => '<h4 class="widgettitle">',
'after_title' => '</h4>',
));
}
/** Register sidebars by running elanzalite_widgets_init() on the widgets_init hook. */
add_action('widgets_init', 'elanzalite_widgets_init');
