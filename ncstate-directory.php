<?php
/**
 * Plugin Name: NC State Directory
 * Plugin URI: https://github.ncsu.edu/ncstate-ucomm/ncstate-events
 * Description: Creates custom post type for people that are added manually or from campus LDAP.
 * Version: 1.1.1
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
		'supports' => array( 'title', 'editor', 'custom-fields' ),
		'rewrite' => array(
			'slug' => 'people',
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

add_action( 'admin_menu', 'person_options' );
function person_options() {
	add_submenu_page('edit.php?post_type=person', 'People Admin', 'Settings', 'edit_posts', 'person_options_menu_page', 'print_person_options');
	register_setting('person_settings', 'person_ouc');
	register_setting('person_settings', 'person_unity_ids');
}

function print_person_options() {
	echo '<div class="wrap">';
	echo	'<h2>People Admin</h2>';
	echo 		'<form method="post" action="options.php">';
					settings_fields('person_settings');
					do_settings_sections('person_settings');
					
	echo 			'<table class="form-table>"
						<tr valign="top">
							<th scope="row">OUCs</th>
							<td><textarea name="person_ouc" rows="4" cols="150">' . get_option('person_ouc') . '</textarea></td>
						</tr>
						<tr valign="top">
							<th scope="row">Unity IDs</th>
							<td><textarea name="person_unity_ids" rows="4" cols="150">' . get_option('person_unity_ids') . '</textarea></td>
						</tr>
					</table>
					';
					submit_button();
	echo		'</form>';
				if (current_user_can('manage_options')):
					echo '<form method="post" action="edit.php?post_type=person&page=person_options_menu_page">';
							echo '<table class="form-table">';
								echo '<tr valign="top">';
									echo '<th scope="row">Repull all info from campus directory</th>';
									echo '<td><em>This may take awhile.</em></td>';
								echo '</tr>';
							echo '</table>';
							submit_button('Update from campus directory','secondary','directory-update');
					echo '</form>';
				endif;
	echo '</div>';
}

function person_feed_parser($option) {
	$raw = get_option($option);
	$oucs = explode(",", $raw);
	return $oucs;
}

// Setting auto daily directory updates

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
