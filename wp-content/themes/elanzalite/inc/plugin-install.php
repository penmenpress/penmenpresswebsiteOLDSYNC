<?php

/**
 * tgm plugin installation and activation
 *
 * @param  
 * @return mixed|string
 */

function elanzalite_plugin_setup(){
    require get_template_directory() . '/class-tgm-plugin-activation.php';
}
add_action('after_setup_theme', 'elanzalite_plugin_setup');
function elanzalite_register_required_plugins()
{
    //$wp_version_nr = get_bloginfo('version');
        $plugins = array(
            array(
                'name' => 'Contact Form',
                'slug' => 'lead-form-builder', 
                
            ),
             array(
                'name' => 'Themehunk Customizer',
                'slug' => 'themehunk-customizer', 
                
            )
        );

    $config = array(
        'default_path' => '',
        'menu' => 'tgmpa-install-plugins',
        'has_notices' => true,
        'dismissable' => true,
        'dismiss_msg' => '',
        'is_automatic' => false,
        'message' => '',
        'strings' => array(
            'page_title' => __('Install Required Plugins', 'elanzalite'),
            'menu_title' => __('Install Plugins', 'elanzalite'),
            'installing' => __('Installing Plugin: %s', 'elanzalite'),
            'oops' => __('Something went wrong with the plugin API.', 'elanzalite'),
            'notice_can_install_required' => _n_noop('This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.','elanzalite'),
            'notice_can_install_recommended' => _n_noop('This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.','elanzalite'),
            'notice_cannot_install' => _n_noop('Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.','elanzalite'),
            'notice_can_activate_required' => _n_noop('The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.','elanzalite'),
            'notice_can_activate_recommended' => _n_noop('The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.','elanzalite'),
            'notice_cannot_activate' => _n_noop('Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.','elanzalite'),
            'notice_ask_to_update' => _n_noop('The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.','elanzalite'),
            'notice_cannot_update' => _n_noop('Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.','elanzalite'),
            'install_link' => _n_noop('Begin installing plugin', 'Begin installing plugins','elanzalite'),
            'activate_link' => _n_noop('Begin activating plugin', 'Begin activating plugins','elanzalite'),
            'return' => __('Return to Required Plugins Installer', 'elanzalite'),
            'plugin_activated' => __('Plugin activated successfully.', 'elanzalite'),
            'complete' => __('All plugins installed and activated successfully. %s', 'elanzalite'),
            'nag_type' => 'updated'
        )
    );
    tgmpa($plugins, $config);
}
add_action('tgmpa_register', 'elanzalite_register_required_plugins');
?>