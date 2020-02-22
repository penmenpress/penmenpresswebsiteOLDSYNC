<?php

/**
* Tag Groups
*
* @package     Tag Groups
* @author      Christoph Amthor
* @copyright   2019 Christoph Amthor (@ Chatty Mango, chattymango.com)
* @license     GPL-3.0+
*
*/
if ( !class_exists( 'TagGroups_Loader' ) ) {
    class TagGroups_Loader
    {
        /**
         * absolute path to the plugin main file
         *
         * @var string
         */
        var  $plugin_path ;
        function __construct( $plugin_path )
        {
            $this->plugin_path = $plugin_path;
        }
        
        /**
         * Provide objects that we'll need frequently
         *
         * @param void
         * @return object $this
         */
        public function provide_globals()
        {
            global  $tag_group_groups, $tag_group_terms ;
            $tag_group_groups = new TagGroups_Groups();
            $tag_group_terms = new TagGroups_Terms();
            return $this;
        }
        
        /**
         * Adds all required classes
         *
         * @param void
         * @return object $this
         */
        public function require_classes()
        {
            /*
             * Require all classes of this plugin
             */
            foreach ( glob( $this->plugin_path . '/include/entities/*.php' ) as $filename ) {
                require_once $filename;
            }
            foreach ( glob( $this->plugin_path . '/include/helpers/*.php' ) as $filename ) {
                require_once $filename;
            }
            foreach ( glob( $this->plugin_path . '/include/admin/*.php' ) as $filename ) {
                require_once $filename;
            }
            /**
             * Must be after helpers
             */
            foreach ( glob( $this->plugin_path . '/include/shortcodes/*.php' ) as $filename ) {
                require_once $filename;
            }
            return $this;
        }
        
        /**
         * Sets the version from the plugin main file
         *
         * @param void
         * @return object $this;
         */
        public function set_version()
        {
            if ( defined( 'TAG_GROUPS_VERSION' ) ) {
                return $this;
            }
            if ( !function_exists( 'get_plugin_data' ) ) {
                require_once ABSPATH . '/wp-admin/includes/plugin.php';
            }
            $plugin_header = get_plugin_data( WP_PLUGIN_DIR . '/' . TAG_GROUPS_PLUGIN_BASENAME, false, false );
            
            if ( isset( $plugin_header['Version'] ) ) {
                $version = $plugin_header['Version'];
            } else {
                $version = '1.0';
            }
            
            define( 'TAG_GROUPS_VERSION', $version );
        }
        
        /**
         * Check if WordPress meets the minimum version
         *
         * @param void
         * @return void
         */
        public function check_preconditions()
        {
            if ( !defined( 'TAG_GROUPS_MINIMUM_VERSION_WP' ) ) {
                return;
            }
            global  $wp_version ;
            // Check the minimum WP version
            
            if ( version_compare( $wp_version, TAG_GROUPS_MINIMUM_VERSION_WP, '<' ) ) {
                error_log( '[Tag Groups] Insufficient WordPress version for Tag Groups plugin.' );
                TagGroups_Admin_Notice::add( 'error', sprintf( __( 'The plugin %1$s requires WordPress %2$s to function properly.', 'tag-groups' ), '<b>Tag Groups</b>', TAG_GROUPS_MINIMUM_VERSION_WP ) . __( 'Please upgrade WordPress and then try again.', 'tag-groups' ) );
                return;
            }
        
        }
        
        /**
         * adds all hooks
         *
         * @param void
         * @return object $this
         */
        public function add_hooks()
        {
            global  $tag_group_groups, $tag_group_terms ;
            add_action( 'init', array( $this, 'add_init_hooks' ) );
            // general stuff
            add_action( 'plugins_loaded', array( $this, 'register_textdomain' ) );
            
            if ( is_admin() ) {
                // backend stuff
                add_action( 'admin_init', array( 'TagGroups_Admin', 'admin_init' ) );
                add_action( 'admin_init', array( $this, 'check_old_premium' ) );
                add_action( 'admin_menu', array( 'TagGroups_Admin', 'register_menus' ) );
                add_action( 'admin_enqueue_scripts', array( 'TagGroups_Admin', 'add_admin_js_css' ) );
                add_action( 'admin_notices', array( 'TagGroups_Admin_Notice', 'display' ) );
                /**
                 * Processing routines in chunks with process bar
                 */
                add_action( 'wp_ajax_tg_free_ajax_process', array( 'TagGroups_Process', 'tg_ajax_process' ) );
                // ### Terms ###
                /**
                 * After a term has changed its groups, we must update the array of terms per group.
                 */
                add_action( 'groups_of_term_saved', array( $tag_group_groups, 'clear_tag_groups_group_terms' ), 11 );
                // ### Groups ###
                /**
                 * After a term group has been deleted, we must update the tag meta with the groups.
                 */
                add_action( 'term_group_deleted', array( $tag_group_terms, 'remove_missing_groups' ) );
                // ### Taxonomies ###
                /**
                 * After the taxonomies have been changed, we check if we must migrate all newly enabled tags.
                 */
                add_action( 'taxonomies_saved', array( $tag_group_terms, 'check_if_we_need_to_run_migration' ), 10 );
            } else {
                // frontend stuff
                add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
                add_action( 'init', array( 'TagGroups_Shortcode', 'widget_hook' ) );
            }
            
            return $this;
        }
        
        /**
         * adds all hooks that need to be registered after init
         *
         * @param void
         * @return object $this
         */
        public function add_init_hooks()
        {
            return $this;
        }
        
        /**
         * registers the shortcodes with Gutenberg blocks
         *
         * @param void
         * @return object $this
         */
        public function register_shortcodes_and_blocks()
        {
            /**
             * add Gutenberg functionality
             */
            require_once $this->plugin_path . '/src/init.php';
            // Register shortcodes also for admin so that we can remove them with strip_shortcodes in Ajax call
            TagGroups_Shortcode::register();
            return $this;
        }
        
        /**
         * registers the REST API
         *
         * @param void
         * @return object $this
         */
        public function register_REST_API()
        {
            TagGroups_REST_API::register();
            return $this;
        }
        
        /**
         * Check if we don't have any old Tag Groups Premium
         *
         * @param void
         * @return void
         */
        public function check_old_premium()
        {
            // Check the minimum WP version
            
            if ( defined( 'TAG_GROUPS_VERSION' ) && defined( 'TAG_GROUPS_VERSION' ) && version_compare( TAG_GROUPS_VERSION, '0.38', '>' ) && version_compare( TAG_GROUPS_VERSION, '1.12', '<' ) ) {
                error_log( '[Tag Groups Premium] Incompatible versions of Tag Groups and Tag Groups Premium.' );
                TagGroups_Admin_Notice::add( 'info', sprintf( __( 'Your version of Tag Groups Premium is out of date and will not work with this version of Tag Groups. Please <a %s>update Tag Groups Premium</a>.', 'tag-groups' ), 'href="https://documentation.chattymango.com/documentation/tag-groups-premium/maintenance-and-troubleshooting/updating-tag-groups-premium/" target="_blank"' ), '<b>Tag Groups</b>' );
                return;
            }
        
        }
        
        /**
         *   Initializes values and prevents errors that stem from wrong values, e.g. based on earlier bugs.
         *   Runs when plugin is activated.
         *
         * @param void
         * @return void
         */
        static function on_activation()
        {
            global  $tag_groups_premium_fs_sdk ;
            
            if ( !current_user_can( 'activate_plugins' ) ) {
                if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                    error_log( '[Tag Groups] Insufficient permissions to activate plugin.' );
                }
                return;
            }
            
            if ( TAG_GROUPS_PLUGIN_IS_KERNL ) {
                register_uninstall_hook( TAG_GROUPS_PLUGIN_ABSOLUTE_PATH, array( 'TagGroups_Activation_Deactivation', 'on_uninstall' ) );
            }
            $tag_groups_loader = new TagGroups_Loader( __FILE__ );
            // $tag_groups_loader->require_classes();
            if ( !defined( 'TAG_GROUPS_VERSION' ) ) {
                $tag_groups_loader->set_version();
            }
            update_option( 'tag_group_base_version', TAG_GROUPS_VERSION );
            /*
             * Taxonomy should not be empty
             */
            $tag_group_taxonomy = get_option( 'tag_group_taxonomy', array() );
            
            if ( empty($tag_group_taxonomy) ) {
                update_option( 'tag_group_taxonomy', array( 'post_tag' ) );
            } elseif ( !is_array( $tag_group_taxonomy ) ) {
                // Prevent some weird errors
                update_option( 'tag_group_taxonomy', array( $tag_group_taxonomy ) );
            }
            
            /*
             * Theme should not be empty
             */
            if ( '' == get_option( 'tag_group_theme', '' ) ) {
                update_option( 'tag_group_theme', TAG_GROUPS_STANDARD_THEME );
            }
            /**
             * Register time of first use
             */
            
            if ( defined( 'TAG_GROUPS_PLUGIN_IS_FREE' ) && TAG_GROUPS_PLUGIN_IS_FREE ) {
                if ( !get_option( 'tag_group_base_first_activation_time', false ) ) {
                    update_option( 'tag_group_base_first_activation_time', time() );
                }
            } else {
            }
            
            // If requested and new options exist, then remove old options.
            
            if ( defined( 'TAG_GROUPS_REMOVE_OLD_OPTIONS' ) && TAG_GROUPS_REMOVE_OLD_OPTIONS && get_option( 'term_groups', false ) && get_option( 'term_group_positions', false ) && get_option( 'term_group_labels', false ) ) {
                delete_option( 'tag_group_labels' );
                delete_option( 'tag_group_ids' );
                delete_option( 'max_tag_group_id' );
                if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                    error_log( '[Tag Groups] Deleted deprecated options' );
                }
            }
            
            // purge cache
            
            if ( class_exists( 'TagGroups_Cache' ) ) {
                $cache = new TagGroups_Cache();
                $cache->type( get_option( 'tag_group_object_cache', TagGroups_Cache::WP_OPTIONS ) )->path( WP_CONTENT_DIR . '/chatty-mango/cache/' )->purge_all();
            }
            
            $tag_groups_loader->register_CRON();
            /**
             * Start with some delay so that in the case of simultaneous activation the base plugin will be available
             */
            wp_schedule_single_event( time() + 2, 'tag_groups_check_tag_migration' );
            wp_schedule_single_event( time() + 20, 'tag_groups_run_post_migration' );
            /**
             * Reset the group filter above the tags list
             */
            update_option( 'tag_group_tags_filter', array() );
        }
        
        /**
         * Adds js and css to frontend
         *
         *
         * @param void
         * @return void
         */
        public function enqueue_scripts()
        {
            /* enqueue frontend scripts and styling only if shortcode in use */
            global  $post ;
            
            if ( get_option( 'tag_group_shortcode_enqueue_always', 1 ) || (!is_a( $post, 'WP_Post' ) || (has_shortcode( $post->post_content, 'tag_groups_cloud' ) || has_shortcode( $post->post_content, 'tag_groups_accordion' ) || has_shortcode( $post->post_content, 'tag_groups_table' ) || has_shortcode( $post->post_content, 'tag_groups_tag_list' ) || has_shortcode( $post->post_content, 'tag_groups_alphabetical_index' ))) ) {
                $theme = get_option( 'tag_group_theme', TAG_GROUPS_STANDARD_THEME );
                $default_themes = explode( ',', TAG_GROUPS_BUILT_IN_THEMES );
                $tag_group_enqueue_jquery = get_option( 'tag_group_enqueue_jquery', 1 );
                
                if ( $tag_group_enqueue_jquery ) {
                    wp_enqueue_script( 'jquery' );
                    wp_enqueue_script( 'jquery-ui-core' );
                    wp_enqueue_script( 'jquery-ui-tabs' );
                    wp_enqueue_script( 'jquery-ui-accordion' );
                }
                
                if ( $theme == '' ) {
                    return;
                }
                wp_register_style(
                    'tag-groups-css-frontend-structure',
                    TAG_GROUPS_PLUGIN_URL . '/assets/css/jquery-ui.structure.min.css',
                    array(),
                    TAG_GROUPS_VERSION
                );
                
                if ( in_array( $theme, $default_themes ) ) {
                    wp_register_style(
                        'tag-groups-css-frontend-theme',
                        TAG_GROUPS_PLUGIN_URL . '/assets/css/' . $theme . '/jquery-ui.theme.min.css',
                        array(),
                        TAG_GROUPS_VERSION
                    );
                } else {
                    /*
                     * Load minimized css if available
                     */
                    
                    if ( file_exists( WP_CONTENT_DIR . '/uploads/' . $theme . '/jquery-ui.theme.min.css' ) ) {
                        wp_register_style(
                            'tag-groups-css-frontend-theme',
                            get_bloginfo( 'wpurl' ) . '/wp-content/uploads/' . $theme . '/jquery-ui.theme.min.css',
                            array(),
                            TAG_GROUPS_VERSION
                        );
                    } else {
                        
                        if ( file_exists( WP_CONTENT_DIR . '/uploads/' . $theme . '/jquery-ui.theme.css' ) ) {
                            wp_register_style(
                                'tag-groups-css-frontend-theme',
                                get_bloginfo( 'wpurl' ) . '/wp-content/uploads/' . $theme . '/jquery-ui.theme.css',
                                array(),
                                TAG_GROUPS_VERSION
                            );
                        } else {
                            /*
                             * Fallback: Is this a custom theme of an old version?
                             */
                            try {
                                $dh = opendir( WP_CONTENT_DIR . '/uploads/' . $theme );
                            } catch ( ErrorException $e ) {
                                error_log( '[Tag Groups] Error searching ' . WP_CONTENT_DIR . '/uploads/' . $theme );
                            }
                            if ( $dh ) {
                                while ( false !== ($filename = readdir( $dh )) ) {
                                    
                                    if ( preg_match( "/jquery-ui-\\d+\\.\\d+\\.\\d+\\.custom\\.(min\\.)?css/i", $filename ) ) {
                                        wp_register_style(
                                            'tag-groups-css-frontend-theme',
                                            get_bloginfo( 'wpurl' ) . '/wp-content/uploads/' . $theme . '/' . $filename,
                                            array(),
                                            TAG_GROUPS_VERSION
                                        );
                                        break;
                                    }
                                
                                }
                            }
                        }
                    
                    }
                
                }
                
                wp_enqueue_style( 'tag-groups-css-frontend-structure' );
                wp_enqueue_style( 'tag-groups-css-frontend-theme' );
                
                if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                    wp_register_style(
                        'tag-groups-css-frontend',
                        TAG_GROUPS_PLUGIN_URL . '/assets/css/frontend.css',
                        array(),
                        TAG_GROUPS_VERSION
                    );
                } else {
                    wp_register_style(
                        'tag-groups-css-frontend',
                        TAG_GROUPS_PLUGIN_URL . '/assets/css/frontend.min.css',
                        array(),
                        TAG_GROUPS_VERSION
                    );
                }
                
                wp_enqueue_style( 'tag-groups-css-frontend' );
            }
        
        }
        
        /**
         * Loads text domain for internationalization
         */
        public function register_textdomain()
        {
            load_plugin_textdomain( 'tag-groups', false, TAG_GROUPS_PLUGIN_RELATIVE_PATH . '/languages/' );
        }
        
        /**
         * registers the CRON routines
         *
         * @param void
         * @return object $this
         */
        public function register_CRON()
        {
            // CRON independent from admin or frontend
            TagGroups_Cron::register_identifiers();
            TagGroups_Cron::schedule_regular( 'hourly', 'tag_groups_check_tag_migration' );
            return $this;
        }
    
    }
}