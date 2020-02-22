<?php

/**
* @package     Tag Groups
* @author      Christoph Amthor
* @copyright   2018 Christoph Amthor (@ Chatty Mango, chattymango.com)
* @license     GPL-3.0+
*/

if ( !class_exists( 'TagGroups_Shortcode_Tag_List' ) ) {
    class TagGroups_Shortcode_Tag_List extends TagGroups_Shortcode
    {
        /**
         *
         * Render the accordion tag cloud
         *
         * @param array $atts
         * @return string
         */
        static function tag_groups_tag_list( $atts = array() )
        {
            global  $tag_group_groups, $tag_group_premium_terms, $tag_groups_premium_fs_sdk ;
            $shortcode_id = 'tag_groups_tag_list';
            extract( shortcode_atts( array(
                'add_premium_filter' => 0,
                'amount'             => 0,
                'append'             => '',
                'assigned_class'     => null,
                'column_count'       => 2,
                'column_gap'         => '10px',
                'custom_title'       => null,
                'div_class'          => 'tag-groups-tag-list',
                'div_id'             => 'tag-groups-tag-list-' . uniqid(),
                'exclude_terms'      => '',
                'group_in_class'     => 0,
                'groups_post_id'     => -1,
                'h_level'            => 3,
                'header_class'       => '',
                'hide_empty_content' => false,
                'hide_empty'         => true,
                'include'            => '',
                'include_terms'      => '',
                'keep_together'      => 1,
                'largest'            => 12,
                'link_target'        => '',
                'link_append'        => '',
                'not_assigned_name'  => 'not assigned',
                'order'              => 'ASC',
                'orderby'            => 'name',
                'prepend'            => '',
                'show_not_assigned'  => false,
                'show_all_groups'    => false,
                'show_tag_count'     => true,
                'source'             => 'shortcode',
                'smallest'           => 12,
                'tags_div_class'     => 'tag-groups-tag-list-tags',
                'tags_post_id'       => -1,
                'taxonomy'           => null,
            ), $atts ) );
            $div_id_output = ( $div_id ? ' id="' . TagGroups_Base::sanitize_html_classes( $div_id ) . '"' : '' );
            $div_class_output = ( $div_class ? ' class="' . TagGroups_Base::sanitize_html_classes( $div_class ) . '"' : '' );
            $div_column_output = ( empty($column_count) ? '' : ' style="column-count:' . intval( $column_count ) . '; column-gap:' . $column_gap . '"' );
            if ( is_array( $atts ) ) {
                asort( $atts );
            }
            $h_level = intval( $h_level );
            if ( $tags_post_id == 0 ) {
                $tags_post_id = get_the_ID();
            }
            if ( $groups_post_id == 0 ) {
                $groups_post_id = get_the_ID();
            }
            $cache_key = md5( 'tag_list' . serialize( $atts ) . serialize( $tags_post_id ) . serialize( $groups_post_id ) );
            // check for a cached version (premium plugin)
            $html = apply_filters( 'tag_groups_hook_cache_get', false, $cache_key );
            
            if ( !$html ) {
                $assigned_terms = array();
                $include_tags_post_id_groups = array();
                $data = $tag_group_groups->get_all_with_position_as_key();
                $tag_group_ids = $tag_group_groups->get_group_ids_by_position();
                
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
                if ( !empty($link_append) && mb_strpos( $link_append, '?' ) === 0 ) {
                    $link_append = mb_substr( $link_append, 1 );
                }
                
                if ( !empty($show_not_assigned) ) {
                    $start_group = 0;
                } else {
                    $start_group = 1;
                }
                
                if ( isset( $taxonomy ) ) {
                    
                    if ( empty($taxonomy) ) {
                        unset( $taxonomy );
                    } else {
                        $taxonomy_array = explode( ',', $taxonomy );
                        $taxonomy_array = array_filter( array_map( 'trim', $taxonomy_array ) );
                    }
                
                }
                $taxonomies = TagGroups_Taxonomy::get_enabled_taxonomies();
                
                if ( !empty($taxonomy_array) ) {
                    $taxonomies = array_intersect( $taxonomies, $taxonomy_array );
                    
                    if ( empty($taxonomies) ) {
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
                $posttags = get_terms( $taxonomies, array(
                    'hide_empty' => $hide_empty,
                    'orderby'    => $orderby,
                    'order'      => $order,
                    'include'    => $include_terms,
                    'exclude'    => $exclude_terms,
                ) );
                /**
                 * In case of errors: return empty array
                 */
                
                if ( !is_array( $posttags ) ) {
                    $posttags = array();
                    if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                        error_log( '[Tag Groups] Error retrieving tags with get_terms.' );
                    }
                }
                
                $tags_div_class_output = ( $tags_div_class ? ' class="' . TagGroups_Base::sanitize_html_classes( $tags_div_class ) . '"' : '' );
                
                if ( $include !== '' ) {
                    $include_array = explode( ',', str_replace( ' ', '', $include ) );
                } else {
                    $include_array = array();
                }
                
                /*
                 *  applying parameter tags_post_id
                 */
                if ( $tags_post_id < -1 ) {
                    $tags_post_id = -1;
                }
                
                if ( $tags_post_id > 0 ) {
                    $result = self::add_tags_of_post(
                        $tags_post_id,
                        $taxonomies,
                        $posttags,
                        $assigned_class
                    );
                    $assigned_terms = $result['assigned_terms'];
                    $posttags = $result['posttags'];
                    $include_tags_post_id_groups = $result['include_tags_post_id_groups'];
                }
                
                /*
                 *  applying parameter groups_post_id
                 */
                
                if ( $groups_post_id > 0 ) {
                    $include_array = self::add_groups_of_post( $groups_post_id, $taxonomies, $include_array );
                } elseif ( count( $include_array ) == 0 ) {
                    $include_array = $tag_group_ids;
                }
                
                // apply sorting that cannot be done on database level
                
                if ( $natural_sorting ) {
                    $posttags = self::natural_sorting( $posttags, $order );
                } elseif ( 'random' == $orderby ) {
                    $posttags = self::random_sorting( $posttags );
                }
                
                $post_counts = array();
                $min_max = self::determine_min_max(
                    $posttags,
                    $amount,
                    $tag_group_ids,
                    $include_tags_post_id_groups,
                    $data,
                    $post_counts
                );
                $html = '';
                $first = true;
                for ( $i = $start_group ;  $i <= $tag_group_groups->get_max_position() ;  $i++ ) {
                    $html_header = '';
                    $html_tags = '';
                    $count_amount = 0;
                    
                    if ( $show_all_groups || in_array( $data[$i]['term_group'], $include_array ) ) {
                        /*
                         *  headers
                         */
                        
                        if ( $i == 0 ) {
                            $group_name = $not_assigned_name;
                        } else {
                            $group_name = $data[$i]['label'];
                        }
                        
                        $header_class_group = $header_class;
                        if ( !empty($group_in_class) ) {
                            $header_class_group .= ' ' . sanitize_html_class( ' tg_header_group_id_' . $data[$i]['term_group'] ) . ' ' . sanitize_html_class( 'tg_header_group_label_' . strtolower( $data[$i]['label'] ) );
                        }
                        
                        if ( $first ) {
                            $header_class_group .= ' tag-groups-first-group';
                            $first = false;
                        }
                        
                        $header_class_output = ( $header_class_group ? ' class="' . TagGroups_Base::sanitize_html_classes( $header_class_group ) . '"' : '' );
                        $html_header .= '<h' . $h_level . $header_class_output . '>' . htmlentities( $group_name, ENT_QUOTES, "UTF-8" ) . '</h' . $h_level . '>';
                        if ( 'count' == $orderby && !empty($post_counts) ) {
                            // We have to sort the tags according to the post counts for this particular group
                            $posttags = self::sort_within_groups(
                                $posttags,
                                $data[$i]['term_group'],
                                $post_counts,
                                $order
                            );
                        }
                        /*
                         *  render the accordion content
                         */
                        foreach ( $posttags as $tag ) {
                            $other_tag_classes = '';
                            $description = '';
                            if ( !empty($amount) && $count_amount >= $amount ) {
                                break;
                            }
                            $term_o = new TagGroups_Term( $tag );
                            if ( $term_o->is_in_group( $data[$i]['term_group'] ) ) {
                                
                                if ( empty($include_tags_post_id_groups) || in_array( $tag->term_id, $include_tags_post_id_groups[$data[$i]['term_group']] ) ) {
                                    // check if tag has posts for this particular group
                                    
                                    if ( !empty($post_counts) ) {
                                        
                                        if ( isset( $post_counts[$tag->term_id][$data[$i]['term_group']] ) ) {
                                            $tag_count = $post_counts[$tag->term_id][$data[$i]['term_group']];
                                        } else {
                                            $tag_count = 0;
                                        }
                                    
                                    } else {
                                        $tag_count = $tag->count;
                                    }
                                    
                                    
                                    if ( !$hide_empty || $tag_count > 0 ) {
                                        $tag_link = get_term_link( $tag );
                                        if ( !empty($link_append) ) {
                                            
                                            if ( mb_strpos( $tag_link, '?' ) === false ) {
                                                $tag_link = esc_url( $tag_link . '?' . $link_append );
                                            } else {
                                                $tag_link = esc_url( $tag_link . '&' . $link_append );
                                            }
                                        
                                        }
                                        $font_size = self::font_size(
                                            $tag_count,
                                            $min_max[$data[$i]['term_group']]['min'],
                                            $min_max[$data[$i]['term_group']]['max'],
                                            $smallest,
                                            $largest
                                        );
                                        if ( !empty($assigned_class) ) {
                                            
                                            if ( !empty($assigned_terms[$tag->term_id]) ) {
                                                $other_tag_classes = ' ' . $assigned_class . '_1';
                                            } else {
                                                $other_tag_classes = ' ' . $assigned_class . '_0';
                                            }
                                        
                                        }
                                        
                                        if ( !empty($custom_title) ) {
                                            $description = ( !empty($tag->description) ? esc_html( $tag->description ) : '' );
                                            $title = preg_replace( "/(\\{description\\})/", $description, $custom_title );
                                            $title = preg_replace( "/(\\{count\\})/", $tag_count, $title );
                                        } else {
                                            // description and number
                                            $description = ( !empty($tag->description) ? esc_html( $tag->description ) . ' ' : '' );
                                            $tag_count_brackets = ( $show_tag_count ? '(' . $tag_count . ')' : '' );
                                            $title = $description . $tag_count_brackets;
                                        }
                                        
                                        // replace placeholders in prepend and append
                                        
                                        if ( !empty($prepend) ) {
                                            $prepend_output = preg_replace( "/(\\{count\\})/", $tag_count, $prepend );
                                        } else {
                                            $prepend_output = '';
                                        }
                                        
                                        
                                        if ( !empty($append) ) {
                                            $append_output = preg_replace( "/(\\{count\\})/", $tag_count, $append );
                                        } else {
                                            $append_output = '';
                                        }
                                        
                                        // adding link target
                                        $link_target_html = ( !empty($link_target) ? 'target="' . $link_target . '"' : '' );
                                        // adding class for group
                                        if ( !empty($group_in_class) ) {
                                            $other_tag_classes .= ' ' . sanitize_html_class( ' tg_tag_group_id_' . $data[$i]['term_group'] ) . ' ' . sanitize_html_class( 'tg_tag_group_label_' . strtolower( $data[$i]['label'] ) );
                                        }
                                        // assembling a tag
                                        $html_tags .= '<span class="tag-groups-tag' . $other_tag_classes . '" style="font-size:' . $font_size . 'px"><a href="' . $tag_link . '" ' . $link_target_html . ' title="' . $title . '"  class="' . $tag->slug . '">';
                                        if ( '' != $prepend_output ) {
                                            $html_tags .= '<span class="tag-groups-prepend" style="font-size:' . $font_size . 'px">' . htmlentities( $prepend_output, ENT_QUOTES, "UTF-8" ) . '</span>';
                                        }
                                        $html_tags .= apply_filters(
                                            'tag_groups_cloud_tag_outer',
                                            '<span class="tag-groups-label" style="font-size:' . $font_size . 'px">' . apply_filters(
                                            'tag_groups_cloud_tag_inner',
                                            $tag->name,
                                            $tag->term_id,
                                            $shortcode_id
                                        ) . '</span>',
                                            $tag->term_id,
                                            $shortcode_id
                                        );
                                        if ( '' != $append_output ) {
                                            $html_tags .= '<span class="tag-groups-append" style="font-size:' . $font_size . 'px">' . htmlentities( $append_output, ENT_QUOTES, "UTF-8" ) . '</span>';
                                        }
                                        $html_tags .= '</a></span> ';
                                        $count_amount++;
                                    }
                                
                                }
                            
                            }
                        }
                    }
                    
                    if ( !empty($html_header) && (!$hide_empty_content || $count_amount) ) {
                        
                        if ( $keep_together ) {
                            $html .= '<div class="tag-groups-keep-together">' . $html_header . '<div' . $tags_div_class_output . '>' . $html_tags . '</div></div>' . "\n";
                        } else {
                            $html .= $html_header . '<div' . $tags_div_class_output . '>' . $html_tags . '</div>' . "\n";
                        }
                    
                    }
                }
                // create a cached version (premium plugin)
                do_action( 'tag_groups_hook_cache_set', $cache_key, $html );
            }
            
            $html = '<div' . $div_id_output . $div_class_output . $div_column_output . '>' . $html . '</div>';
            apply_filters(
                'tag_groups_cloud_html',
                $html,
                $shortcode_id,
                $atts
            );
            return $html;
        }
    
    }
    // class
}
