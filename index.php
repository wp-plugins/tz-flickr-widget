<?php
 /*
	Plugin Name: TZ Flickr Widget
	Plugin URI:
	Description: Display your Flickr latest photostream in widget with Pretty Photo. Easy to customize, just put your Flickr id and your widget ready to lunch.
	Version: 1.0.2
	Author: tuyennv
	Author URI: http://www.templaza.com
	License: Under GPL2

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.
*/


/**
 * Exit if accessed directly
 * @since 1.2.5
 */
if ( ! defined( 'ABSPATH' ) )
	exit;
	

/**
 * Set constant path to the members plugin directory
 * @since 1.0
 */
define( 'TZ_FLICKR_WIDGET_VERSION', '1.0.0' );
define( 'TZ_FLICKR_WIDGET_DIR', plugin_dir_path( __FILE__ ) );
define( 'TZ_FLICKR_WIDGET_URL', plugin_dir_url( __FILE__ ) );


/**
 * Launch the plugin
 * @since 1.0
 */
add_action( 'plugins_loaded', 'tz_flickr_widget_plugins_loaded' );


/**
 * Initializes the plugin and it's features
 * Loads and registers the new widgets
 * @since 1.0
 */
function tz_flickr_widget_plugins_loaded() {
	add_action( 'widgets_init', 'tz_flickr_widget_init' );
}


/**
 * Register the extra widgets. Each widget is meant to replace or extend the current default 
 * Load widget file
 * @since 1.0
 */
function tz_flickr_widget_init() {
	require_once( TZ_FLICKR_WIDGET_DIR . 'widget.php' );
	register_widget( 'Pretty_Flickr_Widget' );
}
?>