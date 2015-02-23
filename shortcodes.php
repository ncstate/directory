<?php

include 'views/directory_listing.php';

$person_meta='';

// TODO: Escape user input?
function get_person_field($the_field) {
	global $person_meta;	
	return $person_meta[$the_field][0];
}

function person_shortcode($atts, $content=null) {
	$unity_id = $atts['unity_id'];
	$args = array(
		'name' => $unity_id,
		'post_type' => 'person',
		'post_status' => 'publish',
	);
	$people = get_posts($args);
	$person_id = $people[0]->ID;
	global $person_meta;
	$person_meta=get_post_meta($person_id);
	return do_shortcode($content);
}
add_shortcode('person', 'person_shortcode');

function person_info_shortcode($atts) {
	if($atts['field']=='email'):
		return '<a href="mailto:' . get_person_field($atts['field']) . '">' . get_person_field($atts['field']) . '</a>';
	else:
		return get_person_field($atts['field']);
	endif;
}
add_shortcode('person_info', 'person_info_shortcode');

function directory_shortcode($atts) {
	$group = $atts['group'];
	return print_directory($group);
}
add_shortcode('directory', 'directory_shortcode');

/*
[person unity_id="csthomp2"]
	[person_info field="name"]
	[person_info field="title"]
	[person_info field="email"]
	[person_info field="favorite_equation"]
[/person]
*/