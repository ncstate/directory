<?php

use GuzzleHttp\Client;

add_action('init', 'get_updates');

function get_updates() {
	$oucs = person_feed_parser('person_ouc');
	$unity_ids = person_feed_parser('person_unity_ids');
	
	$people = array();
	foreach($oucs as $ouc) {
		$people = array_merge($people, get_ouc(trim($ouc)));
	}
	
	foreach($unity_ids as $unity_id) {
		$people[] = get_person(trim($unity_id));
	}
	
	if(count($people)>0) {
		update_people($people);
	}
	
}

function get_ouc($ouc) {
	/*
		TODO: If 100 or more items are return query again for another 100 items;
		recursively do this until that condition is no longer met
	*/
	
	$items = array();
	$offset = 0;
	do {
		$client = new Client();
		$response = $client->get('http://www.webtools.ncsu.edu/idm/api/users?ouc=' . $ouc . '&limit=100&offset=' . $offset);
		$json = $response->json();
		$items = array_merge($items, $json['items']);
		$offset = $offset + 100;
	} while (count($items)==$offset); 
	//echo '<pre>';
	//var_dump($json);
	//echo '</pre>';
	return $items;
}

function get_person($unity_id) {
	$client = new Client();
	$response = $client->get('http://www.webtools.ncsu.edu/idm/api/users/' . $unity_id);
	$json = $response->json();
	return $json['item'];
}

function update_people($people) {

	foreach($people as $person):
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