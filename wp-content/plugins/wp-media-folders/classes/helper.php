<?php
/* Prohibit direct script loading */
defined('ABSPATH') || die('No direct script access allowed!');

/**
 * Class WP_Media_Folders_Helper
 */
class WPMediaFoldersHelper
{

    /**
     * All WPMF terms
     * Variable used as a cache to not retrieved terms each time the
     * method is called
     *
     * @var null
     */
    protected static $terms = null;

    /**
     * Update folder name and move files
     *
     * @param integer $folder_id   Folder id
     * @param string  $folder_name Folder name
     *
     * @return void
     */
    public static function updateFolderName($folder_id, $folder_name)
    {
        // Get children folders
        $folders = self::getChildTerms($folder_id);

        $folders[] = (int)$folder_id;

        foreach ($folders as $folder) {
            // Get attachments in this folder
            $attachments = get_posts(array(
                'post_type' => 'attachment',
                'numberposts' => -1,
                'tax_query' => array(
                    array(
                        'taxonomy' => WPMF_TAXO,
                        'field' => 'id',
                        'terms' => $folder,
                        'include_children' => false
                    )
                )
            ));

            $folders_path = self::getParentTerms($folder);
            $folders_path_string = implode(DIRECTORY_SEPARATOR, $folders_path);

            foreach ($attachments as $attachment) {
                WPMediaFoldersQueue::addToQueue($attachment->ID, $folders_path_string, false, true);
            }
            WPMediaFoldersQueue::proceedQueueAsync();
        }
    }

    /**
     * Get recursively all child terms of a given term
     *
     * @param integer $parent Term we want to find the children of
     *
     * @return array
     */
    protected static function getChildTerms($parent)
    {
        // get all direct decendents of the $parent
        $terms = get_terms(array(
            'taxonomy' => WPMF_TAXO,
            'parent' => $parent,
            'hide_empty' => false
        ));
        $children = array();

        // go through all the direct decendents of $parent
        foreach ($terms as $term) {
            // recurse to get the direct decendents of the current term
            $children = array_merge($children, self::getChildTerms($term->term_id));

            $children[] = $term->term_id;
        }

        return $children;
    }

    /**
     * Retrieve all WPMF terms
     *
     * @return array
     */
    public static function getAllTerms()
    {
        // Retrieve all folders
        $wpterms = get_terms(
            array(
                'taxonomy' => WPMF_TAXO,
                'hide_empty' => false
            )
        );

        // Associate folders by term id
        $terms = array();
        foreach ($wpterms as $term) {
            $terms[$term->term_id] = $term;
        }

        return $terms;
    }

    /**
     * Retrieve all parent terms
     *
     * @param integer $term Term we want to find parents of
     *
     * @return array
     */
    public static function getParentTerms($term)
    {
        if ($term === 0) {
            return array();
        }

        // Retrieve all WPMF terms if not already done
        if (self::$terms === null) {
            self::$terms = self::getAllTerms();
        }

        // Initialize array that will contain all parent folders
        $folders = array();
        $current_folder = $term;
        // Retrieve parents one by one
        do {
            $current_term = self::$terms[$current_folder];
            $folders[] = $current_term->name;
            $current_folder = $current_term->parent;
        } while ($current_term->parent);

        return array_reverse($folders);
    }

