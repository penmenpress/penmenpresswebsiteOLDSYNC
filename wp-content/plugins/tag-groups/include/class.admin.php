<?php
/**
* @package     Tag Groups
* @author      Christoph Amthor
* @copyright   2018 Christoph Amthor (@ Chatty Mango, chattymango.com)
* @license     GPL-3.0+
*/

if ( ! class_exists('TagGroups_Admin') ) {

  class TagGroups_Admin {


    function __construct() {
    }


    /**
    * Initial settings after calling the plugin
    * Effective only for admin backend
    */
    static function admin_init() {

      $tag_group_taxonomy = get_option( 'tag_group_taxonomy', array('post_tag') );

      foreach ( $tag_group_taxonomy as $taxonomy ) {

        // creating and editing tags
        add_action( "{$taxonomy}_edit_form_fields", array( 'TagGroups_Admin', 'tag_input_metabox' ) );

        add_action( "{$taxonomy}_add_form_fields", array( 'TagGroups_Admin', 'create_new_tag' ) );

        // extra columns on tag page
        add_filter( "manage_edit-{$taxonomy}_columns", array( 'TagGroups_Admin', 'add_taxonomy_columns' ) );

        add_filter( "manage_{$taxonomy}_custom_column", array( 'TagGroups_Admin', 'add_taxonomy_column_content' ), 10, 3 );
      }

      //admin_head
      add_action( 'in_admin_header', array( 'TagGroups_Admin', 'settings_page_actions' ) );

      add_action( 'admin_notices', array('TagGroups_Base','admin_notice') );

      add_action( 'quick_edit_custom_box', array( 'TagGroups_Admin', 'quick_edit_tag' ), 10, 3 );

      add_action( 'create_term', array( 'TagGroups_Admin', 'update_edit_term_group' ) );

      add_action( 'edit_term', array( 'TagGroups_Admin', 'update_edit_term_group' ) );

      add_action( 'delete_term', array( 'TagGroups_Admin', 'update_post_meta' ) );

      add_action( 'term_groups_saved', array( 'TagGroups_Admin', 'update_post_meta' ) );

      add_action( 'load-edit-tags.php', array( 'TagGroups_Admin', 'bulk_action' ) );

      add_filter( "plugin_action_links_" . TAG_GROUPS_PLUGIN_BASENAME, array( 'TagGroups_Admin', 'add_plugin_settings_link' ) );

      add_action( 'admin_footer-edit-tags.php', array( 'TagGroups_Admin', 'quick_edit_javascript' ) );

      add_action( 'admin_footer-edit-tags.php', array( 'TagGroups_Admin', 'bulk_admin_footer' ) );

      add_filter( 'tag_row_actions', array( 'TagGroups_Admin', 'expand_quick_edit_link' ), 10, 2 );

      add_action( 'restrict_manage_posts', array( 'TagGroups_Admin', 'add_post_filter' ) );

      add_filter( 'parse_query', array( 'TagGroups_Admin', 'apply_post_filter' ) );

      // Ajax Handler
      add_action( 'wp_ajax_tg_ajax_manage_groups', array( 'TagGroups_Admin', 'ajax_manage_groups' ) );

      add_action( 'wp_ajax_tg_ajax_get_feed', array( 'TagGroups_Admin', 'ajax_get_feed' ) );

    }


    /**
    * Adds the submenus and the option page to the admin backend
    */
    static function register_menus()
    {

      // general settings
      if ( defined( 'TAG_GROUPS_PREMIUM_VERSION' ) ) {
        $title = 'Tag Groups Premium';
      } else {
        $title = 'Tag Groups';
      }
      add_options_page( $title, $title, 'manage_options', 'tag-groups-settings', array( 'TagGroups_Admin', 'settings_page' ) );

      // for each registered taxonomy a tag group admin page

      $tag_group_taxonomies = get_option( 'tag_group_taxonomy', array('post_tag') );

      if ( class_exists( 'TagGroups_Premium' ) ) {

        $tag_group_role_edit_groups = get_option( 'tag_group_role_edit_groups', 'edit_pages');

      } else {

        $tag_group_role_edit_groups = 'edit_pages';

      }

      $tag_group_post_types = TagGroups_Taxonomy::post_types_from_taxonomies( $tag_group_taxonomies );

      foreach ( $tag_group_post_types as $post_type ) {

        if ( 'post' == $post_type ) {

          $post_type_query = '';

        } else {

          $post_type_query = '?post_type=' . $post_type;

        }

        $submenu_page = add_submenu_page( 'edit.php' . $post_type_query, 'Tag Groups', 'Tag Groups', $tag_group_role_edit_groups, 'tag-groups_' . $post_type, array( 'TagGroups_Admin', 'group_administration' ) );

        if ( class_exists( 'TagGroups_Premium_Admin' ) && method_exists( 'TagGroups_Premium_Admin', 'add_screen_option' ) ) {

          add_action( "load-$submenu_page", array( 'TagGroups_Premium_Admin', 'add_screen_option' ) );

        }

      }

    }


    /**
    *   Retrieves post types from taxonomies
    *   @DEPRECATED since 0.37; use TagGroups_Taxonomy::post_types_from_taxonomies()
    */
    static function post_types_from_taxonomies( $taxonomies = array() ) {

      if ( ! is_array( $taxonomies ) ) {

        $taxonomies = array( $taxonomies );

      }

      asort( $taxonomies ); // avoid duplicate cache entries

      $key = md5( serialize( $taxonomies ) );

      $transient_value = get_transient( 'tag_groups_post_types' );

      if ( $transient_value === false || ! isset( $transient_value[ $key ] ) ) {

        $post_types = array();

        foreach ( $taxonomies as $taxonomy ) {

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


    /**
    * Create the html to add tags to tag groups on single tag view (after clicking tag for editing)
    * @param type $tag
    */
    static function tag_input_metabox( $tag )
    {
      $screen = get_current_screen();

      $group = new TagGroups_Group();

      if ( 'post' == $screen->post_type ) {

        $url_post_type = '';

      } else {

        $url_post_type = '&post_type=' . $screen->post_type;

      }

      $tag_group_admin_url = admin_url( 'edit.php?page=tag-groups_' . $screen->post_type . $url_post_type );

      $data = $group->get_all_with_position_as_key();

      unset( $data[0] );

      $term = new TagGroups_Term( $tag );

      ?>
      <tr class="form-field">
        <th scope="row" valign="top"><label for="tag_widget"><?php
        _e( 'Tag Groups', 'tag-groups' )
        ?></label></th>
        <td>
          <select id="term-group" name="term-group<?php if ( class_exists( 'TagGroups_Premium_Group' ) ) echo '[]' ?>"<?php if ( class_exists( 'TagGroups_Premium_Group' ) ) echo ' multiple' ?>>
            <?php if ( ! class_exists( 'TagGroups_Premium_Group' ) ) : ?>
              <option value="0" <?php
              if ( $term->is_in_group( 0 ) ) {
                echo 'selected';
              }
              ?> ><?php
              _e( 'not assigned', 'tag-groups' )
              ?></option>
              <?php
            endif;

            foreach ( $data as $term_group ) : ?>

            <option value="<?php echo $term_group[ 'term_group' ]; ?>"

              <?php
              if ( $term->is_in_group( $term_group[ 'term_group' ] ) ) {
                echo 'selected';
              }
              ?> ><?php echo htmlentities( $term_group[ 'label' ], ENT_QUOTES, "UTF-8" ); ?></option>

            <?php endforeach; ?>

          </select>
          <input type="hidden" name="tag-groups-nonce" id="tag-groups-nonce" value="<?php echo wp_create_nonce( 'tag-groups-nonce' )
          ?>" />
          <input type="hidden" name="tag-groups-taxonomy" id="tag-groups-taxonomy" value="<?php echo $screen->taxonomy; ?>" />

          <script>
          jQuery(document).ready(function () {
            jQuery('#term-group').SumoSelect({
              search: true,
              forceCustomRendering: true,
              <?php if ( class_exists( 'TagGroups_Premium_Group' ) ) : ?>
              triggerChangeCombined: true,
              selectAll: true,
              captionFormatAllSelected: '<?php _e( 'all {0} selected', 'tag-groups-premium' ) ?>',
              captionFormat: '<?php _e( '{0} selected', 'tag-groups-premium' ) ?>',
              <?php endif; ?>
            });
          });
          </script>
          <p>&nbsp;</p>
          <p><a href="<?php echo $tag_group_admin_url ?>"><?php
          _e( 'Edit tag groups', 'tag-groups' )
          ?></a>. (<?php
          _e( 'Clicking will leave this page without saving.', 'tag-groups' )
          ?>)</p>
        </td>
      </tr>
      <?php

    }


    /**
    * Create the html to assign tags to tag groups upon new tag creation (left of the table)
    * @param type $tag
    */
    static function create_new_tag( $tag )
    {

      $screen = get_current_screen();

      $group = new TagGroups_Group();

      $data = $group->get_all_with_position_as_key();

      unset( $data[0] );
      ?>

      <div class="form-field">
        <label for="term-group"><?php _e( 'Tag Groups', 'tag-groups' ) ?></label>

        <select id="term-group" name="term-group<?php if ( class_exists( 'TagGroups_Premium_Group' ) ) echo '[]' ?>"<?php if ( class_exists( 'TagGroups_Premium_Group' ) ) echo ' multiple' ?>>
          <?php if ( ! class_exists( 'TagGroups_Premium_Group' ) ) : ?>
            <option value="0" selected ><?php
            _e( 'not assigned', 'tag-groups' )
            ?></option>
          <?php endif;

          foreach ( $data as $term_group ) :
            ?>
            <option value="<?php echo $term_group['term_group']; ?>"><?php echo htmlentities( $term_group['label'], ENT_QUOTES, "UTF-8" ); ?></option>
          <?php endforeach; ?>
        </select>
        <script>
        jQuery(document).ready(function () {
          jQuery('#term-group').SumoSelect({
            search: true,
            forceCustomRendering: true,
            <?php if ( class_exists( 'TagGroups_Premium_Group' ) ) : ?>
            triggerChangeCombined: true,
            selectAll: true,
            captionFormatAllSelected: '<?php _e( 'all {0} selected', 'tag-groups-premium' ) ?>',
            captionFormat: '<?php _e( '{0} selected', 'tag-groups-premium' ) ?>',
            <?php endif; ?>
          });
        });
        </script>
        <input type="hidden" name="tag-groups-nonce" id="tag-groups-nonce" value="<?php echo wp_create_nonce( 'tag-groups-nonce' )
        ?>" />
        <input type="hidden" name="new-tag-created" id="new-tag-created" value="1" />
        <input type="hidden" name="tag-groups-taxonomy" id="tag-groups-taxonomy" value="<?php echo $screen->taxonomy; ?>" />
      </div>

      <?php

    }



    /**
    * adds a custom column to the table of tags/terms
    * thanks to http://coderrr.com/add-columns-to-a-taxonomy-terms-table/
    * @global object $wp
    * @param array $columns
    * @return string
    */
    static function add_taxonomy_columns( $columns )
    {

      global $wp;

      $new_order = (isset( $_GET['order'] ) && $_GET['order'] == 'asc' && isset( $_GET['orderby'] ) && $_GET['orderby'] == 'term_group') ? 'desc' : 'asc';

      $screen = get_current_screen();
      if ( ! empty( $screen )) {

        $taxonomy = $screen->taxonomy;


        $link = add_query_arg( array('orderby' => 'term_group', 'order' => $new_order, 'taxonomy' => $taxonomy), admin_url( "edit-tags.php" . $wp->request ) );

        $link_escaped = esc_url( $link );

        $columns['term_group'] = '<a href="' . $link_escaped . '"><span>' . __( 'Tag Group', 'tag-groups' ) . '</span><span class="sorting-indicator"></span></a>';

      }  else {

        $columns['term_group'] = '';

      }

      return $columns;

    }



    /**
    * adds data into custom column of the table for each row
    * thanks to http://coderrr.com/add-columns-to-a-taxonomy-terms-table/
    * @param type $a
    * @param type $b
    * @param type $term_id
    * @return string
    */
    static function add_taxonomy_column_content( $a = '', $b = '', $term_id = 0 )
    {

      if ( 'term_group' != $b ) {

        return $a;

      } // credits to Navarro (http://navarradas.com)

      if ( ! empty( $_REQUEST['taxonomy'] ) ) {

        $taxonomy = sanitize_title( $_REQUEST['taxonomy'] );

      } else {

        return '';
      }

      $term = get_term( $term_id, $taxonomy );

      $group = new TagGroups_Group();

      if ( isset( $term ) ) {

        $term_o = new TagGroups_Term( $term );

        return implode( ', ', $group->get_labels( $term_o->get_groups() ) ) ;

      } else {

        return '';

      }

    }


    /**
    *
    * processing actions defined in bulk_admin_footer()
    * credits http://www.foxrunsoftware.net
    * @global int $tg_update_edit_term_group_called
    * @return void
    */
    static function bulk_action()
    {

      global $tg_update_edit_term_group_called;

      $tag_group_taxonomy = get_option( 'tag_group_taxonomy', array('post_tag') );

      $screen = get_current_screen();

      $taxonomy = $screen->taxonomy;

      if ( is_object( $screen ) && ( !in_array( $taxonomy, $tag_group_taxonomy ) ) ) {

        return;

      }

      $show_filter_tags = get_option( 'tag_group_show_filter_tags', 1 );

      if ( $show_filter_tags ) {

        $tag_group_tags_filter = get_option( 'tag_group_tags_filter', array() );

        /*
        * Processing the filter
        * Values come as POST (via menu, precedence) or GET (via link from group admin)
        */
        if ( isset( $_POST['term-filter'] ) ) {

          $term_filter = (int) $_POST['term-filter'];

        } elseif ( isset( $_GET['term-filter'] ) ) {

          $term_filter = (int) $_GET['term-filter'];

          // We need to remove the term-filter piece, or it will stay forever
          $sendback = remove_query_arg( array( 'term-filter' ), $_SERVER['REQUEST_URI']);

        }

        if ( isset( $term_filter ) ) {

          if ( '-1' == $term_filter ) {

            unset( $tag_group_tags_filter[ $taxonomy ] );

            update_option( 'tag_group_tags_filter', $tag_group_tags_filter );

          } else {

            $tag_group_tags_filter[ $taxonomy ] = $term_filter;

            update_option( 'tag_group_tags_filter', $tag_group_tags_filter );

            /*
            * Modify the query
            */
            add_action( 'terms_clauses', array( 'TagGroups_Admin', 'terms_clauses' ), 10, 3 );

          }

          if ( isset( $sendback ) ) {

            // escaping $sendback
            wp_redirect( esc_url_raw( $sendback ) );

          }

        } else {

          /*
          * If filter is set, make sure to modify the query
          */
          if ( isset( $tag_group_tags_filter[ $taxonomy ] ) ) {

            add_action( 'terms_clauses', array( 'TagGroups_Admin', 'terms_clauses' ), 10, 3 );

          }
        }

      }

      $wp_list_table = _get_list_table( 'WP_Terms_List_Table' );

      $action = $wp_list_table->current_action();

      $allowed_actions = array( 'assign' );

      if ( ! in_array( $action, $allowed_actions ) ) {

        return;

      }

      if ( isset( $_REQUEST['delete_tags'] ) ) {

        $term_ids = $_REQUEST['delete_tags'];

      }

      if ( isset( $_REQUEST['term-group-top'] ) ) {

        $term_group = (int) $_REQUEST['term-group-top'];

      } else {

        return;

      }

      $sendback = remove_query_arg( array( 'assigned', 'deleted' ), wp_get_referer() );

      if ( !$sendback ) {

        $sendback = admin_url( 'edit-tags.php?taxonomy=' . $taxonomy );

      }

      if ( empty( $term_ids ) ) {

        $sendback = add_query_arg( array( 'number_assigned' => 0, 'group_id' => $term_group ), $sendback );

        $sendback = remove_query_arg( array( 'action', 'action2', 'tags_input', 'post_author', 'comment_status', 'ping_status', '_status', 'post', 'bulk_edit', 'post_view' ), $sendback );

        // escaping $sendback
        wp_redirect( esc_url_raw( $sendback ) );

        exit();
      }

      $pagenum = $wp_list_table->get_pagenum();

      $sendback = add_query_arg( 'paged', $pagenum, $sendback );

      $tg_update_edit_term_group_called = true; // skip update_edit_term_group()

      switch ( $action ) {
        case 'assign':

        $assigned = 0;

        foreach ( $term_ids as $term_id ) {

          $term = new TagGroups_Term( $term_id );

          if ( false !== $term ) {

            if ( 0 == $term_group ) {

              $term->remove_all_groups();

              $term->save();

            } else {

              $term->add_group( $term_group );

              $term->save();

            }

            $assigned++;

          }

        }

        if ( 0 == $term_group ) {

          $message = _n( 'The term has been removed from all groups.', sprintf( '%d terms have been removed from all groups.', number_format_i18n( (int) $assigned ) ), (int) $assigned, 'tag-groups' );

        } else {

          $group = new TagGroups_Group( $term_group );

          $message = _n( sprintf( 'The term has been assigned to the group %s.', '<i>' . $group->get_label() . '</i>' ), sprintf( '%d terms have been assigned to the group %s.', number_format_i18n( (int) $assigned ), '<i>' . $group->get_label() . '</i>' ), (int) $assigned, 'tag-groups' );
        }

        break;

        default:
        // Need to show a message?

        break;
      }

      update_option( 'tag_group_admin_notice', array(
        'type' => 'success',
        'content' => $message
      ) );

      $sendback = remove_query_arg( array('action', 'action2', 'tags_input', 'post_author', 'comment_status', 'ping_status', '_status', 'post', 'bulk_edit', 'post_view'), $sendback );

      wp_redirect( esc_url_raw( $sendback ) );

      exit();

    }


    /**
    * modifies Quick Edit link to call JS when clicked
    * thanks to http://shibashake.com/WordPress-theme/expand-the-WordPress-quick-edit-menu
    * @param array $actions
    * @param object $tag
    * @return array
    */
    static function expand_quick_edit_link( $actions, $tag )
    {

      $screen = get_current_screen();

      $tag_group_taxonomy = get_option( 'tag_group_taxonomy', array('post_tag') );

      if ( is_object( $screen ) && (!in_array( $screen->taxonomy, $tag_group_taxonomy ) ) ) {

        return $actions;

      }

      $term_o = new TagGroups_Term( $tag );

      $groups = htmlspecialchars( json_encode( $term_o->get_groups() ) );


      $nonce = wp_create_nonce( 'tag-groups-nonce' );

      $actions['inline hide-if-no-js'] = '<a href="#" class="editinline" title="';

      $actions['inline hide-if-no-js'] .= esc_attr( __( 'Edit this item inline', 'tag-groups' ) ) . '" ';

      $actions['inline hide-if-no-js'] .= " onclick=\"set_inline_tag_group_selected('{$groups}', '{$nonce}')\">";

      $actions['inline hide-if-no-js'] .= __( 'Quick&nbsp;Edit', 'tag-groups' );

      $actions['inline hide-if-no-js'] .= '</a>';

      return $actions;

    }


    /**
    * adds JS function that sets the saved tag group for a given element when it's opened in quick edit
    * thanks to http://shibashake.com/WordPress-theme/expand-the-WordPress-quick-edit-menu
    * @return void
    */
    static function quick_edit_javascript()
    {

      $screen = get_current_screen();

      $tag_group_taxonomy = get_option( 'tag_group_taxonomy', array('post_tag') );

      if ( ! in_array( $screen->taxonomy, $tag_group_taxonomy ) ) {

        return;
      }

      ?>
      <script type="text/javascript">
      <!--
      function set_inline_tag_group_selected(termGroupsSelectedJson, nonce) {
        var termGroupsSelected = JSON.parse(termGroupsSelectedJson);
        inlineEditTax.revert();
        var tagGroupsSelectElement = document.getElementById('term-group-option');
        var nonceInput = document.getElementById('tag-groups-option-nonce');
        nonceInput.value = nonce;
        for (i = 0; i < tagGroupsSelectElement.options.length; i++) {
          if (termGroupsSelected.indexOf(parseInt(tagGroupsSelectElement.options[i].value)) > -1) {
            tagGroupsSelectElement.options[i].setAttribute("selected", "selected");
          } else {
            tagGroupsSelectElement.options[i].removeAttribute("selected");
          }
          if (i + 1 == tagGroupsSelectElement.options.length) callSumoSelect();
        }
      }

      function callSumoSelect() {
        setTimeout(function() {
          jQuery('#term-group-option').SumoSelect({
            search: true,
            forceCustomRendering: true,
            <?php if ( class_exists( 'TagGroups_Premium_Group' ) ) : ?>
            selectAll: true,
            captionFormatAllSelected: '<?php _e( 'all {0} selected', 'tag-groups-premium' ) ?>',
            captionFormat: '<?php _e( '{0} selected', 'tag-groups-premium' ) ?>',
            <?php endif; ?>
          });
        }, 50);
      }


      //-->
      </script>
      <?php

    }


    /**
    * Create the html to assign tags to tag groups directly in tag table ('quick edit')
    * @return type
    */
    static function quick_edit_tag()
    {

      global $tg_quick_edit_tag_called;

      if ( $tg_quick_edit_tag_called ) {

        return;

      }

      $tg_quick_edit_tag_called = true;

      $screen = get_current_screen();

      $tag_group_taxonomy = get_option( 'tag_group_taxonomy', array('post_tag') );

      if ( !in_array( $screen->taxonomy, $tag_group_taxonomy ) ) {

        return;

      }

      $group = new TagGroups_Group();

      $data = $group->get_all_with_position_as_key();

      unset( $data[0] );
      ?>

      <fieldset><div class="inline-edit-col">

        <label><span class="title"><?php
        _e( 'Groups', 'tag-groups' )
        ?></span><span class="input-text-wrap">

          <select id="term-group-option" name="term-group<?php if ( class_exists( 'TagGroups_Premium_Group' ) ) echo '[]' ?>" class="ptitle"<?php if ( class_exists( 'TagGroups_Premium_Group' ) ) echo ' multiple' ?>>
            <?php if ( ! class_exists( 'TagGroups_Premium_Group' ) ) : ?>
              <option value="0" ><?php
              _e( 'not assigned', 'tag-groups' )
              ?></option>
            <?php endif;

            foreach ( $data as $term_group ) :
              ?>

              <option value="<?php echo $term_group['term_group']; ?>" ><?php echo htmlentities( $term_group['label'], ENT_QUOTES, "UTF-8" ); ?></option>

            <?php endforeach; ?>
          </select>

          <?php // id must be "tag-groups-option-nonce" because otherwise identical with "Add New Tag" form on the left side. ?>
          <input type="hidden" name="tag-groups-nonce" id="tag-groups-option-nonce" value="" />

          <input type="hidden" name="tag-groups-taxonomy" id="tag-groups-taxonomy" value="<?php echo $screen->taxonomy; ?>" />

        </span></label>

      </div></fieldset>
      <?php

    }


    /**
    * Updates the post meta
    *
    *
    * @param type var Description
    * @return return type
    */
    public static function update_post_meta( $term_id, $term_group = -1 )
    {

      /**
      * update the post meta
      */
      if ( class_exists( 'TagGroups_Premium_Post' ) ) {

        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {

          error_log( 'Tag Groups Premium: Checking if posts need to be migrated.' );

          $start_time = microtime( true );

        }

        $count = TagGroups_Premium_Post::update_post_meta_for_term( $term_id, $term_group );

        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {

          error_log( sprintf( 'Tag Groups Premium: Meta of %d post(s) updated in %d milliseconds.', $count, round( ( microtime( true ) - $start_time ) * 1000 ) ) );

        }

      }

    }


    /**
    * Get the $_POSTed value after saving a tag/term and save it in the table
    *
    * @global int $tg_update_edit_term_group_called
    * @param type $term_id
    * @return type
    */
    public static function update_edit_term_group( $term_id )
    {

      // next lines to prevent infinite loops when the hook edit_term is called again from the function wp_update_term
      global $tg_update_edit_term_group_called;

      if ( $tg_update_edit_term_group_called ) {

        return;

      }

      $screen = get_current_screen();


      // $_POST['term-group'] won't be submitted if multi select is empty
      if ( !isset( $_POST['term-group'] ) && empty( $_POST['tag-groups-nonce'] ) ) {

        return;

      }

      $tag_group_taxonomy = get_option( 'tag_group_taxonomy', array('post_tag') );

      if ( is_object( $screen ) && (!in_array( $screen->taxonomy, $tag_group_taxonomy ) ) && (!isset( $_POST['new-tag-created'] )) ) {

        return;

      }

      $tg_update_edit_term_group_called = true;

      if ( ! isset( $_POST['tag-groups-nonce'] ) || ! wp_verify_nonce( $_POST['tag-groups-nonce'], 'tag-groups-nonce' ) ) {

        die( "Security check" );
      }

      $term_id = (int) $term_id;

      $term = new TagGroups_Term( $term_id );


      if ( isset( $_POST['term-group'] ) ) {

        if ( is_array( $_POST['term-group'] ) ) {

          $term_group = array_map( 'intval', $_POST['term-group'] );

        } else {

          $term_group = (int) $_POST['term-group'];

        }

        // $_POSTed value can be string or array of strings
        $term->set_group( $term_group );

        $term->save();

        /**
        * update the post meta, if required
        */
        // TODO now done by hook
        // self::update_post_meta( $term_id, $term_group );

      } else {

        $term->set_group( 0 );

        $term->save();

        /**
        * update the post meta
        */
        if ( class_exists( 'TagGroups_Premium_Post' ) ) {

          $count = TagGroups_Premium_Post::update_post_meta_for_term( $term_id, 0 );

        }

      }

      if ( isset( $_POST['name'] ) && ( $_POST['name'] != '' ) ) { // allow zeros

        $args['name'] = stripslashes( sanitize_text_field( $_POST['name'] ) );

      }

      if ( isset( $_POST['slug'] ) ) { // allow empty values

        $args['slug'] = sanitize_title( $_POST['slug'] );

      }

      if ( isset( $_POST['description'] ) ) { // allow empty values

        if ( get_option( 'tag_group_html_description', 0 ) ) {

          $args['description'] = $_POST['description'];

        } else {

          $args['description'] = stripslashes( sanitize_text_field( $_POST['description'] ) );

        }
      }

      if ( isset( $_POST['parent'] ) && ($_POST['parent'] != '') ) {

        $args['parent'] = (int) $_POST['parent'] ;

      }

      if ( isset( $_POST['tag-groups-taxonomy'] ) ) {

        $category = stripslashes( sanitize_title( $_POST['tag-groups-taxonomy'] ) );

        wp_update_term( $term_id, $category, $args );
      }

    }


    /**
    * Adds a bulk action menu to a term list page
    * credits http://www.foxrunsoftware.net
    * @return void
    */
    static function bulk_admin_footer()
    {

      $tag_group_taxonomy = get_option( 'tag_group_taxonomy', array('post_tag') );

      $screen = get_current_screen();

      if ( is_object( $screen ) && ( ! in_array( $screen->taxonomy, $tag_group_taxonomy ) ) ) {
        return;
      }

      $show_filter_tags = get_option( 'tag_group_show_filter_tags', 1 );

      $group = new TagGroups_Group();
      $data = $group->get_all_with_position_as_key();

      /*
      * 	constructing the action menu
      */
      ?>
      <script type="text/javascript">
      jQuery(document).ready(function () {
        jQuery('<option>').val('assign').text('<?php
        _e( 'Assign to', 'tag-groups' )
        ?>').appendTo("select[name='action']");
        jQuery('<option>').val('assign').text('<?php
        _e( 'Assign to', 'tag-groups' )
        ?>').appendTo("select[name='action2']");
        var sel_top = jQuery("<select name='term-group-top'>").insertAfter("select[name='action']");
        var sel_bottom = jQuery("<select name='term-group-bottom'>").insertAfter("select[name='action2']");
        <?php foreach ( $data as $term_group ) : ?>
        sel_top.append(jQuery("<option>").attr("value", "<?php echo $term_group['term_group'] ?>").text("<?php echo htmlentities( $term_group['label'], ENT_QUOTES, "UTF-8" )
        ?>"));
        sel_bottom.append(jQuery("<option>").attr("value", "<?php echo $term_group['term_group'] ?>").text("<?php echo htmlentities( $term_group['label'], ENT_QUOTES, "UTF-8" )
        ?>"));
        <?php endforeach; ?>

        <?php if ( isset( $_GET['orderby'] ) && $_GET['orderby'] == 'term_group' ) : ?>
        jQuery('th#term_group').addClass('sorted');
        <?php else: ?>
        jQuery('th#term_group').addClass('sortable');
        <?php endif; ?>
        <?php if ( isset( $_GET['order'] ) && $_GET['order'] == 'asc' ) : ?>
        jQuery('th#term_group').addClass('asc');
        <?php else: ?>
        jQuery('th#term_group').addClass('desc');
        <?php endif; ?>

        jQuery('[name="term-group-top"]').change(function () {
          jQuery('[name="action"]').val('assign');
          jQuery('[name="action2"]').val('assign');
          var selected = jQuery(this).val();
          jQuery('[name="term-group-bottom"]').val(selected);
        });
        jQuery('[name="term-group-bottom"]').change(function () {
          jQuery('[name="action"]').val('assign');
          jQuery('[name="action2"]').val('assign');
          var selected = jQuery(this).val();
          jQuery('[name="term-group-top"]').val(selected);
        });
        <?php
        /*
        * 	constructing the filter menu
        */
        if ( $show_filter_tags ) :

          $tag_group_tags_filter = get_option( 'tag_group_tags_filter', array() );

          if ( isset( $tag_group_tags_filter[ $screen->taxonomy ] ) ) {

            $tag_filter = $tag_group_tags_filter[ $screen->taxonomy ];

          } else {

            $tag_filter = -1;

          }

          ?>
          var sel_filter = jQuery("<select id='tag_filter' name='term-filter' style='margin-left: 20px;'>").insertAfter("select[name='term-group-top']");
          sel_filter.append(jQuery("<option>").attr("value", "-1").text("<?php
          _e( 'Filter off', 'tag-groups' )
          ?>"));
          <?php foreach ( $data as $term_group ) : ?>
          sel_filter.append(jQuery("<option>").attr("value", "<?php echo $term_group['term_group'] ?>").text("<?php echo htmlentities( $term_group['label'], ENT_QUOTES, "UTF-8" )?>"));
          <?php endforeach; ?>
          jQuery("#tag_filter option[value=<?php echo $tag_filter ?>]").prop('selected', true);
        });</script>
        <?php
      endif;

    }


    /**
    * Adds a pull-down menu to the filters above the posts.
    * Based on the code by Ohad Raz, http://wordpress.stackexchange.com/q/45436/2487
    * License: Creative Commons Share Alike
    * @return void
    */
    static function add_post_filter()
    {

      if ( ! get_option( 'tag_group_show_filter', 1 ) ) {

        return;

      }

      $tag_group_taxonomy = get_option( 'tag_group_taxonomy', array('post_tag') );


      $post_type = ( isset( $_GET['post_type'] ) ) ? sanitize_title( $_GET['post_type'] ) : 'post';

      if ( count( array_intersect( $tag_group_taxonomy, get_object_taxonomies( $post_type ) ) ) ) {

        $group = new TagGroups_Group();

        $data = $group->get_all_term_group_label();

        ?>
        <select name="tg_filter_posts_value">
          <option value=""><?php
          _e( 'Filter by tag group', 'tag-groups' ); ?></option>
          <?php
          $current_term_group = isset( $_GET['tg_filter_posts_value'] ) ? sanitize_text_field( $_GET['tg_filter_posts_value'] ) : '';

          foreach ( $data as $term_group => $label ) {
            printf( '<option value="%s"%s>%s</option>', $term_group, ( '' != $current_term_group && $term_group == $current_term_group ) ? ' selected="selected"' : '', htmlentities( $label, ENT_QUOTES, "UTF-8" ) );
          }
          ?>
        </select>
        <script>
        jQuery(document).ready(function(){
          jQuery('#cat').hide();
        });
        </script>
        <?php
      }

    }


    /**
    * Applies the filter, if used.
    * Based on the code by Ohad Raz, http://wordpress.stackexchange.com/q/45436/2487
    * License: Creative Commons Share Alike
    *
    * @global type $pagenow
    * @param type $query
    * @return type
    */
    static function apply_post_filter( $query )
    {

      global $pagenow;

      if ( $pagenow != 'edit.php' ) {

        return $query;

      }

      $show_filter_posts = get_option( 'tag_group_show_filter', 1 );

      if ( ! $show_filter_posts ) {

        return;

      }

      if ( isset( $_GET['post_type'] ) ) {

        $post_type = sanitize_title( $_GET['post_type'] );

      } else {

        $post_type = 'post';

      }

      /**
      * Losing here the filter by language from Polylang, but currently no other way to show any posts when combining tax_query and meta_query
      */
      unset( $query->query_vars['tax_query'] );


      $tg_taxonomy = get_option( 'tag_group_taxonomy', array('post_tag') );
      // note: removed restriction count( $tg_taxonomy ) <= 1 - rather let user figure out if the result works

      $taxonomy_intersect = array_intersect( $tg_taxonomy, get_object_taxonomies( $post_type ) );

      if ( count( $taxonomy_intersect ) && isset( $_GET['tg_filter_posts_value'] ) &&  $_GET['tg_filter_posts_value'] !== '' ) {

        if ( ! class_exists( 'TagGroups_Premium_Post' ) ) {
          // one tag group per tag

          $filter_terms = array( );
          $query->query_vars['tax_query'] = array(
            'relation' => 'OR'
          );

          $args = array(
            'taxonomy' => $taxonomy_intersect
          );

          $terms = get_terms( $args );

          if ( $terms ) {

            $selected_term_group = (int) $_GET['tg_filter_posts_value'];

            /**
            * Filtering for terms that are not assigned to group $selected_term_group
            * Add per taxonomy for future extensibility
            */
            foreach ( $terms as $term ) {

              if ( $term->term_group == $selected_term_group ) {

                $filter_terms[$term->taxonomy][] = $term->term_id;
              }

            }

            foreach ( $taxonomy_intersect as $taxonomy ) {

              /**
              * Add a dummy so that the taxonomy condition will not be ignored even if no applicable tags were found.
              */
              if ( ! isset( $filter_terms[$taxonomy] ) ) {
                $filter_terms[$taxonomy][] = 0;
              }

              $query->query_vars['tax_query'][] = array(
                'taxonomy'  => $taxonomy,
                'field'     => 'term_id',
                'terms'     => $filter_terms[$taxonomy],
                'compare'   => 'IN',
              );
            }

          }

        } else {
          // multiple tag groups per tag

          $query->query_vars['meta_query'] = TagGroups_Premium_Post::get_meta_query_group( (int) $_GET['tg_filter_posts_value'] );

        }

        /**
        * In case we use the Polylang plugin: get the terms for the language of that post.
        */
        if ( function_exists( 'pll_current_language' ) ) {

          /**
          * Better sanitize what we get from other plugins
          */
          $query->query_vars['lang'] = sanitize_text_field( pll_current_language( 'locale' ) );

        }

      }

      return $query;
    }


    /**
    * AJAX handler to get a feed
    */
    static function ajax_get_feed()
    {

      $response = new WP_Ajax_Response;

      if ( isset( $_REQUEST['url'] ) ) {
        $url = esc_url_raw( $_REQUEST['url'] );
      } else {
        $url = '';
      }

      if ( isset( $_REQUEST['amount'] ) ) {
        $amount = (int) $_REQUEST['amount'];
      } else {
        $amount = 5;
      }

      /**
      * Assuming that the posts URL is the $url minus the trailing /feed
      */
      $posts_url = preg_replace( '/(.+)feed\/?/i', '$1', $url );

      $rss = new TagGroups_Feed;

      $rss->debug( WP_DEBUG )->url( $url );
      $cache = $rss->cache_get();

      if ( empty( $cache ) ) {

        $cache = $rss->posts_url( $posts_url )->load()->parse()->render( $amount );

      }

      $response->add( array(
        'data' => 'success',
        'supplemental' => array(
          'output' => $cache,
        ),
      ));

      // Cannot use the method $response->send() because it includes die()
      header( 'Content-Type: text/xml; charset=' . get_option( 'blog_charset' ) );
      echo "<?xml version='1.0' encoding='" . get_option( 'blog_charset' ) . "' standalone='yes'?><wp_ajax>";
      foreach ( (array) $response->responses as $response_item ){
        echo $response_item;
      }
      echo '</wp_ajax>';


      // check if we received expired cache content
      if ( false !== $cache && $rss->expired ) {

        // load in background for next time
        $rss->posts_url( $posts_url )->load()->parse()->render( $amount );

        if ( WP_DEBUG ) {
          error_log('Preloaded feed into cache.');
        }
      }

      if ( wp_doing_ajax() ) {

        wp_die();

      } else {

        die();

      }
    }


    /**
    * AJAX handler to manage Tag Groups
    */
    static function ajax_manage_groups()
    {

      $response = new WP_Ajax_Response;

      if ( isset( $_REQUEST['task'] ) ) {

        $task = $_REQUEST['task'];

      } else {

        $task = 'refresh';

      }

      if ( isset( $_REQUEST['taxonomy'] ) ) {

        $taxonomy = $_REQUEST['taxonomy'];

      } else {

        $taxonomy = array( 'post_tag' );

      }

      $message = '';

      if ( class_exists( 'TagGroups_Premium' ) ) {

        $tag_group_role_edit_groups = get_option( 'tag_group_role_edit_groups', 'edit_pages');

      } else {

        $tag_group_role_edit_groups = 'edit_pages';

      }

      if ( ( current_user_can( $tag_group_role_edit_groups ) && wp_verify_nonce( $_REQUEST['nonce'], 'tg_groups_management' ) ) ||
      $task == 'refresh' ) {

        if ( isset( $_REQUEST['position'] ) ) {
          $position = (int) $_REQUEST['position'];
        } else {
          $position = 0;
        }

        if ( isset( $_REQUEST['new_position'] ) ) {
          $new_position = (int) $_REQUEST['new_position'];
        } else {
          $new_position = 0;
        }

        if ( isset( $_REQUEST['start_position'] ) ) {
          $start_position = (int) $_REQUEST['start_position'];
        }

        if ( empty( $start_position ) || $start_position < 1 ) {
          $start_position = 1;
        }

        if ( isset( $_REQUEST['end_position'] ) ) {
          $end_position = (int) $_REQUEST['end_position'];
        }

        if ( empty( $end_position ) || $end_position < 1 ) {
          $end_position = 1;
        }

        $group = new TagGroups_Group();

        switch ( $task ) {
          case "new":

          if ( isset( $_REQUEST['label'] ) ) {

            $label = stripslashes( sanitize_text_field( $_REQUEST['label'] ) );

          }

          if ( empty( $label ) ) {

            $message = __( 'The label cannot be empty.', 'tag-groups' );
            TagGroups_Admin::send_error( $response, $message, $task );

          } elseif ( $group->find_by_label( $label ) ) {

            $message = sprintf( __( 'A tag group with the label \'%s\' already exists, or the label has not changed. Please choose another one or go back.', 'tag-groups' ), $label );
            TagGroups_Admin::send_error( $response, $message, $task );

          } else {

            $group->create( $position + 1, $label );

            $message = sprintf( __( 'A new tag group with the label \'%s\' has been created!', 'tag-groups' ), $label );

          }
          break;

          case "update":
          if ( isset( $_REQUEST['label'] ) ) {

            $label = stripslashes( sanitize_text_field( $_REQUEST['label'] ) );

          }

          if ( empty( $label ) ) {

            $message = __( 'The label cannot be empty.', 'tag-groups' );
            TagGroups_Admin::send_error( $response, $message, $task );

          } elseif ( $group->find_by_label( $label ) ) {

            if ( ! empty( $position ) && $position == $group->get_position() ) {
              // Label hast not changed, just ignore

            } else {

              $message = sprintf( __( 'A tag group with the label \'%s\' already exists.', 'tag-groups' ), $label );
              TagGroups_Admin::send_error( $response, $message, $task );

            }
          } else {

            if ( ! empty( $position ) ) {

              if ( $group->find_by_position( $position ) ) {

                $group->change_label( $label );

              }

            } else {

              TagGroups_Admin::send_error( $response, 'error: invalid position: ' . $position, $task );

            }

            $message = sprintf( __( 'The tag group with the label \'%s\' has been saved!', 'tag-groups' ), $label );

          }


          break;

          case "delete":
          if ( ! empty( $position ) && $group->find_by_position( $position ) ) {

            $message = sprintf( __( 'A tag group with the id %1$s and the label \'%2$s\' has been deleted.', 'tag-groups' ), $group->get_term_group(), $group->get_label() );

            $group->delete();

          } else {

            TagGroups_Admin::send_error( $response, 'error: invalid position: ' . $position, $task );

          }

          break;

          case "up":
          if ( $position > 1 && $group->find_by_position( $position ) ) {

            if ( $group->move_to_position( $position - 1 ) !== false ) {

              $group->save();

            }

          }
          break;

          case "down":
          if ( $position < $group->get_max_position() && $group->find_by_position( $position ) ) {

            if ( $group->move_to_position( $position + 1 ) !== false ) {

              $group->save();

            }

          }
          break;

          case "move":

          if ( $new_position < 1 ) {

            $new_position = 1;

          }

          if ( $new_position > $group->get_max_position() ) {

            $new_position = $group->get_max_position();

          }

          if ( $position == $new_position ) {

            break;

          }

          if ( $group->find_by_position( $position ) ) {

            if ( $group->move_to_position( $new_position ) !== false ) {

              $group->save();

              $message = __( 'New order saved.', 'tag-groups' );

            }

          }

          break;

          case "refresh":
          // do nothing here
          break;
        }

        $number_of_term_groups = $group->get_number_of_term_groups() - 1; // "not assigned" won't be displayed

        if ( $start_position > $number_of_term_groups ) {

          $start_position = $number_of_term_groups;

        }

        $items_per_page = self::get_items_per_page();

        // calculate start and end positions
        $start_position = floor( ($start_position - 1) / $items_per_page ) * $items_per_page + 1;

        if ( $start_position + $items_per_page - 1 < $number_of_term_groups ) {

          $end_position = $start_position + $items_per_page - 1;

        } else {

          $end_position = $number_of_term_groups;

        }

        $response->add( array(
          'data' => 'success',
          'supplemental' => array(
            'task' => $task,
            'message' => $message,
            'nonce' => wp_create_nonce( 'tg_groups_management' ),
            'start_position' => $start_position,
            'groups' => json_encode( TagGroups_Admin::group_table( $start_position, $end_position, $taxonomy, $group ) ),
            'max_number' => $number_of_term_groups
          ),
        ));

      } else {

        TagGroups_Admin::send_error( $response, 'Security check', $task );

      }

      $response->send();
      exit();

    }



    /**
    *  Rerturns an error message to AJAX
    */
    static function send_error( $response, $message = 'error', $task = 'unknown' )
    {
      $response->add( array(
        'data' => 'error',
        'supplemental' => array(
          'message' => $message,
          'task' => $task,
        )
      ) );
      $response->send();
      exit();

    }


    /**
    * Assemble the content of the table of tag groups for AJAX
    */
    static function group_table( $start_position, $end_position, $taxonomy, $group )
    {

      $data = $group->get_all_with_position_as_key();

      $output = array();

      if ( count( $data ) > 1 ) {

        for ( $i = $start_position; $i <= $end_position; $i++ ) {
          if ( ! empty( $data[$i] ) ) {

            array_push( $output, array(
              'id' => $data[ $i ]['term_group'],
              'label' => $data[ $i ]['label'],
              'amount' => $group->get_number_of_terms( $taxonomy, $data[ $i ]['term_group'] )
            ) );
          }
        }
      }

      return $output;

    }


    /**
    * Processes form submissions from the settings page
    *
    *
    */
    static function settings_page_actions() {

      global $tagGroups_Base_instance;

      if ( ! empty( $_REQUEST['tg_action'] ) ) {
        $tg_action = $_REQUEST['tg_action'];
      } else {
        return;
      }

      if ( isset( $_GET['id'] ) ) {
        $tag_groups_id = (int) $_GET['id'];
      } else {
        $tag_groups_id = 0;
      }

      if ( isset( $_POST['theme-name'] ) ) {
        $theme_name = stripslashes( sanitize_text_field( $_POST['theme-name'] ) );
      } else {
        $theme_name = '';
      }

      if ( isset( $_POST['theme'] ) ) {
        $theme = stripslashes( sanitize_text_field( $_POST['theme'] ) );
      } else {
        $theme = '';
      }

      if ( isset( $_POST['taxonomies'] ) ) {
        $taxonomy = $_POST['taxonomies'];
      } else {
        $taxonomy = array();
      }

      if ( isset( $_POST['ok'] ) ) {
        $ok = $_POST['ok'];
      } else {
        $ok = '';
      }


      switch ( $tg_action ) {

        case 'shortcode':

        if ( !isset( $_POST['tag-groups-shortcode-nonce'] ) || !wp_verify_nonce( $_POST['tag-groups-shortcode-nonce'], 'tag-groups-shortcode' ) ) {

          die( "Security check" );

        }

        if ( isset( $_POST['widget'] ) && ($_POST['widget'] == '1') ) {

          update_option( 'tag_group_shortcode_widget', 1 );

        } else {

          update_option( 'tag_group_shortcode_widget', 0 );

        }


        if ( isset( $_POST['enqueue'] ) && ($_POST['enqueue'] == '1') ) {

          update_option( 'tag_group_shortcode_enqueue_always', 1 );

        } else {

          update_option( 'tag_group_shortcode_enqueue_always', 0 );

        }

        update_option( 'tag_group_admin_notice', array(
          'type' => 'success',
          'content' => __( 'Your settings have been saved.', 'tag-groups' )
        ));

        break;

        case 'reset':

        if ( !isset( $_POST['tag-groups-reset-nonce'] ) || !wp_verify_nonce( $_POST['tag-groups-reset-nonce'], 'tag-groups-reset' ) ) {

          die( "Security check" );

        }


        if ( $ok == 'yes' ) {

          $group = new TagGroups_Group();

          $group->reset();

          /**
          * Remove filters
          */

          delete_option( 'tag_group_tags_filter' );

          update_option( 'tag_group_admin_notice', array(
            'type' => 'success',
            'content' => __( 'All groups have been deleted and assignments reset.', 'tag-groups' )
          ));

        }

        break;

        case 'uninstall':

        if ( !isset( $_POST['tag-groups-uninstall-nonce'] ) || !wp_verify_nonce( $_POST['tag-groups-uninstall-nonce'], 'tag-groups-uninstall' ) ) {

          die( "Security check" );

        }


        if ( $ok == 'yes' ) {

          update_option( 'tag_group_reset_when_uninstall', 1 );

        } else {

          update_option( 'tag_group_reset_when_uninstall', 0 );

        }

        update_option( 'tag_group_admin_notice', array(
          'type' => 'success',
          'content' => __( 'Your settings have been saved.', 'tag-groups' )
        ));

        break;

        case 'wpml':

        $group = new TagGroups_Group();

        $data = $group->get_all_term_group_label();

        foreach ( $data as $term_group => $label ) {
          TagGroups_Admin::register_string_wpml( 'Group Label ID ' . $term_group, $label );
        }

        update_option( 'tag_group_admin_notice', array(
          'type' => 'success',
          'content' => __( 'All labels were registered.', 'tag-groups' )
        ));

        break;

        case 'theme':

        if ( $theme == 'own' ) {

          $theme = $theme_name;

        }

        if ( ! isset( $_POST['tag-groups-settings-nonce'] ) || !wp_verify_nonce( $_POST['tag-groups-settings-nonce'], 'tag-groups-settings' ) ) {

          die( "Security check" );

        }

        update_option( 'tag_group_theme', $theme );

        $mouseover = (isset( $_POST['mouseover'] ) && $_POST['mouseover'] == '1') ? 1 : 0;

        $collapsible = (isset( $_POST['collapsible'] ) && $_POST['collapsible'] == '1') ? 1 : 0;

        $html_description = (isset( $_POST['html_description'] ) && $_POST['html_description'] == '1') ? 1 : 0;

        update_option( 'tag_group_mouseover', $mouseover );

        update_option( 'tag_group_collapsible', $collapsible );

        update_option( 'tag_group_html_description', $html_description );

        $tag_group_enqueue_jquery = (isset( $_POST['enqueue-jquery'] ) && $_POST['enqueue-jquery'] == '1') ? 1 : 0;

        update_option( 'tag_group_enqueue_jquery', $tag_group_enqueue_jquery );

        TagGroups_Admin::clear_cache();

        update_option( 'tag_group_admin_notice', array(
          'type' => 'success',
          'content' => __( 'Your tag cloud theme settings have been saved.', 'tag-groups' )
        ));

        break;

        case 'taxonomy':

        if ( ! isset( $_POST['tag-groups-taxonomy-nonce'] ) || !wp_verify_nonce( $_POST['tag-groups-taxonomy-nonce'], 'tag-groups-taxonomy' ) ) {

          die( "Security check" );

        }

        $taxonomies = TagGroups_Taxonomy::get_public_taxonomies();

        foreach ( $taxonomy as $taxonomy_item ) {
          $taxonomy_item = stripslashes( sanitize_text_field( $taxonomy_item ) );

          if ( ! in_array( $taxonomy_item, $taxonomies ) ) {

            die( "Security check: taxonomies" );

          }
        }

        update_option( 'tag_group_taxonomy', $taxonomy );


        if ( class_exists( 'TagGroups_Premium_Post' ) && ( ! defined( 'TAG_GROUPS_DISABLE_CACHE_REBUILD' ) || TAG_GROUPS_DISABLE_CACHE_REBUILD ) ) {

          // schedule rebuild of cache
          wp_schedule_single_event( time() + 10, 'tag_groups_rebuild_post_terms' );

        }

        update_option( 'tag_group_admin_notice', array(
          'type' => 'success',
          'content' => __( 'Your taxonomy settings have been saved.', 'tag-groups' )
        ));

        break;

        case 'backend':

        if ( !isset( $_POST['tag-groups-backend-nonce'] ) || !wp_verify_nonce( $_POST['tag-groups-backend-nonce'], 'tag-groups-backend' ) ) {

          die( "Security check" );

        }

        $show_filter_posts = isset( $_POST['filter_posts'] ) ? 1 : 0;

        update_option( 'tag_group_show_filter', $show_filter_posts );

        $show_filter_tags = isset( $_POST['filter_tags'] ) ? 1 : 0;

        update_option( 'tag_group_show_filter_tags', $show_filter_tags );

        update_option( 'tag_group_admin_notice', array(
          'type' => 'success',
          'content' => __( 'Your back end settings have been saved.', 'tag-groups' )
        ));

        break;

        case 'export':

        if ( !isset( $_POST['tag-groups-export-nonce'] ) || !wp_verify_nonce( $_POST['tag-groups-export-nonce'], 'tag-groups-export' ) ) {

          die( "Security check" );

        }

        $options = array(
          'name' => 'tag_groups_options',
          'version' => TAG_GROUPS_VERSION,
          'date' => current_time( 'mysql' )
        );

        $option_names = $tagGroups_Base_instance->get_option_names();

        foreach ( $option_names as $key => $value ) {

          if ( $option_names[ $key ][ 'export' ] ) {

            $options[ $key ] = get_option( $key );

          }

        }

        // generate array of all terms
        $terms = get_terms( array(
          'hide_empty' => false,
        ) );

        $cm_terms = array(
          'name' => 'tag_groups_terms',
          'version' => TAG_GROUPS_VERSION,
          'date' => current_time( 'mysql' )
        );

        $cm_terms['terms'] = array();

        $tag_group_taxonomy = get_option( 'tag_group_taxonomy', array('post_tag') );

        foreach ( $terms as $term ) {
          if ( in_array( $term->taxonomy, $tag_group_taxonomy ) ) {

            if ( class_exists('TagGroups_Premium_Term') && get_term_meta( $term->term_id, '_cm_term_group_array', true ) != '' ) {

              $term_group = explode( ',', get_term_meta( $term->term_id, '_cm_term_group_array', true ) );

            } else {

              $term_group = $term->term_group;

            }

            $cm_terms['terms'][] = array(
              'term_id' => $term->term_id,
              'name' => $term->name,
              'slug' => $term->slug,
              'term_group' => $term_group,
              'term_taxonomy_id' => $term->term_taxonomy_id,
              'taxonomy' => $term->taxonomy,
              'description' => $term->description,
              'parent' => $term->parent,
              'count' => $term->count,
              'filter' => $term->filter,
              'meta' => $term->meta,
            );
          }
        }


        /**
        * Writing file
        */
        try {

          // misusing the password generator to get a hash
          $hash = wp_generate_password( 10, false );

          /*
          * Write settings/groups and tags separately
          */
          $fp = fopen( WP_CONTENT_DIR . '/uploads/tag_groups_settings-' . $hash . '.json', 'w' );
          fwrite( $fp, json_encode( $options ) );
          fclose( $fp );

          $fp = fopen( WP_CONTENT_DIR . '/uploads/tag_groups_terms-' . $hash . '.json', 'w' );
          fwrite( $fp, json_encode( $cm_terms ) );
          fclose( $fp );

          update_option( 'tag_group_admin_notice', array(
            'type' => 'success',
            'content' => __( 'Your settings/groups and your terms have been exported. Please download the resulting files with right-click or ctrl-click:', 'tag-groups' ) .
            '  <p>
            <a href="' . get_bloginfo( 'wpurl' ) . '/wp-content/uploads/tag_groups_settings-' . $hash . '.json" target="_blank">tag_groups_settings-' . $hash . '.json</a>
            </p>' .
            '  <p>
            <a href="' . get_bloginfo( 'wpurl' ) . '/wp-content/uploads/tag_groups_terms-' . $hash . '.json" target="_blank">tag_groups_terms-' . $hash . '.json</a>
            </p>'
          ));

        } catch ( Exception $e ) {

          update_option( 'tag_group_admin_notice', array(
            'type' => 'error',
            'content' => __( 'Writing of the exported settings failed.', 'tag-groups' )
          ));

        }
        break;

        case 'import':

        if ( !isset( $_POST['tag-groups-import-nonce'] ) || !wp_verify_nonce( $_POST['tag-groups-import-nonce'], 'tag-groups-import' ) ) {
          die( "Security check" );
        }

        // Make very sure that only administrators can upload stuff
        if ( !current_user_can( 'manage_options' ) ) {
          die( "Capability check failed" );
        }

        if ( !isset( $_FILES['settings_file'] ) ) {
          die( "File missing" );
        }

        if ( !function_exists( 'wp_handle_upload' ) ) {
          require_once( ABSPATH . 'wp-admin/includes/file.php' );
        }

        $settings_file = $_FILES['settings_file'];

        // Check file name, but allow for some additional characters in file name since downloading multiple times may add something to the original name.
        // Allow extension txt for backwards compatibility
        preg_match( '/^tag_groups_settings-\w{10}[\w,\s-]*\.((txt)|(json))$/', $_FILES['settings_file']['name'], $matches_settings );

        preg_match( '/^tag_groups_terms-\w{10}[\w,\s-]*\.json$/', $_FILES['settings_file']['name'], $matches_terms );

        if ( ! empty( $matches_settings ) && ! empty( $matches_settings[0] ) && $matches_settings[0] == $_FILES['settings_file']['name'] ) {

          $contents = @file_get_contents( $settings_file['tmp_name'] );

          if ( $contents === false ) {

            update_option( 'tag_group_admin_notice', array(
              'type' => 'error',
              'content' => __( 'Error reading the file.', 'tag-groups' )
            ));

          } else {

            $options = @json_decode( $contents , true);

            if ( empty( $options ) || !is_array( $options ) || $options['name'] != 'tag_groups_options' ) {

              update_option( 'tag_group_admin_notice',
              array(
                'type' => 'error',
                'content' => __( 'Error parsing the file.', 'tag-groups' )
              ));

            } else {

              $option_names = $tagGroups_Base_instance->get_option_names();

              $changed = 0;

              // import only whitelisted options
              foreach ( $option_names as $key => $value ) {

                if ( isset( $options[ $key ] ) ) {

                  $changed += update_option( $key, $options[ $key ] ) ? 1 : 0;

                }

              }

              if ( !isset( $options['date'] ) ) {
                $options['date'] = ' - ' . __( 'date unknown', 'tag-groups' ) . ' - ';
              }

              update_option( 'tag_group_admin_notice', array(
                'type' => 'success',
                'content' => sprintf( __( 'Your settings and groups have been imported from the file %1$s (created with plugin version %2$s on %3$s).', 'tag-groups' ), '<b>' . $_FILES['settings_file']['name'] . '</b>', $options['version'], $options['date'] ) . '</p><p>' .
                sprintf( _n( '%d option was added or changed.','%d options were added or changed.', $changed, 'tag-groups' ), $changed )
              ));

            }

          }

        } elseif ( ! empty( $matches_terms ) && ! empty( $matches_terms[0] ) && $matches_terms[0] == $_FILES['settings_file']['name'] ) {

          $contents = @file_get_contents( $settings_file['tmp_name'] );

          if ( $contents === false ) {

            update_option( 'tag_group_admin_notice', array(
              'type' => 'error',
              'content' => __( 'Error reading the file.', 'tag-groups' )
            ));

          } else {

            $terms = @json_decode( $contents , true);

            if ( empty( $terms ) || !is_array( $terms ) || $terms['name'] != 'tag_groups_terms' ) {

              update_option( 'tag_group_admin_notice',
              array(
                'type' => 'error',
                'content' => __( 'Error parsing the file.', 'tag-groups' )
              ));

            } else {

              $changed = 0;

              foreach ( $terms['terms'] as $term ) {
                // change only terms with the same name, else create new one
                if ( !term_exists( $term['term_id'], $term['taxonomy'] ) ) {
                  $inserted_term = wp_insert_term( $term['name'], $term['taxonomy'] );

                  if ( is_array( $inserted_term ) ) {

                    if ( is_array( $term['term_group'] ) && class_exists( 'TagGroups_Premium_Term' ) ) {

                      TagGroups_Premium_Term::save( $inserted_term['term_id'], $term['taxonomy'], $term['term_group'] );

                      unset( $term['term_group'] );

                    }

                    $result = wp_update_term( $inserted_term['term_id'], $term['taxonomy'], $term );

                    if ( is_array( $result ) ) {

                      $changed++;

                    }
                  }
                } else {
                  $result = wp_update_term( $term['term_id'], $term['taxonomy'], $term );

                  if ( is_array( $result ) ) {

                    $changed++;

                  }
                }

              }

              if ( !isset( $terms['date'] ) ) {
                $terms['date'] = ' - ' . __( 'date unknown', 'tag-groups' ) . ' - ';
              }

              update_option( 'tag_group_admin_notice', array(
                'type' => 'success',
                'content' => sprintf( __( 'Your terms have been imported from the file %1$s (created with plugin version %2$s on %3$s).', 'tag-groups' ), '<b>' . $_FILES['settings_file']['name'] . '</b>', $terms['version'], $terms['date'] ) . '</p><p>' .
                sprintf( _n( '%d term was added or updated.','%d terms were added or updated.', $changed, 'tag-groups' ), $changed )
              ));

            }

          }

        } else {

          if ( ! empty( $_FILES['settings_file']['name'] ) ) {

            $file_info = ' ' . $_FILES['settings_file']['name'];

          } else {

            $file_info = '';

          }

          update_option( 'tag_group_admin_notice', array(
            'type' => 'error',
            'content' => __( 'Error uploading the file.', 'tag-groups' ) . $file_info
          ));

        }

        break;

        default:
        // hook for premium plugin
        do_action( 'tag_groups_hook_settings_action', $tg_action );

        break;
      }


    }

    /**
    * Outputs the general settings page and handles changes
    *
    * @param void
    */
    static function settings_page() {

      global $tagGroups_Base_instance;

      $active_tab = 0;
      ?>

      <div class='wrap'>
        <h2><?php _e( 'Tag Groups Settings', 'tag-groups' ) ?></h2>

        <?php
        /*
        *  performing actions
        */

        $tag_group_theme = get_option( 'tag_group_theme', TAG_GROUPS_STANDARD_THEME );

        $tag_group_mouseover = get_option( 'tag_group_mouseover', '' );

        $tag_group_collapsible = get_option( 'tag_group_collapsible', '' );

        $tag_group_enqueue_jquery = get_option( 'tag_group_enqueue_jquery', 1 );

        $tag_group_html_description = get_option( 'tag_group_html_description', 0 );

        $tag_group_taxonomy = get_option( 'tag_group_taxonomy', array('post_tag') );

        $tag_group_shortcode_widget = get_option( 'tag_group_shortcode_widget' );

        $tag_group_shortcode_enqueue_always = get_option( 'tag_group_shortcode_enqueue_always', 1 );

        $show_filter_posts = get_option( 'tag_group_show_filter', 1 );

        $show_filter_tags = get_option( 'tag_group_show_filter_tags', 1 );

        $tag_group_reset_when_uninstall = get_option( 'tag_group_reset_when_uninstall', 0 );


        $default_themes = explode( ',', TAG_GROUPS_BUILT_IN_THEMES );


        /*
        * Render the Settings page
        */

        if ( isset( $_GET['active-tab'] ) ) {

          $active_tab = sanitize_title( $_GET['active-tab'] );

        } else {

          $active_tab = 'basics';

        }


        $tabs = array();
        $tabs['basics'] = __('Basics', 'tag-groups' );
        $tabs['theme'] = __('Theme', 'tag-groups' );

        if ( function_exists( 'icl_register_string' ) ) {

          $tabs['wpml'] = __('WPML', 'tag-groups' );

        }

        $tabs['tag-cloud'] = __('Tag Cloud', 'tag-groups' );
        $tabs['export-import'] = __('Export/Import', 'tag-groups' );
        $tabs['reset'] = __('Reset', 'tag-groups' );

        if ( !defined( 'TAG_GROUPS_PREMIUM_VERSION' ) ) {

          $tabs['premium'] = __('Premium', 'tag-groups' );

        }

        // hook for premium plugin
        $tabs = apply_filters( 'tag_groups_hook_settings_tabs', $tabs );

        $tabs['support'] = __('Support', 'tag-groups' );
        $tabs['about'] = __('About', 'tag-groups' );

        if ( !array_key_exists( $active_tab, $tabs ) ) {

          $active_tab = 'basics';

        }

        $html = '<h2 class="nav-tab-wrapper">';

        foreach ( $tabs as $slug => $label ) {

          $settings_url = admin_url( 'options-general.php?page=tag-groups-settings&amp;active-tab=' . $slug );

          $html .= '<a href="' . $settings_url . '" class="nav-tab ';

          if ( $slug == $active_tab) {

            $html .= 'nav-tab-active';

          }

          $html .= '">' . $label .'</a>';
        }
        $html .= '</h2>';

        echo $html;
        ?>

        <p>&nbsp;</p>

        <?php
        if ( 'basics' == $active_tab ) {

          $taxonomies = TagGroups_Taxonomy::get_public_taxonomies();

          $html = '<form method="POST" action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '">' .
          wp_nonce_field( 'tag-groups-taxonomy', 'tag-groups-taxonomy-nonce', true, false ) .
          '<h3>' . __( 'Taxonomies', 'tag-groups' ) . '</h3>
          <p>' . __( 'Choose the taxonomies for which you want to use tag groups. Default is <b>post_tag</b>. Please note that the tag cloud might not work with all taxonomies and that some taxonomies listed here may not be accessible in the admin backend. If you don\'t understand what is going on here, just leave the default.', 'tag-groups' ) . '</p>' .
          '<p>' . __( '<b>Please deselect taxonomies that you don\'t use. Using several taxonomies for the same post type or hierarchical taxonomies (like categories) is experimental and not supported.</b>', 'tag-groups' ) . '</p>' .
          '<p>' . __( 'To see the post type, hover you mouse over the option.', 'tag-groups' ) . '</p>' .
          '<ul>';

          foreach ( $taxonomies as $taxonomy ) {

            $post_types = TagGroups_Taxonomy::post_types_from_taxonomies( $taxonomy );

            $html .= '<li><input type="checkbox" name="taxonomies[]" id="' . $taxonomy . '" value="' . $taxonomy . '"';

            if ( in_array( $taxonomy, $tag_group_taxonomy ) ) {
              $html .= 'checked';
            }

            $html .= '/>&nbsp;<label for="' . $taxonomy . '" class="tg_unhide_trigger">' . $taxonomy . ' <span style="display:none; color:#999;">(' . __( 'post type', 'tag-groups-premium') . ': ' . implode( ', ', $post_types ) . ')</span></label></li>';

          }

          $html .= '</ul>
          <script>
          jQuery(document).ready(function () {
            jQuery(".tg_unhide_trigger").mouseover(function () {
              jQuery(this).find("span").show();
            });
            jQuery(".tg_unhide_trigger").mouseout(function () {
              jQuery(this).find("span").hide();
            });
          });
          </script>
          <input type="hidden" name="tg_action" value="taxonomy">
          <input class="button-primary" type="submit" name="Save" value="' .
          __( 'Save Taxonomy', 'tag-groups' ) . '" id="submitbutton" />
          </form>
          <p>&nbsp;</p>';

          $html .= '<form method="POST" action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '">' .
          wp_nonce_field( 'tag-groups-backend', 'tag-groups-backend-nonce', true, false ) .
          '
          <h3>' . __( 'Back End Settings', 'tag-groups' ) . '</h3>
          <p>' . __( 'You can add a pull-down menu to the filters above the list of posts. If you filter posts by tag groups, then only items will be shown that have tags (terms) in that particular group. This feature can be turned off so that the menu won\'t obstruct your screen if you use a high number of groups. May not work with all taxonomies.', 'tag-groups' ) . '</p>
          <ul>
          <li><input type="checkbox" id="tg_filter_posts" name="filter_posts" value="1"';

          if ( $show_filter_posts ) {
            $html .= ' checked';
          }

          $html .= '/>&nbsp;<label for="tg_filter_posts">' .
          __( 'Display filter on post admin', 'tag-groups' ) .
          '</label></li>
          </ul>
          <p>' .
          __( 'Here you can deactivate the filter on the list of tags if it conflicts with other plugins or themes.', 'tag-groups' ) . ' ' .
          __( '(Doesn\'t show category children, if parents don\'t belong to the same group.)' ) .
          '</p>
          <ul>
          <li><input type="checkbox" id="tg_filter_tags" name="filter_tags" value="1"';

          if ( $show_filter_tags ) {
            $html .= ' checked';
          }
          $html .= '/>&nbsp;<label for="tg_filter_tags">' .
          __( 'Display filter on tag admin', 'tag-groups' ) .
          '</label></li>
          </ul>
          <input type="hidden" name="tg_action" value="backend">
          <input class="button-primary" type="submit" name="Save" value="' .
          __( 'Save Back End Settings', 'tag-groups' ) .
          '" id="submitbutton" />
          </form>';

          echo $html;
        }

        if ( 'theme' == $active_tab ) {
          $html = '<form method="POST" action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '">' .
          wp_nonce_field( 'tag-groups-settings', 'tag-groups-settings-nonce', true, false ) .
          '<p>' . __( "Here you can choose a theme for the tag cloud. The path to own themes is relative to the <i>uploads</i> folder of your WordPress installation. Leave empty if you don't use any.", 'tag-groups' ) .
          '</p><p>' .
          __( 'New themes can be created with the <a href="http://jqueryui.com/themeroller/" target="_blank">jQuery UI ThemeRoller</a>:', 'tag-groups' ) .
          '<ol>
          <li>' . __( 'On the page "Theme Roller" you can customize all features or pick one set from the gallery. Finish with the "download" button.', 'tag-groups' ) . '</li>
          <li>' . __( 'On the next page ("Download Builder") you will need to select the version 1.12.x and the components "Core", "Widget", "Accordion" and "Tabs". Make sure that before downloading you enter at the bottom as "CSS Scope" <b>.tag-groups-cloud</b> (including the dot).', 'tag-groups' ) . '</li>
          <li>' . __( 'Then you unpack the downloaded zip file. You will need the "images" folder and the "jquery-ui.theme.min.css" file.', 'tag-groups' ) . '</li>
          <li>' . __( 'Create a new folder inside your <i>wp-content/uploads</i> folder (for example "my-theme") and copy there these two items.', 'tag-groups' ) . '</li>
          <li>' . __( 'Enter the name of this new folder (for example "my-theme") below.', 'tag-groups' ) .
          '</li>
          </ol>
          </p>
          <table>
          <tr>
          <td style="width:400px; padding-right:50px;">
          <ul>';

          foreach ( $default_themes as $theme ) {

            $html .= '<li><input type="radio" name="theme" id="tg_' . $theme . '" value="' . $theme . '"';

            if ( $tag_group_theme == $theme ) {

              $html .= ' checked';
            }
            $html .= '/>&nbsp;<label for="tg_' . $theme . '">' . $theme . '</label></li>';

          }

          $html .= '<li><input type="radio" name="theme" value="own" id="tg_own"';

          if ( !in_array( $tag_group_theme, $default_themes ) ) {

            $html .= ' checked';

          }

          $html .= '/>&nbsp;<label for="tg_own">own: /wp-content/uploads/</label><input type="text" id="theme-name" name="theme-name" value="';

          if ( !in_array( $tag_group_theme, $default_themes ) ) {

            $html .= $tag_group_theme;

          }

          $html .= '" /></li>
          <li><input type="checkbox" name="enqueue-jquery" id="tg_enqueue-jquery" value="1"';

          if ( $tag_group_enqueue_jquery ) {
            $html .= ' checked';
          }

          $html .= '/>&nbsp;<label for="tg_enqueue-jquery">' .
          __( 'Use jQuery.  (Default is on. Other plugins might override this setting.)', 'tag-groups' ) ;
          $html .= '</label></li>
          </ul>
          </td>

          <td>
          <h4>' . __( 'Further options', 'tag-groups' ) . '</h4>
          <ul>
          <li><input type="checkbox" name="mouseover" id="mouseover" value="1"';

          if ( $tag_group_mouseover ) {
            $html .= ' checked';
          }

          $html .= '>&nbsp;<label for="mouseover">' . __( 'Tabs triggered by hovering mouse pointer (without clicking).', 'tag-groups' ) .
          '</label></li>
          <li><input type="checkbox" name="collapsible" id="collapsible" value="1"';

          if ( $tag_group_collapsible ) {
            $html .= ' checked';
          }

          $html .= '>&nbsp;<label for="collapsible">' . __( 'Collapsible tabs (toggle open/close).', 'tag-groups' ) . '</label></li>
          <li><input type="checkbox" name="html_description" id="html_description" value="1" ';

          if ( $tag_group_html_description ) {
            $html .= 'checked';
          }

          $html .= '>&nbsp;<label for="html_description">' . __( 'Allow HTML in tag description.', 'tag-groups' ) . '</label></li>
          </ul>
          </td>
          </tr>
          </table>
          <input type="hidden" id="action" name="tg_action" value="theme">
          <input class="button-primary" type="submit" name="save" value="' .
          __( "Save Theme Options", "tag-groups" ) .
          '" id="submitbutton" />
          </form>';

          echo $html;

        }

        if ( 'wpml' == $active_tab && function_exists( 'icl_register_string' ) ) {

          $html = '<form method="POST" action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '">
          <h3>' .
          __( 'Register group labels with WPML', 'tag-groups' ) .
          '</h3>
          <p>' .
          __( 'Use this button to register all existing group labels with WPML for string translation. This is only necessary if labels have existed before you installed WPML.', 'tag-groups' ) .
          '  </p>
          <input type="hidden" id="action" name="tg_action" value="wpml">
          <input class="button-primary" type="submit" name="register" value="' .
          __( "Register Labels", "tag-groups" ) .
          '" id="submitbutton" />
          </form>';

          echo $html;
        }
        if ( 'tag-cloud' == $active_tab ): ?>
        <p><?php
        _e( 'You can use a shortcode to embed the tag cloud directly in a post, page or widget or you call the function in the PHP code of your theme.', 'tag-groups' )
        ?></p>
        <form method="POST" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>">
          <input type="hidden" name="tag-groups-shortcode-nonce" id="tag-groups-shortcode-nonce" value="<?php echo wp_create_nonce( 'tag-groups-shortcode' )
          ?>" />
          <ul>
            <li><input type="checkbox" name="widget" id="tg_widget" value="1" <?php if ( $tag_group_shortcode_widget ) echo 'checked'; ?> >&nbsp;<label for="tg_widget"><?php
            _e( 'Enable shortcode in sidebar widgets (if not visible anyway).', 'tag-groups' )
            ?></label></li>
            <li><input type="checkbox" name="enqueue" id="tg_enqueue" value="1" <?php if ( $tag_group_shortcode_enqueue_always ) echo 'checked'; ?> >&nbsp;<label for="tg_enqueue"><?php
            _e( 'Always load shortcode scripts. (Otherwise only if shortcode appears in a post or page. Turn on if you use these shortcodes in widgets.)', 'tag-groups' )
            ?></label></li>
          </ul>
          <input type="hidden" id="action" name="tg_action" value="shortcode">
          <input class='button-primary' type='submit' name='save' value='<?php
          _e( 'Save', 'tag-groups' ); ?>' id='submitbutton' />
        </form>

        <p>&nbsp;</p>
        <p><?php _e('Click for more information.', 'tag-groups') ?></p>
        <h3><?php _e('Shortcodes', 'tag-groups') ?></h3>
        <div class="tg_admin_accordion" >
          <h4>
            <?php _e( 'Tabbed Tag Cloud', 'tag-groups' ) ?>
          </h4>
          <div>
            <h4>[tag_groups_cloud]</h4>
            <p><?php _e( 'Display the tags in a tabbed tag cloud.', 'tag-groups' ) ?></p>
            <h4><?php _e( 'Example', 'tag-groups' ) ?></h4>
            <p>[tag_groups_cloud smallest=9 largest=30 include=1,2,10]</p>
            <h4><?php _e( 'Parameters', 'tag-groups' ) ?></h4>
            <p><?php printf( __( 'Please find the parameters in the <a %s>documentation</a>.', 'tag-groups' ), 'href="https://chattymango.com/tag-groups/tag-groups-shortcodes/?pk_campaign=tg&pk_kwd=documentation#Parameters" target="_blank"' ) ?></p>
          </div>

          <h4>
            <?php _e( 'Accordion', 'tag-groups' ) ?>
          </h4>
          <div>
            <h4>[tag_groups_accordion]</h4>
            <p><?php _e( 'Display the tags in an accordion.', 'tag-groups' ) ?></p>
            <h4><?php _e( 'Example', 'tag-groups' ) ?></h4>
            <p>[tag_groups_accordion smallest=9 largest=30 include=1,2,10]</p>
            <h4><?php _e( 'Parameters', 'tag-groups' ) ?></h4>
            <p><?php printf( __( 'Please find the parameters in the <a %s>documentation</a>.', 'tag-groups' ), 'href="https://chattymango.com/tag-groups/tag-groups-shortcodes/?pk_campaign=tg&pk_kwd=documentation#Parameters-2" target="_blank"' ) ?></p>
          </div>
          <?php
          do_action( 'tag_groups_hook_shortcodes' );
          ?>

          <h4>
            <?php _e( 'Group Information', 'tag-groups' ) ?>
          </h4>
          <div>
            <h4>[tag_groups_info]</h4>
            <p><?php _e( 'Display information about tag groups.', 'tag-groups' ) ?></p>
            <h4><?php _e( 'Example', 'tag-groups' ) ?></h4>
            <p>[tag_groups_info group_id="all"]</p>
            <h4><?php _e( 'Parameters', 'tag-groups' ) ?></h4>
            <p><?php printf( __( 'Please find the parameters in the <a %s>documentation</a>.', 'tag-groups' ), 'href="https://chattymango.com/tag-groups/tag-groups-shortcodes/?pk_campaign=tg&pk_kwd=documentation#Parameters-3" target="_blank"' ) ?></p>
          </div>
        </div>

        <h3>PHP</h3>
        <div class="tg_admin_accordion">
          <h4> tag_groups_cloud()</h4>
          <div>
            <p><?php _e( 'The function <b>tag_groups_cloud</b> accepts the same parameters as the [tag_groups_cloud] shortcode, except for those that determine tabs and styling.', 'tag-groups' ) ?></p>
            <p><?php _e( 'By default it returns a string with the html for a tabbed tag cloud.', 'tag-groups' ) ?></p>
            <h4><?php _e( 'Example', 'tag-groups' );
            echo '</h4>
            <p><code>' . htmlentities( "<?php if ( function_exists( 'tag_groups_cloud' ) ) echo tag_groups_cloud( array( 'include' => '1,2,5,6' ) ); ?>" )
            ?></code></p>
            <p>&nbsp;</p>
            <p><?php
            _e( 'If the optional second parameter is set to \'true\', the function returns a multidimensional array containing tag groups and tags.', 'tag-groups' ); ?></p>
            <h4><?php _e( 'Example', 'tag-groups' );
            echo '</h4>
            <p><code>' . htmlentities( "<?php if ( function_exists( 'tag_groups_cloud' ) ) print_r( tag_groups_cloud( array( 'orderby' => 'count', 'order' => 'DESC' ), true ) ); ?>" )
            ?></code></p>
          </div>
        </div>


      <?php endif; ?>

      <?php if ( 'export-import' == $active_tab ): ?>
        <p>
          <form method="POST" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>">
            <h3><?php
            _e( 'Export', 'tag-groups' )
            ?></h3>
            <input type="hidden" name="tag-groups-export-nonce" id="tag-groups-export-nonce" value="<?php echo wp_create_nonce( 'tag-groups-export' )
            ?>" />
            <p><?php
            _e( 'Use this button to export all Tag Groups settings and groups and all terms that are assigned to a group into files.', 'tag-groups' )
            ?></p>
            <p><?php
            _e( "You can import both files separately. Category hierarchy won't be saved. When you restore terms that were deleted, they receive new IDs and you must assign them to posts again. Exporting cannot substitute a backup.", 'tag-groups' )
            ?></p>
            <input type="hidden" id="action" name="tg_action" value="export">
            <p><input class='button-primary' type='submit' name='export' value='<?php
            _e( 'Export Files', 'tag-groups' ); ?>' id='submitbutton' /></p>
          </form>
        </p>
        <p>&nbsp;</p>
        <p>
          <form method="POST" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>" enctype="multipart/form-data">
            <h3><?php
            _e( 'Import', 'tag-groups' )
            ?></h3>
            <input type="hidden" name="tag-groups-import-nonce" id="tag-groups-import-nonce" value="<?php echo wp_create_nonce( 'tag-groups-import' )
            ?>" />
            <p><?php
            _e( 'Below you can import previously exported settings/groups or terms from a file.', 'tag-groups' )
            ?></p>
            <p><?php
            _e( 'It is recommended to back up the database of your blog before proceeding.', 'tag-groups' )
            ?></p>
            <input type="hidden" id="action" name="tg_action" value="import">
            <p><input type="file" id="settings_file" name="settings_file"></p>
            <p><input class='button-primary' type='submit' name='import' value='<?php
            _e( 'Import File', 'tag-groups' ); ?>' id='submitbutton' /></p>
          </form>
        </p>
      <?php endif;

      if ( 'reset' == $active_tab ) {
        $html = '<form method="POST" action="' . esc_url( $_SERVER['REQUEST_URI'] ). '">' .
        wp_nonce_field( 'tag-groups-reset', 'tag-groups-reset-nonce', true, false ) .
        '<p>' .
        __( 'Use this button to delete all tag groups and assignments. Your tags will not be changed. Check the checkbox to confirm.', 'tag-groups' ) .
        '</p>
        <p>' .
        __( '(Please keep in mind that the tag assignments cannot be recovered by the export/import function.)', 'tag-groups' ) .
        '</p>
        <input type="checkbox" id="ok" name="ok" value="yes" />
        <label>' .
        __( 'I know what I am doing.', 'tag-groups' ) .
        '</label>
        <input type="hidden" id="action" name="tg_action" value="reset">
        <p><input class="button-primary" type="submit" name="delete" value="' .
        __( "Delete Groups", "tag-groups" ) . '" id="submitbutton" /></p>
        </form>
        <p>&nbsp;</p>
        <h4>' .
        __( 'Delete Settings and Groups', 'tag-groups' ) .
        '</h4>
        <form method="POST" action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '">
        <p>' .
        wp_nonce_field( 'tag-groups-uninstall', 'tag-groups-uninstall-nonce', true, false ) .
        '<input type="checkbox" id="ok" name="ok" value="yes"';

        if ( $tag_group_reset_when_uninstall ) {
          $html .= ' checked';
        }
        $html .= '/>
        <label>' .
        __( "Delete all groups and settings when uninstalling the plugin.", "tag-groups" ) .
        '</label>
        <input type="hidden" id="action" name="tg_action" value="uninstall">
        </p>
        <input class="button-primary" type="submit" name="save" value="' .
        __( "Save", "tag-groups" ) . '" id="submitbutton" />
        </form>';

        echo $html;
      }

      if ( ! defined( 'TAG_GROUPS_PREMIUM_VERSION' ) && 'premium' == $active_tab ) :
        ?>
        <div style="float:right; margin:10px; width:300px; clear:right;"><img src="<?php echo TAG_GROUPS_PLUGIN_URL . '/images/tgp-meta-box.png'
        ?>" alt="Tag Groups Meta Box" title="Replace the default tag meta box with one that understands your tag groups!" border="0" style="clear:both;"/>
        <span>Replace the default tag meta box with one that understands your tag groups!</span>
      </div>
      <h2><?php _e( 'Get More Features', 'tag-groups' ) ?></h2>
      <p><?php printf( __( 'The <b>Tag Groups</b> plugin can be extended by <a %s>Tag Groups Premium</a>, which offers you many more useful features to take your tags to the next level:', 'tag-groups' ), 'href="https://chattymango.com/tag-groups-premium/?pk_campaign=tg&pk_kwd=dashboard" target="_blank"' ); ?></p>
      <ul style="list-style:disc;">
        <li style="padding:0 1em; margin-left:1em;">A <b>tag input tool</b> on the post edit screen allows you to work with tags on two levels: first select the group, and then choose among the tags of that group with type-ahead.</li>
        <li style="padding:0 1em; margin-left:1em;"><b>Color coding</b> minimizes the risk of accidentally creating a new tag with a typo: New tags are green, tags that changed their groups are yellow.</li>
        <li style="padding:0 1em; margin-left:1em;"><b>Control new tags:</b> Optionally restrict the creation of new tags or prevent moving tags to another group on the post edit screen. These restrictions can be overridden per user role.</li>
        <li style="padding:0 1em; margin-left:1em;"><b>Bulk-add tags:</b> If you often need to insert the same set of tags, simply join them in one group and insert them with the push of a button.</li>
        <li style="padding:0 1em; margin-left:1em;">The option to add each term to <b>multiple groups</b>.</li>
        <li style="padding:0 1em; margin-left:1em;"><b>Filter posts</b> on the front end by a tag group.</li>
        <li style="padding:0 1em; margin-left:1em;">Your visitors will love the <b>Dynamic Post Filter</b>: While they choose from available tags, the list shows posts that match these tags. Tags are organized under groups, which allows for useful logical operators. (e.g. show products that are (group "color") red OR blue AND (group "size") M OR XL OR XXL.)</li>
        <li style="padding:0 1em; margin-left:1em;">Set <b>permission</b> who is allowed to edit tag groups.</li>
        <li style="padding:0 1em; margin-left:1em;"><b>New tag clouds:</b> Display your tags in a table or tags from multiple groups combined into one tag cloud.</li>
      </ul>
      <p><?php printf( __( 'See the complete <a %1$s>feature comparison</a> or check out the <a %2$s>demos</a>.', 'tag-groups' ), 'href="https://chattymango.com/tag-groups-base-premium-comparison/?pk_campaign=tg&pk_kwd=dashboard" target="_blank"', 'href="https://demo.chattymango.com/category/tag-groups-premium/?pk_campaign=tg&pk_kwd=dashboard" target="_blank"' ); ?></p>


      <?php
    endif;

    // hook for premium plugin
    $premium_html  = apply_filters( 'tag_groups_hook_settings_content', '', $active_tab );

    // "About" page either for premium plugin or default version
    if ( ! empty( $premium_html ) ) :

      echo $premium_html;

      elseif ( 'support' == $active_tab ):

        $settings_url = admin_url( 'options-general.php?page=tag-groups-settings' );

        ?>

        <h2><?php _e( 'Support', 'tag-groups' ) ?></h2>
        <div style="background:#fff; padding:10px;">
          <p>Get started in 3 easy steps:</p><ol>
            <li>Go to the <span class="dashicons dashicons-admin-settings"></span>&nbsp;<a href="<?php echo $settings_url ?>">settings</a> and select the <b>taxonomy</b> of your tags. In most cases just leave the default: post_tag.</li>
            <li>Go to the <b>Tag Groups</b> page and create some groups. The default location of this page is under <span class="dashicons dashicons-admin-post"></span>Posts.</li>
            <li>Go to your <b>tags</b> and assign them to these groups.</li>
          </ol>
        </div>
        <p>&nbsp;</p>
        <p><?php printf( __( 'If you need help, please make sure to read the <a %1$s>instructions and troubleshooting information</a> or visit the official <a %2$s>support forum</a>.', 'tag-groups' ), 'href="https://chattymango.com/tag-groups/tag-groups-documentation/?pk_campaign=tg&pk_kwd=dashboard" target="_blank"', 'href="https://wordpress.org/support/plugin/tag-groups" target="_blank"' ); ?></p>

      <?php elseif ( 'about' == $active_tab ): ?>
        <h4>Tag Groups, Version: <?php echo TAG_GROUPS_VERSION ?></h4>
        <ul>
          <li>Developed by Christoph Amthor @ <a href="https://chattymango.com?pk_campaign=tg&pk_kwd=dashboard" target="_blank">Chatty Mango</a></li>
        </ul>

        <div style="float:left; width:400px; margin:20px;">
          <h2><?php
          _e( 'Newsletter', 'tag-groups' )
          ?></h2>
          <p><?php printf( __( '<a %s>Sign up for our newsletter</a> to receive updates about new versions and related tipps and news.', 'tag-groups' ), 'href="http://eepurl.com/c6AeK1" target="_blank"' ) ?></p>
            <p>&nbsp;</p>

            <h2><?php
            _e( 'Donations', 'tag-groups' )
            ?></h2>
            <p><?php
            _e( 'This plugin is the result of many years of development, adding new features, fixing bugs and answering to support questions.', 'tag-groups' )
            ?></p>
            <p><?php
            _e( 'If you find <b>Tag Groups</b> useful or use it to make money, I would appreciate a donation:', 'tag-groups' ); ?></p>
            <p><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=NUR3YJG7VAENA" target="_blank"><img src="<?php echo TAG_GROUPS_PLUGIN_URL . '/images/btn_donateCC_LG.gif'
            ?>" alt="Donate via Paypal" title="Donate via Paypal" border="0" /></a></p>
            <p><strong>Bitcoin: </strong><a href="bitcoin:1Fe21r57vDK56Yy2MbwjEoTVMiLefpV1v?label=Donation%20for%20Free%20Software" target="_blank">1Fe21r57vDK56Yy2MbwjEoTVMiLefpV1v</a></p>
            <p><?php printf( __( 'If you travel a lot, you can <a %s>use this affiliate link to book a hotel</a> so that I get a percentage of the sales.', 'tag-groups' ), 'href="http://www.booking.com/index.html?aid=947828&label=plugin" target="_blank"' );
            ?></p>
            <p><?php printf( __( 'You can also <a %s>donate to my favourite charity</a>.', 'tag-groups' ), 'href="http://www.burma-center.org/support-our-work/?pk_campaign=tg&pk_kwd=dashboard" target="_blank"' ); ?></p>

            <p>&nbsp;</p>
            <h2><?php
            _e( 'Reviews', 'tag-groups' )
            ?></h2>
            <p><?php printf( __( 'I would be glad if you could give my plugin a <a %s>five-star rating</a>.', 'tag-groups' ), 'href="https://wordpress.org/support/plugin/tag-groups/reviews/?filter=5" target="_blank"' ); ?>
              <div style="display:inline-block;"><a href="https://wordpress.org/support/plugin/tag-groups/reviews/?filter=5" target="_blank" style="color: #ffb900;text-decoration: none;">
                <span class="dashicons dashicons-star-filled"></span>
                <span class="dashicons dashicons-star-filled"></span>
                <span class="dashicons dashicons-star-filled"></span>
                <span class="dashicons dashicons-star-filled"></span>
                <span class="dashicons dashicons-star-filled"></span>
              </a></div>
            </p>
            <?php
            _e( 'Thanks!', 'tag-groups' )
            ?></p>
            <p>Christoph</p>
            <p>&nbsp;</p>
            <h2><?php
            _e( 'Credits and 3rd-party licences', 'tag-groups' )
            ?></h2>
            <ul>
              <li><?php printf( __( 'These plugins use css and images by <a %s>jQuery UI</a>. (bundled with WordPress)', 'tag-groups' ), 'href="http://jqueryui.com/" target="_blank"') ?></li>
              <li><?php printf( __( 'jQuery plugin <a %1$s>SumoSelect</a>: <a %2$s>MIT License</a>. Copyright (c) 2016 Hemant Negi', 'tag-groups' ), 'href="https://github.com/HemantNegi/jquery.sumoselect"', 'href="http://www.opensource.org/licenses/mit-license.php" target="_blank"') ?>'</li>
              <li><?php printf( __( 'React JS plugin <a %1$s>React-Select</a>: <a %2$s>MIT License</a>. Copyright (c) 2018 Jed Watson', 'tag-groups' ), 'https://github.com/JedWatson/react-select"', 'href="http://www.opensource.org/licenses/mit-license.php" target="_blank"') ?>'</li>
              <li>Spanish translation (es_ES) by <a href="http://www.webhostinghub.com/" target="_blank">Andrew Kurtis</a></li>
            </ul>
          </div>

          <?php

          $html = '<div style="float:left; margin:20px;">';

          $html .= '<h2>' . __( 'Latest Development News', 'tag-groups' ) . '</h2>'.
          '
          <table class="widefat fixed" cellspacing="0" style="max-width:550px;">
          <thead>
          <tr>
          <th style="width:200px;"></th>
          <th></th>
          </tr>
          </thead>
          <tbody id="tg_feed_container"><tr><td colspan="2" style="text-align:center;">' .
          __( 'Loading...', 'tag-groups') .
          '</td></tr></tbody>
          </table>

          <script>
          jQuery(document).ready(function(){
            var tg_feed_amount = jQuery("#tg_feed_amount").val();
            var data = {
              action: "tg_ajax_get_feed",
              url: "' . TAG_GROUPS_UPDATES_RSS_URL . '",
              amount: 5
            };

            jQuery.post("';

            $protocol = isset( $_SERVER['HTTPS'] ) ? 'https://' : 'http://';
            $html .= admin_url( 'admin-ajax.php', $protocol ) .

            '", data, function (data) {
              var status = jQuery(data).find("response_data").text();
              if (status == "success") {
                var output = jQuery(data).find("output").text();
                jQuery("#tg_feed_container").html(output);
              }
            });
          });
          </script>
          </div>';
          echo $html;

        endif;
        ?>
      </div>

      <?php

      $html = '
      <!-- begin Tag Groups plugin -->
      <script type="text/javascript">
      jQuery(function() {
        var icons = {
          header: "dashicons dashicons-arrow-right",
          activeHeader: "dashicons dashicons-arrow-down"
        };
        jQuery( ".tg_admin_accordion" ).accordion({
          icons:icons,
          collapsible: true,
          active: false,
          heightStyle: "content"
        });
      });
      </script>
      <!-- end Tag Groups plugin -->
      ';
      echo $html;

    }


    /**
    * Outputs a table on a submenu page where you can add, delete, change tag groups, their labels and their order.
    */
    static function group_administration()
    {

      $tag_group_show_filter_tags = get_option( 'tag_group_show_filter_tags', 1 );

      $tag_group_show_filter = get_option( 'tag_group_show_filter', 1 );

      $taxonomy_link = '';

      $post_type_link = '';


      if ( $tag_group_show_filter_tags || $tag_group_show_filter ) {

        $post_type = preg_replace( '/tag-groups_(.+)/', '$1', sanitize_title( $_GET['page'] ) );

      }

      /**
      * Check if the tag filter is activated
      */
      if ( $tag_group_show_filter_tags ) {

        // get first of taxonomies that are associated with that $post_type
        $tg_taxonomies = get_option( 'tag_group_taxonomy', array('post_tag') );

        $taxonomy_names = get_object_taxonomies( $post_type );

        $taxonomies = array_intersect( $tg_taxonomies, $taxonomy_names );

        /**
        * Show the link to the taxonomy filter only if there is only one taxonomy for this post type (otherwise ambiguous where to link)
        */
        if ( ! empty( $taxonomies ) && count( $taxonomies ) == 1 ) {

          $taxonomy_link = reset( $taxonomies );

        }
      }


      /**
      * Check if the post filter is activated
      */
      if ( $tag_group_show_filter ) {

        $post_type_link = $post_type;

      }

      $items_per_page = self::get_items_per_page();

      ?>

      <div class='wrap'>
        <h2><?php _e( 'Tag Groups', 'tag-groups' ) ?></h2>

        <p><?php
        _e( 'On this page you can define tag groups. Tags (or terms) can be assigned to these groups on the page where you edit the tags (terms).', 'tag-groups' ); ?></p>
        <p><?php _e( 'Change the order by drag and drop or with the up/down icons. Click into a labels for editing.', 'tag-groups' );
        ?></p>

        <div id="tg_message_container"></div>

        <table class="widefat tg_groups_table">
          <thead>
            <tr>
              <th style="min-width:30px;"><?php
              _e( 'Group ID', 'tag-groups' )
              ?></th>
              <th><?php
              _e( 'Label displayed on the frontend', 'tag-groups' )
              ?></th>
              <th><?php
              _e( 'Number of assigned tags', 'tag-groups' )
              ?></th>
              <th><?php
              _e( 'Action', 'tag-groups' )
              ?></th>
              <th><?php
              _e( 'Change sort order', 'tag-groups' )
              ?></th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th><?php
              _e( 'Group ID', 'tag-groups' )
              ?></th>
              <th><?php
              _e( 'Label displayed on the frontend', 'tag-groups' )
              ?></th>
              <th><?php
              _e( 'Number of assigned tags', 'tag-groups' )
              ?></th>
              <th><?php
              _e( 'Action', 'tag-groups' )
              ?></th>
              <th><?php
              _e( 'Change sort order', 'tag-groups' )
              ?></th>
            </tr>
          </tfoot>
          <tbody id="tg_groups_container">
            <tr>
              <td colspan="5" style="padding: 50px; text-align: center;">
                <img src="<?php echo admin_url('images/spinner.gif') ?>" />
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div id="tg_pager_container_adjuster">
        <div id="tg_pager_container"></div>
      </div>
      <input type="hidden" id="tg_nonce" value="">
      <input type="hidden" id="tg_start_position" value="1">

      <script>
      var labels = new Object();
      labels.edit = '<?php
      _e( 'Edit', 'tag-groups' )
      ?>';
      labels.create = '<?php
      _e( 'Create', 'tag-groups' )
      ?>';
      labels.newgroup = '<?php
      _e( 'new', 'tag-groups' )
      ?>';
      labels.placeholder_new = '<?php
      _e( 'label', 'tag-groups' )
      ?>';
      labels.tooltip_delete = '<?php
      _e( 'Delete this group.', 'tag-groups' )
      ?>';
      labels.tooltip_newbelow = '<?php
      _e( 'Create a new group below.', 'tag-groups' )
      ?>';
      labels.tooltip_move_up = '<?php
      _e( 'move up', 'tag-groups' )
      ?>';
      labels.tooltip_move_down = '<?php
      _e( 'move down', 'tag-groups' )
      ?>';
      labels.tooltip_reload = '<?php
      _e( 'reload', 'tag-groups' )
      ?>';
      labels.tooltip_showposts = '<?php
      _e( 'Show posts', 'tag-groups' )
      ?>';
      labels.tooltip_showtags = '<?php
      _e( 'Show tags', 'tag-groups' )
      ?>';

      var tg_params = {"ajaxurl": "<?php
        $protocol = isset( $_SERVER['HTTPS'] ) ? 'https://' : 'http://';
        echo admin_url( 'admin-ajax.php', $protocol );
        ?>", "postsurl": "<?php
        if ( ! empty( $post_type_link ) ) {
          echo admin_url( 'edit.php?post_type=' . $post_type_link, $protocol );
        }
        ?>", "tagsurl": "<?php
        if ( ! empty( $taxonomy_link ) ) {
          echo admin_url( 'edit-tags.php?taxonomy=' . $taxonomy_link, $protocol );
        }
        ?>", "items_per_page": "<?php echo $items_per_page ?>"};
        var data = {
          taxonomy: <?php echo json_encode( $taxonomies ) ?>
        };

        jQuery(document).ready(function () {
          data.task = "refresh";
          tg_do_ajax(tg_params, data, labels);

          jQuery(".tg_edit_label").live('click', function () {
            tg_close_all_textfields();
            var element = jQuery(this);
            var position = element.attr("data-position");
            var label = escape_html(element.attr("data-label"));
            element.replaceWith('<span class="tg_edit_label_active"><input data-position="' + position + '" data-label="' + label + '" value="' + label + '"> <span class="tg_edit_label_yes dashicons dashicons-yes tg_pointer" ></span> <span class="tg_edit_label_no dashicons dashicons-no-alt tg_pointer"></span></span>');
          });

          jQuery(".tg_edit_label_active").live('keypress', function (e) {
            if (e.keyCode == 13) {
              var input = jQuery(this).children(":first");
              var data = {
                task: 'update',
                position: input.attr('data-position'),
                label: input.val(),
                taxonomy: <?php echo json_encode( $taxonomies ) ?>,
              };
              tg_do_ajax(tg_params, data, labels);
            }
          });

          jQuery(".tg_edit_label_yes").live('click', function () {
            var input = jQuery(this).parent().children(":first");
            var data = {
              task: 'update',
              position: input.attr('data-position'),
              label: input.val(),
              taxonomy: <?php echo json_encode( $taxonomies ) ?>,
            };
            tg_do_ajax(tg_params, data, labels);
          });

          jQuery(".tg_edit_label_no").live('click', function () {
            var input = jQuery(this).parent().children(":first");
            tg_close_textfield(jQuery(this).parent(), false);
          });

          jQuery("[id^='tg_new_']:visible").live('keypress', function (e) {
            if (e.keyCode == 13) {
              var input = jQuery(this).find("input");
              var data = {
                task: 'new',
                position: input.attr('data-position'),
                label: input.val(),
                taxonomy: <?php echo json_encode( $taxonomies ) ?>,
              };
              tg_do_ajax(tg_params, data, labels);
            }
          });

          jQuery(".tg_new_yes").live('click', function () {
            var input = jQuery(this).parent().children(":first");
            var data = {
              task: 'new',
              position: input.attr('data-position'),
              label: input.val(),
              taxonomy: <?php echo json_encode( $taxonomies ) ?>,
            };
            tg_do_ajax(tg_params, data, labels);
          });

          jQuery(".tg_delete").live('click', function () {
            var position = jQuery(this).attr("data-position");
            jQuery('.tg_sort_tr[data-position='+position+'] td').addClass('tg_ask_delete');
            var answer = confirm('<?php
            _e( 'Do you really want to delete this tag group?', 'tag-groups' )
            ?> ');
            if (answer) {
              var data = {
                task: 'delete',
                position: position,
                taxonomy: <?php echo json_encode( $taxonomies ) ?>,
              };
              tg_do_ajax(tg_params, data, labels);

            } else {
              jQuery('.tg_sort_tr[data-position='+position+'] td').removeClass('tg_ask_delete')
            }
          });

          jQuery(".tg_edit_label").live('mouseenter', function () {
            jQuery(this).children(".dashicons-edit").fadeIn();
          });

          jQuery(".tg_edit_label").live('mouseleave', function () {
            jQuery(this).children(".dashicons-edit").fadeOut();
          });

          jQuery(".tg_pager_button").live('click', function () {
            var page = jQuery(this).attr('data-page');
            jQuery("#tg_start_position").val((page - 1) * <?php echo $items_per_page ?> + 1);
            data.task = "refresh";
            tg_do_ajax(tg_params, data, labels);
          });

          jQuery(".tg_up").live('click', function () {
            data.position = jQuery(this).attr('data-position');
            data.task = "up";
            tg_do_ajax(tg_params, data, labels);
          });

          jQuery(".tg_down").live('click', function () {
            data.position = jQuery(this).attr('data-position');
            data.task = "down";
            tg_do_ajax(tg_params, data, labels);
          });

          var element, start_pos, end_pos;
          jQuery("#tg_groups_container").sortable({
            start: function (event, ui) {
              element = Number(ui.item.attr("data-position"));
              start_pos = ui.item.index(".tg_sort_tr") + 1;
            },
            update: function (event, ui) {
              end_pos = ui.item.index(".tg_sort_tr") + 1;
              data.position = element;
              data.task = "move";
              data.new_position = element + end_pos - start_pos;
              tg_do_ajax(tg_params, data, labels);
            }
          });
          jQuery("#tg_groups_container").disableSelection();

          jQuery("#tg_groups_reload").live('click', function () {
            data.task = "refresh";
            tg_do_ajax(tg_params, data, labels);
          });
        });
        </script>
        <?php if ( current_user_can( 'manage_options' ) ) :
          $settings_url = admin_url( 'options-general.php?page=tag-groups-settings' );
          ?>
          <p><a href="<?php echo $settings_url ?>"><?php
          _e( 'Go to the settings.', 'tag-groups' )
          ?></a></p>
        <?php endif;

      }


      /**
      * Good idea to purge the cache after changing theme options - else your visitors won't see the change for a while. Currently implemented for W3T Total Cache and WP Super Cache.
      */
      static function clear_cache()
      {

        if ( function_exists( 'flush_pgcache' ) ) {
          flush_pgcache;
        }

        if ( function_exists( 'flush_minify' ) ) {
          flush_minify;
        }

        if ( function_exists( 'wp_cache_clear_cache' ) ) {
          wp_cache_clear_cache();
        }

      }


      /**
      * Makes sure that WPML knows about the tag group label that can have different language versions.
      *
      * @param string $name
      * @param string $value
      */
      static function register_string_wpml( $name, $value )
      {

        if ( function_exists( 'icl_register_string' ) ) {

          icl_register_string( 'tag-groups', $name, $value );

        }

      }


      /**
      * Asks WPML to forget about $name
      *
      * @param string $name
      */
      static function unregister_string_wpml( $name )
      {

        if ( function_exists( 'icl_unregister_string' ) ) {

          icl_unregister_string( 'tag-groups', $name );

        }

      }


      /**
      *
      * Modifies the query to retrieve tags for filtering in the backend.
      *
      * @param array $pieces
      * @param array $taxonomies
      * @param array $args
      * @return array
      */
      static function terms_clauses( $pieces, $taxonomies, $args )
      {
        $taxonomy = array_shift($taxonomies);

        if ( empty( $taxonomy ) || is_array( $taxonomy ) ) {

          $taxonomy = 'post_tag';

        }

        $show_filter_tags = get_option( 'tag_group_show_filter_tags', 1 );

        if ( $show_filter_tags ) {

          $tag_group_tags_filter = get_option( 'tag_group_tags_filter', array() );

          if ( isset( $tag_group_tags_filter[ $taxonomy ] ) ) {

            $group_id = $tag_group_tags_filter[ $taxonomy ];

          } else {

            $group_id = -1;

          }


          // check if group exists (could be deleted since last time the filter was set)
          $group_o = new TagGroups_Group();

          if ( $group_id > $group_o->get_max_term_group() ) {

            $group_id = -1;

          }


          if ( $group_id > -1 ) {

            if ( ! class_exists('TagGroups_Premium_Group') ) {

              if ( ! empty( $pieces['where'] ) ) {

                $pieces['where'] .= sprintf( " AND t.term_group = %d ", $group_id );

              } else {

                $pieces['where'] = sprintf( "t.term_group = %d ", $group_id );

              }

            } else {

              $mq_sql = TagGroups_Premium_Group::terms_clauses( $group_id );

              if ( ! empty( $pieces['join'] ) ) {

                $pieces['join'] .= $mq_sql['join'];

              } else {

                $pieces['join'] = $mq_sql['join'];

              }

              if ( ! empty( $pieces['where'] ) ) {

                $pieces['where'] .= $mq_sql['where'];

              } else {

                $pieces['where'] = $mq_sql['where'];

              }

            }
          }
        }

        return $pieces;

      }


      /**
      * Adds css to backend
      */
      static function add_admin_js_css( $where )
      {

        if ( strpos( $where, 'tag-groups-settings' ) !== false ) {

          wp_enqueue_script( 'jquery' );

          wp_enqueue_script( 'jquery-ui-core' );

          wp_enqueue_script( 'jquery-ui-accordion' );

          wp_register_style( 'tag-groups-css-backend', TAG_GROUPS_PLUGIN_URL .  '/css/admin-style.css', array(), TAG_GROUPS_VERSION );

          wp_enqueue_style( 'tag-groups-css-backend' );

          wp_register_style( 'tag-groups-css-backend-structure', TAG_GROUPS_PLUGIN_URL . '/css/jquery-ui.structure.min.css', array(), TAG_GROUPS_VERSION );

          wp_enqueue_style( 'tag-groups-css-backend-structure' );

          wp_register_script( 'sumoselect-js', TAG_GROUPS_PLUGIN_URL . '/js/jquery.sumoselect.min.js', array(), TAG_GROUPS_VERSION );

          wp_enqueue_script( 'sumoselect-js' );

          wp_register_style( 'sumoselect-css', TAG_GROUPS_PLUGIN_URL .  '/css/sumoselect.css', array(), TAG_GROUPS_VERSION );

          wp_enqueue_style( 'sumoselect-css' );


        } elseif ( strpos( $where, '_page_tag-groups' ) !== false ) {

          wp_register_style( 'tag-groups-css-backend', TAG_GROUPS_PLUGIN_URL .  '/css/admin-style.css', array(), TAG_GROUPS_VERSION );

          wp_enqueue_style( 'tag-groups-css-backend' );

          if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {

            wp_register_script( 'tag-groups-js-backend', TAG_GROUPS_PLUGIN_URL . '/js/taggroups.js', array(), TAG_GROUPS_VERSION );

          } else {

            wp_register_script( 'tag-groups-js-backend', TAG_GROUPS_PLUGIN_URL . '/js/taggroups.min.js', array(), TAG_GROUPS_VERSION );

          }

          wp_enqueue_script( 'tag-groups-js-backend' );

          wp_enqueue_script( 'jquery-ui-sortable' );

        } elseif ( strpos( $where, 'edit-tags.php' ) !== false || strpos( $where, 'term.php' ) !== false  || strpos( $where, 'edit.php' ) !== false ) {

          wp_register_script( 'sumoselect-js', TAG_GROUPS_PLUGIN_URL . '/js/jquery.sumoselect.min.js', array(), TAG_GROUPS_VERSION );

          wp_enqueue_script( 'sumoselect-js' );

          wp_register_style( 'sumoselect-css', TAG_GROUPS_PLUGIN_URL .  '/css/sumoselect.css', array(), TAG_GROUPS_VERSION );

          wp_enqueue_style( 'sumoselect-css' );

          wp_register_style( 'tag-groups-css-backend', TAG_GROUPS_PLUGIN_URL .  '/css/admin-style.css', array(), TAG_GROUPS_VERSION );

          wp_enqueue_style( 'tag-groups-css-backend' );

        } elseif ( strpos( $where, 'post-new.php' ) !== false || strpos( $where, 'post.php' ) !== false ) {

          wp_register_style( 'react-select-css', TAG_GROUPS_PLUGIN_URL .  '/css/react-select.css', array(), TAG_GROUPS_VERSION );

          wp_enqueue_style( 'react-select-css' );

        }

      }


      /**
      * Adds Settings link to plugin list
      *
      * @param array $links
      * @return array
      */
      static function add_plugin_settings_link( $links )
      {

        $settings_link = '<a href="' . admin_url( 'options-general.php?page=tag-groups-settings' ) . '">' . __( 'Settings', 'tag-groups' ) . '</a>';

        array_unshift( $links, $settings_link );


        if ( ! class_exists('TagGroups_Premium') ) {

          $settings_link = '<a href="https://chattymango.com/tag-groups-premium/?pk_campaign=tg&pk_kwd=settings_link" target="_blank"><span style="color:#3A0;">' . __( 'Upgrade to Premium', 'tag-groups' ) . '</span></a>';

          array_unshift( $links, $settings_link );

        }

        return $links;

      }


      /**
      * Returns the items per page on the tag groups screen
      *
      *
      * @param void
      * @return int
      */
      public static function get_items_per_page()
      {

        if ( class_exists( 'TagGroups_Premium_Admin' ) && method_exists( 'TagGroups_Premium_Admin', 'add_screen_option' ) ) {

          $items_per_page_all_users = get_option( 'tag_groups_per_page', array() );

          $user = get_current_user_id();

          if ( isset( $items_per_page_all_users[ $user ] ) ) {

            $items_per_page = intval( $items_per_page_all_users[ $user ] );

          }


          if ( ! isset( $items_per_page_all_users[ $user ] ) || $items_per_page < 1 ) {

            $items_per_page = TAG_GROUPS_ITEMS_PER_PAGE;

          }

        } else {

          $items_per_page = TAG_GROUPS_ITEMS_PER_PAGE;

        }

        return $items_per_page;
      }

    } // class

  }
