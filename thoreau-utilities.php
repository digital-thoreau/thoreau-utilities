<?php
/**
 * Thoreau Harding
 *
 * Plugin Name:       Thoreau Utilities
 * Description:       Better handling of Walter Harding comments on the Digital Thoreau Website.
 * Plugin URI:        https://github.com/digital-thoreau/thoreau-utilities
 * GitHub Plugin URI: https://github.com/digital-thoreau/thoreau-utilities
 * Version:           1.0
 * Author:            Christian Wach
 * Author URI:        https://haystack.co.uk
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Requires at least: 5.7
 * Requires PHP:      7.4
 * Text Domain:       thoreau-utilities
 * Domain Path:       /languages
 *
 * @package Thoreau_Utilities
 * @link    https://github.com/digital-thoreau/thoreau-utilities
 * @license GPL v2 or later
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
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
