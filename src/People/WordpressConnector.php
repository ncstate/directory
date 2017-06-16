<?php

namespace NCState\People;

use Exception;

class WordpressConnector
{
	
	public function __construct() {
		
	}
	
	public function update_person($person) {
		
	}
	
	public function get_person($unity_id) {
		
	}
	
	// Accepts Unity ID as string
	// Returns false if person doesn't exist in site directory; returns post ID otherwise
	private function exists($unity_id) {
	
		$posts = get_posts(array(
			'post_type' => 'person',
			'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash'),
			'meta_query' => array(
				array(
					'key' => 'uid',
					'value' => $unity_id,
					'compare' => '='
				),
			),
		));

		if (count($posts)>0) {
			return $posts[0]->ID;
		}

		return false;
	}
}