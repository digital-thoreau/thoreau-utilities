<?php
/**
 * Plugin Name: Thoreau Utilities
 * Plugin URI: https://github.com/digital-thoreau/thoreau-utilities
 * Description: Network-wide Utilities for the Readers' Thoreau website.
 * Author: Christian Wach
 * Version: 1.0
 * Author URI: https://haystack.co.uk
 *
 * @package Thoreau_Utilities
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Set our version here.
define( 'THOREAU_UTILITIES_VERSION', '1.0' );

// Store reference to this file.
if ( ! defined( 'THOREAU_UTILITIES_FILE' ) ) {
	define( 'THOREAU_UTILITIES_FILE', __FILE__ );
}

// Store URL to this plugin's directory.
if ( ! defined( 'THOREAU_UTILITIES_URL' ) ) {
	define( 'THOREAU_UTILITIES_URL', plugin_dir_url( THOREAU_UTILITIES_FILE ) );
}

// Store PATH to this plugin's directory.
if ( ! defined( 'THOREAU_UTILITIES_PATH' ) ) {
	define( 'THOREAU_UTILITIES_PATH', plugin_dir_path( THOREAU_UTILITIES_FILE ) );
}

/**
 * Thoreau Utilities Class.
 *
 * A class that encapsulates network-wide utilities.
 *
 * @since 1.0
 */
class Thoreau_Utilities {

	/**
	 * Constructor.
	 *
	 * @since 1.0
	 */
	public function __construct() {
		$this->register_hooks();
	}

	/**
	 * Register WordPress hooks.
	 *
	 * @since 1.0
	 */
	public function register_hooks() {

		// Add filter for Open Graph images.
		add_filter( 'jetpack_images_get_images', [ $this, 'custom_og_image' ], 10, 3 );
		add_filter( 'jetpack_open_graph_image_default', [ $this, 'default_og_image' ], 10, 3 );

	}

	/**
	 * Get default image for OpenGraph sharing.
	 *
	 * @since 1.0
	 *
	 * @param array $media The existing media array.
	 * @param int   $post_id The numeric ID of the WordPress Post.
	 * @param array $args The additional arguments.
	 * @return array $media The modified media array.
	 */
	public function custom_og_image( $media, $post_id, $args ) {

		// Bail if no Post ID.
		if ( ! is_numeric( $post_id ) ) {
			return $media;
		}

		// Get the permalink of the Post.
		$permalink = get_permalink( $post_id );

		// Get URL of image.
		$url = apply_filters( 'jetpack_photon_url', $this->default_og_image() );

		// Build image data array.
		$image_data = [
			'type'       => 'image',
			'from'       => 'custom_fallback',
			'src'        => esc_url( $url ),
			'src_width'  => 200,
			'src_height' => 200,
			'href'       => $permalink,
		];

		// Build media array.
		$media = [ $image_data ];

		// --<
		return $media;

	}

	/**
	 * Set default image for OpenGraph sharing.
	 *
	 * @since 1.0
	 *
	 * @param str $src The location of the default sharing image.
	 * @return str The modified location of the default sharing image.
	 */
	public function default_og_image( $src = '' ) {
		return THOREAU_UTILITIES_URL . 'thoreau-at-37.jpg';
	}

}

/**
 * Utility to get a reference to this plugin.
 *
 * @since 1.0
 *
 * @return Thoreau_Utilities $plugin The plugin reference.
 */
function thoreau_utilities_plugin() {

	// Instantiate if not yet done.
	static $plugin;
	if ( ! isset( $plugin ) ) {
		$plugin = new Thoreau_Utilities();
	}

	// --<
	return $plugin;

}

// Bootstrap the plugin.
thoreau_utilities_plugin();
