<?php
/*
Plugin Name: Tag Groups
Plugin URI: https://chattymango.com/tag-groups/
Description: Assign tags to groups and display them in a tabbed tag cloud
Author: Chatty Mango
Author URI: https://chattymango.com/about/
Version: 0.37.0
License: GNU GENERAL PUBLIC LICENSE, Version 3
Text Domain: tag-groups
Domain Path: /languages
*/

// Don't call this file directly
if ( ! defined( 'ABSPATH') ) {

  die;

}

/**
* The required minimum version of WordPress.
*/
define( "TAG_GROUPS_MINIMUM_VERSION_WP", "4.0" );

/**
* Comma-separated list of default themes that come bundled with this plugin.
*/
define( "TAG_GROUPS_BUILT_IN_THEMES", "ui-gray,ui-lightness,ui-darkness" );

/**
* The theme that is selected by default. Must be among TAG_GROUPS_BUILT_IN_THEMES.
*/
define( "TAG_GROUPS_STANDARD_THEME", "ui-gray" );

/**
* The default number of groups on one page on the edit group screen.
*/
define( "TAG_GROUPS_ITEMS_PER_PAGE", 20 );

/**
* This plugin's last piece of the path, i.e. basically the plugin's name
*/
define( "TAG_GROUPS_PLUGIN_RELATIVE_PATH", basename( dirname( __FILE__ ) ) );

/**
* This plugin's absolute path on this server - starting from root.
*/
define( "TAG_GROUPS_PLUGIN_ABSOLUTE_PATH", dirname( __FILE__ ) );

/**
* The plugin's relative path (starting below the plugin directory), including the name of this file.
*/
define ( "TAG_GROUPS_PLUGIN_BASENAME", plugin_basename( __FILE__ ) );

/**
* The assumed name of the premium plugin, should we need it.
*/
define ( "TAG_GROUPS_PREMIUM_PLUGIN_PATH", WP_PLUGIN_DIR . '/tag-groups-premium' );

/**
* The full URL (including protocol) of the RSS channel that informas about updates.
*/
define ( "TAG_GROUPS_UPDATES_RSS_URL", "https://chattymango.com/category/updates/tag-groups-base/feed/");




require_once( TAG_GROUPS_PLUGIN_ABSOLUTE_PATH . '/include/class.base.php' );

require_once( TAG_GROUPS_PLUGIN_ABSOLUTE_PATH . '/include/class.admin.php' );

require_once( TAG_GROUPS_PLUGIN_ABSOLUTE_PATH . '/include/class.shortcode.php' );

require_once( TAG_GROUPS_PLUGIN_ABSOLUTE_PATH . '/include/class.feed.php' );

require_once( TAG_GROUPS_PLUGIN_ABSOLUTE_PATH . '/include/class.cache.php' );

require_once( TAG_GROUPS_PLUGIN_ABSOLUTE_PATH . '/include/class.options.php' );

require_once( TAG_GROUPS_PLUGIN_ABSOLUTE_PATH . '/include/class.group.php' );

require_once( TAG_GROUPS_PLUGIN_ABSOLUTE_PATH . '/include/class.term.php' );

require_once( TAG_GROUPS_PLUGIN_ABSOLUTE_PATH . '/include/class.rest_api.php' );


/**
* Do all initial stuff: register hooks, check dependencies
*
*
* @param void
* @return void
*/
function tag_groups_init() {

  global $tagGroups_Base_instance;

  // URL must be defined after WP has finished loading its settings
  define ( "TAG_GROUPS_PLUGIN_URL", plugins_url( '', __FILE__ ) );

  // start all initializations, registration of hooks, housekeeping, menus, ...
  $tagGroups_Base_instance = new TagGroups_Base();

}
add_action( 'plugins_loaded', 'tag_groups_init');

register_activation_hook( __FILE__, array( 'TagGroups_Base', 'on_activation' ) );


/**
*
* Wrapper for the static method
*
* @param array $atts
* @param bool $return_array
* @return string
*/
function tag_groups_cloud( $atts = array(), $return_array = false ) {

  return TagGroups_Shortcode::tag_groups_cloud( $atts, $return_array );

}

/**
*
* Wrapper for the static method
*
* @param array $atts
* @return string
*/
function tag_groups_accordion( $atts = array() ) {

  return TagGroups_Shortcode::tag_groups_accordion( $atts );

}


if ( !function_exists( 'post_in_tag_group' ) ) {
  /**
  * Checks if the post with $post_id has a tag that is in the tag group with $tag_group_id.
  *
  * @param int $post_id
  * @param int $tag_group_id
  * @return boolean
  */
  function post_in_tag_group( $post_id, $tag_group_id )
  {

    if ( class_exists( 'TagGroups_Premium_Post' ) ) {

      $post = new TagGroups_Premium_Post( $post_id );

      if ( method_exists( $post, 'has_group' ) ) {

        return $post->has_group( $tag_group_id );

      } else {

        return 'not implemented';

      }

    } else {

      $tag_group_taxonomy = get_option( 'tag_group_taxonomy', array('post_tag') );

      $tags = get_the_terms( $post_id, $tag_group_taxonomy );

      if ( $tags ) {

        foreach ( $tags as $tag ) {

          if ( $tag->term_group == $tag_group_id ) {
            return true;
          }
        }
      }

      return false;
    }

  }
}

/**
* guess what - the end
*/
