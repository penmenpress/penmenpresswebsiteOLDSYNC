<?php
/**
* Blocks Initializer
*
* Enqueue CSS/JS of all the blocks.
*
* @since 0.38
* @package Tag Groups
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
* Enqueue Gutenberg block assets for both frontend + backend.
*
* `wp-blocks`: includes block type registration and related functions.
*
* @since 1.0.0
*/
function chatty_mango_tag_cloudblock_assets() {
	// Styles.
	wp_enqueue_style(
		'chatty-mango_tag-cloud-style-css', // Handle.
		plugins_url( 'dist/blocks.style.build.css', dirname( __FILE__ ) ), // Block style CSS.
		array( 'wp-blocks' ) // Dependency to include the CSS after it.
		// filemtime( plugin_dir_path( __FILE__ ) . 'editor.css' ) // Version: filemtime — Gets file modification time.
	);
} // End function chatty_mango_tag_cloudblock_assets().

// Hook: Frontend assets.
add_action( 'enqueue_block_assets', 'chatty_mango_tag_cloudblock_assets' );

/**
* Enqueue Gutenberg block assets for backend editor.
*
* `wp-blocks`: includes block type registration and related functions.
* `wp-element`: includes the WordPress Element abstraction for describing the structure of your blocks.
* `wp-i18n`: To internationalize the block's text.
*
* @since 1.0.0
*/
function chatty_mango_tag_cloudeditor_assets() {
	// Scripts.
	wp_enqueue_script(
		'chatty-mango_tag-cloud-block-js', // Handle.
		plugins_url( '/dist/blocks.build.js', dirname( __FILE__ ) ), // Block.build.js: We register the block here. Built with Webpack.
		array( 'wp-blocks', 'wp-i18n', 'wp-element' ) // Dependencies, defined above.
		// filemtime( plugin_dir_path( __FILE__ ) . 'block.js' ) // Version: filemtime — Gets file modification time.
	);


	// make some data available
	$args = array(
		'siteUrl' 	=> get_option( 'siteurl' ),
		'siteLang'	=> '',	// for future use
		'pluginUrl'	=> TAG_GROUPS_PLUGIN_URL,
		'hasPremium'	=> defined( 'TAG_GROUPS_PREMIUM_MINIMUM_VERSION_WP' ),
	);

	wp_localize_script( 'chatty-mango_tag-cloud-block-js', 'ChattyMangoTagGroupsGlobal', $args );

	// Styles.
	wp_enqueue_style(
		'chatty-mango_tag-cloud-block-editor-css', // Handle.
		plugins_url( 'dist/blocks.editor.build.css', dirname( __FILE__ ) ), // Block editor CSS.
		array( 'wp-edit-blocks' ) // Dependency to include the CSS after it.
		// filemtime( plugin_dir_path( __FILE__ ) . 'editor.css' ) // Version: filemtime — Gets file modification time.
	);
} // End function chatty_mango_tag_cloudeditor_assets().

// Hook: Editor assets.
add_action( 'enqueue_block_editor_assets', 'chatty_mango_tag_cloudeditor_assets' );