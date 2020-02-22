<?php

/**
* Tag Groups
*
* @package     Tag Groups
* @author      Christoph Amthor
* @copyright   2018 Christoph Amthor (@ Chatty Mango, chattymango.com)
* @license     GPL-3.0+
*
*/
if ( !class_exists( 'TagGroups_Terms' ) ) {
    class TagGroups_Terms
    {
        /**
         * Get all term IDs that match a group
         *
         * @since 1.23.0
         * @param integer $group_id
         * @return array
         */
        public function get_all_term_ids_no_meta( $group_id = 0 )
        {
            global  $wpdb ;
            $term_ids = array();
            $group_id = (int) $group_id;
            $terms_matching_group = $wpdb->get_results( "SELECT term_id FROM {$wpdb->prefix}terms WHERE term_group = {$group_id}", ARRAY_A );
            foreach ( $terms_matching_group as $term_matching_group ) {
                $term_ids[] = (int) $term_matching_group['term_id'];
            }
            return array_values( $term_ids );
        }
        
        /**
         * Converts all WP-native term_group attributes to the term meta format, if no term meta format was found.
         * Term meta must use _cm_term_group_array as key.
         *
         * @param void
         * @return int number of processed items
         */
        public function convert_to_term_meta( $count_only = false, $offset = null, $length = null )
        {
            
            if ( $count_only ) {
                // In case we restarted the tasks, we need to get fresh data
                $terms = false;
            } else {
                $terms = get_transient( 'tag_groups_convert_to_term_meta_terms' );
            }
            
            
            if ( $terms === false ) {
                /**
                 * Process only those that don't have any meta
                 */
                $args = array(
                    'hide_empty' => false,
                    'taxoomy'    => TagGroups_Taxonomy::get_enabled_taxonomies(),
                    'meta_query' => array( array(
                    'key'     => '_cm_term_group_array',
                    'compare' => 'NOT EXISTS',
                ) ),
                );
                $terms = get_terms( $args );
                // Try to keep for 10 minutes so that our offset always starts with unprocessed items.
                set_transient( 'tag_groups_convert_to_term_meta_terms', $terms, 600 );
            }
            
            if ( $count_only ) {
                
                if ( is_array( $terms ) ) {
                    return count( $terms );
                } else {
                    return 0;
                }
            
            }
            if ( isset( $offset ) && isset( $length ) ) {
                $terms = array_slice( $terms, $offset, $length );
            }
            $count = 0;
            if ( is_array( $terms ) ) {
                foreach ( $terms as $term ) {
                    /**
                     *  Fast way of saving: not necessary to use method save()
                     */
                    $result = update_term_meta( $term->term_id, '_cm_term_group_array', ',' . $term->term_group . ',' );
                    if ( is_int( $result ) || $result === true ) {
                        $count++;
                    }
                }
            }
            return $count;
        }
        
        /**
         * Recommend to run the migration
         *
         * @since 1.24.0
         * @param void
         * @return void
         */
        public static function recommend_to_run_migration()
        {
            global  $tag_groups_premium_fs_sdk ;
            TagGroups_Admin_Notice::add( 'info', sprintf( __( 'Please <a %s>click here to run the migration routines</a> to make sure we have migrated all tags.', 'tag-groups' ), 'href="' . admin_url( 'admin.php?page=tag-groups-settings-troubleshooting&process-tasks=migratetermmeta' ) . '"' ) );
        }
        
        /**
         * Check if we need to run the migration
         *
         * @since 1.24.0
         * @param void
         * @return void
         */
        public static function check_if_we_need_to_run_migration()
        {
            global  $tag_group_terms ;
            if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                error_log( '[Tag Groups] Checking if we should migrate terms.' );
            }
            $count = $tag_group_terms->convert_to_term_meta( true );
            
            if ( $count ) {
                if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                    error_log( sprintf( '[Tag Groups] %d terms should be migrated.', $count ) );
                }
                // $tag_group_terms->convert_to_term_meta();
                wp_schedule_single_event( time() + 2, 'tag_groups_run_term_migration' );
                if ( $count > 1000 ) {
                    // If there's a lot to do, we also want to show the admin notice
                    self::recommend_to_run_migration();
                }
            }
        
        }
        
        /**
         * Removes non-existing groups from term meta
         *
         * @param boolean $count_only
         * @param integer $offset
         * @param integer $length
         * @return integer
         */
        public function remove_missing_groups( $count_only = false, $offset = null, $length = null )
        {
            global  $tag_group_groups ;
            $group_ids = $tag_group_groups->get_group_ids();
            $count = 0;
            
            if ( $count_only ) {
                // In case we restarted the tasks, we need to get fresh data
                $term_ids = false;
            } else {
                $term_ids = get_transient( 'tag_groups_remove_missing_groups' );
            }
            
            
            if ( $term_ids === false ) {
                $args = array(
                    'hide_empty' => false,
                    'taxonomy'   => TagGroups_Taxonomy::get_enabled_taxonomies(),
                    'fields'     => 'ids',
                    'meta_query' => array( array(
                    'key'     => '_cm_term_group_array',
                    'compare' => 'EXISTS',
                ) ),
                );
                $term_ids = get_terms( $args );
                // Try to keep for 10 minutes so that our offset always starts with unprocessed items.
                set_transient( 'tag_groups_remove_missing_groups', $term_ids, 600 );
            }
            
            if ( $count_only ) {
                
                if ( is_array( $term_ids ) ) {
                    return count( $term_ids );
                } else {
                    return 0;
                }
            
            }
            if ( isset( $offset ) && isset( $length ) ) {
                $term_ids = array_slice( $term_ids, $offset, $length );
            }
            if ( is_array( $term_ids ) ) {
                foreach ( $term_ids as $term_id ) {
                    $changed = false;
                    $groups = get_term_meta( $term_id, '_cm_term_group_array', true );
                    
                    if ( ',,' == $groups ) {
                        // fixing results of bug in version <= 1.23.0
                        $groups_a = array( 0 );
                        $changed = true;
                    } else {
                        $groups_a = explode( ',', $groups );
                        // remove empty values
                        $groups_a = array_filter( $groups_a, function ( $v ) {
                            return $v != '';
                        } );
                        foreach ( $groups_a as $key => $group ) {
                            
                            if ( !in_array( $group, $group_ids ) ) {
                                // This group id doesn't exist -> we remove it from the term meta
                                unset( $groups_a[$key] );
                                $changed = true;
                            }
                        
                        }
                    }
                    
                    
                    if ( $changed ) {
                        if ( count( $groups_a ) == 0 ) {
                            $groups_a = array( 0 );
                        }
                        // We need to update the term meta.
                        update_term_meta( $term_id, '_cm_term_group_array', ',' . implode( ',', $groups_a ) . ',' );
                        $count++;
                    }
                
                }
            }
            return $count;
        }
        
        /**
         * Deletes the transients that are relevant for terms and groups
         *
         * To be called only if general purging of cache is needed. Otherwise use hooks.
         *
         * @param int $rebuild_in_seconds Turned off if less than 0.
         * @return void
         */
        public function clear_term_cache( $rebuild_in_seconds = 10 )
        {
            $languages = apply_filters( 'wpml_active_languages', NULL, '' );
            
            if ( !empty($languages) ) {
                foreach ( $languages as $language_code => $language_info ) {
                    delete_transient( 'tag_groups_post_counts-' . $language_code );
                    delete_transient( 'tag_groups_group_terms-' . $language_code );
                }
            } else {
                delete_transient( 'tag_groups_post_counts' );
                delete_transient( 'tag_groups_group_terms' );
            }
            
            delete_transient( 'tag_groups_post_terms' );
            delete_transient( 'tag_groups_post_types' );
            delete_transient( 'tag_groups_post_ids_groups' );
            if ( $rebuild_in_seconds < 0 ) {
                return;
            }
            if ( !defined( 'TAG_GROUPS_DISABLE_CACHE_REBUILD' ) || TAG_GROUPS_DISABLE_CACHE_REBUILD ) {
                if ( class_exists( 'TagGroups_Premium_Cron' ) ) {
                    // schedule rebuild of cache
                    TagGroups_Premium_Cron::schedule_in_secs( $rebuild_in_seconds, 'tag_groups_rebuild_post_counts' );
                }
            }
        }
    
    }
}