    /**
     * Move a file and its thumbnails physically
     *
     * @param integer $post_id                   Attachment id
     * @param string  $destination               Destination folder to move the file to
     * @param boolean $destination_with_filename Does the destination parameter contains also the file name (without extension)
     * @param boolean $delete_folder             Delete the folder containing this file after the process if it's empty
     * @param boolean $is_wpml_translation      Is this file a wpml translation of another moved file?
     *
     * @return boolean|WP_Error true on success or WP_ERROR
     */
    public static function moveFile($post_id, $destination, $destination_with_filename = true, $delete_folder = false, $is_wpml_translation = false)
    {
        WP_Filesystem();
        global $wp_filesystem;
        global $wpdb;

        // Sanitize destination path
        $destination = self::sanitizePath($destination);
        if ($destination_with_filename && !strlen(trim($destination, '/'))) {
            WP_Media_Folders_Debug::log('Error : destination file empty');
            return new WP_Error('error', __('The file name cannot be empty', 'wp-media-folders'));
        }

        $related_files = array('original' => array(), 'thumbnails' => array(), 'backups' => array());

        $wp_uploads = wp_upload_dir();

        // Retrieve attachment full path
        $related_files['original']['path'] = get_attached_file($post_id, 1);
        $related_files['original']['url'] = wp_get_attachment_url($post_id);

        if (!$related_files['original']['path'] || !$related_files['original']['url']) {
            WP_Media_Folders_Debug::log('Error : Attachment %s not found', $$post_id);
            return new WP_Error('error', __('Attachment not found', 'wp-media-folders'));
        }

        // Remove file name from path and url
        $base_path = pathinfo($related_files['original']['path'], PATHINFO_DIRNAME);
        $url_parts = explode('/', $related_files['original']['url']);
        unset($url_parts[count($url_parts)-1]); // Remove the filename from parts
        $base_url = implode('/', $url_parts);

        // Check that the file is located inside the WordPress upload dir
        if (strpos($base_url, $wp_uploads['baseurl']) !== 0 || strpos($base_path, $wp_uploads['basedir']) !== 0) {
            WP_Media_Folders_Debug::log('Error : file is not int the upload folder %s', $base_path);
            return new WP_Error('error', __('This file is not in the allowed upload folder', 'wp-media-folders'));
        }

        // Make url and path relative to the wordpress installation
        $base_path = str_replace($wp_uploads['basedir'], '', $base_path);
        $base_url = str_replace($wp_uploads['baseurl'], '', $base_url);

        // Replace windows
        $base_path = str_replace(DIRECTORY_SEPARATOR, '/', $base_path);

        // Apply relative path to the original file and url
        $related_files['original']['path'] = str_replace($wp_uploads['basedir'], '', $related_files['original']['path']);
        $related_files['original']['url'] = str_replace($wp_uploads['baseurl'], '', $related_files['original']['url']);

        // Retrieve file extension from thumbnail name
        $extension = explode('.', $related_files['original']['path']);
        $extension = $extension[count($extension)-1];

        if ($destination_with_filename) {
            $filename = $destination . '.' . $extension;
        } else {
            $filename = $destination . '/' . pathinfo($related_files['original']['path'], PATHINFO_BASENAME);
        }
        $related_files['original']['new_path'] = '/' . ltrim($filename, '/');
        $related_files['original']['new_url'] = '/' .  ltrim($filename, '/');

        // Retrieve all meta to extract the tumbnails
        $meta = wp_get_attachment_metadata($post_id);
        if (isset($meta['sizes']) && is_array($meta['sizes'])) {
            foreach ($meta['sizes'] as &$size) {
                // Add to array original url and path
                $file = array(
                    'path' => $base_path . '/' . $size['file'],
                    'url' => $base_url . '/' . $size['file']
                );

                // Retrieve file extension from thumbnail name
                $extension = explode('.', $size['file']);
                $extension = $extension[count($extension) - 1];

                if ($destination_with_filename) {
                    $filename = $destination . '-' . (int)$size['width'] . 'x' . (int)$size['height'] . '.' . $extension;

                    // Update meta filename
                    $size['file'] = pathinfo($filename, PATHINFO_BASENAME);
                } else {
                    $filename = $destination . '/' . $size['file'];
                }
                $file['new_path'] = '/' . ltrim($filename, '/');
                $file['new_url'] = '/' . ltrim($filename, '/');

                $related_files['thumbnails'][] = $file;
            }
        }
        if (isset($meta['file'])) {
            $meta['file'] = ltrim($related_files['original']['new_path'], '/');
        }

        // Retrieve the backups of the images
        $backup_sizes = get_post_meta($post_id, '_wp_attachment_backup_sizes', true);
        if ($backup_sizes && is_array($backup_sizes)) {
            foreach ($backup_sizes as $backup_size) {
                $destination_folder = $destination;
                if ($destination_with_filename) {
                    // Remove filename from destination var
                    $destination_folder = explode('/', $destination_folder);
                    unset($destination_folder[count($destination_folder)-1]);
                    $destination_folder = implode('/', $destination_folder);
                }

                $related_files['backups'][] = array(
                    'path' => $base_path . '/' . $backup_size['file'],
                    'url' => $base_url . '/' . $backup_size['file'],
                    'new_path' => '/' . $destination_folder . '/' . $backup_size['file'],
                    'new_url' => '/' . $destination_folder . '/' . $backup_size['file']
                );
            }
        }

        $original_file = $wp_uploads['basedir'] . $related_files['original']['path'];
        $new_file = $wp_uploads['basedir'] . $related_files['original']['new_path'];

        // Check if source file exists
        if (file_exists($original_file) && is_file($original_file)) {
            if (realpath($original_file) !== realpath($new_file)) {
                // Check is there is already a destination file with this name
                if (file_exists($new_file)) {
                    WP_Media_Folders_Debug::log('Error : file is %s already exists', $new_file);
                    return new WP_Error('error', __('The destination file already exists', 'wp-media-folders'));
                }

                // Create directory
                $dir = pathinfo($new_file, PATHINFO_DIRNAME);
                if (!file_exists($dir)) {
                    wp_mkdir_p($dir);
                }

                // Move actual file
                if (!$wp_filesystem->move(
                    $original_file,
                    $new_file
                )) {
                    WP_Media_Folders_Debug::log('Error : moving file %s to %s went wrong', $original_file, $new_file);
                    return new WP_Error('error', __('Error while moving the file', 'wp-media-folders'));
                }
                WP_Media_Folders_Debug::log('Info : file moved from %s to %s', $original_file, $new_file);
            }  elseif (!$is_wpml_translation) {
                WP_Media_Folders_Debug::log('Error : source and destination file are the same %s', $new_file);
                return new WP_Error('error', __('Source and destination file are the same', 'wp-media-folders'));
            }


        }  elseif (!$is_wpml_translation) {
            WP_Media_Folders_Debug::log('Error : file %s does not exist', $original_file);
            return new WP_Error('error', __('This file doesn\'t exist', 'wp-media-folders'));
        }
        // if file is a wpml translation, the file may have already been moved by the calling function


        // Update file meta (_wp_attached_file)
        if (ltrim($related_files['original']['path'], '/') !== ltrim($related_files['original']['new_path'], '/')) {
            if (update_post_meta($post_id, '_wp_attached_file', ltrim($related_files['original']['new_path'], '/')) !== true) {
                WP_Media_Folders_Debug::log('Error : updating post meta failed %s %s', $post_id, ltrim($related_files['original']['new_path'], '/'));
                return new WP_Error('error', __('Error while updating post meta', 'wp-media-folders'));
            }
        }

        // Todo update guid via sql query ???

        // Array containing all actually moved files to be replaced in db
        $done_files = array();
        $done_files[] = $related_files['original'];

        // Move all thumbnails and backup files
        foreach (array($related_files['thumbnails'], $related_files['backups']) as $file_type) {
            foreach ($file_type as $file) {
                $original_file = $wp_uploads['basedir'] . $file['path'];
                $new_file = $wp_uploads['basedir'] . $file['new_path'];
                if (file_exists($original_file)) {
                    if (!$wp_filesystem->move($original_file, $new_file)) {
                        WP_Media_Folders_Debug::log('Error : related file move failed from %s to %s', $original_file, $new_file);
                        continue;
                    }
                } elseif (!$is_wpml_translation) {
                    WP_Media_Folders_Debug::log('Error: related file doesn not exist %s', $original_file);
                    continue;
                }

                $done_files[] = $file;
                WP_Media_Folders_Debug::log('Info : related file moved from %s to %s', $original_file, $new_file);
            }
        }

        // Update thumbnails meta
        wp_update_attachment_metadata($post_id, $meta);

        // Update wpml translation attachments and thumbnails
        if (!$is_wpml_translation && function_exists('icl_object_id')) {
            $post_translations = array();

            $query = 'SELECT * FROM `' . $wpdb->prefix . 'icl_translations` WHERE trid=(SELECT trid FROM `' . $wpdb->prefix . 'icl_translations` WHERE element_type="post_attachment" AND element_id=' . (int)$post_id . ') AND element_id<>' . (int)$post_id;
            // phpcs:ignore WordPress.WP.PreparedSQL.NotPrepared -- Query escaped previously
            $post_translations = $wpdb->get_results($query, ARRAY_A);

            foreach ($post_translations as $translation) {
                $translation_post_meta = get_post_meta($translation['element_id'], '_wp_attached_file', true);

                if ($translation_post_meta === ltrim($related_files['original']['path'], '/')) {
                    // This is the same exact file

                    // Update _wp_attached_file key
                    if (update_post_meta($translation['element_id'], '_wp_attached_file', ltrim($related_files['original']['new_path'], '/')) !== true) {
                        WP_Media_Folders_Debug::log('Error : updating post meta failed %s %s', $translation['element_id'], ltrim($related_files['original']['new_path'], '/'));
                        continue;
                    }

                    $translation_meta = wp_get_attachment_metadata($translation['element_id']);
                    if (isset($translation_meta['file']) && $translation_meta['file'] === ltrim($related_files['original']['path'], '/')) {
                        $translation_meta['file'] = ltrim($related_files['original']['new_path'], '/');
                    }

                    // Only basename is saved in the _wp_attachment_metadata sizes col, no need to do anything if we only change the folder
                    if ($destination_with_filename && isset($translation_meta['sizes']) && is_array($translation_meta['sizes'])) {
                        // Loop over the sizes of this translation attachment
                        foreach ($translation_meta['sizes'] as &$size) {
                            // Loop over the thumbnails already moved to see if this one of them
                            foreach ($related_files['thumbnails'] as $thumbnail) {
                                if (pathinfo($thumbnail['path'], PATHINFO_BASENAME) === $size['file'] &&
                                    pathinfo($translation_post_meta, PATHINFO_DIRNAME) === pathinfo(ltrim($thumbnail['path'], '/'), PATHINFO_DIRNAME)) { // Make this thumbnail is not a thumbnail in another folder
                                    // Update meta filename
                                    $size['file'] = pathinfo($thumbnail['new_path'], PATHINFO_BASENAME);
                                    continue;
                                }
                            }
                        }
                    }
                    preg_match('/(do_action|apply_filters)\(.*\);/', $match, $hook_matches);

                    wp_update_attachment_metadata($translation['element_id'], $translation_meta);
                } else {
                    // This is not the same file but still it has to be in the same folder, lets move all of them (main file, thumbnails, backups)
                    $new_path = pathinfo($related_files['original']['new_path'], PATHINFO_DIRNAME);

                    self::moveFile($translation['element_id'], $new_path, false, $delete_folder, true);

                    if ($destination_with_filename) {
                        // At this points backups have been moved but still it has to be renamed in the db
                        $backup_sizes = get_post_meta($translation['element_id'], '_wp_attachment_backup_sizes', true);

                        if (!empty($backup_sizes) &&
                            isset($backup_sizes['full-orig']) &&
                            $backup_sizes['full-orig']['file'] === ltrim($related_files['original']['path'], '/')) {
                            foreach ($backup_sizes as $backup_name => &$backup_size) {
                                if ($backup_name === 'full-orig') {
                                    $backup_size['file'] = ltrim($related_files['original']['new_path'], '/');
                                } else {
                                    // Retrieve file extension from thumbnail name
                                    $extension = explode('.', $backup_size['file']);
                                    $extension = $extension[count($extension) - 1];

                                    $backup_size['file'] = $destination . '-' . (int)$backup_size['width'] . 'x' . (int)$backup_size['height'] . '.' . $extension;
                                }
                            }

                            update_post_meta($translation['element_id'], '_wp_attachment_backup_sizes', $backup_sizes);
                        }
                    }
                }
            }
        }

        if ($delete_folder) {
            $dir = pathinfo($wp_uploads['basedir'] . '/' . $related_files['original']['path'], PATHINFO_DIRNAME);
            $wp_filesystem->rmdir($dir);
        }

        // Replace in database file url
        $options = get_option('wp-media-folders-options');

        if (isset($options['auto_detect_tables'])) {
            $tables = self::getDefaultDbColumns();
        } else {
            $tables = get_option('wp-media-folders-tables');
        }

        foreach ($done_files as $done_file) {
            foreach ($tables as $table => &$columns) {
                if (!count($columns)) {
                    continue;
                }

                // Get the primary key of the table
                $key = $wpdb->get_row('SHOW KEYS FROM  ' . esc_sql($table) . ' WHERE Key_name = "PRIMARY"');

                // No primary key, we can't do anything in this table
                if ($key === null) {
                    WP_Media_Folders_Debug::log('No primary key in table', $table);
                    continue;
                }

                $key = $key->Column_name;

                foreach ($columns as $column => $column_value) {
                    // Search for serialized strings
                    $query = 'SELECT ' . esc_sql($key) . ',' . esc_sql($column) . ' FROM ' . esc_sql($table). ' WHERE
                        ' . esc_sql($column) . ' REGEXP \'s:[0-9]+:".*(' . esc_sql(preg_quote($wp_uploads['baseurl'] . $done_file['url'])) . '|' . esc_sql(preg_quote($done_file['url'])) . ').*";\'';

                    // phpcs:ignore WordPress.WP.PreparedSQL.NotPrepared -- Query escaped previously
                    $results = $wpdb->get_results($query, ARRAY_N);

                    if (count($results)) {
                        foreach ($results as $result) {
                            $unserialized_var = unserialize($result[1]);
                            if ($unserialized_var !== false) {
                                // We're sure this is a serialized value, proceed it here
                                unset($columns[$column]);
                                // Actually replace string in all available strin array and properties
                                $unserialized_var = self::replaceStringRecursive($unserialized_var, $done_file['url'], $done_file['new_url']);
                                $unserialized_var = self::replaceStringRecursive($unserialized_var, $wp_uploads['baseurl'] . $done_file['url'], $wp_uploads['baseurl'] . $done_file['new_url']);
                                // Serialize it back
                                $serialized_var = serialize($unserialized_var);

                                // Update the database with new serialized value
                                $nb_rows = $wpdb->query($wpdb->prepare(
                                    'UPDATE ' . esc_sql($table) . ' SET ' . esc_sql($column) . '=%s WHERE ' . esc_sql($key) . '=%s AND meta_key NOT IN("_wp_attached_file", "_wp_attachment_metadata")',
                                    array($serialized_var, $result[0])
                                ));
                                WP_Media_Folders_Debug::log('Update serialized data (%s row affected) : %s', $nb_rows, $query);
                            }
                        }
                    }
                }

                if (count($columns)) {
                    $columns_query = array();

                    foreach ($columns as $column => $column_value) {
                        $columns_query[] = '`' . $column . '` = replace(`' . esc_sql($column) . '`, "' . esc_sql($wp_uploads['baseurl'] .  $done_file['url']) . '", "' . esc_sql($wp_uploads['baseurl'] . $done_file['new_url']) . '")';

                        // Relative urls
                        $columns_query[] = '`' . $column . '` = replace(`' . esc_sql($column) . '`, "' . esc_sql($done_file['url']) . '", "' . esc_sql($done_file['new_url']) . '")';
                    }

                    $query = 'UPDATE `' . esc_sql($table) . '` SET ' . implode(',', $columns_query);

                    // Ignore attachments meta column
                    if ($table === $wpdb->prefix . 'postmeta') {
                        $query .= ' WHERE meta_key NOT IN("_wp_attached_file", "_wp_attachment_metadata")';
                    }

                    // phpcs:ignore WordPress.WP.PreparedSQL.NotPrepared -- Query escaped previously
                    $nb_rows = $wpdb->query($query);
                    WP_Media_Folders_Debug::log('Query (%s row affected) : %s', $nb_rows, $query);
                }
            }
        }

        return true;
    }

