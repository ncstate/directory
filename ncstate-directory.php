<?php
/**
 * Plugin Name: NC State Directory
 * Plugin URI: https://github.ncsu.edu/ncstate-ucomm/ncstate-events
 * Description: Creates custom post type for people that are added manually or from campus LDAP.
 * Version: 2.0-beta.1
 * Author: University Communications, NC State
 * Author URI: http://university-communications.ncsu.edu/
 * License: MIT
 */


/**
 * ----------------------------------------------------------------
 * Environment sanity check
 * ----------------------------------------------------------------
 * We need to make sure that we have initialized Composer correctly.
 *
 */

if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
	throw new \RuntimeException('Unable to register autoloader. Has Composer been initialized?');
}

/**
 * ----------------------------------------------------------------
 * Register the autoloader
 * ----------------------------------------------------------------
 * Composer's autoloader supports PSR-4 style autoloading. We will
 * use that to lazy load all our classes. A global functions file is
 * also autoloaded at `src/functions.php`. Useful for any WordPress-
 * style facades to services or utility functions.
 *
 */

require_once __DIR__ . '/vendor/autoload.php';


/**
 * ----------------------------------------------------------------
 * Plugin bootstrapping and logic
 * ----------------------------------------------------------------
 *
 */

include 'add-views.php';
include 'communicator.php';
include 'shortcodes.php';
include 'includes/directory-plugin-settings.php';

$settings = new Directory_Plugin_Template_Settings( __FILE__ );

add_action( 'admin_menu', 'directory_update');
function directory_update() {
	if(isset($_POST['directory-update']) && current_user_can('manage_options')) {
		get_updates();
	}
}

add_action('wp_enqueue_scripts', 'directory_styles');
function directory_styles() {
	if ( file_exists(get_stylesheet_directory() . '/ncstate-directory/css/style.css') ) {
		wp_enqueue_style('ncstate_directory_style', get_stylesheet_directory_uri() . '/ncstate-directory/css/style.css');
	} else {
		wp_enqueue_style('ncstate_directory_style', plugin_dir_url(__FILE__) . '/css/style.css');
	}
}

// Create 'Person' custom post type
add_action( 'init', 'create_person_post_type' );
function create_person_post_type() {
	register_post_type( 'person', array(
		'labels' => array(
			'name' => __( 'People' ),
			'singular_name' => __( 'Person' )
		),
		'public' => true,
		'has_archive' => true,
		'supports' => array( 'title', 'editor', 'custom-fields', 'author' ),
		'rewrite' => array(
			'slug' => get_option('ncstate_directory_url', 'people'),
			'with_front' => false
		),
		'menu_icon' => 'dashicons-id',
	));
}

// Create custom taxonomy for 'Person' CPT
add_action( 'init', 'person_init' );
function person_init() {
	// create a new taxonomy
	register_taxonomy('subgroup', 'person', array(
		'labels' => array(
			'name' => 'Subgroup',
			'add_new_item' => 'Add Subgroup',
			'new_item_name' => "New Subgroup"
		),
		'show_ui' => true,
		'show_tagcloud' => false,
		'hierarchical' => true,
		'rewrite' => array(
			'with_front' => false
		)
	));

	wp_insert_term('Staff', 'subgroup', array(
		'slug' => 'staff',
	));

	wp_insert_term('Faculty', 'subgroup', array(
		'slug' => 'faculty',
	));
}

function person_feed_parser($option) {
	$raw = get_option($option);
	$oucs = explode(",", $raw);
	return $oucs;
}

/*
 * Pulls directory information when a person post type is
 * published or updated.
 *
*/

add_action('publish_person', 'person_ldap_query', 10, 2);

function person_ldap_query($ID, $post) {
	$ds = ldap_connect("ldap.ncsu.edu");
	ldap_bind($ds);
	$unity_id = get_post_meta($post->ID, 'uid', true);
	if(!empty($unity_id)) {
		update_people(get_person_ldap($unity_id, $ds));
	}
}

/*
 * Setting auto daily directory updates
 *
*/

register_activation_hook(__FILE__, 'ncstate_directory_schedule');
function ncstate_directory_schedule() {
	if(!wp_next_scheduled('ncstate_directory_hourly_update')):
		wp_schedule_event(time(), 'daily', 'ncstate_directory_hourly_update');
	endif;
}

add_action('ncstate_directory_hourly_update', 'ncstate_directory_hourly_update');

function ncstate_directory_hourly_update() {
	get_updates();
}

register_deactivation_hook(__FILE__, 'ncstate_directory_unschedule');
function ncstate_directory_unschedule() {
	wp_clear_scheduled_hook('ncstate_directory_hourly_update');
}

// End daily updates section
