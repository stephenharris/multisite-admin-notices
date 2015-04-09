<?php
/**
 * Plugin Name: Multisite Admin Notices
 * Plugin URI:  http://stephenharris.info
 * Description: Allows a network adminto create admin notices for blog admins.
 * Version:     0.1.2
 * Author:      Stephen Harris
 * Author URI:  http://stephenharris.info
 * License:     GPLv2+
 * Text Domain: msan
 * Domain Path: /languages
 */

/**
 * Copyright (c) 2014 Stephen Harris (email : contact@stephenharris.info)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

// Useful global constants
define( 'MSAN_VERSION', '0.1.2' );
define( 'MSAN_URL', plugin_dir_url( __FILE__ ) );
define( 'MSAN_DIR', plugin_dir_path( __FILE__ ) );

if ( !defined( 'MSAN_NOTICE_CPT' ) ){
	define( 'MSAN_NOTICE_CPT', 'msan-admin-notices' );
}

/**
 * Default initialization for the plugin:
 * - Registers the default textdomain.
 */
function msan_register_scripts() {
	
	$version = defined( 'MSAN_VERSION' ) ? MSAN_VERSION : false;
	$ext = (defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG) ? '' : '.min';

	load_plugin_textdomain( 'multisite-admin-notices', false, MSAN_DIR . '/languages/' );
	
	wp_register_script( 'msan-notice-manager', MSAN_URL . "assets/js/msan-notice-manager{$ext}.js", array(
		'jquery',
		'backbone',
		'underscore'
	), $version, true );
	
	wp_register_style( 'msan-notice-manager', MSAN_URL . "assets/css/msan-notice-manager{$ext}.css", array(), $version );
}

// Register scripts
add_action( 'init', 'msan_register_scripts' );

//Display the notices
require_once( MSAN_DIR . 'includes/notices-functions.php' );

//Register notice handler class
require_once( MSAN_DIR . 'includes/class-multsite-admin-notice-handler.php' );

//Register post type
require_once( MSAN_DIR . 'includes/cpt.php' );

//Display the notices
require_once( MSAN_DIR . 'includes/notices.php' );

//Admin page
if( is_admin() ){
	require_once( MSAN_DIR . 'includes/admin.php' );
	require_once( MSAN_DIR . 'includes/actions.php' );
}