    /**
     * Recursively parse a variable to replace a string
     *
     * @param mixed  $var     Variable to replace string into
     * @param string $search  String to search
     * @param string $replace String to replace with
     *
     * @return mixed
     */
    private function replaceStringRecursive($var, $search, $replace)
    {
        switch (gettype($var)) {
            case 'string':
                return str_replace($search, $replace, $var);

            case 'array':
                foreach ($var as &$property) {
                    $property = self::replaceStringRecursive($property, $search, $replace);
                }
                return $var;

            case 'object':
                foreach (get_object_vars($var) as $property_name => $property_value) {
                    $var->{$property_name} = self::replaceStringRecursive($property_value, $search, $replace);
                }
                return $var;
        }
    }

    /**
     * Sanitize a file path
     *
     * @param string $path Path
     *
     * @return string
     */
    private static function sanitizePath($path)
    {
        // Replace non unix space separators by /
        $path = str_replace(DIRECTORY_SEPARATOR, '/', $path);

        $path = explode('/', $path);

        $stack = array();
        foreach ($path as $seg) {
            // Remove all dots segments
            if ($seg === '..' || $seg === '.') {
                // Ignore this segment
                continue;
            }

            // Remove all non matching caracters
            $seg = preg_replace('/[^0-9a-zA-Z_-]/', '-', $seg);

            if (strlen($seg)) {
                $stack[] = $seg;
            }
        }

        return implode('/', $stack);
    }

