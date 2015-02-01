<?php

use GuzzleHttp\Client;

add_action('init', 'update_people');

$oucs = person_feed_parser('person_ouc');
$unity_ids = person_feed_parser('person_unity_ids');

function update_people() {
	$client = new Client();
	$response = $client->get('http://www.webtools.ncsu.edu/idm/api/users?ouc=172201&limit=10');
	$json = $response->json();

	$people = $json['items'];
	
	/*
		TODO: If 100 or more items are return query again for another 100 items;
		recursively do this until that condition is no longer met
	*/

	foreach($people as $person):
		//var_dump(person_exists($person['id']));
		if(!person_exists($person['id'])):
			$post = array(
				'post_title' => $person['first_name'] . " " . $person['last_name'],
				'post_name' => $person['id'],
				'post_type' => 'person',
				'post_status' => 'publish',
		
			);
			$id = wp_insert_post($post);
			update_post_meta($id, 'first_name', $person['first_name']);
			update_post_meta($id, 'last_name', $person['last_name']);
			update_post_meta($id, 'first_name', $person['first_name']);
			update_post_meta($id, 'email', $person['email']);
			update_post_meta($id, 'phone', $person['phone']);
			update_post_meta($id, 'title', $person['title']);
			update_post_meta($id, 'auto_update', true);
			if($person['role']=='staff'):
				wp_set_object_terms($id, 'staff', 'subgroup');
			elseif($person['role']=='faculty'):
				wp_set_object_terms($id, 'faculty', 'subgroup');
			else:
				
			endif;
		elseif($id = person_auto_update($person['id'])):
			update_post_meta($id, 'first_name', $person['first_name']);
			update_post_meta($id, 'last_name', $person['last_name']);
			update_post_meta($id, 'first_name', $person['first_name']);
			update_post_meta($id, 'email', $person['email']);
			update_post_meta($id, 'phone', $person['phone']);
			update_post_meta($id, 'title', $person['title']);
		endif;
	endforeach;
}

function person_exists($unity_id) {
	$args = array(
		'name' => $unity_id,
		'post_type' => 'person',
	);
	$posts = get_posts($args);
	if(count($posts)>0) {
		return true;
	} else {
		return false;
	}
}

function person_auto_update($unity_id) {
	$args = array(
		'name' => $unity_id,
		'post_type' => 'person',
	);
	$posts = get_posts($args);
	if(get_post_meta($posts[0]->ID, 'auto_update', true)) {
		return $posts[0]->ID;
	} else {
		return false;
	}
}