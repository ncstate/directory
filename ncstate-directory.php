<?php
/**
 * Plugin Name: NC State Directory
 * Plugin URI: http://sciences.ncsu.edu
 * Description: Creates custom post type for people that are added manually or from campus LDAP.
 * Version: 0.1
 * Author: Scott Thompson, NC State
 * Author URI: http://github.com/csthomp89
 * License: MIT
 */

require 'vendor/autoload.php';

function directory_styles() {
	if ( file_exists(get_stylesheet_directory() . '/ncstate-directory/css/style.css') ) {
		wp_enqueue_style('ncstate_directory_style', get_stylesheet_directory_uri() . '/ncstate-directory/css/style.css');
	} else {
		wp_enqueue_style('ncstate_directory_style', plugin_dir_url(__FILE__) . '/css/style.css');
	}
}
add_action('wp_enqueue_scripts', 'directory_styles');

// Create 'Person' custom post type
add_action( 'init', 'create_person_post_type' );
function create_person_post_type() {
	register_post_type( 'person',
		array(
			'labels' => array(
				'name' => __( 'People' ),
				'singular_name' => __( 'Person' )
			),
		'public' => true,
		'has_archive' => true,
		'supports' => array( 'title', 'editor', 'custom-fields' ),
		'rewrite' => array( 'slug' => 'person', 'with_front' => false ),
		)
	);
}

// Create custom taxonomy for 'Person' CPT
add_action( 'init', 'person_init' );
function person_init() {
	// create a new taxonomy
	register_taxonomy(
		'subgroup',
		'person',
		array(
	        'labels' => array(
	            'name' => 'Subgroup',
	            'add_new_item' => 'Add Subgroup',
	            'new_item_name' => "New Subgroup"
	        ),
	        'show_ui' => true,
	        'show_tagcloud' => false,
	        'hierarchical' => true
	    )
	);
	$args = array(
		'slug' => 'staff',
	);
	wp_insert_term('Staff', 'subgroup', $args);
	$args = array(
		'slug' => 'faculty',
	);
	wp_insert_term('Faculty', 'subgroup', $args);
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
					//echo '<pre>';
					//$feed = person_feed_parser();
					//person_insert_person(person_get_feed($feed[0][0]));
					//echo '</pre>';
	echo		'</form>';
	echo '</div>';
}

function person_feed_parser($option) {
	$raw = get_option($option);
	$oucs = explode(",", $raw);
	return $oucs;
}

// Adding ACF custom fields info

include 'acf.php';
include 'custom-fields.php';
include 'add-views.php';
include 'communicator.php';
include 'shortcodes.php';