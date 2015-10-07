<?php

if ( file_exists( get_stylesheet_directory() . '/ncstate-directory/views/directory_listing.php') ) {
	include get_stylesheet_directory() . '/ncstate-directory/views/directory_listing.php';
} else {
	include 'views/directory_listing.php';
}

$person_meta='';

// TODO: Escape user input?
function get_person_field($the_field) {
	global $person_meta;
	if ($the_field=="phone") {
		return substr($person_meta[$the_field][0], 0, 3) . "." . substr($person_meta[$the_field][0], 3, 3) . "." . substr($person_meta[$the_field][0], 6);
	} elseif($the_field=="email") {
		return '<a href="mailto:' . $person_meta[$the_field][0] . '">' . $person_meta[$the_field][0] . '</a>';
	} else {
		return $person_meta[$the_field][0];
	}
}

function person_shortcode($atts, $content=null) {
	$unity_id = $atts['unity_id'];
	$args = array(
		'name' => $unity_id,
		'post_type' => 'person',
		'post_status' => 'publish',
		'nopaging'	=> true,
	);
	$people = get_posts($args);
	$person_id = $people[0]->ID;
	global $person_meta;
	$person_meta=get_post_meta($person_id);
	return do_shortcode($content);
}
add_shortcode('person', 'person_shortcode');

function person_info_shortcode($atts) {
	return get_person_field($atts['field']);
}
add_shortcode('person_info', 'person_info_shortcode');

function directory_shortcode($atts) {
	$group = '';
	if(isset($atts['group'])) {
		$group = $atts['group'];
	}
	if("all" == $group) {
		$group = '';
	}
	return print_directory($group);
}
add_shortcode('directory', 'directory_shortcode');

function directory_list_shortcode($atts) {
	$group = '';
	$columns = 1;
	if(isset($atts['group'])) {
		$group = $atts['group'];
	}
	if("all" == $group) {
		$group = '';
	}
	if(isset($atts['columns']) && is_numeric($atts['columns']))
		$columns = $atts['columns'];
	return print_directory_list($group, $columns);
}
add_shortcode('directory-list', 'directory_list_shortcode');
