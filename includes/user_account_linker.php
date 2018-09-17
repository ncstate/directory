<?php

function user_account_link() {
	$args = array(
		'post_type' => 'person',
		'numberposts' => -1,
	);
	$people = get_posts($args);
	
	foreach($people as $person):
		$person_meta_unity = get_post_meta($person->ID, 'uid', true);
		if($person_meta_unity != $person->post_name) {
			$user_id = username_exists($person_meta_unity);
			if(!$user_id) {
				$params = array(
					'user_login' => $person_meta_unity,
					'user_email' => $person_meta_unity . "@ncsu.edu",
					'role' => 'ncstate_directory_user',
				);
				$user_id = wp_insert_user($params);
				update_user_meta($user_id, 'ncsu-multiauth-realm', 'wrap');
			}
			wp_update_post(array(
				'ID' => $person->ID,
				'post_author' => $user_id,
				'post_name' => $person_meta_unity,
			));
		}
	endforeach;
}
