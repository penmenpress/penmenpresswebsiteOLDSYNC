<?php
/**
* @package     Tag Groups
* @author      Christoph Amthor
* @copyright   2018 Christoph Amthor (@ Chatty Mango, chattymango.com)
* @license     GPL-3.0+
*/

if ( ! class_exists('TagGroups_Shortcode') ) {

  class TagGroups_Shortcode {

    function __construct() {

    }

    /*
    * Register the shortcodes with WordPress
    */
    static function register() {

      add_shortcode( 'tag_groups_cloud', array( 'TagGroups_Shortcode', 'tag_groups_cloud') );

      if ( function_exists( 'register_block_type') ) {

        register_block_type( 'chatty-mango/tag-groups-cloud-tabs', array(
          'render_callback' => 'tag_groups_cloud',
        ) );

      }

      add_shortcode( 'tag_groups_accordion', array( 'TagGroups_Shortcode', 'tag_groups_accordion') );

      if ( function_exists( 'register_block_type') ) {

        register_block_type( 'chatty-mango/tag-groups-cloud-accordion', array(
          'render_callback' => 'tag_groups_accordion',
        ) );

      }

      add_shortcode( 'tag_groups_info', array( 'TagGroups_Shortcode', 'tag_groups_info') );

    }


    /**
    * Makes sure that shortcodes work in text widgets.
    */
    static function widget_hook() {

      $tag_group_shortcode_widget = get_option( 'tag_group_shortcode_widget', 0 );

      if ( $tag_group_shortcode_widget ) {

        add_filter( 'widget_text', 'do_shortcode' );

      }

    }


    /**
    *
    * Render the tabbed tag cloud, usually by a shortcode, or returning a multidimensional array
    *
    * @param array $atts
    * @param bool $return_array
    * @return string
    */
    static function tag_groups_cloud( $atts = array(), $return_array = false ) {

      // create key that depends on settings
      $key_array = $atts;

      if ( isset( $key_array['div_id'] ) ) {

        unset( $key_array['div_id'] );

      }

      $key = md5( 'tabs' . serialize( $key_array ) . var_export( $return_array, true ) );

      // check for a cached version (premium plugin)
      $html = apply_filters( 'tag_groups_hook_cache_get', false, $key );

      if ( $html ) {

        return $html;

      }

      $html_tabs = array();

      $html_tags = array();

      $post_id_terms = array();

      $assigned_terms = array();

      $include_tags_post_id_groups = array();

      $group = new TagGroups_Group();

      $data = $group->get_all_with_position_as_key();

      $tag_group_ids = $group->get_all_ids();

      extract( shortcode_atts( array(
        'active' => null,
        'adjust_separator_size' => true,
        'add_premium_filter' => 1,
        'amount' => 0,
        'append' => '',
        'assigned_class' => null,
        'collapsible' => null,
        'custom_title' => null,
        'div_class' => 'tag-groups-cloud',
        'div_id' => 'tag-groups-cloud-tabs-' . uniqid(),
        'exclude_terms' => '',
        'groups_post_id' => -1,
        'hide_empty_tabs' => false,
        'hide_empty' => true,
        'include' => '',
        'include_terms' => '',
        'largest' => 22,
        'link_append' => '',
        'link_target' => '',
        'mouseover' => null,
        'not_assigned_name' => 'not assigned',
        'order' => 'ASC',
        'orderby' => 'name',
        'prepend' => '',
        'separator_size' => 12,
        'separator' => '',
        'show_not_assigned' => false,
        'show_all_groups' => false,
        'show_tabs' => '1',
        'show_tag_count' => true,
        'smallest' => 12,
        'tags_post_id' => -1,
        'taxonomy' => null,
        'ul_class' => ''
      ), $atts ) );

      if ( 'natural' == $orderby ) {

        $natural_sorting = true;
        $orderby = 'name';

      } else {

        $natural_sorting = false;

      }

      if ( $smallest < 1 ) {

        $smallest = 1;

      }

      if ( $largest < $smallest ) {

        $largest = $smallest;

      }

      if ( $amount < 0 ) {

        $amount = 0;

      }

      if ( ! empty( $show_not_assigned ) ) {

        $start_group = 0;

      } else {

        $start_group = 1;

      }

      if ( ! empty( $link_append ) && mb_strpos( $link_append, '?' ) === 0 ) {

        $link_append = mb_substr( $link_append, 1 );

      }

      if ( isset( $taxonomy ) ) {

        if ( empty( $taxonomy ) ) {

          unset( $taxonomy );

        } else {

          $taxonomy_array = explode( ',', $taxonomy );

          $taxonomy_array = array_filter( array_map( 'trim', $taxonomy_array ) );

        }
      }


      $taxonomies = TagGroups_Taxonomy::get_public_taxonomies();

      if ( ! empty( $taxonomy_array ) ) {

        $taxonomies = array_intersect( $taxonomies, $taxonomy_array );

        if ( empty( $taxonomies ) ) {

          if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {

            error_log( sprintf( 'Tag Groups: Wrong taxonomy in shortcode "tag_groups_cloud": %s', $taxonomy ) );

          }

          return '';

        }

      }


      /**
      * Reduce the risk of interference from other plugins
      */
      remove_all_filters( 'get_terms_orderby' );
      remove_all_filters( 'list_terms_exclusions' );
      remove_all_filters( 'get_terms' );

      $posttags = get_terms( $taxonomies, array( 'hide_empty' => $hide_empty, 'orderby' => $orderby, 'order' => $order, 'include' => $include_terms, 'exclude' => $exclude_terms ) );

      /**
      * In case of errors: return empty array
      */
      if ( ! is_array( $posttags ) ) {

        $posttags = array();

      }

      $div_id_output = $div_id ? ' id="' . sanitize_html_class( $div_id ) . '"' : '';

      $div_class_output = $div_class ? ' class="' . sanitize_html_class( $div_class ) . '"' : '';

      $ul_class_output = $ul_class ? ' class="' . sanitize_html_class( $ul_class ) . '"' : '';

      if ( $include !== '' ) {

        $include_array = explode( ',', str_replace( ' ', '', $include ) );

      } else {

        $include_array = $tag_group_ids;

      }

      if ( $separator_size < 1 ) {

        $separator_size = 12;

      } else {

        $separator_size = (int) $separator_size;

      }

      /*
      *  applying parameter tags_post_id
      */
      if ( $tags_post_id < -1 ) {

        $tags_post_id = -1;

      }

      if ( $tags_post_id == 0 ) {

        $tags_post_id = get_the_ID();

      }

      if ( $tags_post_id > 0 ) {

        /*
        *  we have a particular post ID
        *  get all tags of this post
        */
        foreach ( $taxonomies as $taxonomy_item ) {

          $terms = get_the_terms( (int) $tags_post_id, $taxonomy_item );

          /*
          *  merging the results of selected taxonomies
          */
          if ( ! empty( $terms ) && is_array( $terms ) ) {

            $post_id_terms = array_merge( $post_id_terms, $terms );

            /**
            *  get all involved groups
            */
            if ( class_exists( 'TagGroups_Premium_Post' ) ) {

              $post_o = new TagGroups_Premium_Post( $tags_post_id );

              $terms_by_group_tmp = $post_o->get_terms_by_group( null, $group );

              foreach ( $terms_by_group_tmp as $key => $value ) {

                if ( ! isset( $include_tags_post_id_groups[ $key ] ) ) {

                  $include_tags_post_id_groups[ $key ] = array();

                }

                $include_tags_post_id_groups[ $key ] = array_merge( $include_tags_post_id_groups[ $key ], $value );

              }

            }

          }

        }


        /*
        *  clean all others from $posttags
        */
        foreach ( $posttags as $key => $tag ) {

          $found = false;

          foreach ( $post_id_terms as $id_tag ) {

            if ( $tag->term_id == $id_tag->term_id ) {

              $found = true;

              break;
            }
          }

          if ( ! empty( $assigned_class ) ) {

            /*
            *  Keep all terms but mark for different styling
            */
            if ( $found ) {

              $assigned_terms[ $tag->term_id ] = true;

            }

          } else {

            /*
            *  Remove unused terms.
            */
            if ( ! $found ) {

              unset( $posttags[ $key ] );

            }

          }

        }

      }

      /*
      *  applying parameter groups_post_id
      */
      if ( $groups_post_id < -1 ) {

        $groups_post_id = -1;

      }

      if ( $groups_post_id == 0 ) {

        $groups_post_id = get_the_ID();

      }

      if ( $groups_post_id > 0 ) {

        /*
        *  get all tags of this post
        */
        foreach ( $taxonomies as $taxonomy_item ) {

          $terms = get_the_terms( (int) $groups_post_id, $taxonomy_item );

          if ( ! empty( $terms ) && is_array( $terms ) ) {

            $post_id_terms = array_merge( $post_id_terms, $terms );

          }

        }


        /*
        *  get all involved groups, append them to $include
        */
        if ( $post_id_terms ) {

          foreach ( $post_id_terms as $term ) {

            $term_o = new TagGroups_Term( $term );

            if ( ! $term_o->is_in_group( $include_array ) ) {

              $include_array = array_merge( $include_array, $term_o->get_groups() );

            }

          }

        }

      }

      // apply sorting that cannot be done on database level
      if ( $natural_sorting ) {

        $posttags = self::natural_sorting( $posttags, $order );

      } elseif ( 'random' == $orderby ) {

        $posttags = self::random_sorting( $posttags );

      }

      if ( $return_array ) {

        /*
        *  return tags as array
        */
        $output = array();

        $min_max = self::determine_min_max( $posttags, $amount, $tag_group_ids, $include_tags_post_id_groups );

        $post_counts = array();

        if ( class_exists( 'TagGroups_Premium_Term' ) && method_exists( 'TagGroups_Premium_Term', 'get_post_counts' ) ) {

          $post_counts = TagGroups_Premium_Term::get_post_counts();

        }

        for ( $i = $start_group; $i <= $group->get_max_position(); $i++ ) {

          if ( $show_all_groups || in_array( $data[ $i ]['term_group'], $include_array ) ) {

            if ( $i == 0 ) {

              $output[ $i ]['name'] = $not_assigned_name;

            } else {

              $output[ $i ]['name'] = self::translate_string_wpml( 'Group Label ID ' . $data[ $i ]['term_group'], $data[ $i ]['label'] );

            }

            $output[ $i ]['term_group'] = $data[ $i ]['term_group'];

            $count_amount = 0;


            foreach ( $posttags as $tag ) {

              if ( ! empty( $amount ) && $count_amount >= $amount ) {

                break;

              }

              $term_o = new TagGroups_Term( $tag );

              if ( $term_o->is_in_group( $data[ $i ]['term_group'] ) ) {


                if ( empty( $include_tags_post_id_groups ) || in_array( $tag->term_id, $include_tags_post_id_groups[ $data[ $i ]['term_group'] ] ) ) {

                  // check if tag has posts for this particular group
                  if ( ! empty( $post_counts ) ) {

                    if ( isset( $post_counts[ $tag->term_id ][ $data[ $i ]['term_group'] ] ) ) {

                      $tag_count = $post_counts[ $tag->term_id ][ $data[ $i ]['term_group'] ];

                    } else {

                      $tag_count = 0;

                    }

                  } else {

                    $tag_count = $tag->count;

                  }

                  if ( ! $hide_empty || $tag_count > 0 ) {

                    $output[ $i ]['tags'][ $count_amount ]['term_id'] = $tag->term_id;

                    $output[ $i ]['tags'][ $count_amount ]['link'] = get_term_link( $tag );

                    $output[ $i ]['tags'][ $count_amount ]['description'] = $tag->description;

                    $output[ $i ]['tags'][ $count_amount ]['count'] = $tag_count;

                    $output[ $i ]['tags'][ $count_amount ]['slug'] = $tag->slug;

                    $output[ $i ]['tags'][ $count_amount ]['name'] = $tag->name;

                    $output[ $i ]['tags'][ $count_amount ]['tg_font_size'] = self::font_size( $tag_count, $min_max[ $data[ $i ]['term_group'] ]['min'], $min_max[ $data[ $i ]['term_group'] ]['max'], $smallest, $largest );

                    if ( ! empty( $assigned_class ) ) {

                      $output[ $i ]['tags'][ $count_amount ]['assigned'] = $assigned_terms[ $tag->term_id ];
                    }

                    $count_amount++;

                  }

                }

              }

            }

            $output[ $i ]['amount'] = $count_amount;

          }

        }

        // create a cached version (premium plugin)
        do_action( 'tag_groups_hook_cache_set', $key, $output );

        return $output;

      } else {
        /*
        *  return as html (in the shape of a tabbed cloud)
        */
        $html = '<div' . $div_id_output . $div_class_output . '>'; // entire wrapper

        /*
        *  render the tabs
        */
        if ( $show_tabs ) {

          for ( $i = $start_group; $i <= $group->get_max_position(); $i++ ) {

            if ( $show_all_groups || in_array( $data[ $i ]['term_group'], $include_array ) ) {

              if ( $i == 0 ) {

                $group_name = $not_assigned_name;

              } else {

                $group_name = self::translate_string_wpml( 'Group Label ID ' . $data[ $i ]['term_group'], $data[ $i ]['label'] );

              }

              $html_tabs[ $i ] = '<li><a href="#tabs-1' . $i . '" >' . htmlentities( $group_name, ENT_QUOTES, "UTF-8" ) . '</a></li>';
            }
          }

        }

        /*
        *  render the tab content
        */
        $min_max = self::determine_min_max( $posttags, $amount, $tag_group_ids, $include_tags_post_id_groups );

        $post_counts = array();

        if ( class_exists( 'TagGroups_Premium_Term' ) && method_exists( 'TagGroups_Premium_Term', 'get_post_counts' ) ) {

          $post_counts = TagGroups_Premium_Term::get_post_counts();

        }

        for ( $i = $start_group; $i <= $group->get_max_position(); $i++ ) {

          $count_amount = 0;

          if ( $show_all_groups || in_array( $data[ $i ]['term_group'], $include_array ) ) {

            $html_tags[ $i ] = '';

            foreach ( $posttags as $tag ) {

              $other_tag_classes = '';

              $description = '';

              if ( ! empty( $amount ) && $count_amount >= $amount ) {

                break;

              }

              $term_o = new TagGroups_Term( $tag );

              if ( $term_o->is_in_group( $data[ $i ]['term_group'] ) ) {


                if ( empty( $include_tags_post_id_groups ) || in_array( $tag->term_id, $include_tags_post_id_groups[ $data[ $i ]['term_group'] ] ) ) {

                  // check if tag has posts for this particular group
                  if ( ! empty( $post_counts ) ) {

                    if ( isset( $post_counts[ $tag->term_id ][ $data[ $i ]['term_group'] ] ) ) {

                      $tag_count = $post_counts[ $tag->term_id ][ $data[ $i ]['term_group'] ];

                    } else {

                      $tag_count = 0;

                    }

                  } else {

                    $tag_count = $tag->count;

                  }

                  if ( ! $hide_empty || $tag_count > 0 ) {

                    $tag_link = get_term_link( $tag );

                    if ( ! empty( $link_append ) ) {

                      if ( mb_strpos( $tag_link, '?' ) === false ) {

                        $tag_link = esc_url( $tag_link . '?' . $link_append );

                      } else {

                        $tag_link = esc_url( $tag_link . '&' . $link_append );

                      }
                    }

                    /**
                    * Append a parameter to separate terms by group on the archive page
                    */
                    if ( class_exists( 'TagGroups_Premium_Term' ) && $add_premium_filter ) {

                      if ( mb_strpos( $tag_link, '?' ) === false ) {

                        $tag_link = esc_url( $tag_link . '?term_group=' . $data[ $i ]['term_group'] . '&term_id=' . $tag->term_id );

                      } else {

                        $tag_link = esc_url( $tag_link . '&term_group=' . $data[ $i ]['term_group'] . '&term_id=' . $tag->term_id );

                      }

                    }

                    $font_size = self::font_size( $tag_count, $min_max[ $data[ $i ]['term_group'] ]['min'], $min_max[ $data[ $i ]['term_group'] ]['max'], $smallest, $largest );

                    $font_size_separator = $adjust_separator_size ? $font_size : $separator_size;

                    if ( $count_amount > 0 && ! empty( $separator ) ) {

                      $html_tags[ $i ] .= '<span style="font-size:' . $font_size_separator . 'px">' . $separator . '</span> ';

                    }

                    if ( ! empty( $assigned_class ) ) {

                      if ( ! empty( $assigned_terms[ $tag->term_id ] ) ) {

                        $other_tag_classes = ' ' . $assigned_class . '_1';

                      } else {

                        $other_tag_classes = ' ' . $assigned_class . '_0';

                      }

                    }

                    if ( ! empty( $custom_title ) ) {

                      $description = ! empty( $tag->description ) ? esc_html( $tag->description ) : '';

                      $title = preg_replace("/(\{description\})/", $description, $custom_title);

                      $title = preg_replace("/(\{count\})/", $tag_count, $title);

                    } else {
                      // description and number
                      $description = ! empty( $tag->description ) ? esc_html( $tag->description ) . ' ' : '';

                      $tag_count_brackets = $show_tag_count ? '(' . $tag_count . ')' : '';

                      $title = $description . $tag_count_brackets;
                    }

                    // replace placeholders in prepend and append
                    if ( ! empty( $prepend ) ) {

                      $prepend_output = preg_replace("/(\{count\})/", $tag_count, $prepend );

                    }

                    if ( ! empty( $append ) ) {

                      $append_output = preg_replace("/(\{count\})/", $tag_count, $append );

                    }

                    // adding link target
                    $link_target_html = ! empty( $link_target ) ? 'target="' . $link_target . '"' : '';

                    // assembling a tag
                    $html_tags[ $i ] .= '<span class="tag-groups-tag' . $other_tag_classes . '"><a href="' . $tag_link . '" ' . $link_target_html . ' title="' . $title . '"  class="' . $tag->slug . '">';

                    if ( ! empty( $prepend_output ) ) {

                      $html_tags[ $i ] .= '<span class="tag-groups-prepend" style="font-size:' . $font_size . 'px">' . htmlentities( $prepend_output, ENT_QUOTES, "UTF-8" ) . '</span>';

                    }

                    $html_tags[ $i ] .= '<span style="font-size:' . $font_size . 'px">' . $tag->name . '</span>';

                    if ( ! empty( $append_output ) ) {

                      $html_tags[ $i ] .= '<span class="tag-groups-append" style="font-size:' . $font_size . 'px">' . htmlentities( $append_output, ENT_QUOTES, "UTF-8" ) . '</span>';

                    }

                    $html_tags[ $i ] .= '</a></span> ';

                    $count_amount++;

                  }

                }

              }

            }

          }

          if ( $hide_empty_tabs && ! $count_amount ) {

            unset( $html_tabs[ $i ] );

            unset( $html_tags[ $i ] );

          } elseif ( isset( $html_tags[ $i ] ) ) {

            $html_tags[ $i ] = '<div id="tabs-1' . $i . '">' .  $html_tags[ $i ] . '</div>';

          }
        }

        /*
        * assemble tabs
        */
        $html .= '<ul' . $ul_class_output . '>' . implode( "\n", $html_tabs ) . '</ul>';

        /*
        * assemble tags
        */
        $html .= implode( "\n", $html_tags );

        $html .= '</div>'; // entire wrapper

        $html .= self::custom_js_tabs( $div_id, $mouseover, $collapsible, $active );

        // create a cached version (premium plugin)
        do_action( 'tag_groups_hook_cache_set', $key, $html );

        return $html;
      }

    }


    /**
    *
    * Render the accordion tag cloud
    *
    * @param array $atts
    * @return string
    */
    static function tag_groups_accordion( $atts = array() ) {

      // create key that depends on settings
      $key_array = $atts;

      if ( isset( $key_array['div_id'] ) ) {

        unset( $key_array['div_id'] );

      }

      $key = md5( 'accordion' . serialize( $key_array ) );

      // check for a cached version (premium plugin)
      $html = apply_filters( 'tag_groups_hook_cache_get', false, $key );

      if ( $html ) {

        return $html;

      }

      $post_id_terms = array();

      $assigned_terms = array();

      $include_tags_post_id_groups = array();

      $group = new TagGroups_Group();

      $data = $group->get_all_with_position_as_key();

      $tag_group_ids = $group->get_all_ids();

      extract( shortcode_atts( array(
        'active' => null,
        'adjust_separator_size' => true,
        'add_premium_filter' => 1,
        'amount' => 0,
        'append' => '',
        'assigned_class' => null,
        'collapsible' => null,
        'custom_title' => null,
        'div_class' => 'tag-groups-cloud', // change for different themes tabs vs. accordion
        'div_id' => 'tag-groups-cloud-accordion-' . uniqid(),
        'exclude_terms' => '',
        'groups_post_id' => -1,
        'header_class' => '',
        'heightstyle' => 'content',
        'hide_empty_content' => false,
        'hide_empty' => true,
        'include' => '',
        'include_terms' => '',
        'inner_div_class' => '',
        'largest' => 22,
        'link_target' => '',
        'link_append' => '',
        'mouseover' => null,
        'not_assigned_name' => 'not assigned',
        'order' => 'ASC',
        'orderby' => 'name',
        'prepend' => '',
        'separator_size' => 12,
        'separator' => '',
        'show_not_assigned' => false,
        'show_all_groups' => false,
        'show_accordion' => 1,
        'show_tag_count' => true,
        'smallest' => 12,
        'tags_post_id' => -1,
        'taxonomy' => null
      ), $atts ) );

      if ( 'natural' == $orderby ) {

        $natural_sorting = true;
        $orderby = 'name';

      } else {

        $natural_sorting = false;

      }

      if ( $smallest < 1 ) {

        $smallest = 1;

      }

      if ( $largest < $smallest ) {

        $largest = $smallest;

      }

      if ( $amount < 0 ) {

        $amount = 0;

      }

      if ( ! empty( $link_append ) && mb_strpos( $link_append, '?' ) === 0 ) {

        $link_append = mb_substr( $link_append, 1 );

      }

      if ( ! empty( $show_not_assigned ) ) {

        $start_group = 0;

      } else {

        $start_group = 1;

      }

      if ( isset( $taxonomy ) ) {

        if ( empty( $taxonomy ) ) {

          unset( $taxonomy );

        } else {

          $taxonomy_array = explode( ',', $taxonomy );

          $taxonomy_array = array_filter( array_map( 'trim', $taxonomy_array ) );

        }
      }


      $taxonomies = TagGroups_Taxonomy::get_enabled_taxonomies();

      if ( ! empty( $taxonomy_array ) ) {

        $taxonomies = array_intersect( $taxonomies, $taxonomy_array );

        if ( empty( $taxonomies ) ) {

          if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {

            error_log( sprintf( 'Tag Groups: Wrong taxonomy in shortcode "tag_groups_accordion": %s', $taxonomy ) );

          }

          return '';

        }

      }

      /**
      * Reduce the risk of interference from other plugins
      */
      remove_all_filters( 'get_terms_orderby' );
      remove_all_filters( 'list_terms_exclusions' );
      remove_all_filters( 'get_terms' );

      $posttags = get_terms( $taxonomies, array( 'hide_empty' => $hide_empty, 'orderby' => $orderby, 'order' => $order, 'include' => $include_terms, 'exclude' => $exclude_terms ) );

      /**
      * In case of errors: return empty array
      */
      if ( ! is_array( $posttags ) ) {

        $posttags = array();

      }

      $div_id_output = $div_id ? ' id="' . sanitize_html_class( $div_id ) . '"' : '';

      $div_class_output = $div_class ? ' class="' . sanitize_html_class( $div_class ) . '"' : '';

      $header_class_output = $header_class ? ' class="' . sanitize_html_class( $header_class ) . '"' : '';

      $inner_div_class_output = $inner_div_class ? ' class="' . sanitize_html_class( $inner_div_class ) . '"' : '';

      if ( $include !== '' ) {

        $include_array = explode( ',', str_replace( ' ', '', $include ) );

      }  else {

        $include_array = $tag_group_ids;

      }

      if ( $separator_size < 1 ) {

        $separator_size = 12;

      } else {

        $separator_size = (int) $separator_size;

      }

      /*
      *  applying parameter tags_post_id
      */
      if ( $tags_post_id < -1 ) {

        $tags_post_id = -1;

      }

      if ( $tags_post_id == 0 ) {

        $tags_post_id = get_the_ID();

      }

      if ( $tags_post_id > 0 ) {

        /*
        *  we have a particular post ID
        *  get all tags of this post
        */
        foreach ( $taxonomies as $taxonomy_item ) {

          $terms = get_the_terms( (int) $tags_post_id, $taxonomy_item );

          /*
          *  merging the results of selected taxonomies
          */
          if ( ! empty( $terms ) && is_array( $terms ) ) {

            $post_id_terms = array_merge( $post_id_terms, $terms );

            /**
            *  get all involved groups
            */
            if ( class_exists( 'TagGroups_Premium_Post' ) ) {

              $post_o = new TagGroups_Premium_Post( $tags_post_id );

              $terms_by_group_tmp = $post_o->get_terms_by_group( null, $group );

              foreach ( $terms_by_group_tmp as $key => $value ) {

                if ( ! isset( $include_tags_post_id_groups[ $key ] ) ) {

                  $include_tags_post_id_groups[ $key ] = array();

                }

                $include_tags_post_id_groups[ $key ] = array_merge( $include_tags_post_id_groups[ $key ], $value );

              }

            }

          }

        }


        /*
        *  clean all others from $posttags
        */
        foreach ( $posttags as $key => $tag ) {

          $found = false;

          foreach ( $post_id_terms as $id_tag ) {

            if ( $tag->term_id == $id_tag->term_id ) {

              $found = true;

              break;
            }
          }

          if ( ! empty( $assigned_class ) ) {

            /*
            *  Keep all terms but mark for different styling
            */
            if ( $found ) {

              $assigned_terms[ $tag->term_id ] = true;

            }

          } else {

            /*
            *  Remove unused terms.
            */
            if ( ! $found ) {

              unset( $posttags[ $key ] );

            }

          }

        }

      }


      /*
      *  applying parameter groups_post_id
      */
      if ( $groups_post_id < -1 ) {

        $groups_post_id = -1;

      }

      if ( $groups_post_id == 0 ) {

        $groups_post_id = get_the_ID();

      }

      if ( $groups_post_id > 0 ) {

        /*
        *  get all tags of this post
        */
        foreach ( $taxonomies as $taxonomy_item ) {

          $terms = get_the_terms( (int) $groups_post_id, $taxonomy_item );

          if ( ! empty( $terms ) && is_array( $terms ) ) {

            $post_id_terms = array_merge( $post_id_terms, $terms );

          }

        }


        /*
        *  get all involved groups, append them to $include
        */
        if ( $post_id_terms ) {

          foreach ( $post_id_terms as $term ) {

            $term_o = new TagGroups_Term( $term );

            if ( ! $term_o->is_in_group( $include_array ) ) {

              $include_array = array_merge( $include_array, $term_o->get_groups() );

            }

          }

        }

      }

      // apply sorting that cannot be done on database level
      if ( $natural_sorting ) {

        $posttags = self::natural_sorting( $posttags, $order );

      } elseif ( 'random' == $orderby ) {

        $posttags = self::random_sorting( $posttags );

      }

      /*
      *  return as html (in the shape of clouds in an accordion)
      */
      $min_max = self::determine_min_max( $posttags, $amount, $tag_group_ids, $include_tags_post_id_groups );

      $post_counts = array();

      if ( class_exists( 'TagGroups_Premium_Term' ) && method_exists( 'TagGroups_Premium_Term', 'get_post_counts' ) ) {

        $post_counts = TagGroups_Premium_Term::get_post_counts();

      }

      $html = '<div' . $div_id_output . $div_class_output . '>';

      for ( $i = $start_group; $i <= $group->get_max_position(); $i++ ) {

        $html_header = '';

        $html_tags = '';

        $count_amount = 0;


        if ( $show_all_groups || in_array( $data[ $i ]['term_group'], $include_array ) ) {

          /*
          *  render the accordion headers
          */
          if ( $show_accordion == 1 ) {

            if ( $i == 0 ) {

              $group_name = $not_assigned_name;

            } else {

              $group_name = self::translate_string_wpml( 'Group Label ID ' . $data[ $i ]['term_group'], $data[ $i ]['label'] );

            }

            $html_header .= '<h3' . $header_class_output . '>'
            . htmlentities( $group_name, ENT_QUOTES, "UTF-8" )
            . '</h3>';
          }

          /*
          *  render the accordion content
          */
          foreach ( $posttags as $tag ) {

            $other_tag_classes = '';

            $description = '';

            if ( ! empty( $amount ) && $count_amount >= $amount ) {

              break;

            }

            $term_o = new TagGroups_Term( $tag );

            if ( $term_o->is_in_group( $data[ $i ]['term_group'] ) ) {

              if ( empty( $include_tags_post_id_groups ) || in_array( $tag->term_id, $include_tags_post_id_groups[ $data[ $i ]['term_group'] ] ) ) {

                // check if tag has posts for this particular group
                if ( ! empty( $post_counts ) ) {

                  if ( isset( $post_counts[ $tag->term_id ][ $data[ $i ]['term_group'] ] ) ) {

                    $tag_count = $post_counts[ $tag->term_id ][ $data[ $i ]['term_group'] ];

                  } else {

                    $tag_count = 0;

                  }

                } else {

                  $tag_count = $tag->count;

                }

                if ( ! $hide_empty || $tag_count > 0 ) {

                  $tag_link = get_term_link( $tag );

                  if ( ! empty( $link_append ) ) {

                    if ( mb_strpos( $tag_link, '?' ) === false ) {

                      $tag_link = esc_url( $tag_link . '?' . $link_append );

                    } else {

                      $tag_link = esc_url( $tag_link . '&' . $link_append );

                    }
                  }

                  /**
                  * Append a parameter to separate terms by group on the archive page
                  */
                  if ( class_exists( 'TagGroups_Premium_Term' ) && $add_premium_filter ) {

                    if ( mb_strpos( $tag_link, '?' ) === false ) {

                      $tag_link = esc_url( $tag_link . '?term_group=' . $data[ $i ]['term_group'] . '&term_id=' . $tag->term_id );

                    } else {

                      $tag_link = esc_url( $tag_link . '&term_group=' . $data[ $i ]['term_group'] . '&term_id=' . $tag->term_id );

                    }

                  }

                  $font_size = self::font_size( $tag_count, $min_max[ $data[ $i ]['term_group'] ]['min'], $min_max[ $data[ $i ]['term_group'] ]['max'], $smallest, $largest );

                  $font_size_separator = $adjust_separator_size ? $font_size : $separator_size;

                  if ( $count_amount > 0 && ! empty( $separator ) ) {

                    $html_tags .= '<span style="font-size:' . $font_size_separator . 'px">' . $separator . '</span> ';

                  }

                  if ( ! empty( $assigned_class ) ) {

                    if ( ! empty( $assigned_terms[ $tag->term_id ] ) ) {

                      $other_tag_classes = ' ' . $assigned_class . '_1';

                    } else {

                      $other_tag_classes = ' ' . $assigned_class . '_0';

                    }
                  }

                  if ( ! empty( $custom_title ) ) {

                    $description = ! empty( $tag->description ) ? esc_html( $tag->description ) : '';

                    $title = preg_replace("/(\{description\})/", $description, $custom_title);

                    $title = preg_replace("/(\{count\})/", $tag_count, $title);

                  } else {
                    // description and number
                    $description = ! empty( $tag->description ) ? esc_html( $tag->description ) . ' ' : '';

                    $tag_count_brackets = $show_tag_count ? '(' . $tag_count . ')' : '';

                    $title = $description . $tag_count_brackets;
                  }

                  // replace placeholders in prepend and append
                  if ( ! empty( $prepend ) ) {

                    $prepend_output = preg_replace("/(\{count\})/", $tag_count, $prepend );

                  }

                  if ( ! empty( $append ) ) {

                    $append_output = preg_replace("/(\{count\})/", $tag_count, $append );

                  }

                  // adding link target
                  $link_target_html = ! empty( $link_target ) ? 'target="' . $link_target . '"' : '';

                  // assembling a tag
                  $html_tags .= '<span class="tag-groups-tag' . $other_tag_classes . '"><a href="' . $tag_link . '" ' . $link_target_html . ' title="' . $title . '"  class="' . $tag->slug . '">';

                  if ( ! empty( $prepend_output ) ) {

                    $html_tags .= '<span class="tag-groups-prepend" style="font-size:' . $font_size . 'px">' . htmlentities( $prepend_output, ENT_QUOTES, "UTF-8" ) . '</span>';

                  }

                  $html_tags .= '<span style="font-size:' . $font_size . 'px">' . $tag->name . '</span>';

                  if ( ! empty( $append_output ) ) {

                    $html_tags .= '<span class="tag-groups-append" style="font-size:' . $font_size . 'px">' . htmlentities( $append_output, ENT_QUOTES, "UTF-8" ) . '</span>';

                  }

                  $html_tags .= '</a></span> ';

                  $count_amount++;

                }

              }

            }

          }

        }

        if ( ! empty( $html_header ) && ( ! $hide_empty_content || $count_amount ) ) {

          $html .= $html_header . '<div' . $inner_div_class_output . '>' . $html_tags . '</div>';
        }
      }

      /*
      * Closing the accordion
      */
      $html .= '</div>';

      $html .= self::custom_js_accordion( $div_id, $mouseover, $collapsible, $active, $heightstyle );

      // create a cached version (premium plugin)
      do_action( 'tag_groups_hook_cache_set', $key, $html );

      return $html;

    }



    /**
    * If WPML is installed: return translation; otherwise return original
    *
    * @param type $name
    * @param type $string
    * @return type
    */
    static function translate_string_wpml( $name, $string )
    {

      if ( function_exists( 'icl_t' ) ) {
        return icl_t( 'tag-groups', $name, $string );
      } else {
        return $string;
      }

    }


    /**
    * Calculates the font size for the cloud tag for a particular tag ($min, $max and $size with same unit, e.g. pt.)
    *
    * @param int $count
    * @param int $min
    * @param int $max
    * @param int $smallest
    * @param int $largest
    * @return int
    */
    static function font_size( $count, $min, $max, $smallest, $largest )
    {

      if ( $max > $min ) {

        $size = round( ( $count - $min ) * ( $largest - $smallest ) / ( $max - $min ) + $smallest );

      } else {

        $size = round( $smallest );

      }

      return $size;

    }



    /**
    * A piece of script for the tabs to work, including options, for each individual cloud
    *
    * @param type $id
    * @param type $option_mouseover
    * @param type $option_collapsible
    * @return string
    */
    static function custom_js_tabs( $id = null, $option_mouseover = null, $option_collapsible = null, $option_active = null )
    {

      $options = array();

      if ( isset( $option_mouseover ) ) {

        if ( $option_mouseover ) {

          $options[] = 'event: "mouseover"';

        }

      } else {

        if ( get_option( 'tag_group_mouseover', '' ) ) {

          $options[] = 'event: "mouseover"';

        }

      }

      if ( isset( $option_collapsible ) ) {

        if ( $option_collapsible ) {

          $options[] = 'collapsible: true';

        }

      } else {

        if ( get_option( 'tag_group_collapsible', '' ) ) {

          $options[] = 'collapsible: true';

        }

      }

      if ( isset( $option_active ) ) {

        if ( $option_active ) {

          $options[] = 'active: true';

        } else {

          $options[] = 'active: false';

        }

      }

      if ( empty( $options ) ) {

        $options_serialized = '';

      } else {

        $options_serialized = "{\n" . implode( ",\n", $options ) . "\n}";

      }

      if ( empty( $id ) ) {

        $id = 'tag-groups-cloud-tabs';

      } else {

        $id = sanitize_html_class( $id );

      }

      $html = '
      <!-- begin Tag Groups plugin -->
      <script type="text/javascript">
      jQuery(function() {
        if (jQuery.isFunction(jQuery.fn.tabs) ) {
          jQuery( "#' . $id . '" ).tabs(' . $options_serialized . ');
        }
      });
      </script>
      <!-- end Tag Groups plugin -->
      ';

      return $html;

    }


    /**
    * A piece of script for the tabs to work, including options, for each individual cloud
    *
    * @param type $id
    * @param type $option_mouseover
    * @param type $option_collapsible
    * @return string
    */
    static function custom_js_accordion( $id = null, $option_mouseover = null, $option_collapsible = null, $option_active = null, $heightstyle = null )
    {

      $options = array();

      if ( isset( $option_mouseover ) ) {

        if ( $option_mouseover ) {

          $options[] = 'event: "mouseover"';

        }

      } else {

        if ( get_option( 'tag_group_mouseover', '' ) ) {

          $options[] = 'event: "mouseover"';

        }

      }

      if ( isset( $option_collapsible ) ) {

        if ( $option_collapsible ) {

          $options[] = 'collapsible: true';

        }

      } else {

        if ( get_option( 'tag_group_collapsible', '' ) ) {

          $options[] = 'collapsible: true';

        }

      }

      if ( ! empty( $heightstyle ) ) {

        $options[] = 'heightStyle: "' . sanitize_title( $heightstyle ) . '"';

      }

      if ( isset( $option_active ) ) {

        if ( $option_active ) {

          $options[] = 'active: true';

        } else {

          $options[] = 'active: false';

        }

      }


      if ( empty( $options ) ) {

        $options_serialized = '';

      } else {

        $options_serialized = "{\n" . implode( ",\n", $options ) . "\n}";

      }

      if ( !isset( $id ) ) {

        $id = 'tag-groups-cloud-accordion';

      } else {

        $id = sanitize_html_class( $id );

      }

      $html = '
      <!-- begin Tag Groups plugin -->
      <script type="text/javascript">
      jQuery(function() {
        if (jQuery.isFunction(jQuery.fn.accordion) ) {
          jQuery( "#' . $id . '" ).accordion(' . $options_serialized . ');
        }
      });
      </script>
      <!-- end Tag Groups plugin -->
      ';

      return $html;

    }

    /*
    *  find minimum and maximum of quantity of posts for each tag
    */
    static function determine_min_max( $tags, $amount, $tag_group_ids, $include_tags_post_id_groups = null ) {

      $min_max = array();

      $count_amount = array();

      foreach ( $tag_group_ids as $tag_group_id ) {

        $count_amount[ $tag_group_id ] = 0;

        $min_max[ $tag_group_id ]['min'] = 0;

        $min_max[ $tag_group_id ]['max'] = 0;

      }

      if ( empty( $tags ) || ! is_array( $tags ) ) {

        return $min_max;

      }

      foreach ( $tags as $tag ) {

        $term_o = new TagGroups_Term( $tag );

        if ( $term_o->is_in_group( $tag_group_ids ) ) {

          // check if tag has posts for this particular group
          if ( ! empty( $post_counts ) ) {

            if ( isset( $post_counts[ $tag->term_id ][ $data[ $i ]['term_group'] ] ) ) {

              $tag_count = $post_counts[ $tag->term_id ][ $data[ $i ]['term_group'] ];

            } else {

              $tag_count = 0;

            }

          } else {

            $tag_count = $tag->count;

          }

          if ( $tag_count > 0 ) {

            /**
            * Use only groups that are in the list
            */
            $term_groups = array_intersect( $term_o->get_groups(), $tag_group_ids );

            foreach ( $term_groups as $term_group ){

              if ( 0 == $amount || $count_amount[ $term_group ] < $amount ) {

                if ( empty( $include_tags_post_id_groups ) || in_array( $tag->term_id, $include_tags_post_id_groups[ $term_group ] ) ) {

                  if ( isset( $min_max[ $term_group ]['max'] ) && $tag_count > $min_max[ $term_group ]['max'] ) {

                    $min_max[ $term_group ]['max'] = $tag_count;

                  }

                  if ( isset( $min_max[ $term_group ]['min'] ) && ( $tag_count < $min_max[ $term_group ]['min'] || 0 == $min_max[ $term_group ]['min'] ) ) {

                    $min_max[ $term_group ]['min'] = $tag_count;

                  }

                  $count_amount[ $term_group ]++;

                }

              }

            }

          }

        }

      }

      return $min_max;

    }


    /*
    *  find minimum and maximum of quantity of posts for each tag
    * DEPRECATED since version 0.31
    */
    static function min_max( $tags, $amount, $tag_group_id ) {

      $count_amount = 0;

      $max = 0;

      $min = 0;

      foreach ( $tags as $tag ) {

        if ( $amount > 0 && $count_amount >= $amount ) {

          break;

        }

        if ( $tag->term_group == $tag_group_id ) {

          if ( $tag->count > $max ) {

            $max = $tag->count;

          }

          if ( $tag->count < $min || 0 == $min) {

            $min = $tag->count;

          }

          $count_amount++;
        }

      }

      return array(
        'min' => $min,
        'max' => $max
      );

    }


    /**
    * Helper for natural sorting of names
    *
    * Inspired by _wp_object_name_sort_cb
    *
    * @param array $terms
    * @param string $order asc or desc
    * @return array
    */
    static function natural_sorting( $terms, $order )
    {
      $factor = ( 'desc' == strtolower( $order ) ) ? -1 : 1;

      // "use" requires PHP 5.3+
      uasort( $terms, function( $a, $b ) use ( $factor ) {
        return $factor * strnatcasecmp( $a->name, $b->name );
      });

      return $terms;

    }


    /**
    * Helper for (pseudo-)random sorting
    *
    *
    * @param array $terms
    * @return array
    */
    static function random_sorting( $terms )
    {

      uasort( $terms, function( $a, $b ) {
        return 2 * mt_rand( 0, 1 ) - 1;
      });

      return $terms;

    }


    /**
    *
    * Render information about tag groups
    *
    * For <p> wrapping issue check: https://gist.github.com/bitfade/4555047
    *
    * @param array $atts
    * @return string
    */
    static function tag_groups_info( $atts = array() ) {

      // create key that depends on settings
      $key_array = $atts;

      if ( isset( $key_array['html_id'] ) ) {

        unset( $key_array['html_id'] );

      }

      $key = md5( 'tag_groups_info' . serialize( $key_array ) );

      // check for a cached version (premium plugin)
      $html = apply_filters( 'tag_groups_hook_cache_get', false, $key );

      if ( $html ) {

        return $html;

      }

      $active_tag_group_taxonomies = get_option( 'tag_group_taxonomy', array('post_tag') );

      $group = new TagGroups_Group();

      extract( shortcode_atts( array(
        'info'      =>  'number_of_tags',
        'group_id'  => '0',
        'html_id'    => '',
        'html_class' => '',
        'taxonomy' => null,
      ), $atts ) );


      if ( ! empty( $div_id ) ) {

        $id_string = ' id="' . $html_id . '"';

      } else {

        $id_string = '';

      }

      if ( ! empty( $html_class ) ) {

        $class_string = ' class="' . $css_class . '"';

      } else {

        $class_string = '';

      }

      if ( ! empty( $taxonomy ) ) {

        $taxonomy_array = explode( ',', $taxonomy );

        $taxonomy_array = array_filter( array_map( 'trim', $taxonomy_array ) );

        if ( ! empty( $taxonomy_array ) ) {

          $tag_group_taxonomies = array_intersect( $active_tag_group_taxonomies, $taxonomy_array );

          if ( empty( $tag_group_taxonomies ) ) {

            if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {

              error_log( sprintf( 'Tag Groups: Wrong taxonomy in shortcode "tag_groups_info": %s', $taxonomy ) );

            }

            return '';

          }

        }

      }

      $term_groups = false;

      $output = '';

      switch ( $info ) {

        case 'number_of_tags':
        /**
        * Show the number of tags
        */

        if ( 'all' == $group_id ) {

          $term_groups = $group->get_all_ids();

        } elseif ( strpos( $group_id, ',' ) !== false ) {

          $term_groups = array_map( 'intval', explode( ',', $group_id ) );

        }

        if ( $term_groups !== false ) {
          /**
          * multiple groups
          */

          $output .= '<table' . $id_string . $class_string . '>';

          foreach ( $term_groups as $term_group ) {

            $group->set_term_group( $term_group );

            if ( $group->exists() ) {

              $output .= '<tr>
              <td class="tag-groups-td-label" title="ID: ' . $term_group . '">';

              $output .= $group->get_label();

              $output .= '</td>
              <td class="tag-groups-td-number">';

              $output .= intval( $group->get_number_of_terms( $active_tag_group_taxonomies ) );

              $output .= '</td>
              </tr>';

            } else {

              if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {

                error_log( sprintf( 'Tag Groups: Unknown group ID in shortcode "tag_groups_info": %s', $term_group ) );

              }

            }

          }

          $output .= '</table>';

        } else {
          /**
          * one group
          */
          $term_group = intval( $group_id );

          $group->set_term_group( $term_group );

          if ( $group->exists() ) {

            $output .= '<span' . $id_string . $class_string . '>';

            $output .= intval( $group->get_number_of_terms( $active_tag_group_taxonomies ) );

            $output .= '</span>';

          } else {

            if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {

              error_log( sprintf( 'Tag Groups: Unknown group ID in shortcode "tag_groups_info": %s', $term_group ) );

            }

          }

        }

        break;

        case 'label':

        if ( strpos( $group_id, ',' ) !== false ) {

          if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {

            error_log( sprintf( 'Tag Groups: Wrong group ID in shortcode "tag_groups_info" with info=label: %s', $group_id ) );

          }

        } else {

          $term_group = intval( $group_id );

          $group->set_term_group( $term_group );

          if ( $group->exists() ) {

            $output = '<span' . $id_string . $class_string . '>';

            $output .= $group->get_label();

            $output .= '</span>';

          } else {

            if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {

              error_log( sprintf( 'Tag Groups: Unknown group ID in shortcode "tag_groups_info": %s', $term_group ) );

            }

          }

        }

        break;

        default:

        $output = '';

        break;
      }

      // create a cached version (premium plugin)
      do_action( 'tag_groups_hook_cache_set', $key, $output );

      return $output;

    }

  } // class

}
