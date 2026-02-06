<?php

/**
 * Uninstall handler for G-code Reference plugin.
 * 
 * Removes all plugin data when plugin is deleted (not deactivated).
 * 
 * @package GCode_Reference
 * @since 2.0.0
 */

// Exit if not called by WordPress
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

/**
 * Remove plugin settings from database
 */
delete_option('gcode_reference_settings');

/**
 * Remove all transients (cached JSON data)
 * 
 * Transients are cached with pattern: gcode_json_*
 * We need to clean them all up to prevent orphaned cache entries.
 */
global $wpdb;

// Delete all transients matching our pattern
$wpdb->query(
    "DELETE FROM {$wpdb->options} 
   WHERE option_name LIKE '_transient_gcode_json_%' 
   OR option_name LIKE '_transient_timeout_gcode_json_%'"
);

/**
 * Remove uploaded JSON files from wp-content/uploads/gcode-reference/
 * 
 * Only removes files in our dedicated directory, not touching
 * any other uploads.
 */
$upload_dir = wp_upload_dir();
$plugin_upload_dir = trailingslashit($upload_dir['basedir']) . 'gcode-reference/';

if (is_dir($plugin_upload_dir)) {
    // Remove all files in directory
    $files = glob($plugin_upload_dir . '*');
    if ($files) {
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    // Remove directory itself
    rmdir($plugin_upload_dir);
}

/**
 * Clear any object cache
 * 
 * In case the site uses persistent object caching (Redis, Memcached),
 * flush our cache group.
 */
wp_cache_flush();

// That's it! Plugin data is now completely removed.