    /**
     * Return a list the main image and all thumbnails of the attachment
     *
     * @param integer $post_id Attachment id
     *
     * @return array
     */
    private static function getAllImagesUrl($post_id)
    {
        $images = array();

        // Add main image
        $images['original'] = wp_get_attachment_url($post_id);

        foreach (get_intermediate_image_sizes() as $size) {
            $image = image_downsize($post_id, $size);

            if ($image !== false && $image[3] === true) {
                $images[$image[1] . 'x' . $image[2]] = $image[0];
            }
        }
        return array_unique($images);
    }

    /**
     * Get all text assimilated columns from database
     *
     * @return array|null|object
     */
    public static function getDbColumns()
    {
        global $wpdb;
        // phpcs:ignore WordPress.WP.PreparedSQL.NotPrepared -- Nothing to prepare
        return $wpdb->get_results('SELECT TABLE_NAME, COLUMN_NAME, COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE DATA_TYPE IN ("varchar", "text", "tinytext", "mediumtext", "longtext") AND TABLE_SCHEMA = "'.DB_NAME.'" ORDER BY TABLE_NAME', OBJECT);
    }


    /**
     * Get the columns that can contain images
     *
     * @return array
     */
    public static function getDefaultDbColumns()
    {
        $columns = self::getDbColumns();

        $final_columns = array();

        foreach ($columns as $column) {
            $matches = array();
            preg_match('/varchar\(([0-9]+)\)/', $column->COLUMN_TYPE, $matches);

            if (count($matches) && (int)$matches[1] < 40) {
                continue;
            }

            if (!isset($final_columns[$column->TABLE_NAME])) {
                $final_columns[$column->TABLE_NAME] = array();
            }
            $final_columns[$column->TABLE_NAME][$column->COLUMN_NAME] = 1;
        }

        return $final_columns;
    }

    /**
     * Generate a random string
     *
     * @param integer $length Length of the returned string
     *
     * @author https://stackoverflow.com/questions/4356289/php-random-string-generator#answer-4356295
     *
     * @return string
     */
    public static function getRandomString($length = 20)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
