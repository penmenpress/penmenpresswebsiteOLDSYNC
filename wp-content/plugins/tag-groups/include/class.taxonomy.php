<?php
/**
* Tag Groups
*
* @package     Tag Groups
* @author      Christoph Amthor
* @copyright   2018 Christoph Amthor (@ Chatty Mango, chattymango.com)
* @license     GPL-3.0+
* @since       0.38
*
*/

if ( ! class_exists( 'TagGroups_Taxonomy' ) ) {

  class TagGroups_Taxonomy {



    /**
    * Constructor
    *
    *
    */
    public function __construct() {
    }


    /**
    * Removes taxonomy names that are not registered with this WP site as public
    *
    *
    * @param array $taxonomy_names
    * @return array
    */
    public static function remove_invalid( $taxonomy_names, $must_be_in_options = false )
    {

      $valid_taxonomy_names = self::get_public_taxonomies();

      return array_intersect( $taxonomy_names, $valid_taxonomy_names );

    }


    /**
    * Returns taxonomy names that are enabled in the options
    *
    *
    * @param array $merge_taxonomy_names Optional array of taxonomy names that needs to be intersected
    * @return array
    */
    public static function get_enabled_taxonomies( $intersect_taxonomy_names = null )
    {

      $tag_group_taxonomies = get_option( 'tag_group_taxonomy', array('post_tag') );

      $valid_taxonomy_names = self::get_public_taxonomies();

      if ( empty( $intersect_taxonomy_names ) ) {

        return array_intersect( $tag_group_taxonomies, $valid_taxonomy_names );

      } else {

        return array_intersect( $tag_group_taxonomies, $valid_taxonomy_names, $intersect_taxonomy_names );

      }

    }


    /**
    * Returns taxonomy names that are enabled in the options for the metabox
    *
    *
    * @param array $merge_taxonomy_names Optional array of taxonomy names that needs to be intersected
    * @return array
    */
    public static function get_metabox( $intersect_taxonomy_names = null )
    {

      $tag_group_taxonomies = get_option( 'tag_group_taxonomy', array('post_tag') );

      $tag_group_meta_box_taxonomies = get_option( 'tag_group_meta_box_taxonomy', array() );

      $valid_taxonomy_names = self::get_public_taxonomies();

      if ( empty( $intersect_taxonomy_names ) ) {

        return array_intersect( $tag_group_taxonomies, $valid_taxonomy_names, $tag_group_meta_box_taxonomies );

      } else {

        return array_intersect( $tag_group_taxonomies, $valid_taxonomy_names, $tag_group_meta_box_taxonomies, $intersect_taxonomy_names );

      }

    }


    /**
    * Returns taxonomy names that are registered with this WP site
    *
    *
    * @param void
    * @return array
    */
    public static function get_public_taxonomies()
    {

      return get_taxonomies( array( 'public' => true ), 'names' );

    }


    /**
    *   Retrieves post types from taxonomies
    */
    static function post_types_from_taxonomies( $taxonomy_names = array() ) {

      if ( ! is_array( $taxonomy_names ) ) {

        $taxonomy_names = array( $taxonomy_names );

      }

      asort( $taxonomy_names ); // avoid duplicate cache entries

      $key = md5( serialize( $taxonomy_names ) );

      $transient_value = get_transient( 'tag_groups_post_types' );

      if ( false === $transient_value  || ! isset( $transient_value[ $key ] ) ) {

        $post_types = array();

        foreach ( $taxonomy_names as $taxonomy ) {

          $post_type_a = array();

          if ( 'post_tag' == $taxonomy ) {

            $post_type_a = array( 'post' );

          } else {

            $taxonomy_o = get_taxonomy( $taxonomy );

            /**
            * The return value of get_taxonomy can be false
            */
            if ( ! empty( $taxonomy_o )) {

              $post_type_a = $taxonomy_o->object_type;

            }
          }

          if ( ! empty( $post_type_a )) {

            foreach ( $post_type_a as $post_type ) {

              if ( ! in_array( $post_type, $post_types ) ) {

                $post_types[] = $post_type;

              }
            }
          }
        }

        if ( ! is_array( $transient_value ) ) {

          $transient_value = array();

        }

        $transient_value[ $key ] = $post_types;

        // Limit lifetime, since base plugin does not have a function to manually clear the cache
        set_transient( 'tag_groups_post_types', $transient_value, 6 * HOUR_IN_SECONDS );

        return $post_types;

      } else {

        return $transient_value[ $key ];

      }

    }


  }
}
