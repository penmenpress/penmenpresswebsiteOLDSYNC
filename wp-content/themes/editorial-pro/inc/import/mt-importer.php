<?php
/**
 * Several functions related to import demo data
 * 
 * @package Mystery Themes
 * @subpackage Editorial Pro
 * @since 1.0.0
 */

/*---------------------------------------------------------------------------------------------------------------*/
/**
 * Custom classes for import demo data
 *
 * @since 1.0.0
 */
if ( class_exists( 'WP_Customize_Control' ) ) { 
    /** 
     * Import Demo Data Cusotm Customizer Control
     */

    class Editorial_Pro_WP_Customize_Demo_Control extends WP_Customize_Control{            
        public function render_content() { ?>
            <label>
                <?php if ( ! empty( $this->label ) ) : ?>
                    <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                <?php endif; ?>
                <div class="">
                    <a href="#" class="button-primary" id="mt_demo_import"><?php esc_html_e( 'Import Demo','editorial-pro' ); ?></a>
                    <div class=""></div>
                    <div class="import-message"><?php esc_html_e( 'Click on Import Demo button about importing demo contents.','editorial-pro' ); ?></div>
                </div>
            </label>
<?php
        }
    }
}

/**
 * customizer option for import demo data
 *
 * @since 1.0.0
 */
add_action( 'customize_register', 'editorial_pro_demo_import_settings_register' );

function editorial_pro_demo_import_settings_register( $wp_customize ) {

/*---------------------------------------------------------------------------------------------------------------*/
    /**
     * Demo Data Import Function Area
     */
    $wp_customize->add_section( 
        'demo_data_import_section', 
        array(
            'title'     =>   __( 'Demo Data', 'editorial-pro' ),
            'priority'  =>  1,
        )
    );

    $wp_customize->add_setting( 
        'demo_data_import', 
        array(
            'default' =>  '1',
            'sanitize_callback' =>  'editorial_pro_sanitize_text'
        )
    );
    $wp_customize->add_control( new Editorial_Pro_WP_Customize_Demo_Control ( 
        $wp_customize, 
        'demo_data_import', 
            array(
                'section'  =>  'demo_data_import_section',
                'label'    =>  __( 'Import Demo Data', 'editorial-pro' ),
                'priority' => 2
            )
        )
    );
}

/*---------------------------------------------------------------------------------------------------------------*/
/**
 * Ajax call for importing demo
 *
 * @since 1.0.0
 */
add_action( 'wp_ajax_editorial_pro_demo_import', 'editorial_pro_action_callback' );

function editorial_pro_action_callback(){
    global $wpdb; 

    if ( !defined('WP_LOAD_IMPORTERS') ) define('WP_LOAD_IMPORTERS', true);

    // Load Importer API
    require_once ABSPATH . 'wp-admin/includes/import.php';
    $importer_error = false;

    if ( ! class_exists( 'WP_Importer' ) ) {
        $class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
        if ( file_exists( $class_wp_importer ) ) {
            require_once $class_wp_importer;
        } else {
			$importer_error = true;
		}
    }

    if ( ! class_exists( 'WP_Import' ) ) {
        $class_wp_importer = get_template_directory() ."/inc/import/wordpress-importer.php";
        if ( file_exists( $class_wp_importer ) ) {
            require_once $class_wp_importer;
        } else {
            $importer_error = true;
        }
    }


    if( $importer_error ){
	   die( "Import error! Please unninstall Wordpress importer plugin and try again" );
    } else {

        wp_delete_post(1);
        wp_delete_post(2); 
        
        $import_filepath = get_template_directory() ."/inc/import/tmp/editorialpro.xml" ; // Get the xml file from directory 

        require get_template_directory() . '/inc/import/mt-import.php';

        $wp_import = new Mt_Import();
        $wp_import->fetch_attachments = true;
        $wp_import->import( $import_filepath );
        $wp_import->set_widgets();
        $wp_import->set_theme_mods();
      	$wp_import->set_menu();      
      
        $page = get_page_by_path( 'home' );
        if ( $page ) {
            $page_id  = $page->ID;
       }

       update_option( 'show_on_front', 'page' );
       update_option( 'page_on_front', $page_id );
    }
    
    die(); // this is required to return a proper result 
}