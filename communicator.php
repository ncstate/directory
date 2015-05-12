<?php

add_action('init', 'get_updates');

function get_updates() {
	$oucs = person_feed_parser('person_ouc');
	$unity_ids = person_feed_parser('person_unity_ids');
	
	$people = array();
	foreach($oucs as $ouc) {
		if(empty($ouc)) { break; }
		$people = array_merge($people, get_ouc_ldap(trim($ouc)));
	}
	
	foreach($unity_ids as $unity_id) {
		if(empty($unity_id)) { break; }
		$people[] = get_person_ldap(trim($unity_id));
	}
	
	if(count($people)>0) {
		update_people($people);
	}
	
}

function get_ouc_ldap($ouc) {
	$items = array();
	$ds = ldap_connect("ldap.ncsu.edu");
	ldap_bind($ds);
	$sr = ldap_search($ds, "ou=employees,ou=people,dc=ncsu,dc=edu", "departmentNumber=" . $ouc, array('uid', 'mail', 'ncsuPreferredGivenName', 'sn','title', 'ncsuWebSite', 'telephoneNumber', 'ncsuPrimaryRole', 'registeredAddress', 'givenName', 'ncsuNickname'));
	$entries = ldap_get_entries($ds, $sr);
	return ldap_formatter($entries);
}

function get_person_ldap($unity_id) {
	$person = array();
	$ds = ldap_connect("ldap.ncsu.edu");
	ldap_bind($ds);
	$sr = ldap_search($ds, "ou=employees,ou=people,dc=ncsu,dc=edu", "uid=" . $unity_id, array('uid', 'mail', 'ncsuPreferredGivenName', 'sn','title', 'ncsuWebSite', 'telephoneNumber', 'ncsuPrimaryRole', 'registeredAddress', 'givenName', 'ncsuNickname'));
	$entries = ldap_get_entries($ds, $sr);
	return ldap_formatter($entries);
}

function update_people($people) {

	foreach($people as $person):
		$person = $person[0];
		if(!person_exists($person['id'])):
			$post = array(
				'post_title' => $person['first_name'] . " " . $person['last_name'],
				'post_name' => $person['id'],
				'post_type' => 'person',
				'post_status' => 'publish',
		
			);
			$id = wp_insert_post($post);
			update_post_meta($id, 'uid', $person['id']);
			update_post_meta($id, 'first_name', $person['first_name']);
			update_post_meta($id, 'last_name', $person['last_name']);
			update_post_meta($id, 'first_name', $person['first_name']);
			update_post_meta($id, 'email', $person['email']);
			update_post_meta($id, 'phone', $person['phone']);
			update_post_meta($id, 'title', $person['title']);
			update_post_meta($id, 'website', $person['website']);
			update_post_meta($id, 'office', $person['office']);
			update_post_meta($id, 'auto_update', true);
			if($person['role']=='staff'):
				wp_set_object_terms($id, 'staff', 'subgroup');
			elseif($person['role']=='faculty'):
				wp_set_object_terms($id, 'faculty', 'subgroup');
			elseif($person['role']=='student'):
				wp_set_object_terms($id, 'student', 'subgroup');
			else:
				
			endif;
		elseif($id = person_auto_update($person['id'])):
			update_post_meta($id, 'first_name', $person['first_name']);
			update_post_meta($id, 'last_name', $person['last_name']);
			update_post_meta($id, 'first_name', $person['first_name']);
			update_post_meta($id, 'email', $person['email']);
			update_post_meta($id, 'phone', $person['phone']);
			update_post_meta($id, 'title', $person['title']);
			update_post_meta($id, 'website', $person['website']);
			update_post_meta($id, 'office', $person['office']);
		endif;
	endforeach;
}

function person_exists($unity_id) {
	$args = array(
		'post_type' => 'person',
		'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash'),
		'meta_key' => 'uid',
		'meta_value' => $unity_id,
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

function ldap_formatter($input) {
	$output = array();
	unset($input['count']);
	foreach($input as $entry):
		$name = '';
		if($entry['ncsunickname'][0]!=null):
			$name = $entry['ncsunickname'][0];
		elseif($entry['ncsupreferredgivenname'][0]!=null):
			$name = $entry['ncsupreferredgivenname'][0];
		else:
			$name = $entry['givenname'][0];
		endif;
		$office = null;
		if($entry['registeredaddress'][0]!=null):
			if(strpos($entry['registeredaddress'][0], "Box")!=0):
				$comma = strpos($entry['registeredaddress'][0], ",");
				$office = substr($entry['registeredaddress'][0], 0, $comma);
			endif;
		endif;
		$output[] = array(
			'id' => $entry['uid'][0],
			'email' => $entry['mail'][0],
			'role' => $entry['ncsuprimaryrole'][0],
			'title' => $entry['title'][0],
			'first_name' => $name,
			'last_name' => $entry['sn'][0],
			'phone' => $entry['telephonenumber'][0],
			'website' => $entry['ncsuwebsite'][0],
			'office' => $office,
		);
	endforeach;
	return $output;
}