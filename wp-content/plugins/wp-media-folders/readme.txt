=== WP Media folders===
Contributors: dbarrere
Tags: media, folder, real media folders, media library, real media library
Requires at least: 3.5.1
Requires PHP: 5.4
Tested up to: 4.9.8
Stable tag: 1.1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WP Media Folders is a media management plugin that: Implement a real folder and media URL structure & Allow WP Media Folder plugin data import

== Description ==

WP Media Folders is a WordPress plugin with 2 main features:

**Type a real media URL structure and the plugin will do the rest for you: create folder, sub folders, rename and move the media in the proper location. A new field is added on the media edition window that allows you to update the full path and name of all media. The old media URL will be also replaced automatically in the databases tables to avoid any broken links.**

**Import and synchronize media structure (folders and sub-folders) with the JoomUnited plugin, <a href="https://www.joomunited.com/wordpress-products/wp-media-folder" rel="friend"> WP Media Folder. </a>**
WP Media Folder allows media management using virtual folders (custom taxonomy) in the WordPress Media Library. This plugin will create real server folders based on that.

Important notes and referral:
    • This plugin does NOT require WP Media Folder plugin to create real media folder structure but provide an optional integration with it
    • Make sure you run a FULL BACKUP before activating and using this plugin, its purpose is related to a very sensitive WordPress core way of working
    • WP Media Folder from JoomUnited: <a href="https://www.joomunited.com/wordpress-products/wp-media-folder" rel="friend"> https://www.joomunited.com/wordpress-products/wp-media-folder</a>

== Installation ==
WordPress installation/update is fully supported, install the plugin.
Once the plugin is installed, you can find all the configuration in the WordPress menu: Settings > WP Media Folders.
By default, you can start to update your media URL structure by editing any media.
In order to import WP Media Folder media structure, go to the WordPress menu: Settings > WP Media Folders and click on the button *Move Existing Media*
If you have a large amount of media it may take a while, you can follow the progression from the top bar counter.
Always make sure all the WP Media Folders tasks are finished before editing any content in your website.

== Screenshots ==
1. Update the media URL structure with a real folder path link
1. Transform WP Media Folder into server real media folder structure
1. Start the WP Media Folder media import

== Changelog ==


= Version 1.1.2 =
 * [fix] Double replacement in some cases

= Version 1.1.1 =
 * [feature] Compatibility with WPML
 * [fix] Do not query on attachment metas columns
 * [fix] Serialized values double replacement

= Version 1.1.0 =
 * [feature] WP Media Folder from Joommunited integration
 * [feature] Use background process to move file and avoid timeout issues
 * [feature] Auto detect table to make url replacements into

= Version 1.0.0 =
 * [feature] Initial release
 * [feature] Replace files path from media manager
