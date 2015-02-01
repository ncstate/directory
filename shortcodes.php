<?php

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
	echo do_shortcode($content);
}
add_shortcode('person', 'person_shortcode');

function person_info_shortcode($atts) {
	return get_person_field($atts['field']);
}
add_shortcode('person_info', 'person_info_shortcode');

/*
[person unity_id="csthomp2"]
	[person_info field="name"]
	[person_info field="title"]
	[person_info field="email"]
	[person_info field="favorite_equation"]
[/person]
*/