<?php
/**
* @package     Tag Groups
* @author      Christoph Amthor
* @copyright   2018 Christoph Amthor (@ Chatty Mango, chattymango.com)
* @license     GPL-3.0+
*/

if ( ! class_exists('TagGroups_Shortcode_Alphabetical_Index') ) {

  class TagGroups_Shortcode_Alphabetical_Index extends TagGroups_Shortcode {


    /**
    *
    * Render the accordion tag cloud
    *
    * @param array $atts
    * @return string
    */
    static function tag_groups_alphabetical_index( $atts = array() ) {

      global $tag_group_groups, $tag_group_premium_terms, $tag_groups_premium_fs_sdk;

      $shortcode_id = 'tag_groups_alphabetical_index';

      extract( shortcode_atts( array(
        'amount' => 0,
        'append' => '',
        'column_count'  => 2,
        'column_gap'  => '10px',
        'custom_title' => null,
        'div_class' => 'tag-groups-alphabetical-index',
        'div_id' => 'tag-groups-alphabetical-index-' . uniqid(),
        'exclude_letters' => '',
        'exclude_terms' => '',
        'h_level' => 3,
        'header_class' => '',
        'hide_empty_content' => false,
        'hide_empty' => true,
        'include' => '',
        'include_letters' => '',
        'include_terms' => '',
        'keep_together' => 1,
        'largest' => 12,
        'link_target' => '',
        'link_append' => '',
        'order' => 'ASC',
        'orderby' => 'name',
        'prepend' => '',
        'show_tag_count' => true,
        'source' => 'shortcode',
        'smallest' => 12,
        'tags_div_class' => 'tag-groups-alphabetical-index-tags',
        'tags_post_id' => -1,
        'taxonomy' => null
      ), $atts ) );


      $div_id_output = $div_id ? ' id="' . TagGroups_Base::sanitize_html_classes( $div_id ) . '"' : '';

      $div_class_output = $div_class ? ' class="' . TagGroups_Base::sanitize_html_classes( $div_class ) . '"' : '';

      $div_column_output = empty( $column_count ) ? '' : ' style="column-count:' . intval( $column_count ) .'; column-gap:' . $column_gap .'"' ;

      if ( is_array( $atts ) ) {

        asort( $atts );

      }

      $h_level = intval( $h_level );


      if ( $tags_post_id == 0 ) {

        $tags_post_id = get_the_ID();

      }

      $cache_key = md5( 'tag_alphabetical_index' . serialize( $atts ) . serialize( $tags_post_id ) );

      // check for a cached version (premium plugin)
      $html = apply_filters( 'tag_groups_hook_cache_get', false, $cache_key );

      if ( ! $html ) {

        $assigned_terms = array();

        $include_tags_post_id_groups = array();

        $tag_group_ids = $tag_group_groups->get_group_ids();


        if ( 'shortcode' == $source ) {

          $prepend = html_entity_decode( $prepend );

          $append = html_entity_decode( $append );

        }

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

              error_log( sprintf( '[Tag Groups] Wrong taxonomy in shortcode "tag_groups_accordion": %s', $taxonomy ) );

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

          if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {

            error_log( '[Tag Groups] Error retrieving tags with get_terms.' );

          }

        }

        $tags_div_class_output = $tags_div_class ? ' class="' . TagGroups_Base::sanitize_html_classes( $tags_div_class ) . '"' : '';

        if ( $include !== '' ) {

          $include_array = explode( ',', str_replace( ' ', '', $include ) );

        }  else {

          $include_array = $tag_group_ids;

        }

        /*
        *  include
        */
        if ( $posttags ) {

          foreach ( $posttags as $key => $term ) {

            $term_o = new TagGroups_Term( $term->term_id );

            if ( ! $term_o->is_in_group( $include_array ) ) {

              unset( $posttags[ $key ] );

            }

          }

        }

        /*
        *  applying parameter tags_post_id
        */
        if ( $tags_post_id < -1 ) {

          $tags_post_id = -1;

        }

        if ( $tags_post_id > 0 ) {

          $result = self::add_tags_of_post( $tags_post_id, $taxonomies, $posttags, $assigned_class );

          $assigned_terms = $result['assigned_terms'];

          $posttags = $result['posttags'];

          $include_tags_post_id_groups = $result['include_tags_post_id_groups'];

        }


        // apply sorting that cannot be done on database level
        if ( $natural_sorting ) {

          $posttags = self::natural_sorting( $posttags, $order );

        } elseif ( 'random' == $orderby ) {

          $posttags = self::random_sorting( $posttags );

        }

        /**
        * Extract the alphabet
        */
        $alphabet = self::extract_alphabet( $posttags );

        /**
        * Use provided list to include
        */
        $include_letters = str_replace( ' ', '', $include_letters );

        if ( $include_letters != '' ) { // don't use empty()

          $include_letters_array = array();

          $include_letters = mb_strtolower( $include_letters );

          for ( $i = 0; $i < mb_strlen( $include_letters ); $i++ ) {

            $include_letters_array[] = mb_substr( $include_letters, $i, 1 );

          }

          $alphabet = array_intersect( $alphabet, $include_letters_array );

        }

        /**
        * Use provided list to exclude
        */
        $exclude_letters = str_replace( ' ', '', $exclude_letters );

        if ( $exclude_letters != '' ) { // don't use empty()

          $exclude_letters_array = array();

          $exclude_letters = mb_strtolower( $exclude_letters );

          for ( $i = 0; $i < mb_strlen( $exclude_letters ); $i++ ) {

            $exclude_letters_array[] = mb_substr( $exclude_letters, $i, 1 );

          }

          $alphabet = array_diff( $alphabet, $exclude_letters_array );

        }

        $alphabet = self::sort_alphabet( $alphabet );

        $html = '';

        $i = 0;

        foreach ( $alphabet as $letter ) {

          /**
          * Convert to upper case only now; otherwise ß would become SS and affect all cases with S
          */
          $html_tabs[ $i ] = '<h' . $h_level . '>'
          . htmlentities( mb_strtoupper( $letter ), ENT_QUOTES, "UTF-8" )
          . '</h'  . $h_level . '>';

          $i++;

        }

        /*
        *  render the tab content
        */
        $min_max = self::determine_min_max_alphabet( $posttags, $amount, $alphabet );

        $post_counts = array();

        $i = 0;

        foreach ( $alphabet as $letter ) {

          $count_amount = 0;

          $html_tags[ $i ] = '';

          foreach ( $posttags as $key => $tag ) {

            $other_tag_classes = '';

            $description = '';

            if ( ! empty( $amount ) && $count_amount >= $amount ) {

              break;

            }

            if ( self::get_first_letter( $tag->name ) == $letter ) {

              $tag_count = $tag->count;

              if ( ! $hide_empty || $tag_count > 0 ) {

                $tag_link = get_term_link( $tag );

                if ( ! empty( $link_append ) ) {

                  if ( mb_strpos( $tag_link, '?' ) === false ) {

                    $tag_link = esc_url( $tag_link . '?' . $link_append );

                  } else {

                    $tag_link = esc_url( $tag_link . '&' . $link_append );

                  }

                }

                $font_size = self::font_size( $tag_count, $min_max[ $letter ]['min'], $min_max[ $letter ]['max'], $smallest, $largest );

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

                } else {

                  $prepend_output = '';

                }

                if ( ! empty( $append ) ) {

                  $append_output = preg_replace("/(\{count\})/", $tag_count, $append );

                } else {

                  $append_output = '';

                }

                // adding link target
                $link_target_html = ! empty( $link_target ) ? 'target="' . $link_target . '"' : '';

                // assembling a tag
                $html_tags[ $i ] .= '<span class="tag-groups-tag' . $other_tag_classes . '" style="font-size:' . $font_size . 'px"><a href="' . $tag_link . '" ' . $link_target_html . ' title="' . $title . '"  class="' . $tag->slug . '">';

                if ( '' != $prepend_output ) {

                  $html_tags[ $i ] .= '<span class="tag-groups-prepend" style="font-size:' . $font_size . 'px">' . htmlentities( $prepend_output, ENT_QUOTES, "UTF-8" ) . '</span>';

                }

                $html_tags[ $i ] .= apply_filters( 'tag_groups_cloud_tag_outer', '<span class="tag-groups-label" style="font-size:' . $font_size . 'px">' . apply_filters( 'tag_groups_cloud_tag_inner', $tag->name, $tag->term_id, $shortcode_id ) . '</span>', $tag->term_id, $shortcode_id );

                if ( '' != $append_output ) {

                  $html_tags[ $i ] .= '<span class="tag-groups-append" style="font-size:' . $font_size . 'px">' . htmlentities( $append_output, ENT_QUOTES, "UTF-8" ) . '</span>';

                }

                $html_tags[ $i ] .= '</a></span> ';

                $count_amount++;

              }

              unset( $posttags[ $key ] ); // We don't need to look into that one again, since it can only appear under on tab

            }

          }

          if ( $hide_empty_content && ! $count_amount ) {

            unset( $html_tabs[ $i ] );

            unset( $html_tags[ $i ] );

          } elseif ( isset( $html_tags[ $i ] ) ) {

            if ( $keep_together ) {

              $html .= '<div class="tag-groups-keep-together">' . $html_tabs[ $i ] . '<div ' . $tags_div_class_output . '">' .  $html_tags[ $i ] . '</div></div>' . "\n";

            } else {

              $html .= $html_tabs[ $i ] . '<div ' . $tags_div_class_output . '">' .  $html_tags[ $i ] . '</div>' . "\n";

            }



          }

          $i++;

        }


        // create a cached version (premium plugin)
        do_action( 'tag_groups_hook_cache_set', $cache_key, $html );

      }

      $html = '<div' . $div_id_output . $div_class_output . $div_column_output . '>' . $html . '</div>';

      apply_filters( 'tag_groups_cloud_html', $html, $shortcode_id, $atts );

      return $html;

    }


  } // class

}
