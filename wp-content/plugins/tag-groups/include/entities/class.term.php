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
if ( !class_exists( 'TagGroups_Term' ) ) {
    class TagGroups_Term
    {
        /**
         * identificator
         *
         * @var int
         */
        private  $term_id ;
        /**
         * taxonomy, needed for updating
         *
         * @var int
         */
        private  $taxonomy ;
        /**
         * name, needed for metabox and dynamic post filter
         *
         * @var string
         */
        private  $name ;
        /**
         * array of groups that this term is a member of
         *
         * @var array
         */
        private  $groups ;
        /**
         * slug of the term
         *
         * @var array
         */
        private  $slug ;
        /**
         * Constructor
         *
         * @param int|object $term term
         * @return object $this|boolean false if error occured during loading
         *
         */
        public function __construct( $term = null, $tg_terms = null )
        {
            
            if ( isset( $term ) ) {
                
                if ( is_object( $term ) ) {
                    /**
                     * We can fill the properties directly from the WP term object.
                     */
                    $this->term_id = $term->term_id;
                    $this->taxonomy = $term->taxonomy;
                    $this->name = $term->name;
                    $this->slug = $term->slug;
                    $this->groups = array( $term->term_group );
                } else {
                    $this->term_id = $term;
                    $this->groups = array();
                }
                
                return $this->load();
            }
            
            return $this;
        }
        
        /**
         * Loads relevant data from the database
         *
         * @param void
         * @return object|boolean $this or false on error
         */
        public function load()
        {
            global  $tag_groups_premium_fs_sdk ;
            if ( empty($this->term_id) ) {
                return false;
            }
            
            if ( empty($this->groups) || empty($this->taxonomy) || empty($this->name) || empty($this->slug) ) {
                /**
                 * We need to fill the properties from the WP term object.
                 */
                /**
                 * Some plugins hook into get_term but forget to forward term_group
                 */
                remove_all_filters( 'get_term' );
                if ( !empty($this->taxonomy) ) {
                    remove_all_filters( 'get_' . $this->taxonomy );
                }
                $term = get_term( $this->term_id );
                /**
                 * Check if term exists.
                 */
                
                if ( is_object( $term ) ) {
                    $this->taxonomy = $term->taxonomy;
                    $this->name = $term->name;
                    $this->slug = $term->slug;
                    $this->groups = array( $term->term_group );
                }
            
            }
            
            $groups = get_term_meta( $this->term_id, '_cm_term_group_array', true );
            
            if ( '' == $groups ) {
                // not found
                $this->groups = array( 0 );
            } else {
                $groups_a = explode( ',', $groups );
                // remove empty values
                $groups_a = array_filter( $groups_a, function ( $v ) {
                    return $v != '';
                } );
                // must be ints and no funny keys
                $groups_a = array_values( array_map( 'intval', $groups_a ) );
                // We return full array even for free plugin, because user might have downgraded after creating multiple groups
                $this->groups = $groups_a;
                return $this;
            }
            
            return $this;
        }
        
        /**
         * Save group-relevant data to the database (We are not saving the name)
         *
         * @param void
         * @return object $this|boolean false in case of error
         */
        public function save()
        {
            global  $tag_groups_premium_fs_sdk ;
            if ( empty($this->term_id) ) {
                return false;
            }
            /**
             * Check permissions
             */
            $tag_group_role_edit_tags = 'manage_options';
            if ( $tag_groups_premium_fs_sdk->is_plan_or_trial( 'premium' ) ) {
            }
            if ( !current_user_can( $tag_group_role_edit_tags ) ) {
                return false;
            }
            /**
             * Remove the "not assigned" element - usually with term_group 0
             * Use a copy of $this->groups.
             */
            $term_groups = $this->groups;
            
            if ( count( $term_groups ) > 1 ) {
                $index_not_assigned = array_search( 0, $term_groups );
                if ( $index_not_assigned !== false ) {
                    unset( $term_groups[$index_not_assigned] );
                }
            }
            
            $first_group = TagGroups_Base::get_first_element( $term_groups );
            update_term_meta( $this->term_id, '_cm_term_group_array', ',' . $first_group . ',' );
            /**
             * save also first element to field term_group of term, for backwards compatibility (downgrading from premium to base)
             */
            $args = array(
                'term_group' => $first_group,
            );
            wp_update_term( $this->term_id, $this->taxonomy, $args );
            do_action( 'groups_of_term_saved', $this->term_id, $term_groups );
            return $this;
        }
        
        /**
         * Checks if this term is assigned to at least one of these groups
         *
         *  @param int|object|array $group (int, object) or groups (array of int)
         *  @return boolean
         */
        public function is_in_group( $group )
        {
            
            if ( 0 === $group ) {
                
                if ( empty($this->groups) || array_values( $this->groups ) == array( 0 ) ) {
                    return true;
                } else {
                    return false;
                }
            
            } else {
                $term_groups = $this->make_array( $group );
                
                if ( count( array_intersect( $this->groups, $term_groups ) ) ) {
                    return true;
                } else {
                    return false;
                }
            
            }
        
        }
        
        /**
         * Checks if this term is assigned to all of these groups
         *
         *  @param int|object|array $group (int, object) or groups (array of int)
         *  @return boolean
         */
        public function has_all_groups( $group )
        {
            
            if ( 0 === $group ) {
                
                if ( empty($this->groups) || array_values( $this->groups ) == array( 0 ) ) {
                    return true;
                } else {
                    return false;
                }
            
            } else {
                $term_groups = $this->make_array( $group );
                // find out which of the submitted groups are not among this term's groups
                
                if ( count( array_diff( $term_groups, $this->groups ) ) ) {
                    return false;
                } else {
                    return true;
                }
            
            }
        
        }
        
        /**
         * Checks if this term is assigned to exactly these groups
         *
         *  @param int|object|array $group (int, object) or groups (array of int)
         *  @return boolean
         */
        public function has_exactly_groups( $group )
        {
            
            if ( 0 === $group ) {
                
                if ( empty($this->groups) || array_values( $this->groups ) == array( 0 ) ) {
                    return true;
                } else {
                    return false;
                }
            
            } else {
                $term_groups = $this->make_array( $group );
                // find out which of the submitted groups are not among this term's groups
                
                if ( count( array_diff( $term_groups, $this->groups ) ) || count( array_diff( $this->groups, $term_groups ) ) ) {
                    return false;
                } else {
                    return true;
                }
            
            }
        
        }
        
        /**
         * Getter for $this->groups (values cast to integer)
         *
         *  @param
         *  @return
         */
        public function get_groups()
        {
            
            if ( is_array( $this->groups ) ) {
                return array_values( array_map( 'intval', $this->groups ) );
            } else {
                return (int) $this->groups;
            }
        
        }
        
        /**
         * Setter for $this->groups
         *
         *  @param int|object|array $group (int, object) or groups (array of int)
         *  @return object $this
         */
        public function set_group( $group )
        {
            $this->groups = $this->make_array( $group );
            return $this;
        }
        
        /**
         * Adds one or more groups to $this->groups
         *
         *  @param int|object|array $group (int, object) or groups (array of int)
         *  @return object|boolean $this|false
         */
        public function add_group( $group )
        {
            /**
             * Important: New group(s) must come first so that it will be saved in base plugin
             */
            $this->groups = array_merge( $this->make_array( $group ), $this->groups );
            return $this;
        }
        
        /**
         * Remove a group from $this->groups
         *
         *  @param int|object|array $group (int, object) or groups (array of int)
         *  @return object|boolean $this|false
         */
        public function remove_group( $group )
        {
            $this->groups = array_diff( $this->groups, $this->make_array( $group ) );
            if ( count( $this->groups ) == 0 ) {
                $this->groups = array( 0 );
            }
            return $this;
        }
        
        /**
         * Remove all groups from $this->groups
         *
         *  @param
         *  @return
         */
        public function remove_all_groups()
        {
            $this->groups = array( 0 );
            return $this;
        }
        
        /**
         * returns the term's name
         *
         * @param void
         * @return string
         */
        public function get_name()
        {
            return $this->name;
        }
        
        /**
         * returns the term's taxonomy
         *
         * @param void
         * @return string
         */
        public function get_taxonomy()
        {
            return $this->taxonomy;
        }
        
        /**
         * returns the term's slug
         *
         * @param void
         * @return string
         */
        public function get_slug()
        {
            return $this->slug;
        }
        
        /**
         * Makes an array of group ids from an object, an integer or an array
         * includes sanitation
         *
         * @param object|array|integer
         * @return array one-dimensional array of integers (term_group values)
         */
        private function make_array( $group )
        {
            
            if ( is_object( $group ) ) {
                return array( intval( $group->get_group_id() ) );
            } elseif ( is_array( $group ) ) {
                return array_map( 'intval', $group );
            } else {
                return array( intval( $group ) );
            }
        
        }
    
    }
}